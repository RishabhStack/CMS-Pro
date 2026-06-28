<?php

namespace App\Http\Controllers;

use App\Http\Requests\DesignationRequest;
use App\Models\Department;
use App\Models\Designation;
use App\Services\DesignationService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class DesignationController extends BaseController
{
    public function __construct(
        protected DesignationService $designationService
    ) {
    }

    public function create()
    {
        try {
            $this->authorize('create', Designation::class);
            $departments = Department::byCompany($this->getCompanyId())->active()->get();
            return $this->view('designations.create', compact('departments'));
        } catch (\Exception $e) {
            return $this->error('Failed to load form.', $e->getMessage());
        }
    }

    public function edit($id)
    {
        try {
            $designation = Designation::findOrFail($id);
            $this->authorize('update', $designation);
            $departments = Department::byCompany($this->getCompanyId())->active()->get();
            return $this->view('designations.create', compact('designation', 'departments'));
        } catch (ModelNotFoundException $e) {
            return $this->error('Designation not found.', null, 404);
        } catch (\Exception $e) {
            return $this->error('Failed to load form.', $e->getMessage());
        }
    }

    public function index()
    {
        try {
            $this->authorize('viewAny', Designation::class);
            return $this->view('designations.index');
        } catch (\Exception $e) {
            return $this->error('Failed to load page.', $e->getMessage());
        }
    }

    public function list(Request $request)
    {
        try {
            $this->authorize('viewAny', Designation::class);
            $companyId = $this->getCompanyId();

            $query = Designation::byCompany($companyId)->with(['department'])->withCount('employees');

            if ($search = $request->input('search.value')) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('slug', 'like', "%{$search}%");
                });
            }

            if ($request->filled('department_id')) {
                $query->where('department_id', $request->department_id);
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

    public function store(DesignationRequest $request)
    {
        try {
            $this->authorize('create', Designation::class);

            $designation = $this->designationService->store([
                'company_id' => $this->getCompanyId(),
                ...$request->validated(),
            ]);

            return $this->created('Designation created successfully.', $designation);
        } catch (\Exception $e) {
            return $this->error('Failed to create designation.', $e->getMessage());
        }
    }

    public function show($id)
    {
        try {
            $designation = Designation::with(['department', 'employees'])->findOrFail($id);
            $this->authorize('view', $designation);

            return $this->view('designations.show', compact('designation'));
        } catch (ModelNotFoundException $e) {
            return $this->error('Designation not found.', null, 404);
        } catch (\Exception $e) {
            return $this->error('Failed to load designation details.', $e->getMessage());
        }
    }

    public function update(DesignationRequest $request, $id)
    {
        try {
            $designation = Designation::findOrFail($id);
            $this->authorize('update', $designation);

            $this->designationService->update($designation, $request->validated());

            return $this->updated('Designation updated successfully.', $designation->fresh());
        } catch (ModelNotFoundException $e) {
            return $this->error('Designation not found.', null, 404);
        } catch (\Exception $e) {
            return $this->error('Failed to update designation.', $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $designation = Designation::findOrFail($id);
            $this->authorize('delete', $designation);

            $this->designationService->destroy($designation);

            return $this->deleted('Designation deleted successfully.');
        } catch (ModelNotFoundException $e) {
            return $this->error('Designation not found.', null, 404);
        } catch (\Exception $e) {
            return $this->error('Failed to delete designation.', $e->getMessage());
        }
    }
}
