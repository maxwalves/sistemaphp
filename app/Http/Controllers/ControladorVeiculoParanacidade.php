<?php

namespace App\Http\Controllers;

use App\Models\VeiculoParanacidade;
use Illuminate\Http\Request;

class ControladorVeiculoParanacidade extends Controller
{
    public function index()
    {
        $veiculosParanacidade = VeiculoParanacidade::all();

        return view('veiculosParanacidade.veiculosParanacidade', ['avs' => $veiculosParanacidade]);
    }

    public function veiculosParanacidade()
    {
        $veiculosParanacidade = VeiculoParanacidade::all();
        return view('veiculosParanacidade.veiculosParanacidade', ['veiculosParanacidade' => $veiculosParanacidade]);
    }

    public function create()
    {
        return view('veiculosParanacidade.createVeiculoParanacidade');
    }

    public function store(Request $request)
    {

        $regras = [
            'marca' => 'required',
            'modelo' => 'required',
            'placa' => 'required',
        ];
        $mensagens = [
            'required' => 'Este campo não pode estar em branco',
        ];
        $request->validate($regras, $mensagens);

        $veiculosParanacidade = new VeiculoParanacidade();

        $veiculosParanacidade->marca = $request->marca;
        $veiculosParanacidade->modelo = $request->modelo;
        $veiculosParanacidade->placa = $request->placa;
        $veiculosParanacidade->isAtivo = $request->isAtivo;
        $veiculosParanacidade->observacao = $request->observacao;
    
        $veiculosParanacidade->save();

        return redirect('/veiculosParanacidade/veiculosParanacidade')->with('msg', 'Veículo Paranacidade criado com sucesso!');
    }

    public function show($id)
    {
        $veiculoParanacidade = VeiculoParanacidade::findOrFail($id);

        //$avOwner = Av::where('id', $av->user_id)->first()->toArray();

        return view('veiculosParanacidade.show', ['veiculoParanacidade' => $veiculoParanacidade]);
    }

    public function destroy($id)
    {
        try {
            $veiculoParanacidade = VeiculoParanacidade::findOrFail($id)->delete();
        } catch (\Throwable $th) {
            return redirect('/veiculosParanacidade/veiculosParanacidade')->with('msg', 'Veículo Paranacidade está vinculado a alguma AV, se quiser desabilitá-lo edite o cadastro!');
        }
        
        return redirect('/veiculosParanacidade/veiculosParanacidade')->with('msg', 'Veículo Paranacidade excluído com sucesso!');
    }

    public function edit($id)
    {
        $veiculoParanacidade = VeiculoParanacidade::findOrFail($id);

        //if($user->id != $veiculoParanacidade->user->id) {  ** AQUI COLOCAR REGRA PARA APENAS ADM EDITAR VEÍCULO **
        //    return redirect('/veiculosParanacidade/veiculosParanacidade')->with('msg', 'Você não tem permissão para editar este veículo!');
        //}

        return view('veiculosParanacidade.editVeiculoParanacidade', ['veiculoParanacidade' => $veiculoParanacidade]);
    }

    public function update(Request $request)
    {
        $data = $request->all();

        VeiculoParanacidade::findOrFail($request->id)->update($data);

        return redirect('/veiculosParanacidade/veiculosParanacidade')->with('msg', 'Veículo Paranacidade editado com sucesso!');
    }
}