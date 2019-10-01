<?php

namespace App\Providers;

use View;
use App\Ciclo;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;



class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

        $ciclo_activo = Ciclo::where('estado',1)->first();
        View::share('ciclo_activo', $ciclo_activo);
        Schema::defaultStringLength(191);
        //Carbon para fechas en español
        Carbon::setLocale('es');
        Carbon::setUTF8(true);
        setlocale(LC_TIME, 'es_ES');
    }
}
