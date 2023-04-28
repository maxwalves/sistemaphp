<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Objetivo;

class ControladorObjetivoViagem extends Controller
{
    public function indexJson()
    {
        $objetivos = Objetivo::all();
        return json_encode($objetivos);
    }

    public function index()
    {
        $user = auth()->user();
        $objetivos = Objetivo::all();

        $search = request('search');

        return view('objetivos.objetivos', ['avs' => $objetivos, 'search' => $search, 'user'=> $user]); //Referenciar a view de adm de objetivos
    }

    public function objetivos()
    {
        $user = auth()->user();
        $objetivos = Objetivo::all();
        return view('objetivos.objetivos', ['objetivos' => $objetivos, 'user'=> $user]);
    }

    public function create()
    {
        return view('objetivos.createObjetivo');
    }

    public function store(Request $request)
    {

        $objetivos = new Objetivo();

        $objetivos->nomeObjetivo = $request->nomeObjetivo;
    
        $objetivos->save();

        return redirect('/objetivos/objetivos')->with('msg', 'Objetivo criado com sucesso!');
    }

    public function show($id)
    {
        $user = auth()->user();
        $objetivo = Objetivo::findOrFail($id);

        //$avOwner = Av::where('id', $av->user_id)->first()->toArray();

        return view('objetivos.show', ['objetivo' => $objetivo, 'user'=> $user]);
    }

    public function destroy($id)
    {

        try {
            $objetivo = objetivo::findOrFail($id)->delete();
        } catch (\Throwable $th) {
            return redirect('/objetivos/objetivos')->with('msg', 'O objetivo atual está vinculado a alguma AV e não pode ser excluído!');
        }

        return redirect('/objetivos/objetivos')->with('msg', 'Objetivo excluído com sucesso!');
    }

    public function edit($id)
    {
        $user = auth()->user();

        $objetivo = Objetivo::findOrFail($id);

        //if($user->id != $objetivo->user->id) { ** AQUI COLOCAR REGRA PARA APENAS ADM EDITAR OBJETIVO **
        //    return redirect('/objetivos/objetivos')->with('msg', 'Você não tem permissão para editar este objetivo!');
        //}

        return view('objetivos.editObjetivo', ['objetivo' => $objetivo, 'user'=> $user]);
    }

    public function update(Request $request)
    {
        $data = $request->all();

        objetivo::findOrFail($request->id)->update($data);

        return redirect('/objetivos/objetivos')->with('msg', 'Objetivo editado com sucesso!');
    }
}
