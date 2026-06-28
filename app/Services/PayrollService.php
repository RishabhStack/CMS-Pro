<?php

namespace App\Services;

use App\Models\Payroll;
use App\Repositories\PayrollRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;

class PayrollService extends BaseService
{
    protected function repository(): string
    {
        return PayrollRepository::class;
    }

    public function generatePayroll(array $data): Payroll
    {
        try {
            return DB::transaction(function () use ($data) {
                return $this->repository->create($data);
            });
        } catch (\Exception $e) {
            throw new \RuntimeException('Failed to generate payroll: ' . $e->getMessage());
        }
    }

    public function processPayment(int $payrollId, array $paymentData): Payroll
    {
        try {
            return DB::transaction(function () use ($payrollId, $paymentData) {
                $payroll = $this->repository->findOrFail($payrollId);
                $this->repository->update($payroll, array_merge($paymentData, [
                    'status' => 'paid',
                    'paid_at' => now(),
                ]));
                return $payroll->fresh();
            });
        } catch (ModelNotFoundException $e) {
            throw $e;
        } catch (\Exception $e) {
            throw new \RuntimeException('Failed to process payment: ' . $e->getMessage());
        }
    }
}
