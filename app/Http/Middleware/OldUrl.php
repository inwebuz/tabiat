<?php

namespace App\Http\Middleware;

use Closure;

class OldUrl
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (true) {
            echo url()->current();
        }
        return $next($request);
    }
}
