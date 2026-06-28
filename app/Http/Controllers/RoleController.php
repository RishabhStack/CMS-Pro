<?php

namespace App\Http\Controllers;

use App\Http\Requests\RoleRequest;
use App\Models\Role;
use App\Services\RoleService;
use App\Services\PermissionService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class RoleController extends BaseController
{
    public function __construct(
        protected RoleService $roleService,
        protected PermissionService $permissionService
    ) {
    }

    public function create()
    {
        try {
            $this->authorize('create', Role::class);
            return $this->view('roles.create');
        } catch (\Exception $e) {
            return $this->error('Failed to load form.', $e->getMessage());
        }
    }

    public function edit($id)
    {
        try {
            $role = Role::findOrFail($id);
            $this->authorize('update', $role);

            if ($role->users()->where('user_id', auth()->id())->exists()) {
                return $this->error('You cannot edit your own role.', null, 403);
            }

            return $this->view('roles.create', compact('role'));
        } catch (ModelNotFoundException $e) {
            return $this->error('Role not found.', null, 404);
        } catch (\Exception $e) {
            return $this->error('Failed to load form.', $e->getMessage());
        }
    }

    public function index()
    {
        try {
            $this->authorize('viewAny', Role::class);
            return $this->view('roles.index');
        } catch (\Exception $e) {
            return $this->error('Failed to load page.', $e->getMessage());
        }
    }

    public function list(Request $request)
    {
        try {
            $this->authorize('viewAny', Role::class);
            $companyId = $this->getCompanyId();

            $query = Role::byCompany($companyId)->with(['permissions'])->withCount('users');

            if ($search = $request->input('search.value')) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%");
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

    public function store(RoleRequest $request)
    {
        try {
            $this->authorize('create', Role::class);

            $role = $this->roleService->store([
                'company_id' => $this->getCompanyId(),
                'created_by' => auth()->id(),
                ...$request->validated(),
            ]);

            if ($request->filled('permissions')) {
                $this->roleService->syncPermissions($role, $request->permissions);
            }

            return $this->created('Role created successfully.', $role->load('permissions'));
        } catch (\Exception $e) {
            return $this->error('Failed to create role.', $e->getMessage());
        }
    }

    public function show($id)
    {
        try {
            $companyId = $this->getCompanyId();
            $role = Role::byCompany($companyId)->with(['permissions', 'users'])->findOrFail($id);
            $this->authorize('view', $role);

            return $this->success('Role retrieved successfully.', $role);
        } catch (ModelNotFoundException $e) {
            return $this->error('Role not found.', null, 404);
        } catch (\Exception $e) {
            return $this->error('Failed to retrieve role.', $e->getMessage());
        }
    }

    public function update(RoleRequest $request, $id)
    {
        try {
            $role = Role::findOrFail($id);
            $this->authorize('update', $role);

            if ($role->users()->where('user_id', auth()->id())->exists()) {
                return $this->error('You cannot edit your own role.', null, 403);
            }

            $this->roleService->update($role, $request->validated());

            if ($request->has('permissions')) {
                $this->roleService->syncPermissions($role, $request->permissions ?? []);
            }

            return $this->updated('Role updated successfully.', $role->fresh()->load('permissions'));
        } catch (ModelNotFoundException $e) {
            return $this->error('Role not found.', null, 404);
        } catch (\Exception $e) {
            return $this->error('Failed to update role.', $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $role = Role::findOrFail($id);

            if ($role->is_system) {
                return $this->error('System roles cannot be deleted.', null, 403);
            }

            if ($role->users()->where('user_id', auth()->id())->exists()) {
                return $this->error('You cannot delete your own role.', null, 403);
            }

            $userCount = $role->users()->count();
            if ($userCount > 0) {
                return $this->error(
                    "Cannot delete role \"{$role->name}\" because {$userCount} user(s) are assigned to it. Please reassign these users to another role first.",
                    null,
                    400
                );
            }

            $this->authorize('delete', $role);
            $this->roleService->destroy($role);

            return $this->deleted('Role deleted successfully.');
        } catch (ModelNotFoundException $e) {
            return $this->error('Role not found.', null, 404);
        } catch (\Exception $e) {
            return $this->error('Failed to delete role.', $e->getMessage());
        }
    }

    public function managePermissions($id)
    {
        try {
            $role = Role::findOrFail($id);
            $this->authorize('update', $role);

            if ($role->users()->where('user_id', auth()->id())->exists()) {
                return $this->error('You cannot edit your own role.', null, 403);
            }

            $permissions = $this->permissionService->getByGroup();
            $rolePermissions = $role->permissions->pluck('id')->toArray();

            return $this->view('roles.permissions', compact('role', 'permissions', 'rolePermissions'));
        } catch (ModelNotFoundException $e) {
            return $this->error('Role not found.', null, 404);
        } catch (\Exception $e) {
            return $this->error('Failed to load permissions.', $e->getMessage());
        }
    }

    public function permissions()
    {
        try {
            $this->authorize('viewAny', Role::class);

            $permissions = $this->permissionService->getByGroup();

            return $this->success('Permissions retrieved successfully.', $permissions);
        } catch (\Exception $e) {
            return $this->error('Failed to retrieve permissions.', $e->getMessage());
        }
    }
}
