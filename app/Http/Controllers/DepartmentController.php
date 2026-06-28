<?php

namespace App\Http\Controllers;

use App\Http\Requests\DepartmentRequest;
use App\Models\Department;
use App\Services\DepartmentService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class DepartmentController extends BaseController
{
    public function __construct(
        protected DepartmentService $departmentService
    ) {
    }

    public function create()
    {
        try {
            $this->authorize('create', Department::class);
            return $this->view('departments.create');
        } catch (\Exception $e) {
            return $this->error('Failed to load form.', $e->getMessage());
        }
    }

    public function edit($id)
    {
        try {
            $department = Department::findOrFail($id);
            $this->authorize('update', $department);
            return $this->view('departments.create', compact('department'));
        } catch (ModelNotFoundException $e) {
            return $this->error('Department not found.', null, 404);
        } catch (\Exception $e) {
            return $this->error('Failed to load form.', $e->getMessage());
        }
    }

    public function index()
    {
        try {
            $this->authorize('viewAny', Department::class);
            return $this->view('departments.index');
        } catch (\Exception $e) {
            return $this->error('Failed to load page.', $e->getMessage());
        }
    }

    public function list(Request $request)
    {
        try {
            $this->authorize('viewAny', Department::class);
            $companyId = $this->getCompanyId();

            $query = Department::byCompany($companyId)->with(['manager'])->withCount('employees');

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

    public function store(DepartmentRequest $request)
    {
        try {
            $this->authorize('create', Department::class);

            $department = $this->departmentService->store([
                'company_id' => $this->getCompanyId(),
                ...$request->validated(),
            ]);

            return $this->created('Department created successfully.', $department);
        } catch (\Exception $e) {
            return $this->error('Failed to create department.', $e->getMessage());
        }
    }

    public function show($id)
    {
        try {
            $companyId = $this->getCompanyId();
            $department = Department::with(['manager', 'designations', 'employees'])
                ->findOrFail($id);
            $this->authorize('view', $department);

            return $this->view('departments.show', compact('department'));
        } catch (ModelNotFoundException $e) {
            return $this->error('Department not found.', null, 404);
        } catch (\Exception $e) {
            return $this->error('Failed to load department details.', $e->getMessage());
        }
    }

    public function update(DepartmentRequest $request, $id)
    {
        try {
            $department = Department::findOrFail($id);
            $this->authorize('update', $department);

            $this->departmentService->update($department, $request->validated());

            return $this->updated('Department updated successfully.', $department->fresh());
        } catch (ModelNotFoundException $e) {
            return $this->error('Department not found.', null, 404);
        } catch (\Exception $e) {
            return $this->error('Failed to update department.', $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $department = Department::findOrFail($id);
            $this->authorize('delete', $department);

            $this->departmentService->destroy($department);

            return $this->deleted('Department deleted successfully.');
        } catch (ModelNotFoundException $e) {
            return $this->error('Department not found.', null, 404);
        } catch (\Exception $e) {
            return $this->error('Failed to delete department.', $e->getMessage());
        }
    }
}
