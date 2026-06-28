<?php

namespace App\Repositories;

use App\Models\Permission;
use Illuminate\Database\Eloquent\Collection;

class PermissionRepository extends BaseRepository
{
    protected function model(): string
    {
        return Permission::class;
    }

    public function getByGroup(): Collection
    {
        return $this->model->orderBy('group')->get()->groupBy('group');
    }

    public function getByRole(int $roleId): Collection
    {
        return $this->model->whereHas('roles', function ($query) use ($roleId) {
            $query->where('role_id', $roleId);
        })->get();
    }
}
