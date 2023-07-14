<?php

namespace App\Http\Controllers;

use App\Models\AnoPepPoaPmr;
use Illuminate\Http\Request;
use App\Models\PepPoaPmr;

class ControladorAnoPeppoaPmr extends Controller
{
    public function indexView(){
        $user = auth()->user();
        $anoPepPoaPmrTodos = AnoPepPoaPmr::all();
        return view('relatoriosDss.parametros', compact(['anoPepPoaPmrTodos', 'user']));
    }

    public function index()
    {
        $anoPepPoaPmrTodos = AnoPepPoaPmr::all();
        return $anoPepPoaPmrTodos->toJson();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $anoPepPoaPmr = new AnoPepPoaPmr();
        $anoPepPoaPmr->ano_id = $request->input('ano_id');
        $anoPepPoaPmr->justificativaNaoAtingimento = $request->input('justificativaNaoAtingimento');
        $anoPepPoaPmr->metaFisicaBid = $request->input('metaFisicaBid');
        $anoPepPoaPmr->unidadeMedidaBid = $request->input('unidadeMedidaBid');
        $anoPepPoaPmr->metaFisicaPrcid = $request->input('metaFisicaPrcid');
        $anoPepPoaPmr->unidadeMedidaPrcid = $request->input('unidadeMedidaPrcid');
        $anoPepPoaPmr->metaFinanceiraBid = $request->input('metaFinanceiraBid');
        $anoPepPoaPmr->metaFinanceiraPrcid = $request->input('metaFinanceiraPrcid');
        $anoPepPoaPmr->peppoa_pmr_id = $request->input('peppoa_pmr_id');
        $anoPepPoaPmr->save();
        return json_encode($anoPepPoaPmr);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $anoPepPoaPmr = AnoPepPoaPmr::find($id);
        if(isset($anoPepPoaPmr)) {
            return json_encode($anoPepPoaPmr);
        }
        else {
            return response('AnoPepPoaPmr não encontrado', 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $anoPepPoaPmr = AnoPepPoaPmr::find($id);
        if(isset($anoPepPoaPmr)) {
            $anoPepPoaPmr->ano_id = $request->input('ano_id');
            $anoPepPoaPmr->justificativaNaoAtingimento = $request->input('justificativaNaoAtingimento');
            $anoPepPoaPmr->metaFisicaBid = $request->input('metaFisicaBid');
            $anoPepPoaPmr->unidadeMedidaBid = $request->input('unidadeMedidaBid');
            $anoPepPoaPmr->metaFisicaPrcid = $request->input('metaFisicaPrcid');
            $anoPepPoaPmr->unidadeMedidaPrcid = $request->input('unidadeMedidaPrcid');
            $anoPepPoaPmr->metaFinanceiraBid = $request->input('metaFinanceiraBid');
            $anoPepPoaPmr->metaFinanceiraPrcid = $request->input('metaFinanceiraPrcid');
            $anoPepPoaPmr->peppoa_pmr_id = $request->input('peppoa_pmr_id');
            $anoPepPoaPmr->save();
            return json_encode($anoPepPoaPmr);
        }
        else {
            return response('AnoPepPoaPmr não encontrada', 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $anoPepPoaPmr = AnoPepPoaPmr::find($id);
        if(isset($anoPepPoaPmr)) {
            $anoPepPoaPmr->delete();
            return response('OK', 200);
        }
        return response('AnoPepPoaPmr não encontrada', 404);
    }

    public function findAnoPepPoaPmrById($id)
    {
        $anoPepPoaPmr = AnoPepPoaPmr::findOrFail($id);
        if(isset($anoPepPoaPmr)) {
            return json_encode($anoPepPoaPmr);
        }
    }

    public function findAnoPepPoaPmrByCodigoPepPoaPmr($id)
    {
        $anoPepPoaPmr = AnoPepPoaPmr::all()->where('peppoa_pmr_id', $id);
        if(isset($anoPepPoaPmr)) {
            return json_encode($anoPepPoaPmr);
        }
    }
    
}
