<?php

namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class LogActivityOnSessionExpire{
    public function handle(Request $request, Closure $next){
        if (Auth::check()) {
            $user = Auth::user();
            $lastActivity = session('last_activity_time');
            $sessionLifetime = config('session.lifetime') * 60; // Convert minutes to seconds
            $currentTime = Carbon::now()->timestamp;

            if ($lastActivity && ($currentTime - $lastActivity > $sessionLifetime)) {
                // Log user activity
                logActivity(' قام الموظف بتسجيل الخروج', 'المصادقة');

                // Log the user out
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
            }

            session(['last_activity_time' => $currentTime]);
        }

        return $next($request);
    }
}