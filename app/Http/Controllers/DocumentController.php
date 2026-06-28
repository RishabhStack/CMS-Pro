<?php

namespace App\Http\Controllers;

use App\Http\Requests\DocumentRequest;
use App\Models\Document;
use App\Models\Employee;
use App\Services\DocumentService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DocumentController extends BaseController
{
    public function __construct(
        protected DocumentService $documentService
    ) {
    }

    public function index()
    {
        try {
            $this->authorize('viewAny', Document::class);

            $companyId = $this->getCompanyId();
            $employees = Employee::byCompany($companyId)->with('user')->get();

            return $this->view('documents.index', compact('employees'));
        } catch (\Exception $e) {
            return $this->error('Failed to load page.', $e->getMessage());
        }
    }

    public function create()
    {
        try {
            $this->authorize('create', Document::class);
            $companyId = $this->getCompanyId();
            $employees = Employee::byCompany($companyId)->with('user')->get();

            return $this->view('documents.create', compact('employees'));
        } catch (\Exception $e) {
            return $this->error('Failed to load form.', $e->getMessage());
        }
    }

    public function list(Request $request)
    {
        try {
            $this->authorize('viewAny', Document::class);
            $companyId = $this->getCompanyId();

            $query = Document::byCompany($companyId)->with(['employee.user', 'creator']);

            if (auth()->user()->employee && !auth()->user()->hasRole(['Owner', 'Admin'])) {
                $query->where('employee_id', auth()->user()->employee->id);
            }

            if ($search = $request->input('search.value')) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('type', 'like', "%{$search}%");
                });
            }

            if ($request->filled('employee_id')) {
                $query->where('employee_id', $request->employee_id);
            }

            if ($request->filled('type')) {
                $query->where('type', $request->type);
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

    public function store(DocumentRequest $request)
    {
        try {
            $this->authorize('create', Document::class);

            $document = $this->documentService->uploadDocument([
                'company_id' => $this->getCompanyId(),
                'created_by' => auth()->id(),
                ...$request->validated(),
            ], $request->file('file'));

            return $this->created('Document uploaded successfully.', $document->load(['employee.user']));
        } catch (\Exception $e) {
            return $this->error('Failed to upload document.', $e->getMessage());
        }
    }

    public function show($id)
    {
        try {
            $companyId = $this->getCompanyId();
            $document = Document::byCompany($companyId)
                ->with(['employee.user', 'creator'])
                ->findOrFail($id);
            $this->authorize('view', $document);

            return $this->success('Document retrieved successfully.', $document);
        } catch (ModelNotFoundException $e) {
            return $this->error('Document not found.', null, 404);
        } catch (\Exception $e) {
            return $this->error('Failed to retrieve document.', $e->getMessage());
        }
    }

    public function download($id)
    {
        try {
            $companyId = $this->getCompanyId();
            $document = Document::byCompany($companyId)->findOrFail($id);
            $this->authorize('view', $document);

            if (!Storage::disk('public')->exists($document->file_path)) {
                return redirect()->back()->with('error', 'File not found.');
            }

            return Storage::disk('public')->download($document->file_path, $document->name);
        } catch (ModelNotFoundException $e) {
            return redirect()->back()->with('error', 'Document not found.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to download document.');
        }
    }

    public function destroy($id)
    {
        try {
            $document = Document::findOrFail($id);
            $this->authorize('delete', $document);

            if (Storage::disk('public')->exists($document->file_path)) {
                Storage::disk('public')->delete($document->file_path);
            }

            $this->documentService->destroy($document);

            return $this->deleted('Document deleted successfully.');
        } catch (ModelNotFoundException $e) {
            return $this->error('Document not found.', null, 404);
        } catch (\Exception $e) {
            return $this->error('Failed to delete document.', $e->getMessage());
        }
    }
}
