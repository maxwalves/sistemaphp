<?php

namespace App\Http\Controllers;

use Dompdf\Dompdf;
use Dompdf\Options;
use App\Models\Av;
use App\Models\Country;
use App\Models\Objetivo;
use App\Models\Historico;
use App\Models\User;
use PHPUnit\Framework\Constraint\Count;

class RelatorioController extends Controller
{
    public function gerarRelatorioPDF($id)
    {
        $avs = Av::all();
        $av = Av::findOrFail($id);
        $userAv = User::findOrFail($av->user_id);
        $objetivos = Objetivo::all();
        $historicosTodos = Historico::all();
        $historicos = [];
        $users = User::all();
        $valorRecebido = $av;
        $valorReais = 0;
        $valorAcertoContasReal = 0;
        $valorAcertoContasDolar = 0;

        foreach($historicosTodos as $historico){
            if($historico->av_id == $av->id){
                array_push($historicos, $historico);
            }
        }

        $paises = Country::all();

        $options = new Options();
        $options->set('defaultFont', 'sans-serif');
        $dompdf = new Dompdf($options);
        
        
        $dompdf->loadHtml(view('relatorioViagemInternacional', compact('avs', 'av', 'objetivos', 'historicos', 'users', 'userAv', 
        'valorRecebido', 'valorReais', 'valorAcertoContasReal', 'valorAcertoContasDolar', 'paises')));

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
        $user = User::findOrFail($av->user_id);
        foreach($historicosTodos as $historico){
            if($historico->av_id == $av->id){
                array_push($historicos, $historico);
            }
        }
        return view('relatorio', ['avs' => $avs, 'av' => $av, 'objetivos' => $objetivos, 'historicos' => $historicos, 'users' => $users, 'user' => $user]);
    }
}