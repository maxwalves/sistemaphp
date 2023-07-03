<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Municipio;
use DateTime;
use Illuminate\Support\Facades\DB;
use DateTimeZone;
use SoapClient;

class DssController extends Controller
{
    public function paranaUrbanoIII()
    {
        $user = auth()->user();

        $codStatusLote35 = 35;
        $codStatusLote45 = 45;
        $codStatusLote80 = 80;
        $endereco = 'http://srvwebservice/wssam/default.asmx?WSDL';
        $opcoes = [
            'cache_wsdl' => WSDL_CACHE_NONE, 
            'stream_context' => stream_context_create([
                'http' => [
                    'header' => false ? '' : 'Cache-control: max-age=0'
                ],
            ]),
        ];
    //------------------------------------------------------------------------------------------------------------------Consulta Lotes 35 - Em Execução
    $urlConsultaLotes =  new SoapClient($endereco, $opcoes);
    $respostaSoapLotes35 = $urlConsultaLotes ->__soapCall("LotesPorStatus", array(['codStatusLote'=>$codStatusLote35]));
    $consultaLotes35 = json_encode($respostaSoapLotes35);
    //------------------------------------------------------------------------------------------------------------------Consulta Lotes 45 - Concluídos
    $respostaSoapLotes45 = $urlConsultaLotes ->__soapCall("LotesPorStatus", array(['codStatusLote'=>$codStatusLote45]));
    $consultaLotes45 = json_encode($respostaSoapLotes35);
    //------------------------------------------------------------------------------------------------------------------Consulta Lotes 80 - Medição Encerrada
    $respostaSoapLotes80 = $urlConsultaLotes ->__soapCall("LotesPorStatus", array(['codStatusLote'=>$codStatusLote80]));
    $consultaLotes80 = json_encode($respostaSoapLotes35);
    //------------------------------------------------------------------------------------------------------------------Consulta Componentes BID
    $componentesBID = null;
    $caminhoArquivo = public_path('componentesBID.json');
    if (file_exists($caminhoArquivo)) {
        // Abra o arquivo e obtenha seu conteúdo JSON
        $conteudoJSON = file_get_contents($caminhoArquivo);

        // Decodifique o JSON para um array ou objeto PHP
        $componentesBID = json_decode($conteudoJSON, true);
    }
    //------------------------------------------------------------------------------------------------------------------Consulta Municipios
    $municipios = Municipio::all();
    //------------------------------------------------------------------------------------------------------------------Consulta PLs PPUIII
    $dataFaturaInicial = '2023-01-01';
    $dataFaturaFinal = '2023-12-31';
    $urlConsulta=  new SoapClient($endereco, $opcoes);
    $respostaSoap= $urlConsulta ->__soapCall("PedidosLiberacaoPPUIII", array(['dataFaturaInicial'=>$dataFaturaInicial,'dataFaturaFinal'=>$dataFaturaFinal]));
    $consultaPLsPPUIII= json_encode($respostaSoap);
    
    //------------------------------------------------------------------------------------------------------------------Consulta APs PPUIII
    $urlConsulta=  new SoapClient($endereco, $opcoes);
    $respostaSoap2= $urlConsulta ->__soapCall("AutorizacoesPagamentoPPUIII", array(['dataFaturaInicial'=>$dataFaturaInicial,'dataFaturaFinal'=>$dataFaturaFinal]));
    $consultaAPsPPUIII= json_encode($respostaSoap2);
    //------------------------------------------------------------------------------------------------------------------

        return view('relatoriosDss.paranaUrbanoIII', ['user' => $user, 'consultaLotes35' => $consultaLotes35, 
        'consultaLotes45' => $consultaLotes45, 'consultaLotes80' => $consultaLotes80, 'componentesBID' => $componentesBID, 'municipios' => $municipios]);
    }
}
