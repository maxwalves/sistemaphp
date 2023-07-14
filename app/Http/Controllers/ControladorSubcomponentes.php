<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Componente;
use App\Models\Subcomponente;

class ControladorSubcomponentes extends Controller
{
    public function indexView(){
        $user = auth()->user();
        $subcomponentes = Subcomponente::all();
        return view('relatoriosDss.parametros', compact(['subcomponentes', 'user']));
    }

    public function index()
    {
        $subcomponentes = Subcomponente::all();
        return $subcomponentes->toJson();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $subcomponente = new Subcomponente();
        $subcomponente->nome = $request->input('nome');
        $subcomponente->componente_id = $request->input('componente_id');
        $subcomponente->save();
        return json_encode($subcomponente);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $subcomponente = Subcomponente::find($id);
        if(isset($subcomponente)) {
            return json_encode($subcomponente);
        }
        else {
            return response('Subcomponente não encontrado', 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $subcomponente = Subcomponente::find($id);
        if(isset($subcomponente)) {
            $subcomponente->nome = $request->input('nome');
            $subcomponente->componente_id = $request->input('componente_id');
            $subcomponente->save();
            return json_encode($subcomponente);
        }
        else {
            return response('Subcomponente não encontrado', 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $subcomponente = Subcomponente::find($id);
        if(isset($subcomponente)) {
            $subcomponente->delete();
            return response('OK', 200);
        }
        return response('Subcomponente não encontrado', 404);
    }
    
    public function findSubcomponenteByCodigoComponente($id)
    {
        $subcomponente = Subcomponente::all()->where('componente_id', $id);
        if(isset($subcomponente)) {
            return json_encode($subcomponente);
        }
        else{
            return response('Subcomponente não encontrado', 404);
        }
    }
}
