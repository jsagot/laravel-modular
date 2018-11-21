<?php

namespace Modules\Demo\Middleware;

use Closure;

class DemoMiddleware
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
        if ($request->demo != 'demo') {
            return redirect('/');
        }

        return $next($request);
    }
}
