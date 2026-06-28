<?php

namespace App\Services;

use App\Models\Role;
use App\Repositories\RoleRepository;
use Illuminate\Support\Facades\DB;

class RoleService extends BaseService
{
    protected function repository(): string
    {
        return RoleRepository::class;
    }

    public function assignPermissions(Role $role, array $permissions): Role
    {
        return DB::transaction(function () use ($role, $permissions) {
            $role->permissions()->sync($permissions);
            return $role->load('permissions');
        });
    }

    public function syncPermissions(Role $role, array $permissions): Role
    {
        return DB::transaction(function () use ($role, $permissions) {
            $role->permissions()->sync($permissions);
            return $role->load('permissions');
        });
    }

    public function getRoleWithPermissions(int $roleId): Role
    {
        return $this->repository->getRoleWithPermissions($roleId);
    }
}
