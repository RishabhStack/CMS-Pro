<?php

namespace App\Repositories;

use App\Models\Role;

class RoleRepository extends BaseRepository
{
    protected function model(): string
    {
        return Role::class;
    }

    public function getRoleWithPermissions(int $roleId): Role
    {
        return $this->model->with('permissions')->findOrFail($roleId);
    }
}
