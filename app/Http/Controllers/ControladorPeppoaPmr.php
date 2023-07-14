<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PepPoaPmr;

class ControladorPeppoaPmr extends Controller
{
    public function indexView(){
        $user = auth()->user();
        $pepPoaPmrTodos = PepPoaPmr::all();
        return view('relatoriosDss.parametros', compact(['pepPoaPmrTodos', 'user']));
    }

    public function index()
    {
        $pepPoaPmrTodos = PepPoaPmr::all();
        return $pepPoaPmrTodos->toJson();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $pepPoaPmr = new PepPoaPmr();
        $pepPoaPmr->categoriaPeppoa_id = $request->input('categoriaPeppoa_id');
        $pepPoaPmr->categoriaPmr_id = $request->input('categoriaPmr_id');
        $pepPoaPmr->codigoBid = $request->input('codigoBid');
        $pepPoaPmr->save();
        return json_encode($pepPoaPmr);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $pepPoaPmr = PepPoaPmr::find($id);
        if(isset($pepPoaPmr)) {
            return json_encode($pepPoaPmr);
        }
        else {
            return response('PepPoaPmr não encontrado', 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $pepPoaPmr = PepPoaPmr::find($id);
        if(isset($pepPoaPmr)) {
            $pepPoaPmr->categoriaPeppoa_id = $request->input('categoriaPeppoa_id');
            $pepPoaPmr->categoriaPmr_id = $request->input('categoriaPmr_id');
            $pepPoaPmr->codigoBid = $request->input('codigoBid');
            $pepPoaPmr->save();
            return json_encode($pepPoaPmr);
        }
        else {
            return response('PepPoaPmr não encontrada', 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $pepPoaPmr = PepPoaPmr::find($id);
        if(isset($pepPoaPmr)) {
            $pepPoaPmr->delete();
            return response('OK', 200);
        }
        return response('PepPoaPmr não encontrada', 404);
    }

    public function findPepPoaPmrById($id)
    {
        $pepPoaPmr = PepPoaPmr::findOrFail($id);
        if(isset($pepPoaPmr)) {
            return json_encode($pepPoaPmr);
        }
    }
    
}
