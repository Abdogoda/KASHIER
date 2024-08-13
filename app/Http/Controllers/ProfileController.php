<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Employee;
use App\Rules\ValidPhoneRule;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{

    
    public function show(){
        $employee = Employee::find(auth()->user()->id);
        if($employee){
            return view('pages.profile', compact('employee'));
        }else{
            toastr()->error('لم يتم العثور علي حسابك');
            return redirect()->back();
        }
    }
    
    public function update(Request $request){
        $employee = Employee::find(auth()->user()->id);
        if($employee){
            $request->validate([
                'name' => ['required', 'string', 'max:255', 'unique:employees,name,'.$employee->id ?? 0],
                'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:employees,email,'.$employee->id ?? 0],
                'phone' => ['nullable', new ValidPhoneRule, 'unique:employees,phone,'.$employee->id ?? 0],
            ]);
            
            try {
                DB::beginTransaction();
                $attributes = $request->all();
    
                $employee->update($attributes);
                
                logActivity(' قام الموظف بتعديل بيانات حسابة رقم '.$employee->id, 'الموظفين');
                DB::commit();
                toastr()->success('تم تعديل البيانات بنجاح');
                return redirect()->back();
            } catch (\Exception $e) {
                DB::rollBack();
                toastr()->error($e->getMessage());
                return redirect()->back();
            }
        }else{
            toastr()->error('لم يتم العثور علي حسابك');
            return redirect()->back();
        }
    }

    
    public function destroy(Request $request): RedirectResponse{
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}