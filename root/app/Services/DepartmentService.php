<?php

namespace App\Services;

use App\Repositories\DepartmentRepository;
use App\Models\Department;

class DepartmentService
{
    protected $departmentRepository;

    public function __construct(DepartmentRepository $departmentRepository)
    {
        $this->departmentRepository = $departmentRepository;
    }

    //一覧取得
    public function getAllDepartments()
    {
        return $this->departmentRepository->all();
    }

    //作成
    public function createDepartment(array $data)
    {
        return $this->departmentRepository->create($data);
    }

    //更新
    public function updateDepartment(Department $department, array $data): bool
    {
        return $this->departmentRepository->update($department, $data);
    }

    //削除
    public function deleteDepartment(Department $department):bool
    {
        return $this->departmentRepository->delete($department);
    }
}