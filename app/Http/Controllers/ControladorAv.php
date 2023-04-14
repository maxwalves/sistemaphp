<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Av;
use App\Models\Objetivo;

class ControladorAv extends Controller
{
    public function indexView(){
        $avs = Av::all();
        $objetivos = Objetivo::all();
        return view('avs', compact(['avs', 'objetivos']));
    }

    public function index()
    {
        $avs = Av::all();
        //$cats = Categoria::all();
        //return view('produtos', compact(['prods', 'cats']));
        return $avs->toJson();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //Verificar se está sendo utilizado
        //Lista os objetivos para carregar na View de AV
        $objetivos = Objetivo::all();
        return view('novaav', compact('objetivos'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $av = new Av();
        $av->dataCriacao = $request->input('dataCriacao');
        $av->prioridade = $request->input('prioridade');
        $av->banco = $request->input('banco');
        $av->agencia = $request->input('agencia');
        $av->conta = $request->input('conta');
        $av->pix = $request->input('pix');
        $av->comentario = $request->input('comentario');
        $av->status = $request->input('status');
        $av->valorExtra = $request->input('valorExtra');
        $av->justificativaValorExtra = $request->input('justificativaValorExtra');
        $av->conta = $request->input('isVeiculoProprio');
        $av->conta = $request->input('isVeiculoEmpresa');
        $av->conta = $request->input('contatos');
        $av->conta = $request->input('atividades');
        $av->conta = $request->input('conclusoes');

        $av->user_id  = $request->input('user_id');
        $av->objetivo_id  = $request->input('objetivo_id');

        $av->save();
        return json_encode($av);
        //return redirect('produtos');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $av = Av::find($id);
        if(isset($av)) {
            return json_encode($av);
        }
        else {
            return response('Av não encontrada', 404);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $objetivos = Objetivo::all();
        $av = Av::find($id);
        if(isset($prod)) {
            return view('editarav', compact(['av', 'objetivos']));
        }
        else {
            abort(404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $av = Av::find($id);
        if(isset($av)) {
            $av->dataCriacao = $request->input('dataCriacao');
            $av->prioridade = $request->input('prioridade');
            $av->banco = $request->input('banco');
            $av->agencia = $request->input('agencia');
            $av->conta = $request->input('conta');
            $av->pix = $request->input('pix');
            $av->comentario = $request->input('comentario');
            $av->status = $request->input('status');
            $av->valorExtra = $request->input('valorExtra');
            $av->justificativaValorExtra = $request->input('justificativaValorExtra');
            $av->conta = $request->input('isVeiculoProprio');
            $av->conta = $request->input('isVeiculoEmpresa');
            $av->conta = $request->input('contatos');
            $av->conta = $request->input('atividades');
            $av->conta = $request->input('conclusoes');

            $av->user_id  = $request->input('user_id');
            $av->objetivo_id  = $request->input('objetivo_id');

            $av->save();
            return json_encode($av);
        }
        else {
            return response('Av não encontrada', 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $av = Av::find($id);
        if(isset($av)) {
            $av->delete();
            return response('OK', 200);
        }
        return response('Av não encontrada', 404);
    }
    
}
