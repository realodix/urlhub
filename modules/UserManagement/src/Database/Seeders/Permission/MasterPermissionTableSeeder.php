<?php

namespace UrlHub\UserManagement\seeders\Permission;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use UrlHub\UserManagement\Repository\Contracts\PermissionRepositoryInterface;
use UrlHub\UserManagement\Repository\Contracts\RoleRepositoryInterface;

class MasterPermissionTableSeeder extends Seeder
{
    protected $permissions = [];
    protected $guardName   = "web";
    protected $permissionRepository;
    protected $roleRepository;

    public function __construct(
        PermissionRepositoryInterface $repository,
        RoleRepositoryInterface $role
    ) {
        $this->permissionRepository = $repository;
        $this->roleRepository       = $role;
    }

    protected function getPermissions()
    {
        return $this->permissions;
    }
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $this->command->info('==========================================================================');
        $this->command->info('USER MANAGEMENT PACKAGE: INSERT PERMISSIONS DATA');
        $this->command->info('YOU CAN ADD NEW PERMISSION IN "database/seeds/PermissionTableSeeder.php"');
        $this->command->info('==========================================================================');
        $this->command->info("\n");

        $rolePermissions = array();

        foreach ($this->getPermissions() as $permission) {

            /// WHEN WE NEED A PERMISSION FOR DIFFERENT GUARD NAMES
            //////////////////////////////////////////////////////////
            if (is_array($permission['guard_name'])) {
                foreach ($permission['guard_name'] as $guard) {
                    $rolePermissions = $this->setPermissions($permission, $guard);

                    $this->command->info('  THIS PERMISSION <<' . array_keys($rolePermissions)[0] . ' >> ASSIGNED TO THESE ROLES <<<< '. implode(' - ', $rolePermissions[array_keys($rolePermissions)[0]]) . ' >>> GUARD NAME = ' . $guard);
                    $permObject = $this->permissionRepository->findBy([
                        'name'          => array_keys($rolePermissions)[0],
                        'guard_name'    => $guard
                    ]);
                    $permObject->syncRoles($this->getRolesID($rolePermissions[array_keys($rolePermissions)[0]], $guard));
                }

                continue;
            }

            $rolePermissions = $this->setPermissions($permission, $permission['guard_name']);
            $this->guardName = $permission['guard_name'];

            /*
            |--------------------------------------------------------------------------
            |  UPDATE ROLE'S PERMISSIONS
            |--------------------------------------------------------------------------
            |
            */

            if (!empty($rolePermissions)) {
                $this->command->info("\n");
                $this->command->info('        *********************************************        ');
                $this->command->info('               UPDATING ROLE\'S PERMISSIONS                  ');
                $this->command->info('        *********************************************        ');
                $this->command->info("\n");
                foreach ($rolePermissions as $perm => $roles) {
                    $this->command->info('  THIS PERMISSION <<' . $perm . ' >> ASSIGNED TO THESE ROLES <<<< '. implode(' - ', $roles) . ' >>> GUARD NAME = ' . $this->guardName);
                    $permObject = $this->permissionRepository->findBy(['name' => $perm]);
                    $permObject->syncRoles($this->getRolesID($roles, $this->guardName));
                }

                $this->command->info("\n");
                $this->command->info('        *********************************************        ');
                $this->command->info('           FINALIZED UPDATING ROLE\'S PERMISSIONS            ');
                $this->command->info('        *********************************************        ');
            }
        }

        $this->command->info("\n");
        $this->command->info('=============================================================');
        $this->command->info('      INSERTING PERMISSIONS FINALIZED!');
        $this->command->info('=============================================================');
        $this->command->info("\n");
    }

    private function setPermissions(array $permission, $guard = null)
    {
        $getGuard       = $guard ?? $permission['guard_name'];
        $getPermission  = $this->permissionRepository->findBy([
            'name'      => $permission['name'],
            'guard_name'=> $getGuard
        ]);

        if (! is_null($getPermission)) {
            $this->command->info('THIS PERMISSION << ' . $permission['name'] . ' >> EXISTED! UPDATING DATA ...');

            $this->permissionRepository->update($getPermission->id, [
                'name'          => $permission['name'],
                'guard_name'    => $guard ?? $permission['guard_name'],
                'title'         => isset($permission['title']) ? $permission['title'] : null ,
                'module'        => isset($permission['module']) ? $permission['module'] : null ,
                'description'   => isset($permission['description']) ? $permission['description'] : null ,
            ]);

            $rolePermissions[$permission['name']] = array_values($permission['roles']) ?? null ;

            return $rolePermissions;
        }

        $this->command->info('CREATING THIS PERMISSION <<' . $permission['name'] . ' >> ...');

        $this->permissionRepository->store([
            'name'          => $permission['name'],
            'guard_name'    => $getGuard,
            'title'         => isset($permission['title']) ? $permission['title'] : null ,
            'module'        => isset($permission['module']) ? $permission['module'] : null ,
            'description'   => isset($permission['description']) ? $permission['description'] : null ,
        ]);

        $rolePermissions[$permission['name']] = array_values($permission['roles']) ?? null ;

        return $rolePermissions;
    }


    private function getRolesID(array $roles, $guard)
    {
        $roleIDs    = array();
        foreach ($roles as $role) {
            $findRole = $this->roleRepository->findBy([
                'name'       => $role,
                'guard_name' => $guard
            ]);
            $roleIDs[] = $findRole ? $findRole->id : null;
        }
        return array_values($roleIDs);
    }
}
