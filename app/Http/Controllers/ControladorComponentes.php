<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Componente;

class ControladorComponentes extends Controller
{
    public function indexView(){
        $user = auth()->user();
        $componentes = Componente::all();
        return view('relatoriosDss.parametros', compact(['componentes', 'user']));
    }

    public function index()
    {
        $componentes = Componente::all();
        return $componentes->toJson();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $componente = new Componente();
        $componente->nome = $request->input('nome');
        $componente->save();
        return json_encode($componente);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $componente = Componente::find($id);
        if(isset($componente)) {
            return json_encode($componente);
        }
        else {
            return response('Componente não encontrado', 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $componente = Componente::find($id);
        if(isset($componente)) {
            $componente->nome = $request->input('nome');
            $componente->save();
            return json_encode($componente);
        }
        else {
            return response('Componente não encontrado', 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $componente = Componente::find($id);
        if(isset($componente)) {
            $componente->delete();
            return response('OK', 200);
        }
        return response('Componente não encontrado', 404);
    }

    public function findComponenteById($id)
    {
        $componente = Componente::findOrFail($id);
        if(isset($componente)) {
            return json_encode($componente);
        }
    }
    
}
