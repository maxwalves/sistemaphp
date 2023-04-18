<?php

namespace App\Http\Controllers;

use App\Models\VeiculoProprio;
use Illuminate\Http\Request;

class ControladorVeiculoProprio extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $veiculosProprios = $user->veiculosProprios;

        $search = request('search');

        if ($search) {
            $veiculosProprios = $veiculosProprios::where([
                ['title', 'like', '%'.$search. '%']
            ])->get();
        }

        return view('veiculosProprios.veiculosProprios', ['avs' => $veiculosProprios, 'search' => $search]); //Referenciar a view de adm de veiculos
    }

    public function veiculosProprios()
    {
        $user = auth()->user();
        $veiculosProprios = $user->veiculosProprios;
        return view('veiculosProprios.veiculosProprios', ['veiculosProprios' => $veiculosProprios]);
    }

    public function create()
    {
        return view('veiculosProprios.createVeiculo');
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

        $veiculosProprios = new VeiculoProprio();

        $veiculosProprios->marca = $request->marca;
        $veiculosProprios->modelo = $request->modelo;
        $veiculosProprios->placa = $request->placa;

        $user = auth()->user();
        $veiculosProprios->user_id = $user->id;
    
        $veiculosProprios->save();

        return redirect('/veiculosProprios/veiculosProprios')->with('msg', 'Veículo Próprio criado com sucesso!');
    }

    public function show($id)
    {
        $veiculoProprio = VeiculoProprio::findOrFail($id);

        //$avOwner = Av::where('id', $av->user_id)->first()->toArray();

        return view('veiculosProprios.show', ['veiculoProprio' => $veiculoProprio]);
    }

    public function destroy($id)
    {
        $veiculoProprio = VeiculoProprio::findOrFail($id)->delete();

        return redirect('/veiculosProprios/veiculosProprios')->with('msg', 'Veículo Próprio excluído com sucesso!');
    }

    public function edit($id)
    {
        $user = auth()->user();

        $veiculoProprio = VeiculoProprio::findOrFail($id);

        if($user->id != $veiculoProprio->user->id) {
            return redirect('/veiculosProprios/veiculosProprios')->with('msg', 'Você não tem permissão para editar este veículo!');
        }

        return view('veiculosProprios.editVeiculo', ['veiculoProprio' => $veiculoProprio]);
    }

    public function update(Request $request)
    {
        $data = $request->all();

        VeiculoProprio::findOrFail($request->id)->update($data);

        return redirect('/veiculosProprios/veiculosProprios')->with('msg', 'Veículo Próprio editado com sucesso!');
    }
}
