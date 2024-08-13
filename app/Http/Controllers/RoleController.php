<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\Role;
use App\Models\RolePermission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RoleController extends Controller{

    public function __construct(){
        $this->middleware('permission:view_roles')->only('index');
        $this->middleware('permission:add_role')->only('create');
        $this->middleware('permission:add_role')->only('store');
        $this->middleware('permission:view_role')->only('show');
        $this->middleware('permission:delete_role')->only('destroy');
    }

    public function index(){
        $roles = Role::all();
        return view('pages.roles.index', compact('roles'));
    }
    
    public function create(){
        $permissions = Permission::all();
        return view('pages.roles.create', compact('permissions'));
    }

    public function store(Request $request){
        $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:roles,name'],
            'permissions' => 'required|array',
            'permissions.*' => 'integer|exists:permissions,id',
        ]);
        
        try {
            DB::beginTransaction();
            $role = Role::create(['name' => $request->name]);
            foreach ($request->permissions as $permissionId) {
                DB::table('permission_role')->insert(
                    ['role_id' => $role->id, 'permission_id' => $permissionId]
                );
            }
            
            logActivity(' قام الموظف باضافة وظيفة جديدة رقم '.$role->id, 'الموظفين');

            DB::commit();
            toastr()->success('تم اضافة وظيفة جديدة بنجاح');
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollBack();
            toastr()->error($e->getMessage());
            return redirect()->back();
        }
    }
    
    public function show(Role $role){
        if($role){
            $permissions = Permission::all();
            $rolePermissions = $role->permissions->pluck('id')->toArray();
            return view('pages.roles.show', compact('permissions', 'role', 'rolePermissions'));
        }else{
            toastr()->error('لم يتم العثور عل هذة الوظيفة');
        }
    }
    
    public function edit(Role $role){
        //
    }
    
    public function update(Request $request, Role $role){
        if($role){
            $request->validate([
                'name' => ['required', 'string', 'max:255', 'unique:roles,name,'.$role->id],
                'permissions' => 'required|array',
                'permissions.*' => 'integer|exists:permissions,id',
            ]);
            
            try {
                DB::beginTransaction();
                $role->update(['name' => $request->name]);
                DB::table('permission_role')->where('role_id', $role->id)->delete();
                foreach ($request->permissions as $permissionId) {
                    DB::table('permission_role')->insert(
                        ['role_id' => $role->id, 'permission_id' => $permissionId]
                    );
                }

                logActivity(' قام الموظف بتعديل وظيفة رقم '.$role->id, 'الموظفين');
                
                DB::commit();
                toastr()->success('تم تعديل الوظيفة بنجاح');
                return redirect()->back();
            } catch (\Exception $e) {
                DB::rollBack();
                toastr()->error($e->getMessage());
                return redirect()->back();
            }
        }else{
            toastr()->error('لم يتم العثور عل هذة الوظيفة');
            return redirect()->back();
        }
    }

    public function destroy(Role $role){
        if($role){
            if($role->employees->count() > 0){
                toastr()->error('لا يمكن حذف هذة الوظيفة لأنها مرتبطة بموظفين');
                return redirect()->back();
            }else{
                $role->delete();
                logActivity(' قام الموظف بحذف وظيفة رقم '.$role->id, 'الموظفين');
                toastr()->success('تم حذف الوظيفة بنجاح');
                return redirect()->intended(route('roles.index'));
            }
        }else{
            toastr()->error('لم يتم العثور عل هذة الوظيفة');
            return redirect()->back();
        }
    }
}