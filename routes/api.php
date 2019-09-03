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
Route::post('/area/edit','AreaController@update')->name("area_update");
Route::post('/area/delete','AreaController@destroy')->name("area_delete");
Route::get('/evaluacion/{id}/duracion/','TurnoController@getDuracionEvaluacion');

//Obtener evaluación
Route::get('/evaluacion/turno/{turno_id}/obtener/{estudiante_id}','TurnoController@getEvaluacion');

//Clave
Route::get('/area/{id}/preguntas', 'ClaveController@preguntasPorArea');
Route::get('/preguntas-agregadas/{id}', 'ClaveController@preguntasAgregadas');

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
