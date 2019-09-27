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

//Route para obtener materias segun el estudiante
Route::get('/materias/estudiante/{id}', 'ApiController@getMateriasEstudiante');

Route::get('/respuesta/{id}','AreaController@respuesta');
Route::post('/respuesta/{id}','AreaController@respuesta');
Route::get('/evaluacion/{id}/duracion/','ApiController@getDuracionEvaluacion');

//Obtener evaluación
Route::get('/evaluacion/turno/{turno_id}/obtener/{estudiante_id}','ApiController@getEvaluacion');
Route::get('/encuesta/{encuesta_id}/{mac}','ApiController@getEncuesta');
Route::get('/evaluaciones_m/turnos_m/{id_carga}','ApiController@evaluacionTurnosDisponibles');
Route::get('/user/acceso/{email}/{password}','ApiController@accesoUserMovil');

//Clave
Route::get('/area/{id}/preguntas', 'ClaveController@preguntasPorArea');
Route::get('/area-emparejamiento/{id}/preguntas', 'ClaveController@preguntasPorAreaEmp');
Route::get('/preguntas-agregadas/{id}', 'ClaveController@preguntasAgregadas');
Route::get('/preguntas-agregadas-emp/{id}', 'ClaveController@preguntasAgregadasEmp');
Route::get('/clave-area/{id_clave_area}/validar-peso', 'ClaveController@validarPeso');


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
