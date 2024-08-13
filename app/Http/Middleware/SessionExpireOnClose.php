<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SessionExpireOnClose{
    
    public function handle(Request $request, Closure $next){
        config(['session.expire_on_close' => true]);
        return $next($request);
    }
}