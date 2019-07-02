<?php

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


//Plantillas y ejemplo.

Route::get('plantilla/', function () {
    return view('layout.plantilla');
});

Route::get('plantillafull/', function () {
    return view('layout.plantilla_llena');
});

Route::get('/ejemplo/{nombre}', function ($nombre) {
    return view('layout.ejemplo',['nombre'=>$nombre]);
});