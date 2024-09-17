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
use App\Mail\EnvioGestorToUsuarioDevolverDespesas;
use App\Mail\EnvioUsuarioToFinanceiroDevolucao;
use App\Mail\EnvioUsuarioToAdministrativoAvInternacionalAviso;
use App\Mail\EnvioUsuarioToAdministrativoAvInternacionalNotificacao;
use App\Mail\EnvioUsuarioToFinanceiroAvInternacionalAviso;
use App\Mail\EnvioUsuarioToFinanceiroAvInternacionalNotificacao;
use Illuminate\Support\Facades\Mail;
use Spatie\Permission\Models\Permission;
use App\Models\Country;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ControladorAv extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $avs = $user->avs;

        $managerDN = $user->manager; // CN=Leandro Victorino Moura,OU=CTI,OU=Empregados,DC=prcidade,DC=br

        // Dividir a string em partes usando o caractere de vírgula como delimitador
        $parts = explode(',', $managerDN);

        // Extrair o nome do gerente da primeira parte
        $managerName = substr($parts[0], 3); // Remover os primeiros 3 caracteres "CN="

        $isCuritiba = false;

        if($user->department != "ERCSC" 
        && $user->department != "ERMGA" 
        && $user->department != "ERFCB" 
        && $user->department != "ERGUA" 
        && $user->department != "ERLDA" 
        && $user->department != "ERPTG"){
            $isCuritiba = true;
        }
        
        if ($user->dataAssinaturaTermo == null) {
            return view('termoResponsabilidade', ['user'=> $user]);
        }
        else{
            return view('welcome', ['avs' => $avs, 'user'=> $user, 'managerName' => $managerName, 'isCuritiba' => $isCuritiba]);
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

    public function gerenciarAvsRh()
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
        return view('avs.avsAdmRh', ['avs' => $avs, 'user'=> $user, 'objetivos' => $objetivos, 'users' => $users]);
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

            if($item->email_supervisor == $user->username){
                array_push($filtro, $item);
            }
            else{
                array_push($filtroTodos, $item);
            }
            
        }
        return view('avs.create', ['objetivos' => $objetivos, 'veiculosProprios' => $veiculosProprios, 'veiculosParanacidade' => $veiculosParanacidade, 
        'user'=> $user, 'filtro' => $filtro, 'filtroTodos' => $filtroTodos, 'bancos' => $bancos, 'agencias' => $agencias, 'contas' => $contas, 'pixs' => $pixs]);
    }

    public function tirarAcentos($string){
        return preg_replace(array("/(á|à|ã|â|ä)/", "/(Á|À|Ã|Â|Ä)/", "/(é|è|ê|ë)/", "/(É|È|Ê|Ë)/", "/(í|ì|î|ï)/", "/(Í|Ì|Î|Ï)/", "/(ó|ò|õ|ô|ö)/", "/(Ó|Ò|Õ|Ô|Ö)/", "/(ú|ù|û|ü)/", "/(Ú|Ù|Û|Ü)/", "/(ñ)/", "/(Ñ)/"), explode(" ", "a A e E i I o O u U n N"), $string);
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

        if(count($av->rotas) > 0){
            $arrayDiasValores = $this->geraArrayDiasValoresCerto($av);
        }
        else{
            $arrayDiasValores = [];
        }

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

        $managerDN = $userAv->manager; // CN=Leandro Victorino Moura,OU=CTI,OU=Empregados,DC=prcidade,DC=br

        // Dividir a string em partes usando o caractere de vírgula como delimitador
        $parts = explode(',', $managerDN);

        // Extrair o nome do gerente da primeira parte
        $managerName = substr($parts[0], 3); // Remover os primeiros 3 caracteres "CN="

        if($managerName == $userAv->name){
            $possoEditar = true;
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

        //código para obter as reservas do usuário da AV -------------------------------------------------------------------------
        $eventos = [];
        $reservas2 = [];
        $veiculos = [];

        $url = 'http://10.51.10.43/reservas/public/api/getVeiculosAPI';
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $veiculos = json_decode(curl_exec($ch));
        //crie uma coleção de $veiculos
        $veiculos = collect($veiculos);

        $url = 'http://10.51.10.43/reservas/public/api/getTodasReservasAPI';
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $reservas2 = json_decode(curl_exec($ch));
        //crie uma coleção de $reservas2
        $reservas2 = collect($reservas2);

        if(count($reservas2) > 0){
            //filtre as reservas de acordo com o $av->idReservaVeiculo e a coluna a ser filtrada é id de reserva
            $reservas2 = $reservas2->filter(function ($reserva) use ($av) {
                if($reserva->id == $av->idReservaVeiculo){
                    return true;
                }
                else{
                    return false;
                }
            });
        }

        if(count($veiculos) > 0){
            //verifique qual é o veículo da reserva pela coluna idVeiculo de $reservas2 e adicione uma nova coluna chamada veiculo
            $reservas2 = $reservas2->map(function ($reserva) use ($veiculos) {
                $veiculo = $veiculos->where('id', $reserva->idVeiculo)->first();
                $reserva->veiculo = $veiculo;
                return $reserva;
            });
        }
        //------------------------------------------------------------------------------------------------------------------------

        if($possoEditar == true){
            return view('avs.verFluxoGestor', ['av' => $av, 'objetivos' => $objetivos, 'veiculosProprios' => $veiculosProprios, 
            'user'=> $user, 'historicos'=> $historicos, 'users'=> $users, 'userAv' => $userAv, 'veiculosParanacidade' => $veiculosParanacidade,
            'isInternacional' => $isInternacional, 'medicoesFiltradas' => $medicoesFiltradas, 'arrayDiasValores' => $arrayDiasValores,
            'eventos' => $eventos, 'reservas2' => $reservas2, 'veiculos' => $veiculos]);
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

        if(count($av->rotas) > 0){
            $arrayDiasValores = $this->geraArrayDiasValoresCerto($av);
        }
        else{
            $arrayDiasValores = [];
        }

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
            'anexos' => $anexos, 'arrayDiasValores' => $arrayDiasValores, 'medicoesFiltradas' => $medicoesFiltradas]);
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

        if(count($av->rotas) >0){
            $arrayDiasValores = $this->geraArrayDiasValoresCerto($av);
        }
        else{
            $arrayDiasValores = [];
        }

        //código para obter as reservas do usuário da AV -------------------------------------------------------------------------
        $eventos = [];
        $reservas2 = [];
        $veiculos = [];

        $url = 'http://10.51.10.43/reservas/public/api/getVeiculosAPI';
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $veiculos = json_decode(curl_exec($ch));
        //crie uma coleção de $veiculos
        $veiculos = collect($veiculos);

        $url = 'http://10.51.10.43/reservas/public/api/getTodasReservasAPI';
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $reservas2 = json_decode(curl_exec($ch));
        //crie uma coleção de $reservas2
        $reservas2 = collect($reservas2);

        if(count($reservas2) > 0){
            //filtre as reservas de acordo com o $av->idReservaVeiculo e a coluna a ser filtrada é id de reserva
            $reservas2 = $reservas2->filter(function ($reserva) use ($av) {
                if($reserva->id == $av->idReservaVeiculo){
                    return true;
                }
                else{
                    return false;
                }
            });
        }

        if(count($veiculos) > 0){
            //verifique qual é o veículo da reserva pela coluna idVeiculo de $reservas2 e adicione uma nova coluna chamada veiculo
            $reservas2 = $reservas2->map(function ($reserva) use ($veiculos) {
                $veiculo = $veiculos->where('id', $reserva->idVeiculo)->first();
                $reserva->veiculo = $veiculo;
                return $reserva;
            });
        }
        //------------------------------------------------------------------------------------------------------------------------

        if($possoEditar == true){
            return view('avs.verFluxoSecretaria', ['av' => $av, 'objetivos' => $objetivos, 'veiculosProprios' => $veiculosProprios, 
            'user'=> $user, 'historicos'=> $historicos, 'users'=> $users, 'userAv' => $userAv, 'veiculosParanacidade' => $veiculosParanacidade,
            'medicoesFiltradas' => $medicoesFiltradas, 'arrayDiasValores' => $arrayDiasValores,
            'reservas2' => $reservas2], ['anexosRotas' => $anexosRotas]);
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

        $todosAnexosRotas = AnexoRota::all();

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

        foreach($todosAnexosRotas as $r){//Verifica todas as rotas da AV
            if($r->av_id == $id){// Verifica cada um dos anexos da rota
                array_push($anexosRotas, $r);// Empilha no array cada um dos anexos
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

        //código para obter as reservas do usuário da AV -------------------------------------------------------------------------
        $eventos = [];
        $reservas2 = [];
        $veiculos = [];

        $url = 'http://10.51.10.43/reservas/public/api/getVeiculosAPI';
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $veiculos = json_decode(curl_exec($ch));
        //crie uma coleção de $veiculos
        $veiculos = collect($veiculos);

        $url = 'http://10.51.10.43/reservas/public/api/getTodasReservasAPI';
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $reservas2 = json_decode(curl_exec($ch));
        //crie uma coleção de $reservas2
        $reservas2 = collect($reservas2);

        if(count($reservas2) > 0){
            //filtre as reservas de acordo com o $av->idReservaVeiculo e a coluna a ser filtrada é id de reserva
            $reservas2 = $reservas2->filter(function ($reserva) use ($av) {
                if($reserva->id == $av->idReservaVeiculo){
                    return true;
                }
                else{
                    return false;
                }
            });
        }

        if(count($veiculos) > 0){
            //verifique qual é o veículo da reserva pela coluna idVeiculo de $reservas2 e adicione uma nova coluna chamada veiculo
            $reservas2 = $reservas2->map(function ($reserva) use ($veiculos) {
                $veiculo = $veiculos->where('id', $reserva->idVeiculo)->first();
                $reserva->veiculo = $veiculo;
                return $reserva;
            });
        }
        //------------------------------------------------------------------------------------------------------------------------

        if(($av["isEnviadoUsuario"]==1 && $av["isAprovadoGestor"]==true && $av["isRealizadoReserva"]==true && $av["isAprovadoFinanceiro"]==true
        && $av["isPrestacaoContasRealizada"]==false && $av["isCancelado"]==false) || 
        ($av["isCancelado"]==true && $av["isAprovadoFinanceiro"]==true)){ //Se a av dele já foi enviada e autorizada pelo Gestor
                $possoEditar = true;
        }


        if($possoEditar == true){
            return view('avs.fazerPrestacaoContas', ['av' => $av, 'objetivos' => $objetivos, 'veiculosProprios' => $veiculosProprios, 
            'user'=> $user, 'historicos'=> $historicos, 'anexosRotas' => $anexosRotas, 'anexosFinanceiro' => $anexosFinanceiro, 
            'users'=> $users, 'userAv' => $userAv, 'historicoPc' => $historicoPc, 'comprovantes' => $comprovantes,
            'medicoesFiltradas' => $medicoesFiltradas, 'veiculosParanacidade' => $veiculosParanacidade, 'reservas2' => $reservas2]);
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

        $anexoRotasTodos = AnexoRota::all();

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
            if($hisPc->av_id == $av->id && $hisPc->comentario == "Documento AV"){
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
        //dd($valorRecebido);

        foreach($anexoRotasTodos as $r){//Verifica todas as rotas da AV
            if($r->av_id == $id){// Verifica cada um dos anexos da rota
                array_push($anexosRotas, $r);// Empilha no array cada um dos anexos
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

        //código para obter as reservas do usuário da AV -------------------------------------------------------------------------
        $reservas2 = [];
        $veiculos = [];

        $url = 'http://10.51.10.43/reservas/public/api/getVeiculosAPI';
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $veiculos = json_decode(curl_exec($ch));
        //crie uma coleção de $veiculos
        $veiculos = collect($veiculos);

        $url = 'http://10.51.10.43/reservas/public/api/getTodasReservasAPI';
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $reservas2 = json_decode(curl_exec($ch));
        //crie uma coleção de $reservas2
        $reservas2 = collect($reservas2);

        if(count($reservas2) > 0){
            //filtre as reservas de acordo com o $av->idReservaVeiculo e a coluna a ser filtrada é id de reserva
            $reservas2 = $reservas2->filter(function ($reserva) use ($av) {
                if($reserva->id == $av->idReservaVeiculo){
                    return true;
                }
                else{
                    return false;
                }
            });
        }

        if(count($veiculos) > 0){
            //verifique qual é o veículo da reserva pela coluna idVeiculo de $reservas2 e adicione uma nova coluna chamada veiculo
            $reservas2 = $reservas2->map(function ($reserva) use ($veiculos) {
                $veiculo = $veiculos->where('id', $reserva->idVeiculo)->first();
                $reserva->veiculo = $veiculo;
                return $reserva;
            });
        }
        //------------------------------------------------------------------------------------------------------------------------
        
        if(($av["isEnviadoUsuario"]==1 && $av["isAprovadoGestor"]==true && $av["isRealizadoReserva"]==true && $av["isAprovadoFinanceiro"]==true
                && $av["isPrestacaoContasRealizada"]==true && $av["isFinanceiroAprovouPC"]==false && $av["isCancelado"]==false) || 
                ($av["isCancelado"]==true && $av["isAprovadoFinanceiro"]==true && $av["isPrestacaoContasRealizada"] == true 
                && $av["isFinanceiroAprovouPC"] == false)){ //Se a av dele já foi enviada e autorizada pelo Gestor
            $possoEditar = true;
        }
        
        
        if($possoEditar == true){
            // dd($av, $objetivos, $veiculosProprios, $user, $historicos, $anexosRotas, $anexosFinanceiro, $users, $userAv, $historicoPc, $comprovantes,
            // $medicoesFiltradas, $valorRecebido, $valorAcertoContasReal, $valorAcertoContasDolar, $veiculosParanacidade, $reservas2);
            
            return view('avs.avaliarPcFinanceiro', ['av' => $av, 'objetivos' => $objetivos, 'veiculosProprios' => $veiculosProprios, 
            'user'=> $user, 'historicos'=> $historicos, 'anexosRotas' => $anexosRotas, 'anexosFinanceiro' => $anexosFinanceiro, 
            'users'=> $users, 'userAv' => $userAv, 'historicoPc' => $historicoPc, 'comprovantes' => $comprovantes,
            'medicoesFiltradas' => $medicoesFiltradas, 'valorRecebido' => $valorRecebido, 'valorAcertoContasReal' => $valorAcertoContasReal,
            'valorAcertoContasDolar' => $valorAcertoContasDolar, 'veiculosParanacidade' => $veiculosParanacidade, 'reservas2' => $reservas2]);
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

        $anexoRotasTodos = AnexoRota::all();

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
            if($hisPc->av_id == $av->id && $hisPc->comentario == "Documento AV"){
                $valorRecebido = $hisPc;
            }
        }

        foreach($anexoRotasTodos as $r){//Verifica todas as rotas da AV
            if($r->av_id == $id){// Verifica cada um dos anexos da rota
                array_push($anexosRotas, $r);// Empilha no array cada um dos anexos
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

        //código para obter as reservas do usuário da AV -------------------------------------------------------------------------
        $reservas2 = [];
        $veiculos = [];

        $url = 'http://10.51.10.43/reservas/public/api/getVeiculosAPI';
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $veiculos = json_decode(curl_exec($ch));
        //crie uma coleção de $veiculos
        $veiculos = collect($veiculos);

        $url = 'http://10.51.10.43/reservas/public/api/getTodasReservasAPI';
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $reservas2 = json_decode(curl_exec($ch));
        //crie uma coleção de $reservas2
        $reservas2 = collect($reservas2);

        if(count($reservas2) > 0){
            //filtre as reservas de acordo com o $av->idReservaVeiculo e a coluna a ser filtrada é id de reserva
            $reservas2 = $reservas2->filter(function ($reserva) use ($av) {
                if($reserva->id == $av->idReservaVeiculo){
                    return true;
                }
                else{
                    return false;
                }
            });
        }

        if(count($veiculos) > 0){
            //verifique qual é o veículo da reserva pela coluna idVeiculo de $reservas2 e adicione uma nova coluna chamada veiculo
            $reservas2 = $reservas2->map(function ($reserva) use ($veiculos) {
                $veiculo = $veiculos->where('id', $reserva->idVeiculo)->first();
                $reserva->veiculo = $veiculo;
                return $reserva;
            });
        }
        //------------------------------------------------------------------------------------------------------------------------
        
        if(($av["isEnviadoUsuario"]==1 && $av["isAprovadoGestor"]==true && $av["isRealizadoReserva"]==true && $av["isAprovadoFinanceiro"]==true
                && $av["isPrestacaoContasRealizada"]==true && $av["isFinanceiroAprovouPC"]==true 
                && $av["isGestorAprovouPC"]==true&& $av["isAcertoContasRealizado"]==false && $av["isCancelado"]==false) || 
                ($av["isCancelado"]==true && $av["isAprovadoFinanceiro"]==true && $av["isPrestacaoContasRealizada"] == true 
                && $av["isFinanceiroAprovouPC"] == true && $av["isGestorAprovouPC"] == true && $av["isAcertoContasRealizado"] == false)){ //Se a av dele já foi enviada e autorizada pelo Gestor
            $possoEditar = true;
        }

        if($possoEditar == true){
            return view('avs.realizarAcertoContasFinanceiro', ['av' => $av, 'objetivos' => $objetivos, 'veiculosProprios' => $veiculosProprios, 
            'user'=> $user, 'historicos'=> $historicos, 'anexosRotas' => $anexosRotas, 'anexosFinanceiro' => $anexosFinanceiro, 
            'users'=> $users, 'userAv' => $userAv, 'historicoPc' => $historicoPc, 'comprovantes' => $comprovantes, 'valorRecebido' => $valorRecebido,
            'valorAcertoContasReal'=>$valorAcertoContasReal, 'valorAcertoContasDolar'=>$valorAcertoContasDolar, 'medicoesFiltradas' => $medicoesFiltradas, 
            'veiculosParanacidade' => $veiculosParanacidade, 'reservas2' => $reservas2]);
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

        $anexosRotasTodos = AnexoRota::all();

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
            if($hisPc->av_id == $av->id && $hisPc->comentario == "Documento AV"){
                $valorRecebido = $hisPc;
            }
        }

        foreach($anexosRotasTodos as $r){//Verifica todas as rotas da AV
            if($r->av_id == $id){// Verifica cada um dos anexos da rota
                array_push($anexosRotas, $r);// Empilha no array cada um dos anexos
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

        //código para obter as reservas do usuário da AV -------------------------------------------------------------------------
        $reservas2 = [];
        $veiculos = [];

        $url = 'http://10.51.10.43/reservas/public/api/getVeiculosAPI';
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $veiculos = json_decode(curl_exec($ch));
        //crie uma coleção de $veiculos
        $veiculos = collect($veiculos);

        $url = 'http://10.51.10.43/reservas/public/api/getTodasReservasAPI';
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $reservas2 = json_decode(curl_exec($ch));
        //crie uma coleção de $reservas2
        $reservas2 = collect($reservas2);

        if(count($reservas2) > 0){
            //filtre as reservas de acordo com o $av->idReservaVeiculo e a coluna a ser filtrada é id de reserva
            $reservas2 = $reservas2->filter(function ($reserva) use ($av) {
                if($reserva->id == $av->idReservaVeiculo){
                    return true;
                }
                else{
                    return false;
                }
            });
        }

        if(count($veiculos) > 0){
            //verifique qual é o veículo da reserva pela coluna idVeiculo de $reservas2 e adicione uma nova coluna chamada veiculo
            $reservas2 = $reservas2->map(function ($reserva) use ($veiculos) {
                $veiculo = $veiculos->where('id', $reserva->idVeiculo)->first();
                $reserva->veiculo = $veiculo;
                return $reserva;
            });
        }
        //------------------------------------------------------------------------------------------------------------------------

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
            'veiculosParanacidade' => $veiculosParanacidade, 'reservas2' => $reservas2]);
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

        $anexoRotasTodos = AnexoRota::all();

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
            if($hisPc->av_id == $av->id && $hisPc->comentario == "Documento AV"){
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

        foreach($anexoRotasTodos as $r){//Verifica todas as rotas da AV
            if($r->av_id == $id){// Verifica cada um dos anexos da rota
                array_push($anexosRotas, $r);// Empilha no array cada um dos anexos
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

        //código para obter as reservas do usuário da AV -------------------------------------------------------------------------
        $reservas2 = [];
        $veiculos = [];

        $url = 'http://10.51.10.43/reservas/public/api/getVeiculosAPI';
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $veiculos = json_decode(curl_exec($ch));
        //crie uma coleção de $veiculos
        $veiculos = collect($veiculos);

        $url = 'http://10.51.10.43/reservas/public/api/getTodasReservasAPI';
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $reservas2 = json_decode(curl_exec($ch));
        //crie uma coleção de $reservas2
        $reservas2 = collect($reservas2);

        if(count($reservas2) > 0){
            //filtre as reservas de acordo com o $av->idReservaVeiculo e a coluna a ser filtrada é id de reserva
            $reservas2 = $reservas2->filter(function ($reserva) use ($av) {
                if($reserva->id == $av->idReservaVeiculo){
                    return true;
                }
                else{
                    return false;
                }
            });
        }

        if(count($veiculos) > 0){
            //verifique qual é o veículo da reserva pela coluna idVeiculo de $reservas2 e adicione uma nova coluna chamada veiculo
            $reservas2 = $reservas2->map(function ($reserva) use ($veiculos) {
                $veiculo = $veiculos->where('id', $reserva->idVeiculo)->first();
                $reserva->veiculo = $veiculo;
                return $reserva;
            });
        }
        //------------------------------------------------------------------------------------------------------------------------

        if($userAv->id != $user->id){//Se  o usuário não for você
            if(($av["isEnviadoUsuario"]==1 && $av["isAprovadoGestor"]==true && $av["isRealizadoReserva"]==true && $av["isAprovadoFinanceiro"]==true
                && $av["isPrestacaoContasRealizada"]==true && $av["isFinanceiroAprovouPC"]==true  && $av["isGestorAprovouPC"]==false
                && $av["isCancelado"]==false) || 
                ($av["isCancelado"]==true && $av["isAprovadoFinanceiro"]==true && $av["isPrestacaoContasRealizada"] == true 
                && $av["isFinanceiroAprovouPC"] == true && $av["isGestorAprovouPC"] == false)){ //Se a av dele já foi enviada e autorizada pelo Gestor
                $possoEditar = true;
            }
        }
        
        $managerDN = $userAv->manager; // CN=Leandro Victorino Moura,OU=CTI,OU=Empregados,DC=prcidade,DC=br

        // Dividir a string em partes usando o caractere de vírgula como delimitador
        $parts = explode(',', $managerDN);

        // Extrair o nome do gerente da primeira parte
        $managerName = substr($parts[0], 3); // Remover os primeiros 3 caracteres "CN="

        if($managerName == $userAv->name){
            $possoEditar = true;
        }

        if($possoEditar == true){
            return view('avs.avaliarPcGestor', ['av' => $av, 'objetivos' => $objetivos, 'veiculosProprios' => $veiculosProprios, 
            'user'=> $user, 'historicos'=> $historicos, 'anexosRotas' => $anexosRotas, 'anexosFinanceiro' => $anexosFinanceiro, 
            'users'=> $users, 'userAv' => $userAv, 'historicoPc' => $historicoPc, 'comprovantes' => $comprovantes,
            'medicoesFiltradas' => $medicoesFiltradas, 'valorRecebido' => $valorRecebido, 'valorAcertoContasReal' => $valorAcertoContasReal,
            'valorAcertoContasDolar' => $valorAcertoContasDolar, 'veiculosParanacidade' => $veiculosParanacidade, 'reservas2' => $reservas2]);
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
        $isViagemInternacional = $av->isAprovadoViagemInternacional;

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
            
                $possoEditar = true;
            
        }

        if(count($av->rotas) > 0){
            $arrayDiasValores = $this->geraArrayDiasValoresCerto($av);
        }
        else{
            $arrayDiasValores = [];
        }

        //código para obter as reservas do usuário da AV -------------------------------------------------------------------------
        $eventos = [];
        $reservas2 = [];
        $veiculos = [];

        $url = 'http://10.51.10.43/reservas/public/api/getVeiculosAPI';
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $veiculos = json_decode(curl_exec($ch));
        //crie uma coleção de $veiculos
        $veiculos = collect($veiculos);

        $url = 'http://10.51.10.43/reservas/public/api/getTodasReservasAPI';
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $reservas2 = json_decode(curl_exec($ch));
        //crie uma coleção de $reservas2
        $reservas2 = collect($reservas2);

        if(count($reservas2) > 0){
            //filtre as reservas de acordo com o $av->idReservaVeiculo e a coluna a ser filtrada é id de reserva
            $reservas2 = $reservas2->filter(function ($reserva) use ($av) {
                if($reserva->id == $av->idReservaVeiculo){
                    return true;
                }
                else{
                    return false;
                }
            });
        }

        if(count($veiculos) > 0){
            //verifique qual é o veículo da reserva pela coluna idVeiculo de $reservas2 e adicione uma nova coluna chamada veiculo
            $reservas2 = $reservas2->map(function ($reserva) use ($veiculos) {
                $veiculo = $veiculos->where('id', $reserva->idVeiculo)->first();
                $reserva->veiculo = $veiculo;
                return $reserva;
            });
        }
        //------------------------------------------------------------------------------------------------------------------------
        
        if($possoEditar == true){
            return view('avs.verFluxoFinanceiro', ['av' => $av, 'objetivos' => $objetivos, 'veiculosProprios' => $veiculosProprios, 'user'=> $user, 
            'historicos'=> $historicos, 'anexos' => $anexos, 'users'=> $users, 'userAv' => $userAv, 'veiculosParanacidade' => $veiculosParanacidade,
            'medicoesFiltradas' => $medicoesFiltradas, 'arrayDiasValores' => $arrayDiasValores, 'reservas2' => $reservas2, 'isViagemInternacional' => $isViagemInternacional]);
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
            $anexoRota->av_id = $av->id;
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
            $anexoRota->av_id = $av->id;
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

    public function deletarComprovanteDevolucaoUsuario($id, $avId)
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
        return redirect('/avs/verPaginaDevolucaoPc/' . $av->id)->with('msg', 'Comprovante excluído com sucesso!');
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

        $valorReaisFiltrado = 0;
        if($request->valorReais != null){
            $valorReaisFiltrado = str_replace(',', '.', $request->valorReais);
            $valorReaisFiltrado = str_replace('R$', '', $valorReaisFiltrado);
            $valorReaisFiltrado = str_replace(' ', '', $valorReaisFiltrado);
        }

        $valorDolarFiltrado = 0;
        if($request->valorDolar != null){
            $valorDolarFiltrado = str_replace(',', '.', $request->valorDolar);
            $valorDolarFiltrado = str_replace('US$', '', $valorDolarFiltrado);
            $valorDolarFiltrado = str_replace(' ', '', $valorDolarFiltrado);
        }
        
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
            
            if($av->isCancelado == true){
                $comprovante->descricao = "Cancelamento de viagem";
            }
            else{
                $comprovante->descricao = $request->descricao;
            }

            if($request->valorReais != null){
                $comprovante->valorReais = $valorReaisFiltrado;
            }

            if($request->valorDolar != null){
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

    public function gravarComprovanteDevolucaoUsuario(Request $request){
        
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
            $historicoPc->ocorrencia = "Usuário realizou devolução de valores";
            $historicoPc->comentario = "Comprovante Devolução Usuário";
            $timezone = new DateTimeZone('America/Sao_Paulo');
            $historicoPc->dataOcorrencia = new DateTime('now', $timezone);
            $historicoPc->save();
        }

        return redirect('/avs/verPaginaDevolucaoPc/' . $av->id)->with('msg', 'Comprovante salvo com sucesso!');
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

        $todosAnexosRotas = AnexoRota::all();

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
            if($hisPc->av_id == $av->id && $hisPc->comentario == "Documento AV"){
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

        foreach($todosAnexosRotas as $r){//Verifica todas as rotas da AV
            if($r->av_id == $id){// Verifica cada um dos anexos da rota
                array_push($anexosRotas, $r);// Empilha no array cada um dos anexos
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

        //código para obter as reservas do usuário da AV -------------------------------------------------------------------------
        $eventos = [];
        $reservas2 = [];
        $veiculos = [];

        $url = 'http://10.51.10.43/reservas/public/api/getVeiculosAPI';
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $veiculos = json_decode(curl_exec($ch));
        //crie uma coleção de $veiculos
        $veiculos = collect($veiculos);

        $url = 'http://10.51.10.43/reservas/public/api/getTodasReservasAPI';
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $reservas2 = json_decode(curl_exec($ch));
        //crie uma coleção de $reservas2
        $reservas2 = collect($reservas2);

        if(count($reservas2) > 0){
            //filtre as reservas de acordo com o $av->idReservaVeiculo e a coluna a ser filtrada é id de reserva
            $reservas2 = $reservas2->filter(function ($reserva) use ($av) {
                if($reserva->id == $av->idReservaVeiculo){
                    return true;
                }
                else{
                    return false;
                }
            });
        }

        if(count($veiculos) > 0){
            //verifique qual é o veículo da reserva pela coluna idVeiculo de $reservas2 e adicione uma nova coluna chamada veiculo
            $reservas2 = $reservas2->map(function ($reserva) use ($veiculos) {
                $veiculo = $veiculos->where('id', $reserva->idVeiculo)->first();
                $reserva->veiculo = $veiculo;
                return $reserva;
            });
        }
        //------------------------------------------------------------------------------------------------------------------------

        return view('avs.verDetalhesAv', ['av' => $av, 'objetivos' => $objetivos, 'veiculosProprios' => $veiculosProprios, 
        'user'=> $user, 'historicos'=> $historicos, 'anexosRotas' => $anexosRotas, 'anexosFinanceiro' => $anexosFinanceiro, 
        'users'=> $users, 'userAv' => $userAv, 'historicoPc' => $historicoPc, 'comprovantes' => $comprovantes, 'valorRecebido' => $valorRecebido,
        'valorAcertoContasReal'=>$valorAcertoContasReal, 'valorAcertoContasDolar'=>$valorAcertoContasDolar, 'veiculosParanacidade' => $veiculosParanacidade,
        'isInternacional' => $isInternacional, 'medicoesFiltradas' => $medicoesFiltradas, 'reservas2' => $reservas2]);
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

        $todosAnexosRotas = AnexoRota::all();

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
            if($hisPc->av_id == $av->id && $hisPc->comentario == "Documento AV"){
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

        foreach($todosAnexosRotas as $r){//Verifica todas as rotas da AV
            if($r->av_id == $id){// Verifica cada um dos anexos da rota
                array_push($anexosRotas, $r);// Empilha no array cada um dos anexos
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

    public function verPaginaDevolucaoPc($id){

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

        $todosAnexosRotas = AnexoRota::all();

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
            if($hisPc->av_id == $av->id && $hisPc->comentario == "Documento AV"){
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

        foreach($todosAnexosRotas as $r){//Verifica todas as rotas da AV
            if($r->av_id == $id){// Verifica cada um dos anexos da rota
                array_push($anexosRotas, $r);// Empilha no array cada um dos anexos
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

        //código para obter as reservas do usuário da AV -------------------------------------------------------------------------
        $reservas2 = [];
        $veiculos = [];

        $url = 'http://10.51.10.43/reservas/public/api/getVeiculosAPI';
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $veiculos = json_decode(curl_exec($ch));
        //crie uma coleção de $veiculos
        $veiculos = collect($veiculos);

        $url = 'http://10.51.10.43/reservas/public/api/getTodasReservasAPI';
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $reservas2 = json_decode(curl_exec($ch));
        //crie uma coleção de $reservas2
        $reservas2 = collect($reservas2);

        if(count($reservas2) > 0){
            //filtre as reservas de acordo com o $av->idReservaVeiculo e a coluna a ser filtrada é id de reserva
            $reservas2 = $reservas2->filter(function ($reserva) use ($av) {
                if($reserva->id == $av->idReservaVeiculo){
                    return true;
                }
                else{
                    return false;
                }
            });
        }

        if(count($veiculos) > 0){
            //verifique qual é o veículo da reserva pela coluna idVeiculo de $reservas2 e adicione uma nova coluna chamada veiculo
            $reservas2 = $reservas2->map(function ($reserva) use ($veiculos) {
                $veiculo = $veiculos->where('id', $reserva->idVeiculo)->first();
                $reserva->veiculo = $veiculo;
                return $reserva;
            });
        }
        //------------------------------------------------------------------------------------------------------------------------

        return view('avs.verPaginaDevolucaoPc', ['av' => $av, 'objetivos' => $objetivos, 'veiculosProprios' => $veiculosProprios, 
        'user'=> $user, 'historicos'=> $historicos, 'anexosRotas' => $anexosRotas, 'anexosFinanceiro' => $anexosFinanceiro, 
        'users'=> $users, 'userAv' => $userAv, 'historicoPc' => $historicoPc, 'comprovantes' => $comprovantes, 'valorRecebido' => $valorRecebido,
        'valorAcertoContasReal'=>$valorAcertoContasReal, 'valorAcertoContasDolar'=>$valorAcertoContasDolar, 'veiculosParanacidade' => $veiculosParanacidade,
        'isInternacional' => $isInternacional, 'medicoesFiltradas' => $medicoesFiltradas, 'reservas2' => $reservas2]);
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

        $todosAnexosRotas = AnexoRota::all();

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
            if($hisPc->av_id == $av->id && $hisPc->comentario == "Documento AV"){
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

        foreach($todosAnexosRotas as $r){//Verifica todas as rotas da AV
            if($r->av_id == $id){// Verifica cada um dos anexos da rota
                array_push($anexosRotas, $r);// Empilha no array cada um dos anexos
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

        if(count($av->rotas) > 0){
            $arrayDiasValores = $this->geraArrayDiasValoresCerto($av);
        }
        else{
            $arrayDiasValores = [];
        }

        //código para obter as reservas do usuário da AV -------------------------------------------------------------------------
        $eventos = [];
        $reservas2 = [];
        $veiculos = [];

        $url = 'http://10.51.10.43/reservas/public/api/getVeiculosAPI';
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $veiculos = json_decode(curl_exec($ch));
        //crie uma coleção de $veiculos
        $veiculos = collect($veiculos);

        $url = 'http://10.51.10.43/reservas/public/api/getTodasReservasAPI';
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $reservas2 = json_decode(curl_exec($ch));
        //crie uma coleção de $reservas2
        $reservas2 = collect($reservas2);

        if(count($reservas2) > 0){
            //filtre as reservas de acordo com o $av->idReservaVeiculo e a coluna a ser filtrada é id de reserva
            $reservas2 = $reservas2->filter(function ($reserva) use ($av) {
                if($reserva->id == $av->idReservaVeiculo){
                    return true;
                }
                else{
                    return false;
                }
            });
        }

        if(count($veiculos) > 0){
            //verifique qual é o veículo da reserva pela coluna idVeiculo de $reservas2 e adicione uma nova coluna chamada veiculo
            $reservas2 = $reservas2->map(function ($reserva) use ($veiculos) {
                $veiculo = $veiculos->where('id', $reserva->idVeiculo)->first();
                $reserva->veiculo = $veiculo;
                return $reserva;
            });
        }
        //------------------------------------------------------------------------------------------------------------------------
        return view('avs.verDetalhesAvGerenciar', ['av' => $av, 'objetivos' => $objetivos, 'veiculosProprios' => $veiculosProprios, 
        'user'=> $user, 'historicos'=> $historicos, 'anexosRotas' => $anexosRotas, 'anexosFinanceiro' => $anexosFinanceiro, 
        'users'=> $users, 'userAv' => $userAv, 'historicoPc' => $historicoPc, 'comprovantes' => $comprovantes, 'valorRecebido' => $valorRecebido,
        'valorAcertoContasReal'=>$valorAcertoContasReal, 'valorAcertoContasDolar'=>$valorAcertoContasDolar, 
        'veiculosParanacidade' => $veiculosParanacidade, 'medicoesFiltradas' => $medicoesFiltradas, 
        'arrayDiasValores' => $arrayDiasValores, 'reservas2' => $reservas2]);
    }

    public function verDetalhesAvGerenciarRh($id){

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
        //se a AV não tiver rotas, redireciona para a página de gerenciar AVs com a mensagem de erro
        if(count($av->rotas) == 0){
            return redirect('/avs/gerenciarAvsRh')->with('msg', 'A AV não possui rotas cadastradas!');
        }

        $userAv = User::findOrFail($av->user_id);
        $historicoPcAll = HistoricoPc::all();
        $historicoPc = [];
        $valorRecebido = null;

        $medicoes = Medicao::all();
        $medicoesFiltradas = [];

        $todosAnexosRotas = AnexoRota::all();

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
            if($hisPc->av_id == $av->id && $hisPc->comentario == "Documento AV"){
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

        foreach($todosAnexosRotas as $r){//Verifica todas as rotas da AV
            if($r->av_id == $id){// Verifica cada um dos anexos da rota
                array_push($anexosRotas, $r);// Empilha no array cada um dos anexos
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

        if(count($av->rotas) > 0){
            $arrayDiasValores = $this->geraArrayDiasValoresCerto($av);
        }
        else{
            $arrayDiasValores = [];
        }

        //código para obter as reservas do usuário da AV -------------------------------------------------------------------------
        $eventos = [];
        $reservas2 = [];
        $veiculos = [];

        $url = 'http://10.51.10.43/reservas/public/api/getVeiculosAPI';
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $veiculos = json_decode(curl_exec($ch));
        //crie uma coleção de $veiculos
        $veiculos = collect($veiculos);

        $url = 'http://10.51.10.43/reservas/public/api/getTodasReservasAPI';
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $reservas2 = json_decode(curl_exec($ch));
        //crie uma coleção de $reservas2
        $reservas2 = collect($reservas2);

        if(count($reservas2) > 0){
            //filtre as reservas de acordo com o $av->idReservaVeiculo e a coluna a ser filtrada é id de reserva
            $reservas2 = $reservas2->filter(function ($reserva) use ($av) {
                if($reserva->id == $av->idReservaVeiculo){
                    return true;
                }
                else{
                    return false;
                }
            });
        }

        if(count($veiculos) > 0){
            //verifique qual é o veículo da reserva pela coluna idVeiculo de $reservas2 e adicione uma nova coluna chamada veiculo
            $reservas2 = $reservas2->map(function ($reserva) use ($veiculos) {
                $veiculo = $veiculos->where('id', $reserva->idVeiculo)->first();
                $reserva->veiculo = $veiculo;
                return $reserva;
            });
        }
        //------------------------------------------------------------------------------------------------------------------------

        return view('avs.verDetalhesAvGerenciarRh', ['av' => $av, 'objetivos' => $objetivos, 'veiculosProprios' => $veiculosProprios, 
        'user'=> $user, 'historicos'=> $historicos, 'anexosRotas' => $anexosRotas, 'anexosFinanceiro' => $anexosFinanceiro, 
        'users'=> $users, 'userAv' => $userAv, 'historicoPc' => $historicoPc, 'comprovantes' => $comprovantes, 'valorRecebido' => $valorRecebido,
        'valorAcertoContasReal'=>$valorAcertoContasReal, 'valorAcertoContasDolar'=>$valorAcertoContasDolar, 
        'veiculosParanacidade' => $veiculosParanacidade, 'medicoesFiltradas' => $medicoesFiltradas, 
        'arrayDiasValores' => $arrayDiasValores, 'reservas2' => $reservas2]);
    }

    public function gestorAprovarAv(Request $request){

        $user = auth()->user();
        $av = Av::findOrFail($request->get('id'));
        $userAv = User::findOrFail($av->user_id);

        $isVeiculoProprio = false;
        $isInternacional = false;
        $temReservaHotelOuTransporte = false;
        $temReservaVeiculoEmpresa = false;
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
        $historico->save();

        foreach($av->rotas as $rota){
            if($rota["isVeiculoProprio"]==1){
                $isVeiculoProprio = true;
            }
            if($rota["isViagemInternacional"]==1){
                $isInternacional = true;
            }
            if($rota["isReservaHotel"]==1){
                $temReservaHotelOuTransporte = true;
            }
            if($rota["isOnibusLeito"]==1){
                $temReservaHotelOuTransporte = true;
            }
            if($rota["isOnibusConvencional"]==1){
                $temReservaHotelOuTransporte = true;
            }
            if($rota["isAereo"]==1){
                $temReservaHotelOuTransporte = true;
            }
            if($rota["isVeiculoEmpresa"]==1){
                $temReservaVeiculoEmpresa = true;
            }
        }

        if($isInternacional==true){
            $dados = array(
                "isAprovadoGestor" => 1,
                "status" => "Aguardando aprovação da DAF"
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

        //QUANDO O FLUXO DA AV INTERNACIONAL IA SOMENTE ATÉ A SUA GERAÇÃO
        // if($isInternacional == true){
        //     //Gera o PDF
        //     $avs = Av::all();
        //     $av = Av::findOrFail($request->get('id'));
        //     $userAv = User::findOrFail($av->user_id);
        //     $objetivos = Objetivo::all();
        //     $historicosTodos = Historico::all();
        //     $historicos = [];
        //     $users = User::all();
        //     $valorRecebido = $av;
        //     $valorReais = 0;
        //     $valorAcertoContasReal = 0;
        //     $valorAcertoContasDolar = 0;

        //     foreach($historicosTodos as $historico){
        //         if($historico->av_id == $av->id){
        //             array_push($historicos, $historico);
        //         }
        //     }

        //     $paises = Country::all();

        //     $options = new Options();
        //     $options->set('defaultFont', 'sans-serif');
        //     $dompdf = new Dompdf($options);

        //     $dompdf->loadHtml(view('relatorioViagemInternacional', compact('avs', 'av', 'objetivos', 'historicos', 'users', 'userAv', 
        //     'valorRecebido', 'valorReais', 'valorAcertoContasReal', 'valorAcertoContasDolar', 'paises')));
        //     $dompdf->render();

        //     $nomeArquivo = md5("relatorio" . strtotime("now")) . ".pdf";
        //     $caminhoDiretorio = '/mnt/arquivos_viagem/AVs/' . $userAv->name . '/' . $av->id . '/internacional' . '/';
        //     $caminhoArquivo = $caminhoDiretorio . $nomeArquivo;
        //     if (!file_exists($caminhoDiretorio)) {
        //         mkdir($caminhoDiretorio, 0777, true);
        //     }
        //     file_put_contents($caminhoArquivo, $dompdf->output());

        //     //Salva no HistoricoPC
        //     $historicoPc = new HistoricoPc();
        //     $historicoPc->valorReais = $av->valorReais;
        //     $historicoPc->valorDolar = $av->valorDolar;
        //     $historicoPc->valorExtraReais = $av->valorExtraReais;
        //     $historicoPc->valorExtraDolar = $av->valorExtraDolar;
        //     $historicoPc->ocorrencia ="Gestor aprovou AV internacional";
        //     $historicoPc->comentario ="AV Internacional gerada";
        //     $historicoPc->av_id = $av->id;

        //     $historicoPc->dataOcorrencia = new DateTime('now', $timezone);
        //     $historicoPc->anexoRelatorio = $nomeArquivo;
        //     $historicoPc->save();
        // }

        Av::findOrFail($av->id)->update($dados);
        
        $permissionDir = Permission::where('name', 'aprov avs diretoria')->first();
        $permission2 = Permission::where('name', 'aprov avs secretaria')->first();
        $permission3 = Permission::where('name', 'aprov avs financeiro')->first();

        $existeResponsavelFinanceiroCascavel = false;
        $existeResponsavelFinanceiroMaringa = false;
        $existeResponsavelFinanceiroFrancisco = false;
        $existeResponsavelFinanceiroGuarapuava = false;
        $existeResponsavelFinanceiroLondrina = false;
        $existeResponsavelFinanceiroPontaGrossa = false;
        
        $users = User::all();
        foreach($users as $uf){
            if($uf->department == "ERCSC"){
                try {
                    if($uf->hasPermissionTo($permission3)){
                        $existeResponsavelFinanceiroCascavel = true;
                        $existeResponsavelFinanceiroFrancisco = true;
                    }
                } catch (\Throwable $th) {
                }
            }
            else if($uf->department == "ERMGA"){
                try {
                    if($uf->hasPermissionTo($permission3)){
                        $existeResponsavelFinanceiroMaringa = true;
                    }
                } catch (\Throwable $th) {
                }
            }
            else if($uf->department == "ERGUA"){
                try {
                    if($uf->hasPermissionTo($permission3)){
                        $existeResponsavelFinanceiroGuarapuava = true;
                    }
                } catch (\Throwable $th) {
                }
            }
            else if($uf->department == "ERLDA"){
                try {
                    if($uf->hasPermissionTo($permission3)){
                        $existeResponsavelFinanceiroLondrina = true;
                    }
                } catch (\Throwable $th) {
                }
            }
            else if($uf->department == "ERPTG"){
                try {
                    if($uf->hasPermissionTo($permission3)){
                        $existeResponsavelFinanceiroPontaGrossa = true;
                    }
                } catch (\Throwable $th) {
                }
            }
        }
        
        //QUANDO O FLUXO DA AV INTERNACIONAL IA SOMENTE ATÉ A SUA GERAÇÃO
        // if($isInternacional==true){
            
        //     Mail::to($userAv->username)
        //                 ->send(new EnvioGestorToUsuarioViagemInternacional($userAv->id, $av->id));

        //     $users = User::all();
        //     foreach($users as $u){
        //         try {
        //             if($u->hasPermissionTo($permission2)){
        //                 if(
        //                 ($u->department != "ERCSC" 
        //                 && $u->department != "ERMGA" 
        //                 && $u->department != "ERFCB" 
        //                 && $u->department != "ERGUA" 
        //                 && $u->department != "ERLDA" 
        //                 && $u->department != "ERPTG")
        //                 ){
        //                     Mail::to($u->username)
        //                     ->send(new EnvioUsuarioToAdministrativoAvInternacionalNotificacao($userAv->id, $u->id, $av->id));
        //                 }
        //             }
        //             if($u->hasPermissionTo($permission3)){
        //                 if(
        //                 ($u->department != "ERCSC" 
        //                 && $u->department != "ERMGA" 
        //                 && $u->department != "ERFCB" 
        //                 && $u->department != "ERGUA" 
        //                 && $u->department != "ERLDA" 
        //                 && $u->department != "ERPTG")
        //                 ){
        //                     Mail::to($u->username)
        //                     ->send(new EnvioUsuarioToFinanceiroAvInternacionalNotificacao($userAv->id, $u->id, $av->id));
        //                 }
        //             }
        //         } catch (\Throwable $th) {
        //         }
        //     }
        // }

        if($isVeiculoProprio == true || $isInternacional==true){
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

                        if($temReservaVeiculoEmpresa && !$temReservaHotelOuTransporte){
                            if
                            (
                            ($u->department == "ERCSC" && $userAv->department == "ERCSC")
                            ||
                            ($u->department == "ERMGA" && $userAv->department == "ERMGA")
                            ||
                            ($u->department == "ERFCB" && $userAv->department == "ERFCB")
                            ||
                            ($u->department == "ERGUA" && $userAv->department == "ERGUA")
                            ||
                            ($u->department == "ERLDA" && $userAv->department == "ERLDA")
                            ||
                            ($u->department == "ERPTG" && $userAv->department == "ERPTG")
                            ){
                                Mail::to($u->username)
                                ->send(new EnvioGestorToSecretaria($av->user_id, $u->id, $av->id));
                            }
                            else if(
                            ($u->department != "ERCSC" 
                            && $u->department != "ERMGA" 
                            && $u->department != "ERFCB" 
                            && $u->department != "ERGUA" 
                            && $u->department != "ERLDA" 
                            && $u->department != "ERPTG")
                            &&
                            ($userAv->department != "ERCSC"
                            && $userAv->department != "ERMGA"
                            && $userAv->department != "ERFCB"
                            && $userAv->department != "ERGUA"
                            && $userAv->department != "ERLDA"
                            && $userAv->department != "ERPTG")
                            ){
                                Mail::to($u->username)
                                ->send(new EnvioGestorToSecretaria($av->user_id, $u->id, $av->id));
                            }
                        }
                        else if($temReservaHotelOuTransporte){
                            Mail::to($u->username)
                            ->send(new EnvioGestorToSecretaria($av->user_id, $u->id, $av->id));
                        }
                    }
                } catch (\Throwable $th) {
                }
            }
            foreach($users as $u2){
                try {
                    if($u2->hasPermissionTo($permission3)){
                        //verifique se u2 é da mesma regional que $userAv
                        if(
                        ($u2->department != "ERCSC" 
                        && $u2->department != "ERMGA" 
                        && $u2->department != "ERFCB" 
                        && $u2->department != "ERGUA" 
                        && $u2->department != "ERLDA" 
                        && $u2->department != "ERPTG")
                        &&
                        ($userAv->department != "ERCSC"
                        && $userAv->department != "ERMGA"
                        && $userAv->department != "ERFCB"
                        && $userAv->department != "ERGUA"
                        && $userAv->department != "ERLDA"
                        && $userAv->department != "ERPTG")
                        )
                        {
                            Mail::to($u2->username)
                            ->send(new EnvioGestorToFinanceiro($av->user_id, $u2->id, $av->id));
                        }
                        else if($u2->department != $userAv->department && $userAv->department == "ERFCB" && $u2->department == "ERCSC"){
                            Mail::to($u2->username)
                            ->send(new EnvioGestorToFinanceiro($av->user_id, $u2->id, $av->id));
                        }
                        else if
                        (
                        ($u2->department == "ERCSC" && $userAv->department == "ERCSC")
                        ||
                        ($u2->department == "ERMGA" && $userAv->department == "ERMGA")
                        ||
                        ($u2->department == "ERFCB" && $userAv->department == "ERFCB")
                        ||
                        ($u2->department == "ERGUA" && $userAv->department == "ERGUA")
                        ||
                        ($u2->department == "ERLDA" && $userAv->department == "ERLDA")
                        ||
                        ($u2->department == "ERPTG" && $userAv->department == "ERPTG")
                        )
                        {
                            Mail::to($u2->username)
                            ->send(new EnvioGestorToFinanceiro($av->user_id, $u2->id, $av->id));
                        }
                        else if(
                        (
                        $u2->department != "ERCSC" 
                        && $u2->department != "ERMGA" 
                        && $u2->department != "ERFCB" 
                        && $u2->department != "ERGUA" 
                        && $u2->department != "ERLDA" 
                        && $u2->department != "ERPTG")
                        &&
                        ($userAv->department == "ERCSC")
                        )
                        {
                            if(!$existeResponsavelFinanceiroCascavel){
                                Mail::to($u2->username)
                                ->send(new EnvioGestorToFinanceiro($av->user_id, $u2->id, $av->id));
                            }
                        }
                        else if(
                        (
                        $u2->department != "ERCSC"
                        && $u2->department != "ERMGA"
                        && $u2->department != "ERFCB"
                        && $u2->department != "ERGUA"
                        && $u2->department != "ERLDA"
                        && $u2->department != "ERPTG")
                        &&
                        ($userAv->department == "ERMGA")
                        )
                        {
                            if(!$existeResponsavelFinanceiroMaringa){
                                Mail::to($u2->username)
                                ->send(new EnvioGestorToFinanceiro($av->user_id, $u2->id, $av->id));
                            }
                        }
                        else if(
                        (
                        $u2->department != "ERCSC"
                        && $u2->department != "ERMGA"
                        && $u2->department != "ERFCB"
                        && $u2->department != "ERGUA"
                        && $u2->department != "ERLDA"
                        && $u2->department != "ERPTG")
                        &&
                        ($userAv->department == "ERGUA")
                        )
                        {
                            if(!$existeResponsavelFinanceiroGuarapuava){
                                Mail::to($u2->username)
                                ->send(new EnvioGestorToFinanceiro($av->user_id, $u2->id, $av->id));
                            }
                        }
                        else if(
                        (
                        $u2->department != "ERCSC"
                        && $u2->department != "ERMGA"
                        && $u2->department != "ERFCB"
                        && $u2->department != "ERGUA"
                        && $u2->department != "ERLDA"
                        && $u2->department != "ERPTG")
                        &&
                        ($userAv->department == "ERLDA")
                        )
                        {
                            if(!$existeResponsavelFinanceiroLondrina){
                                Mail::to($u2->username)
                                ->send(new EnvioGestorToFinanceiro($av->user_id, $u2->id, $av->id));
                            }
                        }
                        else if(
                        (
                        $u2->department != "ERCSC"
                        && $u2->department != "ERMGA"
                        && $u2->department != "ERFCB"
                        && $u2->department != "ERGUA"
                        && $u2->department != "ERLDA"
                        && $u2->department != "ERPTG")
                        &&
                        ($userAv->department == "ERPTG")
                        )
                        {
                            if(!$existeResponsavelFinanceiroPontaGrossa){
                                Mail::to($u2->username)
                                ->send(new EnvioGestorToFinanceiro($av->user_id, $u2->id, $av->id));
                            }
                        }
                        else if(
                        (
                        $u2->department != "ERCSC"
                        && $u2->department != "ERMGA"
                        && $u2->department != "ERFCB"
                        && $u2->department != "ERGUA"
                        && $u2->department != "ERLDA"
                        && $u2->department != "ERPTG")
                        &&
                        ($userAv->department == "ERFCB")
                        )
                        {
                            if(!$existeResponsavelFinanceiroCascavel){
                                Mail::to($u2->username)
                                ->send(new EnvioGestorToFinanceiro($av->user_id, $u2->id, $av->id));
                            }
                        }
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
                        ->send(new EnvioGestorToUsuarioReprovarAv($userAv->id, $av->id));

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
                    ->send(new EnvioSecretariaToUsuario($userAv->id, $av->id));
                
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
                        ->send(new EnvioSecretariaToUsuarioReprovarAv($userAv->id, $av->id));

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

        $historico->tipoOcorrencia = "Documento AV";
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

        if(count($av->rotas) > 0){
            $arrayDiasValores = $this->geraArrayDiasValoresCerto($av);
        }
        else{
            $arrayDiasValores = [];
        }

        $medicoes = Medicao::all();
        $medicoesFiltradas = [];

        foreach($medicoes as $medicao){
            if($medicao->av_id == $av->id){
                array_push($medicoesFiltradas, $medicao); 
            }
        }

        foreach($historicosTodos as $hist){
            if($hist->av_id == $av->id){
                array_push($historicos, $hist);
            }
        }

        $dataAtual = new DateTime('now', $timezone);
        //formate para o seguinte formato: 01/01/2021 00:00:00
        $dataFormatadaAtual = $dataAtual->format('d/m/Y H:i:s');

        //código para obter as reservas do usuário da AV -------------------------------------------------------------------------
        $reservas2 = [];
        $veiculos = [];

        $url = 'http://10.51.10.43/reservas/public/api/getVeiculosAPI';
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $veiculos = json_decode(curl_exec($ch));
        //crie uma coleção de $veiculos
        $veiculos = collect($veiculos);

        $url = 'http://10.51.10.43/reservas/public/api/getTodasReservasAPI';
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $reservas2 = json_decode(curl_exec($ch));
        //crie uma coleção de $reservas2
        $reservas2 = collect($reservas2);

        if(count($reservas2) > 0){
            //filtre as reservas de acordo com o $av->idReservaVeiculo e a coluna a ser filtrada é id de reserva
            $reservas2 = $reservas2->filter(function ($reserva) use ($av) {
                if($reserva->id == $av->idReservaVeiculo){
                    return true;
                }
                else{
                    return false;
                }
            });
        }

        if(count($veiculos) > 0){
            //verifique qual é o veículo da reserva pela coluna idVeiculo de $reservas2 e adicione uma nova coluna chamada veiculo
            $reservas2 = $reservas2->map(function ($reserva) use ($veiculos) {
                $veiculo = $veiculos->where('id', $reserva->idVeiculo)->first();
                $reserva->veiculo = $veiculo;
                return $reserva;
            });
        }
        //------------------------------------------------------------------------------------------------------------------------

        $options = new Options();
        $options->set('defaultFont', 'sans-serif');
        $dompdf = new Dompdf($options);

        $dompdf = new Dompdf();
        $dompdf->loadHtml(view('relatorio', compact('avs', 'av', 'objetivos', 'historicos', 'users', 'userAv', 'arrayDiasValores', 
        'isVeiculoEmpresa', 'medicoesFiltradas', 'dataFormatadaAtual', 'reservas2')));
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
        $historicoPc->comentario ="Documento AV";
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
                        ->send(new EnvioFinanceiroToUsuarioAdiantamento($userAv->id, $av->id));
    
        return redirect('/avs/autFinanceiro')->with('msg', 'AV aprovada pelo financeiro!');
    }

    public function usuarioEnviarPrestacaoContas(Request $request){

        $user = auth()->user();
        $av = Av::findOrFail($request->get('id'));
        $userAv = User::findOrFail($av->user_id);

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

        $regras = [];
        $mensagens = [];

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
                "valorExtraReais" => $somaDespesasReal,
                "valorExtraDolar" => $somaDespesasDolar
            );
        }
        else{
            $dados = array(
                "isPrestacaoContasRealizada" => 1,
                "status" => "Aguardando aprovação da Prestação de Contas pelo Financeiro",
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
        
        //$request->validate($regras, $mensagens);
        
        Av::findOrFail($av->id)->update($dados);
        $historico->save();

        $permission = Permission::where('name', 'aprov avs financeiro')->first();
        
        $users = User::all();
        if($userAv->name != "testeviagem"){
            foreach($users as $u2){
                try {
                    if($u2->hasPermissionTo($permission)){
                        //verifique se u2 é da mesma regional que $userAv
                        if(
                        ($u2->department != "ERCSC" 
                        && $u2->department != "ERMGA" 
                        && $u2->department != "ERFCB" 
                        && $u2->department != "ERGUA" 
                        && $u2->department != "ERLDA" 
                        && $u2->department != "ERPTG")
                        &&
                        ($userAv->department != "ERCSC"
                        && $userAv->department != "ERMGA"
                        && $userAv->department != "ERFCB"
                        && $userAv->department != "ERGUA"
                        && $userAv->department != "ERLDA"
                        && $userAv->department != "ERPTG")
                        )
                        {
                            Mail::to($u2->username)
                            ->send(new EnvioUsuarioToFinanceiroPc($av->user_id, $u2->id, $av->id));
                        }
                        else if($u2->department != $userAv->department && $userAv->department == "ERFCB" && $u2->department == "ERCSC"){
                            Mail::to($u2->username)
                            ->send(new EnvioUsuarioToFinanceiroPc($av->user_id, $u2->id, $av->id));
                        }
                        else if
                        (
                        ($u2->department == "ERCSC" && $userAv->department == "ERCSC")
                        ||
                        ($u2->department == "ERMGA" && $userAv->department == "ERMGA")
                        ||
                        ($u2->department == "ERFCB" && $userAv->department == "ERFCB")
                        ||
                        ($u2->department == "ERGUA" && $userAv->department == "ERGUA")
                        ||
                        ($u2->department == "ERLDA" && $userAv->department == "ERLDA")
                        ||
                        ($u2->department == "ERPTG" && $userAv->department == "ERPTG")
                        )
                        {
                            Mail::to($u2->username)
                            ->send(new EnvioUsuarioToFinanceiroPc($av->user_id, $u2->id, $av->id));
                        }
                    }
                } catch (\Throwable $th) {
                }
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
                        ->send(new EnvioFinanceiroToUsuarioReprovarAv($userAv->id, $av->id));

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
            ->send(new EnvioFinanceiroToGestorPc($userAv->id, $usermanager->id, $av->id));

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
            if($hisPc->av_id == $av->id && $hisPc->comentario == "Documento AV"){
                $valorRecebido = $hisPc;
            }
        }

        if(count($av->rotas) > 0){
            $arrayDiasValores = $this->geraArrayDiasValoresCerto($av);
        }
        else{
            $arrayDiasValores = [];
        }

        $medicoes = Medicao::all();
        $medicoesFiltradas = [];

        foreach($medicoes as $medicao){
            if($medicao->av_id == $av->id){
                array_push($medicoesFiltradas, $medicao); 
            }
        }

        $dataAtual = new DateTime('now', $timezone);
        //formate para o seguinte formato: 01/01/2021 00:00:00
        $dataFormatadaAtual = $dataAtual->format('d/m/Y H:i:s');

        //código para obter as reservas do usuário da AV -------------------------------------------------------------------------
        $reservas2 = [];
        $veiculos = [];

        $url = 'http://10.51.10.43/reservas/public/api/getVeiculosAPI';
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $veiculos = json_decode(curl_exec($ch));
        //crie uma coleção de $veiculos
        $veiculos = collect($veiculos);

        $url = 'http://10.51.10.43/reservas/public/api/getTodasReservasAPI';
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $reservas2 = json_decode(curl_exec($ch));
        //crie uma coleção de $reservas2
        $reservas2 = collect($reservas2);

        if(count($reservas2) > 0){
            //filtre as reservas de acordo com o $av->idReservaVeiculo e a coluna a ser filtrada é id de reserva
            $reservas2 = $reservas2->filter(function ($reserva) use ($av) {
                if($reserva->id == $av->idReservaVeiculo){
                    return true;
                }
                else{
                    return false;
                }
            });
        }

        if(count($veiculos) > 0){
            //verifique qual é o veículo da reserva pela coluna idVeiculo de $reservas2 e adicione uma nova coluna chamada veiculo
            $reservas2 = $reservas2->map(function ($reserva) use ($veiculos) {
                $veiculo = $veiculos->where('id', $reserva->idVeiculo)->first();
                $reserva->veiculo = $veiculo;
                return $reserva;
            });
        }

        //----------------------------------------------------------------------------------------------------------
        $options = new Options();
        $options->set('defaultFont', 'sans-serif');
        $dompdf = new Dompdf($options);

        $dompdf = new Dompdf();
        $dompdf->loadHtml(view('relatorioAcertoContas', compact('av', 'objetivos', 'historicos', 'users', 'userAv', 'valorRecebido', 
        'valorAcertoContasReal', 'valorAcertoContasDolar', 'arrayDiasValores', 'medicoesFiltradas', 'dataFormatadaAtual', 'reservas2')));
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
                        ->send(new EnvioFinanceiroToUsuarioAcertoContas($userAv->id, $av->id));

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

    public function enviarComprovanteDevolucaoParaCFI(Request $request){

        $user = auth()->user();
        $av = Av::findOrFail($request->get('id'));
        $userAv = User::findOrFail($av->user_id);

        $dados = [];

        $historico = new Historico();
        $timezone = new DateTimeZone('America/Sao_Paulo');
        $historico->dataOcorrencia = new DateTime('now', $timezone);
        $historico->tipoOcorrencia = "Devolução enviada pelo usuário";
        $historico->comentario = $request->get('comentario');
        $historico->perfilDonoComentario = "Usuário";
        $historico->usuario_id = $av->user_id;
        $historico->usuario_comentario_id = $user->id;
        $historico->av_id = $av->id;
        

        $dados = array(
            "status" => "Devolução enviada pelo usuário - Aguardando Acerto de Contas do Financeiro"
        );

        Av::findOrFail($av->id)->update($dados);
        $historico->save();

        //enviar para todos do financeiro um e-mail avisando que o usuário enviou a devolução
        $permission = Permission::where('name', 'aprov avs financeiro')->first();

        $users = User::all();
        
        foreach($users as $u2){
            try {
                if($u2->hasPermissionTo($permission)){
                    //verifique se u2 é da mesma regional que $userAv
                    if(
                    ($u2->department != "ERCSC" 
                    && $u2->department != "ERMGA" 
                    && $u2->department != "ERFCB" 
                    && $u2->department != "ERGUA" 
                    && $u2->department != "ERLDA" 
                    && $u2->department != "ERPTG")
                    &&
                    ($userAv->department != "ERCSC"
                    && $userAv->department != "ERMGA"
                    && $userAv->department != "ERFCB"
                    && $userAv->department != "ERGUA"
                    && $userAv->department != "ERLDA"
                    && $userAv->department != "ERPTG")
                    )
                    {
                        Mail::to($u2->username)
                        ->send(new EnvioUsuarioToFinanceiroDevolucao($av->user_id, $u2->id, $av->id));
                    }
                    else if($u2->department != $userAv->department && $userAv->department == "ERFCB" && $u2->department == "ERCSC"){
                        Mail::to($u2->username)
                        ->send(new EnvioUsuarioToFinanceiroDevolucao($av->user_id, $u2->id, $av->id));
                    }
                    else if
                    (
                    ($u2->department == "ERCSC" && $userAv->department == "ERCSC")
                    ||
                    ($u2->department == "ERMGA" && $userAv->department == "ERMGA")
                    ||
                    ($u2->department == "ERFCB" && $userAv->department == "ERFCB")
                    ||
                    ($u2->department == "ERGUA" && $userAv->department == "ERGUA")
                    ||
                    ($u2->department == "ERLDA" && $userAv->department == "ERLDA")
                    ||
                    ($u2->department == "ERPTG" && $userAv->department == "ERPTG")
                    )
                    {
                        Mail::to($u2->username)
                        ->send(new EnvioUsuarioToFinanceiroDevolucao($av->user_id, $u2->id, $av->id));
                    }
                }
            } catch (\Throwable $th) {
            }
        }

        return redirect('/avs/avs')->with('msg', 'Devolução cadastrada!');
    }

    public function usuarioReprovarAcertoContas(Request $request){

        $user = auth()->user();
        $av = Av::findOrFail($request->get('id'));
        $userAv = User::findOrFail($av->user_id);

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

        foreach($users as $u2){
            try {
                if($u2->hasPermissionTo($permission)){
                    //verifique se u2 é da mesma regional que $userAv
                    if(
                    ($u2->department != "ERCSC" 
                    && $u2->department != "ERMGA" 
                    && $u2->department != "ERFCB" 
                    && $u2->department != "ERGUA" 
                    && $u2->department != "ERLDA" 
                    && $u2->department != "ERPTG")
                    &&
                    ($userAv->department != "ERCSC"
                    && $userAv->department != "ERMGA"
                    && $userAv->department != "ERFCB"
                    && $userAv->department != "ERGUA"
                    && $userAv->department != "ERLDA"
                    && $userAv->department != "ERPTG")
                    )
                    {
                        Mail::to($u2->username)
                        ->send(new EnvioUsuarioToFinanceiroAcertoContas($av->user_id, $u2->id, $av->id));
                    }
                    else if($u2->department != $userAv->department && $userAv->department == "ERFCB" && $u2->department == "ERCSC"){
                        Mail::to($u2->username)
                        ->send(new EnvioUsuarioToFinanceiroAcertoContas($av->user_id, $u2->id, $av->id));
                    }
                    else if
                    (
                    ($u2->department == "ERCSC" && $userAv->department == "ERCSC")
                    ||
                    ($u2->department == "ERMGA" && $userAv->department == "ERMGA")
                    ||
                    ($u2->department == "ERFCB" && $userAv->department == "ERFCB")
                    ||
                    ($u2->department == "ERGUA" && $userAv->department == "ERGUA")
                    ||
                    ($u2->department == "ERLDA" && $userAv->department == "ERLDA")
                    ||
                    ($u2->department == "ERPTG" && $userAv->department == "ERPTG")
                    )
                    {
                        Mail::to($u2->username)
                        ->send(new EnvioUsuarioToFinanceiroAcertoContas($av->user_id, $u2->id, $av->id));
                    }
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
                        ->send(new EnvioFinanceiroToUsuarioReprovarAcertoContas($userAv->id, $av->id));

        return redirect('/avs/autPcFinanceiro')->with('msg', 'Prestação de contas reprovado pelo Financeiro!');
    }

    public function gestorAprovaPrestacaoContas(Request $request){

        $user = auth()->user();
        $av = Av::findOrFail($request->get('id'));
        $userAv = User::findOrFail($av->user_id);

        //---------AQUI VAI SER RESGATADO O VALOR EXTRA UTILIZADO E VERIRICAR SE O USUÁRIO DEVE RECEBER OU PAGAR
        $historicoPcAll = HistoricoPc::all();

        foreach($historicoPcAll as $hisPc){

            if($hisPc->av_id == $av->id && $hisPc->comentario == "Documento AV"){
                $valorRecebido = $hisPc;
            }
        }
        if($valorRecebido == null){
            $valorRecebido = new HistoricoPc();
            $valorRecebido->valorReais = 0;
            $valorRecebido->valorExtraReais = 0;
        }

        $comprovantesAll = ComprovanteDespesa::all();
        $comprovantes = [];

        $valorAcertoContasReal = 0;

        foreach($comprovantesAll as $comp){
            if($comp->av_id == $av->id){
                array_push($comprovantes, $comp);
            }
        }
        foreach($comprovantes as $compFiltrado){
            $valorAcertoContasReal += $compFiltrado->valorReais;
        }
        $resultadoUsuarioReceber = 0;
        $resultadoUsuarioPagar = 0;

        if ($valorRecebido->valorReais - $av->valorReais + ($valorRecebido->valorExtraReais - $valorAcertoContasReal) > 0){
            //Valor que o usuário deve pagar em reais
            $resultadoUsuarioPagar = $valorRecebido->valorReais - $av->valorReais + ($valorRecebido->valorExtraReais - $valorAcertoContasReal);
        }
        //----------------------------------------------------------------------------------------------------------

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
        else if($resultadoUsuarioPagar > 0){
            $dados = array(
                "isGestorAprovouPC" => 1,
                "status" => "Aguardando envio de comprovante de devolução pelo usuário"
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

        if($resultadoUsuarioPagar > 0){

            Mail::to($userAv->username)
            ->send(new EnvioGestorToUsuarioDevolverDespesas($userAv->id, $av->id));
        }
        else{
            $users = User::all();
            foreach($users as $u2){
                try {
                    if($u2->hasPermissionTo($permission)){
                        //verifique se u2 é da mesma regional que $userAv
                        if(
                        ($u2->department != "ERCSC" 
                        && $u2->department != "ERMGA" 
                        && $u2->department != "ERFCB" 
                        && $u2->department != "ERGUA" 
                        && $u2->department != "ERLDA" 
                        && $u2->department != "ERPTG")
                        &&
                        ($userAv->department != "ERCSC"
                        && $userAv->department != "ERMGA"
                        && $userAv->department != "ERFCB"
                        && $userAv->department != "ERGUA"
                        && $userAv->department != "ERLDA"
                        && $userAv->department != "ERPTG")
                        )
                        {
                            Mail::to($u2->username)
                            ->send(new EnvioGestorToFinanceiroAcertoContas($av->user_id, $u2->id, $av->id));
                        }
                        else if($u2->department != $userAv->department && $userAv->department == "ERFCB" && $u2->department == "ERCSC"){
                            Mail::to($u2->username)
                            ->send(new EnvioGestorToFinanceiroAcertoContas($av->user_id, $u2->id, $av->id));
                        }
                        else if
                        (
                        ($u2->department == "ERCSC" && $userAv->department == "ERCSC")
                        ||
                        ($u2->department == "ERMGA" && $userAv->department == "ERMGA")
                        ||
                        ($u2->department == "ERFCB" && $userAv->department == "ERFCB")
                        ||
                        ($u2->department == "ERGUA" && $userAv->department == "ERGUA")
                        ||
                        ($u2->department == "ERLDA" && $userAv->department == "ERLDA")
                        ||
                        ($u2->department == "ERPTG" && $userAv->department == "ERPTG")
                        )
                        {
                            Mail::to($u2->username)
                            ->send(new EnvioGestorToFinanceiroAcertoContas($av->user_id, $u2->id, $av->id));
                        }
                    }
                } catch (\Throwable $th) {
                }
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
                        ->send(new EnvioGestorToUsuarioReprovarPc($userAv->id, $av->id));

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
                    ->send(new EnvioDiretoriaToSecretaria($av->user_id, $u->id, $av->id));
                }
            } catch (\Throwable $th) {
            }
        }
        foreach($users as $u2){
            try {
                if($u2->hasPermissionTo($permission2)){
                    //verifique se u2 é da mesma regional que $userAv
                    if(
                    ($u2->department != "ERCSC" 
                    && $u2->department != "ERMGA" 
                    && $u2->department != "ERFCB" 
                    && $u2->department != "ERGUA" 
                    && $u2->department != "ERLDA" 
                    && $u2->department != "ERPTG")
                    &&
                    ($userAv->department != "ERCSC"
                    && $userAv->department != "ERMGA"
                    && $userAv->department != "ERFCB"
                    && $userAv->department != "ERGUA"
                    && $userAv->department != "ERLDA"
                    && $userAv->department != "ERPTG")
                    )
                    {
                        Mail::to($u2->username)
                        ->send(new EnvioGestorToFinanceiro($av->user_id, $u2->id, $av->id));
                    }
                    else if($u2->department != $userAv->department && $userAv->department == "ERFCB" && $u2->department == "ERCSC"){
                        Mail::to($u2->username)
                        ->send(new EnvioGestorToFinanceiro($av->user_id, $u2->id, $av->id));
                    }
                    else if
                    (
                    ($u2->department == "ERCSC" && $userAv->department == "ERCSC")
                    ||
                    ($u2->department == "ERMGA" && $userAv->department == "ERMGA")
                    ||
                    ($u2->department == "ERFCB" && $userAv->department == "ERFCB")
                    ||
                    ($u2->department == "ERGUA" && $userAv->department == "ERGUA")
                    ||
                    ($u2->department == "ERLDA" && $userAv->department == "ERLDA")
                    ||
                    ($u2->department == "ERPTG" && $userAv->department == "ERPTG")
                    )
                    {
                        Mail::to($u2->username)
                        ->send(new EnvioGestorToFinanceiro($av->user_id, $u2->id, $av->id));
                    }
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
                        ->send(new EnvioDiretoriaToUsuarioReprovarAv($userAv->id, $av->id));

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
        
        $validator = Validator::make($request->all(), $regras, $mensagens);
        if ($validator->fails()) {
            return redirect('/avs/create')
                        ->withErrors($validator)
                        ->withInput();
        }
        
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

    public function salvarContatos(Request $request, $id)
    {
        $av = Av::findOrFail($id);
        $av->contatos = $request->contatos;
        $av->save();
        
        return response()->json([$av->contatos]);
    }

    public function salvarAtividades(Request $request, $id)
    {
        $av = Av::findOrFail($id);
        $av->atividades = $request->atividades;
        $av->save();
        
        return response()->json([$av->atividades]);
    }

    public function salvarConclusoes(Request $request, $id)
    {
        $av = Av::findOrFail($id);
        $av->conclusoes = $request->conclusoes;
        $av->save();
        
        return response()->json([$av->conclusoes]);
    }
    
    public function concluir($avId, $isPc){
        
        $objetivos = Objetivo::all();
        
        $user = auth()->user();

        $av = Av::findOrFail($avId);
        $rotas = $av->rotas;//Busca as rotas da AV

        //Valor do cálculo de rota e verificar quanto que terá que pagar ao usuário
        $diariaTotal = 0;
        $meiaDiaria = 0;

        $valorReais = 0;
        $valorDolar = 0;

        $isInternacional = false;
        $mostrarValor = true;

        //itere rotas e veja se alguma é internacional
        foreach($rotas as $rota){
            if($rota->isViagemInternacional==true){
                $isInternacional = true;
            }
        }

        //se não tiver rotas, retorne para a página de rotas
        if(sizeof($rotas) == 0){
            if($isPc=="sim"){
        
                return redirect('/rotaspc/rotas/' . $avId );
            }
            else{
                return redirect('/rotas/rotas/' . $avId );
            }
        }
        
        $dataInicio = date('Y-m-d', strtotime($rotas[0]->dataHoraSaida));
        $dataFim = date('Y-m-d', strtotime($rotas[sizeof($rotas)-1]->dataHoraChegada));
                       
        $arrayDiasValores = [];
                        
        $intervaloDatas = new DatePeriod(
            new DateTime($dataInicio),
            new DateInterval('P1D'),
            ($dataInicio != $dataFim ? (new DateTime($dataFim))->modify('+1 day') : (new DateTime($dataFim)))
        );
        
        //------------------------------------------------------------------------------------------------------------------------------------------------------
        
        foreach ($intervaloDatas as $data) {
            
            
            $dia = $data->format('Y-m-d');
            $valor = 0;
            $acumulado = 0;

            $rotasDoDia = [];
            $dataPrimeiraRota = null;
            $dataUltimaRota = null;

            //ITERE AS ROTAS E VERIFIQUE SE O DIA ESTÁ ENTRE A DATA DE SAÍDA E A DATA DE CHEGADA-----------------------------------
            for ($i=0; $i < sizeof($rotas) ; $i++) {
                    $dataSaida = DateTime::createFromFormat('Y-m-d H:i:s', $rotas[$i]->dataHoraSaida)->format('Y-m-d H:i:s');
                    $dataChegada = DateTime::createFromFormat('Y-m-d H:i:s', $rotas[$i]->dataHoraChegada)->format('Y-m-d H:i:s');

                    $dataSaidaFormatado = new DateTime($dataSaida);//Data de saída da rota 1
                    $dataChegadaFormatado = new DateTime($dataChegada);//Data de chegada da rota 1
            
                    //verifique se a data do dia está entre a data de saída e a data de chegada
                    if($dia >= $dataSaidaFormatado->format('Y-m-d') && $dia <= $dataChegadaFormatado->format('Y-m-d')){
                        $rotasDoDia[] = $rotas[$i];
                    }

                    //captura a data inicial da primeira rota
                    if($i == 0){
                        $dataPrimeiraRota = $dataSaidaFormatado->format('Y-m-d');
                    }
                    
                    //captura a data final da ultima rota
                    if($i == sizeof($rotas)-1){
                        $dataUltimaRota = $dataChegadaFormatado->format('Y-m-d');
                    }
            }//----------------------------------------------------------------------------------------------------------------------
            $arrayRotasDoDia = [];
            //ISSO TRATA SITUAÇÕES ONDE NÃO HÁ ROTA NO DIA, OU SEJA O DIA ESTÁ NO INTERVALO ENTRE DUAS VIAGENS-----------------------
            if(sizeof($rotasDoDia) == 0){
                //procure a rota em que a dataHoraChegada é anterior ao dia atual e atribua a $rota ao dia
                for ($i=sizeof($rotas)-1; $i >= 0 ; $i--) {
                    $dataChegada = DateTime::createFromFormat('Y-m-d H:i:s', $rotas[$i]->dataHoraChegada)->format('Y-m-d H:i:s');
                    $dataChegadaFormatado = new DateTime($dataChegada);
                    
                    if($dia > $dataChegadaFormatado->format('Y-m-d')){
                        $valor = $this->verificaValorRota($rotas[$i]);
                        $arrayRotasDoDia[] = $rotas[$i]->cidadeDestinoNacional;
                        break;
                    }
                }
            }//----------------------------------------------------------------------------------------------------------------------

            $temDiariaManha = false;
            $temDiariaTarde = false;
            $valorManha = 0;
            $valorTarde = 0;
            

            //AGORA VAMOS ANALISAR OS DIAS QUE POSSUEM ROTAS E CALCULAR O VALOR DA DIÁRIA-----------------------------------------------------------------
            foreach($rotasDoDia as $indice => $rota){
                
                //DECLARAÇÃO DE VARIÁVEIS ------------------------------------------------------------------------------------

                //Captura o valor para a diária da rota atual
                $valor = $this->verificaValorRota($rota);

                //Captura a rota anterior e a próxima rota
                $rotaAnterior = isset($rotasDoDia[$indice - 1]) ? $rotasDoDia[$indice - 1] : false;
                $proximaRota = isset($rotasDoDia[$indice + 1]) ? $rotasDoDia[$indice + 1] : null;

                //Captura a data de saída e formata para DateTime
                $dataSaida = DateTime::createFromFormat('Y-m-d H:i:s', $rota->dataHoraSaida)->format('Y-m-d H:i:s');
                $dataSaidaFormatado = new DateTime($dataSaida);

                //Captura a data de chegada e formata para DateTime
                $dataChegada = DateTime::createFromFormat('Y-m-d H:i:s', $rota->dataHoraChegada)->format('Y-m-d H:i:s');
                $dataChegadaFormatado = new DateTime($dataChegada);

                //Monta um array com as rotas do dia de IDA
                if ($dataSaidaFormatado->format('Y-m-d') == $dia) {
                    $arrayRotasDoDia[] = "Ida: " . $rota->cidadeOrigemNacional . " (" . $dataSaidaFormatado->format('H:i') . ")" . " >";
                }

                //Monta um array com as rotas do dia de VOLTA
                if ($dataChegadaFormatado->format('Y-m-d') == $dia) {
                    $arrayRotasDoDia[] = $rota->cidadeDestinoNacional . " (" . $dataChegadaFormatado->format('H:i') . ")" . "";
                }

                //Captura a data de chegada da rota anterior e formata para DateTime
                if($rotaAnterior != false){
                    $rotaAnteriorDataChegada = DateTime::createFromFormat('Y-m-d H:i:s', $rotaAnterior->dataHoraChegada)->format('Y-m-d H:i:s');
                    $rotaAnteriorDataChegadaFormatado = new DateTime($rotaAnteriorDataChegada);
                }

                //Captura a data de saída e chegada da próxima rota e formata para DateTime
                if($proximaRota != false){
                    
                    $proximaRotaDataSaida = DateTime::createFromFormat('Y-m-d H:i:s', $proximaRota->dataHoraSaida)->format('Y-m-d H:i:s');
                    $proximaRotaDataSaidaFormatado = new DateTime($proximaRotaDataSaida);

                    $proximaRotaDataChegada = DateTime::createFromFormat('Y-m-d H:i:s', $proximaRota->dataHoraChegada)->format('Y-m-d H:i:s');
                    $proximaRotaDataChegadaFormatado = new DateTime($proximaRotaDataChegada);
                }

                //-----------------------------------------------------------------------------------------------------------

                //APENAS SE COINDICIR O DIA DA ROTA COM O DIAS DO INTERVALO, NOS DIAS INTEMEDIÁRIOS O VALOR DA DIÁRIA É O DA ÚLTIMA ROTA
                if($dataSaidaFormatado->format('Y-m-d') == $dia){
                    
                    if($temDiariaManha == false){

                        if( ($dataSaidaFormatado->format('H:i:s') <= "12:00:00" && $dataChegadaFormatado->format('H:i:s') >= "13:01:00") && $dia != $dataUltimaRota){
                        //SE A HORA DE SAÍDA FOR MENOR QUE 12:00 E A HORA DE CHEGADA FOR MAIOR QUE 13:01
                            $valorManha = $valor/2;
                            $temDiariaManha = true;
                        }
                        else if($dataSaidaFormatado->format('H:i:s') <= "12:00:00" && $dataChegadaFormatado->format('H:i:s') < "19:00:00"
                                && $proximaRota == false && $dia != $dataUltimaRota){
                        //SE A HORA DE SAÍDA FOR MENOR QUE 12:00 E A HORA DE CHEGADA FOR MENOR QUE 19:00 E NÃO TIVER PRÓXIMA ROTA NO DIA, MAS NÃO ACABOU A VIAGEM
                            $valorManha = $valor/2;
                            $temDiariaManha = true;
                        }
                        else if($proximaRota != false && $proximaRotaDataSaidaFormatado->format('Y-m-d') == $dia && 
                                $dataSaidaFormatado->format('H:i:s') <= "12:00:00" && $dia != $dataUltimaRota){
                        //SE A PRÓXIMA ROTA FOR NO MESMO DIA, A HORA DE SAÍDA DELA FOR MAIOR QUE 13:01 E NÃO FOR A ÚLTIMA ROTA
                            $valorManha = $valor/2;
                            $temDiariaManha = true;
                        }
                        else if($dia != $dataPrimeiraRota && $dia != $dataUltimaRota){
                        //SE O DIA ATUAL NÃO FOR O DIA DA PRIMEIRA ROTA E A HORA DE SAÍDA FOR MAIOR QUE 12:01
                            $rotaImediatamenteAnterior = $this->buscarRotaAnterior($rota, $rotas);
                            $valor = $this->verificaValorRota($rotaImediatamenteAnterior);
                            $valorManha = $valor/2;
                            $temDiariaManha = true;
                        }
                        else if($dia == $dataUltimaRota && $dataChegadaFormatado->format('H:i:s') >= "13:01:00"){
                        //SE O DIA ATUAL FOR O DIA DA ÚLTIMA ROTA E A HORA DE SAÍDA FOR MENOR QUE 12:00
                            try {
                                $rotaImediatamenteAnterior = $this->buscarRotaAnterior($rota, $rotas);
                                $valor = $this->verificaValorRota($rotaImediatamenteAnterior);
                            } catch (\Throwable $th) {
                                $valor = $this->verificaValorRota($rota);
                            }
                            $valorManha = $valor/2;
                            $temDiariaManha = true;
                        }
                    }

                    if($temDiariaTarde == false){
                        
                        if($dataSaidaFormatado->format('H:i:s') >= "13:01:00" && $dataSaidaFormatado->format('H:i:s') < "19:00:00" 
                            && $dataChegadaFormatado->format('H:i:s') >= "19:01:00" && $dia != $dataUltimaRota){
                        //SE A HORA DE SAÍDA FOR MAIOR QUE 13:01 E MENOR QUE 19:00 E A HORA DE CHEGADA FOR MAIOR QUE 19:01
                            $valorTarde = $valor/2;
                            $temDiariaTarde = true;
                        }
                        else if($proximaRota != false && $dia != $dataUltimaRota &&
                                ($proximaRotaDataSaidaFormatado->format('Y-m-d') == $dia && $proximaRotaDataSaidaFormatado->format('H:i:s') >= "19:01:00" ||
                                 $proximaRotaDataChegadaFormatado->format('Y-m-d') == $dia && $proximaRotaDataChegadaFormatado->format('H:i:s') >= "19:01:00")){
                        //SE A PRÓXIMA ROTA FOR NO MESMO DIA E A HORA DE SAÍDA OU CHEGADA DELA FOR MAIOR QUE 19:01
                            try {
                                $rotaImediatamenteAnterior = $this->buscarRotaPosterior($rota, $rotas);
                                $valor = $this->verificaValorRota($rotaImediatamenteAnterior);
                            } catch (\Throwable $th) {
                                $valor = $this->verificaValorRota($rota);
                            }
                            $valorTarde = $valor/2;
                            $temDiariaTarde = true;
                        }
                        else if($proximaRota == false && $dia != $dataUltimaRota && $dia == $dataChegadaFormatado->format('Y-m-d') && $dataChegadaFormatado->format('H:i:s') < "24:00:00"){
                        //NÃO TEM MAIS ROTAS NO DIA, SIGNFICA QUE JÁ CHEGOU E VAI FICAR NA CIDADE, A HORA DE CHEGADA É MENOR QUE 24:00, NÃO É A ÚLTIMA ROTA E CHEGOU NA CIDADE NO MESMO DIA
                            $valorTarde = $valor/2;
                            $temDiariaTarde = true;
                        }
                        else if($proximaRota == false && $dia != $dataUltimaRota && $dia != $dataChegadaFormatado->format('Y-m-d') && $dataSaidaFormatado->format('H:i:s') < "19:00:00"){
                        //NÃO TEM MAIS ROTAS NO DIA, SIGNFICA QUE JÁ CHEGOU E VAI FICAR NA CIDADE, A HORA DE SAÍDA É MENOR QUE 19:00, NÃO É A ÚLTIMA ROTA E A CHEGADA NO DESTINO VAI SER NO DIA SEGUINTE
                            $valorTarde = $valor/2;
                            $temDiariaTarde = true;
                        }
                        else if($proximaRota == false && $dia != $dataUltimaRota && $dia != $dataChegadaFormatado->format('Y-m-d') && $dia != $dataPrimeiraRota){
                        //NÃO TEM MAIS ROTAS NO DIA, SIGNFICA QUE JÁ CHEGOU E VAI FICAR NA CIDADE, NÃO É A ÚLTIMA ROTA E A CHEGADA NO DESTINO VAI SER NO DIA SEGUINTE E NÃO É A PRIMEIRA ROTA
                            $valorTarde = $valor/2;
                            $temDiariaTarde = true;
                        }
                        else if($dia == $dataUltimaRota && $dataChegadaFormatado->format('H:i:s') >= "19:01:00"){
                        //SE O DIA ATUAL FOR O DIA DA ÚLTIMA ROTA E A HORA DE CHEGADA FOR MAIOR QUE 19:01
                            try {
                                $rotaImediatamenteAnterior = $this->buscarRotaAnterior($rota, $rotas);
                                $valor = $this->verificaValorRota($rotaImediatamenteAnterior);
                            } catch (\Throwable $th) {
                                $valor = $this->verificaValorRota($rota);
                            }
                            $valorTarde = $valor/2;
                            $temDiariaTarde = true;
                        }
                    }

                    if($temDiariaManha == true && $temDiariaTarde == true)
                        $valor = $valorManha + $valorTarde;
                    else if($temDiariaManha == true && $temDiariaTarde == false)
                        $valor = $valorManha;
                    else if($temDiariaManha == false && $temDiariaTarde == true)
                        $valor = $valorTarde;
                    else if($temDiariaManha == false && $temDiariaTarde == false)
                        $valor = 0;
                }
                else if($dia == $dataUltimaRota && $dataSaidaFormatado->format('Y-m-d') != $dia){
                    if($dataChegadaFormatado->format('H:i:s') >= "13:01:00" && $dataChegadaFormatado->format('H:i:s') < "19:00:00"){
                        $valorManha = $valor/2;
                    }
                    else if($dataChegadaFormatado->format('H:i:s') >= "19:01:00"){
                        $valorTarde = $valor/2;
                    }
                    $valor = $valorManha + $valorTarde;
                }

            }

            if(sizeof($rotasDoDia) == 0 && $dia != $dataUltimaRota){
                $valorManha = $valor/2;
                $valorTarde = $valor/2;
            }
            //se o dia for diferente do primeiro e do ultimo
            if($dia != $dataPrimeiraRota && $dia != $dataUltimaRota && $valorManha == 0 && $valorTarde == 0 && $valor != 0){
                $valorManha = $valor/2;
                $valorTarde = $valor/2;
            }
            //se for o ultimo dia
            if($dia == $dataUltimaRota && $valorManha == 0 && $valorTarde == 0 && $valor != 0){
                $valor = 0;
            }
            
            $diaFormatado = DateTime::createFromFormat('Y-m-d', $dia);
            $arrayDiasValores[] = [
                'dia' => $diaFormatado->format('d'),
                'arrayRotasDoDia' => $arrayRotasDoDia,
                'valorManha' => $valorManha,
                'valorTarde' => $valorTarde,
                'valor' => $valor,
            ];
        }
        
        //Se a viagem for somente no mesmo dia
        if(iterator_count($intervaloDatas) == 0){
            
            //Captura a data de $intervaloData e atribua a variável $data
            $data = new DateTime($dataInicio);

            $dia = $data->format('Y-m-d');
            $valor = 0;
            $acumulado = 0;

            $rotasDoDia = [];
            $dataPrimeiraRota = null;
            $dataUltimaRota = null;

            //ITERE AS ROTAS E VERIFIQUE SE O DIA ESTÁ ENTRE A DATA DE SAÍDA E A DATA DE CHEGADA-----------------------------------
            for ($i=0; $i < sizeof($rotas) ; $i++) {
                    $dataSaida = DateTime::createFromFormat('Y-m-d H:i:s', $rotas[$i]->dataHoraSaida)->format('Y-m-d H:i:s');
                    $dataChegada = DateTime::createFromFormat('Y-m-d H:i:s', $rotas[$i]->dataHoraChegada)->format('Y-m-d H:i:s');

                    $dataSaidaFormatado = new DateTime($dataSaida);//Data de saída da rota 1
                    $dataChegadaFormatado = new DateTime($dataChegada);//Data de chegada da rota 1
            
                    //verifique se a data do dia está entre a data de saída e a data de chegada
                    if($dia >= $dataSaidaFormatado->format('Y-m-d') && $dia <= $dataChegadaFormatado->format('Y-m-d')){
                        $rotasDoDia[] = $rotas[$i];
                    }

                    //captura a data inicial da primeira rota
                    if($i == 0){
                        $dataPrimeiraRota = $dataSaidaFormatado->format('Y-m-d');
                    }
                    
                    //captura a data final da ultima rota
                    if($i == sizeof($rotas)-1){
                        $dataUltimaRota = $dataChegadaFormatado->format('Y-m-d');
                    }
            }//----------------------------------------------------------------------------------------------------------------------
            $arrayRotasDoDia = [];
            //ISSO TRATA SITUAÇÕES ONDE NÃO HÁ ROTA NO DIA, OU SEJA O DIA ESTÁ NO INTERVALO ENTRE DUAS VIAGENS-----------------------
            if(sizeof($rotasDoDia) == 0){
                //procure a rota em que a dataHoraChegada é anterior ao dia atual e atribua a $rota ao dia
                for ($i=sizeof($rotas)-1; $i >= 0 ; $i--) {
                    $dataChegada = DateTime::createFromFormat('Y-m-d H:i:s', $rotas[$i]->dataHoraChegada)->format('Y-m-d H:i:s');
                    $dataChegadaFormatado = new DateTime($dataChegada);
                    
                    if($dia > $dataChegadaFormatado->format('Y-m-d')){
                        $valor = $this->verificaValorRota($rotas[$i]);
                        $arrayRotasDoDia[] = $rotas[$i]->cidadeDestinoNacional;
                        break;
                    }
                }
            }//----------------------------------------------------------------------------------------------------------------------

            $temDiariaManha = false;
            $temDiariaTarde = false;
            $valorManha = 0;
            $valorTarde = 0;
            

            //AGORA VAMOS ANALISAR OS DIAS QUE POSSUEM ROTAS E CALCULAR O VALOR DA DIÁRIA-----------------------------------------------------------------
            foreach($rotasDoDia as $indice => $rota){
                
                //DECLARAÇÃO DE VARIÁVEIS ------------------------------------------------------------------------------------

                //Captura o valor para a diária da rota atual
                $valor = $this->verificaValorRota($rota);

                //Captura a rota anterior e a próxima rota
                $rotaAnterior = isset($rotasDoDia[$indice - 1]) ? $rotasDoDia[$indice - 1] : false;
                $proximaRota = isset($rotasDoDia[$indice + 1]) ? $rotasDoDia[$indice + 1] : null;

                //Captura a data de saída e formata para DateTime
                $dataSaida = DateTime::createFromFormat('Y-m-d H:i:s', $rota->dataHoraSaida)->format('Y-m-d H:i:s');
                $dataSaidaFormatado = new DateTime($dataSaida);

                //Captura a data de chegada e formata para DateTime
                $dataChegada = DateTime::createFromFormat('Y-m-d H:i:s', $rota->dataHoraChegada)->format('Y-m-d H:i:s');
                $dataChegadaFormatado = new DateTime($dataChegada);

                //Monta um array com as rotas do dia de IDA
                if ($dataSaidaFormatado->format('Y-m-d') == $dia) {
                    $arrayRotasDoDia[] = "Ida: " . $rota->cidadeOrigemNacional . " (" . $dataSaidaFormatado->format('H:i') . ")" . " >";
                }

                //Monta um array com as rotas do dia de VOLTA
                if ($dataChegadaFormatado->format('Y-m-d') == $dia) {
                    $arrayRotasDoDia[] = $rota->cidadeDestinoNacional . " (" . $dataChegadaFormatado->format('H:i') . ")" . "";
                }

                //Captura a data de chegada da rota anterior e formata para DateTime
                if($rotaAnterior != false){
                    $rotaAnteriorDataChegada = DateTime::createFromFormat('Y-m-d H:i:s', $rotaAnterior->dataHoraChegada)->format('Y-m-d H:i:s');
                    $rotaAnteriorDataChegadaFormatado = new DateTime($rotaAnteriorDataChegada);

                    $rotaAnteriorDataSaida = DateTime::createFromFormat('Y-m-d H:i:s', $rotaAnterior->dataHoraSaida)->format('Y-m-d H:i:s');
                    $rotaAnteriorDataSaidaFormatado = new DateTime($rotaAnteriorDataSaida);
                }

                //Captura a data de saída e chegada da próxima rota e formata para DateTime
                if($proximaRota != false){
                    
                    $proximaRotaDataSaida = DateTime::createFromFormat('Y-m-d H:i:s', $proximaRota->dataHoraSaida)->format('Y-m-d H:i:s');
                    $proximaRotaDataSaidaFormatado = new DateTime($proximaRotaDataSaida);

                    $proximaRotaDataChegada = DateTime::createFromFormat('Y-m-d H:i:s', $proximaRota->dataHoraChegada)->format('Y-m-d H:i:s');
                    $proximaRotaDataChegadaFormatado = new DateTime($proximaRotaDataChegada);
                }

                //-----------------------------------------------------------------------------------------------------------

                //APENAS SE COINDICIR O DIA DA ROTA COM O DIAS DO INTERVALO, NOS DIAS INTEMEDIÁRIOS O VALOR DA DIÁRIA É O DA ÚLTIMA ROTA
                if($dataSaidaFormatado->format('Y-m-d') == $dia){

                    if($temDiariaManha == false){
                        
                        if( ($dataSaidaFormatado->format('H:i:s') <= "12:00:00" && $dataChegadaFormatado->format('H:i:s') >= "13:01:00") && $dia != $dataUltimaRota){
                        //SE A HORA DE SAÍDA FOR MENOR QUE 12:00 E A HORA DE CHEGADA FOR MAIOR QUE 13:01
                            $valorManha = $valor/2;
                            $temDiariaManha = true;
                        }
                        else if($dataSaidaFormatado->format('H:i:s') <= "12:00:00" && $dataChegadaFormatado->format('H:i:s') < "19:00:00"
                                && $proximaRota == false && $dia != $dataUltimaRota){
                        //SE A HORA DE SAÍDA FOR MENOR QUE 12:00 E A HORA DE CHEGADA FOR MENOR QUE 13:00 E NÃO TIVER PRÓXIMA ROTA NO DIA, MAS NÃO ACABOU A VIAGEM
                            $valorManha = $valor/2;
                            $temDiariaManha = true;
                        }
                        else if($proximaRota != false && $proximaRotaDataSaidaFormatado->format('Y-m-d') == $dia && 
                                $dataSaidaFormatado->format('H:i:s') <= "12:00:00" && $dia != $dataUltimaRota){
                        //SE A PRÓXIMA ROTA FOR NO MESMO DIA, A HORA DE SAÍDA DELA FOR MAIOR QUE 13:01 E NÃO FOR A ÚLTIMA ROTA
                            $valorManha = $valor/2;
                            $temDiariaManha = true;
                        }
                        else if($dia != $dataPrimeiraRota && $dia != $dataUltimaRota){
                        //SE O DIA ATUAL NÃO FOR O DIA DA PRIMEIRA ROTA E A HORA DE SAÍDA FOR MAIOR QUE 12:01
                            $rotaImediatamenteAnterior = $this->buscarRotaAnterior($rota, $rotas);
                            $valor = $this->verificaValorRota($rotaImediatamenteAnterior);
                            $valorManha = $valor/2;
                            $temDiariaManha = true;
                        }
                        else if($dia == $dataUltimaRota && $dataSaidaFormatado->format('H:i:s') <= "12:00:00" 
                                && $dataChegadaFormatado->format('H:i:s') >= "13:01:00"){
                        //SE O DIA ATUAL FOR O DIA DA ÚLTIMA ROTA E A HORA DE SAÍDA FOR MENOR QUE 12:00
                            try {
                                $rotaImediatamenteAnterior = $this->buscarRotaAnterior($rota, $rotas);
                                $valor = $this->verificaValorRota($rotaImediatamenteAnterior);
                            } catch (\Throwable $th) {
                                $valor = $this->verificaValorRota($rota);
                            }
                            
                            $valorManha = $valor/2;
                            $temDiariaManha = true;
                        }
                        else if($dia == $dataUltimaRota && $dataSaidaFormatado->format('H:i:s') <= "12:00:00" 
                                && $proximaRota != false && $proximaRotaDataChegadaFormatado->format('H:i:s') >= "13:01:00"){
                        //SE O DIA ATUAL FOR O DIA DA ÚLTIMA ROTA E A HORA DE SAÍDA FOR MENOR QUE 12:00
                            try {
                                $rotaImediatamenteAnterior = $this->buscarRotaAnterior($rota, $rotas);
                                $valor = $this->verificaValorRota($rotaImediatamenteAnterior);
                            } catch (\Throwable $th) {
                                $valor = $this->verificaValorRota($rota);
                            }
                            
                            $valorManha = $valor/2;
                            $temDiariaManha = true;
                        }
                        else if ($dia == $dataUltimaRota && $proximaRota == false && 
                        $rotaAnteriorDataChegadaFormatado->format('H:i:s') <= "13:01:00" &&
                        $rotaAnteriorDataSaidaFormatado->format('H:i:s') <= "13:01:00" &&
                        $dataChegadaFormatado->format('H:i:s') >= "13:01:00" ){
                            //SE O DIA ATUAL FOR O DIA DA ÚLTIMA ROTA E A ROTA ANTERIOR TIVER HORÁRIO DE SAÍDA OU CHEGADA ANTES DE 13:01 E A DATA DE CHEGADA DA ÚLTIMA ROTA FOR DEPOIS DE 13:01
                            try {
                                $rotaImediatamenteAnterior = $this->buscarRotaAnterior($rota, $rotas);
                                $valor = $this->verificaValorRota($rotaImediatamenteAnterior);
                            } catch (\Throwable $th) {
                                $valor = $this->verificaValorRota($rota);
                            }
                            $valorManha = $valor / 2;
                            $temDiariaManha = true;
                        }
                    }

                    if($temDiariaTarde == false){
                        
                        if($dataSaidaFormatado->format('H:i:s') >= "13:01:00" && $dataSaidaFormatado->format('H:i:s') < "19:00:00" 
                            && $dataChegadaFormatado->format('H:i:s') >= "19:01:00" && $dia != $dataUltimaRota){
                        //SE A HORA DE SAÍDA FOR MAIOR QUE 13:01 E MENOR QUE 19:00 E A HORA DE CHEGADA FOR MAIOR QUE 19:01
                            $valorTarde = $valor/2;
                            $temDiariaTarde = true;
                        }
                        else if($proximaRota != false && $dia != $dataUltimaRota &&
                                ($proximaRotaDataSaidaFormatado->format('Y-m-d') == $dia && $proximaRotaDataSaidaFormatado->format('H:i:s') >= "19:01:00" ||
                                 $proximaRotaDataChegadaFormatado->format('Y-m-d') == $dia && $proximaRotaDataChegadaFormatado->format('H:i:s') >= "19:01:00")){
                        //SE A PRÓXIMA ROTA FOR NO MESMO DIA E A HORA DE SAÍDA OU CHEGADA DELA FOR MAIOR QUE 19:01
                            try {
                                $rotaImediatamenteAnterior = $this->buscarRotaPosterior($rota, $rotas);
                                $valor = $this->verificaValorRota($rotaImediatamenteAnterior);
                            } catch (\Throwable $th) {
                                $valor = $this->verificaValorRota($rota);
                            }
                            $valorTarde = $valor/2;
                            $temDiariaTarde = true;
                        }
                        else if($proximaRota == false && $dia != $dataUltimaRota && $dataChegadaFormatado->format('H:i:s') < "24:00:00"){
                        //NÃO TEM MAIS ROTAS NO DIA, SIGNFICA QUE JÁ CHEGOU E VAI FICAR NA CIDADE
                            $valorTarde = $valor/2;
                            $temDiariaTarde = true;
                        }
                        else if($dia == $dataUltimaRota && $dataChegadaFormatado->format('H:i:s') >= "19:01:00"){
                        //SE O DIA ATUAL FOR O DIA DA ÚLTIMA ROTA E A HORA DE CHEGADA FOR MAIOR QUE 19:01

                            try {
                                $rotaImediatamenteAnterior = $this->buscarRotaAnterior($rota, $rotas);
                                $valor = $this->verificaValorRota($rotaImediatamenteAnterior);
                            } catch (\Throwable $th) {
                                $valor = $this->verificaValorRota($rota);
                            }
                            
                            $valorTarde = $valor/2;
                            $temDiariaTarde = true;
                        }
                    }

                    if($temDiariaManha == true && $temDiariaTarde == true)
                        $valor = $valorManha + $valorTarde;
                    else if($temDiariaManha == true && $temDiariaTarde == false)
                        $valor = $valorManha;
                    else if($temDiariaManha == false && $temDiariaTarde == true)
                        $valor = $valorTarde;
                    else if($temDiariaManha == false && $temDiariaTarde == false)
                        $valor = 0;
                }
                else if($dia == $dataUltimaRota && $dataSaidaFormatado->format('Y-m-d') != $dia){
                    if($dataChegadaFormatado->format('H:i:s') >= "13:01:00" && $dataChegadaFormatado->format('H:i:s') < "19:00:00"){
                        $valorManha = $valor/2;
                    }
                    else if($dataChegadaFormatado->format('H:i:s') >= "19:01:00"){
                        $valorTarde = $valor/2;
                    }
                    $valor = $valorManha + $valorTarde;
                }

            }
            if(sizeof($rotasDoDia) == 0 && $dia != $dataUltimaRota){
                $valorManha = $valor/2;
                $valorTarde = $valor/2;
            }
            //se o dia for diferente do primeiro e do ultimo
            if($dia != $dataPrimeiraRota && $dia != $dataUltimaRota && $valorManha == 0 && $valorTarde == 0 && $valor != 0){
                $valorManha = $valor/2;
                $valorTarde = $valor/2;
            }
            //se for o ultimo dia
            if($dia == $dataUltimaRota && $valorManha == 0 && $valorTarde == 0 && $valor != 0){
                $valor = 0;
            }
            
            $diaFormatado = DateTime::createFromFormat('Y-m-d', $dia);
            $arrayDiasValores[] = [
                'dia' => $diaFormatado->format('d'),
                'arrayRotasDoDia' => $arrayRotasDoDia,
                'valorManha' => $valorManha,
                'valorTarde' => $valorTarde,
                'valor' => $valor,
            ];
        }
        
        //CASO SÓ TENHA UMA ROTA
        if(sizeof($rotas) == 1){
            $valor = $this->verificaValorRota($rotas[0]);
            $arrayRotasDoDia = [];
            $arrayRotasDoDia[] = " [ " . $rotas[0]->cidadeOrigemNacional . " - " . $rotas[0]->cidadeDestinoNacional . " ] ";
            $arrayDiasValores[] = [
                'dia' => DateTime::createFromFormat('Y-m-d H:i:s', $rotas[0]->dataHoraSaida)->format('d'),
                'arrayRotasDoDia' => $arrayRotasDoDia,
                'valorManha' => $valor/2,
                'valorTarde' => $valor/2,
                'valor' => $valor,
            ];
        }
        // dd($arrayDiasValores);
        //------------------------------------------------------------------------------------------------------------------------------------------------------
        $diaChegadaFinal = DateTime::createFromFormat('Y-m-d H:i:s', $rotas[sizeof($rotas)-1]->dataHoraChegada)->format('d');

        //SOMA AS DIÁRIAS E RETORNO OS ATRIBUTOS NECESSÁRIOS PARA A TELA ----------------------------------------------------------------------
        foreach ($arrayDiasValores as $diaValor) {
            if($diaValor['valor'] == 150.00 || $diaValor['valor'] == 75.00){
                $valorDolar += $diaValor['valor'];
            }
            else if($diaValor['valor'] == 180.00 || $diaValor['valor'] == 90.00){
                $valorDolar += $diaValor['valor'];
            }
            else if($diaValor['valor'] == 140.00 || $diaValor['valor'] == 70.00){
                $valorDolar += $diaValor['valor'];
            }
            else if($diaValor['valor'] == 190.00 || $diaValor['valor'] == 95.00){
                $valorDolar += $diaValor['valor'];
            }
            else if($diaValor['valor'] == 65.00 || $diaValor['valor'] == 32.50){
                $valorReais += $diaValor['valor'];
            }
            else if($diaValor['valor'] == 55.00 || $diaValor['valor'] == 27.50){
                $valorReais += $diaValor['valor'];
            }
            else if($diaValor['valor'] == 100.00 || $diaValor['valor'] == 50.00){
                if (in_array('Brasília', $diaValor['arrayRotasDoDia'])) {
                    $valorReais += $diaValor['valor'];
                }
                else{
                    $valorDolar += $diaValor['valor'];
                }
            }
            else if($diaValor['valor'] == 80.00 || $diaValor['valor'] == 40.00){
                $valorReais += $diaValor['valor'];
            }
        }
        
        if($isInternacional == true){
            // $valorDolar = $valorReais;
            $av->valorDolar = $valorDolar;
            $av->valorReais = $valorReais;
        }
        else{
            $av->valorReais = $valorReais;
            $av->valorDolar = 0;
        }

        $veiculosProprios = $user->veiculosProprios;

        if($user->id != $av->user->id) {
            return redirect('/dashboard')->with('msg', 'Você não tem permissão para editar esta av!');
        }

        //Atualiza no banco de dados o valor calculado para a diária de alimentação
        $dados = array(
            "valorReais" => $av->valorReais,
            "valorDolar" => $av->valorDolar
        );
        Av::findOrFail($av->id)->update($dados);

        $veiculosProprios = VeiculoProprio::all();

        //obter dados da API
        $eventos = [];
        $reservas2 = [];
        $veiculos = [];

        $departmentUser = auth()->user()->department;
        $isCuritiba = false;
        if($departmentUser != "ERCSC"
        && $departmentUser != "ERMGA"
        && $departmentUser != "ERFCB"
        && $departmentUser != "ERGUA"
        && $departmentUser != "ERLDA"
        && $departmentUser != "ERPTG"){
            $isCuritiba = true;
        }

        $url = 'http://10.51.10.43/reservas/public/api/getReservasAPI/' . $user->department;
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $eventos = json_decode(curl_exec($ch));

        $url = 'http://10.51.10.43/reservas/public/api/getReservasUsuarioAPI/' . $user->username;
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $reservas2 = json_decode(curl_exec($ch));
        //crie uma coleção de $reservas2
        $reservas2 = collect($reservas2);

        $url = 'http://10.51.10.43/reservas/public/api/getVeiculosAPI';
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $veiculos = json_decode(curl_exec($ch));
        //crie uma coleção de $veiculos
        $veiculos = collect($veiculos);

        //--------------------------------------------------------------------------------------------------------------------------------

        //filtre os veículos de acordo com o departamento do usuário, a coluna a ser filtrada é codigoRegional de veículo
        $veiculos = $veiculos->filter(function ($veiculo) use ($departmentUser, $isCuritiba) {
            if($isCuritiba && $veiculo->codigoRegional == "CWB"){
                return true;
            }
            else if($veiculo->codigoRegional == $departmentUser){
                return true;
            }
            else{
                return false;
            }
        });

        //captura dados de reserva para popular a lista de possibilidade de ir de carona
        $reservas3 = [];
        $veiculos2 = [];

        $url = 'http://10.51.10.43/reservas/public/api/getVeiculosAPI';
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $veiculos2 = json_decode(curl_exec($ch));
        //crie uma coleção de $veiculos2
        $veiculos2 = collect($veiculos2);

        $url = 'http://10.51.10.43/reservas/public/api/getTodasReservasAPI';
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $reservas3 = json_decode(curl_exec($ch));
        //crie uma coleção de $reservas3
        $reservas3 = collect($reservas3);

        $url = 'http://10.51.10.43/reservas/public/api/getTodosUsuariosAPI';
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $usuariosReservas = json_decode(curl_exec($ch));
        //crie uma coleção de $reservas3
        $usuariosReservas = collect($usuariosReservas);

        if(count($veiculos2) > 0){
            //verifique qual é o veículo da reserva pela coluna idVeiculo de $reservas3 e adicione uma nova coluna chamada veiculo
            $reservas3 = $reservas3->map(function ($reserva) use ($veiculos2) {
                $veiculo = $veiculos2->where('id', $reserva->idVeiculo)->first();
                $reserva->veiculo = $veiculo;
                return $reserva;
            });
        }

        if(count($usuariosReservas) > 0){
            //verifique qual é o usuário da reserva pela coluna username de $reservas3 e adicione uma nova coluna chamada usuario
            $reservas3 = $reservas3->map(function ($reserva) use ($usuariosReservas) {
                $usuario = $usuariosReservas->where('id', $reserva->idUsuario)->first();
                $reserva->usuario = $usuario;
                return $reserva;
            });
        }

        //filtre as reservas para pegar somente as reservas em que dataInicio maior ou igual a data atual
        $reservas3 = $reservas3->filter(function ($reserva) {
            $dataInicio = new DateTime($reserva->dataInicio);
            $dataInicio->setTimezone(new DateTimeZone('America/Sao_Paulo'));
            $dataAtual = new DateTime('now', new DateTimeZone('America/Sao_Paulo'));
            return $dataInicio >= $dataAtual;
        });

        $departmentUser = auth()->user()->department;
        $isCuritiba = false;
        if($departmentUser != "ERCSC"
        && $departmentUser != "ERMGA"
        && $departmentUser != "ERFCB"
        && $departmentUser != "ERGUA"
        && $departmentUser != "ERLDA"
        && $departmentUser != "ERPTG"){
            $isCuritiba = true;
        }

        $reservas3 = $reservas3->filter(function ($reserva) use ($departmentUser, $isCuritiba) {
            if($isCuritiba && $reserva->veiculo->codigoRegional == "CWB"){
                return true;
            }
            else if($reserva->usuario->department == $departmentUser){
                return true;
            }
            else{
                return false;
            }
        });

        //---------------------------------------------------------------------------------------------------------------------

        if($isPc=="sim"){
            
            $historico = new Historico();
            $timezone = new DateTimeZone('America/Sao_Paulo');
            $historico->dataOcorrencia = new DateTime('now', $timezone);
            $historico->tipoOcorrencia = "Atualização de cálculo de diárias";
            $historico->comentario = "Edição de Av na Prestação de Contas";
            $historico->perfilDonoComentario = "Usuário";
            $historico->usuario_id = $av->user_id;
            $historico->usuario_comentario_id = $user->id;
            $historico->av_id = $av->id;
    
            Av::findOrFail($avId)->update($dados);
            $historico->save();
    
            return redirect('/rotaspc/rotas/' . $avId )->with('msg', 'Cálculo salvo!');
        }
        else{
            $av->valorExtraReais = number_format($av->valorExtraReais, 2, ',', '.');
            $av->valorExtraReais = 'R$ ' . $av->valorExtraReais;

            $av->valorDeducaoReais = number_format($av->valorDeducaoReais, 2, ',', '.');
            $av->valorDeducaoReais = 'R$ ' . $av->valorDeducaoReais;

            return view('avs.concluir', ['av' => $av, 'objetivos' => $objetivos, 'veiculosProprios' => $veiculosProprios, 'user'=> $user, 'rotas' => $rotas,
            'diariaTotal' => $diariaTotal, 'meiaDiaria' => $meiaDiaria, 'mostrarValor' => $mostrarValor, 'diaChegadaFinal' => $diaChegadaFinal,
            'arrayDiasValores' => $arrayDiasValores, 'isInternacional' => $isInternacional, 'reservas2' => $reservas2, 'eventos' => $eventos, 
            'veiculos' => $veiculos, 'reservas3' => $reservas3]);
        }
    }

    public function buscarRotaAnterior($rota, $rotas){
        $rotaAnterior = null;
        for ($i=0; $i < sizeof($rotas) ; $i++) {
            if($rota->id == $rotas[$i]->id){
                $rotaAnterior = $rotas[$i-1];
                break;
            }
        }
        return $rotaAnterior;
    }

    //bucar rota posterior
    public function buscarRotaPosterior($rota, $rotas){
        $rotaPosterior = null;
        for ($i=0; $i < sizeof($rotas) ; $i++) {
            if($rota->id == $rotas[$i]->id){
                $rotaPosterior = $rotas[$i+1];
                break;
            }
        }
        return $rotaPosterior;
    }

    public function verificaValorRota($rota){
        if($rota->continenteDestinoInternacional == 1 && $rota->paisDestinoInternacional !=30){//América Latina ou Amética Central
            $valor = 100;
        }
        else if($rota->continenteDestinoInternacional == 2){//América do Norte
            $valor = 150;
        }
        else if($rota->continenteDestinoInternacional == 3){//Europa
            $valor = 180;
        }
        else if($rota->continenteDestinoInternacional == 4){//África
            $valor = 140;
        }
        else if($rota->continenteDestinoInternacional == 5){//Ásia
            $valor = 190;
        }else if(($rota->cidadeDestinoNacional == "Curitiba" || $rota->cidadeDestinoNacional == "Foz do Iguaçu") ||
        ($rota->paisDestinoInternacional == 30 && $rota->estadoDestinoInternacional == "Paraná" && 
        ($rota->cidadeDestinoInternacional == "Curitiba" || $rota->cidadeDestinoInternacional == "Foz do Iguaçu"))){//Se for Curitiba ou Foz do Iguaçu
            $valor = 65;
        }
        else if($rota->estadoDestinoNacional == "Paraná" || ($rota->paisDestinoInternacional == 30 && $rota->estadoDestinoInternacional == "Paraná")){//Se for outra cidade do Paraná
            $valor = 55;
        }
        else if($rota->cidadeDestinoNacional == "Brasília" || ($rota->paisDestinoInternacional == 30 && $rota->cidadeDestinoInternacional == "Brasília")){//Se for Brasília
            $valor = 100;
        }
        else{//Se não entrou em nenhum if, então é uma capital ou cidade de outros estados
            $valor = 80;
        }

        return $valor;
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
        $avs = $user->avs;

        $managerDN = $user->manager; // CN=Leandro Victorino Moura,OU=CTI,OU=Empregados,DC=prcidade,DC=br

        // Dividir a string em partes usando o caractere de vírgula como delimitador
        $parts = explode(',', $managerDN);

        // Extrair o nome do gerente da primeira parte
        $managerName = substr($parts[0], 3); // Remover os primeiros 3 caracteres "CN="

        $isCuritiba = false;

        if($user->department != "ERCSC" 
        && $user->department != "ERMGA" 
        && $user->department != "ERFCB" 
        && $user->department != "ERGUA" 
        && $user->department != "ERLDA" 
        && $user->department != "ERPTG"){
            $isCuritiba = true;
        }
        
        if ($user->dataAssinaturaTermo == null) {
            return view('termoResponsabilidade', ['user'=> $user]);
        }
        else{
            return view('welcome', ['avs' => $avs, 'user'=> $user, 'managerName' => $managerName, 'isCuritiba' => $isCuritiba]);
        }
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
                if($item->email_supervisor == $user->username){
                    array_push($filtro, $item);
                }
                else{
                    array_push($filtroTodos, $item);
                }
            }
        }

        //filtre o $medicoes e veja qual o id da av que está na tabela medicoes é igual a av que está sendo editada
        $medicoes = Medicao::all();
        $idAv = $av->id;
        $medicoesFiltradas = [];
        foreach ($medicoes as $medicao) {
            if($medicao->av_id == $idAv){
                array_push($medicoesFiltradas, $medicao);
            }
        }

        if($user->id != $av->user->id) {
            return redirect('/dashboard')->with('msg', 'Você não tem permissão para editar esta av!');
        }

        return view('avs.edit', ['av' => $av, 'objetivos' => $objetivos, 'veiculosProprios' => $veiculosProprios, 
        'user'=> $user, 'filtro' => $filtro, 'filtroTodos' => $filtroTodos, 'userAv' => $userAv, 'medicoesFiltradas' => $medicoesFiltradas]);
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

            if($item->email_supervisor == $user->username){
                array_push($filtro, $item);
            }
            else{
                array_push($filtroTodos, $item);
            }
            
        }

        //filtre o $medicoes e veja qual o id da av que está na tabela medicoes é igual a av que está sendo editada
        $medicoes = Medicao::all();
        $idAv = $av->id;
        $medicoesFiltradas = [];
        foreach ($medicoes as $medicao) {
            if($medicao->av_id == $idAv){
                array_push($medicoesFiltradas, $medicao);
            }
        }

        if($user->id != $av->user->id) {
            return redirect('/dashboard')->with('msg', 'Você não tem permissão para editar esta av!');
        }

        return view('avspc.edit', ['av' => $av, 'objetivos' => $objetivos, 'veiculosProprios' => $veiculosProprios, 'user'=> $user, 
        'filtro' => $filtro, 'filtroTodos' => $filtroTodos, 'userAv' => $userAv, 'medicoesFiltradas' => $medicoesFiltradas]);
    }

    public function enviarGestor(Request $request)
    {
        $av = Av::findOrFail($request->id);

        $valorExtraReaisFormatado = str_replace(',', '.', $request->valorExtraReais);
        $valorExtraReaisFormatado = str_replace('R$', '', $valorExtraReaisFormatado);
        $valorExtraReaisFormatado = str_replace(' ', '', $valorExtraReaisFormatado);

        $valorDeducaoReaisFormatado = str_replace(',', '.', $request->valorDeducaoReais);
        $valorDeducaoReaisFormatado = str_replace('R$', '', $valorDeducaoReaisFormatado);
        $valorDeducaoReaisFormatado = str_replace(' ', '', $valorDeducaoReaisFormatado);

        if($request->valorExtraDolar != null && $request->valorExtraDolar != ""){
            // Formatação para o valor extra em dólares
            $valorExtraDolarFormatado = str_replace(',', '', $request->valorExtraDolar);   // Remove a vírgula de separação de milhares
            $valorExtraDolarFormatado = str_replace('$', '', $valorExtraDolarFormatado);    // Remove o símbolo de dólar
            $valorExtraDolarFormatado = str_replace(' ', '', $valorExtraDolarFormatado);    // Remove espaços
        }
        else{
            $valorExtraDolarFormatado = 0;
        }

        if($request->valorDeducaoDolar != null && $request->valorDeducaoDolar != ""){
            // Formatação para o valor de dedução em dólares
            $valorDeducaoDolarFormatado = str_replace(',', '', $request->valorDeducaoDolar); // Remove a vírgula de separação de milhares
            $valorDeducaoDolarFormatado = str_replace('$', '', $valorDeducaoDolarFormatado); // Remove o símbolo de dólar
            $valorDeducaoDolarFormatado = str_replace(' ', '', $valorDeducaoDolarFormatado); // Remove espaços
        }
        else{
            $valorDeducaoDolarFormatado = 0;
        }

        $dados = array(
            "valorReais" => $av->valorReais,
            "valorDolar" => $av->valorDolar,
            "valorDeducaoReais" => $valorDeducaoReaisFormatado,
            "valorDeducaoDolar" => $valorDeducaoDolarFormatado,
            "valorExtraReais" => $valorExtraReaisFormatado,
            "valorExtraDolar" => $valorExtraDolarFormatado,
            "justificativaValorExtra"=>$request->justificativaValorExtra,
            "status"=>"AV aguardando aprovação do Gestor",
        );

        //se alguma rota for do tipo Carona, então adicione a priopriedade idReservaVeiculo em $dados
        $rotasEncontradas = $av->rotas;
        foreach ($rotasEncontradas as $r) {
            if($r->isOutroMeioTransporte == 2){
                $dados["idReservaVeiculo"] = $request->reservaVeiculo_id;
            }
        }

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
            ->send(new EnvioEmailGestor($user->id, $usermanager->id, $av->id));


        $isInternacional = false;
        foreach($rotasEncontradas as $r){
            if($r->isViagemInternacional == true){
                $isInternacional = true;
            }
        }

        if($isInternacional){
            $permission2 = Permission::where('name', 'aprov avs secretaria')->first();
            $permission3 = Permission::where('name', 'aprov avs financeiro')->first();

            $users = User::all();
            foreach($users as $u){
                try {
                    if($u->hasPermissionTo($permission2)){
                        if(
                        ($u->department != "ERCSC" 
                        && $u->department != "ERMGA" 
                        && $u->department != "ERFCB" 
                        && $u->department != "ERGUA" 
                        && $u->department != "ERLDA" 
                        && $u->department != "ERPTG")
                        ){
                            Mail::to($u->username)
                            ->send(new EnvioUsuarioToAdministrativoAvInternacionalAviso($user->id, $u->id, $av->id));
                        }
                    }
                    if($u->hasPermissionTo($permission3)){
                        if(
                        ($u->department != "ERCSC" 
                        && $u->department != "ERMGA" 
                        && $u->department != "ERFCB" 
                        && $u->department != "ERGUA" 
                        && $u->department != "ERLDA" 
                        && $u->department != "ERPTG")
                        ){
                            Mail::to($u->username)
                            ->send(new EnvioUsuarioToFinanceiroAvInternacionalAviso($user->id, $u->id, $av->id));
                        }
                    }
                } catch (\Throwable $th) {
                }
            }
        }

        Log::channel('email')->info("E-mail enviado para {$email}", [
            'user_id' => $user->id,
            'user_name' => $user->name,
            'usermanager_id' => $usermanager->id,
            'usermanager_name' => $usermanager->name,
            'av_id' => $av->id,
        ]);

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
            if($u->name == $managerName && $u->id == $user->id){
                array_push($usersFiltrados, $u);
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

        //$avs é um array, ordene pelo id em ordem decrescente
        usort($avs, function($a, $b) {
            return $b->id <=> $a->id;
        });
        
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
            if($u->name == $managerName && $u->id == $user->id){
                array_push($usersFiltrados, $u);
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
                    if($uf->department == "ERCSC" || $uf->department == "ERFCB"){
                        array_push($usersFiltrados, $uf);
                        $temFinanceiroCascavel = true;
                        $temFinanceiroFrancisco = true;
                    }
                }
            }
            else if($user->department == "ERMGA"){
                foreach($users as $uf){
                    if($uf->department == "ERMGA"){
                        array_push($usersFiltrados, $uf);
                        $temFinanceiroMaringa = true;
                    }
                }
            }
            // else if($user->department == "ERFCB"){
            //     foreach($users as $uf){
            //         if($uf->department == "ERFCB"){
            //             array_push($usersFiltrados, $uf);
            //         }
            //     }
            // }
            else if($user->department == "ERGUA"){
                foreach($users as $uf){
                    if($uf->department == "ERGUA"){
                        array_push($usersFiltrados, $uf);
                        $temFinanceiroGuarapuava = true;
                    }
                }
            }
            else if($user->department == "ERLDA"){
                foreach($users as $uf){
                    if($uf->department == "ERLDA"){
                        array_push($usersFiltrados, $uf);
                        $temFinanceiroLondrina = true;
                    }
                }
            }
            else if($user->department == "ERPTG"){
                foreach($users as $uf){
                    if($uf->department == "ERPTG"){
                        array_push($usersFiltrados, $uf);
                        $temFinanceiroPontaGrossa = true;
                    }
                }
            }
            else{
                $isFinanceiroCuritiba = true;
            }
        }

        $existeResponsavelFinanceiroCascavel = false;
        $existeResponsavelFinanceiroMaringa = false;
        $existeResponsavelFinanceiroFrancisco = false;
        $existeResponsavelFinanceiroGuarapuava = false;
        $existeResponsavelFinanceiroLondrina = false;
        $existeResponsavelFinanceiroPontaGrossa = false;
        
        if($isFinanceiroCuritiba == true){
            foreach($users as $uf){
                if($uf->department == "ERCSC"){
                    try {
                        if($uf->hasPermissionTo($permission)){
                            $existeResponsavelFinanceiroCascavel = true;
                            $existeResponsavelFinanceiroFrancisco = true;
                        }
                    } catch (\Throwable $th) {
                    }
                }
                else if($uf->department == "ERMGA"){
                    try {
                        if($uf->hasPermissionTo($permission)){
                            $existeResponsavelFinanceiroMaringa = true;
                        }
                    } catch (\Throwable $th) {
                    }
                }
                // else if($uf->department == "ERFCB"){
                //     try {
                //         if($uf->hasPermissionTo($permission)){
                //             $temFinanceiroFrancisco = true;
                //         }
                //     } catch (\Throwable $th) {
                //     }
                // }
                else if($uf->department == "ERGUA"){
                    try {
                        if($uf->hasPermissionTo($permission)){
                            $existeResponsavelFinanceiroGuarapuava = true;
                        }
                    } catch (\Throwable $th) {
                    }
                }
                else if($uf->department == "ERLDA"){
                    try {
                        if($uf->hasPermissionTo($permission)){
                            $existeResponsavelFinanceiroLondrina = true;
                        }
                    } catch (\Throwable $th) {
                    }
                }
                else if($uf->department == "ERPTG"){
                    try {
                        if($uf->hasPermissionTo($permission)){
                            $existeResponsavelFinanceiroPontaGrossa = true;
                        }
                    } catch (\Throwable $th) {
                    }
                }
            }
            
            $tempUsers = [];
            foreach($users as $uf){
                
                if($existeResponsavelFinanceiroCascavel == false){
                    if($uf->department == "ERCSC"){
                        array_push($usersFiltrados, $uf);
                    }
                }
                if($existeResponsavelFinanceiroMaringa == false){
                    if($uf->department == "ERMGA"){
                        array_push($usersFiltrados, $uf);
                    }
                }
                if($existeResponsavelFinanceiroFrancisco == false){
                    if($uf->department == "ERFCB"){
                        array_push($usersFiltrados, $uf);
                    }
                }
                if($existeResponsavelFinanceiroGuarapuava == false){
                    if($uf->department == "ERGUA"){
                        array_push($usersFiltrados, $uf);
                    }
                }
                if($existeResponsavelFinanceiroLondrina == false){
                    if($uf->department == "ERLDA"){
                        array_push($usersFiltrados, $uf);
                    }
                }
                if($existeResponsavelFinanceiroPontaGrossa == false){
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

        if($user->department != "ERCSC" &&
            $user->department != "ERMGA" &&
            $user->department != "ERFCB" &&
            $user->department != "ERGUA" &&
            $user->department != "ERLDA" &&
            $user->department != "ERPTG")
        {
            if(!$existeResponsavelFinanceiroCascavel){
                $temFinanceiroCascavel = true;
            }
            if(!$existeResponsavelFinanceiroMaringa){
                $temFinanceiroMaringa = true;
            }
            if(!$existeResponsavelFinanceiroFrancisco){
                $temFinanceiroFrancisco = true;
            }
            if(!$existeResponsavelFinanceiroGuarapuava){
                $temFinanceiroGuarapuava = true;
            }
            if(!$existeResponsavelFinanceiroLondrina){
                $temFinanceiroLondrina = true;
            }
            if(!$existeResponsavelFinanceiroPontaGrossa){
                $temFinanceiroPontaGrossa = true;
            }
        }

        $avsFiltradas = [];
        foreach($usersFiltrados as $uf){//Verifica todos os usuários
            
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

        $avs = $avsFiltradas;
        $objetivos = Objetivo::all();
        return view('avs.autFinanceiro', ['avs' => $avs, 'user'=> $user, 'objetivos' => $objetivos, 'users' => $users,
        'temFinanceiroCascavel' => $temFinanceiroCascavel, 'temFinanceiroMaringa' => $temFinanceiroMaringa, 'temFinanceiroFrancisco' => $temFinanceiroFrancisco,
        'temFinanceiroGuarapuava' => $temFinanceiroGuarapuava, 'temFinanceiroLondrina' => $temFinanceiroLondrina, 'temFinanceiroPontaGrossa' => $temFinanceiroPontaGrossa,
        'isFinanceiroCuritiba' => $isFinanceiroCuritiba, 'existeResponsavelFinanceiroCascavel' => $existeResponsavelFinanceiroCascavel, 'existeResponsavelFinanceiroMaringa' => $existeResponsavelFinanceiroMaringa,
        'existeResponsavelFinanceiroFrancisco' => $existeResponsavelFinanceiroFrancisco, 'existeResponsavelFinanceiroGuarapuava' => $existeResponsavelFinanceiroGuarapuava,
        'existeResponsavelFinanceiroLondrina' => $existeResponsavelFinanceiroLondrina, 'existeResponsavelFinanceiroPontaGrossa' => $existeResponsavelFinanceiroPontaGrossa]);
    }

    public function autPcFinanceiro(){
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
                    if($uf->department == "ERCSC" || $uf->department == "ERFCB"){
                        array_push($usersFiltrados, $uf);
                        $temFinanceiroCascavel = true;
                        $temFinanceiroFrancisco = true;
                    }
                }
            }
            else if($user->department == "ERMGA"){
                foreach($users as $uf){
                    if($uf->department == "ERMGA"){
                        array_push($usersFiltrados, $uf);
                        $temFinanceiroMaringa = true;
                    }
                }
            }
            // else if($user->department == "ERFCB"){
            //     foreach($users as $uf){
            //         if($uf->department == "ERFCB"){
            //             array_push($usersFiltrados, $uf);
            //         }
            //     }
            // }
            else if($user->department == "ERGUA"){
                foreach($users as $uf){
                    if($uf->department == "ERGUA"){
                        array_push($usersFiltrados, $uf);
                        $temFinanceiroGuarapuava = true;
                    }
                }
            }
            else if($user->department == "ERLDA"){
                foreach($users as $uf){
                    if($uf->department == "ERLDA"){
                        array_push($usersFiltrados, $uf);
                        $temFinanceiroLondrina = true;
                    }
                }
            }
            else if($user->department == "ERPTG"){
                foreach($users as $uf){
                    if($uf->department == "ERPTG"){
                        array_push($usersFiltrados, $uf);
                        $temFinanceiroPontaGrossa = true;
                    }
                }
            }
            else{
                $isFinanceiroCuritiba = true;
            }
        }
        $existeResponsavelFinanceiroCascavel = false;
        $existeResponsavelFinanceiroMaringa = false;
        $existeResponsavelFinanceiroFrancisco = false;
        $existeResponsavelFinanceiroGuarapuava = false;
        $existeResponsavelFinanceiroLondrina = false;
        $existeResponsavelFinanceiroPontaGrossa = false;
        
        if($isFinanceiroCuritiba == true){
            foreach($users as $uf){
                if($uf->department == "ERCSC"){
                    try {
                        if($uf->hasPermissionTo($permission)){
                            $existeResponsavelFinanceiroCascavel = true;
                            $existeResponsavelFinanceiroFrancisco = true;
                        }
                    } catch (\Throwable $th) {
                    }
                }
                else if($uf->department == "ERMGA"){
                    try {
                        if($uf->hasPermissionTo($permission)){
                            $existeResponsavelFinanceiroMaringa = true;
                        }
                    } catch (\Throwable $th) {
                    }
                }
                // else if($uf->department == "ERFCB"){
                //     try {
                //         if($uf->hasPermissionTo($permission)){
                //             $temFinanceiroFrancisco = true;
                //         }
                //     } catch (\Throwable $th) {
                //     }
                // }
                else if($uf->department == "ERGUA"){
                    try {
                        if($uf->hasPermissionTo($permission)){
                            $existeResponsavelFinanceiroGuarapuava = true;
                        }
                    } catch (\Throwable $th) {
                    }
                }
                else if($uf->department == "ERLDA"){
                    try {
                        if($uf->hasPermissionTo($permission)){
                            $existeResponsavelFinanceiroLondrina = true;
                        }
                    } catch (\Throwable $th) {
                    }
                }
                else if($uf->department == "ERPTG"){
                    try {
                        if($uf->hasPermissionTo($permission)){
                            $existeResponsavelFinanceiroPontaGrossa = true;
                        }
                    } catch (\Throwable $th) {
                    }
                }
            }
            
            foreach($users as $uf){
                if($existeResponsavelFinanceiroCascavel == false){
                    if($uf->department == "ERCSC"){
                        array_push($usersFiltrados, $uf);
                    }
                }
                if($existeResponsavelFinanceiroMaringa == false){
                    if($uf->department == "ERMGA"){
                        array_push($usersFiltrados, $uf);
                    }
                }
                if($existeResponsavelFinanceiroFrancisco == false){
                    if($uf->department == "ERFCB"){
                        array_push($usersFiltrados, $uf);
                    }
                }
                if($existeResponsavelFinanceiroGuarapuava == false){
                    if($uf->department == "ERGUA"){
                        array_push($usersFiltrados, $uf);
                    }
                }
                if($existeResponsavelFinanceiroLondrina == false){
                    if($uf->department == "ERLDA"){
                        array_push($usersFiltrados, $uf);
                    }
                }
                if($existeResponsavelFinanceiroPontaGrossa == false){
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
            
                foreach($uf->avs as $avAtual){//Percorre todas as Avs do usuário encontrado
                    if(($avAtual["isEnviadoUsuario"]==1 && $avAtual["isAprovadoGestor"]==true && $avAtual["isRealizadoReserva"]==true && $avAtual["isAprovadoFinanceiro"]==true
                    && $avAtual["isPrestacaoContasRealizada"]==true && $avAtual["isFinanceiroAprovouPC"]==false && $avAtual["isCancelado"]==false) || 
                    ($avAtual["isCancelado"]==true && $avAtual["isAprovadoFinanceiro"]==true && $avAtual["isPrestacaoContasRealizada"] == true 
                    && $avAtual["isFinanceiroAprovouPC"] == false)){ //Se a av dele já foi enviada e autorizada pelo Gestor, adiciona ao array de avs filtradas

                        array_push($avsFiltradas, $avAtual);
                    }
                }
            
        }
        $avs = $avsFiltradas;
        $objetivos = Objetivo::all();
        return view('avs.autPcFinanceiro', ['avs' => $avs, 'user'=> $user, 'objetivos' => $objetivos, 'users'=> $users]);
    }

    public function acertoContasFinanceiro(){
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
                    if($uf->department == "ERCSC" || $uf->department == "ERFCB"){
                        array_push($usersFiltrados, $uf);
                        $temFinanceiroCascavel = true;
                        $temFinanceiroFrancisco = true;
                    }
                }
            }
            else if($user->department == "ERMGA"){
                foreach($users as $uf){
                    if($uf->department == "ERMGA"){
                        array_push($usersFiltrados, $uf);
                        $temFinanceiroMaringa = true;
                    }
                }
            }
            // else if($user->department == "ERFCB"){
            //     foreach($users as $uf){
            //         if($uf->department == "ERFCB"){
            //             array_push($usersFiltrados, $uf);
            //         }
            //     }
            // }
            else if($user->department == "ERGUA"){
                foreach($users as $uf){
                    if($uf->department == "ERGUA"){
                        array_push($usersFiltrados, $uf);
                        $temFinanceiroGuarapuava = true;
                    }
                }
            }
            else if($user->department == "ERLDA"){
                foreach($users as $uf){
                    if($uf->department == "ERLDA"){
                        array_push($usersFiltrados, $uf);
                        $temFinanceiroLondrina = true;
                    }
                }
            }
            else if($user->department == "ERPTG"){
                foreach($users as $uf){
                    if($uf->department == "ERPTG"){
                        array_push($usersFiltrados, $uf);
                        $temFinanceiroPontaGrossa = true;
                    }
                }
            }
            else{
                $isFinanceiroCuritiba = true;
            }
        }
        $existeResponsavelFinanceiroCascavel = false;
        $existeResponsavelFinanceiroMaringa = false;
        $existeResponsavelFinanceiroFrancisco = false;
        $existeResponsavelFinanceiroGuarapuava = false;
        $existeResponsavelFinanceiroLondrina = false;
        $existeResponsavelFinanceiroPontaGrossa = false;
        
        if($isFinanceiroCuritiba == true){
            foreach($users as $uf){
                if($uf->department == "ERCSC"){
                    try {
                        if($uf->hasPermissionTo($permission)){
                            $existeResponsavelFinanceiroCascavel = true;
                            $existeResponsavelFinanceiroFrancisco = true;
                        }
                    } catch (\Throwable $th) {
                    }
                }
                else if($uf->department == "ERMGA"){
                    try {
                        if($uf->hasPermissionTo($permission)){
                            $existeResponsavelFinanceiroMaringa = true;
                        }
                    } catch (\Throwable $th) {
                    }
                }
                // else if($uf->department == "ERFCB"){
                //     try {
                //         if($uf->hasPermissionTo($permission)){
                //             $temFinanceiroFrancisco = true;
                //         }
                //     } catch (\Throwable $th) {
                //     }
                // }
                else if($uf->department == "ERGUA"){
                    try {
                        if($uf->hasPermissionTo($permission)){
                            $existeResponsavelFinanceiroGuarapuava = true;
                        }
                    } catch (\Throwable $th) {
                    }
                }
                else if($uf->department == "ERLDA"){
                    try {
                        if($uf->hasPermissionTo($permission)){
                            $existeResponsavelFinanceiroLondrina = true;
                        }
                    } catch (\Throwable $th) {
                    }
                }
                else if($uf->department == "ERPTG"){
                    try {
                        if($uf->hasPermissionTo($permission)){
                            $existeResponsavelFinanceiroPontaGrossa = true;
                        }
                    } catch (\Throwable $th) {
                    }
                }
            }
            
            foreach($users as $uf){
                if($existeResponsavelFinanceiroCascavel == false){
                    if($uf->department == "ERCSC"){
                        array_push($usersFiltrados, $uf);
                    }
                }
                if($existeResponsavelFinanceiroMaringa == false){
                    if($uf->department == "ERMGA"){
                        array_push($usersFiltrados, $uf);
                    }
                }
                if($existeResponsavelFinanceiroFrancisco == false){
                    if($uf->department == "ERFCB"){
                        array_push($usersFiltrados, $uf);
                    }
                }
                if($existeResponsavelFinanceiroGuarapuava == false){
                    if($uf->department == "ERGUA"){
                        array_push($usersFiltrados, $uf);
                    }
                }
                if($existeResponsavelFinanceiroLondrina == false){
                    if($uf->department == "ERLDA"){
                        array_push($usersFiltrados, $uf);
                    }
                }
                if($existeResponsavelFinanceiroPontaGrossa == false){
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
            
                foreach($uf->avs as $avAtual){//Percorre todas as Avs do usuário encontrado
                    if(($avAtual["isEnviadoUsuario"]==1 
                    && $avAtual["isAprovadoGestor"]==true 
                    && $avAtual["isRealizadoReserva"]==true 
                    && $avAtual["isAprovadoFinanceiro"]==true
                    && $avAtual["isPrestacaoContasRealizada"]==true 
                    && $avAtual["isFinanceiroAprovouPC"]==true 
                    && $avAtual["isGestorAprovouPC"]==true
                    && $avAtual["isAcertoContasRealizado"]==false 
                    && $avAtual["isCancelado"]==false) || 
                    
                    ($avAtual["isCancelado"]==true 
                    && $avAtual["isAprovadoFinanceiro"]==true 
                    && $avAtual["isPrestacaoContasRealizada"] == true 
                    && $avAtual["isFinanceiroAprovouPC"] == true 
                    && $avAtual["isGestorAprovouPC"] == true 
                    && $avAtual["isAcertoContasRealizado"] == false)){ //Se a av dele já foi enviada e autorizada pelo Gestor, adiciona ao array de avs filtradas
                        
                        array_push($avsFiltradas, $avAtual);
                    }
                }
            
        }
        $avs = $avsFiltradas;
        $objetivos = Objetivo::all();
        return view('avs.acertoContasFinanceiro', ['avs' => $avs, 'user'=> $user, 'objetivos' => $objetivos, 'users'=> $users]);
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
            if($managerName == $user->name && $u->id == $user->id){//Se o usuário for você mesmo
                array_push($usersFiltrados, $u);
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
        $data['isDiaria'] = true;

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

    public function geraArrayDiasValoresCerto($av){

        $rotas = $av->rotas;

        $dataInicio = date('Y-m-d', strtotime($rotas[0]->dataHoraSaida));
        $dataFim = date('Y-m-d', strtotime($rotas[sizeof($rotas)-1]->dataHoraChegada));
                       
        $arrayDiasValores = [];
                        
        $intervaloDatas = new DatePeriod(
            new DateTime($dataInicio),
            new DateInterval('P1D'),
            ($dataInicio != $dataFim ? (new DateTime($dataFim))->modify('+1 day') : (new DateTime($dataFim)))
        );

        //------------------------------------------------------------------------------------------------------------------------------------------------------
        
        foreach ($intervaloDatas as $data) {
            
            
            $dia = $data->format('Y-m-d');
            $valor = 0;
            $acumulado = 0;

            $rotasDoDia = [];
            $dataPrimeiraRota = null;
            $dataUltimaRota = null;

            //ITERE AS ROTAS E VERIFIQUE SE O DIA ESTÁ ENTRE A DATA DE SAÍDA E A DATA DE CHEGADA-----------------------------------
            for ($i=0; $i < sizeof($rotas) ; $i++) {
                    $dataSaida = DateTime::createFromFormat('Y-m-d H:i:s', $rotas[$i]->dataHoraSaida)->format('Y-m-d H:i:s');
                    $dataChegada = DateTime::createFromFormat('Y-m-d H:i:s', $rotas[$i]->dataHoraChegada)->format('Y-m-d H:i:s');

                    $dataSaidaFormatado = new DateTime($dataSaida);//Data de saída da rota 1
                    $dataChegadaFormatado = new DateTime($dataChegada);//Data de chegada da rota 1
            
                    //verifique se a data do dia está entre a data de saída e a data de chegada
                    if($dia >= $dataSaidaFormatado->format('Y-m-d') && $dia <= $dataChegadaFormatado->format('Y-m-d')){
                        $rotasDoDia[] = $rotas[$i];
                    }

                    //captura a data inicial da primeira rota
                    if($i == 0){
                        $dataPrimeiraRota = $dataSaidaFormatado->format('Y-m-d');
                    }
                    
                    //captura a data final da ultima rota
                    if($i == sizeof($rotas)-1){
                        $dataUltimaRota = $dataChegadaFormatado->format('Y-m-d');
                    }
            }//----------------------------------------------------------------------------------------------------------------------
            $arrayRotasDoDia = [];
            //ISSO TRATA SITUAÇÕES ONDE NÃO HÁ ROTA NO DIA, OU SEJA O DIA ESTÁ NO INTERVALO ENTRE DUAS VIAGENS-----------------------
            if(sizeof($rotasDoDia) == 0){
                //procure a rota em que a dataHoraChegada é anterior ao dia atual e atribua a $rota ao dia
                for ($i=sizeof($rotas)-1; $i >= 0 ; $i--) {
                    $dataChegada = DateTime::createFromFormat('Y-m-d H:i:s', $rotas[$i]->dataHoraChegada)->format('Y-m-d H:i:s');
                    $dataChegadaFormatado = new DateTime($dataChegada);
                    
                    if($dia > $dataChegadaFormatado->format('Y-m-d')){
                        $valor = $this->verificaValorRota($rotas[$i]);
                        $arrayRotasDoDia[] = $rotas[$i]->cidadeDestinoNacional;
                        break;
                    }
                }
            }//----------------------------------------------------------------------------------------------------------------------

            $temDiariaManha = false;
            $temDiariaTarde = false;
            $valorManha = 0;
            $valorTarde = 0;            

            //AGORA VAMOS ANALISAR OS DIAS QUE POSSUEM ROTAS E CALCULAR O VALOR DA DIÁRIA-----------------------------------------------------------------
            foreach($rotasDoDia as $indice => $rota){
                
                //DECLARAÇÃO DE VARIÁVEIS ------------------------------------------------------------------------------------

                //Captura o valor para a diária da rota atual
                $valor = $this->verificaValorRota($rota);

                //Captura a rota anterior e a próxima rota
                $rotaAnterior = isset($rotasDoDia[$indice - 1]) ? $rotasDoDia[$indice - 1] : false;
                $proximaRota = isset($rotasDoDia[$indice + 1]) ? $rotasDoDia[$indice + 1] : null;

                //Captura a data de saída e formata para DateTime
                $dataSaida = DateTime::createFromFormat('Y-m-d H:i:s', $rota->dataHoraSaida)->format('Y-m-d H:i:s');
                $dataSaidaFormatado = new DateTime($dataSaida);

                //Captura a data de chegada e formata para DateTime
                $dataChegada = DateTime::createFromFormat('Y-m-d H:i:s', $rota->dataHoraChegada)->format('Y-m-d H:i:s');
                $dataChegadaFormatado = new DateTime($dataChegada);

                //Monta um array com as rotas do dia de IDA
                if ($dataSaidaFormatado->format('Y-m-d') == $dia) {
                    $arrayRotasDoDia[] = "Ida: " . $rota->cidadeOrigemNacional . " (" . $dataSaidaFormatado->format('H:i') . ")" . " >";
                }

                //Monta um array com as rotas do dia de VOLTA
                if ($dataChegadaFormatado->format('Y-m-d') == $dia) {
                    $arrayRotasDoDia[] = $rota->cidadeDestinoNacional . " (" . $dataChegadaFormatado->format('H:i') . ")" . "";
                }

                //Captura a data de chegada da rota anterior e formata para DateTime
                if($rotaAnterior != false){
                    $rotaAnteriorDataChegada = DateTime::createFromFormat('Y-m-d H:i:s', $rotaAnterior->dataHoraChegada)->format('Y-m-d H:i:s');
                    $rotaAnteriorDataChegadaFormatado = new DateTime($rotaAnteriorDataChegada);
                }

                //Captura a data de saída e chegada da próxima rota e formata para DateTime
                if($proximaRota != false){
                    
                    $proximaRotaDataSaida = DateTime::createFromFormat('Y-m-d H:i:s', $proximaRota->dataHoraSaida)->format('Y-m-d H:i:s');
                    $proximaRotaDataSaidaFormatado = new DateTime($proximaRotaDataSaida);

                    $proximaRotaDataChegada = DateTime::createFromFormat('Y-m-d H:i:s', $proximaRota->dataHoraChegada)->format('Y-m-d H:i:s');
                    $proximaRotaDataChegadaFormatado = new DateTime($proximaRotaDataChegada);
                }

                //-----------------------------------------------------------------------------------------------------------
                
                //APENAS SE COINDICIR O DIA DA ROTA COM O DIAS DO INTERVALO, NOS DIAS INTEMEDIÁRIOS O VALOR DA DIÁRIA É O DA ÚLTIMA ROTA
                if($dataSaidaFormatado->format('Y-m-d') == $dia){
                    
                    if($temDiariaManha == false){

                        if( ($dataSaidaFormatado->format('H:i:s') <= "12:00:00" && $dataChegadaFormatado->format('H:i:s') >= "13:01:00") && $dia != $dataUltimaRota){
                        //SE A HORA DE SAÍDA FOR MENOR QUE 12:00 E A HORA DE CHEGADA FOR MAIOR QUE 13:01
                            $valorManha = $valor/2;
                            $temDiariaManha = true;
                        }
                        else if($dataSaidaFormatado->format('H:i:s') <= "12:00:00" && $dataChegadaFormatado->format('H:i:s') < "19:00:00"
                                && $proximaRota == false && $dia != $dataUltimaRota){
                        //SE A HORA DE SAÍDA FOR MENOR QUE 12:00 E A HORA DE CHEGADA FOR MENOR QUE 13:00 E NÃO TIVER PRÓXIMA ROTA NO DIA, MAS NÃO ACABOU A VIAGEM
                            $valorManha = $valor/2;
                            $temDiariaManha = true;
                        }
                        else if($proximaRota != false && $proximaRotaDataSaidaFormatado->format('Y-m-d') == $dia && 
                                $dataSaidaFormatado->format('H:i:s') <= "12:00:00" && $dia != $dataUltimaRota){
                        //SE A PRÓXIMA ROTA FOR NO MESMO DIA, A HORA DE SAÍDA DELA FOR MAIOR QUE 13:01 E NÃO FOR A ÚLTIMA ROTA
                            $valorManha = $valor/2;
                            $temDiariaManha = true;
                        }
                        else if($dia != $dataPrimeiraRota && $dia != $dataUltimaRota){
                        //SE O DIA ATUAL NÃO FOR O DIA DA PRIMEIRA ROTA E A HORA DE SAÍDA FOR MAIOR QUE 12:01
                            $rotaImediatamenteAnterior = $this->buscarRotaAnterior($rota, $rotas);
                            $valor = $this->verificaValorRota($rotaImediatamenteAnterior);
                            $valorManha = $valor/2;
                            $temDiariaManha = true;
                        }
                        else if($dia == $dataUltimaRota && $dataChegadaFormatado->format('H:i:s') >= "13:01:00"){
                        //SE O DIA ATUAL FOR O DIA DA ÚLTIMA ROTA E A HORA DE SAÍDA FOR MENOR QUE 12:00
                            try {
                                $rotaImediatamenteAnterior = $this->buscarRotaAnterior($rota, $rotas);
                                $valor = $this->verificaValorRota($rotaImediatamenteAnterior);
                            } catch (\Throwable $th) {
                                $valor = $this->verificaValorRota($rota);
                            }
                            $valorManha = $valor/2;
                            $temDiariaManha = true;
                        }
                    }

                    if($temDiariaTarde == false){
                        
                        if($dataSaidaFormatado->format('H:i:s') >= "13:01:00" && $dataSaidaFormatado->format('H:i:s') < "19:00:00" 
                            && $dataChegadaFormatado->format('H:i:s') >= "19:01:00" && $dia != $dataUltimaRota){
                        //SE A HORA DE SAÍDA FOR MAIOR QUE 13:01 E MENOR QUE 19:00 E A HORA DE CHEGADA FOR MAIOR QUE 19:01
                            $valorTarde = $valor/2;
                            $temDiariaTarde = true;
                        }
                        else if($proximaRota != false && $dia != $dataUltimaRota &&
                                ($proximaRotaDataSaidaFormatado->format('Y-m-d') == $dia && $proximaRotaDataSaidaFormatado->format('H:i:s') >= "19:01:00" ||
                                 $proximaRotaDataChegadaFormatado->format('Y-m-d') == $dia && $proximaRotaDataChegadaFormatado->format('H:i:s') >= "19:01:00")){
                        //SE A PRÓXIMA ROTA FOR NO MESMO DIA E A HORA DE SAÍDA OU CHEGADA DELA FOR MAIOR QUE 19:01
                            try {
                                $rotaImediatamenteAnterior = $this->buscarRotaPosterior($rota, $rotas);
                                $valor = $this->verificaValorRota($rotaImediatamenteAnterior);
                            } catch (\Throwable $th) {
                                $valor = $this->verificaValorRota($rota);
                            }
                            $valorTarde = $valor/2;
                            $temDiariaTarde = true;
                        }
                        else if($proximaRota == false && $dia != $dataUltimaRota && $dia == $dataChegadaFormatado->format('Y-m-d') && $dataChegadaFormatado->format('H:i:s') < "24:00:00"){
                        //NÃO TEM MAIS ROTAS NO DIA, SIGNFICA QUE JÁ CHEGOU E VAI FICAR NA CIDADE, A HORA DE CHEGADA É MENOR QUE 24:00, NÃO É A ÚLTIMA ROTA E CHEGOU NA CIDADE NO MESMO DIA
                            $valorTarde = $valor/2;
                            $temDiariaTarde = true;
                        }
                        else if($proximaRota == false && $dia != $dataUltimaRota && $dia != $dataChegadaFormatado->format('Y-m-d') && $dataSaidaFormatado->format('H:i:s') < "19:00:00"){
                        //NÃO TEM MAIS ROTAS NO DIA, SIGNFICA QUE JÁ CHEGOU E VAI FICAR NA CIDADE, A HORA DE SAÍDA É MENOR QUE 19:00, NÃO É A ÚLTIMA ROTA E A CHEGADA NO DESTINO VAI SER NO DIA SEGUINTE
                            $valorTarde = $valor/2;
                            $temDiariaTarde = true;
                        }
                        else if($proximaRota == false && $dia != $dataUltimaRota && $dia != $dataChegadaFormatado->format('Y-m-d') && $dia != $dataPrimeiraRota){
                        //NÃO TEM MAIS ROTAS NO DIA, SIGNFICA QUE JÁ CHEGOU E VAI FICAR NA CIDADE, NÃO É A ÚLTIMA ROTA E A CHEGADA NO DESTINO VAI SER NO DIA SEGUINTE E NÃO É A PRIMEIRA ROTA
                            $valorTarde = $valor/2;
                            $temDiariaTarde = true;
                        }
                        else if($dia == $dataUltimaRota && $dataChegadaFormatado->format('H:i:s') >= "19:01:00"){
                        //SE O DIA ATUAL FOR O DIA DA ÚLTIMA ROTA E A HORA DE CHEGADA FOR MAIOR QUE 19:01
                            try {
                                $rotaImediatamenteAnterior = $this->buscarRotaAnterior($rota, $rotas);
                                $valor = $this->verificaValorRota($rotaImediatamenteAnterior);
                            } catch (\Throwable $th) {
                                $valor = $this->verificaValorRota($rota);
                            }
                            $valorTarde = $valor/2;
                            $temDiariaTarde = true;
                        }
                    }

                    if($temDiariaManha == true && $temDiariaTarde == true)
                        $valor = $valorManha + $valorTarde;
                    else if($temDiariaManha == true && $temDiariaTarde == false)
                        $valor = $valorManha;
                    else if($temDiariaManha == false && $temDiariaTarde == true)
                        $valor = $valorTarde;
                    else if($temDiariaManha == false && $temDiariaTarde == false)
                        $valor = 0;
                }
                else if($dia == $dataUltimaRota && $dataSaidaFormatado->format('Y-m-d') != $dia){
                    if($dataChegadaFormatado->format('H:i:s') >= "13:01:00" && $dataChegadaFormatado->format('H:i:s') < "19:00:00"){
                        $valorManha = $valor/2;
                    }
                    else if($dataChegadaFormatado->format('H:i:s') >= "19:01:00"){
                        $valorTarde = $valor/2;
                    }
                    $valor = $valorManha + $valorTarde;
                }
            }

            if(sizeof($rotasDoDia) == 0 && $dia != $dataUltimaRota){
                $valorManha = $valor/2;
                $valorTarde = $valor/2;
            }
            //se o dia for diferente do primeiro e do ultimo
            if($dia != $dataPrimeiraRota && $dia != $dataUltimaRota && $valorManha == 0 && $valorTarde == 0 && $valor != 0){
                $valorManha = $valor/2;
                $valorTarde = $valor/2;
            }
            //se for o ultimo dia
            if($dia == $dataUltimaRota && $valorManha == 0 && $valorTarde == 0 && $valor != 0){
                $valor = 0;
            }
            
            $diaFormatado = DateTime::createFromFormat('Y-m-d', $dia);
            $arrayDiasValores[] = [
                'dia' => $diaFormatado->format('d'),
                'arrayRotasDoDia' => $arrayRotasDoDia,
                'valorManha' => $valorManha,
                'valorTarde' => $valorTarde,
                'valor' => $valor,
            ];
        }

        //Se a viagem for somente no mesmo dia
        if(iterator_count($intervaloDatas) == 0){

            //Captura a data de $intervaloData e atribua a variável $data
            $data = new DateTime($dataInicio);

            $dia = $data->format('Y-m-d');
            $valor = 0;
            $acumulado = 0;

            $rotasDoDia = [];
            $dataPrimeiraRota = null;
            $dataUltimaRota = null;

            //ITERE AS ROTAS E VERIFIQUE SE O DIA ESTÁ ENTRE A DATA DE SAÍDA E A DATA DE CHEGADA-----------------------------------
            for ($i=0; $i < sizeof($rotas) ; $i++) {
                    $dataSaida = DateTime::createFromFormat('Y-m-d H:i:s', $rotas[$i]->dataHoraSaida)->format('Y-m-d H:i:s');
                    $dataChegada = DateTime::createFromFormat('Y-m-d H:i:s', $rotas[$i]->dataHoraChegada)->format('Y-m-d H:i:s');

                    $dataSaidaFormatado = new DateTime($dataSaida);//Data de saída da rota 1
                    $dataChegadaFormatado = new DateTime($dataChegada);//Data de chegada da rota 1
            
                    //verifique se a data do dia está entre a data de saída e a data de chegada
                    if($dia >= $dataSaidaFormatado->format('Y-m-d') && $dia <= $dataChegadaFormatado->format('Y-m-d')){
                        $rotasDoDia[] = $rotas[$i];
                    }

                    //captura a data inicial da primeira rota
                    if($i == 0){
                        $dataPrimeiraRota = $dataSaidaFormatado->format('Y-m-d');
                    }
                    
                    //captura a data final da ultima rota
                    if($i == sizeof($rotas)-1){
                        $dataUltimaRota = $dataChegadaFormatado->format('Y-m-d');
                    }
            }//----------------------------------------------------------------------------------------------------------------------
            
            $arrayRotasDoDia = [];
            //ISSO TRATA SITUAÇÕES ONDE NÃO HÁ ROTA NO DIA, OU SEJA O DIA ESTÁ NO INTERVALO ENTRE DUAS VIAGENS-----------------------
            if(sizeof($rotasDoDia) == 0){
                //procure a rota em que a dataHoraChegada é anterior ao dia atual e atribua a $rota ao dia
                for ($i=sizeof($rotas)-1; $i >= 0 ; $i--) {
                    $dataChegada = DateTime::createFromFormat('Y-m-d H:i:s', $rotas[$i]->dataHoraChegada)->format('Y-m-d H:i:s');
                    $dataChegadaFormatado = new DateTime($dataChegada);
                    
                    if($dia > $dataChegadaFormatado->format('Y-m-d')){
                        $valor = $this->verificaValorRota($rotas[$i]);
                        $arrayRotasDoDia[] = $rotas[$i]->cidadeDestinoNacional;
                        break;
                    }
                }
            }//----------------------------------------------------------------------------------------------------------------------

            $temDiariaManha = false;
            $temDiariaTarde = false;
            $valorManha = 0;
            $valorTarde = 0;
            

            //AGORA VAMOS ANALISAR OS DIAS QUE POSSUEM ROTAS E CALCULAR O VALOR DA DIÁRIA-----------------------------------------------------------------
            foreach($rotasDoDia as $indice => $rota){
                
                //DECLARAÇÃO DE VARIÁVEIS ------------------------------------------------------------------------------------

                //Captura o valor para a diária da rota atual
                $valor = $this->verificaValorRota($rota);

                //Captura a rota anterior e a próxima rota
                $rotaAnterior = isset($rotasDoDia[$indice - 1]) ? $rotasDoDia[$indice - 1] : false;
                $proximaRota = isset($rotasDoDia[$indice + 1]) ? $rotasDoDia[$indice + 1] : null;

                //Captura a data de saída e formata para DateTime
                $dataSaida = DateTime::createFromFormat('Y-m-d H:i:s', $rota->dataHoraSaida)->format('Y-m-d H:i:s');
                $dataSaidaFormatado = new DateTime($dataSaida);

                //Captura a data de chegada e formata para DateTime
                $dataChegada = DateTime::createFromFormat('Y-m-d H:i:s', $rota->dataHoraChegada)->format('Y-m-d H:i:s');
                $dataChegadaFormatado = new DateTime($dataChegada);

                //Monta um array com as rotas do dia de IDA
                if ($dataSaidaFormatado->format('Y-m-d') == $dia) {
                    $arrayRotasDoDia[] = "Ida: " . $rota->cidadeOrigemNacional . " (" . $dataSaidaFormatado->format('H:i') . ")" . " >";
                }

                //Monta um array com as rotas do dia de VOLTA
                if ($dataChegadaFormatado->format('Y-m-d') == $dia) {
                    $arrayRotasDoDia[] = $rota->cidadeDestinoNacional . " (" . $dataChegadaFormatado->format('H:i') . ")" . "";
                }

                //Captura a data de chegada da rota anterior e formata para DateTime
                if($rotaAnterior != false){
                    $rotaAnteriorDataChegada = DateTime::createFromFormat('Y-m-d H:i:s', $rotaAnterior->dataHoraChegada)->format('Y-m-d H:i:s');
                    $rotaAnteriorDataChegadaFormatado = new DateTime($rotaAnteriorDataChegada);

                    $rotaAnteriorDataSaida = DateTime::createFromFormat('Y-m-d H:i:s', $rotaAnterior->dataHoraSaida)->format('Y-m-d H:i:s');
                    $rotaAnteriorDataSaidaFormatado = new DateTime($rotaAnteriorDataSaida);
                }

                //Captura a data de saída e chegada da próxima rota e formata para DateTime
                if($proximaRota != false){
                    
                    $proximaRotaDataSaida = DateTime::createFromFormat('Y-m-d H:i:s', $proximaRota->dataHoraSaida)->format('Y-m-d H:i:s');
                    $proximaRotaDataSaidaFormatado = new DateTime($proximaRotaDataSaida);

                    $proximaRotaDataChegada = DateTime::createFromFormat('Y-m-d H:i:s', $proximaRota->dataHoraChegada)->format('Y-m-d H:i:s');
                    $proximaRotaDataChegadaFormatado = new DateTime($proximaRotaDataChegada);
                }

                //-----------------------------------------------------------------------------------------------------------

                //APENAS SE COINDICIR O DIA DA ROTA COM O DIAS DO INTERVALO, NOS DIAS INTEMEDIÁRIOS O VALOR DA DIÁRIA É O DA ÚLTIMA ROTA
                if($dataSaidaFormatado->format('Y-m-d') == $dia){

                    if($temDiariaManha == false){

                        if( ($dataSaidaFormatado->format('H:i:s') <= "12:00:00" && $dataChegadaFormatado->format('H:i:s') >= "13:01:00") && $dia != $dataUltimaRota){
                        //SE A HORA DE SAÍDA FOR MENOR QUE 12:00 E A HORA DE CHEGADA FOR MAIOR QUE 13:01
                            $valorManha = $valor/2;
                            $temDiariaManha = true;
                        }
                        else if($dataSaidaFormatado->format('H:i:s') <= "12:00:00" && $dataChegadaFormatado->format('H:i:s') < "19:00:00"
                                && $proximaRota == false && $dia != $dataUltimaRota){
                        //SE A HORA DE SAÍDA FOR MENOR QUE 12:00 E A HORA DE CHEGADA FOR MENOR QUE 13:00 E NÃO TIVER PRÓXIMA ROTA NO DIA, MAS NÃO ACABOU A VIAGEM
                            $valorManha = $valor/2;
                            $temDiariaManha = true;
                        }
                        else if($proximaRota != false && $proximaRotaDataSaidaFormatado->format('Y-m-d') == $dia && 
                                $dataSaidaFormatado->format('H:i:s') <= "12:00:00" && $dia != $dataUltimaRota){
                        //SE A PRÓXIMA ROTA FOR NO MESMO DIA, A HORA DE SAÍDA DELA FOR MAIOR QUE 13:01 E NÃO FOR A ÚLTIMA ROTA
                            $valorManha = $valor/2;
                            $temDiariaManha = true;
                        }
                        else if($dia != $dataPrimeiraRota && $dia != $dataUltimaRota){
                        //SE O DIA ATUAL NÃO FOR O DIA DA PRIMEIRA ROTA E A HORA DE SAÍDA FOR MAIOR QUE 12:01
                            $rotaImediatamenteAnterior = $this->buscarRotaAnterior($rota, $rotas);
                            $valor = $this->verificaValorRota($rotaImediatamenteAnterior);
                            $valorManha = $valor/2;
                            $temDiariaManha = true;
                        }
                        else if($dia == $dataUltimaRota && $dataSaidaFormatado->format('H:i:s') <= "12:00:00" 
                                && $dataChegadaFormatado->format('H:i:s') >= "13:01:00"){
                        //SE O DIA ATUAL FOR O DIA DA ÚLTIMA ROTA E A HORA DE SAÍDA FOR MENOR QUE 12:00
                            try {
                                $rotaImediatamenteAnterior = $this->buscarRotaAnterior($rota, $rotas);
                                $valor = $this->verificaValorRota($rotaImediatamenteAnterior);
                            } catch (\Throwable $th) {
                                $valor = $this->verificaValorRota($rota);
                            }
                            
                            $valorManha = $valor/2;
                            $temDiariaManha = true;
                        }
                        else if($dia == $dataUltimaRota && $dataSaidaFormatado->format('H:i:s') <= "12:00:00" 
                                && $proximaRota != false && $proximaRotaDataChegadaFormatado->format('H:i:s') >= "13:01:00"){
                        //SE O DIA ATUAL FOR O DIA DA ÚLTIMA ROTA E A HORA DE SAÍDA FOR MENOR QUE 12:00
                            try {
                                $rotaImediatamenteAnterior = $this->buscarRotaAnterior($rota, $rotas);
                                $valor = $this->verificaValorRota($rotaImediatamenteAnterior);
                            } catch (\Throwable $th) {
                                $valor = $this->verificaValorRota($rota);
                            }
                            
                            $valorManha = $valor/2;
                            $temDiariaManha = true;
                        }
                        else if ($dia == $dataUltimaRota && $proximaRota == false && 
                        $rotaAnteriorDataChegadaFormatado->format('H:i:s') <= "13:01:00" &&
                        $rotaAnteriorDataSaidaFormatado->format('H:i:s') <= "13:01:00" &&
                        $dataChegadaFormatado->format('H:i:s') >= "13:01:00" ){
                            //SE O DIA ATUAL FOR O DIA DA ÚLTIMA ROTA E A ROTA ANTERIOR TIVER HORÁRIO DE SAÍDA OU CHEGADA ANTES DE 13:01 E A DATA DE CHEGADA DA ÚLTIMA ROTA FOR DEPOIS DE 13:01
                            try {
                                $rotaImediatamenteAnterior = $this->buscarRotaAnterior($rota, $rotas);
                                $valor = $this->verificaValorRota($rotaImediatamenteAnterior);
                            } catch (\Throwable $th) {
                                $valor = $this->verificaValorRota($rota);
                            }
                            $valorManha = $valor / 2;
                            $temDiariaManha = true;
                        }
                    }

                    if($temDiariaTarde == false){
                        
                        if($dataSaidaFormatado->format('H:i:s') >= "13:01:00" && $dataSaidaFormatado->format('H:i:s') < "19:00:00" 
                            && $dataChegadaFormatado->format('H:i:s') >= "19:01:00" && $dia != $dataUltimaRota){
                        //SE A HORA DE SAÍDA FOR MAIOR QUE 13:01 E MENOR QUE 19:00 E A HORA DE CHEGADA FOR MAIOR QUE 19:01
                            $valorTarde = $valor/2;
                            $temDiariaTarde = true;
                        }
                        else if($proximaRota != false && $dia != $dataUltimaRota &&
                                ($proximaRotaDataSaidaFormatado->format('Y-m-d') == $dia && $proximaRotaDataSaidaFormatado->format('H:i:s') >= "19:01:00" ||
                                 $proximaRotaDataChegadaFormatado->format('Y-m-d') == $dia && $proximaRotaDataChegadaFormatado->format('H:i:s') >= "19:01:00")){
                        //SE A PRÓXIMA ROTA FOR NO MESMO DIA E A HORA DE SAÍDA OU CHEGADA DELA FOR MAIOR QUE 19:01
                            try {
                                $rotaImediatamenteAnterior = $this->buscarRotaPosterior($rota, $rotas);
                                $valor = $this->verificaValorRota($rotaImediatamenteAnterior);
                            } catch (\Throwable $th) {
                                $valor = $this->verificaValorRota($rota);
                            }
                            $valorTarde = $valor/2;
                            $temDiariaTarde = true;
                        }
                        else if($proximaRota == false && $dia != $dataUltimaRota && $dataChegadaFormatado->format('H:i:s') < "24:00:00"){
                        //NÃO TEM MAIS ROTAS NO DIA, SIGNFICA QUE JÁ CHEGOU E VAI FICAR NA CIDADE
                            $valorTarde = $valor/2;
                            $temDiariaTarde = true;
                        }
                        else if($dia == $dataUltimaRota && $dataChegadaFormatado->format('H:i:s') >= "19:01:00"){
                        //SE O DIA ATUAL FOR O DIA DA ÚLTIMA ROTA E A HORA DE CHEGADA FOR MAIOR QUE 19:01
                            try {
                                $rotaImediatamenteAnterior = $this->buscarRotaAnterior($rota, $rotas);
                                $valor = $this->verificaValorRota($rotaImediatamenteAnterior);
                            } catch (\Throwable $th) {
                                $valor = $this->verificaValorRota($rota);
                            }
                            $valorTarde = $valor/2;
                            $temDiariaTarde = true;
                        }
                    }

                    if($temDiariaManha == true && $temDiariaTarde == true)
                        $valor = $valorManha + $valorTarde;
                    else if($temDiariaManha == true && $temDiariaTarde == false)
                        $valor = $valorManha;
                    else if($temDiariaManha == false && $temDiariaTarde == true)
                        $valor = $valorTarde;
                    else if($temDiariaManha == false && $temDiariaTarde == false)
                        $valor = 0;
                }
                else if($dia == $dataUltimaRota && $dataSaidaFormatado->format('Y-m-d') != $dia){
                    if($dataChegadaFormatado->format('H:i:s') >= "13:01:00" && $dataChegadaFormatado->format('H:i:s') < "19:00:00"){
                        $valorManha = $valor/2;
                    }
                    else if($dataChegadaFormatado->format('H:i:s') >= "19:01:00"){
                        $valorTarde = $valor/2;
                    }
                    $valor = $valorManha + $valorTarde;
                }

            }

            if(sizeof($rotasDoDia) == 0 && $dia != $dataUltimaRota){
                $valorManha = $valor/2;
                $valorTarde = $valor/2;
            }
            //se o dia for diferente do primeiro e do ultimo
            if($dia != $dataPrimeiraRota && $dia != $dataUltimaRota && $valorManha == 0 && $valorTarde == 0 && $valor != 0){
                $valorManha = $valor/2;
                $valorTarde = $valor/2;
            }
            //se for o ultimo dia
            if($dia == $dataUltimaRota && $valorManha == 0 && $valorTarde == 0 && $valor != 0){
                $valor = 0;
            }
            
            $diaFormatado = DateTime::createFromFormat('Y-m-d', $dia);
            $arrayDiasValores[] = [
                'dia' => $diaFormatado->format('d'),
                'arrayRotasDoDia' => $arrayRotasDoDia,
                'valorManha' => $valorManha,
                'valorTarde' => $valorTarde,
                'valor' => $valor,
            ];
        }
        //CASO SÓ TENHA UMA ROTA
        if(sizeof($rotas) == 1){
            $valor = $this->verificaValorRota($rotas[0]);
            $arrayRotasDoDia = [];
            $arrayRotasDoDia[] = " [ " . $rotas[0]->cidadeOrigemNacional . " - " . $rotas[0]->cidadeDestinoNacional . " ] ";
            $arrayDiasValores[] = [
                'dia' => DateTime::createFromFormat('Y-m-d H:i:s', $rotas[0]->dataHoraSaida)->format('d'),
                'arrayRotasDoDia' => $arrayRotasDoDia,
                'valorManha' => $valor/2,
                'valorTarde' => $valor/2,
                'valor' => $valor,
            ];
        }

        return $arrayDiasValores;
    }
}
