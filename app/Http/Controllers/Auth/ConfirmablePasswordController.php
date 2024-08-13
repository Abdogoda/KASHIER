<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class ConfirmablePasswordController extends Controller
{
    /**
     * Show the confirm password view.
     */
    public function show(Request $request): View{

        $request->session()->put('previous_url', url()->previous());
        return view('auth.confirm-password');
    }

    public function store(Request $request): RedirectResponse{
        if (! Auth::guard('employee')->validate([
            'name' => $request->user()->name,
            'password' => $request->password,
        ])) {
            throw ValidationException::withMessages([
                'password' => __('كلمة المرور غير صحيحة'),
            ]);
        }

        $request->session()->put('auth.password_confirmed_at', time());

        toastr()->success('تم تأكيد كلمة المرور بنجاح');
        return redirect($request->session()->get('previous_url'));
    }
}