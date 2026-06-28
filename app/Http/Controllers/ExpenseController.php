<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\ExpenseCategory;
use App\Models\Employee;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ExpenseController extends BaseController
{
    public function index()
    {
        try {
            $this->authorize('viewAny', Expense::class);
            $companyId = $this->getCompanyId();

            $categories = ExpenseCategory::byCompany($companyId)->where('status', 'active')->get();
            $employees = Employee::byCompany($companyId)->with('user')->get();

            return $this->view('expenses.index', compact('categories', 'employees'));
        } catch (\Exception $e) {
            return $this->error('Failed to load page.', $e->getMessage());
        }
    }

    public function list(Request $request)
    {
        try {
            $this->authorize('viewAny', Expense::class);
            $companyId = $this->getCompanyId();

            $query = Expense::byCompany($companyId)
                ->with(['employee.user', 'category', 'approver', 'creator']);

            if (!auth()->user()->hasRole(['Owner', 'Admin']) && auth()->user()->employee) {
                $query->where('employee_id', auth()->user()->employee->id);
            }

            if ($search = $request->input('search.value')) {
                $query->where(function ($q) use ($search) {
                    $q->where('description', 'like', "%{$search}%")
                        ->orWhereHas('employee.user', function ($uq) use ($search) {
                            $uq->where('first_name', 'like', "%{$search}%")
                                ->orWhere('last_name', 'like', "%{$search}%");
                        });
                });
            }

            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            if ($request->filled('category_id')) {
                $query->where('category_id', $request->category_id);
            }

            if ($request->filled('employee_id')) {
                $query->where('employee_id', $request->employee_id);
            }

            if ($request->filled('date_from')) {
                $query->where('expense_date', '>=', $request->date_from);
            }

            if ($request->filled('date_to')) {
                $query->where('expense_date', '<=', $request->date_to);
            }

            return $this->datatableResponse($query, $request);
        } catch (\Exception $e) {
            return response()->json([
                'draw' => (int) $request->input('draw', 0),
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => [],
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function create()
    {
        try {
            $this->authorize('create', Expense::class);
            $companyId = $this->getCompanyId();
            $categories = ExpenseCategory::byCompany($companyId)->where('status', 'active')->get();
            return $this->view('expenses.create', compact('categories'));
        } catch (\Exception $e) {
            return $this->error('Failed to load form.', $e->getMessage());
        }
    }

    public function edit($id)
    {
        try {
            $expense = Expense::findOrFail($id);
            $this->authorize('update', $expense);
            $companyId = $this->getCompanyId();
            $categories = ExpenseCategory::byCompany($companyId)->where('status', 'active')->get();
            return $this->view('expenses.create', compact('expense', 'categories'));
        } catch (ModelNotFoundException $e) {
            return $this->error('Expense not found.', null, 404);
        } catch (\Exception $e) {
            return $this->error('Failed to load form.', $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            $this->authorize('create', Expense::class);

            $validated = $request->validate([
                'category_id' => 'nullable|exists:expense_categories,id',
                'expense_date' => 'required|date',
                'amount' => 'required|numeric|min:0',
                'description' => 'required|string',
                'notes' => 'nullable|string',
                'receipt' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:10240',
            ]);

            $employee = auth()->user()->employee;
            if (!$employee) {
                return $this->error('No employee record found.', null, 404);
            }

            $data = [
                'company_id' => $this->getCompanyId(),
                'employee_id' => $employee->id,
                'created_by' => auth()->id(),
                'status' => 'pending',
                ...$validated,
            ];
            unset($data['receipt']);

            if ($request->hasFile('receipt')) {
                $data['receipt_path'] = $request->file('receipt')->store('expenses/receipts', 'public');
                $data['receipt_original_name'] = $request->file('receipt')->getClientOriginalName();
            }

            $expense = Expense::create($data);

            return $this->created('Expense submitted successfully.', $expense->load(['employee.user', 'category']));
        } catch (\Exception $e) {
            return $this->error('Failed to submit expense.', $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $expense = Expense::findOrFail($id);
            $this->authorize('update', $expense);

            $validated = $request->validate([
                'category_id' => 'nullable|exists:expense_categories,id',
                'expense_date' => 'required|date',
                'amount' => 'required|numeric|min:0',
                'description' => 'required|string',
                'notes' => 'nullable|string',
                'receipt' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:10240',
            ]);

            $data = $validated;
            unset($data['receipt']);

            if ($request->hasFile('receipt')) {
                if ($expense->receipt_path) {
                    Storage::disk('public')->delete($expense->receipt_path);
                }
                $data['receipt_path'] = $request->file('receipt')->store('expenses/receipts', 'public');
                $data['receipt_original_name'] = $request->file('receipt')->getClientOriginalName();
            }

            $expense->update($data);

            return $this->updated('Expense updated successfully.', $expense->fresh()->load(['employee.user', 'category']));
        } catch (ModelNotFoundException $e) {
            return $this->error('Expense not found.', null, 404);
        } catch (\Exception $e) {
            return $this->error('Failed to update expense.', $e->getMessage());
        }
    }

    public function show($id)
    {
        try {
            $companyId = $this->getCompanyId();
            $expense = Expense::byCompany($companyId)
                ->with(['employee.user', 'category', 'approver'])
                ->findOrFail($id);
            $this->authorize('view', $expense);

            return $this->view('expenses.show', compact('expense'));
        } catch (ModelNotFoundException $e) {
            return $this->error('Expense not found.', null, 404);
        } catch (\Exception $e) {
            return $this->error('Failed to load expense details.', $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $expense = Expense::findOrFail($id);
            $this->authorize('delete', $expense);

            if ($expense->receipt_path) {
                Storage::disk('public')->delete($expense->receipt_path);
            }

            $expense->delete();

            return $this->deleted('Expense deleted successfully.');
        } catch (ModelNotFoundException $e) {
            return $this->error('Expense not found.', null, 404);
        } catch (\Exception $e) {
            return $this->error('Failed to delete expense.', $e->getMessage());
        }
    }

    public function approve($id)
    {
        try {
            $expense = Expense::findOrFail($id);
            $this->authorize('approve', $expense);

            $expense->update([
                'status' => 'approved',
                'approved_by' => auth()->id(),
                'approved_at' => now(),
            ]);

            return $this->success('Expense approved successfully.', $expense->fresh()->load(['employee.user', 'category']));
        } catch (ModelNotFoundException $e) {
            return $this->error('Expense not found.', null, 404);
        } catch (\Exception $e) {
            return $this->error('Failed to approve expense.', $e->getMessage());
        }
    }

    public function reject(Request $request, $id)
    {
        try {
            $expense = Expense::findOrFail($id);
            $this->authorize('reject', $expense);

            $request->validate(['rejection_reason' => 'nullable|string|max:500']);

            $expense->update([
                'status' => 'rejected',
                'approved_by' => auth()->id(),
                'approved_at' => now(),
                'rejection_reason' => $request->rejection_reason,
            ]);

            return $this->success('Expense rejected successfully.', $expense->fresh()->load(['employee.user', 'category']));
        } catch (ModelNotFoundException $e) {
            return $this->error('Expense not found.', null, 404);
        } catch (\Exception $e) {
            return $this->error('Failed to reject expense.', $e->getMessage());
        }
    }

    public function pay($id)
    {
        try {
            $expense = Expense::findOrFail($id);
            $this->authorize('pay', $expense);

            $expense->update([
                'status' => 'paid',
                'paid_at' => now(),
            ]);

            return $this->success('Expense marked as paid successfully.', $expense->fresh()->load(['employee.user', 'category']));
        } catch (ModelNotFoundException $e) {
            return $this->error('Expense not found.', null, 404);
        } catch (\Exception $e) {
            return $this->error('Failed to mark expense as paid.', $e->getMessage());
        }
    }
}
