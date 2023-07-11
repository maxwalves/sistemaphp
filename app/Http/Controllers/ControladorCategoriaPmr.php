<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CategoriaPmr;

class ControladorCategoriaPmr extends Controller
{
    public function indexView(){
        $user = auth()->user();
        $categoriasPmr = CategoriaPmr::all();
        return view('relatoriosDss.parametros', compact(['categoriasPmr', 'user']));
    }

    public function index()
    {
        $categoriasPmr = CategoriaPmr::all();
        return $categoriasPmr->toJson();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $categoriaPmr = new CategoriaPmr();
        $categoriaPmr->nome = $request->input('nome');
        $categoriaPmr->codigo = $request->input('codigo');
        $categoriaPmr->save();
        return json_encode($categoriaPmr);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $categoriaPmr = CategoriaPmr::find($id);
        if(isset($categoriaPmr)) {
            return json_encode($categoriaPmr);
        }
        else {
            return response('CategoriaPmr não encontrada', 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $categoriaPmr = CategoriaPmr::find($id);
        if(isset($categoriaPmr)) {
            $categoriaPmr->nome = $request->input('nome');
            $categoriaPmr->codigo = $request->input('codigo');
            $categoriaPmr->save();
            return json_encode($categoriaPmr);
        }
        else {
            return response('CategoriaPmr não encontrada', 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $categoriaPmr = CategoriaPmr::find($id);
        if(isset($categoriaPmr)) {
            $categoriaPmr->delete();
            return response('OK', 200);
        }
        return response('CategoriaPmr não encontrada', 404);
    }

    public function findCategoriaPmrById($id)
    {
        $categoriaPmr = CategoriaPmr::findOrFail($id);
        if(isset($categoriaPmr)) {
            return json_encode($categoriaPmr);
        }
    }
    
}
