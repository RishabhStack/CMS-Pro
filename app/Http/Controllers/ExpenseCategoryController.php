<?php

namespace App\Http\Controllers;

use App\Models\ExpenseCategory;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class ExpenseCategoryController extends BaseController
{
    public function index()
    {
        try {
            $this->authorize('viewAny', ExpenseCategory::class);
            return $this->view('expense-categories.index');
        } catch (\Exception $e) {
            return $this->error('Failed to load page.', $e->getMessage());
        }
    }

    public function list(Request $request)
    {
        try {
            $this->authorize('viewAny', ExpenseCategory::class);
            $companyId = $this->getCompanyId();

            $query = ExpenseCategory::byCompany($companyId);

            if ($search = $request->input('search.value')) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%");
                });
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
            $this->authorize('create', ExpenseCategory::class);
            return $this->view('expense-categories.create');
        } catch (\Exception $e) {
            return $this->error('Failed to load form.', $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            $this->authorize('create', ExpenseCategory::class);

            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'max_amount' => 'nullable|numeric|min:0',
                'status' => 'required|string|in:active,inactive',
            ]);

            $category = ExpenseCategory::create([
                'company_id' => $this->getCompanyId(),
                ...$validated,
            ]);

            return $this->created('Expense category created successfully.', $category);
        } catch (\Exception $e) {
            return $this->error('Failed to create expense category.', $e->getMessage());
        }
    }

    public function show($id)
    {
        try {
            $companyId = $this->getCompanyId();
            $category = ExpenseCategory::byCompany($companyId)->findOrFail($id);
            $this->authorize('view', $category);

            return $this->success('Expense category retrieved successfully.', $category);
        } catch (ModelNotFoundException $e) {
            return $this->error('Expense category not found.', null, 404);
        } catch (\Exception $e) {
            return $this->error('Failed to retrieve expense category.', $e->getMessage());
        }
    }

    public function edit($id)
    {
        try {
            $category = ExpenseCategory::findOrFail($id);
            $this->authorize('update', $category);
            return $this->view('expense-categories.create', compact('category'));
        } catch (ModelNotFoundException $e) {
            return $this->error('Expense category not found.', null, 404);
        } catch (\Exception $e) {
            return $this->error('Failed to load form.', $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $category = ExpenseCategory::findOrFail($id);
            $this->authorize('update', $category);

            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'max_amount' => 'nullable|numeric|min:0',
                'status' => 'required|string|in:active,inactive',
            ]);

            $category->update($validated);

            return $this->updated('Expense category updated successfully.', $category->fresh());
        } catch (ModelNotFoundException $e) {
            return $this->error('Expense category not found.', null, 404);
        } catch (\Exception $e) {
            return $this->error('Failed to update expense category.', $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $category = ExpenseCategory::findOrFail($id);
            $this->authorize('delete', $category);

            $category->delete();

            return $this->deleted('Expense category deleted successfully.');
        } catch (ModelNotFoundException $e) {
            return $this->error('Expense category not found.', null, 404);
        } catch (\Exception $e) {
            return $this->error('Failed to delete expense category.', $e->getMessage());
        }
    }
}
