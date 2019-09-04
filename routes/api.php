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

//Clave
Route::get('/area/{id}/preguntas', 'ClaveController@preguntasPorArea');
Route::get('/preguntas-agregadas/{id}', 'ClaveController@preguntasAgregadas');

//Consultar encuestas desde la app móvil
Route::get('/encuestas-disponibles', 'EncuestaController@encuestasDisponibles');

//Enviar respuestas desde la app móvil
Route::post('finalizar-intento/', 'IntentoController@finalizarIntento');

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
