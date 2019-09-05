<?php

namespace UrlHub\UserManagement\Repository\Eloquents;

use App\Entities\Permission;
use App\Entities\Role;
use UrlHub\UserManagement\Repository\Eloquents\BaseEloquentRepository;
use UrlHub\UserManagement\Repository\Contracts\PermissionRepositoryInterface;

class PermissionRepository extends BaseEloquentRepository implements PermissionRepositoryInterface
{
    protected $model        = Permission::class;
    protected $roleModel    = Role::class;

    public function setPermissionToRole(int $roleID, $permission, $give = true)
    {
        $query  = $this->roleModel::query();
        $role   = $query->find($roleID);

        if ($give) {
            return $role->givePermissionTo($permission);
        }

        return $role->revokePermissionTo($permission);
    }

    public function SyncPermToRole(int $roleID, array $permissions)
    {
        $query  = $this->roleModel::query();
        $role   = $query->find($roleID);

        return $role->syncPermissions($permissions);
    }

    public function getPermissionsModule()
    {
        $query = $this->model::query();
        return array_keys(collect($query->get())->keyBy('module')->toArray());
    }
}
