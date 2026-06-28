<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProjectRequest;
use App\Models\Project;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProjectController extends BaseController
{
    public function create()
    {
        try {
            $this->authorize('create', Project::class);
            return $this->view('projects.create');
        } catch (\Exception $e) {
            return $this->error('Failed to load form.', $e->getMessage());
        }
    }

    public function edit($id)
    {
        try {
            $project = Project::findOrFail($id);
            $this->authorize('update', $project);
            return $this->view('projects.create', compact('project'));
        } catch (ModelNotFoundException $e) {
            return $this->error('Project not found.', null, 404);
        } catch (\Exception $e) {
            return $this->error('Failed to load form.', $e->getMessage());
        }
    }

    public function index()
    {
        try {
            $this->authorize('viewAny', Project::class);
            return $this->view('projects.index');
        } catch (\Exception $e) {
            return $this->error('Failed to load page.', $e->getMessage());
        }
    }

    public function list(Request $request)
    {
        try {
            $this->authorize('viewAny', Project::class);
            $companyId = $this->getCompanyId();

            $query = Project::byCompany($companyId);

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

    public function store(ProjectRequest $request)
    {
        try {
            $this->authorize('create', Project::class);

            $project = Project::create([
                'company_id' => $this->getCompanyId(),
                ...$request->validated(),
            ]);

            if (!$project->slug) {
                $project->slug = Str::slug($project->name);
                $project->save();
            }

            return $this->created('Project created successfully.', $project);
        } catch (\Exception $e) {
            return $this->error('Failed to create project.', $e->getMessage());
        }
    }

    public function show($id)
    {
        try {
            $companyId = $this->getCompanyId();
            $project = Project::byCompany($companyId)->findOrFail($id);
            $this->authorize('view', $project);

            return $this->success('Project retrieved successfully.', $project);
        } catch (ModelNotFoundException $e) {
            return $this->error('Project not found.', null, 404);
        } catch (\Exception $e) {
            return $this->error('Failed to retrieve project.', $e->getMessage());
        }
    }

    public function update(ProjectRequest $request, $id)
    {
        try {
            $project = Project::findOrFail($id);
            $this->authorize('update', $project);

            $data = $request->validated();
            if (empty($data['slug']) && isset($data['name'])) {
                $data['slug'] = Str::slug($data['name']);
            }

            $project->update($data);

            return $this->updated('Project updated successfully.', $project->fresh());
        } catch (ModelNotFoundException $e) {
            return $this->error('Project not found.', null, 404);
        } catch (\Exception $e) {
            return $this->error('Failed to update project.', $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $project = Project::findOrFail($id);
            $this->authorize('delete', $project);

            $project->delete();

            return $this->deleted('Project deleted successfully.');
        } catch (ModelNotFoundException $e) {
            return $this->error('Project not found.', null, 404);
        } catch (\Exception $e) {
            return $this->error('Failed to delete project.', $e->getMessage());
        }
    }
}
