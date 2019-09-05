<?php

namespace UrlHub\UserManagement\seeders\Permission;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use UrlHub\UserManagement\Repository\Contracts\RoleRepositoryInterface;

class MasterRoleTableSeeder extends Seeder
{
    protected $roles = [];
    protected $roleRepository;

    public function __construct(RoleRepositoryInterface $repository)
    {
        $this->roleRepository = $repository;
    }

    protected function getRoles()
    {
        return $this->roles;
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $this->command->info('=============================================================');
        $this->command->info('              USER MODULE: INSERT ROLES DATA');
        $this->command->info('=============================================================');
        $this->command->info("\n");

        foreach ($this->getRoles() as $role)
        {
            $findRole = $this->roleRepository->findBy([
                'name'       => $role['name'],
                'guard_name' => $role['guard_name']
            ]);

            if ($findRole)
            {
                $this->command->info('THIS ROLE << ' . $role['name'] .'['. $role['guard_name'] . '] >> EXISTED! UPDATING DATA ...');

                $this->roleRepository->update($findRole->id,[
                    'name'          => $role['name'],
                    'title'         => $role['title'],
                    'guard_name'    => $role['guard_name'],
                    'description'   => isset($role['description']) ? $role['description'] : null,
                ]);

                continue;
            }

            $this->command->info('CREATING THIS ROLE <<' . $role['name'] .'['. $role['guard_name'] . '] >> ...');

            $this->roleRepository->store([
                'name'          => $role['name'],
                'title'         => $role['title'],
                'guard_name'    => $role['guard_name'],
                'description'   => isset($role['description']) ? $role['description'] : null,
            ]);

        }

        $this->command->info("\n");
        $this->command->info('=============================================================');
        $this->command->info('              INSERTING ROLES FINALIZED!');
        $this->command->info('=============================================================');
        $this->command->info("\n");

    }

}
