<?php
namespace App\Repositories;

use App\Models\Department;

class DepartmentRepository
{
    public function all()
    {
        return Department::all();
    }

    public function find($id)
    {
        return Department::find($id);
    }

    public function create(array $data)
    {
        return Department::create($data);
    }

    public function update(Department $department, array $data)
    {
        return $department->update($data);
    }

    public function delete(Department $department): bool
    {
        return $department->delete();
    }
}