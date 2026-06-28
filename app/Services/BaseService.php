<?php

namespace App\Services;

use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

abstract class BaseService
{
    protected BaseRepository $repository;

    public function __construct()
    {
        $this->repository = app($this->repository());
    }

    abstract protected function repository(): string;

    public function all()
    {
        try {
            return $this->repository->all();
        } catch (\Exception $e) {
            throw new \RuntimeException('Failed to fetch records: ' . $e->getMessage());
        }
    }

    public function paginate(int $perPage = 25, array $relations = [])
    {
        try {
            return $this->repository->paginate($perPage, ['*'], $relations);
        } catch (\Exception $e) {
            throw new \RuntimeException('Failed to fetch records: ' . $e->getMessage());
        }
    }

    public function find(int $id, array $relations = []): ?Model
    {
        try {
            return $this->repository->findById($id, ['*'], $relations);
        } catch (\Exception $e) {
            throw new \RuntimeException('Failed to find record: ' . $e->getMessage());
        }
    }

    public function store(array $data): Model
    {
        try {
            return DB::transaction(function () use ($data) {
                return $this->repository->create($data);
            });
        } catch (\Exception $e) {
            throw new \RuntimeException('Failed to create record: ' . $e->getMessage());
        }
    }

    public function update(Model $model, array $data): bool
    {
        try {
            return DB::transaction(function () use ($model, $data) {
                return $this->repository->update($model, $data);
            });
        } catch (\Exception $e) {
            throw new \RuntimeException('Failed to update record: ' . $e->getMessage());
        }
    }

    public function destroy(Model $model): ?bool
    {
        try {
            return DB::transaction(function () use ($model) {
                return $this->repository->delete($model);
            });
        } catch (\Exception $e) {
            throw new \RuntimeException('Failed to delete record: ' . $e->getMessage());
        }
    }

    public function getByCompany(int $companyId, int $perPage = 25, array $relations = [])
    {
        try {
            return $this->repository->getByCompany($companyId, $perPage, $relations);
        } catch (\Exception $e) {
            throw new \RuntimeException('Failed to fetch records: ' . $e->getMessage());
        }
    }

    public function getAllByCompany(int $companyId, array $relations = [])
    {
        try {
            return $this->repository->getAllByCompany($companyId, $relations);
        } catch (\Exception $e) {
            throw new \RuntimeException('Failed to fetch records: ' . $e->getMessage());
        }
    }

    public function findForCompany(int $id, int $companyId, array $relations = []): ?Model
    {
        try {
            return $this->repository->findForCompany($id, $companyId, $relations);
        } catch (\Exception $e) {
            throw new \RuntimeException('Failed to find record: ' . $e->getMessage());
        }
    }
}
