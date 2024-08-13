<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Role;
use App\Rules\ValidPhoneRule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class EmployeeController extends Controller{

    public function __construct(){
        $this->middleware('permission:view_employees')->only('index');
        $this->middleware('permission:add_employee')->only('create');
        $this->middleware('permission:add_employee')->only('store');
        $this->middleware('permission:view_employee')->only('show');
        $this->middleware('permission:change_employee_status')->only('changeStatus');
        $this->middleware('permission:change_employee_role')->only('changeRole');
    }

    public function index(){
        $employees = Employee::all();
        return view('pages.employees.index', compact('employees'));
    }

    public function create(){
        $roles = Role::all();
        return view('pages.employees.create', compact('roles'));
    }

    public function store(Request $request){
        $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:employees,name'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:employees,email'],
            'phone' => ['nullable', new ValidPhoneRule, 'unique:employees,phone'],
            'status' => ['nullable', 'string', 'max:255', 'in:مفعل,غير مفعل'],
            'password' => ['required', 'min:6'],
            'role_id' => ['required', 'numeric', 'exists:roles,id'],
        ]);
        
        try {
            DB::beginTransaction();
            $attributes = $request->all();

            $employee = Employee::create($attributes);

            logActivity(' قام الموظف باضافة موظف جديد رقم '.$employee->id, 'الموظفين');
            
            DB::commit();
            toastr()->success('تم اضافة موظف جديد بنجاح');
            return redirect()->intended(route('employees.index'));
        } catch (\Exception $e) {
            DB::rollBack();
            toastr()->error($e->getMessage());
            return redirect()->back();
        }
    }

    public function show(Employee $employee){
        if($employee){
            $roles = Role::all();
            return view('pages.employees.show', compact('employee', 'roles'));
        }else{
            toastr()->error('لم يتم العثور علي الموظف');
            return redirect()->back();
        }
    }
    
    public function edit(Employee $employee){
        //
    }
    
    public function changeRole(Request $request, Employee $employee){
        if($employee){
            $request->validate([
                'role_id' => ['required', 'numeric', 'exists:roles,id'],
            ]);
            
            try {
                DB::beginTransaction();
    
                $employee->update(['role_id' => $request->role_id]);
                
                logActivity(' قام الموظف بتغيير وظيفة موظف رقم '.$employee->id, 'الموظفين');
                
                DB::commit();
                toastr()->success('تم تعديل الوظيفة بنجاح');
                return redirect()->back();
            } catch (\Exception $e) {
                DB::rollBack();
                toastr()->error($e->getMessage());
                return redirect()->back();
            }
        }else{
            toastr()->error('لم يتم العثور علي الموظف');
            return redirect()->back();
        }
    }
    
    
    public function changeStatus(Employee $employee){
        $user = Employee::find(auth()->user()->id);
        if($user and $employee){
            if ($user->role->name == 'مالك' and $employee->role->name != 'مالك' and $user->id !== $employee->id){
                $employee->status = $employee->status == 'مفعل' ? 'غير مفعل' : 'مفعل';
                $employee->save();
                logActivity(' قام الموظف بتغيير حالة موظف رقم '.$employee->id, 'الموظفين');
                toastr()->success('تم تغير حالة الموظف بنجاح');
                return redirect()->back();
            }else{
                toastr()->error('غير ممسوح لك بتغير حالة هذا الموظف');
                return redirect()->back();
            }
        }else{
            toastr()->error('الموظف غير متوفر');
            return redirect()->back();
        }

    }

    public function update(Request $request, Employee $employee){
        //   
    }

    public function destroy(Employee $employee){
        //
    }
}