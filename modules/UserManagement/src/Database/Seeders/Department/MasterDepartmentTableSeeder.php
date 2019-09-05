<?php

namespace UrlHub\UserManagement\seeders\Department;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use UrlHub\UserManagement\Repository\Contracts\DepartmentRepositoryInterface;

class MasterDepartmentTableSeeder extends Seeder
{
    protected $departments = [];
    protected $departmentRepository;

    public function __construct(DepartmentRepositoryInterface $repository)
    {
        $this->departmentRepository = $repository;
    }

    protected function getDepartments()
    {
        return $this->departments;
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
        $this->command->info('              USER MODULE: INSERT DEPARTMENTS DATA');
        $this->command->info('=============================================================');
        $this->command->info("\n");

        foreach ($this->getDepartments() as $item)
        {
            $parent = null;
            if($item['parent'] != null)
            {
                $parent = $this->departmentRepository->findBy([
                    'title'         => $item['title'],
                ])->id;
            }

            $findDepartment = $this->departmentRepository->findBy([
                'title'         => $item['title'],
                'parent_id'     => $parent
            ]);

            if ($findDepartment)
            {
                $this->command->info('THIS DEPARTMENT << ' . $item['title'] . '] >> EXISTED! UPDATING DATA ...');

                $this->departmentRepository->update($findDepartment->id,[
                    'title'     => $item['title'],
                    'parent_id' => $parent,
                ]);

                continue;
            }

            $this->command->info('CREATING THIS DEPARTMENT <<' . $item['title'] . '] >> ...');

            $this->departmentRepository->store([
                'title'     => $item['title'],
                'parent_id' => $parent,
            ]);

        }

        $this->command->info("\n");
        $this->command->info('=============================================================');
        $this->command->info('              INSERTING DEPARTMENTS DATA FINALIZED!');
        $this->command->info('=============================================================');
        $this->command->info("\n");

    }

}
