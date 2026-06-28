<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\TravelRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class TravelRequestController extends BaseController
{
    public function create()
    {
        try {
            $this->authorize('create', TravelRequest::class);
            $companyId = $this->getCompanyId();
            $employees = Employee::byCompany($companyId)->with('user')->get();

            return $this->view('travel-requests.create', compact('employees'));
        } catch (\Exception $e) {
            return $this->error('Failed to load form.', $e->getMessage());
        }
    }

    public function index()
    {
        try {
            $this->authorize('viewAny', TravelRequest::class);
            $companyId = $this->getCompanyId();

            $employees = Employee::byCompany($companyId)->with('user')->get();
            $travelModes = ['flight', 'train', 'bus', 'cab', 'own'];

            return $this->view('travel-requests.index', compact('employees', 'travelModes'));
        } catch (\Exception $e) {
            return $this->error('Failed to load page.', $e->getMessage());
        }
    }

    public function list(Request $request)
    {
        try {
            $this->authorize('viewAny', TravelRequest::class);
            $companyId = $this->getCompanyId();

            $query = TravelRequest::byCompany($companyId)
                ->with(['employee.user', 'approver']);

            if (auth()->user()->employee && !auth()->user()->hasRole(['Owner', 'Admin'])) {
                $query->where('employee_id', auth()->user()->employee->id);
            }

            if ($search = $request->input('search.value')) {
                $query->where(function ($q) use ($search) {
                    $q->where('destination', 'like', "%{$search}%")
                        ->orWhere('purpose', 'like', "%{$search}%")
                        ->orWhereHas('employee.user', function ($uq) use ($search) {
                            $uq->where('first_name', 'like', "%{$search}%")
                                ->orWhere('last_name', 'like', "%{$search}%");
                        });
                });
            }

            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            if ($request->filled('employee_id')) {
                $query->where('employee_id', $request->employee_id);
            }

            if ($request->filled('mode')) {
                $query->where('mode', $request->mode);
            }

            if ($request->filled('date_from')) {
                $query->where('from_date', '>=', $request->date_from);
            }

            if ($request->filled('date_to')) {
                $query->where('to_date', '<=', $request->date_to);
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

    public function store(Request $request)
    {
        try {
            $this->authorize('create', TravelRequest::class);

            $employee = auth()->user()->employee;
            if (!$employee && !auth()->user()->hasRole(['Owner', 'Admin'])) {
                return $this->error('No employee record found.', null, 404);
            }

            $validated = $request->validate([
                'employee_id' => auth()->user()->hasRole(['Owner', 'Admin']) ? 'required|exists:employees,id' : 'nullable',
                'from_date' => 'required|date',
                'to_date' => 'required|date|after_or_equal:from_date',
                'destination' => 'required|string|max:255',
                'purpose' => 'required|string',
                'mode' => 'required|in:flight,train,bus,cab,own',
                'estimated_cost' => 'nullable|numeric|min:0',
                'notes' => 'nullable|string',
            ]);

            $travelRequest = TravelRequest::create([
                'company_id' => $this->getCompanyId(),
                'employee_id' => $validated['employee_id'] ?? $employee->id,
                'created_by' => auth()->id(),
                'status' => 'draft',
                ...$validated,
            ]);

            return $this->created('Travel request created successfully.', $travelRequest->load(['employee.user']));
        } catch (\Exception $e) {
            return $this->error('Failed to create travel request.', $e->getMessage());
        }
    }

    public function show($id)
    {
        try {
            $companyId = $this->getCompanyId();
            $travelRequest = TravelRequest::byCompany($companyId)
                ->with(['employee.user', 'approver', 'itineraries', 'creator'])
                ->findOrFail($id);
            $this->authorize('view', $travelRequest);

            return $this->view('travel-requests.show', compact('travelRequest'));
        } catch (ModelNotFoundException $e) {
            return $this->error('Travel request not found.', null, 404);
        } catch (\Exception $e) {
            return $this->error('Failed to load travel request.', $e->getMessage());
        }
    }

    public function edit($id)
    {
        try {
            $companyId = $this->getCompanyId();
            $travelRequest = TravelRequest::byCompany($companyId)->with(['employee.user'])->findOrFail($id);
            $this->authorize('update', $travelRequest);

            $employees = Employee::byCompany($companyId)->with('user')->get();

            return $this->view('travel-requests.create', compact('travelRequest', 'employees'));
        } catch (ModelNotFoundException $e) {
            return $this->error('Travel request not found.', null, 404);
        } catch (\Exception $e) {
            return $this->error('Failed to load form.', $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $travelRequest = TravelRequest::findOrFail($id);
            $this->authorize('update', $travelRequest);

            $validated = $request->validate([
                'employee_id' => 'required|exists:employees,id',
                'from_date' => 'required|date',
                'to_date' => 'required|date|after_or_equal:from_date',
                'destination' => 'required|string|max:255',
                'purpose' => 'required|string',
                'mode' => 'required|in:flight,train,bus,cab,own',
                'estimated_cost' => 'nullable|numeric|min:0',
                'actual_cost' => 'nullable|numeric|min:0',
                'notes' => 'nullable|string',
                'status' => 'nullable|in:draft,pending,approved,rejected,cancelled,settled',
            ]);

            $travelRequest->update($validated);

            return $this->updated('Travel request updated successfully.', $travelRequest->load(['employee.user']));
        } catch (ModelNotFoundException $e) {
            return $this->error('Travel request not found.', null, 404);
        } catch (\Exception $e) {
            return $this->error('Failed to update travel request.', $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $travelRequest = TravelRequest::findOrFail($id);
            $this->authorize('delete', $travelRequest);

            $travelRequest->delete();

            return $this->deleted('Travel request deleted successfully.');
        } catch (ModelNotFoundException $e) {
            return $this->error('Travel request not found.', null, 404);
        } catch (\Exception $e) {
            return $this->error('Failed to delete travel request.', $e->getMessage());
        }
    }

    public function approve($id)
    {
        try {
            $travelRequest = TravelRequest::findOrFail($id);
            $this->authorize('approve', $travelRequest);

            if (!in_array($travelRequest->status, ['draft', 'pending'])) {
                return $this->error('Only draft or pending requests can be approved.');
            }

            $travelRequest->update([
                'status' => 'approved',
                'approved_by' => auth()->id(),
                'approved_at' => now(),
            ]);

            return $this->success('Travel request approved successfully.', $travelRequest->fresh()->load(['employee.user', 'approver']));
        } catch (ModelNotFoundException $e) {
            return $this->error('Travel request not found.', null, 404);
        } catch (\Exception $e) {
            return $this->error('Failed to approve travel request.', $e->getMessage());
        }
    }

    public function reject(Request $request, $id)
    {
        try {
            $travelRequest = TravelRequest::findOrFail($id);
            $this->authorize('approve', $travelRequest);

            if (!in_array($travelRequest->status, ['draft', 'pending'])) {
                return $this->error('Only draft or pending requests can be rejected.');
            }

            $validated = $request->validate([
                'notes' => 'nullable|string|max:1000',
            ]);

            $travelRequest->update([
                'status' => 'rejected',
                'approved_by' => auth()->id(),
                'approved_at' => now(),
                'notes' => $validated['notes'] ?? $travelRequest->notes,
            ]);

            return $this->success('Travel request rejected.', $travelRequest->fresh()->load(['employee.user', 'approver']));
        } catch (ModelNotFoundException $e) {
            return $this->error('Travel request not found.', null, 404);
        } catch (\Exception $e) {
            return $this->error('Failed to reject travel request.', $e->getMessage());
        }
    }

    public function submit($id)
    {
        try {
            $travelRequest = TravelRequest::findOrFail($id);
            $this->authorize('update', $travelRequest);

            if ($travelRequest->status !== 'draft') {
                return $this->error('Only draft requests can be submitted.');
            }

            $travelRequest->update(['status' => 'pending']);

            return $this->success('Travel request submitted for approval.', $travelRequest->fresh()->load(['employee.user']));
        } catch (ModelNotFoundException $e) {
            return $this->error('Travel request not found.', null, 404);
        } catch (\Exception $e) {
            return $this->error('Failed to submit travel request.', $e->getMessage());
        }
    }
}
