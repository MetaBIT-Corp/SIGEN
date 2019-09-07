<?php

namespace App\Http\Middleware;

use Closure;

class AdminTeacherMiddleware
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
            
        if(auth()->user()->role != 0 && auth()->user()->role != 1) //No es administrador y NO es docente
            return redirect('materias');

        return $next($request);
       
    }
}
