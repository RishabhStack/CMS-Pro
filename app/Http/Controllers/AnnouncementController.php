<?php

namespace App\Http\Controllers;

use App\Http\Requests\AnnouncementRequest;
use App\Models\Announcement;
use App\Services\AnnouncementService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class AnnouncementController extends BaseController
{
    public function __construct(
        protected AnnouncementService $announcementService
    ) {
    }

    public function create()
    {
        try {
            $this->authorize('create', Announcement::class);
            return $this->view('announcements.create');
        } catch (\Exception $e) {
            return $this->error('Failed to load form.', $e->getMessage());
        }
    }

    public function edit($id)
    {
        try {
            $announcement = Announcement::findOrFail($id);
            $this->authorize('update', $announcement);
            return $this->view('announcements.create', compact('announcement'));
        } catch (ModelNotFoundException $e) {
            return $this->error('Announcement not found.', null, 404);
        } catch (\Exception $e) {
            return $this->error('Failed to load form.', $e->getMessage());
        }
    }

    public function index()
    {
        try {
            $this->authorize('viewAny', Announcement::class);
            return $this->view('announcements.index');
        } catch (\Exception $e) {
            return $this->error('Failed to load page.', $e->getMessage());
        }
    }

    public function list(Request $request)
    {
        try {
            $this->authorize('viewAny', Announcement::class);
            $companyId = $this->getCompanyId();

            $query = Announcement::byCompany($companyId)->with(['creator']);

            if ($search = $request->input('search.value')) {
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                        ->orWhere('content', 'like', "%{$search}%");
                });
            }

            if ($request->filled('type')) {
                $query->where('type', $request->type);
            }

            if ($request->filled('priority')) {
                $query->where('priority', $request->priority);
            }

            if ($request->filled('status')) {
                $query->where('status', $request->status);
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

    public function store(AnnouncementRequest $request)
    {
        try {
            $this->authorize('create', Announcement::class);

            $announcement = $this->announcementService->store([
                'company_id' => $this->getCompanyId(),
                'created_by' => auth()->id(),
                ...$request->validated(),
            ]);

            return $this->created('Announcement created successfully.', $announcement);
        } catch (\Exception $e) {
            return $this->error('Failed to create announcement.', $e->getMessage());
        }
    }

    public function show($id)
    {
        try {
            $companyId = $this->getCompanyId();
            $announcement = Announcement::byCompany($companyId)->with(['creator'])->findOrFail($id);
            $this->authorize('view', $announcement);

            return $this->view('announcements.show', compact('announcement'));
        } catch (ModelNotFoundException $e) {
            return $this->error('Announcement not found.', null, 404);
        } catch (\Exception $e) {
            return $this->error('Failed to load announcement.', $e->getMessage());
        }
    }

    public function update(AnnouncementRequest $request, $id)
    {
        try {
            $announcement = Announcement::findOrFail($id);
            $this->authorize('update', $announcement);

            $this->announcementService->update($announcement, $request->validated());

            return $this->updated('Announcement updated successfully.', $announcement->fresh());
        } catch (ModelNotFoundException $e) {
            return $this->error('Announcement not found.', null, 404);
        } catch (\Exception $e) {
            return $this->error('Failed to update announcement.', $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $announcement = Announcement::findOrFail($id);
            $this->authorize('delete', $announcement);

            $this->announcementService->destroy($announcement);

            return $this->deleted('Announcement deleted successfully.');
        } catch (ModelNotFoundException $e) {
            return $this->error('Announcement not found.', null, 404);
        } catch (\Exception $e) {
            return $this->error('Failed to delete announcement.', $e->getMessage());
        }
    }
}
