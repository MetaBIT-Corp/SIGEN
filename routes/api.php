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
Route::post('/area/edit','AreaController@update')->name("area_update");
Route::post('/area/delete','AreaController@destroy')->name("area_delete");
Route::get('/evaluacion/{id}/duracion/','TurnoController@getDuracionEvaluacion');
Route::get('/area/{id}/preguntas', 'ClaveController@preguntas_por_area');

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
