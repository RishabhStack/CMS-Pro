<?php

namespace App\Services;

use App\Repositories\PermissionRepository;

class PermissionService extends BaseService
{
    protected function repository(): string
    {
        return PermissionRepository::class;
    }

    public function getByGroup()
    {
        return $this->repository->getByGroup();
    }

    public function getByRole(int $roleId)
    {
        return $this->repository->getByRole($roleId);
    }
}
