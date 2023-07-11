<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ano;

class ControladorAnos extends Controller
{
    public function indexView(){
        $user = auth()->user();
        $anos = Ano::all();
        return view('relatoriosDss.parametros', compact(['anos', 'user']));
    }

    public function index()
    {
        $anos = Ano::all();
        return $anos->toJson();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $anos = Ano::all();
        return view('novoano', compact('anos'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $ano = new Ano();
        $ano->ano = $request->input('ano');
        $ano->save();
        return json_encode($ano);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $ano = Ano::find($id);
        if(isset($ano)) {
            return json_encode($ano);
        }
        else {
            return response('Ano não encontrado', 404);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $ano = Ano::find($id);
        if(isset($ano)) {
            return view('editarano', compact(['prod']));
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
        $ano = Ano::find($id);
        if(isset($ano)) {
            $ano->ano = $request->input('ano');
            $ano->save();
            return json_encode($ano);
        }
        else {
            return response('Ano não encontrado', 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $ano = Ano::find($id);
        if(isset($ano)) {
            $ano->delete();
            return response('OK', 200);
        }
        return response('Ano não encontrado', 404);
    }
    
}
