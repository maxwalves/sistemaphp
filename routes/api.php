<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ControladorAv;
use App\Http\Controllers\ControladorRota;
use App\Http\Controllers\ControladorObjetivoViagem;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\ControladorAnos;
use App\Http\Controllers\ControladorComponentes;
use App\Http\Controllers\ControladorSubcomponentes;
use App\Http\Controllers\ControladorCategoriaPeppoa;
use App\Http\Controllers\ControladorCategoriaPmr;
use App\Http\Controllers\ControladorPeppoaPmr;
use App\Http\Controllers\ControladorAnoPeppoaPmr;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/getAvsByUser/{id}',[ControladorAv::class, 'getAvsByUser']);
Route::get('/getAvsByManager/{id}',[ControladorAv::class, 'getAvsByManager']);
Route::get('/getAllRotas',[ControladorRota::class, 'getAllRotas']);

Route::get('/getAllObjetivos',[ControladorObjetivoViagem::class, 'getAllObjetivos']);
Route::get('/getAllUsers',[UsersController::class, 'getAllUsers']);

Route::resource('/avs', ControladorAv::class);

Route::resource('/componentes', ControladorComponentes::class);


Route::get('/findComponenteById/{id}',[ControladorComponentes::class, 'findComponenteById']);
Route::get('/getHorasExtrasByUser/{name}',[UsersController::class, 'getHorasExtrasByUser']);