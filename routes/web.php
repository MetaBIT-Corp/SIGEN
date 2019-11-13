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
Auth::routes();
Route::get('/', function () {
    return view('layouts.plantilla');
});
Route::get('/home', 'HomeController@index')->name('home');



//Rutas relacionadas con Clave Area
Route::get('random/', 'EvaluacionController@random');
Route::post('turno/claves', 'ClaveController@asignarPreguntas')->name('agregar_clave_area');
Route::post('clave-area/editar/', 'ClaveController@editarClaveArea')->name('editar_clave_area');
Route::post('clave-area/eliminar/', 'ClaveController@eliminarClaveArea')->name('eliminar_clave_area');
Route::get('clave-area/{clave_area_id}/preguntas','ClaveAreaController@listarPreguntas')->name('preguntas_por_area')->middleware('signed');

//Rutas relacionadas con Intento y Encuesta (Resolucion de encuesta y evaluacion)
Route::get('persistencia/', 'IntentoController@persistence');
Route::get('calificar/', 'IntentoController@calificacionEvaluacion')->name('calificar_evaluacion');
Route::get('recalificar/{id_intento}', 'IntentoController@recalificarEvaluacion')->name('recalificar_evaluacion');
Route::get('intento/{id_intento}','IntentoController@iniciarEvaluacion')->name('prueba');
Route::get('encuesta/{id_clave}','IntentoController@iniciarEncuesta')->name('prueba_encuesta');
Route::get('intento/revision/{id_intento}','IntentoController@revisionEvaluacion')->name('revision_evaluacion')->middleware('signed');
Route::get('intento/{intento_id}/respuestas','IntentoController@calcularNota')->name('calcular_nota');

//Rutas relacionadas con materias
Route::get('/materias', 'MateriaController@listar')->name('materias');
Route::get('/materias/listado_estudiante/{id}', 'EstudianteController@index')->name('listado_estudiante')->middleware('signed'); 
Route::get('/materia/estudiante/{id}/{id_mat}', 'EstudianteController@show')->name('detalle_estudiante')->middleware('signed');
Route::get('docentes-ciclo/{id_mat_ci}', 'DocenteController@docentes_materia_ciclo')->name('docentes_materia_ciclo')->middleware('signed');
Route::get('materia/listado-evaluacion/{id}','EvaluacionController@listado')->name('listado_evaluacion')->middleware('signed');

//Rutas relacionadas con encuestas
Route::get('/encuestas','EncuestaController@listado_publico')->name('encuestas'); 
Route::post('/encuestas','EncuestaController@acceso')->name('acceso_encuesta');

    
//Aqui iran las rutas a las que tiene acceso solo el Administrador
Route::group(['middleware' => 'admin'], function(){

});

//Aqui iran las rutas a las que tiene acceso solo el Docente
Route::group(['middleware' => 'teacher'], function(){

    //Rutas relacionadas con Evaluacion
    Route::get('/evaluacion/{id}', 'EvaluacionController@show')->name('detalle_evaluacion')->middleware('signed');;
    Route::resource('/evaluacion/{id}/turnos', 'TurnoController');

    //Rutas relacionadas con Turno
    Route::get('/evaluacion/{id}/turnos/create', 'TurnoController@create')->name('crear_turno')->middleware('signed');
    Route::get('/evaluacion/{id}/turnos/{turno_id}/edit', 'TurnoController@edit')->name('editar_turno')->middleware('signed');
    Route::resource('/evaluacion/{id}/turnos', 'TurnoController')->except(['index','create','edit']);
    Route::get('/evaluacion/{id}/turno/{id_turno}','TurnoController@duplicarTurno')->name('duplicar');

    //Rutas relacionadas con Area
    Route::get('/materia/{id}/areas/create','AreaController@create')->name('crear_area')->middleware('signed');
    Route::post('materia/{id}/areas','AreaController@store')->name('storeArea');
    Route::get('materia/{id}/areas','AreaController@index')->name('getAreaIndex')->middleware('signed');
    Route::post('materia/{id}/areas/post','AreaController@index')->name('postAreaIndex');
    Route::get('materia/{id}/areas/{id_area}/edit','AreaController@edit')->name('getArea');
    Route::put('materia/{id}/areas/{id_area}','AreaController@update')->name('putArea');
    Route::delete('materia/{id}/areas/{id_area}','AreaController@destroy')->name('deleteArea');
    Route::get('areas/encuestas', 'AreaController@indexEncuesta')->name('areas_encuestas');
    Route::post('areas/encuestas', 'AreaController@indexEncuesta')->name('post_areas_encuestas');

    
    //Rutas relacionadas con Pregunta
    Route::get('area/{id}/pregunta','PreguntaController@index')->name('getPreguntas')->middleware('signed');
    Route::post('area/{id}/pregunta','PreguntaController@index')->name('postPregunta');
    Route::post('area/{id}/pregunta/create','PreguntaController@store')->name('postPregunta');
    Route::get('area/{id}/pregunta/{id_preg}','PreguntaController@show')->name('showPregunta');
    Route::put('/area/{id}/pregunta/{pregunta}','PreguntaController@update');
    Route::delete('/area/{id}/pregunta/{pregunta}','PreguntaController@destroy');

    //Descarga de plantilla excel
    Route::get('download/excel/{id}/{id_area}','PreguntaController@downloadExcel')->name('dExcel')->middleware('signed');

    Route::post('upload-excel/{id_area}','PreguntaController@uploadExcel')->name('uExcel');

    //Descargar notas en formato Excel
    Route::get('notas/exportar/{evaluacion_id}/excel', 'EvaluacionController@exportarNotasExcel')->name('notasExcel');
    //Descargar notas en formato PDF
    Route::get('notas/exportar/{evaluacion_id}/pdf', 'EvaluacionController@exportarNotasPdf')->name('notasPdf');

    //Descargar resultados de encuesta en formato Excel
    Route::get('resultados/exportar/{encuesta_id}/excel', 'EncuestaController@exportarResultadosExcel')->name('resultadosExcel');
    //Descargar resultados de encuesta en formato PDF
    Route::get('resultados/exportar/{encuesta_id}/pdf', 'EncuestaController@exportarResultadosPdf')->name('resultadosPdf');

    //Rutas relacionadas con Grupo Emparejamiento CRUD
    Route::post('grupo/{grupo_id}/edit','GrupoEmparejamientoController@updateGE')->name('editar-grupo');
    Route::post('area/{id}/grupo-store','GrupoEmparejamientoController@storeGE')->name('crear-grupo-emparejamiento');
    Route::post('area/{id}/grupo-edit','GrupoEmparejamientoController@editGE')->name('editar-grupo-emparejamiento');
    Route::post('area/{id}/grupo-delete/','GrupoEmparejamientoController@destroyGE')->name('eliminar-grupo-emparejamiento');


     //URL's para  evaluacion
    Route::get('materia/evaluacion/{id}/nuevo','EvaluacionController@getCreate')->name('gc_evaluacion')->middleware('signed');
    Route::post('materia/evaluacion/{id}/nuevo','EvaluacionController@postCreate')->name('pc_evaluacion');
    Route::get('materia/evaluacion/{id_eva}/editar','EvaluacionController@getUpdate')->name('gu_evaluacion')->middleware('signed');
    Route::post('materia/evaluacion/{id_eva}/editar','EvaluacionController@postUpdate')->name('pu_evaluacion');
    Route::post('/deshabilitar-evaluacion','EvaluacionController@deshabilitarEvaluacion')->name('deshabilitar_evaluacion');
    Route::post('/habilitar-evaluacion','EvaluacionController@habilitar')->name('habilitar_evaluacion');
    Route::get('materia/habilitar-evaluacion/{id}','EvaluacionController@reciclaje')->name('reciclaje_evaluacion')->middleware('signed');
    Route::post('evaluacion/publicar-turno','EvaluacionController@publicar')->name('publicar_evaluacion');
    
    Route::get('evaluacion/{evaluacion_id}/estudiantes', 'EstudianteController@estudiantesEnEvaluacion')
            ->name('estudiantes_en_evaluacion')
            ->middleware('signed');

    Route::get('evaluacion/{evaluacion_id}/estadisticos', 'EvaluacionController@estadisticosEvaluacion')
            ->name('estadisticas_evaluacion')
            ->middleware('signed');

    Route::post('/deshabilitar-revision','IntentoController@deshabilitarRevision')->name('deshabilitar_revision');
    Route::post('/habilitar-revision','IntentoController@habilitarRevision')->name('habilitar_revision');


    //URL's para  encuesta
    Route::get('/encuesta','EncuestaController@getCreate')->name('gc_encuesta')->middleware('signed');
    Route::post('/encuesta','EncuestaController@postCreate')->name('pc_encuesta');
    Route::get('/encuesta/{id}/editar','EncuestaController@getUpdate')->name('gu_encuesta')->middleware('signed');;
    Route::post('/encuesta/{id}/editar','EncuestaController@postUpdate')->name('pu_encuesta');    
    Route::post('/eliminar-encuesta','EncuestaController@eliminarEncuesta')->name('eliminar_encuesta'); 
    Route::post('/encuesta/publicar-encuesta','EncuestaController@publicar')->name('publicar_encuesta');

    //Encuestas estadísticas
    Route::get('estadisticas/{id}', 'EncuestaController@estadisticas')->name('estadisticas_enc')->middleware('signed'); 

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
    Route::post('encuesta/{encuesta_id}/claves/store','ClaveAreaController@storeAreaEncuesta')->name('asignar-area-encuesta'); 
    
});

//Aqui iran las rutas a las que tiene acceso solo el Estudiante
Route::group(['middleware' => 'student'], function(){
    Route::post('evaluacion/acceso','EvaluacionController@acceso')->name('acceso_evaluacion');

});

//Aqui iran las rutas a las que tiene acceso solamente el docente y el admin
Route::group(['middleware' => 'admin_teacher'], function(){
    Route::get('/evaluacion/{id}/turnos', 'TurnoController@index')->name('listado_turnos')->middleware('signed');
    Route::get('/listado-encuesta','EncuestaController@listado')->name('listado_encuesta')->middleware('signed');
});

Route::get('/evaluacion/{evaluacion_id}/estadisticos/porcentajes', 'EvaluacionController@getPorcentajeAprovadosReprobados');
Route::get('/evaluacion/{evaluacion_id}/estadisticos/intervalo/{intervalo}', 'EvaluacionController@getIntervalosNotas');