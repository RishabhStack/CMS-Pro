<?php

namespace App\Repositories;

use App\Models\Payroll;

class PayrollRepository extends BaseRepository
{
    protected function model(): string
    {
        return Payroll::class;
    }

    public function create(array $data): Payroll
    {
        try {
            $this->clearCache();
            return $this->model->create($data);
        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->getCode() === '23000') {
                throw new \RuntimeException('A payroll record already exists for this employee and period.');
            }
            throw new \RuntimeException('Database error while creating payroll: ' . $e->getMessage());
        } catch (\Exception $e) {
            throw new \RuntimeException('Failed to create payroll: ' . $e->getMessage());
        }
    }

    public function update(\Illuminate\Database\Eloquent\Model $model, array $data): bool
    {
        try {
            $this->clearCache();
            return $model->update($data);
        } catch (\Exception $e) {
            throw new \RuntimeException('Failed to update payroll: ' . $e->getMessage());
        }
    }

    public function delete(\Illuminate\Database\Eloquent\Model $model): ?bool
    {
        try {
            $this->clearCache();
            return $model->delete();
        } catch (\Exception $e) {
            throw new \RuntimeException('Failed to delete payroll: ' . $e->getMessage());
        }
    }
}
