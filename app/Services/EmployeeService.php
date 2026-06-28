<?php

namespace App\Services;

use App\Models\Employee;
use App\Models\User;
use App\Repositories\EmployeeRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class EmployeeService extends BaseService
{
    protected function repository(): string
    {
        return EmployeeRepository::class;
    }

    public function store(array $data): Employee
    {
        return DB::transaction(function () use ($data) {
            if (empty($data['employee_code'])) {
                $data['employee_code'] = $this->repository->generateEmployeeCode($data['company_id']);
            }

            $userData = [
                'company_id' => $data['company_id'] ?? null,
                'first_name' => $data['first_name'] ?? '',
                'last_name' => $data['last_name'] ?? '',
                'email' => $data['email'] ?? '',
                'phone' => $data['phone'] ?? '',
                'password' => Hash::make('Password1'),
                'created_by' => $data['created_by'] ?? auth()->id(),
            ];

            if (isset($data['email'])) {
                $user = User::updateOrCreate(
                    ['email' => $data['email']],
                    $userData
                );
            } else {
                $user = User::create($userData);
            }

            $employeeData = array_merge($data, ['user_id' => $user->id]);
            unset($employeeData['first_name'], $employeeData['last_name'], $employeeData['email'], $employeeData['phone']);

            return $this->repository->create($employeeData);
        });
    }

    public function update(Model $model, array $data): bool
    {
        $model = $model instanceof Employee ? $model : Employee::findOrFail($model->id);

        return DB::transaction(function () use ($model, $data) {
            if ($model->user) {
                $model->user->update([
                    'first_name' => $data['first_name'] ?? $model->user->first_name,
                    'last_name' => $data['last_name'] ?? $model->user->last_name,
                    'email' => $data['email'] ?? $model->user->email,
                    'phone' => $data['phone'] ?? $model->user->phone,
                ]);
            } else {
                $userData = [
                    'company_id' => $data['company_id'] ?? $model->company_id,
                    'first_name' => $data['first_name'] ?? '',
                    'last_name' => $data['last_name'] ?? '',
                    'email' => $data['email'] ?? '',
                    'phone' => $data['phone'] ?? '',
                    'password' => Hash::make('Password1'),
                ];
                $user = User::create($userData);
                $model->user_id = $user->id;
                $model->save();
            }

            unset($data['first_name'], $data['last_name'], $data['email'], $data['phone']);

            return $this->repository->update($model, $data);
        });
    }

    public function inviteEmployee(array $data): Employee
    {
        return DB::transaction(function () use ($data) {
            return $this->repository->create($data);
        });
    }

    public function updateStatus(int $employeeId, string $status): bool
    {
        $employee = $this->repository->findOrFail($employeeId);
        return $this->repository->update($employee, ['status' => $status]);
    }

    public function getByDepartment(int $departmentId, array $relations = [])
    {
        return $this->repository->getWhere(['department_id' => $departmentId], $relations);
    }
}
