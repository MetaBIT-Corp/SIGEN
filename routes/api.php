<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::get('/respuesta/{id}','AreaController@respuesta');
Route::post('/respuesta/{id}','AreaController@respuesta');
Route::get('/evaluacion/{id}/duracion/','TurnoController@getDuracionEvaluacion');

//Obtener evaluación
Route::get('/evaluacion/turno/{turno_id}/obtener/{estudiante_id}','TurnoController@getEvaluacion');
Route::get('/encuesta/{encuesta_id}/{mac}','TurnoController@getEncuesta');
Route::get('/evaluaciones_m/turnos_m/{id_carga}','EvaluacionController@evaluacionTurnosDisponibles');
Route::get('/user/acceso/{email}/{password}','EvaluacionController@accesoUserMovil');
//Clave
Route::get('/area/{id}/preguntas', 'ClaveController@preguntasPorArea');
Route::get('/area-emparejamiento/{id}/preguntas', 'ClaveController@preguntasPorAreaEmp');
Route::get('/preguntas-agregadas/{id}', 'ClaveController@preguntasAgregadas');
Route::get('/preguntas-agregadas-emp/{id}', 'ClaveController@preguntasAgregadasEmp');

//Turno
Route::get('/evaluacion/{id}/turnos', 'TurnoController@turnosPorEvaluacion');


//Consultar encuestas desde la app móvil
Route::get('/encuestas-disponibles', 'ApiController@encuestasDisponibles');

//Enviar respuestas desde la app móvil
Route::post('/finalizar-intento', 'IntentoController@finalizarIntentoMovil');
Route::get('/calcular-nota/{intento_id}', 'IntentoController@calcularNota');

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
