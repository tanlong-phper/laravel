<?php

namespace App\Http\Middleware;

use Closure;

class UnsetParameter
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        //TODO :: 不知道哪里多出来的参数
        unset($request['s']);

        return $next($request);
    }
}
