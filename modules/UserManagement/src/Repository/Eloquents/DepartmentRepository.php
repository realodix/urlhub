<?php

namespace UrlHub\UserManagement\Repository\Eloquents;

use App\Entities\Department;
use UrlHub\UserManagement\Repository\Eloquents\BaseEloquentRepository;
use UrlHub\UserManagement\Repository\Contracts\DepartmentRepositoryInterface;

class DepartmentRepository extends BaseEloquentRepository implements DepartmentRepositoryInterface
{
    protected $model = Department::class;

    public function syncDepartments($owner, array $departments=[])
    {
        return $owner->departments()->sync($departments, false);
    }

    public function attachDepartment($owner, array $departments=[])
    {
        return $owner->departments()->attach($departments);
    }
}
