<?php

namespace App\Http\Controllers;

use App\Models\AnexoRota;
use App\Models\AnexoFinanceiro;
use Illuminate\Http\Request;
use App\Models\Av;
use App\Models\ComprovanteDespesa;
use App\Models\Objetivo;
use App\Models\VeiculoProprio;
use App\Models\VeiculoParanacidade;
use App\Models\Rota;
use App\Models\User;
use App\Models\Historico;
use App\Models\HistoricoPc;
use App\Models\Medicao;
use DateTime;
use DatePeriod;
use DateInterval;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Support\Facades\Storage;
use DateTimeZone;
use App\Mail\EnvioEmailGestor;
use App\Mail\EnvioGestorToDiretoria;
use App\Mail\EnvioGestorToSecretaria;
use App\Mail\EnvioDiretoriaToSecretaria;
use App\Mail\EnvioSecretariaToFinanceiro;
use App\Mail\EnvioFinanceiroToAdmFrota;
use App\Mail\EnvioFinanceiroToUsuarioAdiantamento;
use App\Mail\EnvioUsuarioToFinanceiroPc;
use App\Mail\EnvioFinanceiroToGestorPc;
use App\Mail\EnvioGestorToFinanceiroAcertoContas;
use App\Mail\EnvioFinanceiroToUsuarioAcertoContas;
use App\Mail\EnvioUsuarioToFinanceiroAcertoContas;
use App\Mail\EnvioFinanceiroToUsuarioReprovarAcertoContas;
use App\Mail\EnvioGestorToUsuarioReprovarPc;
use App\Mail\EnvioFinanceiroToUsuarioReprovarAv;
use App\Mail\EnvioDiretoriaToUsuarioReprovarAv;
use App\Mail\EnvioSecretariaToUsuarioReprovarAv;
use App\Mail\EnvioGestorToUsuarioReprovarAv;
use App\Mail\EnvioGestorToFinanceiro;
use App\Mail\EnvioSecretariaToUsuario;
use App\Mail\EnvioGestorToUsuarioViagemInternacional;
use Illuminate\Support\Facades\Mail;
use Spatie\Permission\Models\Permission;

class ControladorAv extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $avs = $user->avs;
        if ($user->dataAssinaturaTermo == null) {
            return view('termoResponsabilidade', ['user'=> $user]);
        }
        else{
            return view('welcome', ['avs' => $avs, 'user'=> $user]);
        }
    }

    public function avs()
    {
        $user = auth()->user();
        $avs = $user->avs;
        $objetivos = Objetivo::all();
        return view('avs.avs', ['avs' => $avs, 'user'=> $user, 'objetivos' => $objetivos]);
    }

    public function gerenciarAvs()
    {
        $user = auth()->user();
        $usersAll = User::all();
        $users = [];
        foreach ($usersAll as $u) {
            if ($u->employeeNumber != null) {
                array_push($users, $u);
            }
        }
        $names = array_column($users, 'name');

        // Ordena os arrays $users e $names em ordem ascendente
        array_multisort($names, SORT_ASC, $users);
        
        $avs = Av::all();
        $objetivos = Objetivo::all();
        return view('avs.avsAdm', ['avs' => $avs, 'user'=> $user, 'objetivos' => $objetivos, 'users' => $users]);
    }

    public function create()
    {
        $user = auth()->user();
        $veiculosProprios = $user->veiculosProprios;

        $objetivos = Objetivo::all();
        $veiculosParanacidade = VeiculoParanacidade::all(); // Fazer filtro para apresentar somentes os ativos

        $url = 'https://portaldosmunicipios.pr.gov.br/api/v1/medicao?status=27';
        $json = file_get_contents($url);
        $data = json_decode($json);
        $filtro = [];
        $filtroTodos = [];
        $medicoes = Medicao::all();

        $avs = $user->avs;
        $bancos = [];
        $agencias = [];
        $contas = [];
        $pixs = [];
        //itere avs
        foreach($avs as $av){
            //adicione nos arrays
            array_push($bancos, $av->banco);
            array_push($agencias, $av->agencia);
            array_push($contas, $av->conta);
            array_push($pixs, $av->pix);
            //elimine os repetidos
            $bancos = array_unique($bancos);
            $agencias = array_unique($agencias);
            $contas = array_unique($contas);
            $pixs = array_unique($pixs);
        }

        foreach ($data as $item) {
            $jaExiste = false;
            foreach ($medicoes as $medicao) {
                if (($item->municipio_id == $medicao->municipio_id) && ($item->numero_projeto == $medicao->numero_projeto)
                && ($item->numero_lote == $medicao->numero_lote) && ($item->numero == $medicao->numero_medicao)) {
                    $jaExiste = true;
                }
            }
            if(!$jaExiste){
                if ($item->nome_supervisor == $user->name) { //  para teste 'Fernanda Espindola de Oliveira'
                    array_push($filtro, $item);
                }
                else{
                    array_push($filtroTodos, $item);
                }
            }
        }
        return view('avs.create', ['objetivos' => $objetivos, 'veiculosProprios' => $veiculosProprios, 'veiculosParanacidade' => $veiculosParanacidade, 
        'user'=> $user, 'filtro' => $filtro, 'filtroTodos' => $filtroTodos, 'bancos' => $bancos, 'agencias' => $agencias, 'contas' => $contas, 'pixs' => $pixs]);
    }

    public function verFluxo($id){

        $objetivos = Objetivo::all();
        $historicos = Historico::all();
        $users = User::all();

        $user = auth()->user();

        $av = Av::findOrFail($id);

        $user = auth()->user();
        $veiculosProprios = $user->veiculosProprios;

        if($user->id != $av->user->id) {
            return redirect('/dashboard')->with('msg', 'Você não tem permissão para editar esta av!');
        }

        return view('avs.fluxo', ['av' => $av, 'objetivos' => $objetivos, 'veiculosProprios' => $veiculosProprios, 'user'=> $user, 'historicos'=> $historicos, 'users'=> $users]);

    }

    public function verFluxoGestor($id){

        $objetivos = Objetivo::all();
        $historicosTodos = Historico::all();
        $veiculosParanacidade = VeiculoParanacidade::all();
        $users = User::all();
        $historicos = [];
        $user = auth()->user();
        $usersFiltrados = [];
        $usersFiltradosSubordinados = [];
        $possoEditar = false;

        $av = Av::findOrFail($id);
        $userAv = User::findOrFail($av->user_id);
        $rotas = $av->rotas;
        $isInternacional = false;

        $medicoes = Medicao::all();
        $medicoesFiltradas = [];

        $colecao = $this->geraArrayDiasValores($av);

        foreach($medicoes as $medicao){
            if($medicao->av_id == $av->id){
                array_push($medicoesFiltradas, $medicao); 
            }
        }

        foreach($rotas as $r){
            if($r->isViagemInternacional == true){
                $isInternacional = true;
            }
        }

        foreach ($users as $u){//Percorre todos os usuários do sistema
            $managerDN = $u->manager; // CN=Leandro Victorino Moura,OU=CTI,OU=Empregados,DC=prcidade,DC=br

            // Dividir a string em partes usando o caractere de vírgula como delimitador
            $parts = explode(',', $managerDN);

            // Extrair o nome do gerente da primeira parte
            $managerName = substr($parts[0], 3); // Remover os primeiros 3 caracteres "CN="
            
            if($managerName == $user->name && $u->id != $user->id){//Verifica se cada um pertence ao seu time, exceto vc mesmo
                array_push($usersFiltrados, $u);//Adiciona ao array filtrado o usuário encontrado
            }
        }
        
        foreach ($users as $u2){//Percorre todos os usuários do sistema
            $managerDN = $u2->manager; // CN=Leandro Victorino Moura,OU=CTI,OU=Empregados,DC=prcidade,DC=br

            // Dividir a string em partes usando o caractere de vírgula como delimitador
            $parts = explode(',', $managerDN);

            // Extrair o nome do gerente da primeira parte
            $managerName = substr($parts[0], 3); // Remover os primeiros 3 caracteres "CN="
            
            foreach($usersFiltrados as $uF){
                if($managerName == $uF->name && $u2->employeeNumber != null && $uF->id != $u2->id && $uF->id != 92){//Verifica se cada um pertence ao seu time, exceto vc mesmo
                    array_push($usersFiltradosSubordinados, $u2);//Adiciona ao array filtrado o usuário encontrado
                }
            }
        }

        foreach ($usersFiltrados as $u){//Percorre todos os usuários
            if($av->user_id == $u->id){//Verifica se o usuário do da AV atual pertence ao meu time
                if($av->isEnviadoUsuario == 1 && $av->isAprovadoGestor == 0){
                    $possoEditar = true;
                }
            }
        }
        foreach ($usersFiltradosSubordinados as $u){//Percorre todos os usuários
            if($av->user_id == $u->id){//Verifica se o usuário do da AV atual pertence ao meu time
                if($av->isEnviadoUsuario == 1 && $av->isAprovadoGestor == 0){
                    $possoEditar = true;
                }
            }
        }

        foreach($historicosTodos as $historico){
            if($historico->av_id == $av->id){
                array_push($historicos, $historico);
            }
        }

        $veiculosProprios = $userAv->veiculosProprios;

        $arrayDiasValores = $colecao[0];
        $anoSaidaInicial = $colecao[1];
        $mesSaidaInicial = $colecao[2];
        $diaSaidaInicial = $colecao[3];
        $horaSaidaInicial = $colecao[4];
        $minutoSaidaInicial = $colecao[5];
        $mesChegadaInicial = $colecao[6];
        $diaChegadaInicial = $colecao[7];
        $horaChegadaInicial = $colecao[8];
        $minutoChegadaInicial = $colecao[9];
        $mesSaidaFinal = $colecao[10];
        $diaSaidaFinal = $colecao[11];
        $horaSaidaFinal = $colecao[12];
        $minutoSaidaFinal = $colecao[13];
        $mesChegadaFinal = $colecao[14];
        $diaChegadaFinal = $colecao[15];
        $horaChegadaFinal = $colecao[16];
        $minutoChegadaFinal = $colecao[17];
        $dataInicio = $colecao[18];
        $dataFim = $colecao[19];

        if($possoEditar == true){
            return view('avs.verFluxoGestor', ['av' => $av, 'objetivos' => $objetivos, 'veiculosProprios' => $veiculosProprios, 
            'user'=> $user, 'historicos'=> $historicos, 'users'=> $users, 'userAv' => $userAv, 'veiculosParanacidade' => $veiculosParanacidade,
            'isInternacional' => $isInternacional, 'medicoesFiltradas' => $medicoesFiltradas, 'arrayDiasValores' => $arrayDiasValores,
            'anoSaidaInicial' => $anoSaidaInicial, 'mesSaidaInicial' => $mesSaidaInicial, 'diaSaidaInicial' => $diaSaidaInicial,
            'horaSaidaInicial' => $horaSaidaInicial, 'minutoSaidaInicial' => $minutoSaidaInicial, 'mesChegadaInicial' => $mesChegadaInicial,
            'diaChegadaInicial' => $diaChegadaInicial, 'horaChegadaInicial' => $horaChegadaInicial, 'minutoChegadaInicial' => $minutoChegadaInicial,
            'mesSaidaFinal' => $mesSaidaFinal, 'diaSaidaFinal' => $diaSaidaFinal, 'horaSaidaFinal' => $horaSaidaFinal, 'minutoSaidaFinal' => $minutoSaidaFinal,
            'mesChegadaFinal' => $mesChegadaFinal, 'diaChegadaFinal' => $diaChegadaFinal, 'horaChegadaFinal' => $horaChegadaFinal, 'minutoChegadaFinal' => $minutoChegadaFinal,
            'dataInicio' => $dataInicio, 'dataFim' => $dataFim]);
        }
        else{
            return redirect('/dashboard')->with('msg', 'Você não tem permissão para avaliar esta av!');
        }
        
    }

    public function verFluxoDiretoria($id){
        $objetivos = Objetivo::all();
        $historicosTodos = Historico::all();
        $veiculosParanacidade = VeiculoParanacidade::all();
        $users = User::all();
        $historicos = [];
        $user = auth()->user();
        $usersFiltrados = [];
        $possoEditar = false;

        $av = Av::findOrFail($id);
        $userAv = User::findOrFail($av->user_id);

        $anexos = [];
        $anexosFinanceiro = AnexoFinanceiro::all();
        foreach($anexosFinanceiro as $a){
            if($a->av_id == $av->id){
                array_push($anexos, $a);
            }
        }
		

        if($av["isEnviadoUsuario"]==1 && $av["isAprovadoGestor"]==true && $av["isVistoDiretoria"]==false && $av["isCancelado"]==false){ //Se a av já foi enviada e autorizada pelo Gestor
            foreach($av->rotas as $rota){//Percorre todas as rotas da AV
                if($rota["isViagemInternacional"]==1 || $rota["isVeiculoProprio"]==1){//Se a viagem for internacional ou tiver veículo próprio
                    
                    if( $av->user_id != $user->id){//Verifica se não é vc mesmo
                        $possoEditar = true;
                    }
                }
            }
        }

        foreach($historicosTodos as $historico){
            if($historico->av_id == $av->id){
                array_push($historicos, $historico);
            }
        }

        $veiculosProprios = $userAv->veiculosProprios;

        $colecao = $this->geraArrayDiasValores($av);
        $arrayDiasValores = $colecao[0];
        $anoSaidaInicial = $colecao[1];
        $mesSaidaInicial = $colecao[2];
        $diaSaidaInicial = $colecao[3];
        $horaSaidaInicial = $colecao[4];
        $minutoSaidaInicial = $colecao[5];
        $mesChegadaInicial = $colecao[6];
        $diaChegadaInicial = $colecao[7];
        $horaChegadaInicial = $colecao[8];
        $minutoChegadaInicial = $colecao[9];
        $mesSaidaFinal = $colecao[10];
        $diaSaidaFinal = $colecao[11];
        $horaSaidaFinal = $colecao[12];
        $minutoSaidaFinal = $colecao[13];
        $mesChegadaFinal = $colecao[14];
        $diaChegadaFinal = $colecao[15];
        $horaChegadaFinal = $colecao[16];
        $minutoChegadaFinal = $colecao[17];
        $dataInicio = $colecao[18];
        $dataFim = $colecao[19];

        $medicoes = Medicao::all();
        $medicoesFiltradas = [];
		
		foreach($medicoes as $medicao){
            if($medicao->av_id == $av->id){
                array_push($medicoesFiltradas, $medicao); 
            }
        }

        if($possoEditar == true){
            return view('avs.verFluxoDiretoria', ['av' => $av, 'objetivos' => $objetivos, 'veiculosProprios' => $veiculosProprios, 
            'user'=> $user, 'historicos'=> $historicos, 'users'=> $users, 'userAv' => $userAv, 'veiculosParanacidade' => $veiculosParanacidade, 
            'anexos' => $anexos, 'arrayDiasValores' => $arrayDiasValores,
            'anoSaidaInicial' => $anoSaidaInicial, 'mesSaidaInicial' => $mesSaidaInicial, 'diaSaidaInicial' => $diaSaidaInicial,
            'horaSaidaInicial' => $horaSaidaInicial, 'minutoSaidaInicial' => $minutoSaidaInicial, 'mesChegadaInicial' => $mesChegadaInicial,
            'diaChegadaInicial' => $diaChegadaInicial, 'horaChegadaInicial' => $horaChegadaInicial, 'minutoChegadaInicial' => $minutoChegadaInicial,
            'mesSaidaFinal' => $mesSaidaFinal, 'diaSaidaFinal' => $diaSaidaFinal, 'horaSaidaFinal' => $horaSaidaFinal, 'minutoSaidaFinal' => $minutoSaidaFinal,
            'mesChegadaFinal' => $mesChegadaFinal, 'diaChegadaFinal' => $diaChegadaFinal, 'horaChegadaFinal' => $horaChegadaFinal, 'minutoChegadaFinal' => $minutoChegadaFinal,
            'dataInicio' => $dataInicio, 'dataFim' => $dataFim, 'medicoesFiltradas' => $medicoesFiltradas]);
        }
        else{
            return redirect('/dashboard')->with('msg', 'Você não tem permissão para avaliar esta av!');
        }
    }

    public function verFluxoSecretaria($id){
        $objetivos = Objetivo::all();
        $historicosTodos = Historico::all();
        $veiculosParanacidade = VeiculoParanacidade::all();
        $users = User::all();
        $historicos = [];
        $user = auth()->user();
        $usersFiltrados = [];
        $possoEditar = false;

        $anexosRotas = AnexoRota::all();

        $av = Av::findOrFail($id);
        $userAv = User::findOrFail($av->user_id);
        $veiculosProprios = $userAv->veiculosProprios;

        $medicoes = Medicao::all();
        $medicoesFiltradas = [];

        foreach($medicoes as $medicao){
            if($medicao->av_id == $av->id){
                array_push($medicoesFiltradas, $medicao); 
            }
        }

        foreach($historicosTodos as $historico){
            if($historico->av_id == $av->id){
                array_push($historicos, $historico);
            }
        }

        if(($av["isEnviadoUsuario"]==1 && $av["isAprovadoGestor"]==true && $av["isRealizadoReserva"]==false && $av["isCancelado"]==false)
            || ($av["isCancelado"]== true && $av["isRealizadoReserva"]== true)){ //Se a av dele já foi enviada e autorizada pelo Gestor
            $isNecessarioAvaliacaoDiretoria = false;
            $passouPelaDiretoria = false;
            foreach($av->rotas as $rota){//Percorre todas as rotas da AV
                if($rota["isViagemInternacional"]==1 || $rota["isVeiculoProprio"]==1){//Se a viagem for internacional ou tiver veículo próprio
                    $isNecessarioAvaliacaoDiretoria = true;

                    if($av["isVistoDiretoria"]==true)
                    {
                        $passouPelaDiretoria = true;
                    }
                }
            }
            if(($isNecessarioAvaliacaoDiretoria == true && $passouPelaDiretoria == true) || $isNecessarioAvaliacaoDiretoria == false){
                if( $av->user_id != $user->id){//Verifica se não é vc mesmo
                    $possoEditar = true;
                }
            }
        }

        $colecao = $this->geraArrayDiasValores($av);
        $arrayDiasValores = $colecao[0];
        $anoSaidaInicial = $colecao[1];
        $mesSaidaInicial = $colecao[2];
        $diaSaidaInicial = $colecao[3];
        $horaSaidaInicial = $colecao[4];
        $minutoSaidaInicial = $colecao[5];
        $mesChegadaInicial = $colecao[6];
        $diaChegadaInicial = $colecao[7];
        $horaChegadaInicial = $colecao[8];
        $minutoChegadaInicial = $colecao[9];
        $mesSaidaFinal = $colecao[10];
        $diaSaidaFinal = $colecao[11];
        $horaSaidaFinal = $colecao[12];
        $minutoSaidaFinal = $colecao[13];
        $mesChegadaFinal = $colecao[14];
        $diaChegadaFinal = $colecao[15];
        $horaChegadaFinal = $colecao[16];
        $minutoChegadaFinal = $colecao[17];
        $dataInicio = $colecao[18];
        $dataFim = $colecao[19];

        if($possoEditar == true){
            return view('avs.verFluxoSecretaria', ['av' => $av, 'objetivos' => $objetivos, 'veiculosProprios' => $veiculosProprios, 
            'user'=> $user, 'historicos'=> $historicos, 'users'=> $users, 'userAv' => $userAv, 'veiculosParanacidade' => $veiculosParanacidade,
            'medicoesFiltradas' => $medicoesFiltradas, 'arrayDiasValores' => $arrayDiasValores,
            'anoSaidaInicial' => $anoSaidaInicial, 'mesSaidaInicial' => $mesSaidaInicial, 'diaSaidaInicial' => $diaSaidaInicial,
            'horaSaidaInicial' => $horaSaidaInicial, 'minutoSaidaInicial' => $minutoSaidaInicial, 'mesChegadaInicial' => $mesChegadaInicial,
            'diaChegadaInicial' => $diaChegadaInicial, 'horaChegadaInicial' => $horaChegadaInicial, 'minutoChegadaInicial' => $minutoChegadaInicial,
            'mesSaidaFinal' => $mesSaidaFinal, 'diaSaidaFinal' => $diaSaidaFinal, 'horaSaidaFinal' => $horaSaidaFinal, 'minutoSaidaFinal' => $minutoSaidaFinal,
            'mesChegadaFinal' => $mesChegadaFinal, 'diaChegadaFinal' => $diaChegadaFinal, 'horaChegadaFinal' => $horaChegadaFinal, 'minutoChegadaFinal' => $minutoChegadaFinal,
            'dataInicio' => $dataInicio, 'dataFim' => $dataFim], ['anexosRotas' => $anexosRotas]);
        }
        else{
            return redirect('avs/autSecretaria')->with('msg', 'Você não tem permissão para avaliar esta av!');
        }
    }

    public function fazerPrestacaoContas($id){
        $objetivos = Objetivo::all();
        $historicosTodos = Historico::all();
        $users = User::all();
        $historicos = [];
        $anexos = [];
        $user = auth()->user();
        $usersFiltrados = [];
        $possoEditar = false;
        $veiculosProprios = $user->veiculosProprios;
        $veiculosParanacidade = VeiculoParanacidade::all();
        $anexosFinanceiro = [];
        $anexosRotas = [];
        $comprovantesAll = ComprovanteDespesa::all();
        $comprovantes = [];

        $av = Av::findOrFail($id);
        $userAv = User::findOrFail($av->user_id);
        $historicoPcAll = HistoricoPc::all();
        $historicoPc = [];

        $medicoes = Medicao::all();
        $medicoesFiltradas = [];

        foreach($medicoes as $medicao){
            if($medicao->av_id == $av->id){
                array_push($medicoesFiltradas, $medicao); 
            }
        }

        foreach($comprovantesAll as $comp){
            if($comp->av_id == $av->id){
                array_push($comprovantes, $comp);
            }
        }
        
        foreach($historicoPcAll as $hisPc){
            if($hisPc->av_id == $av->id){
                array_push($historicoPc, $hisPc);
            }
        }

        foreach($av->rotas as $r){//Verifica todas as rotas da AV
            foreach($r->anexos as $a){// Verifica cada um dos anexos da rota
                array_push($anexosRotas, $a);// Empilha no array cada um dos anexos
            }
        }
        
        foreach($historicosTodos as $historico){
            if($historico->av_id == $av->id){
                array_push($historicos, $historico);
            }
        }

        foreach($av->anexosFinanceiro as $anexF){
                array_push($anexosFinanceiro, $anexF);
        }


        if(($av["isEnviadoUsuario"]==1 && $av["isAprovadoGestor"]==true && $av["isRealizadoReserva"]==true && $av["isAprovadoFinanceiro"]==true
        && $av["isPrestacaoContasRealizada"]==false && $av["isCancelado"]==false) || 
        ($av["isCancelado"]==true && $av["isAprovadoFinanceiro"]==true)){ //Se a av dele já foi enviada e autorizada pelo Gestor
                $possoEditar = true;
        }


        if($possoEditar == true){
            return view('avs.fazerPrestacaoContas', ['av' => $av, 'objetivos' => $objetivos, 'veiculosProprios' => $veiculosProprios, 
            'user'=> $user, 'historicos'=> $historicos, 'anexosRotas' => $anexosRotas, 'anexosFinanceiro' => $anexosFinanceiro, 
            'users'=> $users, 'userAv' => $userAv, 'historicoPc' => $historicoPc, 'comprovantes' => $comprovantes,
            'medicoesFiltradas' => $medicoesFiltradas, 'veiculosParanacidade' => $veiculosParanacidade]);
        }
        else{
            return redirect('avs/autFinanceiro')->with('msg', 'Você não tem permissão para avaliar esta av!');
        }
    }

    public function avaliarPcFinanceiro($id){
        $objetivos = Objetivo::all();
        $historicosTodos = Historico::all();
        $users = User::all();
        $veiculosParanacidade = VeiculoParanacidade::all();
        $historicos = [];
        $anexos = [];
        $user = auth()->user();
        $usersFiltrados = [];
        $possoEditar = false;
        
        $anexosFinanceiro = [];
        $anexosRotas = [];
        $comprovantesAll = ComprovanteDespesa::all();
        $comprovantes = [];

        $av = Av::findOrFail($id);
        $userAv = User::findOrFail($av->user_id);
        $veiculosProprios = $userAv->veiculosProprios;
        $historicoPcAll = HistoricoPc::all();
        $historicoPc = [];
        $valorRecebido = null;

        $medicoes = Medicao::all();
        $medicoesFiltradas = [];

        $valorAcertoContasReal = 0;
        $valorAcertoContasDolar = 0;

        foreach($comprovantesAll as $comp){
            if($comp->av_id == $av->id){
                array_push($comprovantes, $comp);
            }
        }
		
		foreach($comprovantes as $compFiltrado){
            $valorAcertoContasReal += $compFiltrado->valorReais;
            $valorAcertoContasDolar += $compFiltrado->valorDolar;
        }

        foreach($medicoes as $medicao){
            if($medicao->av_id == $av->id){
                array_push($medicoesFiltradas, $medicao); 
            }
        }

        foreach($historicoPcAll as $hisPc){
            if($hisPc->av_id == $av->id){
                array_push($historicoPc, $hisPc);
            }
            if($hisPc->av_id == $av->id && $hisPc->comentario == "Adiantamento realizado - valor inicial"){
                $valorRecebido = $hisPc;
            }
        }
        if($valorRecebido == null){
            $valorRecebido = new HistoricoPc();
            $valorRecebido->valorReais = 0;
            $valorRecebido->valorExtraReais = 0;
            $valorRecebido->valorDolar = 0;
            $valorRecebido->valorExtraDolar = 0;
        }

        foreach($av->rotas as $r){//Verifica todas as rotas da AV
            foreach($r->anexos as $a){// Verifica cada um dos anexos da rota
                array_push($anexosRotas, $a);// Empilha no array cada um dos anexos
            }
        }
        
        foreach($historicosTodos as $historico){
            if($historico->av_id == $av->id){
                array_push($historicos, $historico);
            }
        }

        foreach($av->anexosFinanceiro as $anexF){
                array_push($anexosFinanceiro, $anexF);
        }

        if($userAv->id != $user->id){
            if(($av["isEnviadoUsuario"]==1 && $av["isAprovadoGestor"]==true && $av["isRealizadoReserva"]==true && $av["isAprovadoFinanceiro"]==true
                    && $av["isPrestacaoContasRealizada"]==true && $av["isFinanceiroAprovouPC"]==false && $av["isCancelado"]==false) || 
                    ($av["isCancelado"]==true && $av["isAprovadoFinanceiro"]==true && $av["isPrestacaoContasRealizada"] == true 
                    && $av["isFinanceiroAprovouPC"] == false)){ //Se a av dele já foi enviada e autorizada pelo Gestor
                $possoEditar = true;
            }
        }
        
        if($possoEditar == true){
            return view('avs.avaliarPcFinanceiro', ['av' => $av, 'objetivos' => $objetivos, 'veiculosProprios' => $veiculosProprios, 
            'user'=> $user, 'historicos'=> $historicos, 'anexosRotas' => $anexosRotas, 'anexosFinanceiro' => $anexosFinanceiro, 
            'users'=> $users, 'userAv' => $userAv, 'historicoPc' => $historicoPc, 'comprovantes' => $comprovantes,
            'medicoesFiltradas' => $medicoesFiltradas, 'valorRecebido' => $valorRecebido, 'valorAcertoContasReal' => $valorAcertoContasReal,
            'valorAcertoContasDolar' => $valorAcertoContasDolar, 'veiculosParanacidade' => $veiculosParanacidade]);
        }
        else{
            return redirect('avs/autPcFinanceiro')->with('msg', 'Você não tem permissão para avaliar esta av!');
        }
    }

    public function realizarAcertoContasFinanceiro($id){
        $objetivos = Objetivo::all();
        $historicosTodos = Historico::all();
        $veiculosParanacidade = VeiculoParanacidade::all();
        $users = User::all();
        $historicos = [];
        $anexos = [];
        $user = auth()->user();
        $usersFiltrados = [];
        $possoEditar = false;
        $veiculosProprios = $user->veiculosProprios;
        $anexosFinanceiro = [];
        $anexosRotas = [];
        $comprovantesAll = ComprovanteDespesa::all();
        $comprovantes = [];

        $av = Av::findOrFail($id);
        $userAv = User::findOrFail($av->user_id);
        $historicoPcAll = HistoricoPc::all();
        $historicoPc = [];
        $valorRecebido = null;
        $valorAcertoContasReal = 0;
        $valorAcertoContasDolar = 0;

        $medicoes = Medicao::all();
        $medicoesFiltradas = [];

        foreach($medicoes as $medicao){
            if($medicao->av_id == $av->id){
                array_push($medicoesFiltradas, $medicao); 
            }
        }

        foreach($comprovantesAll as $comp){
            if($comp->av_id == $av->id){
                array_push($comprovantes, $comp);
            }
        }
        foreach($comprovantes as $compFiltrado){
            $valorAcertoContasReal += $compFiltrado->valorReais;
            $valorAcertoContasDolar += $compFiltrado->valorDolar;
        }
        
        foreach($historicoPcAll as $hisPc){
            if($hisPc->av_id == $av->id){
                array_push($historicoPc, $hisPc);
            }
            if($hisPc->av_id == $av->id && $hisPc->comentario == "Adiantamento realizado - valor inicial"){
                $valorRecebido = $hisPc;
            }
        }

        foreach($av->rotas as $r){//Verifica todas as rotas da AV
            foreach($r->anexos as $a){// Verifica cada um dos anexos da rota
                array_push($anexosRotas, $a);// Empilha no array cada um dos anexos
            }
        }
        
        foreach($historicosTodos as $historico){
            if($historico->av_id == $av->id){
                array_push($historicos, $historico);
            }
        }

        foreach($av->anexosFinanceiro as $anexF){
                array_push($anexosFinanceiro, $anexF);
        }
        
        if($userAv->id != $user->id){//Se  o usuário não for você
            if(($av["isEnviadoUsuario"]==1 && $av["isAprovadoGestor"]==true && $av["isRealizadoReserva"]==true && $av["isAprovadoFinanceiro"]==true
                    && $av["isPrestacaoContasRealizada"]==true && $av["isFinanceiroAprovouPC"]==true 
                    && $av["isGestorAprovouPC"]==true&& $av["isAcertoContasRealizado"]==false && $av["isCancelado"]==false) || 
                    ($av["isCancelado"]==true && $av["isAprovadoFinanceiro"]==true && $av["isPrestacaoContasRealizada"] == true 
                    && $av["isFinanceiroAprovouPC"] == true && $av["isGestorAprovouPC"] == true && $av["isAcertoContasRealizado"] == false)){ //Se a av dele já foi enviada e autorizada pelo Gestor
                $possoEditar = true;
            }
        }

        if($possoEditar == true){
            return view('avs.realizarAcertoContasFinanceiro', ['av' => $av, 'objetivos' => $objetivos, 'veiculosProprios' => $veiculosProprios, 
            'user'=> $user, 'historicos'=> $historicos, 'anexosRotas' => $anexosRotas, 'anexosFinanceiro' => $anexosFinanceiro, 
            'users'=> $users, 'userAv' => $userAv, 'historicoPc' => $historicoPc, 'comprovantes' => $comprovantes, 'valorRecebido' => $valorRecebido,
            'valorAcertoContasReal'=>$valorAcertoContasReal, 'valorAcertoContasDolar'=>$valorAcertoContasDolar, 'medicoesFiltradas' => $medicoesFiltradas, 
            'veiculosParanacidade' => $veiculosParanacidade]);
        }
        else{
            return redirect('avs/autPcFinanceiro')->with('msg', 'Você não tem permissão para avaliar esta av!');
        }
    }

    public function validarAcertoContasUsuario($id){
        $objetivos = Objetivo::all();
        $historicosTodos = Historico::all();
        $veiculosParanacidade = VeiculoParanacidade::all();
        $users = User::all();
        $historicos = [];
        $anexos = [];
        $user = auth()->user();
        $usersFiltrados = [];
        $possoEditar = false;
        $veiculosProprios = $user->veiculosProprios;
        $anexosFinanceiro = [];
        $anexosRotas = [];
        $comprovantesAll = ComprovanteDespesa::all();
        $comprovantes = [];
        $valorAcertoContasReal = 0;
        $valorAcertoContasDolar = 0;

        $av = Av::findOrFail($id);
        $userAv = User::findOrFail($av->user_id);
        $historicoPcAll = HistoricoPc::all();
        $historicoPc = [];
        $valorRecebido = null;

        $medicoes = Medicao::all();
        $medicoesFiltradas = [];

        foreach($medicoes as $medicao){
            if($medicao->av_id == $av->id){
                array_push($medicoesFiltradas, $medicao); 
            }
        }

        foreach($comprovantesAll as $comp){
            if($comp->av_id == $av->id){
                array_push($comprovantes, $comp);
            }
        }

        foreach($comprovantes as $compFiltrado){
            $valorAcertoContasReal += $compFiltrado->valorReais;
            $valorAcertoContasDolar += $compFiltrado->valorDolar;
        }
        
        foreach($historicoPcAll as $hisPc){
            if($hisPc->av_id == $av->id){
                array_push($historicoPc, $hisPc);
            }
            if($hisPc->av_id == $av->id && $hisPc->comentario == "Adiantamento realizado - valor inicial"){
                $valorRecebido = $hisPc;
            }
        }

        foreach($av->rotas as $r){//Verifica todas as rotas da AV
            foreach($r->anexos as $a){// Verifica cada um dos anexos da rota
                array_push($anexosRotas, $a);// Empilha no array cada um dos anexos
            }
        }
        
        foreach($historicosTodos as $historico){
            if($historico->av_id == $av->id){
                array_push($historicos, $historico);
            }
        }

        foreach($av->anexosFinanceiro as $anexF){
                array_push($anexosFinanceiro, $anexF);
        }


        if(($av["isEnviadoUsuario"]==1 && $av["isAprovadoGestor"]==true && $av["isRealizadoReserva"]==true && $av["isAprovadoFinanceiro"]==true
                    && $av["isPrestacaoContasRealizada"]==true && $av["isFinanceiroAprovouPC"]==true 
                    && $av["isGestorAprovouPC"]==true && $av["isAcertoContasRealizado"]==true && $av["isCancelado"]==false) || 
                    
                    ($av["isCancelado"]==true && $av["isAprovadoFinanceiro"]==true && $av["isPrestacaoContasRealizada"] == true 
                    && $av["isFinanceiroAprovouPC"] == true && $av["isGestorAprovouPC"] == true && $av["isAcertoContasRealizado"] == true)){ //Se a av dele já foi enviada e autorizada pelo Gestor
                $possoEditar = true;
        }

        if($possoEditar == true){
            return view('avs.validarAcertoContasUsuario', ['av' => $av, 'objetivos' => $objetivos, 'veiculosProprios' => $veiculosProprios, 
            'user'=> $user, 'historicos'=> $historicos, 'anexosRotas' => $anexosRotas, 'anexosFinanceiro' => $anexosFinanceiro, 
            'users'=> $users, 'userAv' => $userAv, 'historicoPc' => $historicoPc, 'comprovantes' => $comprovantes, 'valorRecebido' => $valorRecebido,
            'valorAcertoContasReal'=>$valorAcertoContasReal, 'valorAcertoContasDolar'=>$valorAcertoContasDolar, 'medicoesFiltradas' => $medicoesFiltradas, 
            'veiculosParanacidade' => $veiculosParanacidade]);
        }
        else{
            return redirect('avs/avs')->with('msg', 'Você não tem permissão para avaliar esta av!');
        }
    }

    public function avaliarPcGestor($id){
        $objetivos = Objetivo::all();
        $historicosTodos = Historico::all();
        $veiculosParanacidade = VeiculoParanacidade::all();
        $users = User::all();
        $historicos = [];
        $anexos = [];
        $user = auth()->user();
        $usersFiltrados = [];
        $possoEditar = false;
        
        $anexosFinanceiro = [];
        $anexosRotas = [];
        $comprovantesAll = ComprovanteDespesa::all();
        $comprovantes = [];

        $av = Av::findOrFail($id);
        $userAv = User::findOrFail($av->user_id);
        $veiculosProprios = $userAv->veiculosProprios;
        $historicoPcAll = HistoricoPc::all();
        $historicoPc = [];
        $valorRecebido = null;

        $medicoes = Medicao::all();
        $medicoesFiltradas = [];

        $valorAcertoContasReal = 0;
        $valorAcertoContasDolar = 0;

        foreach($comprovantesAll as $comp){
            if($comp->av_id == $av->id){
                array_push($comprovantes, $comp);
            }
        }

        foreach($comprovantes as $compFiltrado){
            $valorAcertoContasReal += $compFiltrado->valorReais;
            $valorAcertoContasDolar += $compFiltrado->valorDolar;
        }

        foreach($medicoes as $medicao){
            if($medicao->av_id == $av->id){
                array_push($medicoesFiltradas, $medicao); 
            }
        }
        
        foreach($historicoPcAll as $hisPc){
            if($hisPc->av_id == $av->id){
                array_push($historicoPc, $hisPc);
            }
            if($hisPc->av_id == $av->id && $hisPc->comentario == "Adiantamento realizado - valor inicial"){
                $valorRecebido = $hisPc;
            }
        }
        if($valorRecebido == null){
            $valorRecebido = new HistoricoPc();
            $valorRecebido->valorReais = 0;
            $valorRecebido->valorExtraReais = 0;
            $valorRecebido->valorDolar = 0;
            $valorRecebido->valorExtraDolar = 0;
        }

        foreach($av->rotas as $r){//Verifica todas as rotas da AV
            foreach($r->anexos as $a){// Verifica cada um dos anexos da rota
                array_push($anexosRotas, $a);// Empilha no array cada um dos anexos
            }
        }
        
        foreach($historicosTodos as $historico){
            if($historico->av_id == $av->id){
                array_push($historicos, $historico);
            }
        }

        foreach($av->anexosFinanceiro as $anexF){
                array_push($anexosFinanceiro, $anexF);
        }

        if($userAv->id != $user->id){//Se  o usuário não for você
            if(($av["isEnviadoUsuario"]==1 && $av["isAprovadoGestor"]==true && $av["isRealizadoReserva"]==true && $av["isAprovadoFinanceiro"]==true
                && $av["isPrestacaoContasRealizada"]==true && $av["isFinanceiroAprovouPC"]==true  && $av["isGestorAprovouPC"]==false
                && $av["isCancelado"]==false) || 
                ($av["isCancelado"]==true && $av["isAprovadoFinanceiro"]==true && $av["isPrestacaoContasRealizada"] == true 
                && $av["isFinanceiroAprovouPC"] == true && $av["isGestorAprovouPC"] == false)){ //Se a av dele já foi enviada e autorizada pelo Gestor
                $possoEditar = true;
            }
        }

        if($possoEditar == true){
            return view('avs.avaliarPcGestor', ['av' => $av, 'objetivos' => $objetivos, 'veiculosProprios' => $veiculosProprios, 
            'user'=> $user, 'historicos'=> $historicos, 'anexosRotas' => $anexosRotas, 'anexosFinanceiro' => $anexosFinanceiro, 
            'users'=> $users, 'userAv' => $userAv, 'historicoPc' => $historicoPc, 'comprovantes' => $comprovantes,
            'medicoesFiltradas' => $medicoesFiltradas, 'valorRecebido' => $valorRecebido, 'valorAcertoContasReal' => $valorAcertoContasReal,
            'valorAcertoContasDolar' => $valorAcertoContasDolar, 'veiculosParanacidade' => $veiculosParanacidade]);
        }
        else{
            return redirect('avs/autPcGestor')->with('msg', 'Você não tem permissão para avaliar esta av!');
        }
    }

    public function verFluxoAdmFrota($id){
        $objetivos = Objetivo::all();
        $historicosTodos = Historico::all();
        $users = User::all();
        $historicos = [];
        $user = auth()->user();
        $possoEditar = false;
        $veiculosParanacidade = VeiculoParanacidade::all();
        $veiculosProprios = $user->veiculosProprios;

        $av = Av::findOrFail($id);
        $userAv = User::findOrFail($av->user_id);

        foreach($historicosTodos as $historico){
            if($historico->av_id == $av->id){
                array_push($historicos, $historico);
            }
        }

        if($av["isEnviadoUsuario"]==1 && $av["isAprovadoGestor"]==true && $av["isReservadoVeiculoParanacidade"]==false && $av["isCancelado"]==false){ //Se a av dele já foi enviada e autorizada pelo Gestor

            foreach($av->rotas as $rota){//Percorre todas as rotas da AV
                if($rota["isVeiculoEmpresa"]==1){//Se a viagem tiver veículo da empresa
                    if( $av->user_id != $user->id){//Verifica se não é vc mesmo
                        $possoEditar = true;
                    }
                }
            }
        }

        if($possoEditar == true){
            return view('avs.verFluxoAdmFrota', ['av' => $av, 'objetivos' => $objetivos, 'veiculosParanacidade' => $veiculosParanacidade, 
            'veiculosProprios' => $veiculosProprios, 'user'=> $user, 'historicos'=> $historicos, 'users'=> $users, 'userAv' => $userAv]);
        }
        else{
            return redirect('avs/autSecretaria')->with('msg', 'Você não tem permissão para avaliar esta av!');
        }
    }

    public function verFluxoFinanceiro($id){
        $objetivos = Objetivo::all();
        $historicosTodos = Historico::all();
        $veiculosParanacidade = VeiculoParanacidade::all();
        $users = User::all();
        $historicos = [];
        $anexos = [];
        $user = auth()->user();
        $usersFiltrados = [];
        $possoEditar = false;
        
        $anexosFinanceiro = AnexoFinanceiro::all();

        $av = Av::findOrFail($id);
        $userAv = User::findOrFail($av->user_id);
        $veiculosProprios = $userAv->veiculosProprios;

        $medicoes = Medicao::all();
        $medicoesFiltradas = [];

        foreach($medicoes as $medicao){
            if($medicao->av_id == $av->id){
                array_push($medicoesFiltradas, $medicao); 
            }
        }
        
        foreach($historicosTodos as $historico){
            if($historico->av_id == $av->id){
                array_push($historicos, $historico);
            }
        }

        foreach($anexosFinanceiro as $a){
            if($a->av_id == $av->id){
                array_push($anexos, $a);
            }
        }

        if($av["isEnviadoUsuario"]==1 && $av["isAprovadoGestor"]==true && $av["isAprovadoFinanceiro"]==false && $av["isCancelado"]==false){ //Se a av dele já foi enviada e autorizada pelo Gestor
            if( $av->user_id != $user->id){//Verifica se não é vc mesmo
                $possoEditar = true;
            }
        }

        $colecao = $this->geraArrayDiasValores($av);
        $arrayDiasValores = $colecao[0];
        $anoSaidaInicial = $colecao[1];
        $mesSaidaInicial = $colecao[2];
        $diaSaidaInicial = $colecao[3];
        $horaSaidaInicial = $colecao[4];
        $minutoSaidaInicial = $colecao[5];
        $mesChegadaInicial = $colecao[6];
        $diaChegadaInicial = $colecao[7];
        $horaChegadaInicial = $colecao[8];
        $minutoChegadaInicial = $colecao[9];
        $mesSaidaFinal = $colecao[10];
        $diaSaidaFinal = $colecao[11];
        $horaSaidaFinal = $colecao[12];
        $minutoSaidaFinal = $colecao[13];
        $mesChegadaFinal = $colecao[14];
        $diaChegadaFinal = $colecao[15];
        $horaChegadaFinal = $colecao[16];
        $minutoChegadaFinal = $colecao[17];
        $dataInicio = $colecao[18];
        $dataFim = $colecao[19];


        if($possoEditar == true){
            return view('avs.verFluxoFinanceiro', ['av' => $av, 'objetivos' => $objetivos, 'veiculosProprios' => $veiculosProprios, 'user'=> $user, 
            'historicos'=> $historicos, 'anexos' => $anexos, 'users'=> $users, 'userAv' => $userAv, 'veiculosParanacidade' => $veiculosParanacidade,
            'medicoesFiltradas' => $medicoesFiltradas, 'arrayDiasValores' => $arrayDiasValores,
            'anoSaidaInicial' => $anoSaidaInicial, 'mesSaidaInicial' => $mesSaidaInicial, 'diaSaidaInicial' => $diaSaidaInicial,
            'horaSaidaInicial' => $horaSaidaInicial, 'minutoSaidaInicial' => $minutoSaidaInicial, 'mesChegadaInicial' => $mesChegadaInicial,
            'diaChegadaInicial' => $diaChegadaInicial, 'horaChegadaInicial' => $horaChegadaInicial, 'minutoChegadaInicial' => $minutoChegadaInicial,
            'mesSaidaFinal' => $mesSaidaFinal, 'diaSaidaFinal' => $diaSaidaFinal, 'horaSaidaFinal' => $horaSaidaFinal, 'minutoSaidaFinal' => $minutoSaidaFinal,
            'mesChegadaFinal' => $mesChegadaFinal, 'diaChegadaFinal' => $diaChegadaFinal, 'horaChegadaFinal' => $horaChegadaFinal, 'minutoChegadaFinal' => $minutoChegadaFinal,
            'dataInicio' => $dataInicio, 'dataFim' => $dataFim]);
        }
        else{
            return redirect('avs/autFinanceiro')->with('msg', 'Você não tem permissão para avaliar esta av!');
        }
    }

    public function gravarReservaHotel(Request $request){
        $rota = Rota::findOrFail($request->rotaId);
        $av = Av::findOrFail($rota->av_id);
        $userAv = User::findOrFail($av->user_id);
        $user = auth()->user();
        $users = User::all();
        $anexoRota = new AnexoRota();
        
        if($request->hasFile('arquivo1') && $request->file('arquivo1')->isValid())
        {
            $requestFile = $request->arquivo1;

            $extension = $requestFile->extension();

            $fileName = md5($requestFile->getClientOriginalName() . strtotime("now")) . "." . $extension;
            
            $requestFile->move('/mnt/arquivos_viagem/AVs/' . $userAv->name . '/' . $av->id . '/', $fileName);

            $anexoRota->anexoHotel = $fileName;
            $anexoRota->usuario_id = $av->user_id;
            $anexoRota->rota_id = $rota->id;
            $anexoRota->descricao = $request->descricao;
            $anexoRota->save();
        }
        return redirect('/avs/realizarReservas/' . $rota->id)->with('msg', 'Anexo salvo com sucesso!');
    }

    public function gravarReservaTransporte(Request $request){
        $rota = Rota::findOrFail($request->get('rotaId'));
        $av = Av::findOrFail($rota->av_id);
        $userAv = User::findOrFail($av->user_id);
        $user = auth()->user();
        $users = User::all();
        $anexoRota = new AnexoRota();

        if($request->hasFile('arquivo2') && $request->file('arquivo2')->isValid())
        {
            $requestFile = $request->arquivo2;

            $extension = $requestFile->extension();

            $fileName = md5($requestFile->getClientOriginalName() . strtotime("now")) . "." . $extension;
            
            $requestFile->move('/mnt/arquivos_viagem/AVs/' . $userAv->name . '/' . $av->id . '/', $fileName);

            $anexoRota->anexoTransporte = $fileName;
            $anexoRota->usuario_id = $av->user_id;
            $anexoRota->rota_id = $rota->id;
            $anexoRota->descricao = $request->descricao;
            $anexoRota->save();
        }

        return redirect('/avs/realizarReservas/' . $rota->id)->with('msg', 'Anexo salvo com sucesso!');
    }

    public function deletarAnexoHotel($id, $rotaId)
    {
        $rota = Rota::findOrFail($rotaId);
        $av = Av::findOrFail($rota->av_id);
        $userAv = User::findOrFail($av->user_id);
        $anexoRota = AnexoRota::findOrFail($id);

        $fileName = $anexoRota->anexoHotel;
        
        $filePath = '/mnt/arquivos_viagem/AVs/' . $userAv->name . '/' . $av->id . '/' . $fileName;
        //dd($filePath);
        if (file_exists($filePath)) {
            //dd($filePath);
            unlink($filePath);
        }

        $anexoRota->delete();
        return redirect('/avs/realizarReservas/' . $rota->id)->with('msg', 'Anexo excluído com sucesso!');
    }

    public function deletarAnexoTransporte($id, $rotaId)
    {
        $rota = Rota::findOrFail($rotaId);
        $av = Av::findOrFail($rota->av_id);
        $userAv = User::findOrFail($av->user_id);
        $anexoRota = AnexoRota::findOrFail($id);

        $fileName = $anexoRota->anexoTransporte;
        $filePath = '/mnt/arquivos_viagem/AVs/' . $userAv->name . '/' . $av->id . '/' . $fileName;

        if (file_exists($filePath)) {
            unlink($filePath);
        }

        $anexoRota->delete();

        return redirect('/avs/realizarReservas/' . $rota->id)->with('msg', 'Anexo excluído com sucesso!');
    }

    public function deletarAnexoFinanceiro($id, $avId)
    {
        $av = Av::findOrFail($avId);
        $userAv = User::findOrFail($av->user_id);
        $anexoFin = AnexoFinanceiro::findOrFail($id);

        $fileName = $anexoFin->anexoFinanceiro;
        
        $filePath = '/mnt/arquivos_viagem/AVs/' . $userAv->name . '/' . $av->id . '/adiantamentos' . '/' . $fileName;
        
        if (file_exists($filePath)) {
            unlink($filePath);
        }

        $anexoFin->delete();
        return redirect('/avs/verFluxoFinanceiro/' . $av->id)->with('msg', 'Anexo excluído com sucesso!');
    }

    public function deletarComprovante($id, $avId)
    {
        $av = Av::findOrFail($avId);
        $userAv = User::findOrFail($av->user_id);
        $comprovante = ComprovanteDespesa::findOrFail($id);

        $fileName = $comprovante->anexoDespesa;
        
        $filePath = '/mnt/arquivos_viagem/AVs/' . $userAv->name . '/' . $av->id . '/comprovantesDespesa' . '/' . $fileName;
        
        if (file_exists($filePath)) {
            unlink($filePath);
        }

        $comprovante->delete();
        return redirect('/avs/fazerPrestacaoContas/' . $av->id)->with('msg', 'Comprovante excluído com sucesso!');
    }

    public function deletarComprovanteAcertoContas($id, $avId)
    {
        $av = Av::findOrFail($avId);
        $userAv = User::findOrFail($av->user_id);
        $historicoPc = HistoricoPc::findOrFail($id);

        $fileName = $historicoPc->anexoRelatorio;
        
        $filePath = '/mnt/arquivos_viagem/AVs/' . $userAv->name . '/' . $av->id . '/resumo' . '/' . $fileName;
        
        if (file_exists($filePath)) {
            unlink($filePath);
        }

        $historicoPc->delete();
        return redirect('/avs/realizarAcertoContasFinanceiro/' . $av->id)->with('msg', 'Comprovante excluído com sucesso!');
    }

    public function deletarComprovanteAcertoContasUsuario($id, $avId)
    {
        $av = Av::findOrFail($avId);
        $userAv = User::findOrFail($av->user_id);
        $historicoPc = HistoricoPc::findOrFail($id);

        $fileName = $historicoPc->anexoRelatorio;
        
        $filePath = '/mnt/arquivos_viagem/AVs/' . $userAv->name . '/' . $av->id . '/resumo' . '/' . $fileName;
        
        if (file_exists($filePath)) {
            unlink($filePath);
        }

        $historicoPc->delete();
        return redirect('/avs/validarAcertoContasUsuario/' . $av->id)->with('msg', 'Comprovante excluído com sucesso!');
    }


    public function gravarAdiantamento(Request $request){
        
        $av = Av::findOrFail($request->get('avId'));
        $userAv = User::findOrFail($av->user_id);
        $user = auth()->user();
        $users = User::all();
        $anexoFinanceiro = new AnexoFinanceiro();
        
        if($request->hasFile('arquivo1') && $request->file('arquivo1')->isValid())
        {
            $requestFile = $request->arquivo1;

            $extension = $requestFile->extension();

            $fileName = md5($requestFile->getClientOriginalName() . strtotime("now")) . "." . $extension;
            
            $requestFile->move('/mnt/arquivos_viagem/AVs/' . $userAv->name . '/' . $av->id . '/adiantamentos' . '/', $fileName);

            $anexoFinanceiro->anexoFinanceiro = $fileName;
            $anexoFinanceiro->av_id = $av->id;
            $anexoFinanceiro->descricao = $request->descricao;
            $anexoFinanceiro->save();
        }

        return redirect('/avs/verFluxoFinanceiro/' . $av->id)->with('msg', 'Anexo salvo com sucesso!');
    }

    public function gravarComprovante(Request $request){
        
        $av = Av::findOrFail($request->get('avId'));
        $userAv = User::findOrFail($av->user_id);
        $user = auth()->user();
        $users = User::all();
        $comprovante = new ComprovanteDespesa();
        
        if($request->hasFile('arquivo1') && $request->file('arquivo1')->isValid())
        {
            $requestFile = $request->arquivo1;

            if ($requestFile->getClientOriginalExtension() !== 'pdf') {
                return redirect('/avs/fazerPrestacaoContas/'  . $av->id)->with('error', 'Por favor, envie apenas arquivos PDF.');
            }

            $extension = $requestFile->extension();

            $fileName = md5($requestFile->getClientOriginalName() . strtotime("now")) . "." . $extension;
            
            $requestFile->move('/mnt/arquivos_viagem/AVs/' . $userAv->name . '/' . $av->id . '/comprovantesDespesa' . '/', $fileName);

            $comprovante->anexoDespesa = $fileName;
            $comprovante->av_id = $av->id;
            $comprovante->descricao = $request->descricao;

            if($request->valorReais != null){
                $valorReaisFiltrado = str_replace(',', '.', $request->valorReais);
                $comprovante->valorReais = $valorReaisFiltrado;
            }
            
            if($request->valorDolar != null){
                $valorDolarFiltrado = str_replace(',', '.', $request->valorDolar);
            $comprovante->valorDolar = $valorDolarFiltrado;
            }
            
            $timezone = new DateTimeZone('America/Sao_Paulo');
            $comprovante->dataOcorrencia = new DateTime('now', $timezone);
            $comprovante->save();
        }

        return redirect('/avs/fazerPrestacaoContas/' . $av->id)->with('msg', 'Comprovante salvo com sucesso!');
    }

    public function gravarComprovanteAcertoContas(Request $request){
        
        $av = Av::findOrFail($request->get('avId'));
        $userAv = User::findOrFail($av->user_id);
        $user = auth()->user();
        $users = User::all();
        $historicoPc = new HistoricoPc();
        
        if($request->hasFile('arquivo1') && $request->file('arquivo1')->isValid())
        {
            $requestFile = $request->arquivo1;

            $extension = $requestFile->extension();

            $fileName = md5($requestFile->getClientOriginalName() . strtotime("now")) . "." . $extension;
            
            $requestFile->move('/mnt/arquivos_viagem/AVs/' . $userAv->name . '/' . $av->id . '/resumo' . '/', $fileName);

            $historicoPc->anexoRelatorio = $fileName;
            $historicoPc->av_id = $av->id;
            $historicoPc->ocorrencia = "Financeiro realizou acerto de contas";
            $historicoPc->comentario = "Comprovante Acerto de Contas Financeiro";
            $timezone = new DateTimeZone('America/Sao_Paulo');
            $historicoPc->dataOcorrencia = new DateTime('now', $timezone);
            $historicoPc->save();
        }

        return redirect('/avs/realizarAcertoContasFinanceiro/' . $av->id)->with('msg', 'Comprovante salvo com sucesso!');
    }

    public function gravarComprovanteAcertoContasUsuario(Request $request){
        
        $av = Av::findOrFail($request->get('avId'));
        $userAv = User::findOrFail($av->user_id);
        $user = auth()->user();
        $users = User::all();
        $historicoPc = new HistoricoPc();
        
        if($request->hasFile('arquivo1') && $request->file('arquivo1')->isValid())
        {
            $requestFile = $request->arquivo1;

            $extension = $requestFile->extension();

            $fileName = md5($requestFile->getClientOriginalName() . strtotime("now")) . "." . $extension;
            
            $requestFile->move('/mnt/arquivos_viagem/AVs/' . $userAv->name . '/' . $av->id . '/resumo' . '/', $fileName);

            $historicoPc->anexoRelatorio = $fileName;
            $historicoPc->av_id = $av->id;
            $historicoPc->ocorrencia = "Usuário realizou acerto de contas";
            $historicoPc->comentario = "Comprovante Acerto de Contas Usuário";
            $timezone = new DateTimeZone('America/Sao_Paulo');
            $historicoPc->dataOcorrencia = new DateTime('now', $timezone);
            $historicoPc->save();
        }

        return redirect('/avs/validarAcertoContasUsuario/' . $av->id)->with('msg', 'Comprovante salvo com sucesso!');
    }

    public function realizarReservas($id){
        
        $rota = Rota::findOrFail($id);
        $anexos = $rota->anexos;
        $av = Av::findOrFail($rota->av_id);
        $rotasDaAv = $av->rotas;
        $anexosRotas = AnexoRota::all();
        $userAv = User::findOrFail($av->user_id);
        $users = User::all();
        $user = auth()->user();
        $rotaPertenceAv = false;
        $possoEditar = false;
        $objetivos = Objetivo::all();

        if(($av["isEnviadoUsuario"]==1 && $av["isAprovadoGestor"]==true  && $av["isRealizadoReserva"]==false && $av["isCancelado"]==false)
        || ($av["isCancelado"]== true && $av["isRealizadoReserva"]== true)){ //Se a av dele já foi enviada e autorizada pelo Gestor
            $isNecessarioAvaliacaoDiretoria = false;
            $passouPelaDiretoria = false;
            foreach($av->rotas as $r){//Percorre todas as rotas da AV
                if($r["isViagemInternacional"]==1 || $r["isVeiculoProprio"]==1){//Se a viagem for internacional ou tiver veículo próprio
                    $isNecessarioAvaliacaoDiretoria = true;

                    if($av["isVistoDiretoria"]==true)
                    {
                        $passouPelaDiretoria = true;
                    }
                }
                if($r->id == $id){
                    $rotaPertenceAv = true;
                }
            }
            if(($isNecessarioAvaliacaoDiretoria == true && $passouPelaDiretoria == true) || $isNecessarioAvaliacaoDiretoria == false){
                //if( $av->user_id != $user->id){//Verifica se não é vc mesmo
                    if($rotaPertenceAv == true){
                        $possoEditar = true;
                    }
                //}
            }
        }

        //Puxar do banco todas os arquivos e passar para a tela



        if($possoEditar == true){
            return view('avs.realizarReservas', ['rota' => $rota, 'user'=> $user, 'av' => $av, 'users' => $users, 'anexos' => $anexos, 'userAv' => $userAv, 
            'objetivos' => $objetivos, 'rotasDaAv' => $rotasDaAv, 'anexosRotas' => $anexosRotas]);
        }
        else{
            return redirect('avs/autSecretaria')->with('msg', 'Você não tem permissão para avaliar esta av!');
        }
    }

    public function verDetalhesAv($id){

        $objetivos = Objetivo::all();
        $historicosTodos = Historico::all();
        $veiculosParanacidade = VeiculoParanacidade::all();
        $users = User::all();
        $historicos = [];
        $anexos = [];
        $user = auth()->user();
        $usersFiltrados = [];
        $possoEditar = false;
        $veiculosProprios = $user->veiculosProprios;
        $anexosFinanceiro = [];
        $anexosRotas = [];
        $comprovantesAll = ComprovanteDespesa::all();
        $comprovantes = [];
        $valorAcertoContasReal = 0;
        $valorAcertoContasDolar = 0;
        $isInternacional = false;

        $av = Av::findOrFail($id);
        $userAv = User::findOrFail($av->user_id);
        $historicoPcAll = HistoricoPc::all();
        $historicoPc = [];
        $valorRecebido = null;

        $medicoes = Medicao::all();
        $medicoesFiltradas = [];

        foreach($medicoes as $medicao){
            if($medicao->av_id == $av->id){
                array_push($medicoesFiltradas, $medicao); 
            }
        }

        foreach($comprovantesAll as $comp){
            if($comp->av_id == $av->id){
                array_push($comprovantes, $comp);
            }
        }

        foreach($comprovantes as $compFiltrado){
            $valorAcertoContasReal += $compFiltrado->valorReais;
            $valorAcertoContasDolar += $compFiltrado->valorDolar;
        }
        
        foreach($historicoPcAll as $hisPc){
            if($hisPc->av_id == $av->id){
                array_push($historicoPc, $hisPc);
            }
            if($hisPc->av_id == $av->id && $hisPc->comentario == "Adiantamento realizado - valor inicial"){
                $valorRecebido = $hisPc;
            }
        }
        if($valorRecebido == null){
            $valorRecebido = new HistoricoPc();
            $valorRecebido->valorReais = 0;
            $valorRecebido->valorExtraReais = 0;
            $valorRecebido->valorDolar = 0;
            $valorRecebido->valorExtraDolar = 0;
        }

        foreach($av->rotas as $r){//Verifica todas as rotas da AV
            foreach($r->anexos as $a){// Verifica cada um dos anexos da rota
                array_push($anexosRotas, $a);// Empilha no array cada um dos anexos
            }
        }
        
        foreach($historicosTodos as $historico){
            if($historico->av_id == $av->id){
                array_push($historicos, $historico);
            }
        }

        foreach($av->anexosFinanceiro as $anexF){
                array_push($anexosFinanceiro, $anexF);
        }

        foreach($av->rotas as $r){
            if($r->isViagemInternacional == true){
                $isInternacional = true;
            }
        }

        return view('avs.verDetalhesAv', ['av' => $av, 'objetivos' => $objetivos, 'veiculosProprios' => $veiculosProprios, 
        'user'=> $user, 'historicos'=> $historicos, 'anexosRotas' => $anexosRotas, 'anexosFinanceiro' => $anexosFinanceiro, 
        'users'=> $users, 'userAv' => $userAv, 'historicoPc' => $historicoPc, 'comprovantes' => $comprovantes, 'valorRecebido' => $valorRecebido,
        'valorAcertoContasReal'=>$valorAcertoContasReal, 'valorAcertoContasDolar'=>$valorAcertoContasDolar, 'veiculosParanacidade' => $veiculosParanacidade,
        'isInternacional' => $isInternacional, 'medicoesFiltradas' => $medicoesFiltradas]);
    }

    public function verDetalhesPc($id){

        $objetivos = Objetivo::all();
        $historicosTodos = Historico::all();
        $veiculosParanacidade = VeiculoParanacidade::all();
        $users = User::all();
        $historicos = [];
        $anexos = [];
        $user = auth()->user();
        $usersFiltrados = [];
        $possoEditar = false;
        $veiculosProprios = $user->veiculosProprios;
        $anexosFinanceiro = [];
        $anexosRotas = [];
        $comprovantesAll = ComprovanteDespesa::all();
        $comprovantes = [];
        $valorAcertoContasReal = 0;
        $valorAcertoContasDolar = 0;
        $isInternacional = false;

        $av = Av::findOrFail($id);
        $userAv = User::findOrFail($av->user_id);
        $historicoPcAll = HistoricoPc::all();
        $historicoPc = [];
        $valorRecebido = null;

        $medicoes = Medicao::all();
        $medicoesFiltradas = [];

        foreach($medicoes as $medicao){
            if($medicao->av_id == $av->id){
                array_push($medicoesFiltradas, $medicao); 
            }
        }

        foreach($comprovantesAll as $comp){
            if($comp->av_id == $av->id){
                array_push($comprovantes, $comp);
            }
        }

        foreach($comprovantes as $compFiltrado){
            $valorAcertoContasReal += $compFiltrado->valorReais;
            $valorAcertoContasDolar += $compFiltrado->valorDolar;
        }
        
        foreach($historicoPcAll as $hisPc){
            if($hisPc->av_id == $av->id){
                array_push($historicoPc, $hisPc);
            }
            if($hisPc->av_id == $av->id && $hisPc->comentario == "Adiantamento realizado - valor inicial"){
                $valorRecebido = $hisPc;
            }
        }
        if($valorRecebido == null){
            $valorRecebido = new HistoricoPc();
            $valorRecebido->valorReais = 0;
            $valorRecebido->valorExtraReais = 0;
            $valorRecebido->valorDolar = 0;
            $valorRecebido->valorExtraDolar = 0;
        }

        foreach($av->rotas as $r){//Verifica todas as rotas da AV
            foreach($r->anexos as $a){// Verifica cada um dos anexos da rota
                array_push($anexosRotas, $a);// Empilha no array cada um dos anexos
            }
        }
        
        foreach($historicosTodos as $historico){
            if($historico->av_id == $av->id){
                array_push($historicos, $historico);
            }
        }

        foreach($av->anexosFinanceiro as $anexF){
                array_push($anexosFinanceiro, $anexF);
        }

        foreach($av->rotas as $r){
            if($r->isViagemInternacional == true){
                $isInternacional = true;
            }
        }

        return view('avs.verDetalhesPc', ['av' => $av, 'objetivos' => $objetivos, 'veiculosProprios' => $veiculosProprios, 
        'user'=> $user, 'historicos'=> $historicos, 'anexosRotas' => $anexosRotas, 'anexosFinanceiro' => $anexosFinanceiro, 
        'users'=> $users, 'userAv' => $userAv, 'historicoPc' => $historicoPc, 'comprovantes' => $comprovantes, 'valorRecebido' => $valorRecebido,
        'valorAcertoContasReal'=>$valorAcertoContasReal, 'valorAcertoContasDolar'=>$valorAcertoContasDolar, 'veiculosParanacidade' => $veiculosParanacidade,
        'isInternacional' => $isInternacional, 'medicoesFiltradas' => $medicoesFiltradas]);
    }

    public function verDetalhesAvGerenciar($id){

        $objetivos = Objetivo::all();
        $historicosTodos = Historico::all();
        $veiculosParanacidade = VeiculoParanacidade::all();
        $users = User::all();
        $historicos = [];
        $anexos = [];
        $user = auth()->user();
        $usersFiltrados = [];
        $possoEditar = false;
        $veiculosProprios = $user->veiculosProprios;
        $anexosFinanceiro = [];
        $anexosRotas = [];
        $comprovantesAll = ComprovanteDespesa::all();
        $comprovantes = [];
        $valorAcertoContasReal = 0;
        $valorAcertoContasDolar = 0;

        $av = Av::findOrFail($id);
        $userAv = User::findOrFail($av->user_id);
        $historicoPcAll = HistoricoPc::all();
        $historicoPc = [];
        $valorRecebido = null;

        $medicoes = Medicao::all();
        $medicoesFiltradas = [];

        foreach($medicoes as $medicao){
            if($medicao->av_id == $av->id){
                array_push($medicoesFiltradas, $medicao); 
            }
        }

        foreach($comprovantesAll as $comp){
            if($comp->av_id == $av->id){
                array_push($comprovantes, $comp);
            }
        }

        foreach($comprovantes as $compFiltrado){
            $valorAcertoContasReal += $compFiltrado->valorReais;
            $valorAcertoContasDolar += $compFiltrado->valorDolar;
        }
        
        foreach($historicoPcAll as $hisPc){
            if($hisPc->av_id == $av->id){
                array_push($historicoPc, $hisPc);
            }
            if($hisPc->av_id == $av->id && $hisPc->comentario == "Adiantamento realizado - valor inicial"){
                $valorRecebido = $hisPc;
            }
        }
        if($valorRecebido == null){
            $valorRecebido = new HistoricoPc();
            $valorRecebido->valorReais = 0;
            $valorRecebido->valorExtraReais = 0;
            $valorRecebido->valorDolar = 0;
            $valorRecebido->valorExtraDolar = 0;
        }

        foreach($av->rotas as $r){//Verifica todas as rotas da AV
            foreach($r->anexos as $a){// Verifica cada um dos anexos da rota
                array_push($anexosRotas, $a);// Empilha no array cada um dos anexos
            }
        }
        
        foreach($historicosTodos as $historico){
            if($historico->av_id == $av->id){
                array_push($historicos, $historico);
            }
        }

        foreach($av->anexosFinanceiro as $anexF){
                array_push($anexosFinanceiro, $anexF);
        }

        return view('avs.verDetalhesAvGerenciar', ['av' => $av, 'objetivos' => $objetivos, 'veiculosProprios' => $veiculosProprios, 
        'user'=> $user, 'historicos'=> $historicos, 'anexosRotas' => $anexosRotas, 'anexosFinanceiro' => $anexosFinanceiro, 
        'users'=> $users, 'userAv' => $userAv, 'historicoPc' => $historicoPc, 'comprovantes' => $comprovantes, 'valorRecebido' => $valorRecebido,
        'valorAcertoContasReal'=>$valorAcertoContasReal, 'valorAcertoContasDolar'=>$valorAcertoContasDolar, 
        'veiculosParanacidade' => $veiculosParanacidade, 'medicoesFiltradas' => $medicoesFiltradas]);
    }

    public function gestorAprovarAv(Request $request){

        $user = auth()->user();
        $av = Av::findOrFail($request->get('id'));
        $userAv = User::findOrFail($av->user_id);

        $isVeiculoProprio = false;
        $isInternacional = false;
        $dados = [];

        $historico = new Historico();
        $timezone = new DateTimeZone('America/Sao_Paulo');
        $historico->dataOcorrencia = new DateTime('now', $timezone);
        $historico->tipoOcorrencia = "Aprovado pelo Gestor";
        $historico->comentario = $request->get('comentario');
        $historico->perfilDonoComentario = "Gestor";
        $historico->usuario_id = $av->user_id;
        $historico->usuario_comentario_id = $user->id;
        $historico->av_id = $av->id;

        foreach($av->rotas as $rota){
            if($rota["isVeiculoProprio"]==1){
                $isVeiculoProprio = true;
            }
            if($rota["isViagemInternacional"]==1){
                $isInternacional = true;
            }
        }

        if($isInternacional==true){
            $dados = array(
                "isAprovadoGestor" => 1,
                "status" => "AV Internacional cadastrada no sistema"
            );
        }
        else if($isVeiculoProprio == true){
            $dados = array(
                "isAprovadoGestor" => 1,
                "status" => "Aguardando aprovação da DAF"
            );
        }
        else{
            $dados = array(
                "isAprovadoGestor" => 1,
                "status" => "Aguardando reserva pela CAD e adiantamento pela CFI"
            );
        }

        if($isInternacional == true){
            //Gera o PDF
            $avs = Av::all();
            $objetivos = Objetivo::all();
            $historicosTodos = Historico::all();
            $historicos = [];
            $users = User::all();

            foreach($historicosTodos as $hist){
                if($hist->av_id == $av->id){
                    array_push($historicos, $hist);
                }
            }
            $options = new Options();
            $options->set('defaultFont', 'sans-serif');
            $dompdf = new Dompdf($options);

            $dompdf = new Dompdf();
            $dompdf->loadHtml(view('relatorioViagemInternacional', compact('avs', 'av', 'objetivos', 'historicos', 'users', 'userAv')));
            $dompdf->render();

            $nomeArquivo = md5("relatorio" . strtotime("now")) . ".pdf";
            $caminhoDiretorio = '/mnt/arquivos_viagem/AVs/' . $userAv->name . '/' . $av->id . '/internacional' . '/';
            $caminhoArquivo = $caminhoDiretorio . $nomeArquivo;
            if (!file_exists($caminhoDiretorio)) {
                mkdir($caminhoDiretorio, 0777, true);
            }
            file_put_contents($caminhoArquivo, $dompdf->output());

            //Salva no HistoricoPC
            $historicoPc = new HistoricoPc();
            $historicoPc->valorReais = $av->valorReais;
            $historicoPc->valorDolar = $av->valorDolar;
            $historicoPc->valorExtraReais = $av->valorExtraReais;
            $historicoPc->valorExtraDolar = $av->valorExtraDolar;
            $historicoPc->ocorrencia ="Gestor aprovou AV internacional";
            $historicoPc->comentario ="AV Internacional gerada";
            $historicoPc->av_id = $av->id;

            $historicoPc->dataOcorrencia = new DateTime('now', $timezone);
            $historicoPc->anexoRelatorio = $nomeArquivo;
            $historicoPc->save();
        }

        Av::findOrFail($av->id)->update($dados);
        $historico->save();

        $permissionDir = Permission::where('name', 'aprov avs diretoria')->first();
        $permission2 = Permission::where('name', 'aprov avs secretaria')->first();
        $permission3 = Permission::where('name', 'aprov avs financeiro')->first();

        if($isInternacional==true){
            
            Mail::to($userAv->username)
                        ->send(new EnvioGestorToUsuarioViagemInternacional($userAv->id));
        }
        else if($isVeiculoProprio == true){
            $users = User::all();
            foreach($users as $uDir){
                try {
                    if($uDir->hasPermissionTo($permissionDir)){
                        Mail::to($uDir->username)
                        ->send(new EnvioGestorToDiretoria($av->user_id, $uDir->id));
                    }
                } catch (\Throwable $th) {
                }
            }
        }
        else{
            $users = User::all();
            foreach($users as $u){
                try {
                    if($u->hasPermissionTo($permission2)){
                        Mail::to($u->username)
                        ->send(new EnvioGestorToSecretaria($av->user_id, $u->id));
                    }
                } catch (\Throwable $th) {
                }
            }
            foreach($users as $u2){
                try {
                    if($u2->hasPermissionTo($permission3)){
                        Mail::to($u2->username)
                        ->send(new EnvioGestorToFinanceiro($av->user_id, $u2->id));
                    }
                } catch (\Throwable $th) {
                }
            }
        }

        return redirect('/avs/autGestor')->with('msg', 'AV aprovada!');
    }

    public function gestorReprovarAv(Request $request){
        $av = Av::findOrFail($request->get('id'));
        $avs = Av::all();
        $user = auth()->user();
        $userAv = User::findOrFail($av->user_id);

        $historico = new Historico();
        $timezone = new DateTimeZone('America/Sao_Paulo');
        $historico->dataOcorrencia = new DateTime('now', $timezone);
        $historico->tipoOcorrencia = "Reprovado pelo Gestor";
        $historico->comentario = $request->get('comentario');
        $historico->perfilDonoComentario = "Gestor";
        $historico->usuario_id = $av->user_id;
        $historico->usuario_comentario_id = $user->id;
        $historico->av_id = $av->id;

        $regras = [
            'comentario' => 'required'
        ];

        $mensagens = [
            'required' => 'Este campo não pode estar em branco',
        ];

        $request->validate($regras, $mensagens);

        $dados = array(
            "isEnviadoUsuario" => 0,
            "isAprovadoGestor" => 0,
            "isRealizadoReserva" => 0,
            "isAprovadoFinanceiro" =>0,
            "isVistoDiretoria" =>0,
            "isAprovadoCarroDiretoriaExecutiva" =>0,
            "isAprovadoViagemInternacional" =>0,
            "status" => "Aguardando envio para o Gestor - Reprovado Gestor"
        );

        Av::findOrFail($av->id)->update($dados);
        $historico->save();

        Mail::to($userAv->username)
                        ->send(new EnvioGestorToUsuarioReprovarAv($userAv->id));

        return redirect('/avs/autGestor')->with('msg', 'AV reprovada!');
    }

    public function secretariaAprovarAv(Request $request){
        $user = auth()->user();
        $av = Av::findOrFail($request->get('id'));
        $isVeiculoEmpresa = false;

        $dados = [];

        if($av["isCancelado"]== false){
            $historico = new Historico();
            $timezone = new DateTimeZone('America/Sao_Paulo');
            $historico->dataOcorrencia = new DateTime('now', $timezone);
            $historico->tipoOcorrencia = "Reserva realizada pelo CAD";
            $historico->comentario = $request->get('comentario');
            $historico->perfilDonoComentario = "CAD";
            $historico->usuario_id = $av->user_id;
            $historico->usuario_comentario_id = $user->id;
            $historico->av_id = $av->id;
            $isVeiculoEmpresa = false;

            foreach($av->rotas as $r){
                if($r->isVeiculoEmpresa == 1){
                    $isVeiculoEmpresa = true;
                }
            }
        }else if($av["isCancelado"]== true){
            $historico = new Historico();
            $timezone = new DateTimeZone('America/Sao_Paulo');
            $historico->dataOcorrencia = new DateTime('now', $timezone);
            $historico->tipoOcorrencia = "Cancelamento de Reserva realizado pelo CAD";
            $historico->comentario = $request->get('comentario');
            $historico->perfilDonoComentario = "CAD";
            $historico->usuario_id = $av->user_id;
            $historico->usuario_comentario_id = $user->id;
            $historico->av_id = $av->id;
        }
        
        if($av->isAprovadoFinanceiro == 0 && $av["isCancelado"]== false){
            if($isVeiculoEmpresa == true && $av->isReservadoVeiculoParanacidade == false){
                foreach($av->rotas as $r){
                    if($r->veiculoParanacidade_id = null){
                        return redirect('/avs/verFluxoSecretaria/'. $av->id)->with('msg', 'Escolha o veículo!');
                    }
                }
                $dados = array(
                    "isRealizadoReserva" => 1,
                    "isReservadoVeiculoParanacidade" => 1,
                    "status" => "Aguardando adiantamento da CFI"
                );
            }
            else{
                $dados = array(
                    "isRealizadoReserva" => 1,
                    "status" => "Aguardando adiantamento da CFI"
                );
            }
        }
        else if($isVeiculoEmpresa == true && $av->isAprovadoFinanceiro == 1 && $av["isCancelado"]== false){
           
            $dados = array(
                "isRealizadoReserva" => 1,
                "isReservadoVeiculoParanacidade" => 1,
                "status" => "Aguardando prestação de contas do usuário"
            );
        }
        else if($isVeiculoEmpresa == false && $av->isAprovadoFinanceiro == 1 && $av["isCancelado"]== false){
            $dados = array(
                "isRealizadoReserva" => 1,
                "status" => "Aguardando prestação de contas do usuário"
            );
        }
        else if($av["isCancelado"]== true && $av["isRealizadoReserva"]== true && $av["isAprovadoFinanceiro"]== true){
            $dados = array(
                "status" => "AV Cancelada - Aguardando prestação de contas do usuário",
                "isRealizadoCancelamentoReserva" => 1
            );
        }
        else if($av["isCancelado"]== true && $av["isRealizadoReserva"]== true && $av["isAprovadoFinanceiro"]== false){
            $dados = array(
                "status" => "AV Cancelada (Reservas canceladas pela CAD)",
                "isRealizadoCancelamentoReserva" => 1
            );
        }

        Av::findOrFail($av->id)->update($dados);
        $historico->save();

        if($av["isCancelado"]== false){
            $userAv = User::findOrFail($av->user_id);
            try {
                    Mail::to($userAv->username)
                    ->send(new EnvioSecretariaToUsuario($userAv->id));
                
            } catch (\Throwable $th) {
            }
        }

        return redirect('/avs/autSecretaria')->with('msg', 'AV aprovada pelo CAD!');
    }

    public function secretariaReprovarAv(Request $request){
        
        $user = auth()->user();
        $av = Av::findOrFail($request->get('id'));
        $userAv = User::findOrFail($av->user_id);

        $historico = new Historico();
        $timezone = new DateTimeZone('America/Sao_Paulo');
        $historico->dataOcorrencia = new DateTime('now', $timezone);
        $historico->tipoOcorrencia = "AV reprovada pelo CAD";
        $historico->comentario = $request->get('comentario');
        $historico->perfilDonoComentario = "CAD";
        $historico->usuario_id = $av->user_id;
        $historico->usuario_comentario_id = $user->id;
        $historico->av_id = $av->id;

        $regras = [
            'comentario' => 'required'
        ];

        $mensagens = [
            'required' => 'Este campo não pode estar em branco',
        ];

        $request->validate($regras, $mensagens);

        $dados = array(
            "isEnviadoUsuario" => 0,
            "isAprovadoGestor" => 0,
            "isRealizadoReserva" => 0,
            "isAprovadoFinanceiro" =>0,
            "isVistoDiretoria" =>0,
            "isAprovadoCarroDiretoriaExecutiva" =>0,
            "isAprovadoViagemInternacional" =>0,
            "status" => "Aguardando envio para o Gestor - Reprovado CAD"
        );

        Av::findOrFail($av->id)->update($dados);
        $historico->save();

        Mail::to($userAv->username)
                        ->send(new EnvioSecretariaToUsuarioReprovarAv($userAv->id));

        return redirect('/avs/autSecretaria')->with('msg', 'AV reprovada pelo CAD!');
    }

    public function financeiroAprovarAv(Request $request){

        $user = auth()->user();
        $av = Av::findOrFail($request->get('id'));
        $userAv = User::findOrFail($av->user_id);

        $dados = [];

        $historico = new Historico();
        $timezone = new DateTimeZone('America/Sao_Paulo');
        $historico->dataOcorrencia = new DateTime('now', $timezone);

        $historico->tipoOcorrencia = "Adiantamento realizado pelo Financeiro";
        $historico->comentario = $request->get('comentario');
        $historico->perfilDonoComentario = "Financeiro";
        $historico->usuario_id = $av->user_id;
        $historico->usuario_comentario_id = $user->id;
        $historico->av_id = $av->id;
        $isVeiculoEmpresa = false;

        //Gera o PDF
        $avs = Av::all();
        $objetivos = Objetivo::all();
        $historicosTodos = Historico::all();
        $historicos = [];
        $users = User::all();

        $colecao = $this->geraArrayDiasValores($av);
        $arrayDiasValores = $colecao[0];
        $anoSaidaInicial = $colecao[1];
        $mesSaidaInicial = $colecao[2];
        $diaSaidaInicial = $colecao[3];
        $horaSaidaInicial = $colecao[4];
        $minutoSaidaInicial = $colecao[5];
        $mesChegadaInicial = $colecao[6];
        $diaChegadaInicial = $colecao[7];
        $horaChegadaInicial = $colecao[8];
        $minutoChegadaInicial = $colecao[9];
        $mesSaidaFinal = $colecao[10];
        $diaSaidaFinal = $colecao[11];
        $horaSaidaFinal = $colecao[12];
        $minutoSaidaFinal = $colecao[13];
        $mesChegadaFinal = $colecao[14];
        $diaChegadaFinal = $colecao[15];
        $horaChegadaFinal = $colecao[16];
        $minutoChegadaFinal = $colecao[17];
        $dataInicio = $colecao[18];
        $dataFim = $colecao[19];

        foreach($historicosTodos as $hist){
            if($hist->av_id == $av->id){
                array_push($historicos, $hist);
            }
        }
        $options = new Options();
        $options->set('defaultFont', 'sans-serif');
        $dompdf = new Dompdf($options);

        $dompdf = new Dompdf();
        $dompdf->loadHtml(view('relatorio', compact('avs', 'av', 'objetivos', 'historicos', 'users', 'userAv', 'arrayDiasValores', 'anoSaidaInicial', 
        'mesSaidaInicial', 'diaSaidaInicial', 'horaSaidaInicial', 'minutoSaidaInicial', 'mesChegadaInicial', 'diaChegadaInicial', 'horaChegadaInicial', 
        'minutoChegadaInicial', 'mesSaidaFinal', 'diaSaidaFinal', 'horaSaidaFinal', 'minutoSaidaFinal', 'mesChegadaFinal', 'diaChegadaFinal', 'horaChegadaFinal', 
        'minutoChegadaFinal', 'dataInicio', 'dataFim', 'isVeiculoEmpresa')));
        $dompdf->render();

        $nomeArquivo = md5("relatorio" . strtotime("now")) . ".pdf";
        $caminhoDiretorio = '/mnt/arquivos_viagem/AVs/' . $userAv->name . '/' . $av->id . '/resumo' . '/';
        $caminhoArquivo = $caminhoDiretorio . $nomeArquivo;
        if (!file_exists($caminhoDiretorio)) {
            mkdir($caminhoDiretorio, 0777, true);
        }
        file_put_contents($caminhoArquivo, $dompdf->output());
 
        //Salva no HistoricoPC
        $historicoPc = new HistoricoPc();
        $historicoPc->valorReais = $av->valorReais;
        $historicoPc->valorDolar = $av->valorDolar;
        $historicoPc->valorExtraReais = $av->valorExtraReais;
        $historicoPc->valorExtraDolar = $av->valorExtraDolar;
        $historicoPc->ocorrencia ="Financeiro aprovou AV";
        $historicoPc->comentario ="Adiantamento realizado - valor inicial";
        $historicoPc->av_id = $av->id;

        $historicoPc->dataOcorrencia = new DateTime('now', $timezone);
        $historicoPc->anexoRelatorio = $nomeArquivo;

        //----------------------------------------------------------------
        foreach ($av->rotas as $r){
            if($r->isVeiculoEmpresa == 1){
                $isVeiculoEmpresa = true;
            }
        }

        if($av->isRealizadoReserva == false){
            $dados = array(
                "isAprovadoFinanceiro" => 1,
                "status" => "Aguardando reservas pelo CAD"
            );
        }
        else if($av->isRealizadoReserva == true){
            $dados = array(
                "isAprovadoFinanceiro" => 1,
                "status" => "Aguardando prestação de contas do usuário"
            );
        }

        Av::findOrFail($av->id)->update($dados);
        $historico->save();
        $historicoPc->save();

        $permission = Permission::where('name', 'aprov avs frota')->first();

        Mail::to($userAv->username)
                        ->send(new EnvioFinanceiroToUsuarioAdiantamento($userAv->id));
    
        return redirect('/avs/autFinanceiro')->with('msg', 'AV aprovada pelo financeiro!');
    }

    public function usuarioEnviarPrestacaoContas(Request $request){

        $user = auth()->user();
        $av = Av::findOrFail($request->get('id'));

        $dados = [];

        $historico = new Historico();
        $timezone = new DateTimeZone('America/Sao_Paulo');
        $historico->dataOcorrencia = new DateTime('now', $timezone);
        $historico->tipoOcorrencia = "Prestação de contas realizada pelo usuário";
        $historico->comentario = $request->get('comentario');
        $historico->perfilDonoComentario = "Usuário";
        $historico->usuario_id = $av->user_id;
        $historico->usuario_comentario_id = $user->id;
        $historico->av_id = $av->id;

        $comprovantes = ComprovanteDespesa::all();
        $comprovantesFiltrados = [];
        $somaDespesasReal = 0;
        $somaDespesasDolar = 0;

        $regras = [
            'contatos' => 'required',
            'atividades' => 'required',
            'conclusoes' => 'required',
        ];

        $mensagens = [
            'required' => 'Este campo não pode estar em branco',
        ];

        foreach($comprovantes as $c){
            if($c->av_id == $av->id){
                array_push($comprovantesFiltrados, $c);
                $somaDespesasReal += $c->valorReais;
                $somaDespesasDolar += $c->valorDolar;
            }
        }
        
        if($av->isCancelado == true){
            $dados = array(
                "isPrestacaoContasRealizada" => 1,
                "status" => "AV Cancelada - Aguardando aprovação da Prestação de Contas pelo Financeiro",
                "contatos" => $request->get('contatos'),
                "atividades" => $request->get('atividades'),
                "conclusoes" => $request->get('conclusoes'),
                "valorExtraReais" => $somaDespesasReal,
                "valorExtraDolar" => $somaDespesasDolar
            );
        }
        else{
            $dados = array(
                "isPrestacaoContasRealizada" => 1,
                "status" => "Aguardando aprovação da Prestação de Contas pelo Financeiro",
                "contatos" => $request->get('contatos'),
                "atividades" => $request->get('atividades'),
                "conclusoes" => $request->get('conclusoes'),
                "valorExtraReais" => $somaDespesasReal,
                "valorExtraDolar" => $somaDespesasDolar
            );
        }
        if($av->isAprovadoCarroDiretoriaExecutiva == true){
            $dados += ['odometroIda' => $request->get('odometroIda')];
            $dados += ['odometroVolta' => $request->get('odometroVolta')];
            $dados += ['qtdKmVeiculoProprio' => $request->get('odometroVolta') - $request->get('odometroIda')];

            $regras += ['odometroIda' => 'required'];
            $regras += ['odometroVolta' => 'required'];
        }
        
        if ($request->isSelecionado!=null)
        {
            $regras += ['horas' => 'required'];
            $regras += ['minutos' => 'required'];
            $regras += ['justificativa' => 'required'];

            $dados += ['horasExtras' => $request->get('horas')];
            $dados += ['minutosExtras' => $request->get('minutos')];
            $dados += ['justificativaHorasExtras' => $request->get('justificativa')];
        }
        

        $request->validate($regras, $mensagens);
        
        Av::findOrFail($av->id)->update($dados);
        $historico->save();

        $permission = Permission::where('name', 'aprov avs financeiro')->first();
        
        $users = User::all();
            foreach($users as $u){
                try {
                    if($u->hasPermissionTo($permission)){
                        Mail::to($u->username)
                        ->send(new EnvioUsuarioToFinanceiroPc($av->user_id, $u->id));
                    }
                } catch (\Throwable $th) {
                }
            }

        return redirect('/avs/prestacaoContasUsuario')->with('msg', 'Prestação de contas realizada!');
    }

    public function financeiroReprovarAv(Request $request){
        
        $user = auth()->user();
        $av = Av::findOrFail($request->get('id'));
        $userAv = User::findOrFail($av->user_id);

        $historico = new Historico();
        $timezone = new DateTimeZone('America/Sao_Paulo');
        $historico->dataOcorrencia = new DateTime('now', $timezone);
        $historico->tipoOcorrencia = "AV reprovada pelo Financeiro";
        $historico->comentario = $request->get('comentario');
        $historico->perfilDonoComentario = "Financeiro";
        $historico->usuario_id = $av->user_id;
        $historico->usuario_comentario_id = $user->id;
        $historico->av_id = $av->id;

        $regras = [
            'comentario' => 'required'
        ];

        $mensagens = [
            'required' => 'Este campo não pode estar em branco',
        ];

        $request->validate($regras, $mensagens);

        $dados = array(
            "isEnviadoUsuario" => 0,
            "isAprovadoGestor" => 0,
            "isRealizadoReserva" => 0,
            "isAprovadoFinanceiro" =>0,
            "isVistoDiretoria" =>0,
            "isAprovadoCarroDiretoriaExecutiva" =>0,
            "isAprovadoViagemInternacional" =>0,
            "status" => "Aguardando envio para o Gestor - Reprovado CFI"
        );

        Av::findOrFail($av->id)->update($dados);
        $historico->save();

        Mail::to($userAv->username)
                        ->send(new EnvioFinanceiroToUsuarioReprovarAv($userAv->id));

        return redirect('/avs/autFinanceiro')->with('msg', 'AV reprovada pelo financeiro!');
    }

    public function financeiroAprovaPrestacaoContas(Request $request){

        $user = auth()->user();
        $av = Av::findOrFail($request->get('id'));
        $userAv = User::findOrFail($av->user_id);

        $dados = [];

        $historico = new Historico();
        $timezone = new DateTimeZone('America/Sao_Paulo');
        $historico->dataOcorrencia = new DateTime('now', $timezone);
        $historico->tipoOcorrencia = "Prestação de contas aprovado pelo Financeiro";
        $historico->comentario = $request->get('comentario');
        $historico->perfilDonoComentario = "Financeiro";
        $historico->usuario_id = $av->user_id;
        $historico->usuario_comentario_id = $user->id;
        $historico->av_id = $av->id;
        
        if($av->isCancelado == true){
            $dados = array(
                "isFinanceiroAprovouPC" => 1,
                "status" => "AV Cancelada - Aguardando aprovação da prestação de contas pelo Gestor"
            );
        }
        else{
            $dados = array(
                "isFinanceiroAprovouPC" => 1,
                "status" => "Aguardando aprovação da prestação de contas pelo Gestor"
            );
        }

        Av::findOrFail($av->id)->update($dados);
        $historico->save();

        $email = null;
        $usermanager = null;
        $managerDN = $userAv->manager; // CN=Leandro Victorino Moura,OU=CTI,OU=Empregados,DC=prcidade,DC=br

        // Dividir a string em partes usando o caractere de vírgula como delimitador
        $parts = explode(',', $managerDN);

        // Extrair o nome do gerente da primeira parte
        $managerName = substr($parts[0], 3); // Remover os primeiros 3 caracteres "CN="

        $users = User::all();
        foreach ($users as $u){
            if($u->name == $managerName){
                $email = $u->username;
                $usermanager = $u;
            }
        }

        Mail::to($email)
            ->send(new EnvioFinanceiroToGestorPc($user->id, $usermanager->id));

        return redirect('/avs/autPcFinanceiro')->with('msg', 'Prestação de contas aprovado pelo Financeiro!');
    }

    public function financeiroRealizaAcertoContas(Request $request){

        $user = auth()->user();
        $av = Av::findOrFail($request->get('id'));
        $userAv = User::findOrFail($av->user_id);
        $objetivos = Objetivo::all();
        $historicosTodos = Historico::all();
        $users = User::all();
        $historicos = [];

        $historicoPcAll = HistoricoPc::all();
        $valorRecebido = null;

        $dados = [];

        $historico = new Historico();
        $timezone = new DateTimeZone('America/Sao_Paulo');
        $historico->dataOcorrencia = new DateTime('now', $timezone);
        $historico->tipoOcorrencia = "Acerto de Contas realizado pelo Financeiro";
        $historico->comentario = $request->get('comentario');
        $historico->perfilDonoComentario = "Financeiro";
        $historico->usuario_id = $av->user_id;
        $historico->usuario_comentario_id = $user->id;
        $historico->av_id = $av->id;
        
        $comprovantesAll = ComprovanteDespesa::all();
        $comprovantes = [];

        $valorAcertoContasReal = 0;
        $valorAcertoContasDolar = 0;

        foreach($comprovantesAll as $comp){
            if($comp->av_id == $av->id){
                array_push($comprovantes, $comp);
            }
        }
        foreach($comprovantes as $compFiltrado){
            $valorAcertoContasReal += $compFiltrado->valorReais;
            $valorAcertoContasDolar += $compFiltrado->valorDolar;
        }

        foreach($historicosTodos as $historico){
            if($historico->av_id == $av->id){
                array_push($historicos, $historico);
            }
        }
        
        foreach($historicoPcAll as $hisPc){
            if($hisPc->av_id == $av->id && $hisPc->comentario == "Adiantamento realizado - valor inicial"){
                $valorRecebido = $hisPc;
            }
        }

        //----------------------------------------------------------------------------------------------------------
        $options = new Options();
        $options->set('defaultFont', 'sans-serif');
        $dompdf = new Dompdf($options);

        $dompdf = new Dompdf();
        $dompdf->loadHtml(view('relatorioAcertoContas', compact('av', 'objetivos', 'historicos', 'users', 'userAv', 'valorRecebido', 'valorAcertoContasReal', 'valorAcertoContasDolar')));
        $dompdf->render();

        $nomeArquivo = md5("relatorioAcertoContas" . strtotime("now")) . ".pdf";
        $caminhoDiretorio = '/mnt/arquivos_viagem/AVs/' . $userAv->name . '/' . $av->id . '/resumo' . '/';
        $caminhoArquivo = $caminhoDiretorio . $nomeArquivo;
        if (!file_exists($caminhoDiretorio)) {
            mkdir($caminhoDiretorio, 0777, true);
        }
        file_put_contents($caminhoArquivo, $dompdf->output());
 
        //Salva no HistoricoPC
        $historicoPc = new HistoricoPc();
        $historicoPc->valorReais = $av->valorReais;
        $historicoPc->valorDolar = $av->valorDolar;
        $historicoPc->valorExtraReais = $av->valorExtraReais;
        $historicoPc->valorExtraDolar = $av->valorExtraDolar;
        $historicoPc->ocorrencia ="Financeiro efetuou Acerto de Contas";
        $historicoPc->comentario ="Acerto de contas";
        $historicoPc->av_id = $av->id;

        $timezone = new DateTimeZone('America/Sao_Paulo');
        $historicoPc->dataOcorrencia = new DateTime('now', $timezone);
        $historicoPc->anexoRelatorio = $nomeArquivo;
        $historicoPc->save();
        //----------------------------------------------------------------------------------------------------------

        if($av->isCancelado == true){
            $dados = array(
                "isAcertoContasRealizado" => 1,
                "status" => "AV Cancelada - Acerto de Contas realizado, aguardando validação do usuário"
            );
        }
        else{
            $dados = array(
                "isAcertoContasRealizado" => 1,
                "status" => "Acerto de Contas realizado, aguardando validação do usuário"
            );
        }

        Av::findOrFail($av->id)->update($dados);
        $historico->save();

        Mail::to($userAv->username)
                        ->send(new EnvioFinanceiroToUsuarioAcertoContas($userAv->id));

        return redirect('/avs/acertoContasFinanceiro')->with('msg', 'Acerto de contas realizado pelo Financeiro!');
    }

    public function usuarioAprovarAcertoContas(Request $request){

        $user = auth()->user();
        $av = Av::findOrFail($request->get('id'));

        $dados = [];

        $historico = new Historico();
        $timezone = new DateTimeZone('America/Sao_Paulo');
        $historico->dataOcorrencia = new DateTime('now', $timezone);
        $historico->tipoOcorrencia = "Acerto de Contas aprovado pelo Usuário";
        $historico->comentario = $request->get('comentario');
        $historico->perfilDonoComentario = "Usuário";
        $historico->usuario_id = $av->user_id;
        $historico->usuario_comentario_id = $user->id;
        $historico->av_id = $av->id;
        
        if($av->isCancelado == true){
            $dados = array(
                "isUsuarioAprovaAcertoContas" => 1,
                "status" => "AV Cancelada - Acerto de Contas aprovado pelo usuário - AV finalizada"
            );
        }
        else{
            $dados = array(
                "isUsuarioAprovaAcertoContas" => 1,
                "status" => "Acerto de Contas aprovado pelo usuário - AV finalizada"
            );
        }

        Av::findOrFail($av->id)->update($dados);
        $historico->save();

        return redirect('/avs/avs')->with('msg', 'Acerto de contas aprovado pelo Usuário!');
    }

    public function usuarioReprovarAcertoContas(Request $request){

        $user = auth()->user();
        $av = Av::findOrFail($request->get('id'));

        $dados = [];

        $historico = new Historico();
        $timezone = new DateTimeZone('America/Sao_Paulo');
        $historico->dataOcorrencia = new DateTime('now', $timezone);
        $historico->tipoOcorrencia = "Acerto de Contas reprovado pelo Usuário";
        $historico->comentario = $request->get('comentario');
        $historico->perfilDonoComentario = "Usuário";
        $historico->usuario_id = $av->user_id;
        $historico->usuario_comentario_id = $user->id;
        $historico->av_id = $av->id;

        $regras = [
            'comentario' => 'required'
        ];

        $mensagens = [
            'required' => 'Este campo não pode estar em branco',
        ];

        $request->validate($regras, $mensagens);
        
        $dados = array(
            "isAcertoContasRealizado" => 0,
            "isUsuarioAprovaAcertoContas" => 0,
            "status" => "PC reprovada pelo usuário, pendente de validação pelo Financeiro"
        );

        Av::findOrFail($av->id)->update($dados);
        $historico->save();

        $permission = Permission::where('name', 'aprov avs financeiro')->first();

        $users = User::all();
        foreach($users as $u){
            try {
                if($u->hasPermissionTo($permission)){
                    Mail::to($u->username)
                    ->send(new EnvioUsuarioToFinanceiroAcertoContas($av->user_id, $u->id));
                }
            } catch (\Throwable $th) {
            }
        }

        return redirect('/avs/avs')->with('msg', 'Acerto de contas reprovado pelo Usuário!');
    }

    public function financeiroReprovaPrestacaoContas(Request $request){
        
        $user = auth()->user();
        $av = Av::findOrFail($request->get('id'));
        $userAv = User::findOrFail($av->user_id);

        $historico = new Historico();
        $timezone = new DateTimeZone('America/Sao_Paulo');
        $historico->dataOcorrencia = new DateTime('now', $timezone);
        $historico->tipoOcorrencia = "Prestação de contas reprovado pelo Financeiro";
        $historico->comentario = $request->get('comentario');
        $historico->perfilDonoComentario = "Financeiro";
        $historico->usuario_id = $av->user_id;
        $historico->usuario_comentario_id = $user->id;
        $historico->av_id = $av->id;

        $regras = [
            'comentario' => 'required'
        ];

        $mensagens = [
            'required' => 'Este campo não pode estar em branco',
        ];

        $request->validate($regras, $mensagens);

        $dados = array(
            "isPrestacaoContasRealizada" => 0,
            'isFinanceiroAprovouPC' => 0,
            'isGestorAprovouPC' => 0,
            "status" => "Aguardando nova prestação de contas do usuário - reprovado pelo Financeiro"
        );

        Av::findOrFail($av->id)->update($dados);
        $historico->save();

        Mail::to($userAv->username)
                        ->send(new EnvioFinanceiroToUsuarioReprovarAcertoContas($userAv->id));

        return redirect('/avs/autPcFinanceiro')->with('msg', 'Prestação de contas reprovado pelo Financeiro!');
    }

    public function gestorAprovaPrestacaoContas(Request $request){

        $user = auth()->user();
        $av = Av::findOrFail($request->get('id'));

        $dados = [];

        $historico = new Historico();
        $timezone = new DateTimeZone('America/Sao_Paulo');
        $historico->dataOcorrencia = new DateTime('now', $timezone);
        $historico->tipoOcorrencia = "Prestação de contas aprovado pelo Gestor";
        $historico->comentario = $request->get('comentario');
        $historico->perfilDonoComentario = "Gestor";
        $historico->usuario_id = $av->user_id;
        $historico->usuario_comentario_id = $user->id;
        $historico->av_id = $av->id;
        
        if($av->isCancelado == true){
            $dados = array(
                "isGestorAprovouPC" => 1,
                "status" => "AV Cancelada - Aguardando acerto de contas pelo financeiro"
            );
        }
        else{
            $dados = array(
                "isGestorAprovouPC" => 1,
                "status" => "Aguardando acerto de contas pelo financeiro"
            );
        }
        

        Av::findOrFail($av->id)->update($dados);
        $historico->save();

        $permission = Permission::where('name', 'aprov avs financeiro')->first();

        $users = User::all();
        foreach($users as $u){
            try {
                if($u->hasPermissionTo($permission)){
                    Mail::to($u->username)
                    ->send(new EnvioGestorToFinanceiroAcertoContas($av->user_id, $u->id));
                }
            } catch (\Throwable $th) {
            }
        }


        return redirect('/avs/autPcGestor')->with('msg', 'Prestação de contas aprovado pelo Gestor!');
    }

    public function gestorReprovaPrestacaoContas(Request $request){
        
        $user = auth()->user();
        $av = Av::findOrFail($request->get('id'));
        $userAv = User::findOrFail($av->user_id);

        $historico = new Historico();
        $timezone = new DateTimeZone('America/Sao_Paulo');
        $historico->dataOcorrencia = new DateTime('now', $timezone);
        $historico->tipoOcorrencia = "Prestação de contas reprovado pelo Gestor";
        $historico->comentario = $request->get('comentario');
        $historico->perfilDonoComentario = "Gestor";
        $historico->usuario_id = $av->user_id;
        $historico->usuario_comentario_id = $user->id;
        $historico->av_id = $av->id;

        $regras = [
            'comentario' => 'required'
        ];

        $mensagens = [
            'required' => 'Este campo não pode estar em branco',
        ];

        $request->validate($regras, $mensagens);

        $dados = array(
            "isPrestacaoContasRealizada" => 0,
            "isFinanceiroAprovouPC" => 0,
            "status" => "Aguardando prestação de contas do usuário - reprovado gestor"
        );

        Av::findOrFail($av->id)->update($dados);
        $historico->save();

        Mail::to($userAv->username)
                        ->send(new EnvioGestorToUsuarioReprovarPc($userAv->id));

        return redirect('/avs/autPcGestor')->with('msg', 'Prestação de contas reprovado pelo Gestor!');
    }

    public function diretoriaAprovarAv(Request $request){

        $user = auth()->user();
        $av = Av::findOrFail($request->get('id'));
        $userAv = User::findOrFail($av->user_id);
        $avs = Av::all();
        $isInternacional = false;
        $isCarroProprio = false;
        $isHotel = false;
        $dados = [];

        $historico = new Historico();
        $timezone = new DateTimeZone('America/Sao_Paulo');

        $historico->dataOcorrencia = new DateTime('now', $timezone);
        $historico->tipoOcorrencia = "Aprovado pela DAF";
        $historico->comentario = $request->get('comentario');
        $historico->perfilDonoComentario = "DAF";
        $historico->usuario_id = $av->user_id;
        $historico->usuario_comentario_id = $user->id;
        $historico->av_id = $av->id;
        
        foreach($av->rotas as $rota){
            if($rota->isViagemInternacional==true){
                $isInternacional = true;
            }
            else if($rota->isVeiculoProprio==1){
                $isCarroProprio = true;
            }
            if($rota->isReservaHotel==1){
                $isHotel = true;
            }
        }

        if($isCarroProprio==true && $av->isDiaria==1 && $isHotel==false){
            $dados = array(
                "isAprovadoGestor" => 1,
                "isVistoDiretoria" => 1,
                "isRealizadoReserva" => 1,
                "status" => "Aguardando adiantamento pelo CFI",
                "isAprovadoCarroDiretoriaExecutiva" => "0",
            );
        }
        else{
            $dados = array(
                "isAprovadoGestor" => 1,
                "isVistoDiretoria" => 1,
                "status" => "Aguardando reservas pelo CAD e adiantamento pelo CFI",
                "isAprovadoViagemInternacional" => "0",
                "isAprovadoCarroDiretoriaExecutiva" => "0",
            );
        }

        if($isInternacional==true){
            $dados['isAprovadoViagemInternacional'] = 1;
        }
        else if($isCarroProprio == true){
            $dados['isAprovadoCarroDiretoriaExecutiva'] = 1;
        }

        Av::findOrFail($av->id)->update($dados);
        $historico->save();

        $permission = Permission::where('name', 'aprov avs secretaria')->first();
        $permission2 = Permission::where('name', 'aprov avs financeiro')->first();

        $users = User::all();
        foreach($users as $u){
            try {
                if($u->hasPermissionTo($permission)){
                    Mail::to($u->username)
                    ->send(new EnvioDiretoriaToSecretaria($av->user_id, $u->id));
                }
            } catch (\Throwable $th) {
            }
        }
        foreach($users as $u2){
            try {
                if($u2->hasPermissionTo($permission2)){
                    Mail::to($u2->username)
                    ->send(new EnvioGestorToFinanceiro($av->user_id, $u2->id));
                }
            } catch (\Throwable $th) {
            }
        }

        return redirect('/avs/autDiretoria')->with('msg', 'AV aprovada!');
    }

    public function diretoriaReprovarAv(Request $request){
        $av = Av::findOrFail($request->get('id'));
        $avs = Av::all();
        $user = auth()->user();
        $userAv = User::findOrFail($av->user_id);

        $historico = new Historico();
        $timezone = new DateTimeZone('America/Sao_Paulo');
        $historico->dataOcorrencia = new DateTime('now', $timezone);
        $historico->tipoOcorrencia = "Reprovado pela Diretoria";
        $historico->comentario = $request->get('comentario');
        $historico->perfilDonoComentario = "DAF";
        $historico->usuario_id = $av->user_id;
        $historico->usuario_comentario_id = $user->id;
        $historico->av_id = $av->id;

        $dados = array(
            "isEnviadoUsuario" => 0,
            "isAprovadoGestor" => 0,
            "isRealizadoReserva" => 0,
            "isAprovadoFinanceiro" =>0,
            "isVistoDiretoria" =>0,
            "isAprovadoCarroDiretoriaExecutiva" =>0,
            "isAprovadoViagemInternacional" =>0,
            "status" => "Aguardando envio para o Gestor"
        );

        Av::findOrFail($av->id)->update($dados);
        $historico->save();

        Mail::to($userAv->username)
                        ->send(new EnvioDiretoriaToUsuarioReprovarAv($userAv->id));

        return redirect('/avs/autDiretoria')->with('msg', 'AV reprovada!');
    }

    public function admFrotaAprovarAv(Request $request){

        $user = auth()->user();
        $av = Av::findOrFail($request->get('id'));

        $dados = [];

        $historico = new Historico();
        $timezone = new DateTimeZone('America/Sao_Paulo');
        $historico->dataOcorrencia = new DateTime('now', $timezone);
        $historico->tipoOcorrencia = "Veículo do Paranacidade reservado pela Adm Frota";
        $historico->comentario = $request->get('comentario');
        $historico->perfilDonoComentario = "Adm Frota";
        $historico->usuario_id = $av->user_id;
        $historico->usuario_comentario_id = $user->id;
        $historico->av_id = $av->id;
        $isVeiculoEmpresa = false;

        if($av->isRealizadoReserva == true && $av->isAprovadoFinanceiro == true){
            $dados = array(
                "isReservadoVeiculoParanacidade" => true,
                "status" => "Aguardando prestação de contas do usuário"
            );
        }
        else if($av->isRealizadoReserva == true && $av->isAprovadoFinanceiro == false){
            $dados = array(
                "isReservadoVeiculoParanacidade" => true,
                "status" => "Aguardando adiantamento CFI"
            );
        }
        else if($av->isRealizadoReserva == false && $av->isAprovadoFinanceiro == true){
            $dados = array(
                "isReservadoVeiculoParanacidade" => true,
                "status" => "Aguardando reservas pela CAD"
            );
        }
        
        
        Av::findOrFail($av->id)->update($dados);
        $historico->save();

        return redirect('/avs/autAdmFrota')->with('msg', 'AV aprovada pela Adm Frota!');
    }

    public function admFrotaReprovarAv(Request $request){
        
        $user = auth()->user();
        $av = Av::findOrFail($request->get('id'));

        $historico = new Historico();
        $timezone = new DateTimeZone('America/Sao_Paulo');
        $historico->dataOcorrencia = new DateTime('now', $timezone);
        $historico->tipoOcorrencia = "AV reprovada pela Adm Frota";
        $historico->comentario = $request->get('comentario');
        $historico->perfilDonoComentario = "Adm Frota";
        $historico->usuario_id = $av->user_id;
        $historico->usuario_comentario_id = $user->id;
        $historico->av_id = $av->id;

        $dados = array(
            "isEnviadoUsuario" => 0,
            "isAprovadoGestor" => 0,
            "isRealizadoReserva" => 0,
            "isAprovadoFinanceiro" =>0,
            "isReservadoVeiculoParanacidade" => 0,
            "status" => "Aguardando envio para o Gestor"
        );

        Av::findOrFail($av->id)->update($dados);
        $historico->save();

        return redirect('/avs/autAdmFrota')->with('msg', 'AV reprovada pela Adm Frota!');
    }

    public function store(Request $request)
    {
        $regras = [
            'objetivo_id' => 'required',
            'banco' => 'required',
            'agencia' => 'required',
            'conta' => 'required'
        ];

        $mensagens = [
            'required' => 'Este campo não pode estar em branco',
        ];
       
        $av = new Av();
        $user = auth()->user();

        if ($request->isSelecionado==1) //Se existir outro objetivo, remove a necessidade de validação de objetivo
        {
            $av->objetivo_id = null;
            unset($regras['objetivo_id']); //Retira a regra de validação de objetivo

            $regras += ['outroObjetivo' => 'required']; //Adiciona a validação de outro objetivo
        }
        else{
            $av->objetivo_id = $request->objetivo_id;
        }

        $request->validate($regras, $mensagens);
        
        $av->isDiaria = true;
        
        $timezone = new DateTimeZone('America/Sao_Paulo');
        $av->dataCriacao = new DateTime('now', $timezone);
        $av->banco = ($request->banco == "outro" ? $request->outrobanco : $request->banco);
        $av->agencia = ($request->agencia == "outro" ? $request->outraagencia : $request->agencia);
        $av->conta = ($request->conta == "outro" ? $request->outraconta : $request->conta);
        $av->pix = ($request->pix == "outro" ? $request->outropix : $request->pix);
        $av->comentario = $request->comentario;
        $av->status = "Aguardando envio para o Gestor";
        $av->outroObjetivo = $request->outroObjetivo;
        $idArrayTodosSelecionados = null;
        $idArrayUsuarioSelecionou = null;

        $idArrayTodosSelecionados = $request->input('todosSelecionados');
        $idArrayUsuarioSelecionou = $request->input('medicoesUsuarioSelecionadas');

        if($av->objetivo_id == 3 && ($idArrayTodosSelecionados == null && $idArrayUsuarioSelecionou == null)){
            return redirect('/avs/avs/' . $av->id)->with('msg', 'Não é possível criar uma AV de medição se não existir autorização da comissão!');
        }

        $av->user_id = $user->id;
    
        $av->save();

        $avRecuperada = Av::findOrFail($av->id);

        if($request->hasFile('arquivo1') && $request->file('arquivo1')->isValid())
        {
            $requestFile = $request->arquivo1;

            $extension = $requestFile->extension();

            $fileName = md5($requestFile->getClientOriginalName() . strtotime("now")) . "." . $extension;
            
            $requestFile->move('/mnt/arquivos_viagem/AVs/' . $user->name . '/' . $avRecuperada->id . '/' . 'autorizacaoAv', $fileName);

            $avRecuperada->autorizacao = $fileName;

            $avRecuperada->save();
        }

        $url = 'https://portaldosmunicipios.pr.gov.br/api/v1/medicao?status=27';
        $json = file_get_contents($url);
        $data = json_decode($json);
        foreach ($data as $item) {
            if($idArrayUsuarioSelecionou != null){
                foreach($idArrayUsuarioSelecionou as $selecionado){
                    if ($item->id == $selecionado) {
                        $medicao = new Medicao();
                        $medicao->nome_municipio = $item->nome_municipio;
                        $medicao->municipio_id = $item->municipio_id;
                        $medicao->numero_projeto = $item->numero_projeto;
                        $medicao->numero_lote = $item->numero_lote;
                        $medicao->numero_medicao = $item->numero;
                        $medicao->av_id = $avRecuperada->id;
                        $medicao->save();
                    }
                }
            }
            if($idArrayTodosSelecionados != null){
                foreach($idArrayTodosSelecionados as $selecionado){
                    if ($item->id == $selecionado) {
                        $medicao = new Medicao();
                        $medicao->nome_municipio = $item->nome_municipio;
                        $medicao->municipio_id = $item->municipio_id;
                        $medicao->numero_projeto = $item->numero_projeto;
                        $medicao->numero_lote = $item->numero_lote;
                        $medicao->numero_medicao = $item->numero;
                        $medicao->av_id = $avRecuperada->id;
                        $medicao->save();
                    }
                }
            }
        }

        return redirect('/rotas/rotas/' . $av->id)->with('msg', 'AV criada com sucesso!');
        //return view('rotas.createRota', ['av' => $av]);
    }
    
    public function concluir(Request $request){
        $objetivos = Objetivo::all();
        
        $user = auth()->user();

        $av = Av::findOrFail($request->avId);
        $rotas = $av->rotas;//Busca as rotas da AV

        //se somente existir uma rota, volte uma mensagem de erro dizendo que tem que ter pelo menos duas rotas
        if(sizeof($rotas) < 2){
            return redirect('/rotas/rotas/' . $av->id)->with('error', 'Não é possível concluir uma AV com apenas uma rota!');
        }

        //Valor do cálculo de rota e verificar quanto que terá que pagar ao usuário
        $diariaTotal = 0;
        $meiaDiaria = 0;

        $valorReais = 0;
        $valorDolar = 0;
        $teste =[];

        $diasCompletos =0;

        $anoSaidaRota1 = null;
        $mesSaidaRota1 = null;
        $diaSaidaRota1 = null;
        $horaSaidaRota1 = null;
        $minutoSaidaRota1 = null;

        $mesChegadaRota1 = null;
        $diaChegadaRota1 = null;
        $horaChegadaRota1 = null;
        $minutoChegadaRota1 = null;

        //DATAS ROTA 2
        $mesSaidaRota2 = null;
        $diaSaidaRota2 = null;
        $horaSaidaRota2 = null;
        $minutoSaidaRota2 = null;

        $mesChegadaRota2 = null;
        $diaChegadaRota2 = null;
        $horaChegadaRota2 = null;
        $minutoChegadaRota2 = null;

        $anoSaidaInicial = null;
        $mesSaidaInicial = null;
        $diaSaidaInicial = null;
        $horaSaidaInicial = null;
        $minutoSaidaInicial = null;
        $segundoSaidaInicial = null;

        $anoChegadaInicial = null;
        $mesChegadaInicial = null;
        $diaChegadaInicial = null;
        $horaChegadaInicial = null;
        $minutoChegadaInicial = null;
        $segundoChegadaInicial = null;

        $anoSaidaFinal = null;
        $mesSaidaFinal = null;
        $diaSaidaFinal = null;
        $horaSaidaFinal = null;
        $minutoSaidaFinal = null;
        $segundoSaidaFinal = null;

        $anoChegadaFinal = null;
        $mesChegadaFinal = null;
        $diaChegadaFinal = null;
        $horaChegadaFinal = null;
        $minutoChegadaFinal = null;
        $segundoChegadaFinal = null;

        $isInternacional = false;
        $mostrarValor = true;
        
        if(sizeof($rotas)==1){//Se tem apenas uma rota
            
            if($rotas[0]->continenteDestinoInternacional == 1 && $rotas[0]->paisDestinoInternacional !=30){//América Latina ou Amética Central
                $diariaTotal = 100;
                $meiaDiaria = 50;
                $isInternacional = true;
                $mostrarValor = false;
            }
            else if($rotas[0]->continenteDestinoInternacional == 2){//América do Norte
                $diariaTotal = 150;
                $meiaDiaria = 75;
                $isInternacional = true;
                $mostrarValor = false;
            }
            else if($rotas[0]->continenteDestinoInternacional == 3){//Europa
                $diariaTotal = 180;
                $meiaDiaria = 90;
                $isInternacional = true;
                $mostrarValor = false;
            }
            else if($rotas[0]->continenteDestinoInternacional == 4){//África
                $diariaTotal = 140;
                $meiaDiaria = 70;
                $isInternacional = true;
                $mostrarValor = false;
            }
            else if($rotas[0]->continenteDestinoInternacional == 5){//Ásia
                $diariaTotal = 190;
                $meiaDiaria = 95;
                $isInternacional = true;
                $mostrarValor = false;
            }else if(($rotas[0]->cidadeDestinoNacional == "Curitiba" || $rotas[0]->cidadeDestinoNacional == "Foz do Iguaçu") ||
            ($rotas[0]->paisDestinoInternacional == 30 && $rotas[0]->estadoDestinoInternacional == "Paraná" && 
            ($rotas[0]->cidadeDestinoInternacional == "Curitiba" || $rotas[0]->cidadeDestinoInternacional == "Foz do Iguaçu"))){//Se for Curitiba ou Foz do Iguaçu
                $diariaTotal = 65;
                $meiaDiaria = 32.5;
            }
            else if($rotas[0]->estadoDestinoNacional == "Paraná" || ($rotas[0]->paisDestinoInternacional == 30 && $rotas[0]->estadoDestinoInternacional == "Paraná")){//Se for outra cidade do Paraná
                $diariaTotal = 55;
                $meiaDiaria = 27.5;
            }
            else if($rotas[0]->cidadeDestinoNacional == "Brasília" || ($rotas[0]->paisDestinoInternacional == 30 && $rotas[0]->cidadeDestinoInternacional == "Brasília")){//Se for Brasília
                $diariaTotal = 100;
                $meiaDiaria = 50;
            }
            else{//Se não entrou em nenhum if, então é uma capital ou cidade de outros estados
                $diariaTotal = 80;
                $meiaDiaria = 40;
            }

            $date1 = new DateTime($rotas[0]->dataHoraSaida);//Data de saída
            $date2 = new DateTime($rotas[0]->dataHoraChegada);//Data de chegada

            $anoSaida = $date1->format('Y');
            $mesSaida = $date1->format('m');
            $diaSaida = $date1->format('d');
            $horaSaida = $date1->format('H');
            $minutoSaida = $date1->format('i');
            $segundoSaida = $date1->format('s');

            $anoChegada = $date2->format('Y');
            $mesChegada = $date2->format('m');
            $diaChegada = $date2->format('d');
            $horaChegada = $date2->format('H');
            $minutoChegada = $date2->format('i');
            $segundoChegada = $date2->format('s');
                
            if($diaSaida==$diaChegada){//Sair e chegar no mesmo dia
                if($horaSaida < 12 && $horaChegada >= 13 && $horaChegada < 19){//Se sair antes de 12 e chegar entre 13 e 19
                    if($isInternacional == false) {$valorReais += $meiaDiaria;}
                    if($isInternacional == true) {$valorDolar += $meiaDiaria;}
                }
                else if($horaSaida >= 13 && $horaChegada >= 19){ //Se sair depois das 13 e chegar após 19
                    if($isInternacional == false) {$valorReais += $meiaDiaria;}
                    if($isInternacional == true) {$valorDolar += $meiaDiaria;}
                }
                else if($horaSaida < 12 && $horaChegada >= 19){ // Se sair antes de 12 e chegar após 19
                    if($isInternacional == false) {$valorReais += $diariaTotal;}
                    if($isInternacional == true) {$valorDolar += $diariaTotal;}
                }
            }
    
            if($diaSaida != $diaChegada){ // Sair e chegar em dia diferente
             
                if($horaSaida <12){//Se no primeiro dia ele sair antes de 12 já ganha diária total
                    if($isInternacional == false) {$valorReais += $diariaTotal;}
                    if($isInternacional == true) {$valorDolar += $diariaTotal;}
                }
                else if($horaSaida >= 13){//Se no primeiro dia ele sair após as 13, ganha meia diária
                    if($isInternacional == false) {$valorReais += $meiaDiaria;}
                    if($isInternacional == true) {$valorDolar += $meiaDiaria;}
                }
                 
                //Roda o laço a partir do segundo dia até o penúltimo
                for($i = $diaSaida+1; $i < $diaChegada ; $i++){//Acrescenta uma diária completa para cada dia intermediário
                    if($isInternacional == false) {$valorReais += $diariaTotal;}
                    if($isInternacional == true) {$valorDolar += $diariaTotal;}
                }
                
                if($horaChegada < 13){ //Se no último dia a chegada for antes das 13, recebe meia diária
                    if($isInternacional == false) {$valorReais += $meiaDiaria;}
                    if($isInternacional == true) {$valorDolar += $meiaDiaria;}
                }
                else if($horaChegada >= 13 && $horaChegada < 19){
                    if($isInternacional == false) {$valorReais += $meiaDiaria;}
                    if($isInternacional == true) {$valorDolar += $meiaDiaria;}
                }
                else if($horaChegada >=19){ // Se no último dia a chegada for após as 19, recebe diária inteira
                    if($isInternacional == false) {$valorReais += $diariaTotal;}
                    if($isInternacional == true) {$valorDolar += $diariaTotal;}
                }
            
            }
            
        }
        else if(sizeof($rotas)>1){//Se existir mais de uma rota
            
            for ($i=0; $i < sizeof($rotas)-1 ; $i++) { 
                $isInternacional = false;
                if($rotas[$i]->continenteDestinoInternacional == 1 && $rotas[$i]->paisDestinoInternacional !=30){//América Latina ou Amética Central
                    $diariaTotal = 100;
                    $meiaDiaria = 50;
                    $isInternacional = true;
                    $mostrarValor = false;
                }
                else if($rotas[$i]->continenteDestinoInternacional == 2){//América do Norte
                    $diariaTotal = 150;
                    $meiaDiaria = 75;
                    $isInternacional = true;
                    $mostrarValor = false;
                }
                else if($rotas[$i]->continenteDestinoInternacional == 3){//Europa
                    $diariaTotal = 180;
                    $meiaDiaria = 90;
                    $isInternacional = true;
                    $mostrarValor = false;
                }
                else if($rotas[$i]->continenteDestinoInternacional == 4){//África
                    $diariaTotal = 140;
                    $meiaDiaria = 70;
                    $isInternacional = true;
                    $mostrarValor = false;
                }
                else if($rotas[$i]->continenteDestinoInternacional == 5){//Ásia
                    $diariaTotal = 190;
                    $meiaDiaria = 95;
                    $isInternacional = true;
                    $mostrarValor = false;
                }else if(($rotas[$i]->cidadeDestinoNacional == "Curitiba" || $rotas[$i]->cidadeDestinoNacional == "Foz do Iguaçu") ||
                ($rotas[$i]->paisDestinoInternacional == 30 && $rotas[$i]->estadoDestinoInternacional == "Paraná" && 
                ($rotas[$i]->cidadeDestinoInternacional == "Curitiba" || $rotas[$i]->cidadeDestinoInternacional == "Foz do Iguaçu"))){//Se for Curitiba ou Foz do Iguaçu
                    $diariaTotal = 65;
                    $meiaDiaria = 32.5;
                }
                else if($rotas[$i]->estadoDestinoNacional == "Paraná" || ($rotas[$i]->paisDestinoInternacional == 30 && $rotas[$i]->estadoDestinoInternacional == "Paraná")){//Se for outra cidade do Paraná
                    $diariaTotal = 55;
                    $meiaDiaria = 27.5;
                }
                else if($rotas[$i]->cidadeDestinoNacional == "Brasília" || ($rotas[$i]->paisDestinoInternacional == 30 && $rotas[$i]->cidadeDestinoInternacional == "Brasília")){//Se for Brasília
                    $diariaTotal = 100;
                    $meiaDiaria = 50;
                }
                else{//Se não entrou em nenhum if, então é uma capital ou cidade de outros estados
                    $diariaTotal = 80;
                    $meiaDiaria = 40;
                }
                
                
                //Verifica quais são das datas da rota atual e da próxima
                $dataHoraSaidaRota1 = new DateTime($rotas[$i]->dataHoraSaida);//Data de saída da rota 1
                $dataHoraChegadaRota1 = new DateTime($rotas[$i]->dataHoraChegada);//Data de chegada da rota 1

                $dataHoraSaidaRota2 = new DateTime($rotas[$i + 1]->dataHoraSaida);//Data de saída da rota 2
                $dataHoraChegadaRota2 = new DateTime($rotas[$i + 1]->dataHoraChegada);//Data de chegada da rota 2
                
                //DATAS ROTA 1
                $anoSaidaRota1 = $dataHoraSaidaRota1->format('Y');
                $mesSaidaRota1 = $dataHoraSaidaRota1->format('m');
                $diaSaidaRota1 = $dataHoraSaidaRota1->format('d');
                $horaSaidaRota1 = $dataHoraSaidaRota1->format('H');
                $minutoSaidaRota1 = $dataHoraSaidaRota1->format('i');

                $mesChegadaRota1 = $dataHoraChegadaRota1->format('m');
                $diaChegadaRota1 = $dataHoraChegadaRota1->format('d');
                $horaChegadaRota1 = $dataHoraChegadaRota1->format('H');
                $minutoChegadaRota1 = $dataHoraChegadaRota1->format('i');

                //DATAS ROTA 2
                $mesSaidaRota2 = $dataHoraSaidaRota2->format('m');
                $diaSaidaRota2 = $dataHoraSaidaRota2->format('d');
                $horaSaidaRota2 = $dataHoraSaidaRota2->format('H');
                $minutoSaidaRota2 = $dataHoraSaidaRota2->format('i');

                $mesChegadaRota2 = $dataHoraChegadaRota2->format('m');
                $diaChegadaRota2 = $dataHoraChegadaRota2->format('d');
                $horaChegadaRota2 = $dataHoraChegadaRota2->format('H');
                $minutoChegadaRota2 = $dataHoraChegadaRota2->format('i');
                
                if($i==0){
                    $anoSaidaInicial = $anoSaidaRota1;
                    $mesSaidaInicial = $mesSaidaRota1;
                    $diaSaidaInicial = $diaSaidaRota1;
                    $horaSaidaInicial = $horaSaidaRota1;
                    $minutoSaidaInicial = $minutoSaidaRota1;

                    $mesChegadaInicial = $mesChegadaRota1;
                    $diaChegadaInicial = $diaChegadaRota1;
                    $horaChegadaInicial = $horaChegadaRota1;
                    $minutoChegadaInicial = $minutoChegadaRota1;
                }
                if($i == sizeof($rotas)-2){
                    $mesSaidaFinal = $mesSaidaRota2;
                    $diaSaidaFinal = $diaSaidaRota2;
                    $horaSaidaFinal = $horaSaidaRota2;
                    $minutoSaidaFinal = $minutoSaidaRota2;

                    $mesChegadaFinal = $mesChegadaRota2;
                    $diaChegadaFinal = $diaChegadaRota2;
                    $horaChegadaFinal = $horaChegadaRota2;
                    $minutoChegadaFinal = $minutoChegadaRota2;
                }
                //CÁLCULOS:
                
                //A partir do próximo dia após a chegada da rota 1, conta até o último dia antes da partida da rota 2
                $mesesDiferenca = 0;
                if($mesSaidaRota1 != $mesChegadaRota2){//Verifica se as datas estão em meses diferentes
                    $mesesDiferenca = $mesChegadaRota2-$mesSaidaRota1;
                    
                    $data1 = $dataHoraSaidaRota1->format('Y-m-d');

                    $ultimoDiaMes1 = date('t', strtotime($data1));

                    if($mesesDiferenca==1){
                        $diasCompletos += $ultimoDiaMes1 - ($diaChegadaRota1+1);
                        $diasCompletos += $diaSaidaRota2;
                    }
                    else if($mesesDiferenca==2){
                        $diasCompletos += $ultimoDiaMes1 - ($diaChegadaRota1+1);

                        $ultimoDiaMesx = date('t', strtotime($anoSaidaRota1 . '-' . $mesSaidaRota1 + 1 . '-' . $diaSaidaRota1));
                        
                        $diasCompletos += $ultimoDiaMesx;
                        $diasCompletos += $diaSaidaRota2;
                    }
                }
                else if($mesSaidaRota1 == $mesChegadaRota2){
                    $diasCompletos = ($diaSaidaRota2)-($diaChegadaRota1+1);  
                }
                
                if($isInternacional == false) {$valorReais += ($diariaTotal * $diasCompletos) ;}
                if($isInternacional == true) {$valorDolar += ($diariaTotal * $diasCompletos);}
                
                if($diaSaidaRota1==$diaChegadaRota1){// Se a viagem de ida durar um dia
                    
                    if($horaSaidaRota1 < 12){//Se sair antes de 12h 
                        if($isInternacional == false) {$valorReais += $diariaTotal;}
                        if($isInternacional == true) {$valorDolar += $diariaTotal;}
                    }
                    else if($horaSaidaRota1 >=13){//Sair depois das 13h e chegar depois das 19h
                        if($isInternacional == false) {$valorReais += $meiaDiaria;}
                        if($isInternacional == true) {$valorDolar += $meiaDiaria;}
                    }
                } 
                else if($diaSaidaRota1!=$diaChegadaRota1){//Se a viagem de ida demorar mais de um dia
                    
                    if($horaSaidaRota1 < 12){//Se sair antes de 12
                        if($isInternacional == false) {$valorReais += $diariaTotal;}
                        if($isInternacional == true) {$valorDolar += $diariaTotal;}
                    }
                    else if($horaSaidaRota1 >=13 && $horaSaidaRota1 <19){
                        if($isInternacional == false) {$valorReais += $meiaDiaria;}
                        if($isInternacional == true) {$valorDolar += $meiaDiaria;}
                    }

                    for($j = $diaSaidaRota1; $j < $diaChegadaRota1 ; $j++){//Acrescenta uma diária completa para cada dia intermediário
                        if($isInternacional == false) {$valorReais += $diariaTotal;}
                        if($isInternacional == true) {$valorDolar += $diariaTotal;}
                    }
                }

                //Soma o período antes do início de uma nova rota, caso complete meia diária ou total
                if(!($i+1 >= sizeof($rotas)-1)){//Se não estou na última rota
                    if($horaSaidaRota2 >= 13){
                        if($isInternacional == false) {$valorReais += $meiaDiaria;}
                        if($isInternacional == true) {$valorDolar += $meiaDiaria;}
                    }
                    else if($horaSaidaRota2 >= 19){
                        if($isInternacional == false) {$valorReais += $diariaTotal;}
                        if($isInternacional == true) {$valorDolar += $diariaTotal;}
                    }
                }
                
                if($i+1 >= sizeof($rotas)-1){//Se estou na última rota
                    if($diaSaidaRota2==$diaChegadaRota2){// Se a viagem da rota 2 durar um dia
                        
                        if($horaChegadaRota2 >=19){
                            if($isInternacional == false) {$valorReais += $diariaTotal;}
                            if($isInternacional == true) {$valorDolar += $diariaTotal;}
                        }
                        else if($horaChegadaRota2 >= 13 && $horaChegadaRota2 < 19){
                            if($isInternacional == false) {$valorReais += $meiaDiaria;}
                            if($isInternacional == true) {$valorDolar += $meiaDiaria;}
                        }
                    } 
                    else if($diaSaidaRota2!=$diaChegadaRota2){//Se a viagem da rota 2 demorar mais de um dia
    
                        if($horaSaidaRota2 < 12){//Se sair antes de 12
                            if($isInternacional == false) {$valorReais += $diariaTotal;}
                            if($isInternacional == true) {$valorDolar += $diariaTotal;}
                        }
                        else if($horaSaidaRota2 >=13){
                            if($isInternacional == false) {$valorReais += $meiaDiaria;}
                            if($isInternacional == true) {$valorDolar += $meiaDiaria;}
                        }
    
                        for($j = $diaSaidaRota2+1; $j < $diaChegadaRota2 ; $j++){//Acrescenta uma diária completa para cada dia intermediário
                            if($isInternacional == false) {$valorReais += $diariaTotal;}
                            if($isInternacional == true) {$valorDolar += $diariaTotal;}
                        }
    
                        if($horaChegadaRota2 >=13 && $horaChegadaRota2 < 19){
                            if($isInternacional == false) {$valorReais += $meiaDiaria;}
                            if($isInternacional == true) {$valorDolar += $meiaDiaria;}
                        }
                        else if($horaChegadaRota2 >= 19){ // Se no último dia da Rota 1 a chegada for após as 19, recebe diária inteira
                            if($isInternacional == false) {$valorReais += $diariaTotal;}
                            if($isInternacional == true) {$valorDolar += $diariaTotal;}
                        }
                    }
                }
            }
        }

        $dataInicio = "$anoSaidaInicial-$mesSaidaInicial-$diaSaidaInicial";
        $dataFim = "$anoSaidaInicial-$mesChegadaFinal-$diaChegadaFinal";

                       
        $arrayDiasValores = [];
                        
        $intervaloDatas = new DatePeriod(
            new DateTime($dataInicio),
            new DateInterval('P1D'),
            ($dataInicio != $dataFim ? (new DateTime($dataFim))->modify('+1 day') : (new DateTime($dataFim))) // Adicionar 1 dia ao fim para inclusão do último dia
        );
                        
        foreach ($intervaloDatas as $data) {
            $dia = $data->format('Y-m-d');
            $valor = 0;
            $acumulado = 0;

            for ($i=0; $i < sizeof($rotas)-1 ; $i++) {
                    $dataSaida = DateTime::createFromFormat('Y-m-d H:i:s', $rotas[$i]->dataHoraSaida)->format('Y-m-d H:i:s');
                    $dataSaida2 = DateTime::createFromFormat('Y-m-d H:i:s', $rotas[$i + 1]->dataHoraSaida)->format('Y-m-d H:i:s');
                    $dataChegada2 = DateTime::createFromFormat('Y-m-d H:i:s', $rotas[$i + 1]->dataHoraChegada)->format('Y-m-d H:i:s');

                    $dataSaidaModificado1 = new DateTime($dataSaida);//Data de saída da rota 1
                    $dataSaidaModificado2 = new DateTime($dataSaida2);//Data de saída da rota 2
                    $dataChegadaModificado2 = new DateTime($dataChegada2);//Data de saída da rota 2

                    $horaSaidaRota1 = $dataSaidaModificado1->format('H');
                    $minutoSaidaRota1 = $dataSaidaModificado1->format('i');

                    $horaSaidaRota2 = $dataSaidaModificado2->format('H');
                    $minutoSaidaRota2 = $dataSaidaModificado2->format('i');

                    $horaChegadaRota2 = $dataChegadaModificado2->format('H');
                    $minutoChegadaRota2 = $dataChegadaModificado2->format('i');

                    $dataSaida = DateTime::createFromFormat('Y-m-d H:i:s', $rotas[$i]->dataHoraSaida)->format('Y-m-d');
                    $dataSaida2 = DateTime::createFromFormat('Y-m-d H:i:s', $rotas[$i + 1]->dataHoraSaida)->format('Y-m-d');
                    $dataChegada2 = DateTime::createFromFormat('Y-m-d H:i:s', $rotas[$i + 1]->dataHoraChegada)->format('Y-m-d');

                    if ($dia >= $dataSaida && $dia <= $dataSaida2) {
                        
                        if($rotas[$i]->continenteDestinoInternacional == 1 && $rotas[$i]->paisDestinoInternacional !=30){//América Latina ou Amética Central
                            $valor = 100;
                        }
                        else if($rotas[$i]->continenteDestinoInternacional == 2){//América do Norte
                            $valor = 150;
                        }
                        else if($rotas[$i]->continenteDestinoInternacional == 3){//Europa
                            $valor = 180;
                        }
                        else if($rotas[$i]->continenteDestinoInternacional == 4){//África
                            $valor = 140;
                        }
                        else if($rotas[$i]->continenteDestinoInternacional == 5){//Ásia
                            $valor = 190;
                        }else if(($rotas[$i]->cidadeDestinoNacional == "Curitiba" || $rotas[$i]->cidadeDestinoNacional == "Foz do Iguaçu") ||
                        ($rotas[$i]->paisDestinoInternacional == 30 && $rotas[$i]->estadoDestinoInternacional == "Paraná" && 
                        ($rotas[$i]->cidadeDestinoInternacional == "Curitiba" || $rotas[$i]->cidadeDestinoInternacional == "Foz do Iguaçu"))){//Se for Curitiba ou Foz do Iguaçu
                            $valor = 65;
                        }
                        else if($rotas[$i]->estadoDestinoNacional == "Paraná" || ($rotas[$i]->paisDestinoInternacional == 30 && $rotas[$i]->estadoDestinoInternacional == "Paraná")){//Se for outra cidade do Paraná
                            $valor = 55;
                        }
                        else if($rotas[$i]->cidadeDestinoNacional == "Brasília" || ($rotas[$i]->paisDestinoInternacional == 30 && $rotas[$i]->cidadeDestinoInternacional == "Brasília")){//Se for Brasília
                            $valor = 100;
                        }
                        else{//Se não entrou em nenhum if, então é uma capital ou cidade de outros estados
                            $valor = 80;
                        }
                        if($dia == $dataSaida){
                            if($horaSaidaRota1 >= 13 && $horaSaidaRota1 < 19){
                                $metade = ($valor/2);
                                if($acumulado == 0){
                                    $acumulado = $metade;
                                }
                                else{
                                    $acumulado += $metade;
                                }
                            }
                        }
                        if(!($i == sizeof($rotas)-2)){
                            if($dia == $dataSaida2){
                                if($horaSaidaRota2 >= 13 && $horaSaidaRota2 < 19){
                                    $metade = ($valor/2);
                                    if($acumulado == 0){
                                        $acumulado = $metade;
                                    }
                                    else{
                                        $acumulado += $metade;
                                    }
                                }
                            }
                        }
                        if($i == sizeof($rotas)-2){
                            if($dia == $dataChegada2){
                                if($horaChegadaRota2 >= 13 && $horaChegadaRota2 < 19){
                                    $metade = ($valor/2);
                                    if($acumulado == 0){
                                        $acumulado = $metade;
                                    }
                                    else{
                                        $acumulado += $metade;
                                    }
                                }
                                else if($horaChegadaRota2 >= 19){
                                    if($acumulado == 0){
                                        $acumulado = $valor;
                                    }
                                    else{
                                        $acumulado += $valor;
                                    }
                                }
                            }
                        }
                    }
            }
            $diaFormatado = DateTime::createFromFormat('Y-m-d', $dia);
            $arrayDiasValores[] = [
                'dia' => $diaFormatado->format('d'),
                'valor' => $acumulado != 0 ? $acumulado : $valor,
            ];
        }

        if(empty($arrayDiasValores)){
            $data = new DateTime($rotas[0]->dataHoraSaida);

            $dia = $data->format('Y-m-d');
            $valor = 0;
            $acumulado = 0;

            for ($i=0; $i < sizeof($rotas)-1 ; $i++) {
                    $dataSaida = DateTime::createFromFormat('Y-m-d H:i:s', $rotas[$i]->dataHoraSaida)->format('Y-m-d H:i:s');
                    $dataSaida2 = DateTime::createFromFormat('Y-m-d H:i:s', $rotas[$i + 1]->dataHoraSaida)->format('Y-m-d H:i:s');
                    $dataChegada2 = DateTime::createFromFormat('Y-m-d H:i:s', $rotas[$i + 1]->dataHoraChegada)->format('Y-m-d H:i:s');

                    $dataSaidaModificado1 = new DateTime($dataSaida);//Data de saída da rota 1
                    $dataSaidaModificado2 = new DateTime($dataSaida2);//Data de saída da rota 2
                    $dataChegadaModificado2 = new DateTime($dataChegada2);//Data de saída da rota 2

                    $horaSaidaRota1 = $dataSaidaModificado1->format('H');
                    $minutoSaidaRota1 = $dataSaidaModificado1->format('i');

                    $horaSaidaRota2 = $dataSaidaModificado2->format('H');
                    $minutoSaidaRota2 = $dataSaidaModificado2->format('i');

                    $horaChegadaRota2 = $dataChegadaModificado2->format('H');
                    $minutoChegadaRota2 = $dataChegadaModificado2->format('i');

                    $dataSaida = DateTime::createFromFormat('Y-m-d H:i:s', $rotas[$i]->dataHoraSaida)->format('Y-m-d');
                    $dataSaida2 = DateTime::createFromFormat('Y-m-d H:i:s', $rotas[$i + 1]->dataHoraSaida)->format('Y-m-d');
                    $dataChegada2 = DateTime::createFromFormat('Y-m-d H:i:s', $rotas[$i + 1]->dataHoraChegada)->format('Y-m-d');

                    if ($dia >= $dataSaida && $dia <= $dataSaida2) {
                        
                        if($rotas[$i]->continenteDestinoInternacional == 1 && $rotas[$i]->paisDestinoInternacional !=30){//América Latina ou Amética Central
                            $valor = 100;
                        }
                        else if($rotas[$i]->continenteDestinoInternacional == 2){//América do Norte
                            $valor = 150;
                        }
                        else if($rotas[$i]->continenteDestinoInternacional == 3){//Europa
                            $valor = 180;
                        }
                        else if($rotas[$i]->continenteDestinoInternacional == 4){//África
                            $valor = 140;
                        }
                        else if($rotas[$i]->continenteDestinoInternacional == 5){//Ásia
                            $valor = 190;
                        }else if(($rotas[$i]->cidadeDestinoNacional == "Curitiba" || $rotas[$i]->cidadeDestinoNacional == "Foz do Iguaçu") ||
                        ($rotas[$i]->paisDestinoInternacional == 30 && $rotas[$i]->estadoDestinoInternacional == "Paraná" && 
                        ($rotas[$i]->cidadeDestinoInternacional == "Curitiba" || $rotas[$i]->cidadeDestinoInternacional == "Foz do Iguaçu"))){//Se for Curitiba ou Foz do Iguaçu
                            $valor = 65;
                        }
                        else if($rotas[$i]->estadoDestinoNacional == "Paraná" || ($rotas[$i]->paisDestinoInternacional == 30 && $rotas[$i]->estadoDestinoInternacional == "Paraná")){//Se for outra cidade do Paraná
                            $valor = 55;
                        }
                        else if($rotas[$i]->cidadeDestinoNacional == "Brasília" || ($rotas[$i]->paisDestinoInternacional == 30 && $rotas[$i]->cidadeDestinoInternacional == "Brasília")){//Se for Brasília
                            $valor = 100;
                        }
                        else{//Se não entrou em nenhum if, então é uma capital ou cidade de outros estados
                            $valor = 80;
                        }
                        if($dia == $dataSaida){
                            if($horaSaidaRota1 >= 13 && $horaSaidaRota1 < 19){
                                $metade = ($valor/2);
                                if($acumulado == 0){
                                    $acumulado = $metade;
                                }
                                else{
                                    $acumulado += $metade;
                                }
                            }
                        }
                    }
            }
            $diaFormatado = DateTime::createFromFormat('Y-m-d', $dia);
            $arrayDiasValores[] = [
                'dia' => $diaFormatado->format('d'),
                'valor' => $acumulado != 0 ? $acumulado : $valor,
            ];
        }
        //$teste += ['Data saída: ' => $valorReais];
        //$teste += ['Data chegada: ' => $anoChegada. "/" .$mesChegada. "/" .$diaChegada. "/" .$horaChegada. "/" .$minutoChegada. "/" .$segundoChegada];
        //dd($arrayDiasValores);

        $av->valorReais = $valorReais;
        $av->valorDolar = $valorDolar;

        $veiculosProprios = $user->veiculosProprios;

        if($user->id != $av->user->id) {
            return redirect('/dashboard')->with('msg', 'Você não tem permissão para editar esta av!');
        }
        if($av->isDiaria == false){
            $dados = array(
                "valorReais" => 0,
                "valorDolar" => 0
            );
            Av::findOrFail($av->id)->update($dados);
            $av = Av::findOrFail($av->id);
            return view('avs.concluir', ['av' => $av, 'objetivos' => $objetivos, 'user'=> $user, 'rotas' => $rotas,
            'anoSaidaInicial' => $anoSaidaInicial, 'mesSaidaInicial' => $mesSaidaInicial, 'diaSaidaInicial' => $diaSaidaInicial, 'horaSaidaInicial' => $horaSaidaInicial,
            'mesChegadaInicial' => $mesChegadaInicial, 'diaChegadaInicial' => $diaChegadaInicial, 'horaChegadaInicial' => $horaChegadaInicial,
            'mesSaidaFinal' => $mesSaidaFinal, 'diaSaidaFinal' => $diaSaidaFinal, 'horaSaidaFinal' => $horaSaidaFinal,
            'mesChegadaFinal' => $mesChegadaFinal, 'diaChegadaFinal' => $diaChegadaFinal, 'horaChegadaFinal' => $horaChegadaFinal,
            'minutoSaidaInicial' => $minutoSaidaInicial, 'minutoChegadaInicial' => $minutoChegadaInicial, 'minutoSaidaFinal' => $minutoSaidaFinal,
            'minutoChegadaFinal' => $minutoChegadaFinal, 'diariaTotal' => $diariaTotal, 'meiaDiaria' => $meiaDiaria, 'mostrarValor' => $mostrarValor, 'arrayDiasValores' => $arrayDiasValores]);
        }

        //Atualiza no banco de dados o valor calculado para a diária de alimentação
        $dados = array(
            "valorReais" => $av->valorReais,
            "valorDolar" => $av->valorDolar
        );
        Av::findOrFail($av->id)->update($dados);

        if($request->isPc=="sim"){

            $historico = new Historico();
            $timezone = new DateTimeZone('America/Sao_Paulo');
            $historico->dataOcorrencia = new DateTime('now', $timezone);
            $historico->tipoOcorrencia = "Atualização de cálculo de diárias";
            $historico->comentario = "Edição de Av na Prestação de Contas";
            $historico->perfilDonoComentario = "Usuário";
            $historico->usuario_id = $av->user_id;
            $historico->usuario_comentario_id = $user->id;
            $historico->av_id = $av->id;
    
            Av::findOrFail($request->id)->update($dados);
            $historico->save();
    
            return redirect('/rotaspc/rotas/' . $request->id )->with('msg', 'Cálculo salvo!');
        }
        else{
            return view('avs.concluir', ['av' => $av, 'objetivos' => $objetivos, 'veiculosProprios' => $veiculosProprios, 'user'=> $user, 'rotas' => $rotas,
            'anoSaidaInicial' => $anoSaidaInicial, 'mesSaidaInicial' => $mesSaidaInicial, 'diaSaidaInicial' => $diaSaidaInicial, 'horaSaidaInicial' => $horaSaidaInicial,
            'mesChegadaInicial' => $mesChegadaInicial, 'diaChegadaInicial' => $diaChegadaInicial, 'horaChegadaInicial' => $horaChegadaInicial,
            'mesSaidaFinal' => $mesSaidaFinal, 'diaSaidaFinal' => $diaSaidaFinal, 'horaSaidaFinal' => $horaSaidaFinal,
            'mesChegadaFinal' => $mesChegadaFinal, 'diaChegadaFinal' => $diaChegadaFinal, 'horaChegadaFinal' => $horaChegadaFinal,
            'minutoSaidaInicial' => $minutoSaidaInicial, 'minutoChegadaInicial' => $minutoChegadaInicial, 'minutoSaidaFinal' => $minutoSaidaFinal,
            'minutoChegadaFinal' => $minutoChegadaFinal, 'diariaTotal' => $diariaTotal, 'meiaDiaria' => $meiaDiaria, 'mostrarValor' => $mostrarValor, 
            'arrayDiasValores' => $arrayDiasValores, 'isInternacional' => $isInternacional]);
        }
    }

    public function show($id)
    {
        $av = Av::findOrFail($id);
        
        $user = auth()->user();
        
        $veiculosProprios = $user->veiculosProprios;
        
        try {
            $objetivo = Objetivo::findOrFail($av->objetivo_id);
        } catch (\Throwable $th) {
            $objetivo = $av->outroObjetivo;
        }

        try {
            $veiculoProprio = VeiculoProprio::findOrFail($av->veiculoProprio_id);
            
        } catch (\Throwable $th) {
            $veiculoProprio = VeiculoProprio::all();
        }
 
        return view('avs.show', ['av' => $av, 'objetivo' => $objetivo, 'veiculoProprio' => $veiculoProprio, 'user'=> $user]);
    }

    public function dashboard()
    {

        $user = auth()->user();

        $search = request('search');

        if ($search) {
            $avs = Av::where([
                ['title', 'like', '%'.$search. '%']
            ])->get();
        } else {
            $avs = Av::all();
        }

        $user = auth()->user();
        $avs = $user->avs;

        return view('welcome', ['avs' => $avs, 'search' => $search, 'user'=> $user]);
    }

    public function destroy($id)
    {
        $av = Av::findOrFail($id)->delete();

        return redirect('/avs/avs')->with('msg', 'AV excluída com sucesso!');
    }

    public function edit($id)
    {
        $objetivos = Objetivo::all();

        $user = auth()->user();

        $av = Av::findOrFail($id);
        $userAv = User::findOrFail($av->user_id);

        $user = auth()->user();
        $veiculosProprios = $user->veiculosProprios;

        $url = 'https://portaldosmunicipios.pr.gov.br/api/v1/medicao?status=27';
        $json = file_get_contents($url);
        $data = json_decode($json);
        $filtro = [];
        $filtroTodos = [];
        $medicoes = Medicao::all();

        foreach ($data as $item) {
            $jaExiste = false;
            foreach ($medicoes as $medicao) {
                if (($item->municipio_id == $medicao->municipio_id) && ($item->numero_projeto == $medicao->numero_projeto)
                && ($item->numero_lote == $medicao->numero_lote) && ($item->numero == $medicao->numero_medicao)) {
                    $jaExiste = true;
                }
            }
            if(!$jaExiste){
                if ($item->nome_supervisor == $user->name) { //  para teste 'Fernanda Espindola de Oliveira'
                    array_push($filtro, $item);
                }
                else{
                    array_push($filtroTodos, $item);
                }
            }
        }

        if($user->id != $av->user->id) {
            return redirect('/dashboard')->with('msg', 'Você não tem permissão para editar esta av!');
        }

        return view('avs.edit', ['av' => $av, 'objetivos' => $objetivos, 'veiculosProprios' => $veiculosProprios, 
        'user'=> $user, 'filtro' => $filtro, 'filtroTodos' => $filtroTodos, 'userAv' => $userAv]);
    }

    public function editAvPc($id)
    {
        $objetivos = Objetivo::all();

        $user = auth()->user();

        $av = Av::findOrFail($id);
        $userAv = User::findOrFail($av->user_id);

        $user = auth()->user();
        $veiculosProprios = $user->veiculosProprios;

        $url = 'https://portaldosmunicipios.pr.gov.br/api/v1/medicao?status=27';
        $json = file_get_contents($url);
        $data = json_decode($json);
        $filtro = [];
        $filtroTodos = [];
        $medicoes = Medicao::all();

        foreach ($data as $item) {
            $jaExiste = false;
            foreach ($medicoes as $medicao) {
                if (($item->municipio_id == $medicao->municipio_id) && ($item->numero_projeto == $medicao->numero_projeto)
                && ($item->numero_lote == $medicao->numero_lote) && ($item->numero == $medicao->numero_medicao)) {
                    $jaExiste = true;
                }
            }
            if(!$jaExiste){
                if ($item->nome_supervisor == $user->name) { //  para teste 'Fernanda Espindola de Oliveira'
                    array_push($filtro, $item);
                }
                else{
                    array_push($filtroTodos, $item);
                }
            }
        }

        if($user->id != $av->user->id) {
            return redirect('/dashboard')->with('msg', 'Você não tem permissão para editar esta av!');
        }

        return view('avspc.edit', ['av' => $av, 'objetivos' => $objetivos, 'veiculosProprios' => $veiculosProprios, 'user'=> $user, 
        'filtro' => $filtro, 'filtroTodos' => $filtroTodos, 'userAv' => $userAv]);
    }

    public function enviarGestor(Request $request)
    {
        $av = Av::findOrFail($request->id);

        $dados = array(
            "valorReais" => $av->valorReais,
            "valorDolar" => $av->valorDolar,
            "valorDeducaoReais" => $request->valorDeducaoReais,
            "valorDeducaoDolar" => $request->valorDeducaoDolar,
            "valorExtraReais" => $request->valorExtraReais,
            "valorExtraDolar" => $request->valorExtraDolar,
            "justificativaValorExtra"=>$request->justificativaValorExtra,
            "status"=>"AV aguardando aprovação do Gestor"
        );

        $cidadeOrigem = null;
        $cidadeOrigemInternacional = null;
        $cidadeDestino = null;
        $cidadeDestinoInternacional = null;
        for($i=0;$i<count($av->rotas);$i++){
            if($i==0){
                $cidadeOrigem = $av->rotas[$i]->cidadeOrigemNacional;
                $cidadeOrigemInternacional = $av->rotas[$i]->cidadeOrigemInternacional;
            }
            if($i==count($av->rotas)-1){
                $cidadeDestino = $av->rotas[$i]->cidadeDestinoNacional;
                $cidadeDestinoInternacional = $av->rotas[$i]->cidadeDestinoInternacional;
            }
        }
        if(($cidadeOrigem != $cidadeDestino) && ($cidadeOrigem != $cidadeDestinoInternacional) 
        && ($cidadeOrigemInternacional != $cidadeDestino) && ($cidadeOrigemInternacional != $cidadeDestinoInternacional)){
            return redirect('/rotas/rotas/' . $av->id)->with('msg', 'A cidade de volta final deve ser igual a cidade de origem!');
        }

        $user = auth()->user();

        $historico = new Historico();
        $timezone = new DateTimeZone('America/Sao_Paulo');
        $historico->dataOcorrencia = new DateTime('now', $timezone);
        $historico->tipoOcorrencia = "Aguardando avaliação do Gestor";
        $historico->comentario = "Envio de AV para gestor";
        $historico->perfilDonoComentario = "Usuário";
        $historico->usuario_id = $av->user_id;
        $historico->usuario_comentario_id = $user->id;
        $historico->av_id = $av->id;

        $dados["isEnviadoUsuario"] = 1;

        //dd($dados);
        $users = User::all();
        $email = null;
        $usermanager = null;
        $managerDN = $user->manager; // CN=Leandro Victorino Moura,OU=CTI,OU=Empregados,DC=prcidade,DC=br

        // Dividir a string em partes usando o caractere de vírgula como delimitador
        $parts = explode(',', $managerDN);

        // Extrair o nome do gerente da primeira parte
        $managerName = substr($parts[0], 3); // Remover os primeiros 3 caracteres "CN="

        foreach ($users as $u){
            if($u->name == $managerName){
                $email = $u->username;
                $usermanager = $u;
            }
        }

        Mail::to($email)
            ->send(new EnvioEmailGestor($user->id, $usermanager->id));

        Av::findOrFail($request->id)->update($dados);
        $historico->save();

        return redirect('/avs/avs')->with('msg', 'AV enviada ao gestor!');
    }

    public function envioEmail(){
        return view('emails.envioEmailGestor');
    }

    public function voltarAv($id){
        
        $av = Av::findOrFail($id);
        $user = auth()->user();
        $dados = array(
            "isEnviadoUsuario" => 0,
            "isAprovadoGestor" => 0,
            "isRealizadoReserva" => 0,
            "isAprovadoFinanceiro" => 0,
            "isReservadoVeiculoProprio" => 0,
            "isVistoDiretoria" => 0,
            "status" => "Aguardando envio para o Gestor"
        );

        $historico = new Historico();
        $timezone = new DateTimeZone('America/Sao_Paulo');
        $historico->dataOcorrencia = new DateTime('now', $timezone);
        $historico->tipoOcorrencia = "Usuário retornou AV a estado inicial";
        $historico->comentario = "Retorno de AV para correção";
        $historico->perfilDonoComentario = "Usuário";
        $historico->usuario_id = $av->user_id;
        $historico->usuario_comentario_id = $user->id;
        $historico->av_id = $av->id;

        //dd($dados);

        Av::findOrFail($av->id)->update($dados);
        $historico->save();

        return redirect('/avs/avs')->with('msg', 'AV atualizada!');

    }

    public function autGestor(){
        
        $user = auth()->user();
        $avs = Av::all();
        $avsTodas = Av::all();
        $users = User::all();

        $usersFiltrados = [];
        foreach ($users as $u){//Percorre todos os usuários do sistema
            $managerDN = $u->manager; // CN=Leandro Victorino Moura,OU=CTI,OU=Empregados,DC=prcidade,DC=br

            // Dividir a string em partes usando o caractere de vírgula como delimitador
            $parts = explode(',', $managerDN);

            // Extrair o nome do gerente da primeira parte
            $managerName = substr($parts[0], 3); // Remover os primeiros 3 caracteres "CN="
            
            if($managerName == $user->name && $u->id != $user->id){//Verifica se cada um pertence ao seu time, exceto vc mesmo 
                array_push($usersFiltrados, $u);//Adiciona ao array filtrado o usuário encontrado
            }
        }
        
        $avsFiltradas = [];
        foreach($usersFiltrados as $uf){//Verifica todos os usuários encontrados
            foreach($uf->avs as $av){//Percorre todas as Avs do usuário encontrado
                if($av["isEnviadoUsuario"]==1 && $av["isAprovadoGestor"]==false && $av["isCancelado"]==false){ //Se a av dele já foi enviada, mas ainda não autorizada, adiciona ao array de avs filtradas
                    
                    array_push($avsFiltradas, $av);
                }
            }
        }
        $avs = $avsFiltradas;
        $objetivos = Objetivo::all();
        return view('avs.autGestor', ['avs' => $avs, 'user'=> $user, 'objetivos' => $objetivos, 'users' => $users, 
        'avsTodas' => $avsTodas, 'usersFiltrados' => $usersFiltrados]);
    }

    public function getAvsByUser($id)
    {
        $user = User::findOrFail($id);
        $avs = Av::all();
        $avsFiltradas = [];
        foreach($avs as $av){
            if($av->user_id == $user->id){
                array_push($avsFiltradas, $av);
            }
        }
        return response(json_encode($avsFiltradas, JSON_PRETTY_PRINT), 200)->header('Content-Type', 'application/json');
    }

    public function getAvsByManager($id)
    {
        $user = User::findOrFail($id);
        $users = User::all();
        $usersFiltrados = [];
        foreach ($users as $u){//Percorre todos os usuários do sistema
            $managerDN = $u->manager; // CN=Leandro Victorino Moura,OU=CTI,OU=Empregados,DC=prcidade,DC=br

            // Dividir a string em partes usando o caractere de vírgula como delimitador
            $parts = explode(',', $managerDN);

            // Extrair o nome do gerente da primeira parte
            $managerName = substr($parts[0], 3); // Remover os primeiros 3 caracteres "CN="
            
            if($managerName == $user->name && $u->id != $user->id){//Verifica se cada um pertence ao seu time, exceto vc mesmo 
                array_push($usersFiltrados, $u);//Adiciona ao array filtrado o usuário encontrado
            }
        }
        $avsFiltradas = [];
        foreach($usersFiltrados as $uf){//Verifica todos os usuários encontrados
            foreach($uf->avs as $av){//Percorre todas as Avs do usuário encontrado
                if($av["isEnviadoUsuario"]==1 && $av["isAprovadoGestor"]==false && $av["isCancelado"]==false){ //Se a av dele já foi enviada, mas ainda não autorizada, adiciona ao array de avs filtradas
                    
                    array_push($avsFiltradas, $av);
                }
            }
        }
        return response(json_encode($avsFiltradas, JSON_PRETTY_PRINT), 200)->header('Content-Type', 'application/json');
    }

    public function autDiretoria(){
        $user = auth()->user();
        $avs = Av::all();
        $users = User::all();

        $avsFiltradas = [];
        foreach($users as $uf){//Verifica todos os usuários
            if($uf->id != $user->id){
                foreach($uf->avs as $avAtual){//Percorre todas as Avs do usuário encontrado
                    if($avAtual["isEnviadoUsuario"]==1 && $avAtual["isAprovadoGestor"]==true && $avAtual["isVistoDiretoria"]==false && 
                    $avAtual["isCancelado"]==false && $avAtual["status"]!="AV Internacional cadastrada no sistema"){ //Se a av dele já foi enviada e autorizada pelo Gestor, adiciona ao array de avs filtradas
                        foreach($avAtual->rotas as $rota){//Percorre todas as rotas da AV
                            if($rota["isViagemInternacional"]==1 || $rota["isVeiculoProprio"]==1){//Se a viagem for internacional ou tiver veículo próprio
                                array_push($avsFiltradas, $avAtual);
                                break;
                            }
                        }
                    }
                }
            }
        }
        $avs = $avsFiltradas;
        $objetivos = Objetivo::all();
        return view('avs.autDiretoria', ['avs' => $avs, 'user'=> $user, 'objetivos' => $objetivos, 'users' => $users]);
    }

    public function autSecretaria(){
        $user = auth()->user();
        $avs = Av::all();
        $users = User::all();

        $avsFiltradas = [];
        foreach($users as $uf){//Verifica todos os usuários
            if($uf->id != $user->id){//Se  o usuário não for você
                foreach($uf->avs as $avAtual){//Percorre todas as Avs do usuário encontrado
                    if(($avAtual["isEnviadoUsuario"]==1 && $avAtual["isAprovadoGestor"]==true && $avAtual["isRealizadoReserva"]==false && $avAtual["isCancelado"]==false) 
                    || ($avAtual["isCancelado"]== true && $avAtual["isRealizadoReserva"]== true && $avAtual["isRealizadoCancelamentoReserva"]== false && $avAtual["isRealizadoCancelamentoReserva"] == false)){ //Se a av dele já foi enviada e autorizada pelo Gestor, adiciona ao array de avs filtradas
                        $isNecessarioAvaliacaoDiretoria = false;
                        $passouPelaDiretoria = false;
                        foreach($avAtual->rotas as $rota){//Percorre todas as rotas da AV
                            if($rota["isViagemInternacional"]==1 || $rota["isVeiculoProprio"]==1){//Se a viagem for internacional ou tiver veículo próprio
                                $isNecessarioAvaliacaoDiretoria = true;

                                if($avAtual["isVistoDiretoria"]==true)
                                {
                                    $passouPelaDiretoria = true;
                                }
                            }
                        }
                        if(($isNecessarioAvaliacaoDiretoria == true && $passouPelaDiretoria == true) || $isNecessarioAvaliacaoDiretoria == false){
                            array_push($avsFiltradas, $avAtual);
                        }
                    }
                }
            }
        }
        $avs = $avsFiltradas;
        $objetivos = Objetivo::all();
        return view('avs.autSecretaria', ['avs' => $avs, 'user'=> $user, 'objetivos' => $objetivos, 'users' => $users]);
    }

    public function prestacaoContasUsuario(){
        $user = auth()->user();
        $avs = Av::all();
        $users = User::all();

        $avsFiltradas = [];
        foreach($users as $uf){//Verifica todos os usuários
            if($uf->id == $user->id){//Se o usuário for você
                foreach($uf->avs as $avAtual){//Percorre todas as Avs do usuário encontrado
                    if(($avAtual["isEnviadoUsuario"]==1 && $avAtual["isAprovadoGestor"]==true && $avAtual["isRealizadoReserva"]==true 
                    && $avAtual["isAprovadoFinanceiro"]==true && $avAtual["isCancelado"]==false) || 
                    ($avAtual["isCancelado"]==true && $avAtual["isAprovadoFinanceiro"]==true )){
                        $isVeiculoEmpresa = false;
                        foreach($avAtual->rotas as $rota){//Percorre todas as rotas da AV
                            if($rota["isVeiculoEmpresa"]==1){//Se a viagem tiver veículo da empresa
                                if($avAtual["isReservadoVeiculoParanacidade"]==true){
                                    $isVeiculoEmpresa = true;
                                    array_push($avsFiltradas, $avAtual);
                                    break;
                                }
                            }
                        }
                        if($isVeiculoEmpresa == false){
                            array_push($avsFiltradas, $avAtual);
                        }
                    }
                }
            }
        }
        $avs = $avsFiltradas;
        $objetivos = Objetivo::all();
        return view('avs.prestacaoContasUsuario', ['avs' => $avs, 'user'=> $user, 'objetivos' => $objetivos]);
    }

    public function autAdmFrota(){
        $user = auth()->user();
        $avs = Av::all();
        $users = User::all();

        $avsFiltradas = [];
        foreach($users as $uf){//Verifica todos os usuários
            if($uf->id != $user->id){//Se  o usuário não for você
                foreach($uf->avs as $avAtual){//Percorre todas as Avs do usuário encontrado
                    if($avAtual["isEnviadoUsuario"]==1 && $avAtual["isAprovadoGestor"]==true && $avAtual["isReservadoVeiculoParanacidade"]==false
                    && $avAtual["isCancelado"]==false){ //Se a av dele já foi enviada e autorizada pelo Gestor, adiciona ao array de avs filtradas

                        foreach($avAtual->rotas as $rota){//Percorre todas as rotas da AV
                            if($rota["isVeiculoEmpresa"]==1){//Se a viagem tiver veículo da empresa
                                array_push($avsFiltradas, $avAtual);
                                break;
                            }
                        }
                    }
                }
            }
        }
        $avs = $avsFiltradas;
        $objetivos = Objetivo::all();
        return view('avs.autAdmFrota', ['avs' => $avs, 'user'=> $user, 'objetivos' => $objetivos, 'users' => $users]);
    }

    public function cancelarAv($id){
        $user = auth()->user();
        $avs = $user->avs;
        $av = null;
        foreach($avs as $avFiltrada){
            if($avFiltrada->id == $id){
                $av = $avFiltrada;
            }
        }
        return view('avs.cancelarAv', ['av' => $av, 'user'=> $user]);
    }

    public function autFinanceiro(){
        $user = auth()->user();
        $userAtual = User::findOrFail($user->id);
        $avs = Av::all();
        $users = User::all();
        $usersFiltrados = [];

        $isFinanceiroCuritiba = false;
        $temFinanceiroCascavel = false;
        $temFinanceiroMaringa = false;
        $temFinanceiroFrancisco = false;
        $temFinanceiroGuarapuava = false;
        $temFinanceiroLondrina = false;
        $temFinanceiroPontaGrossa = false;

        $permission = Permission::where('name', 'aprov avs financeiro')->first();
        
        if($userAtual->hasPermissionTo($permission)){
            if($user->department == "ERCSC"){
                foreach($users as $uf){
                    if($uf->department == "ERCSC"){
                        array_push($usersFiltrados, $uf);
                    }
                }
            }
            else if($user->department == "ERMGA"){
                foreach($users as $uf){
                    if($uf->department == "ERMGA"){
                        array_push($usersFiltrados, $uf);
                    }
                }
            }
            else if($user->department == "ERFCB"){
                foreach($users as $uf){
                    if($uf->department == "ERFCB"){
                        array_push($usersFiltrados, $uf);
                    }
                }
            }
            else if($user->department == "ERGUA"){
                foreach($users as $uf){
                    if($uf->department == "ERGUA"){
                        array_push($usersFiltrados, $uf);
                    }
                }
            }
            else if($user->department == "ERLDA"){
                foreach($users as $uf){
                    if($uf->department == "ERLDA"){
                        array_push($usersFiltrados, $uf);
                    }
                }
            }
            else if($user->department == "ERPTG"){
                foreach($users as $uf){
                    if($uf->department == "ERPTG"){
                        array_push($usersFiltrados, $uf);
                    }
                }
            }
            else{
                $isFinanceiroCuritiba = true;
            }
        }
        
        if($isFinanceiroCuritiba == true){
            foreach($users as $uf){
                if($uf->department == "ERCSC"){
                    try {
                        if($uf->hasPermissionTo($permission)){
                            $temFinanceiroCascavel = true;
                        }
                    } catch (\Throwable $th) {
                    }
                }
                else if($uf->department == "ERMGA"){
                    try {
                        if($uf->hasPermissionTo($permission)){
                            $temFinanceiroMaringa = true;
                        }
                    } catch (\Throwable $th) {
                    }
                }
                else if($uf->department == "ERFCB"){
                    try {
                        if($uf->hasPermissionTo($permission)){
                            $temFinanceiroFrancisco = true;
                        }
                    } catch (\Throwable $th) {
                    }
                }
                else if($uf->department == "ERGUA"){
                    try {
                        if($uf->hasPermissionTo($permission)){
                            $temFinanceiroGuarapuava = true;
                        }
                    } catch (\Throwable $th) {
                    }
                }
                else if($uf->department == "ERLDA"){
                    try {
                        if($uf->hasPermissionTo($permission)){
                            $temFinanceiroLondrina = true;
                        }
                    } catch (\Throwable $th) {
                    }
                }
                else if($uf->department == "ERPTG"){
                    try {
                        if($uf->hasPermissionTo($permission)){
                            $temFinanceiroPontaGrossa = true;
                        }
                    } catch (\Throwable $th) {
                    }
                }
            }
            
            foreach($users as $uf){
                if($temFinanceiroCascavel == false){
                    if($uf->department == "ERCSC"){
                        array_push($usersFiltrados, $uf);
                    }
                }
                if($temFinanceiroMaringa == false){
                    if($uf->department == "ERMGA"){
                        array_push($usersFiltrados, $uf);
                    }
                }
                if($temFinanceiroFrancisco == false){
                    if($uf->department == "ERFCB"){
                        array_push($usersFiltrados, $uf);
                    }
                }
                if($temFinanceiroGuarapuava == false){
                    if($uf->department == "ERGUA"){
                        array_push($usersFiltrados, $uf);
                    }
                }
                if($temFinanceiroLondrina == false){
                    if($uf->department == "ERLDA"){
                        array_push($usersFiltrados, $uf);
                    }
                }
                if($temFinanceiroPontaGrossa == false){
                    if($uf->department == "ERPTG"){
                        array_push($usersFiltrados, $uf);
                    }
                }
                if($uf->department != "ERCSC" && $uf->department != "ERMGA" && $uf->department != "ERFCB" && $uf->department != "ERGUA" 
                && $uf->department != "ERLDA" && $uf->department != "ERPTG"){
                    array_push($usersFiltrados, $uf);
                }
            }
        }

        $avsFiltradas = [];
        foreach($usersFiltrados as $uf){//Verifica todos os usuários
            if($uf->id != $user->id){//Se  o usuário não for você
                foreach($uf->avs as $avAtual){//Percorre todas as Avs do usuário encontrado
                    if($avAtual["isEnviadoUsuario"]==1 && $avAtual["isAprovadoGestor"]==true && $avAtual["isAprovadoFinanceiro"]==false 
                    && $avAtual["isCancelado"]==false){ //Se a av dele já foi enviada e autorizada pelo Gestor, adiciona ao array de avs filtradas

                        $isNecessarioAvaliacaoDiretoria = false;
                        $passouPelaDiretoria = false;
                        foreach($avAtual->rotas as $rota){//Percorre todas as rotas da AV
                            if($rota["isViagemInternacional"]==1 || $rota["isVeiculoProprio"]==1){//Se a viagem for internacional ou tiver veículo próprio
                                $isNecessarioAvaliacaoDiretoria = true;

                                if($avAtual["isVistoDiretoria"]==true)
                                {
                                    $passouPelaDiretoria = true;
                                }
                            }
                        }
                        if(($isNecessarioAvaliacaoDiretoria == true && $passouPelaDiretoria == true) || $isNecessarioAvaliacaoDiretoria == false){
                            array_push($avsFiltradas, $avAtual);
                        }
                    }
                }
            }
        }

        $avs = $avsFiltradas;
        $objetivos = Objetivo::all();
        return view('avs.autFinanceiro', ['avs' => $avs, 'user'=> $user, 'objetivos' => $objetivos, 'users' => $users,
        'temFinanceiroCascavel' => $temFinanceiroCascavel, 'temFinanceiroMaringa' => $temFinanceiroMaringa, 'temFinanceiroFrancisco' => $temFinanceiroFrancisco,
        'temFinanceiroGuarapuava' => $temFinanceiroGuarapuava, 'temFinanceiroLondrina' => $temFinanceiroLondrina, 'temFinanceiroPontaGrossa' => $temFinanceiroPontaGrossa,
        'isFinanceiroCuritiba' => $isFinanceiroCuritiba]);
    }

    public function autPcFinanceiro(){
        $user = auth()->user();
        $avs = Av::all();
        $users = User::all();

        $avsFiltradas = [];
        foreach($users as $uf){//Verifica todos os usuários
            if($uf->id != $user->id){//Se  o usuário não for você
                foreach($uf->avs as $avAtual){//Percorre todas as Avs do usuário encontrado
                    if(($avAtual["isEnviadoUsuario"]==1 && $avAtual["isAprovadoGestor"]==true && $avAtual["isRealizadoReserva"]==true && $avAtual["isAprovadoFinanceiro"]==true
                    && $avAtual["isPrestacaoContasRealizada"]==true && $avAtual["isFinanceiroAprovouPC"]==false && $avAtual["isCancelado"]==false) || 
                    ($avAtual["isCancelado"]==true && $avAtual["isAprovadoFinanceiro"]==true && $avAtual["isPrestacaoContasRealizada"] == true 
                    && $avAtual["isFinanceiroAprovouPC"] == false)){ //Se a av dele já foi enviada e autorizada pelo Gestor, adiciona ao array de avs filtradas

                        array_push($avsFiltradas, $avAtual);
                    }
                }
            }
        }
        $avs = $avsFiltradas;
        $objetivos = Objetivo::all();
        return view('avs.autPcFinanceiro', ['avs' => $avs, 'user'=> $user, 'objetivos' => $objetivos, 'users'=> $users]);
    }

    public function acertoContasFinanceiro(){
        $user = auth()->user();
        $avs = Av::all();
        $users = User::all();

        $avsFiltradas = [];
        foreach($users as $uf){//Verifica todos os usuários
            if($uf->id != $user->id){//Se  o usuário não for você
                foreach($uf->avs as $avAtual){//Percorre todas as Avs do usuário encontrado
                    if(($avAtual["isEnviadoUsuario"]==1 && $avAtual["isAprovadoGestor"]==true && $avAtual["isRealizadoReserva"]==true && $avAtual["isAprovadoFinanceiro"]==true
                    && $avAtual["isPrestacaoContasRealizada"]==true && $avAtual["isFinanceiroAprovouPC"]==true 
                    && $avAtual["isGestorAprovouPC"]==true&& $avAtual["isAcertoContasRealizado"]==false && $avAtual["isCancelado"]==false) || 
                    ($avAtual["isCancelado"]==true && $avAtual["isAprovadoFinanceiro"]==true && $avAtual["isPrestacaoContasRealizada"] == true 
                    && $avAtual["isFinanceiroAprovouPC"] == true && $avAtual["isGestorAprovouPC"] == true && $avAtual["isAcertoContasRealizado"] == false)){ //Se a av dele já foi enviada e autorizada pelo Gestor, adiciona ao array de avs filtradas
                        
                        array_push($avsFiltradas, $avAtual);
                    }
                }
            }
        }
        $avs = $avsFiltradas;
        $objetivos = Objetivo::all();
        return view('avs.acertoContasFinanceiro', ['avs' => $avs, 'user'=> $user, 'objetivos' => $objetivos]);
    }

    public function autPcGestor(){
        $user = auth()->user();
        $avs = Av::all();
        $users = User::all();
        $usersFiltrados = [];

        foreach ($users as $u){//Percorre todos os usuários do sistema
            $managerDN = $u->manager; // CN=Leandro Victorino Moura,OU=CTI,OU=Empregados,DC=prcidade,DC=br

            // Dividir a string em partes usando o caractere de vírgula como delimitador
            $parts = explode(',', $managerDN);

            // Extrair o nome do gerente da primeira parte
            $managerName = substr($parts[0], 3); // Remover os primeiros 3 caracteres "CN="
            
            if($managerName == $user->name && $u->id != $user->id){//Verifica se cada um pertence ao seu time, exceto vc mesmo  
                array_push($usersFiltrados, $u);//Adiciona ao array filtrado o usuário encontrado
            }
        }

        $avsFiltradas = [];
        foreach($usersFiltrados as $uf){//Verifica todos os usuários
            foreach($uf->avs as $avAtual){//Percorre todas as Avs do usuário encontrado
                if(($avAtual["isEnviadoUsuario"]==1 && $avAtual["isAprovadoGestor"]==true && $avAtual["isRealizadoReserva"]==true && $avAtual["isAprovadoFinanceiro"]==true
                && $avAtual["isPrestacaoContasRealizada"]==true && $avAtual["isFinanceiroAprovouPC"]==true  && $avAtual["isGestorAprovouPC"]==false
                && $avAtual["isCancelado"]==false) || 
                ($avAtual["isCancelado"]==true && $avAtual["isAprovadoFinanceiro"]==true && $avAtual["isPrestacaoContasRealizada"] == true 
                && $avAtual["isFinanceiroAprovouPC"] == true && $avAtual["isGestorAprovouPC"] == false)){ //Se a av dele já foi enviada e autorizada pelo Gestor, adiciona ao array de avs filtradas

                    array_push($avsFiltradas, $avAtual);
                }
            }
        }
        $avs = $avsFiltradas;
        $objetivos = Objetivo::all();
        return view('avs.autPcGestor', ['avs' => $avs, 'user'=> $user, 'objetivos' => $objetivos, 'users' => $users]);
    }


    public function update(Request $request)
    {

        $regras = [
            'objetivo_id' => 'required',
            'outroObjetivo' => 'required'
        ];

        $idArrayTodosSelecionados = null;
        $idArrayUsuarioSelecionou = null;

        $idArrayTodosSelecionados = $request->input('todosSelecionados');
        $idArrayUsuarioSelecionou = $request->input('medicoesUsuarioSelecionadas');

        if($request->get('isVeiculoProprio')=="1"){ // Se for veículo próprio, adiciona validação de campo
            $request->request->set('isVeiculoEmpresa', null);
            unset($regras['isVeiculoEmpresa']);
        }
        else if($request->get('isVeiculoEmpresa')=="1"){
            $request->request->set('isVeiculoProprio', null);
            unset($regras['isVeiculoProprio']);
        }

        $mensagens = [
            'required' => 'Este campo não pode estar em branco',
        ];
        
        if ($request->get('isSelecionado')=="1" || $request->get('outroObjetivo') != null) //Se existir outro objetivo, remove a necessidade de validação de objetivo
        {
            $request->request->set('objetivo_id', null);
            unset($regras['objetivo_id']); //Retira a regra de validação de objetivo
        }
        else{
            $request->request->set('outroObjetivo', null);
            unset($regras['outroObjetivo']); //Retira a regra de validação de outro objetivo
        }
        $request->validate($regras, $mensagens);

        $data = $request->all();
        $data['isDiaria'] = $request->isDiaria == "Sim" ? true : false;

        $av = Av::findOrFail($request->id);
        $av->update($data);

        $avRecuperada = Av::findOrFail($av->id);

        $medicoes = Medicao::all();
        foreach($medicoes as $medicao){
            if($medicao->av_id == $avRecuperada->id){
                $medicao->delete();
            }
        }

        $user = User::findOrFail($avRecuperada->user_id);

        if($request->hasFile('arquivo1') && $request->file('arquivo1')->isValid())
        {
            //remova o arquivo anterior
            if($avRecuperada->autorizacao != null){
                $path = '/mnt/arquivos_viagem/AVs/' . $user->name . '/' . $avRecuperada->id . '/' . 'autorizacaoAv' . '/' . $avRecuperada->autorizacao;
                unlink($path);
            }

            $requestFile = $request->arquivo1;

            $extension = $requestFile->extension();

            $fileName = md5($requestFile->getClientOriginalName() . strtotime("now")) . "." . $extension;
            
            $requestFile->move('/mnt/arquivos_viagem/AVs/' . $user->name . '/' . $avRecuperada->id . '/' . 'autorizacaoAv', $fileName);

            $avRecuperada->autorizacao = $fileName;

            $avRecuperada->save();
        }

        $url = 'https://portaldosmunicipios.pr.gov.br/api/v1/medicao?status=27';
        $json = file_get_contents($url);
        $data = json_decode($json);
        foreach ($data as $item) {
            if($idArrayUsuarioSelecionou != null){
                foreach($idArrayUsuarioSelecionou as $selecionado){
                    if ($item->id == $selecionado) {
                        $medicao = new Medicao();
                        $medicao->nome_municipio = $item->nome_municipio;
                        $medicao->municipio_id = $item->municipio_id;
                        $medicao->numero_projeto = $item->numero_projeto;
                        $medicao->numero_lote = $item->numero_lote;
                        $medicao->numero_medicao = $item->numero;
                        $medicao->av_id = $avRecuperada->id;
                        $medicao->save();
                    }
                }
            }
            if($idArrayTodosSelecionados != null){
                foreach($idArrayTodosSelecionados as $selecionado){
                    if ($item->id == $selecionado) {
                        $medicao = new Medicao();
                        $medicao->nome_municipio = $item->nome_municipio;
                        $medicao->municipio_id = $item->municipio_id;
                        $medicao->numero_projeto = $item->numero_projeto;
                        $medicao->numero_lote = $item->numero_lote;
                        $medicao->numero_medicao = $item->numero;
                        $medicao->av_id = $avRecuperada->id;
                        $medicao->save();
                    }
                }
            }
        }

        if($request->isPc=="sim"){
            return redirect('/avs/fazerPrestacaoContas/' . $request->id)->with('msg', 'Av editado com sucesso!');
        }
        else{
            return redirect('/avs/avs')->with('msg', 'Av editado com sucesso!');
        }
    }

    public function marcarComoCancelado(Request $request)
    {

        $user = auth()->user();
        $av = Av::findOrFail($request->id);

        if($av->isAprovadoFinanceiro == false && $av->isRealizadoReserva == true){
            $dados = array(
                "isCancelado" => 1,
                "status" => "AV Cancelada - Pendente de cancelamento de reservas pelo CAD",
                "valorExtraReais" => 0,
                "valorExtraDolar" => 0,
                "valorReais" => 0,
                "valorDolar" => 0,
                'justificativaCancelamento' => $request->justificativa
            );
        }
        else if($av->isAprovadoFinanceiro == true && $av->isRealizadoReserva == false){
            $dados = array(
                "isCancelado" => 1,
                "status" => "AV Cancelada - Pendente de prestação de contas pelo usuário",
                "valorExtraReais" => 0,
                "valorExtraDolar" => 0,
                "valorReais" => 0,
                "valorDolar" => 0,
                'justificativaCancelamento' => $request->justificativa
            );
        }
        else if($av->isAprovadoFinanceiro == true && $av->isRealizadoReserva == true){
            $dados = array(
                "isCancelado" => 1,
                "status" => "AV Cancelada - Pendente de prestação de contas pelo usuário e de cancelamento de reservas pelo CAD",
                "valorExtraReais" => 0,
                "valorExtraDolar" => 0,
                "valorReais" => 0,
                "valorDolar" => 0,
                'justificativaCancelamento' => $request->justificativa
            ); 
        }
        else{
            $dados = array(
                "isCancelado" => 1,
                "status" => "AV Cancelada",
                "valorExtraReais" => 0,
                "valorExtraDolar" => 0,
                "valorReais" => 0,
                "valorDolar" => 0,
                'justificativaCancelamento' => $request->justificativa
            ); 
        }

        $historico = new Historico();
        $timezone = new DateTimeZone('America/Sao_Paulo');
        $historico->dataOcorrencia = new DateTime('now', $timezone);
        $historico->tipoOcorrencia = "AV Cancelada pelo usuário: " . $user->name;
        $historico->comentario = "Cancelamento de AV";
        $historico->perfilDonoComentario = "Usuário";
        $historico->usuario_id = $av->user_id;
        $historico->usuario_comentario_id = $user->id;
        $historico->av_id = $av->id;

        //No caso do CFI, if($av->isCancelado && $av->isAprovadoFinanceiro), listar para o CFI
        //Neste caso, também deverá ser mostrado na página de prestação de contas. 
        //Aqui quando for cancelado, deverão ser zeradas as rotas, de modo a não ser necessário pagar diária de alimentação
        //Além disso, deverá ser setado que quando uma AV foi cancelada, não exiba o campo de edição de AV e Rota na página de Prestação de Contas
        // Lá, o usuário deverá preencher as informações sobre o motivo do cancelamento e envia. Quando chegar no Acerto de Contas

        $av->update($dados);
        $historico->save();

        return redirect('/avs/avs')->with('msg', 'Av cancelada com sucesso!');
        
    }

    public function geraArrayDiasValores($av){

        $rotas = $av->rotas;
        for ($i=0; $i < sizeof($rotas)-1 ; $i++) { 
            //Verifica quais são das datas da rota atual e da próxima
            $dataHoraSaidaRota1 = new DateTime($rotas[$i]->dataHoraSaida);//Data de saída da rota 1
            $dataHoraChegadaRota1 = new DateTime($rotas[$i]->dataHoraChegada);//Data de chegada da rota 1

            $dataHoraSaidaRota2 = new DateTime($rotas[$i + 1]->dataHoraSaida);//Data de saída da rota 2
            $dataHoraChegadaRota2 = new DateTime($rotas[$i + 1]->dataHoraChegada);//Data de chegada da rota 2
            
            //DATAS ROTA 1
            $anoSaidaRota1 = $dataHoraSaidaRota1->format('Y');
            $mesSaidaRota1 = $dataHoraSaidaRota1->format('m');
            $diaSaidaRota1 = $dataHoraSaidaRota1->format('d');
            $horaSaidaRota1 = $dataHoraSaidaRota1->format('H');
            $minutoSaidaRota1 = $dataHoraSaidaRota1->format('i');

            $mesChegadaRota1 = $dataHoraChegadaRota1->format('m');
            $diaChegadaRota1 = $dataHoraChegadaRota1->format('d');
            $horaChegadaRota1 = $dataHoraChegadaRota1->format('H');
            $minutoChegadaRota1 = $dataHoraChegadaRota1->format('i');

            //DATAS ROTA 2
            $mesSaidaRota2 = $dataHoraSaidaRota2->format('m');
            $diaSaidaRota2 = $dataHoraSaidaRota2->format('d');
            $horaSaidaRota2 = $dataHoraSaidaRota2->format('H');
            $minutoSaidaRota2 = $dataHoraSaidaRota2->format('i');

            $mesChegadaRota2 = $dataHoraChegadaRota2->format('m');
            $diaChegadaRota2 = $dataHoraChegadaRota2->format('d');
            $horaChegadaRota2 = $dataHoraChegadaRota2->format('H');
            $minutoChegadaRota2 = $dataHoraChegadaRota2->format('i');
            
            if($i==0){
                $anoSaidaInicial = $anoSaidaRota1;
                $mesSaidaInicial = $mesSaidaRota1;
                $diaSaidaInicial = $diaSaidaRota1;
                $horaSaidaInicial = $horaSaidaRota1;
                $minutoSaidaInicial = $minutoSaidaRota1;

                $mesChegadaInicial = $mesChegadaRota1;
                $diaChegadaInicial = $diaChegadaRota1;
                $horaChegadaInicial = $horaChegadaRota1;
                $minutoChegadaInicial = $minutoChegadaRota1;
            }
            if($i == sizeof($rotas)-2){
                $mesSaidaFinal = $mesSaidaRota2;
                $diaSaidaFinal = $diaSaidaRota2;
                $horaSaidaFinal = $horaSaidaRota2;
                $minutoSaidaFinal = $minutoSaidaRota2;

                $mesChegadaFinal = $mesChegadaRota2;
                $diaChegadaFinal = $diaChegadaRota2;
                $horaChegadaFinal = $horaChegadaRota2;
                $minutoChegadaFinal = $minutoChegadaRota2;
            }
        }

        $dataInicio = "$anoSaidaInicial-$mesSaidaInicial-$diaSaidaInicial";
        $dataFim = "$anoSaidaInicial-$mesChegadaFinal-$diaChegadaFinal";

                       
        $arrayDiasValores = [];
                        
        $intervaloDatas = new DatePeriod(
            new DateTime($dataInicio),
            new DateInterval('P1D'),
            (new DateTime($dataFim))->modify('+1 day') // Adicionar 1 dia ao fim para inclusão do último dia
        );
                        
        foreach ($intervaloDatas as $data) {
            $dia = $data->format('Y-m-d');
            $valor = 0;
            $acumulado = 0;

            for ($i=0; $i < sizeof($rotas)-1 ; $i++) {
                    $dataSaida = DateTime::createFromFormat('Y-m-d H:i:s', $rotas[$i]->dataHoraSaida)->format('Y-m-d H:i:s');
                    $dataSaida2 = DateTime::createFromFormat('Y-m-d H:i:s', $rotas[$i + 1]->dataHoraSaida)->format('Y-m-d H:i:s');
                    $dataChegada2 = DateTime::createFromFormat('Y-m-d H:i:s', $rotas[$i + 1]->dataHoraChegada)->format('Y-m-d H:i:s');

                    $dataSaidaModificado1 = new DateTime($dataSaida);//Data de saída da rota 1
                    $dataSaidaModificado2 = new DateTime($dataSaida2);//Data de saída da rota 2
                    $dataChegadaModificado2 = new DateTime($dataChegada2);//Data de saída da rota 2

                    $horaSaidaRota1 = $dataSaidaModificado1->format('H');
                    $minutoSaidaRota1 = $dataSaidaModificado1->format('i');

                    $horaSaidaRota2 = $dataSaidaModificado2->format('H');
                    $minutoSaidaRota2 = $dataSaidaModificado2->format('i');

                    $horaChegadaRota2 = $dataChegadaModificado2->format('H');
                    $minutoChegadaRota2 = $dataChegadaModificado2->format('i');

                    $dataSaida = DateTime::createFromFormat('Y-m-d H:i:s', $rotas[$i]->dataHoraSaida)->format('Y-m-d');
                    $dataSaida2 = DateTime::createFromFormat('Y-m-d H:i:s', $rotas[$i + 1]->dataHoraSaida)->format('Y-m-d');
                    $dataChegada2 = DateTime::createFromFormat('Y-m-d H:i:s', $rotas[$i + 1]->dataHoraChegada)->format('Y-m-d');

                    if ($dia >= $dataSaida && $dia <= $dataSaida2) {
                        
                        if($rotas[$i]->continenteDestinoInternacional == 1 && $rotas[$i]->paisDestinoInternacional !=30){//América Latina ou Amética Central
                            $valor = 100;
                        }
                        else if($rotas[$i]->continenteDestinoInternacional == 2){//América do Norte
                            $valor = 150;
                        }
                        else if($rotas[$i]->continenteDestinoInternacional == 3){//Europa
                            $valor = 180;
                        }
                        else if($rotas[$i]->continenteDestinoInternacional == 4){//África
                            $valor = 140;
                        }
                        else if($rotas[$i]->continenteDestinoInternacional == 5){//Ásia
                            $valor = 190;
                        }else if(($rotas[$i]->cidadeDestinoNacional == "Curitiba" || $rotas[$i]->cidadeDestinoNacional == "Foz do Iguaçu") ||
                        ($rotas[$i]->paisDestinoInternacional == 30 && $rotas[$i]->estadoDestinoInternacional == "Paraná" && 
                        ($rotas[$i]->cidadeDestinoInternacional == "Curitiba" || $rotas[$i]->cidadeDestinoInternacional == "Foz do Iguaçu"))){//Se for Curitiba ou Foz do Iguaçu
                            $valor = 65;
                        }
                        else if($rotas[$i]->estadoDestinoNacional == "Paraná" || ($rotas[$i]->paisDestinoInternacional == 30 && $rotas[$i]->estadoDestinoInternacional == "Paraná")){//Se for outra cidade do Paraná
                            $valor = 55;
                        }
                        else if($rotas[$i]->cidadeDestinoNacional == "Brasília" || ($rotas[$i]->paisDestinoInternacional == 30 && $rotas[$i]->cidadeDestinoInternacional == "Brasília")){//Se for Brasília
                            $valor = 100;
                        }
                        else{//Se não entrou em nenhum if, então é uma capital ou cidade de outros estados
                            $valor = 80;
                        }
                        if($dia == $dataSaida){
                            if($horaSaidaRota1 >= 13 && $horaSaidaRota1 < 19){
                                $metade = ($valor/2);
                                if($acumulado == 0){
                                    $acumulado = $metade;
                                }
                                else{
                                    $acumulado += $metade;
                                }
                            }
                        }
                        if(!($i == sizeof($rotas)-2)){
                            if($dia == $dataSaida2){
                                if($horaSaidaRota2 >= 13 && $horaSaidaRota2 < 19){
                                    $metade = ($valor/2);
                                    if($acumulado == 0){
                                        $acumulado = $metade;
                                    }
                                    else{
                                        $acumulado += $metade;
                                    }
                                }
                            }
                        }
                        if($i == sizeof($rotas)-2){
                            if($dia == $dataChegada2){
                                if($horaChegadaRota2 >= 13 && $horaChegadaRota2 < 19){
                                    $metade = ($valor/2);
                                    if($acumulado == 0){
                                        $acumulado = $metade;
                                    }
                                    else{
                                        $acumulado += $metade;
                                    }
                                }
                                else if($horaChegadaRota2 >= 19){
                                    if($acumulado == 0){
                                        $acumulado = $valor;
                                    }
                                    else{
                                        $acumulado += $valor;
                                    }
                                }
                            }
                        }
                    }
            }
            $diaFormatado = DateTime::createFromFormat('Y-m-d', $dia);
            $arrayDiasValores[] = [
                'dia' => $diaFormatado->format('d'),
                'valor' => $acumulado != 0 ? $acumulado : $valor,
            ];
        }
        $colecao = collect([$arrayDiasValores, $anoSaidaInicial, $mesSaidaInicial, $diaSaidaInicial, $horaSaidaInicial, $minutoSaidaInicial,
                                                                $mesChegadaInicial, $diaChegadaInicial, $horaChegadaInicial, $minutoChegadaInicial,
                                                                $mesSaidaFinal, $diaSaidaFinal, $horaSaidaFinal, $minutoSaidaFinal,
                                                                $mesChegadaFinal, $diaChegadaFinal, $horaChegadaFinal, $minutoChegadaFinal,
                                                                $dataInicio, $dataFim]);
        return $colecao;
    }
}
