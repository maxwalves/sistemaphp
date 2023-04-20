<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Av;
use App\Models\Objetivo;
use App\Models\VeiculoProprio;
use App\Models\VeiculoParanacidade;
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

        return view('welcome', ['avs' => $avs, 'search' => $search]);
    }

    public function avs()
    {
        $user = auth()->user();
        $avs = $user->avs;
        return view('avs.avs', ['avs' => $avs]);
    }

    public function create()
    {
        $user = auth()->user();
        $veiculosProprios = $user->veiculosProprios;

        $objetivos = Objetivo::all();
        $veiculosParanacidade = VeiculoParanacidade::all(); // Fazer filtro para apresentar somentes os ativos
        return view('avs.create', ['objetivos' => $objetivos, 'veiculosProprios' => $veiculosProprios, 'veiculosParanacidade' => $veiculosParanacidade]);
    }

    public function store(Request $request)
    {
        $regras = [
            'objetivo_id' => 'required',
            'prioridade' => 'required',
            'isVeiculoProprio' => 'required',
        ];

        if($request->isVeiculoProprio=="1"){ // Se for veículo próprio, adiciona validação de campo
            $regras += ['veiculoProprio_id' => 'required'];
        }

        $mensagens = [
            'required' => 'Este campo não pode estar em branco',
        ];
       
        $av = new Av();

        if ($request->isSelecionado) //Se existir outro objetivo, remove a necessidade de validação de objetivo
        {
            $av->objetivo_id = $request->objetivo_id;
            unset($regras['objetivo_id']); //Retira a regra de validação de objetivo

            $regras += ['outroObjetivo' => 'required']; //Adiciona a validação de outro objetivo
        }

        $request->validate($regras, $mensagens);
        
        $av->prioridade = $request->prioridade;

        //Validação para salvar no banco de dados, ou é veículo próprio ou da empresa, ou nenhum
        if($request->isVeiculoProprio==1){
            $av->isVeiculoProprio = 1;
            $av->isVeiculoEmpresa = 0;
            $av->veiculoProprio_id = $request->veiculoProprio_id;
        }else if($request->isVeiculoEmpresa==1){
            $av->isVeiculoProprio = 0;
            $av->isVeiculoEmpresa = 1;
            $av->veiculoParanacidade_id = $request->veiculoParanacidade_id;
        } else if($request->isVeiculoProprio==0 && $request->isVeiculoEmpresa==0){
            $av->isVeiculoProprio = 0;
            $av->isVeiculoEmpresa = 0;
        }//-----------------------------------------------------------------
        
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

        return redirect('/')->with('msg', 'AV criada com sucesso!');
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
 
        return view('avs.show', ['av' => $av, 'objetivo' => $objetivo, 'veiculoProprio' => $veiculoProprio]);
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

        return view('avs.edit', ['av' => $av, 'objetivos' => $objetivos, 'veiculosProprios' => $veiculosProprios]);
    }

    public function update(Request $request)
    {
        
        if ($request->isSelecionado) //Se existir outro objetivo, remove a necessidade de validação de objetivo
        {
            $request->objetivo_id->input('null');
        }
        

        $data = $request->all();

        Av::findOrFail($request->id)->update($data);

        return redirect('/avs/avs')->with('msg', 'av editado com sucesso!');
    }
}
