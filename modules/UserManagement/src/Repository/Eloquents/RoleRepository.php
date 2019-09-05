<?php

namespace Mekaeil\LaravelUserManagement\Repository\Eloquents;

use App\Entities\Role;
use Mekaeil\LaravelUserManagement\Repository\Eloquents\BaseEloquentRepository;
use Mekaeil\LaravelUserManagement\Repository\Contracts\RoleRepositoryInterface;

class RoleRepository extends BaseEloquentRepository implements RoleRepositoryInterface
{
    protected $model = Role::class;

    public function syncRoleToUser($owner, array $roles = [])
    {
        return $owner->syncRoles($roles);
    }

    public function setRoleToMember($owner, $role, $assign = true)
    {
        if ($assign)
        {
            return $owner->assignRole($role);
        }

        return $owner->removeRole($role);
    }

    public function getAllRolePermissions(Role $role, $method = 'get')
    {
        if ($method == 'pluck'){
            return $role->getAllPermissions()->pluck('id','id')->toArray();
        }

        return $role->getAllPermissions();
    }

}