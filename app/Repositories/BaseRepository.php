<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;

abstract class BaseRepository
{
    protected Model $model;
    protected string $cacheKey = '';
    protected int $cacheTTL = 3600;

    public function __construct()
    {
        $this->model = app($this->model());
        $this->cacheKey = class_basename($this->model);
    }

    abstract protected function model(): string;

    public function all(array $columns = ['*']): Collection
    {
        try {
            return $this->model->all($columns);
        } catch (\Exception $e) {
            throw new \RuntimeException('Failed to fetch records: ' . $e->getMessage());
        }
    }

    public function paginate(int $perPage = 25, array $columns = ['*'], array $relations = []): LengthAwarePaginator
    {
        try {
            return $this->model->with($relations)->paginate($perPage, $columns);
        } catch (\Exception $e) {
            throw new \RuntimeException('Failed to fetch records: ' . $e->getMessage());
        }
    }

    public function findById(int $modelId, array $columns = ['*'], array $relations = []): ?Model
    {
        try {
            return $this->model->with($relations)->find($modelId, $columns);
        } catch (\Exception $e) {
            throw new \RuntimeException('Failed to find record: ' . $e->getMessage());
        }
    }

    public function findOrFail(int $modelId, array $relations = []): Model
    {
        try {
            return $this->model->with($relations)->findOrFail($modelId);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            throw $e;
        } catch (\Exception $e) {
            throw new \RuntimeException('Failed to find record: ' . $e->getMessage());
        }
    }

    public function findByColumn(string $column, mixed $value, array $relations = []): ?Model
    {
        try {
            return $this->model->with($relations)->where($column, $value)->first();
        } catch (\Exception $e) {
            throw new \RuntimeException('Failed to find record: ' . $e->getMessage());
        }
    }

    public function getWhere(array $conditions, array $relations = [], array $columns = ['*']): Collection
    {
        try {
            return $this->model->with($relations)->where($conditions)->get($columns);
        } catch (\Exception $e) {
            throw new \RuntimeException('Failed to fetch records: ' . $e->getMessage());
        }
    }

    public function create(array $data): Model
    {
        try {
            $this->clearCache();
            return $this->model->create($data);
        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->getCode() === '23000') {
                throw new \RuntimeException('A record with this data already exists.');
            }
            throw new \RuntimeException('Database error: ' . $e->getMessage());
        } catch (\Exception $e) {
            throw new \RuntimeException('Failed to create record: ' . $e->getMessage());
        }
    }

    public function update(Model $model, array $data): bool
    {
        try {
            $this->clearCache();
            return $model->update($data);
        } catch (\Exception $e) {
            throw new \RuntimeException('Failed to update record: ' . $e->getMessage());
        }
    }

    public function delete(Model $model): ?bool
    {
        try {
            $this->clearCache();
            return $model->delete();
        } catch (\Exception $e) {
            throw new \RuntimeException('Failed to delete record: ' . $e->getMessage());
        }
    }

    public function forceDelete(Model $model): ?bool
    {
        try {
            $this->clearCache();
            return $model->forceDelete();
        } catch (\Exception $e) {
            throw new \RuntimeException('Failed to force delete record: ' . $e->getMessage());
        }
    }

    public function restore(Model $model): ?bool
    {
        try {
            $this->clearCache();
            return $model->restore();
        } catch (\Exception $e) {
            throw new \RuntimeException('Failed to restore record: ' . $e->getMessage());
        }
    }

    public function count(array $conditions = []): int
    {
        try {
            if (empty($conditions)) {
                return $this->model->count();
            }
            return $this->model->where($conditions)->count();
        } catch (\Exception $e) {
            throw new \RuntimeException('Failed to count records: ' . $e->getMessage());
        }
    }

    public function exists(array $conditions): bool
    {
        try {
            return $this->model->where($conditions)->exists();
        } catch (\Exception $e) {
            throw new \RuntimeException('Failed to check existence: ' . $e->getMessage());
        }
    }

    public function pluck(string $value, string $key = null): \Illuminate\Support\Collection
    {
        try {
            return $this->model->pluck($value, $key);
        } catch (\Exception $e) {
            throw new \RuntimeException('Failed to fetch data: ' . $e->getMessage());
        }
    }

    public function updateOrCreate(array $attributes, array $values = []): Model
    {
        try {
            $this->clearCache();
            return $this->model->updateOrCreate($attributes, $values);
        } catch (\Exception $e) {
            throw new \RuntimeException('Failed to create or update record: ' . $e->getMessage());
        }
    }

    public function getWithRelation(string $relation, array $columns = ['*']): Collection
    {
        try {
            return $this->model->with($relation)->get($columns);
        } catch (\Exception $e) {
            throw new \RuntimeException('Failed to fetch records: ' . $e->getMessage());
        }
    }

    public function chunk(int $count, callable $callback): bool
    {
        try {
            return $this->model->chunk($count, $callback);
        } catch (\Exception $e) {
            throw new \RuntimeException('Failed to process chunks: ' . $e->getMessage());
        }
    }

    public function clearCache(): void
    {
        Cache::forget($this->cacheKey);
    }

    public function getByCompany(int $companyId, int $perPage = 25, array $relations = []): LengthAwarePaginator
    {
        try {
            return $this->model->byCompany($companyId)->with($relations)->paginate($perPage);
        } catch (\Exception $e) {
            throw new \RuntimeException('Failed to fetch records: ' . $e->getMessage());
        }
    }

    public function getAllByCompany(int $companyId, array $relations = []): Collection
    {
        try {
            return $this->model->byCompany($companyId)->with($relations)->get();
        } catch (\Exception $e) {
            throw new \RuntimeException('Failed to fetch records: ' . $e->getMessage());
        }
    }

    public function findForCompany(int $id, int $companyId, array $relations = []): ?Model
    {
        try {
            return $this->model->byCompany($companyId)->with($relations)->find($id);
        } catch (\Exception $e) {
            throw new \RuntimeException('Failed to find record: ' . $e->getMessage());
        }
    }
}
