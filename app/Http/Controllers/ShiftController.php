<?php

namespace App\Http\Controllers;

use App\Http\Requests\ShiftRequest;
use App\Models\Shift;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ShiftController extends BaseController
{
    public function create()
    {
        try {
            $this->authorize('create', Shift::class);
            return $this->view('shifts.create');
        } catch (\Exception $e) {
            return $this->error('Failed to load form.', $e->getMessage());
        }
    }

    public function edit($id)
    {
        try {
            $shift = Shift::findOrFail($id);
            $this->authorize('update', $shift);
            return $this->view('shifts.create', compact('shift'));
        } catch (ModelNotFoundException $e) {
            return $this->error('Shift not found.', null, 404);
        } catch (\Exception $e) {
            return $this->error('Failed to load form.', $e->getMessage());
        }
    }

    public function index()
    {
        try {
            $this->authorize('viewAny', Shift::class);
            return $this->view('shifts.index');
        } catch (\Exception $e) {
            return $this->error('Failed to load page.', $e->getMessage());
        }
    }

    public function list(Request $request)
    {
        try {
            $this->authorize('viewAny', Shift::class);
            $companyId = $this->getCompanyId();

            $query = Shift::byCompany($companyId);

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

    public function store(ShiftRequest $request)
    {
        try {
            $this->authorize('create', Shift::class);

            $shift = Shift::create([
                'company_id' => $this->getCompanyId(),
                ...$request->validated(),
            ]);

            if (!$shift->slug) {
                $shift->slug = Str::slug($shift->name);
                $shift->save();
            }

            return $this->created('Shift created successfully.', $shift);
        } catch (\Exception $e) {
            return $this->error('Failed to create shift.', $e->getMessage());
        }
    }

    public function show($id)
    {
        try {
            $companyId = $this->getCompanyId();
            $shift = Shift::byCompany($companyId)->findOrFail($id);
            $this->authorize('view', $shift);

            return $this->success('Shift retrieved successfully.', $shift);
        } catch (ModelNotFoundException $e) {
            return $this->error('Shift not found.', null, 404);
        } catch (\Exception $e) {
            return $this->error('Failed to retrieve shift.', $e->getMessage());
        }
    }

    public function update(ShiftRequest $request, $id)
    {
        try {
            $shift = Shift::findOrFail($id);
            $this->authorize('update', $shift);

            $data = $request->validated();
            if (empty($data['slug']) && isset($data['name'])) {
                $data['slug'] = Str::slug($data['name']);
            }

            $shift->update($data);

            return $this->updated('Shift updated successfully.', $shift->fresh());
        } catch (ModelNotFoundException $e) {
            return $this->error('Shift not found.', null, 404);
        } catch (\Exception $e) {
            return $this->error('Failed to update shift.', $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $shift = Shift::findOrFail($id);
            $this->authorize('delete', $shift);

            $shift->delete();

            return $this->deleted('Shift deleted successfully.');
        } catch (ModelNotFoundException $e) {
            return $this->error('Shift not found.', null, 404);
        } catch (\Exception $e) {
            return $this->error('Failed to delete shift.', $e->getMessage());
        }
    }
}
