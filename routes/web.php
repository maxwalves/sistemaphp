<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ControladorAv;
use App\Http\Controllers\ControladorProduto;
use App\Http\Controllers\ControladorCategoria;
use App\Http\Controllers\ControladorVeiculoProprio;
use App\Http\Controllers\ControladorObjetivoViagem;
use App\Http\Controllers\ControladorVeiculoParanacidade;
use App\Http\Controllers\ControladorRota;
use App\Http\Controllers\LocationController;

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

// ROTAS PARA OBJETIVOS
Route::get('/objetivos/objetivos', [ControladorObjetivoViagem::class, 'objetivos'])->middleware('auth');
Route::get('/objetivos/create', [ControladorObjetivoViagem::class, 'create'])->middleware('auth');
Route::get('/objetivos/{id}', [ControladorObjetivoViagem::class, 'show'])->middleware('auth');
Route::delete('/objetivos/{id}', [ControladorObjetivoViagem::class, 'destroy'])->middleware('auth');
Route::get('/objetivos/edit/{id}', [ControladorObjetivoViagem::class, 'edit'])->middleware('auth');
Route::put('/objetivos/update/{id}', [ControladorObjetivoViagem::class, 'update'])->middleware('auth');
Route::post('/objetivos', [ControladorObjetivoViagem::class,'store'])->middleware('auth');

// ROTAS PARA VEICULOS PARANACIDADE
Route::get('/veiculosParanacidade/veiculosParanacidade', [ControladorVeiculoParanacidade::class, 'veiculosParanacidade'])->middleware('auth');
Route::get('/veiculosParanacidade/create', [ControladorVeiculoParanacidade::class, 'create'])->middleware('auth');
Route::get('/veiculosParanacidade/{id}', [ControladorVeiculoParanacidade::class, 'show'])->middleware('auth');
Route::delete('/veiculosParanacidade/{id}', [ControladorVeiculoParanacidade::class, 'destroy'])->middleware('auth');
Route::get('/veiculosParanacidade/edit/{id}', [ControladorVeiculoParanacidade::class, 'edit'])->middleware('auth');
Route::put('/veiculosParanacidade/update/{id}', [ControladorVeiculoParanacidade::class, 'update'])->middleware('auth');
Route::post('/veiculosParanacidade', [ControladorVeiculoParanacidade::class,'store'])->middleware('auth');

// ROTAS PARA ROTAS
Route::get('/rotas/rotas', [ControladorRota::class, 'rotas'])->middleware('auth');
Route::get('/rotas/create', [ControladorRota::class, 'create'])->middleware('auth');
Route::get('/rotas/{id}', [ControladorRota::class, 'show'])->middleware('auth');
Route::delete('/rotas/{id}', [ControladorRota::class, 'destroy'])->middleware('auth');
Route::get('/rotas/edit/{id}', [ControladorRota::class, 'edit'])->middleware('auth');
Route::put('/rotas/update/{id}', [ControladorRota::class, 'update'])->middleware('auth');
Route::post('/rotas', [ControladorRota::class,'store'])->middleware('auth');


    Route::get('/countries', [LocationController::class, 'getCountries']);

    # Get a Country by its ID
    Route::get('/country/{id}', [LocationController::class, 'getCountry']);

    # Get all States
    Route::get('/states', [LocationController::class, 'getStates']);

    # Get a State by its ID
    Route::get('/state/{id}', [LocationController::class, 'getState']);

    # Get all States in a Country using the Country ID
    Route::get('states/{countryId}', [LocationController::class, 'getStatesByCountry']);

    # Get all Cities
    Route::get('cities', [LocationController::class, 'getCities']);

    # Get a City by its ID
    Route::get('city/{id}', [LocationController::class, 'getCity']);

    # Get all Cities in a State using the State ID
    Route::get('cities/{stateId}', [LocationController::class, 'getCitiesByStates']);

    # Get all Cities in a Country using the Country ID
    Route::get('country-cities/{countryId}', [LocationController::class, 'getCitiesByCountry']);
