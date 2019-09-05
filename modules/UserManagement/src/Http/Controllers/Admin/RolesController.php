<?php

namespace Mekaeil\LaravelUserManagement\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Mekaeil\LaravelUserManagement\Repository\Contracts\PermissionRepositoryInterface;
use Mekaeil\LaravelUserManagement\Repository\Contracts\RoleRepositoryInterface;
use Mekaeil\LaravelUserManagement\Http\Requests\Admin\StoreRole;
use Mekaeil\LaravelUserManagement\Http\Requests\Admin\UpdateRole;

class RolesController extends Controller
{
    protected $permissionRepository;
    protected $roleRepository;

    public function __construct(
        PermissionRepositoryInterface $permission,
        RoleRepositoryInterface $role)
    {
        $this->permissionRepository = $permission;
        $this->roleRepository       = $role;
    }

    public function index()
    {   
        $roles = $this->roleRepository->all();
        return view('user-management.role.index', compact('roles'));
    }

    public function create()
    {
        $permissions = $this->permissionRepository->all();

        return view('user-management.role.create', compact('permissions'));    
    }

    public function edit(int $ID)
    {
        if($role = $this->roleRepository->find($ID))
        {
            $permissions        = $this->permissionRepository->all();
            $roleHasPermissions = array_column(json_decode($role->permissions, true), 'id');
            
            return view('user-management.role.edit', compact('role', 'permissions', 'roleHasPermissions'));    
        }

        return redirect()->route('admin.user_management.role.index')->with('message',[
            'type'  => 'danger',
            'text'  => 'This role does not exist!'
        ]);

    }

    public function store(StoreRole $request)
    {
        $role = $this->roleRepository->store([
            'name'          => $request->name,
            'title'         => $request->title,
            'guard_name'    => $request->guard_name,
            'description'   => $request->description,
        ]);
        
        if(! empty($request->permissions))
        {
            $this->permissionRepository->setPermissionToRole($role->id, $request->permissions);
        }

        return redirect()->route('admin.user_management.role.index')->with('message',[
            'type'  => 'success',
            'text'  => "his role << $request->name >> created successfully.",
        ]);

    }

    public function update(int $ID, UpdateRole $request)
    {
        if($role = $this->roleRepository->find($ID))
        {
            $this->roleRepository->update($ID,[
                'name'          => $request->name,
                'title'         => $request->title,
                'guard_name'    => $request->guard_name,
                'description'   => $request->description,
            ]);

            $permissions = $request->permissions ?? [];
            $this->permissionRepository->SyncPermToRole($role->id, $permissions);
   
            return redirect()->route('admin.user_management.role.index')->with('message',[
               'type'  => 'success',
               'text'  => "This role << $request->name >> updated successfully.",
            ]);

        }

        return redirect()->route('admin.user_management.role.index')->with('message',[
            'type'  => 'danger',
            'text'  => 'This role does not exist!'
        ]);
    }

    public function delete(int $ID)
    {
        if($this->roleRepository->find($ID))
        {
            $this->roleRepository->delete($ID);

            return redirect()->route('admin.user_management.role.index')->with('message',[
                'type'  => 'warning',
                'text'  => 'Role deleted successfully!'
            ]);
        }

        return redirect()->route('admin.user_management.role.index')->with('message',[
            'type'  => 'danger',
            'text'  => 'This role does not exist!'
        ]);
    }
    
}
