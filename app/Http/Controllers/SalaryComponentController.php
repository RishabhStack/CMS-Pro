<?php

namespace App\Http\Controllers;

use App\Http\Requests\SalaryComponentRequest;
use App\Models\SalaryComponent;
use App\Services\SalaryComponentService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class SalaryComponentController extends BaseController
{
    public function __construct(
        protected SalaryComponentService $salaryComponentService
    ) {
    }

    public function create()
    {
        try {
            $this->authorize('create', SalaryComponent::class);
            return $this->view('salary-components.create');
        } catch (\Exception $e) {
            return $this->error('Failed to load form.', $e->getMessage());
        }
    }

    public function edit($id)
    {
        try {
            $component = SalaryComponent::findOrFail($id);
            $this->authorize('update', $component);
            return $this->view('salary-components.create', compact('component'));
        } catch (ModelNotFoundException $e) {
            return $this->error('Salary component not found.', null, 404);
        } catch (\Exception $e) {
            return $this->error('Failed to load form.', $e->getMessage());
        }
    }

    public function index()
    {
        try {
            $this->authorize('viewAny', SalaryComponent::class);
            return $this->view('salary-components.index');
        } catch (\Exception $e) {
            return $this->error('Failed to load page.', $e->getMessage());
        }
    }

    public function list(Request $request)
    {
        try {
            $this->authorize('viewAny', SalaryComponent::class);
            $companyId = $this->getCompanyId();

            $query = SalaryComponent::byCompany($companyId);

            if ($search = $request->input('search.value')) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('slug', 'like', "%{$search}%");
                });
            }

            if ($request->filled('type')) {
                $query->where('type', $request->type);
            }

            if ($request->filled('value_type')) {
                $query->where('value_type', $request->value_type);
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

    public function store(SalaryComponentRequest $request)
    {
        try {
            $this->authorize('create', SalaryComponent::class);

            $component = $this->salaryComponentService->store([
                'company_id' => $this->getCompanyId(),
                ...$request->validated(),
            ]);

            return $this->created('Salary component created successfully.', $component);
        } catch (\Exception $e) {
            return $this->error('Failed to create salary component.', $e->getMessage());
        }
    }

    public function show($id)
    {
        try {
            $companyId = $this->getCompanyId();
            $component = SalaryComponent::byCompany($companyId)->findOrFail($id);
            $this->authorize('view', $component);

            return $this->success('Salary component retrieved successfully.', $component);
        } catch (ModelNotFoundException $e) {
            return $this->error('Salary component not found.', null, 404);
        } catch (\Exception $e) {
            return $this->error('Failed to retrieve salary component.', $e->getMessage());
        }
    }

    public function update(SalaryComponentRequest $request, $id)
    {
        try {
            $component = SalaryComponent::findOrFail($id);
            $this->authorize('update', $component);

            $this->salaryComponentService->update($component, $request->validated());

            return $this->updated('Salary component updated successfully.', $component->fresh());
        } catch (ModelNotFoundException $e) {
            return $this->error('Salary component not found.', null, 404);
        } catch (\Exception $e) {
            return $this->error('Failed to update salary component.', $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $component = SalaryComponent::findOrFail($id);
            $this->authorize('delete', $component);

            $this->salaryComponentService->destroy($component);

            return $this->deleted('Salary component deleted successfully.');
        } catch (ModelNotFoundException $e) {
            return $this->error('Salary component not found.', null, 404);
        } catch (\Exception $e) {
            return $this->error('Failed to delete salary component.', $e->getMessage());
        }
    }
}
