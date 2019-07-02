<?php

use App\Docente;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/insertar', function () {

	/*
	$docente = new Docente;
	$docente->id_pdg_dcn= 2;
	$docente->id=1;
	$docente->carnet_dcn='AA99999';
	$docente->anio_titulo='2000';
	$docente->activo=1;
	$docente->tipo_jornada=1;
	$docente->descripcion_docente='Un crack';
	$docente->id_cargo_actual=1;
	$docente->id_segundo_cargo=1;
	$docente->nombre_docente='Rudy Chicas';
	$docente->save();
	
    DB::insert("INSERT INTO pdg_dcn_docente 
    	(id_pdg_dcn,id,carnet_dcn,anio_titulo,activo, tipo_jornada,descripcion_docente,id_cargo_actual,id_segundo_cargo,nombre_docente)
		VALUES(?,?,?,?,?,?,?,?,?,?)",[1,1,'AA99999','2000',1,1,'Buen docente',1,1,'El Amo']);
		*/
});