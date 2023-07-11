<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CategoriaPepPoa;

class ControladorCategoriaPeppoa extends Controller
{
    public function indexView(){
        $user = auth()->user();
        $categoriasPepPoa = CategoriaPepPoa::all();
        return view('relatoriosDss.parametros', compact(['categoriasPepPoa', 'user']));
    }

    public function index()
    {
        $categoriasPepPoa = CategoriaPepPoa::all();
        return $categoriasPepPoa->toJson();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $categoriaPepPoa = new CategoriaPepPoa();
        $categoriaPepPoa->nome = $request->input('nome');
        $categoriaPepPoa->codigo = $request->input('codigo');
        $categoriaPepPoa->subcomponente_id = $request->input('subcomponente_id');
        $categoriaPepPoa->save();
        return json_encode($categoriaPepPoa);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $categoriaPepPoa = CategoriaPepPoa::find($id);
        if(isset($categoriaPepPoa)) {
            return json_encode($categoriaPepPoa);
        }
        else {
            return response('CategoriaPepPoa não encontrada', 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $categoriaPepPoa = CategoriaPepPoa::find($id);
        if(isset($categoriaPepPoa)) {
            $categoriaPepPoa->nome = $request->input('nome');
            $categoriaPepPoa->codigo = $request->input('codigo');
            $categoriaPepPoa->subcomponente_id = $request->input('subcomponente_id');
            $categoriaPepPoa->save();
            return json_encode($categoriaPepPoa);
        }
        else {
            return response('CategoriaPepPoa não encontrada', 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $categoriaPepPoa = CategoriaPepPoa::find($id);
        if(isset($categoriaPepPoa)) {
            $categoriaPepPoa->delete();
            return response('OK', 200);
        }
        return response('CategoriaPepPoa não encontrada', 404);
    }

    public function findCategoriaPepPoaById($id)
    {
        $categoriaPepPoa = CategoriaPepPoa::findOrFail($id);
        if(isset($categoriaPepPoa)) {
            return json_encode($categoriaPepPoa);
        }
    }
    
}
