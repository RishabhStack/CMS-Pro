<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\AssetAssignment;
use App\Models\Employee;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AssetController extends BaseController
{
    public function index()
    {
        try {
            $this->authorize('viewAny', Asset::class);
            $companyId = $this->getCompanyId();

            $employees = Employee::byCompany($companyId)->with('user')->get();

            return $this->view('assets.index', compact('employees'));
        } catch (\Exception $e) {
            return $this->error('Failed to load page.', $e->getMessage());
        }
    }

    public function list(Request $request)
    {
        try {
            $this->authorize('viewAny', Asset::class);
            $companyId = $this->getCompanyId();

            $query = Asset::byCompany($companyId)
                ->with(['currentAssignment.employee.user']);

            if ($search = $request->input('search.value')) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('serial_number', 'like', "%{$search}%")
                        ->orWhere('brand', 'like', "%{$search}%")
                        ->orWhere('model', 'like', "%{$search}%");
                });
            }

            if ($request->filled('type')) {
                $query->where('type', $request->type);
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

    public function create()
    {
        try {
            $this->authorize('create', Asset::class);
            return $this->view('assets.create');
        } catch (\Exception $e) {
            return $this->error('Failed to load form.', $e->getMessage());
        }
    }

    public function edit($id)
    {
        try {
            $asset = Asset::findOrFail($id);
            $this->authorize('update', $asset);
            return $this->view('assets.create', compact('asset'));
        } catch (ModelNotFoundException $e) {
            return $this->error('Asset not found.', null, 404);
        } catch (\Exception $e) {
            return $this->error('Failed to load form.', $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            $this->authorize('create', Asset::class);

            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'type' => 'required|string|in:laptop,phone,accessory,other',
                'serial_number' => 'nullable|string|max:255|unique:assets,serial_number',
                'brand' => 'nullable|string|max:255',
                'model' => 'nullable|string|max:255',
                'purchase_date' => 'nullable|date',
                'purchase_cost' => 'nullable|numeric|min:0',
                'warranty_expiry' => 'nullable|date',
                'status' => 'required|string|in:available,assigned,under_repair,disposed',
                'notes' => 'nullable|string',
                'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            ]);

            $data = [
                'company_id' => $this->getCompanyId(),
                ...$validated,
            ];
            unset($data['image']);

            if ($request->hasFile('image')) {
                $data['image_path'] = $request->file('image')->store('assets/images', 'public');
            }

            $asset = Asset::create($data);

            return $this->created('Asset created successfully.', $asset);
        } catch (\Exception $e) {
            return $this->error('Failed to create asset.', $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $asset = Asset::findOrFail($id);
            $this->authorize('update', $asset);

            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'type' => 'required|string|in:laptop,phone,accessory,other',
                'serial_number' => 'nullable|string|max:255|unique:assets,serial_number,' . $id,
                'brand' => 'nullable|string|max:255',
                'model' => 'nullable|string|max:255',
                'purchase_date' => 'nullable|date',
                'purchase_cost' => 'nullable|numeric|min:0',
                'warranty_expiry' => 'nullable|date',
                'status' => 'required|string|in:available,assigned,under_repair,disposed',
                'notes' => 'nullable|string',
                'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            ]);

            $data = $validated;
            unset($data['image']);

            if ($request->hasFile('image')) {
                if ($asset->image_path) {
                    Storage::disk('public')->delete($asset->image_path);
                }
                $data['image_path'] = $request->file('image')->store('assets/images', 'public');
            }

            $asset->update($data);

            return $this->updated('Asset updated successfully.', $asset->fresh());
        } catch (ModelNotFoundException $e) {
            return $this->error('Asset not found.', null, 404);
        } catch (\Exception $e) {
            return $this->error('Failed to update asset.', $e->getMessage());
        }
    }

    public function show($id)
    {
        try {
            $companyId = $this->getCompanyId();
            $asset = Asset::byCompany($companyId)
                ->with(['currentAssignment.employee.user', 'assignments.employee.user', 'assignments.assignor'])
                ->findOrFail($id);
            $this->authorize('view', $asset);

            return $this->view('assets.show', compact('asset'));
        } catch (ModelNotFoundException $e) {
            return $this->error('Asset not found.', null, 404);
        } catch (\Exception $e) {
            return $this->error('Failed to load asset details.', $e->getMessage());
        }
    }

    public function assignForm($id)
    {
        try {
            $asset = Asset::findOrFail($id);
            $this->authorize('assign', $asset);
            $companyId = $this->getCompanyId();
            $employees = Employee::byCompany($companyId)->with('user')->get();
            return $this->view('assets.assign', compact('asset', 'employees'));
        } catch (ModelNotFoundException $e) {
            return $this->error('Asset not found.', null, 404);
        } catch (\Exception $e) {
            return $this->error('Failed to load form.', $e->getMessage());
        }
    }

    public function returnForm($id)
    {
        try {
            $asset = Asset::with(['currentAssignment.employee.user'])->findOrFail($id);
            $this->authorize('returnAsset', $asset);
            return $this->view('assets.return', compact('asset'));
        } catch (ModelNotFoundException $e) {
            return $this->error('Asset not found.', null, 404);
        } catch (\Exception $e) {
            return $this->error('Failed to load form.', $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $asset = Asset::findOrFail($id);
            $this->authorize('delete', $asset);

            if ($asset->image_path) {
                Storage::disk('public')->delete($asset->image_path);
            }

            $asset->delete();

            return $this->deleted('Asset deleted successfully.');
        } catch (ModelNotFoundException $e) {
            return $this->error('Asset not found.', null, 404);
        } catch (\Exception $e) {
            return $this->error('Failed to delete asset.', $e->getMessage());
        }
    }

    public function assign(Request $request, $id)
    {
        try {
            $asset = Asset::findOrFail($id);
            $this->authorize('assign', $asset);

            $validated = $request->validate([
                'employee_id' => 'required|exists:employees,id',
                'expected_return_date' => 'nullable|date|after:today',
                'condition_on_handover' => 'nullable|string',
                'notes' => 'nullable|string',
            ]);

            if ($asset->status === 'assigned' && $asset->currentAssignment) {
                return $this->error('Asset is already assigned to another employee.', null, 400);
            }

            $assignment = AssetAssignment::create([
                'asset_id' => $asset->id,
                'employee_id' => $validated['employee_id'],
                'assigned_by' => auth()->id(),
                'assigned_at' => now(),
                'expected_return_date' => $validated['expected_return_date'] ?? null,
                'condition_on_handover' => $validated['condition_on_handover'] ?? null,
                'notes' => $validated['notes'] ?? null,
            ]);

            $asset->update(['status' => 'assigned']);

            return $this->success('Asset assigned successfully.', $assignment->load(['employee.user', 'assignor']));
        } catch (ModelNotFoundException $e) {
            return $this->error('Asset not found.', null, 404);
        } catch (\Exception $e) {
            return $this->error('Failed to assign asset.', $e->getMessage());
        }
    }

    public function returnAsset(Request $request, $id)
    {
        try {
            $asset = Asset::findOrFail($id);
            $this->authorize('returnAsset', $asset);

            $currentAssignment = $asset->currentAssignment;
            if (!$currentAssignment) {
                return $this->error('Asset is not currently assigned.', null, 400);
            }

            $validated = $request->validate([
                'condition_on_return' => 'nullable|string',
                'notes' => 'nullable|string',
            ]);

            $currentAssignment->update([
                'returned_at' => now(),
                'condition_on_return' => $validated['condition_on_return'] ?? null,
                'notes' => $validated['notes'] ?? ($currentAssignment->notes),
            ]);

            $asset->update(['status' => 'available']);

            return $this->success('Asset returned successfully.', $currentAssignment->fresh()->load(['employee.user', 'assignor']));
        } catch (ModelNotFoundException $e) {
            return $this->error('Asset not found.', null, 404);
        } catch (\Exception $e) {
            return $this->error('Failed to return asset.', $e->getMessage());
        }
    }
}
