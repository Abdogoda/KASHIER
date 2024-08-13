<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    public function create(): View{
        return view('auth.login');
    }

    public function store(LoginRequest $request): RedirectResponse{
        try {
            $request->authenticate();

            $request->session()->regenerate();

            logActivity(' قام الموظف بتسجيل الدخول', 'المصادقة');

            toastr()->success('مرحبا بك, تم تسجيل الدخول بنجاح');
            return redirect()->intended(RouteServiceProvider::HOME);
        } catch (\Exception $e) {
            toastr()->error($e->getMessage());
            return redirect()->back()->withInput();
        }
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse{
        logActivity(' قام الموظف بتسجيل الخروج', 'المصادقة');
        Auth::guard('employee')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}