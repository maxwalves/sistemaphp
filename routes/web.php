<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ControladorAv;
use App\Http\Controllers\ControladorProduto;
use App\Http\Controllers\ControladorCategoria;
use App\Http\Controllers\ControladorVeiculoProprio;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
use App\Http\Controllers\EventController;

// ROTAS PARA AVS
Route::get('/', [ControladorAv::class, 'index'])->middleware('auth');
Route::get('/avs/avs', [ControladorAv::class, 'avs'])->middleware('auth');
Route::get('/avs/create', [ControladorAv::class, 'create'])->middleware('auth');
Route::get('/avs/{id}', [ControladorAv::class, 'show'])->middleware('auth');

Route::post('/avs', [ControladorAv::class,'store'])->middleware('auth');

Route::delete('/avs/{id}', [ControladorAv::class, 'destroy'])->middleware('auth');
Route::get('/avs/edit/{id}', [ControladorAv::class, 'edit'])->middleware('auth');
Route::put('/avs/update/{id}', [ControladorAv::class, 'update'])->middleware('auth');

//---------------------------------------------------------------- TESTES
Route::get('/contact', function () {
    return view('contact');
});

Route::get('/dashboard', [ControladorAv::class, 'dashboard'])->middleware('auth');

Route::post('/avs/join/{id}', [EventController::class, 'joinEvent'])->middleware('auth');

//----------------------------------------------------------------

// ROTAS PARA VEICULO PRÓPRIO
Route::get('/veiculosProprios/veiculosProprios', [ControladorVeiculoProprio::class, 'veiculosProprios'])->middleware('auth');
Route::get('/veiculosProprios/create', [ControladorVeiculoProprio::class, 'create'])->middleware('auth');
Route::get('/veiculosProprios/{id}', [ControladorVeiculoProprio::class, 'show'])->middleware('auth');
Route::delete('/veiculosProprios/{id}', [ControladorVeiculoProprio::class, 'destroy'])->middleware('auth');
Route::get('/veiculosProprios/edit/{id}', [ControladorVeiculoProprio::class, 'edit'])->middleware('auth');
Route::put('/veiculosProprios/update/{id}', [ControladorVeiculoProprio::class, 'update'])->middleware('auth');
Route::post('/veiculosProprios', [ControladorVeiculoProprio::class,'store'])->middleware('auth');