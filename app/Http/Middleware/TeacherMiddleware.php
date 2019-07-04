<?php

namespace App\Http\Middleware;

use Closure;

class TeacherMiddleware
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
        if(auth()->user()->role != 1) //No es Docente
            return redirect('home');

        return $next($request);
    }
}
