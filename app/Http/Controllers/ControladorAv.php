<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Av;
use App\Models\Objetivo;
use App\Models\VeiculoProprio;
use App\Models\VeiculoParanacidade;
use App\Models\Rota;
use App\Models\User;
use App\Models\Historico;
use DateTime;

class ControladorAv extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $avs = $user->avs;

        $search = request('search');

        if ($search) {
            $avs = $avs::where([
                ['title', 'like', '%'.$search. '%']
            ])->get();
        }

        return view('welcome', ['avs' => $avs, 'search' => $search, 'user'=> $user]);
    }

    public function avs()
    {
        $user = auth()->user();
        $avs = $user->avs;
        $objetivos = Objetivo::all();
        return view('avs.avs', ['avs' => $avs, 'user'=> $user, 'objetivos' => $objetivos]);
    }

    public function create()
    {
        $user = auth()->user();
        $veiculosProprios = $user->veiculosProprios;

        $objetivos = Objetivo::all();
        $veiculosParanacidade = VeiculoParanacidade::all(); // Fazer filtro para apresentar somentes os ativos
        return view('avs.create', ['objetivos' => $objetivos, 'veiculosProprios' => $veiculosProprios, 'veiculosParanacidade' => $veiculosParanacidade, 'user'=> $user]);
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
        $users = User::all();
        $historicos = [];
        $user = auth()->user();
        $usersFiltrados = [];
        $possoEditar = false;

        $av = Av::findOrFail($id);

        foreach ($users as $u){//Percorre todos os usuários do sistema
            if($u->setor_id == $user->setor_id && $u->id != $user->id){//Verifica se cada um pertence ao seu time, exceto vc mesmo
                array_push($usersFiltrados, $u);//Adiciona ao array filtrado o usuário encontrado
            }
        }
        foreach ($usersFiltrados as $u){//Percorre todos os usuários do sistema
            if($av->user_id == $u->id){//Verifica se o usuário do da AV atual pertence ao meu seu time
                $possoEditar = true;
            }
        }

        foreach($historicosTodos as $historico){
            if($historico->av_id == $av->id){
                array_push($historicos, $historico);
            }
        }

        $veiculosProprios = $user->veiculosProprios;

        if($possoEditar == true){
            return view('avs.verFluxoGestor', ['av' => $av, 'objetivos' => $objetivos, 'veiculosProprios' => $veiculosProprios, 'user'=> $user, 'historicos'=> $historicos, 'users'=> $users]);
        }
        else{
            return redirect('/dashboard')->with('msg', 'Você não tem permissão para avaliar esta av!');
        }
        
    }

    public function verFluxoDiretoria($id){
        $objetivos = Objetivo::all();
        $historicosTodos = Historico::all();
        $users = User::all();
        $historicos = [];
        $user = auth()->user();
        $usersFiltrados = [];
        $possoEditar = false;

        $av = Av::findOrFail($id);

        foreach ($users as $u){//Percorre todos os usuários do sistema
            if($u->setor_id == $user->setor_id && $u->id != $user->id){//Verifica se cada um pertence ao seu time, exceto vc mesmo
                array_push($usersFiltrados, $u);//Adiciona ao array filtrado o usuário encontrado
            }
        }
        foreach ($usersFiltrados as $u){//Percorre todos os usuários do sistema
            if($av->user_id == $u->id){//Verifica se o usuário do da AV atual pertence ao meu seu time
                $possoEditar = true;
            }
        }

        foreach($historicosTodos as $historico){
            if($historico->av_id == $av->id){
                array_push($historicos, $historico);
            }
        }

        $veiculosProprios = $user->veiculosProprios;

        if($possoEditar == true){
            return view('avs.verFluxoDiretoria', ['av' => $av, 'objetivos' => $objetivos, 'veiculosProprios' => $veiculosProprios, 'user'=> $user, 'historicos'=> $historicos, 'users'=> $users]);
        }
        else{
            return redirect('/dashboard')->with('msg', 'Você não tem permissão para avaliar esta av!');
        }
    }

    public function verDetalhesAv($id){

        $objetivos = Objetivo::all();
        $historicosTodos = Historico::all();
        $historicos = [];

        $users = User::all();

        $user = auth()->user();

        $av = Av::findOrFail($id);

        foreach($historicosTodos as $historico){
            if($historico->av_id == $av->id){
                array_push($historicos, $historico);
            }
        }

        $user = auth()->user();
        $veiculosProprios = $user->veiculosProprios;

        return view('avs.verDetalhesAv', ['av' => $av, 'objetivos' => $objetivos, 'veiculosProprios' => $veiculosProprios, 'user'=> $user, 'historicos'=> $historicos, 'users'=> $users]);

    }

    public function gestorAprovarAv(Request $request){

        $user = auth()->user();
        $av = Av::findOrFail($request->get('id'));

        $isDiretoria = false;
        $dados = [];

        $historico = new Historico();
        $historico->dataOcorrencia = new DateTime();
        $historico->tipoOcorrencia = "Aprovado pelo Gestor";
        $historico->comentario = $request->get('comentario');
        $historico->perfilDonoComentario = "Gestor";
        $historico->usuario_id = $av->user_id;
        $historico->usuario_comentario_id = $user->id;
        $historico->av_id = $av->id;
        

        foreach($av->rotas as $rota){
            if($rota["isViagemInternacional"]==1 || $rota["isVeiculoProprio"]==1){
                $isDiretoria = true;
            }
        }
        
        if($isDiretoria==true){
            $dados = array(
                "isAprovadoGestor" => 1,
                "status" => "Aguardando aprovação Diretoria Executiva",
            );
        }
        else{
            $dados = array(
                "isAprovadoGestor" => 1,
                "status" => "Aguardando reserva pela Secretaria"
            );
        }

        Av::findOrFail($av->id)->update($dados);
        $historico->save();


        return redirect('/avs/autGestor')->with('msg', 'AV aprovada!');
    }

    public function gestorReprovarAv(Request $request){
        $av = Av::findOrFail($request->get('id'));
        $avs = Av::all();
        $user = auth()->user();

        $historico = new Historico();
        $historico->dataOcorrencia = new DateTime();
        $historico->tipoOcorrencia = "Reprovado pelo Gestor";
        $historico->comentario = $request->get('comentario');
        $historico->perfilDonoComentario = "Gestor";
        $historico->usuario_id = $av->user_id;
        $historico->usuario_comentario_id = $user->id;
        $historico->av_id = $av->id;

        $dados = array(
            "isEnviadoUsuario" => 0,
            "isAprovadoGestor" => 0,
            "status" => "Aguardando envio para o Gestor"
        );

        Av::findOrFail($av->id)->update($dados);
        $historico->save();

        return redirect('/avs/autGestor')->with('msg', 'AV reprovada!');
    }

    public function diretoriaAprovarAv(Request $request){

        $user = auth()->user();
        $av = Av::findOrFail($request->get('id'));
        
        $avs = Av::all();
        $isInternacional = false;
        $isCarroProprio = false;
        $dados = [];

        $historico = new Historico();
        $historico->dataOcorrencia = new DateTime();
        $historico->tipoOcorrencia = "Aprovado pela Diretoria";
        $historico->comentario = $request->get('comentario');
        $historico->perfilDonoComentario = "Diretoria Executiva";
        $historico->usuario_id = $av->user_id;
        $historico->usuario_comentario_id = $user->id;
        $historico->av_id = $av->id;
        
        foreach($avs as $avAtual){
            foreach($avAtual->rotas as $rota){
                if($rota["isViagemInternacional"]==1){
                    $isInternacional = true;
                }
                else if($rota["isVeiculoProprio"]==1){
                    $isCarroProprio = true;
                }
            }
        }

        $dados = array(
            "isAprovadoGestor" => 1,
            "isVistoDiretoria" => 1,
            "status" => "Aguardando reserva pela Secretaria",
            "isAprovadoViagemInternacional" => "0",
            "isAprovadoCarroDiretoriaExecutiva" => "0",
        );
        if($isInternacional==true){
            $dados['isAprovadoViagemInternacional'] = 1;
        }
        else if($isCarroProprio == true){
            $dados['isAprovadoCarroDiretoriaExecutiva'] = 1;
        }

        Av::findOrFail($av->id)->update($dados);
        $historico->save();


        return redirect('/avs/autDiretoria')->with('msg', 'AV aprovada!');
    }

    public function diretoriaReprovarAv(Request $request){
        $av = Av::findOrFail($request->get('id'));
        $avs = Av::all();
        $user = auth()->user();

        $historico = new Historico();
        $historico->dataOcorrencia = new DateTime();
        $historico->tipoOcorrencia = "Reprovado pela Diretoria";
        $historico->comentario = $request->get('comentario');
        $historico->perfilDonoComentario = "Diretoria Executiva";
        $historico->usuario_id = $av->user_id;
        $historico->usuario_comentario_id = $user->id;
        $historico->av_id = $av->id;

        $dados = array(
            "isEnviadoUsuario" => 0,
            "isAprovadoGestor" => 0,
            "status" => "Aguardando envio para o Gestor"
        );

        Av::findOrFail($av->id)->update($dados);
        $historico->save();

        return redirect('/avs/autDiretoria')->with('msg', 'AV reprovada!');
    }

    public function store(Request $request)
    {
        $regras = [
            'objetivo_id' => 'required',
            'prioridade' => 'required'
        ];

        $mensagens = [
            'required' => 'Este campo não pode estar em branco',
        ];
       
        $av = new Av();

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
        
        $av->prioridade = $request->prioridade;
        
        $av->dataCriacao = new DateTime();
        $av->banco = $request->banco;
        $av->agencia = $request->agencia;
        $av->conta = $request->conta;
        $av->pix = $request->pix;
        $av->comentario = $request->comentario;
        $av->status = "Aguardando envio para o Gestor";
        $av->outroObjetivo = $request->outroObjetivo;

        $user = auth()->user();
        $av->user_id = $user->id;
    
        $av->save();

        return redirect('/rotas/rotas/' . $av->id)->with('msg', 'AV criada com sucesso!');
        //return view('rotas.createRota', ['av' => $av]);
    }
    public function concluir($id){
        $objetivos = Objetivo::all();

        $user = auth()->user();

        $av = Av::findOrFail($id);
        $rotas = $av->rotas;//Busca as rotas da AV

        //Valor do cálculo de rota e verificar quanto que terá que pagar ao usuário
        $diariaTotal = 0;
        $meiaDiaria = 0;

        $valorReais = 0;
        $valorDolar = 0;
        $teste =[];

        $diasCompletos =0;


        if(sizeof($rotas)==1){//Se tem apenas uma rota

            if($rotas[0]->isViagemInternacional ==1) //Se a viagem for internacional, seta o valor de acordo com o continente
            {
                if($rotas[0]->continenteDestinoInternacional == 1){//América Latina ou Amética Central
                    $diariaTotal = 100;
                    $meiaDiaria = 50;
                }
                else if($rotas[0]->continenteDestinoInternacional == 2){//América do Norte
                    $diariaTotal = 150;
                    $meiaDiaria = 75;
                }
                else if($rotas[0]->continenteDestinoInternacional == 3){//Europa
                     $diariaTotal = 180;
                    $meiaDiaria = 90;
                }
                else if($rotas[0]->continenteDestinoInternacional == 4){//África
                    $diariaTotal = 140;
                    $meiaDiaria = 70;
                }
                else if($rotas[0]->continenteDestinoInternacional == 5){//Ásia
                    $diariaTotal = 190;
                    $meiaDiaria = 95;
                }
            }

            if($rotas[0]->isViagemInternacional ==0)//Se a viagem não for internacional
            {
                if($rotas[0]->cidadeDestinoNacional == "Curitiba" || $rotas[0]->cidadeDestinoNacional == "Foz do Iguaçu"){//Se for Curitiba ou Foz do Iguaçu
                    $diariaTotal = 65;
                    $meiaDiaria = 32.5;
                }
                else if($rotas[0]->estadoDestinoNacional == "Parana"){//Se for outra cidade do Paraná
                    $diariaTotal = 55;
                    $meiaDiaria = 27.5;
                }
                else if($rotas[0]->cidadeDestinoNacional == "Brasília"){//Se for Brasília
                    $diariaTotal = 100;
                    $meiaDiaria = 50;
                }
                else{//Se não entrou em nenhum if, então é uma capital ou cidade de outros estados
                    $diariaTotal = 80;
                    $meiaDiaria = 40;
                }
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
                    if($rotas[0]->isViagemInternacional ==0) {$valorReais += $meiaDiaria;}
                    if($rotas[0]->isViagemInternacional ==1) {$valorDolar += $meiaDiaria;}
                }
                else if($horaSaida >= 13 && $horaChegada >= 19){ //Se sair depois das 13 e chegar após 19
                    if($rotas[0]->isViagemInternacional ==0) {$valorReais += $meiaDiaria;}
                    if($rotas[0]->isViagemInternacional ==1) {$valorDolar += $meiaDiaria;}
                }
                else if($horaSaida < 12 && $horaChegada >= 19){ // Se sair antes de 12 e chegar após 19
                    if($rotas[0]->isViagemInternacional ==0) {$valorReais += $diariaTotal;}
                    if($rotas[0]->isViagemInternacional ==1) {$valorDolar += $diariaTotal;}
                }
            }
    
            if($diaSaida != $diaChegada){ // Sair e chegar em dia diferente
             
                if($horaSaida <12){//Se no primeiro dia ele sair antes de 12 já ganha diária total
                    if($rotas[0]->isViagemInternacional ==0) {$valorReais += $diariaTotal;}
                    if($rotas[0]->isViagemInternacional ==1) {$valorDolar += $diariaTotal;}
                }
                else if($horaSaida >= 13){//Se no primeiro dia ele sair após as 13, ganha meia diária
                    if($rotas[0]->isViagemInternacional ==0) {$valorReais += $meiaDiaria;}
                    if($rotas[0]->isViagemInternacional ==1) {$valorDolar += $meiaDiaria;}
                }
                 
                //Roda o laço a partir do segundo dia até o penúltimo
                for($i = $diaSaida+1; $i < $diaChegada ; $i++){//Acrescenta uma diária completa para cada dia intermediário
                    if($rotas[0]->isViagemInternacional ==0) {$valorReais += $diariaTotal;}
                    if($rotas[0]->isViagemInternacional ==1) {$valorDolar += $diariaTotal;}
                }
                
                if($horaChegada < 13){ //Se no último dia a chegada for antes das 13, recebe meia diária
                    if($rotas[0]->isViagemInternacional ==0) {$valorReais += $meiaDiaria;}
                    if($rotas[0]->isViagemInternacional ==1) {$valorDolar += $meiaDiaria;}
                }
                else if($horaChegada >= 13 && $horaChegada < 19){
                    if($rotas[0]->isViagemInternacional ==0) {$valorReais += $meiaDiaria;}
                    if($rotas[0]->isViagemInternacional ==1) {$valorDolar += $meiaDiaria;}
                }
                else if($horaChegada >=19){ // Se no último dia a chegada for após as 19, recebe diária inteira
                    if($rotas[0]->isViagemInternacional ==0) {$valorReais += $diariaTotal;}
                    if($rotas[0]->isViagemInternacional ==1) {$valorDolar += $diariaTotal;}
                }
            
            }
            
        }
        else if(sizeof($rotas)>1){//Se existir mais de uma rota
            
            for ($i=0; $i < sizeof($rotas)-1 ; $i++) { 

                if($rotas[$i]->isViagemInternacional ==1) //Se a viagem for internacional, seta o valor de acordo com o continente
                {
                    if($rotas[$i]->continenteDestinoInternacional == 1){//América Latina ou Amética Central
                        $diariaTotal = 100;
                        $meiaDiaria = 50;
                    }
                    else if($rotas[$i]->continenteDestinoInternacional == 2){//América do Norte
                        $diariaTotal = 150;
                        $meiaDiaria = 75;
                    }
                    else if($rotas[$i]->continenteDestinoInternacional == 3){//Europa
                        $diariaTotal = 180;
                        $meiaDiaria = 90;
                    }
                    else if($rotas[$i]->continenteDestinoInternacional == 4){//África
                        $diariaTotal = 140;
                        $meiaDiaria = 70;
                    }
                    else if($rotas[$i]->continenteDestinoInternacional == 5){//Ásia
                        $diariaTotal = 190;
                        $meiaDiaria = 95;
                    }
                }

                if($rotas[$i]->isViagemInternacional ==0)//Se a viagem não for internacional
                {
                    if($rotas[$i]->cidadeDestinoNacional == "Curitiba" || $rotas[0]->cidadeDestinoNacional == "Foz do Iguaçu"){//Se for Curitiba ou Foz do Iguaçu
                        $diariaTotal = 65;
                        $meiaDiaria = 32.5;
                    }
                    else if($rotas[$i]->estadoDestinoNacional == "Parana"){//Se for outra cidade do Paraná
                        $diariaTotal = 55;
                        $meiaDiaria = 27.5;
                    }
                    else if($rotas[$i]->cidadeDestinoNacional == "Brasília"){//Se for Brasília
                        $diariaTotal = 100;
                        $meiaDiaria = 50;
                    }
                    else{//Se não entrou em nenhum if, então é uma capital ou cidade de outros estados
                        $diariaTotal = 80;
                        $meiaDiaria = 40;
                    }
                }

                //Verifica quais são das datas da rota atual e da próxima
                $dataHoraSaidaRota1 = new DateTime($rotas[$i]->dataHoraSaida);//Data de saída da rota 1
                $dataHoraChegadaRota1 = new DateTime($rotas[$i]->dataHoraChegada);//Data de chegada da rota 1

                $dataHoraSaidaRota2 = new DateTime($rotas[$i + 1]->dataHoraSaida);//Data de saída da rota 2
                $dataHoraChegadaRota2 = new DateTime($rotas[$i + 1]->dataHoraChegada);//Data de chegada da rota 2
                
                //DATAS ROTA 1
                $diaSaidaRota1 = $dataHoraSaidaRota1->format('d');
                $horaSaidaRota1 = $dataHoraSaidaRota1->format('H');

                $diaChegadaRota1 = $dataHoraChegadaRota1->format('d');
                $horaChegadaRota1 = $dataHoraChegadaRota1->format('H');

                //DATAS ROTA 2
                $diaSaidaRota2 = $dataHoraSaidaRota2->format('d');
                $horaSaidaRota2 = $dataHoraSaidaRota2->format('H');

                $diaChegadaRota2 = $dataHoraChegadaRota2->format('d');
                $horaChegadaRota2 = $dataHoraChegadaRota2->format('H');
                    
                //CÁLCULOS:

                //A partir do próximo dia após a chegada da rota 1, conta até o último dia antes da partida da rota 2
                $diasCompletos = ($diaSaidaRota2)-($diaChegadaRota1+1);  
                
                if($rotas[$i]->isViagemInternacional ==0) {$valorReais += ($diariaTotal * $diasCompletos) ;}
                if($rotas[$i]->isViagemInternacional ==1) {$valorDolar += ($diariaTotal * $diasCompletos);}
                
                
                if($diaSaidaRota1==$diaChegadaRota1){// Se a viagem de ida durar um dia
                    
                    if($horaSaidaRota1 < 12){//Se sair antes de 12h 
                        if($rotas[$i]->isViagemInternacional ==0) {$valorReais += $diariaTotal;}
                        if($rotas[$i]->isViagemInternacional ==1) {$valorDolar += $diariaTotal;}
                    }
                    else if($horaSaidaRota1 >=13){//Sair depois das 13h e chegar depois das 19h
                        if($rotas[$i]->isViagemInternacional ==0) {$valorReais += $meiaDiaria;}
                        if($rotas[$i]->isViagemInternacional ==1) {$valorDolar += $meiaDiaria;}
                    }
                } 
                else if($diaSaidaRota1!=$diaChegadaRota1){//Se a viagem de ida demorar mais de um dia
                    
                    if($horaSaidaRota1 < 12){//Se sair antes de 12
                        if($rotas[$i]->isViagemInternacional ==0) {$valorReais += $diariaTotal;}
                        if($rotas[$i]->isViagemInternacional ==1) {$valorDolar += $diariaTotal;}
                    }
                    else if($horaSaidaRota1 >=13 && $horaSaidaRota1 <19){
                        if($rotas[$i]->isViagemInternacional ==0) {$valorReais += $meiaDiaria;}
                        if($rotas[$i]->isViagemInternacional ==1) {$valorDolar += $meiaDiaria;}
                    }

                    for($j = $diaSaidaRota1; $j < $diaChegadaRota1 ; $j++){//Acrescenta uma diária completa para cada dia intermediário
                        if($rotas[$i]->isViagemInternacional ==0) {$valorReais += $diariaTotal;}
                        if($rotas[$i]->isViagemInternacional ==1) {$valorDolar += $diariaTotal;}
                    }
                    
                }

                //Soma o período antes do início de uma nova rota, caso complete meia diária ou total
                if($horaSaidaRota2 >= 13){
                    if($rotas[$i]->isViagemInternacional ==0) {$valorReais += $meiaDiaria;}
                    if($rotas[$i]->isViagemInternacional ==1) {$valorDolar += $meiaDiaria;}
                }
                else if($horaSaidaRota2 >= 19){
                    if($rotas[$i]->isViagemInternacional ==0) {$valorReais += $diariaTotal;}
                    if($rotas[$i]->isViagemInternacional ==1) {$valorDolar += $diariaTotal;}
                }
                

                if($i+1 >= sizeof($rotas)-1){//Se estou na última rota
                    if($diaSaidaRota2==$diaChegadaRota2){// Se a viagem da rota 2 durar um dia
                        if($horaSaidaRota2 < 12 && $horaChegadaRota2 >=19){
                            if($rotas[$i]->isViagemInternacional ==0) {$valorReais += $diariaTotal;}
                            if($rotas[$i]->isViagemInternacional ==1) {$valorDolar += $diariaTotal;}
                        }
                        else if($horaSaidaRota2 >=13 && $horaChegadaRota2 >=19){
                            if($rotas[$i]->isViagemInternacional ==0) {$valorReais += $meiaDiaria;}
                            if($rotas[$i]->isViagemInternacional ==1) {$valorDolar += $meiaDiaria;}
                        } 
                        else if($horaSaidaRota2 < 12 && $horaChegadaRota2 >= 13 && $horaChegadaRota2 < 19){
                            if($rotas[$i]->isViagemInternacional ==0) {$valorReais += $meiaDiaria;}
                            if($rotas[$i]->isViagemInternacional ==1) {$valorDolar += $meiaDiaria;}
                        }
                        
                    } 
                    else if($diaSaidaRota2!=$diaChegadaRota2){//Se a viagem da rota 2 demorar mais de um dia
    
                        if($horaSaidaRota2 < 12){//Se sair antes de 12
                            if($rotas[$i]->isViagemInternacional ==0) {$valorReais += $diariaTotal;}
                            if($rotas[$i]->isViagemInternacional ==1) {$valorDolar += $diariaTotal;}
                        }
                        else if($horaSaidaRota2 >=13){
                            if($rotas[$i]->isViagemInternacional ==0) {$valorReais += $meiaDiaria;}
                            if($rotas[$i]->isViagemInternacional ==1) {$valorDolar += $meiaDiaria;}
                        }
    
                        for($j = $diaSaidaRota2+1; $j < $diaChegadaRota2 ; $j++){//Acrescenta uma diária completa para cada dia intermediário
                            if($rotas[$i]->isViagemInternacional ==0) {$valorReais += $diariaTotal;}
                            if($rotas[$i]->isViagemInternacional ==1) {$valorDolar += $diariaTotal;}
                        }
    
                        if($horaChegadaRota2 >=13 && $horaChegadaRota2 < 19){
                            if($rotas[$i]->isViagemInternacional ==0) {$valorReais += $meiaDiaria;}
                            if($rotas[$i]->isViagemInternacional ==1) {$valorDolar += $meiaDiaria;}
                        }
                        else if($horaChegadaRota2 >= 19){ // Se no último dia da Rota 1 a chegada for após as 19, recebe diária inteira
                            if($rotas[$i]->isViagemInternacional ==0) {$valorReais += $diariaTotal;}
                            if($rotas[$i]->isViagemInternacional ==1) {$valorDolar += $diariaTotal;}
                        }
                    }
                }
            }
        }
        //dd($valorDolar);

        //$teste += ['Data saída: ' => $valorReais];
        //$teste += ['Data chegada: ' => $anoChegada. "/" .$mesChegada. "/" .$diaChegada. "/" .$horaChegada. "/" .$minutoChegada. "/" .$segundoChegada];
            
        

        //dd($teste);

        $av->valorReais = $valorReais;
        $av->valorDolar = $valorDolar;

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

        return view('avs.concluir', ['av' => $av, 'objetivos' => $objetivos, 'veiculosProprios' => $veiculosProprios, 'user'=> $user, 'rotas' => $rotas]);
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

        return view('avs.dashboard', ['avs' => $avs], ['search' => $search, 'user'=> $user]);
    }

    public function destroy($id)
    {
        $av = Av::findOrFail($id)->delete();

        return redirect('/avs/avs')->with('msg', 'av excluído com sucesso!');
    }

    public function edit($id)
    {
        $objetivos = Objetivo::all();

        $user = auth()->user();

        $av = Av::findOrFail($id);

        $user = auth()->user();
        $veiculosProprios = $user->veiculosProprios;

        if($user->id != $av->user->id) {
            return redirect('/dashboard')->with('msg', 'Você não tem permissão para editar esta av!');
        }

        return view('avs.edit', ['av' => $av, 'objetivos' => $objetivos, 'veiculosProprios' => $veiculosProprios, 'user'=> $user]);
    }

    public function enviarGestor(Request $request)
    {
        $dados = array(
            "valorExtraReais" => $request->valorExtraReais,
            "valorExtraDolar" => $request->valorExtraDolar,
            "justificativaValorExtra"=>$request->justificativaValorExtra,
            "status"=>"AV aguardando aprovação do Gestor"
        );

        $av = Av::findOrFail($request->id);
        $user = auth()->user();

        $historico = new Historico();
        $historico->dataOcorrencia = new DateTime();
        $historico->tipoOcorrencia = "Aguardando avaliação do Gestor";
        $historico->comentario = "Envio de AV para gestor";
        $historico->perfilDonoComentario = "Usuário";
        $historico->usuario_id = $av->user_id;
        $historico->usuario_comentario_id = $user->id;
        $historico->av_id = $av->id;

        $dados["isEnviadoUsuario"] = 1;

        //dd($dados);

        Av::findOrFail($request->id)->update($dados);
        $historico->save();

        return redirect('/avs/avs')->with('msg', 'AV enviada ao gestor!');
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
        $historico->dataOcorrencia = new DateTime();
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
        $users = User::all();
        $usersFiltrados = [];
        foreach ($users as $u){//Percorre todos os usuários do sistema
            if($u->setor_id == $user->setor_id && $u->id != $user->id){//Verifica se cada um pertence ao seu time, exceto vc mesmo
                array_push($usersFiltrados, $u);//Adiciona ao array filtrado o usuário encontrado
            }
        }
        
        $avsFiltradas = [];
        foreach($usersFiltrados as $uf){//Verifica todos os usuários encontrados
            foreach($uf->avs as $av){//Percorre todas as Avs do usuário encontrado
                if($av["isEnviadoUsuario"]==1 && $av["isAprovadoGestor"]==false){ //Se a av dele já foi enviada, mas ainda não autorizada, adiciona ao array de avs filtradas
                    
                    array_push($avsFiltradas, $av);
                }
            }
        }
        $avs = $avsFiltradas;
        $objetivos = Objetivo::all();
        return view('avs.autGestor', ['avs' => $avs, 'user'=> $user, 'objetivos' => $objetivos]);
    }

    public function autDiretoria(){
        $user = auth()->user();
        $avs = Av::all();
        $users = User::all();

        $avsFiltradas = [];
        foreach($users as $uf){//Verifica todos os usuários
            if($uf->id != $user->id){
                foreach($uf->avs as $avAtual){//Percorre todas as Avs do usuário encontrado
                    if($avAtual["isEnviadoUsuario"]==1 && $avAtual["isAprovadoGestor"]==true && $avAtual["isVistoDiretoria"]==false){ //Se a av dele já foi enviada e autorizada pelo Gestor, adiciona ao array de avs filtradas
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
        return view('avs.autDiretoria', ['avs' => $avs, 'user'=> $user, 'objetivos' => $objetivos]);
    }

    public function autSecretaria(){
        $user = auth()->user();
        $avs = Av::all();
        $users = User::all();

        $avsFiltradas = [];
        foreach($users as $uf){//Verifica todos os usuários
            if($uf->id != $user->id){//Se  o usuário não for você
                foreach($uf->avs as $avAtual){//Percorre todas as Avs do usuário encontrado
                    if($avAtual["isEnviadoUsuario"]==1 && $avAtual["isAprovadoGestor"]==true){ //Se a av dele já foi enviada e autorizada pelo Gestor, adiciona ao array de avs filtradas
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
        return view('avs.autSecretaria', ['avs' => $avs, 'user'=> $user, 'objetivos' => $objetivos]);
    }
    

    public function update(Request $request)
    {

        $regras = [
            'objetivo_id' => 'required',
            'outroObjetivo' => 'required',
            'prioridade' => 'required',
            'isVeiculoProprio' => 'required',
            'isVeiculoEmpresa' => 'required',
        ];

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
        
        if ($request->get('isSelecionado')=="1") //Se existir outro objetivo, remove a necessidade de validação de objetivo
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

        Av::findOrFail($request->id)->update($data);

        return redirect('/avs/avs')->with('msg', 'av editado com sucesso!');
    }
}
