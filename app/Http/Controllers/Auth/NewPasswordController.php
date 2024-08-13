<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class NewPasswordController extends Controller
{
    /**
     * Display the password reset view.
     */
    public function create(Request $request): View
    {
        return view('auth.reset-password', ['request' => $request]);
    }

    /**
     * Handle an incoming new password request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse{
        $request->validate([
            'token' => 'required',
            'email' => 'required|email|exists:employees,email',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $updated_password = DB::table('password_resets')->where([
            'email' => $request->email,
            'token' => $request->token
        ])->first();
        
        if(!$updated_password){
            toastr()->error('الحقول المضافة غير صحيحة, أو انتهت صلاحية الرابط');
            return redirect()->back();
        }
        Employee::where('email', $request->email)->update(['password' => Hash::make($request->password)]);

        DB::table('password_resets')->where([
            'email' => $request->email,
        ])->delete();

        toastr()->success('تم تغيير كلمة المرور الخاصة بك بنجاح, يمكنك تسجيل الدخول الان بكلمة المرور الجديدة');
        return redirect()->route('login');
    }
}