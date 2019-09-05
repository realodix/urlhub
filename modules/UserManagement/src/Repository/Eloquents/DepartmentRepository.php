<?php

namespace Mekaeil\LaravelUserManagement\Repository\Eloquents;

use App\Entities\Department;
use Mekaeil\LaravelUserManagement\Repository\Eloquents\BaseEloquentRepository;
use Mekaeil\LaravelUserManagement\Repository\Contracts\DepartmentRepositoryInterface;

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