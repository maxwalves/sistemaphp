<?php

namespace App\Http\Controllers;

use Dompdf\Dompdf;
use Dompdf\Options;
use App\Models\Av;
use App\Models\Objetivo;
use App\Models\Historico;
use App\Models\User;

class RelatorioController extends Controller
{
    public function gerarRelatorioPDF($id)
    {
        $avs = Av::all();
        $av = Av::findOrFail($id);
        $objetivos = Objetivo::all();
        $historicosTodos = Historico::all();
        $historicos = [];
        $users = User::all();

        foreach($historicosTodos as $historico){
            if($historico->av_id == $av->id){
                array_push($historicos, $historico);
            }
        }

        $dompdf = new Dompdf();
        
        $dompdf->loadHtml(view('relatorio', compact('avs', 'av', 'objetivos', 'historicos', 'users')));

        $dompdf->render();

        return $dompdf->stream('relatorio.pdf');
    }

    public function abrirPagina($id){
        $avs = Av::all();
        $av = Av::findOrFail($id);
        $objetivos = Objetivo::all();
        $historicosTodos = Historico::all();
        $historicos = [];
        $users = User::all();
        foreach($historicosTodos as $historico){
            if($historico->av_id == $av->id){
                array_push($historicos, $historico);
            }
        }
        return view('relatorio', ['avs' => $avs, 'av' => $av, 'objetivos' => $objetivos, 'historicos' => $historicos, 'users' => $users]);
    }
}