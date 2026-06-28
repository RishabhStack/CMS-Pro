<?php

namespace App\Http\Controllers;

use App\Http\Requests\CompanyRequest;
use App\Models\Company;
use App\Services\CompanyService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class CompanyController extends BaseController
{
    public function __construct(
        protected CompanyService $companyService
    ) {
    }

    public function index()
    {
        try {
            $this->authorize('viewAny', Company::class);
            $company = Company::find(auth()->user()->company_id);
            return $this->view('company.index', compact('company'));
        } catch (\Exception $e) {
            return $this->error('Failed to load page.', $e->getMessage());
        }
    }

    public function list(Request $request)
    {
        try {
            $this->authorize('viewAny', Company::class);

            $query = Company::with(['creator']);

            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            }

            $sortField = $request->input('sort_field', 'created_at');
            $sortDir = $request->input('sort_dir', 'desc');
            $query->orderBy($sortField, $sortDir);

            $perPage = $request->input('per_page', 25);
            $data = $query->paginate($perPage);

            return response()->json([
                'success' => true,
                'data' => $data->items(),
                'meta' => [
                    'current_page' => $data->currentPage(),
                    'last_page' => $data->lastPage(),
                    'per_page' => $data->perPage(),
                    'total' => $data->total(),
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch companies.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function store(CompanyRequest $request)
    {
        try {
            $this->authorize('create', Company::class);

            $company = $this->companyService->store($request->validated());

            return $this->created('Company created successfully.', $company);
        } catch (\Exception $e) {
            return $this->error('Failed to create company.', $e->getMessage());
        }
    }

    public function show($id)
    {
        try {
            $company = Company::with(['creator'])->findOrFail($id);
            $this->authorize('view', $company);

            return $this->success('Company retrieved successfully.', $company);
        } catch (ModelNotFoundException $e) {
            return $this->error('Company not found.', null, 404);
        } catch (\Exception $e) {
            return $this->error('Failed to retrieve company.', $e->getMessage());
        }
    }

    public function update(CompanyRequest $request, $id)
    {
        try {
            $company = Company::findOrFail($id);
            $this->authorize('update', $company);

            $this->companyService->update($company, $request->validated());

            return $this->updated('Company updated successfully.', $company->fresh());
        } catch (ModelNotFoundException $e) {
            return $this->error('Company not found.', null, 404);
        } catch (\Exception $e) {
            return $this->error('Failed to update company.', $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $company = Company::findOrFail($id);
            $this->authorize('delete', $company);

            $this->companyService->destroy($company);

            return $this->deleted('Company deleted successfully.');
        } catch (ModelNotFoundException $e) {
            return $this->error('Company not found.', null, 404);
        } catch (\Exception $e) {
            return $this->error('Failed to delete company.', $e->getMessage());
        }
    }
}
