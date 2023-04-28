<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Av;
use App\Models\Objetivo;
use App\Models\VeiculoProprio;
use App\Models\VeiculoParanacidade;
use App\Models\Rota;
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
        return view('avs.avs', ['avs' => $avs, 'user'=> $user]);
    }

    public function create()
    {
        $user = auth()->user();
        $veiculosProprios = $user->veiculosProprios;

        $objetivos = Objetivo::all();
        $veiculosParanacidade = VeiculoParanacidade::all(); // Fazer filtro para apresentar somentes os ativos
        return view('avs.create', ['objetivos' => $objetivos, 'veiculosProprios' => $veiculosProprios, 'veiculosParanacidade' => $veiculosParanacidade, 'user'=> $user]);
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

        //Valor cálculo de rota e verificar quanto que terá que pagar ao usuário
        $diariaTotal = 0;
        $meiaDiaria = 0;

        $valorReais = 0;
        $valorDolar = 0;
        $teste =[];
        
        foreach ($rotas as $r){

            if($r->isViagemInternacional ==1)
            {
                if($r->continenteDestinoInternacional == 1){
                    $diariaTotal = 100;
                    $meiaDiaria = 50;
                }
                else if($r->continenteDestinoInternacional == 2){
                    $diariaTotal = 150;
                    $meiaDiaria = 75;
                }
                else if($r->continenteDestinoInternacional == 3){
                    $diariaTotal = 180;
                    $meiaDiaria = 90;
                }
                else if($r->continenteDestinoInternacional == 4){
                    $diariaTotal = 140;
                    $meiaDiaria = 70;
                }
                else if($r->continenteDestinoInternacional == 5){
                    $diariaTotal = 190;
                    $meiaDiaria = 95;
                }
            }
            //Implementar o valor para as cidades no Brasil


            $date1 = new DateTime($r->dataHoraSaida);
            $date2 = new DateTime($r->dataHoraChegada);

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
                    $valorReais += $meiaDiaria;
                }
                else if($horaSaida >= 13 && $horaChegada >= 19){ //Se sair depois das 13 e chegar após 19
                    $valorReais += $meiaDiaria;
                }
                else if($horaSaida < 12 && $horaChegada >= 19){ // Se sair antes de 12 e chegar após 19
                    $valorReais += $diariaTotal;
                }
            }

            if($diaSaida != $diaChegada){ // Sair e chegar em dia diferente
            
                if($horaSaida <12){
                    $valorReais += $diariaTotal;//Se no primeiro dia ele sair antes de 12 já ganha diária total
                }
                else if($horaSaida >= 13){
                    $valorReais += $meiaDiaria;//Se no primeiro dia ele sair após as 13, ganha meia diária
                }
                //Roda o laço a partir do segundo dia até o penúltimo
                for($i = $diaSaida+1; $i < $diaChegada ; $i++){
                    $valorReais += $diariaTotal; //Acrescenta uma diária completa para cada dia intermediário
                }
                if($horaChegada < 13){ //Se no último dia a chegada for antes das 13, recebe meia diária
                    $valorReais += $meiaDiaria;
                }
                else if($horaChegada >=19){ // Se no último dia a chegada for após as 19, recebe diária inteira
                    $valorReais += $diariaTotal;
                }
            
            }

            $teste += ['Data saída: ' => $valorReais];
            $teste += ['Data chegada: ' => $anoChegada. "/" .$mesChegada. "/" .$diaChegada. "/" .$horaChegada. "/" .$minutoChegada. "/" .$segundoChegada];
            
        }

        //dd($teste);

        $veiculosProprios = $user->veiculosProprios;

        if($user->id != $av->user->id) {
            return redirect('/dashboard')->with('msg', 'Você não tem permissão para editar esta av!');
        }

        return view('avs.concluir', ['av' => $av, 'objetivos' => $objetivos, 'veiculosProprios' => $veiculosProprios, 'user'=> $user, 'rotas' => $rotas]);
    }

    public function show($id)
    {
        $av = Av::findOrFail($id);

        $user = auth()->user();
        $veiculosProprios = $user->veiculosProprios;
        $objetivo = Objetivo::findOrFail($av->objetivo_id);
    
        try {
            $veiculoProprio = VeiculoProprio::findOrFail($av->veiculoProprio_id);
        } catch (\Throwable $th) {
            $veiculoProprio = VeiculoProprio::all();
        }
 
        return view('avs.show', ['av' => $av, 'objetivo' => $objetivo, 'veiculoProprio' => $veiculoProprio, 'user'=> $user]);
    }

    public function dashboard()
    {
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

        return view('avs.dashboard', ['avs' => $avs], ['search' => $search]);
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
