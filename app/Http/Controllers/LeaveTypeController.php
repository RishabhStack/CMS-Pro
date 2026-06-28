<?php

namespace App\Http\Controllers;

use App\Http\Requests\LeaveTypeRequest;
use App\Models\LeaveType;
use App\Services\LeaveTypeService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class LeaveTypeController extends BaseController
{
    public function __construct(
        protected LeaveTypeService $leaveTypeService
    ) {
    }

    public function create()
    {
        try {
            $this->authorize('create', LeaveType::class);
            return $this->view('leave-types.create');
        } catch (\Exception $e) {
            return $this->error('Failed to load form.', $e->getMessage());
        }
    }

    public function edit($id)
    {
        try {
            $leaveType = LeaveType::findOrFail($id);
            $this->authorize('update', $leaveType);
            return $this->view('leave-types.create', compact('leaveType'));
        } catch (ModelNotFoundException $e) {
            return $this->error('Leave type not found.', null, 404);
        } catch (\Exception $e) {
            return $this->error('Failed to load form.', $e->getMessage());
        }
    }

    public function index()
    {
        try {
            $this->authorize('viewAny', LeaveType::class);
            return $this->view('leave-types.index');
        } catch (\Exception $e) {
            return $this->error('Failed to load page.', $e->getMessage());
        }
    }

    public function list(Request $request)
    {
        try {
            $this->authorize('viewAny', LeaveType::class);
            $companyId = $this->getCompanyId();

            $query = LeaveType::byCompany($companyId);

            if ($search = $request->input('search.value')) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('slug', 'like', "%{$search}%");
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

    public function store(LeaveTypeRequest $request)
    {
        try {
            $this->authorize('create', LeaveType::class);

            $leaveType = $this->leaveTypeService->store([
                'company_id' => $this->getCompanyId(),
                'created_by' => auth()->id(),
                ...$request->validated(),
            ]);

            return $this->created('Leave type created successfully.', $leaveType);
        } catch (\Exception $e) {
            return $this->error('Failed to create leave type.', $e->getMessage());
        }
    }

    public function show($id)
    {
        try {
            $companyId = $this->getCompanyId();
            $leaveType = LeaveType::byCompany($companyId)->findOrFail($id);
            $this->authorize('view', $leaveType);

            return $this->success('Leave type retrieved successfully.', $leaveType);
        } catch (ModelNotFoundException $e) {
            return $this->error('Leave type not found.', null, 404);
        } catch (\Exception $e) {
            return $this->error('Failed to retrieve leave type.', $e->getMessage());
        }
    }

    public function update(LeaveTypeRequest $request, $id)
    {
        try {
            $leaveType = LeaveType::findOrFail($id);
            $this->authorize('update', $leaveType);

            $this->leaveTypeService->update($leaveType, $request->validated());

            return $this->updated('Leave type updated successfully.', $leaveType->fresh());
        } catch (ModelNotFoundException $e) {
            return $this->error('Leave type not found.', null, 404);
        } catch (\Exception $e) {
            return $this->error('Failed to update leave type.', $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $leaveType = LeaveType::findOrFail($id);
            $this->authorize('delete', $leaveType);

            $this->leaveTypeService->destroy($leaveType);

            return $this->deleted('Leave type deleted successfully.');
        } catch (ModelNotFoundException $e) {
            return $this->error('Leave type not found.', null, 404);
        } catch (\Exception $e) {
            return $this->error('Failed to delete leave type.', $e->getMessage());
        }
    }
}
