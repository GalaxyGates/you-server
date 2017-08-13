<?php

namespace hiahia\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class MobileAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (Auth::check()) {
            return $next($request);
        } else {
            return response()->json(['status' => 0, 'error' => 0, 'message' => '未登录的用户']);
        }
    }
}
