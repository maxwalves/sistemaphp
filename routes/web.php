<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ControladorAv;
use App\Http\Controllers\ControladorProduto;
use App\Http\Controllers\ControladorCategoria;
use App\Http\Controllers\ControladorVeiculoProprio;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\ControladorObjetivoViagem;
use App\Http\Controllers\ControladorVeiculoParanacidade;
use App\Http\Controllers\ControladorRota;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\SetorController;
use App\Http\Controllers\RelatorioController;
use App\Http\Controllers\ArquivosController;
use App\Http\Controllers\DssController;

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


//EMAIL


Route::get('/termoResponsabilidade', [UsersController::class, 'termoResponsabilidade'])->name('termoResponsabilidade');
Route::put('/aprovarTermoResponsabilidade', [UsersController::class, 'aprovarTermoResponsabilidade'])->middleware('auth');


Route::middleware(['assinatura.termo'])->group(function () {

    Route::get('/recuperaArquivo/{name}/{id}/{pasta}/{anexoRelatorio}', [UsersController::class, 'recuperaArquivo'])->name('recuperaArquivo');
    Route::get('/impersonate/{id}', [UsersController::class, 'impersonate'])->name('impersonate');
    Route::get('/email/envioEmail', [ControladorAv::class, 'envioEmail'])->middleware('auth');
// ROTAS PARA AVS
    Route::get('/', [ControladorAv::class, 'index'])->middleware('auth');
    Route::get('/avs/avs', [ControladorAv::class, 'avs'])->middleware('auth');
    Route::get('/avs/create', [ControladorAv::class, 'create'])->middleware('auth');
    Route::get('/avs/show/{id}', [ControladorAv::class, 'show'])->middleware('auth');

    Route::post('/avs', [ControladorAv::class,'store'])->middleware('auth');
    Route::post('/avs/gravarAv', [ControladorAv::class,'store'])->middleware('auth');

    Route::delete('/avs/{id}', [ControladorAv::class, 'destroy'])->middleware('auth');
    Route::get('/avs/edit/{id}', [ControladorAv::class, 'edit'])->middleware('auth');
    Route::put('/avs/update/{id}', [ControladorAv::class, 'update'])->middleware('auth');
    Route::put('/avs/marcarComoCancelado/{id}', [ControladorAv::class, 'marcarComoCancelado'])->middleware('auth');
    Route::put('/avs/enviarGestor/{id}', [ControladorAv::class, 'enviarGestor'])->middleware('auth');
    Route::get('/avs/concluir/{id}/{isPc}', [ControladorAv::class, 'concluir'])->middleware('auth');
    Route::get('/avs/fluxo/{id}', [ControladorAv::class, 'verFluxo'])->middleware('auth');
    Route::get('/avs/verFluxoGestor/{id}', [ControladorAv::class, 'verFluxoGestor'])->middleware('auth');
    Route::get('/avs/voltarAv/{id}', [ControladorAv::class, 'voltarAv'])->middleware('auth');

    Route::get('/avs/autGestor', [ControladorAv::class, 'autGestor'])->middleware('auth');
    Route::put('/avs/gestorAprovarAv', [ControladorAv::class, 'gestorAprovarAv'])->middleware('auth');
    Route::put('/avs/gestorReprovarAv', [ControladorAv::class, 'gestorReprovarAv'])->middleware('auth');
    Route::get('/avs/verDetalhesAv/{id}', [ControladorAv::class, 'verDetalhesAv'])->middleware('auth');
    Route::get('/avs/verDetalhesPc/{id}', [ControladorAv::class, 'verDetalhesPc'])->middleware('auth');
    Route::get('/avs/verPaginaDevolucaoPc/{id}', [ControladorAv::class, 'verPaginaDevolucaoPc'])->middleware('auth');
    Route::get('/avs/verDetalhesAvGerenciar/{id}', [ControladorAv::class, 'verDetalhesAvGerenciar'])->middleware('auth');

    Route::get('/avs/autDiretoria', [ControladorAv::class, 'autDiretoria'])->middleware('auth');
    Route::get('/avs/verFluxoDiretoria/{id}', [ControladorAv::class, 'verFluxoDiretoria'])->middleware('auth');
    Route::put('/avs/diretoriaAprovarAv', [ControladorAv::class, 'diretoriaAprovarAv'])->middleware('auth');
    Route::put('/avs/diretoriaReprovarAv', [ControladorAv::class, 'diretoriaReprovarAv'])->middleware('auth');

    Route::get('/avs/autSecretaria', [ControladorAv::class, 'autSecretaria'])->middleware('auth');
    Route::get('/avs/verFluxoSecretaria/{id}', [ControladorAv::class, 'verFluxoSecretaria'])->middleware('auth');
    Route::get('/avs/realizarReservas/{id}', [ControladorAv::class, 'realizarReservas'])->middleware('auth');
    Route::post('/avs/gravarReservaHotel', [ControladorAv::class,'gravarReservaHotel'])->middleware('auth');
    Route::post('/avs/gravarReservaTransporte', [ControladorAv::class,'gravarReservaTransporte'])->middleware('auth');
    Route::delete('/avs/deletarAnexoHotel/{id}/{rota}', [ControladorAv::class, 'deletarAnexoHotel'])->middleware('auth');
    Route::delete('/avs/deletarAnexoTransporte/{id}/{rota}', [ControladorAv::class, 'deletarAnexoTransporte'])->middleware('auth');
    Route::put('/avs/secretariaAprovarAv', [ControladorAv::class, 'secretariaAprovarAv'])->middleware('auth');
    Route::put('/avs/secretariaReprovarAv', [ControladorAv::class, 'secretariaReprovarAv'])->middleware('auth');

    Route::get('/avs/autFinanceiro', [ControladorAv::class, 'autFinanceiro'])->middleware('auth');
    Route::get('/avs/verFluxoFinanceiro/{id}', [ControladorAv::class, 'verFluxoFinanceiro'])->middleware('auth');
    Route::post('/avs/gravarAdiantamento', [ControladorAv::class,'gravarAdiantamento'])->middleware('auth');
    Route::delete('/avs/deletarAnexoFinanceiro/{id}/{avId}', [ControladorAv::class, 'deletarAnexoFinanceiro'])->middleware('auth');
    Route::put('/avs/financeiroAprovarAv', [ControladorAv::class, 'financeiroAprovarAv'])->middleware('auth');
    Route::put('/avs/financeiroReprovarAv', [ControladorAv::class, 'financeiroReprovarAv'])->middleware('auth');

    Route::get('/avs/autAdmFrota', [ControladorAv::class, 'autAdmFrota'])->middleware('auth');
    Route::get('/avs/verFluxoAdmFrota/{id}', [ControladorAv::class, 'verFluxoAdmFrota'])->middleware('auth');
    Route::get('/avs/escolherVeiculo/{rota}/{veiculo}', [ControladorAv::class, 'escolherVeiculo'])->middleware('auth');
    Route::put('/avs/admFrotaAprovarAv', [ControladorAv::class, 'admFrotaAprovarAv'])->middleware('auth');
    Route::put('/avs/admFrotaReprovarAv', [ControladorAv::class, 'admFrotaReprovarAv'])->middleware('auth');


    Route::get('/avs/prestacaoContasUsuario', [ControladorAv::class, 'prestacaoContasUsuario'])->middleware('auth');
    Route::get('/avs/fazerPrestacaoContas/{id}', [ControladorAv::class, 'fazerPrestacaoContas'])->middleware('auth');
    Route::get('/avs/gerenciarAvs', [ControladorAv::class, 'gerenciarAvs'])->middleware('auth');
    Route::get('/avs/gerenciarAvsRh', [ControladorAv::class, 'gerenciarAvsRh'])->middleware('auth');

    //Prestação de contas
    Route::get('/avspc/edit/{id}', [ControladorAv::class, 'editAvPc'])->middleware('auth');
    Route::get('/rotaspc/rotas/{id}', [ControladorRota::class, 'rotaspc'])->middleware('auth');
    Route::get('/rotaspc/create/{id}', [ControladorRota::class, 'createpc'])->middleware('auth');
    Route::post('/rotaspc', [ControladorRota::class,'store'])->middleware('auth');
    Route::delete('/rotaspc/{id}', [ControladorRota::class, 'destroyRotaPc'])->middleware('auth');
    Route::get('/rotaspc/edit/{id}', [ControladorRota::class, 'editRotaPc'])->middleware('auth');
    Route::put('/rotaspc/update/{id}', [ControladorRota::class, 'update'])->middleware('auth');
    Route::get('/avspc/concluir/{id}/{isPc}', [ControladorAv::class, 'concluir'])->middleware('auth');

    Route::get('/avs/relatorio-pdf/{id}', [RelatorioController::class, 'gerarRelatorioPDF']);
    Route::get('/avs/relatorioPdfAv/{id}', [RelatorioController::class, 'gerarRelatorioPDFAv']);
    Route::get('/avs/relatorioPdfAcertoContas/{id}', [RelatorioController::class, 'gerarRelatorioPDFAcertoContas']);
    Route::get('/avs/relatorio/{id}', [RelatorioController::class, 'abrirPagina']);
    Route::post('/avs/gravarComprovante', [ControladorAv::class,'gravarComprovante'])->middleware('auth');
    Route::delete('/avs/deletarComprovante/{id}/{avId}', [ControladorAv::class, 'deletarComprovante'])->middleware('auth');
    Route::put('/avs/usuarioEnviarPrestacaoContas', [ControladorAv::class, 'usuarioEnviarPrestacaoContas'])->middleware('auth');

    Route::get('/avs/autPcFinanceiro', [ControladorAv::class, 'autPcFinanceiro'])->middleware('auth');
    Route::get('/avs/avaliarPcFinanceiro/{id}', [ControladorAv::class, 'avaliarPcFinanceiro'])->middleware('auth');
    Route::put('/avs/financeiroAprovaPrestacaoContas', [ControladorAv::class, 'financeiroAprovaPrestacaoContas'])->middleware('auth');
    Route::put('/avs/financeiroReprovaPrestacaoContas', [ControladorAv::class, 'financeiroReprovaPrestacaoContas'])->middleware('auth');

    Route::get('/avs/autPcGestor', [ControladorAv::class, 'autPcGestor'])->middleware('auth');
    Route::get('/avs/avaliarPcGestor/{id}', [ControladorAv::class, 'avaliarPcGestor'])->middleware('auth');
    Route::put('/avs/gestorAprovaPrestacaoContas', [ControladorAv::class, 'gestorAprovaPrestacaoContas'])->middleware('auth');
    Route::put('/avs/gestorReprovaPrestacaoContas', [ControladorAv::class, 'gestorReprovaPrestacaoContas'])->middleware('auth');
    Route::get('/avs/acertoContasFinanceiro', [ControladorAv::class, 'acertoContasFinanceiro'])->middleware('auth');
    Route::get('/avs/realizarAcertoContasFinanceiro/{id}', [ControladorAv::class, 'realizarAcertoContasFinanceiro'])->middleware('auth');
    Route::post('/avs/gravarComprovanteAcertoContas', [ControladorAv::class,'gravarComprovanteAcertoContas'])->middleware('auth');
    Route::post('/avs/gravarComprovanteAcertoContasUsuario', [ControladorAv::class,'gravarComprovanteAcertoContasUsuario'])->middleware('auth');
    Route::post('/avs/gravarComprovanteDevolucaoUsuario', [ControladorAv::class,'gravarComprovanteDevolucaoUsuario'])->middleware('auth');
    Route::delete('/avs/deletarComprovanteAcertoContas/{id}/{avId}', [ControladorAv::class, 'deletarComprovanteAcertoContas'])->middleware('auth');
    Route::delete('/avs/deletarComprovanteAcertoContasUsuario/{id}/{avId}', [ControladorAv::class, 'deletarComprovanteAcertoContasUsuario'])->middleware('auth');
    Route::delete('/avs/deletarComprovanteDevolucaoUsuario/{id}/{avId}', [ControladorAv::class, 'deletarComprovanteDevolucaoUsuario'])->middleware('auth');
    Route::put('/avs/financeiroRealizaAcertoContas', [ControladorAv::class, 'financeiroRealizaAcertoContas'])->middleware('auth');

    Route::get('/avs/validarAcertoContasUsuario/{id}', [ControladorAv::class, 'validarAcertoContasUsuario'])->middleware('auth');
    Route::put('/avs/usuarioAprovarAcertoContas', [ControladorAv::class, 'usuarioAprovarAcertoContas'])->middleware('auth');
    Route::put('/avs/enviarComprovanteDevolucaoParaCFI', [ControladorAv::class, 'enviarComprovanteDevolucaoParaCFI'])->middleware('auth');
    Route::put('/avs/usuarioReprovarAcertoContas', [ControladorAv::class, 'usuarioReprovarAcertoContas'])->middleware('auth');

    Route::get('/avs/cancelarAv/{id}', [ControladorAv::class, 'cancelarAv'])->middleware('auth');

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
    Route::get('/rotas/rotas/{id}', [ControladorRota::class, 'rotas'])->middleware('auth');
    Route::get('/rotas/rotasEditData/{id}', [ControladorRota::class, 'rotasEditData'])->middleware('auth');
    Route::get('/rotas/create/{id}', [ControladorRota::class, 'create'])->middleware('auth');
    Route::get('/rotas/{id}', [ControladorRota::class, 'show'])->middleware('auth');
    Route::delete('/rotas/{id}', [ControladorRota::class, 'destroy'])->middleware('auth');
    Route::get('/rotas/edit/{id}', [ControladorRota::class, 'edit'])->middleware('auth');
    Route::get('/rotas/editNovaData/{id}', [ControladorRota::class, 'editNovaData'])->middleware('auth');
    Route::put('/rotas/update/{id}', [ControladorRota::class, 'update'])->middleware('auth');
    Route::put('/rotas/updateData/{id}', [ControladorRota::class, 'updateData'])->middleware('auth');
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


        // ROTAS PARA ADM USERS
    Route::get('/users/users', [UsersController::class, 'users'])->middleware('auth');
    Route::get('/users/sincronizarGerentes', [UsersController::class, 'sincronizarGerentes'])->middleware('auth');
    Route::get('/users/sincronizarSetores', [UsersController::class, 'sincronizarSetores'])->middleware('auth');
    Route::get('/users/create', [UsersController::class, 'create'])->middleware('auth');
    Route::get('/users/{id}', [UsersController::class, 'show'])->middleware('auth');
    Route::delete('/users/{id}', [UsersController::class, 'destroy'])->middleware('auth');
    Route::get('/users/edit/{id}', [UsersController::class, 'edit'])->middleware('auth');

    Route::get('/users/editPerfil/{id}', [UsersController::class, 'editPerfil'])->middleware('auth');
    Route::get('/users/editPerfil/{id}/{ordem}', [UsersController::class, 'updatePerfil'])->middleware('auth');

    Route::put('/users/update/{id}', [UsersController::class, 'update'])->middleware('auth');
    Route::post('/users', [UsersController::class,'store'])->middleware('auth');

    Route::get('/unauthorized', [UsersController::class, 'naoAutorizado'])->middleware('auth');

    Route::get('/dss', [UsersController::class, 'dss'])->middleware('auth');


        // ROTAS PARA ADM SETORES
        Route::get('/setores/setores', [SetorController::class, 'setores'])->middleware('auth');
        Route::get('/setores/create', [SetorController::class, 'create'])->middleware('auth');
        Route::get('/setores/{id}', [SetorController::class, 'show'])->middleware('auth');
        Route::delete('/setores/{id}', [SetorController::class, 'destroy'])->middleware('auth');
        Route::get('/setores/edit/{id}', [SetorController::class, 'edit'])->middleware('auth');

        Route::get('/setores/funcSetor/{id}', [SetorController::class, 'funcSetor'])->middleware('auth');
        
        Route::put('/setores/update/{id}', [SetorController::class, 'update'])->middleware('auth');
        Route::post('/setores', [SetorController::class,'store'])->middleware('auth');


        // Route::get('/wiki', [ArquivosController::class,'index']);
        // Route::get('/pesquisar', [ArquivosController::class, 'pesquisar'])->name('pesquisar');
        // Route::get('/pesquisarAdmin', [ArquivosController::class, 'pesquisarAdmin'])->name('pesquisarAdmin');
        // Route::get('/pesquisarNormas', [ArquivosController::class, 'pesquisarNormas'])->name('pesquisarNormas');
        // Route::get('/pesquisarInstrucoesNormativas', [ArquivosController::class, 'pesquisarInstrucoesNormativas'])->name('pesquisarInstrucoesNormativas');
        // Route::get('/pesquisarLegislacao', [ArquivosController::class, 'pesquisarLegislacao'])->name('pesquisarLegislacao');
        // Route::get('/pesquisarNormasGestao', [ArquivosController::class, 'pesquisarNormasGestao'])->name('pesquisarNormasGestao');
        // Route::get('/normas', [ArquivosController::class, 'acessarNormas']);
        // Route::get('/instrucoesNormativas', [ArquivosController::class, 'acessarInstrucoesNormativas']);
        // Route::get('/legislacao', [ArquivosController::class, 'acessarLegislacao']);
        // Route::get('/normasGestao', [ArquivosController::class, 'acessarNormasGestao']);
        // Route::get('/admin', [ArquivosController::class, 'acessarAdmin']);
        // Route::get('/create', [ArquivosController::class, 'create']);
        // Route::post('/gravarArquivo', [ArquivosController::class,'store']);
        // Route::get('/edit/{id}', [ArquivosController::class, 'edit']);
        // Route::put('/update/{id}', [ArquivosController::class, 'update']);
        // Route::get('/show/{id}', [ArquivosController::class, 'show']);
        // Route::delete('/delete/{id}', [ArquivosController::class, 'destroy']);


        // Route::get('/relatoriosDss/paranaUrbanoIII/', [DssController::class,'paranaUrbanoIII']);
        // Route::get('/relatoriosDss/parametros/', [DssController::class,'parametros']);
        // Route::get('/relatoriosDss/controlePepPoaPmr/', [DssController::class,'controlePepPoaPmr']);

        //faça uma rota post reservasVeiculo.store
        Route::post('/reservasVeiculo', [ControladorRota::class, 'registrarReservaVeiculo'])->middleware('auth')->name('reservasVeiculo.store');
        Route::get('/reservasVeiculo/{id}/{av}', [ControladorRota::class, 'removerReservaVeiculo'])->middleware('auth')->name('reservasVeiculo.destroy');

});