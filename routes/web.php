<?php

use App\Docente;
use App\Area;
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

//Rutas de pruebas

/*----------------------------Rutas de prueba para agregar preguntas a la clave------------------------------------*/
#Route::post('turno/{id_turno}/claves', 'ClaveController@asignarPreguntas')->name('agregar_clave_area');
Route::post('turno/claves', 'ClaveController@asignarPreguntas')->name('agregar_clave_area');
Route::post('clave-area/editar/', 'ClaveController@editarClaveArea')->name('editar_clave_area');
Route::post('clave-area/eliminar/', 'ClaveController@eliminarClaveArea')->name('eliminar_clave_area');
Route::get('clave-area/{clave_area_id}/preguntas','ClaveAreaController@listarPreguntas')->name('preguntas_por_area')->middleware('signed');
Route::get('intento/{intento_id}/respuestas','IntentoController@calcularNota');
/*-----------------------------------------------------------------------------------------------------------------*/

Route::get('intento/', function() {
	echo "<a href='intento/prueba/1?page=1'>Link para iniciar intento</a>";
});

Route::get('intento/prueba/{id_intento}','IntentoController@iniciarEvaluacion')->name('prueba');

Route::get('/', function () {
    return view('layouts.plantilla');
});

//Plantillas y ejemplo.
/*
Route::get('plantilla/', function () {
    return view('layouts.plantilla');
});
*/

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



//Rutas Funcionales
Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/materias', 'MateriaController@listar')->name('materias');

Route::get('/materias/listado_estudiante/{id}', 'EstudianteController@index')->name('listado_estudiante'); 
//se envia como parametro o el id de materia ciclo 
Route::get('/materia/estudiante/{id}/{id_mat}', 'EstudianteController@show')->name('detalle_estudiante');

Route::get('docentes-ciclo/{id_mat_ci}', 'DocenteController@docentes_materia_ciclo')->name('docentes_materia_ciclo')->middleware('signed');

Route::get('materia/listado-evaluacion/{id}','EvaluacionController@listado')->name('listado_evaluacion');

Route::get('/encuestas','EncuestaController@listado_publico')->name('encuestas'); 

    

//Aqui iran las rutas a las que tiene acceso solo el Administrador
Route::group(['middleware' => 'admin'], function(){

});

//Aqui iran las rutas a las que tiene acceso solo el Docente
Route::group(['middleware' => 'teacher'], function(){

    Route::get('/evaluacion/{id}', 'EvaluacionController@show')->name('detalle_evaluacion');

    Route::resource('/evaluacion/{id}/turnos', 'TurnoController');


    //URL's para Turno
    Route::get('/evaluacion/{id}/turnos', 'TurnoController@index')->name('listado_turnos')->middleware('signed');
    Route::get('/evaluacion/{id}/turnos/create', 'TurnoController@create')->name('crear_turno')->middleware('signed');
    Route::get('/evaluacion/{id}/turnos/{turno_id}/edit', 'TurnoController@edit')->name('editar_turno')->middleware('signed');
    Route::resource('/evaluacion/{id}/turnos', 'TurnoController')->except(['index','create','edit']); 


    //URL's para Area
    Route::get('/materia/{id}/areas/create','AreaController@create')->name('crear_area')->middleware('signed');
    Route::resource('materia/{id}/areas','AreaController')->except(['create']);

    
    //URL's para Pregunta
    Route::resource('/area/{id}/pregunta','PreguntaController')->except(['update']);
    Route::post('/area/{id}/pregunta/{pregunta}','PreguntaController@update');

     //URL's para crear evaluacion
    Route::get('materia/evaluacion/{id}/nuevo','EvaluacionController@getCreate')->name('gc_evaluacion');
    Route::post('materia/evaluacion/{id}/nuevo','EvaluacionController@postCreate')->name('pc_evaluacion');
    Route::get('materia/evaluacion/{id_eva}/editar','EvaluacionController@getUpdate')->name('gu_evaluacion');
    Route::post('materia/evaluacion/{id_eva}/editar','EvaluacionController@postUpdate')->name('pu_evaluacion');


    //URL's para crear encuesta
    Route::get('/encuesta','EncuestaController@getCreate')->name('gc_encuesta');
    Route::post('/encuesta','EncuestaController@postCreate')->name('pc_encuesta');
    Route::get('/encuesta/{id}/editar','EncuestaController@getUpdate')->name('gu_encuesta');
    Route::post('/encuesta/{id}/editar','EncuestaController@postUpdate')->name('pu_encuesta');    
    Route::post('/eliminar-encuesta','EncuestaController@eliminarEncuesta')->name('eliminar_encuesta');   
    
});

//Aqui iran las rutas a las que tiene acceso solo el Estudiante
Route::group(['middleware' => 'student'], function(){
});

//Aqui iran las rutas a las que tiene acceso solamente el docente y el admin
Route::group(['middleware' => 'admin_teacher'], function(){
    Route::get('/listado-encuesta','EncuestaController@listado')->name('listado_encuesta');
});



/*Rutas para Gestión de Opciones (Sin Grupo Emparejamiento)*/
Route::get('pregunta/{pregunta_id}/opcion/','OpcionController@index')->name('index-opcion');
Route::post('pregunta/{pregunta_id}/opcion/store','OpcionController@store')->name('agregar-opcion');
Route::post('pregunta/{pregunta_id}/opcion/update','OpcionController@update')->name('actualizar-opcion');
Route::post('pregunta/{pregunta_id}/opcion/delete','OpcionController@destroy')->name('eliminar-opcion');

/*Rutas para Gestión Grupo Emparejamiento*/
Route::get('grupo/{grupo_id}/preguntas/','GrupoEmparejamientoController@index')->name('list-preguntas');
Route::post('grupo/{grupo_id}/preguntas/store','GrupoEmparejamientoController@store')->name('crear-pregunta-grupo');
Route::post('grupo/{grupo_id}/preguntas/update','GrupoEmparejamientoController@update')->name('editar-pregunta-grupo');
Route::post('grupo/{grupo_id}/preguntas/delete','GrupoEmparejamientoController@destroy')->name('eliminar-pregunta-grupo');

/*Rutas para Asignación de Áreas a Clave/Turno*/
Route::post('turno/{turno_id}/claves/store','ClaveAreaController@store')->name('asignar-area-clave');