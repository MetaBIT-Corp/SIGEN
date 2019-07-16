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
	if(!auth()->check())
            return redirect('/login');

    if(auth()->user()->role != 1) //No es Docente
        return redirect('materias');

    return $next($request);
    }
}
