<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission{
    public function handle($request, Closure $next, $permission){
        $user = Auth::user();

        if ($user && $this->hasPermission($user, $permission)) {
            return $next($request);
        }

        toastr()->error('ليس لديك الصلاحيات اللازمة لدخول هذه الصفحة.');
        return redirect('/');
    }

    private function hasPermission($user, $permission){
        return $user->role->permissions->pluck('en_name')->contains($permission);
    }
}