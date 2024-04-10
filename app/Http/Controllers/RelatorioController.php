<?php

namespace App\Http\Controllers;

use Dompdf\Dompdf;
use Dompdf\Options;
use App\Models\Av;
use App\Models\Country;
use App\Models\Objetivo;
use App\Models\Historico;
use App\Models\User;
use App\Models\HistoricoPc;
use App\Models\ComprovanteDespesa;
use App\Models\Medicao;
use DateInterval;
use DatePeriod;
use DateTime;
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

    public function gerarRelatorioPDFAv($id)
    {
        $av = Av::findOrFail($id);
        $userAv = User::findOrFail($av->user_id);
        $avs = Av::all();
        $objetivos = Objetivo::all();
        $historicosTodos = Historico::all();
        $historicos = [];
        $users = User::all();
        $isVeiculoEmpresa = false;

        $arrayDiasValores = $this->geraArrayDiasValoresCerto($av);


        $medicoes = Medicao::all();
        $medicoesFiltradas = [];

        foreach($medicoes as $medicao){
            if($medicao->av_id == $av->id){
                array_push($medicoesFiltradas, $medicao); 
            }
        }

        foreach($historicosTodos as $hist){
            if($hist->av_id == $av->id){
                array_push($historicos, $hist);
            }
        }

        $timezone = new \DateTimeZone('America/Sao_Paulo');
        $dataAtual = new DateTime('now', $timezone);
        //formate para o seguinte formato: 01/01/2021 00:00:00
        $dataFormatadaAtual = $dataAtual->format('d/m/Y H:i:s');

        //código para obter as reservas do usuário da AV -------------------------------------------------------------------------
        $reservas2 = [];
        $veiculos = [];

        $url = 'http://10.51.10.43/reservas/public/api/getVeiculosAPI';
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $veiculos = json_decode(curl_exec($ch));
        //crie uma coleção de $veiculos
        $veiculos = collect($veiculos);

        $url = 'http://10.51.10.43/reservas/public/api/getTodasReservasAPI';
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $reservas2 = json_decode(curl_exec($ch));
        //crie uma coleção de $reservas2
        $reservas2 = collect($reservas2);

        if(count($reservas2) > 0){
            //filtre as reservas de acordo com o $av->idReservaVeiculo e a coluna a ser filtrada é id de reserva
            $reservas2 = $reservas2->filter(function ($reserva) use ($av) {
                if($reserva->id == $av->idReservaVeiculo){
                    return true;
                }
                else{
                    return false;
                }
            });
        }

        if(count($veiculos) > 0){
            //verifique qual é o veículo da reserva pela coluna idVeiculo de $reservas2 e adicione uma nova coluna chamada veiculo
            $reservas2 = $reservas2->map(function ($reserva) use ($veiculos) {
                $veiculo = $veiculos->where('id', $reserva->idVeiculo)->first();
                $reserva->veiculo = $veiculo;
                return $reserva;
            });
        }
        //------------------------------------------------------------------------------------------------------------------------

        $options = new Options();
        $options->set('defaultFont', 'sans-serif');
        $dompdf = new Dompdf($options);

        $dompdf->loadHtml(view('relatorio', compact('avs', 'av', 'objetivos', 'historicos', 'users', 'userAv', 'arrayDiasValores', 
        'isVeiculoEmpresa', 'medicoesFiltradas', 'dataFormatadaAtual', 'reservas2')));
        $dompdf->render();

        return $dompdf->stream('relatorio.pdf');
    }

    public function gerarRelatorioPDFAcertoContas($id)
    {
        $av = Av::findOrFail($id);
        $userAv = User::findOrFail($av->user_id);
        $objetivos = Objetivo::all();
        $historicosTodos = Historico::all();
        $users = User::all();
        $historicos = [];

        $historicoPcAll = HistoricoPc::all();
        $valorRecebido = null;
        
        $comprovantesAll = ComprovanteDespesa::all();
        $comprovantes = [];

        $valorAcertoContasReal = 0;
        $valorAcertoContasDolar = 0;

        foreach($comprovantesAll as $comp){
            if($comp->av_id == $av->id){
                array_push($comprovantes, $comp);
            }
        }
        foreach($comprovantes as $compFiltrado){
            $valorAcertoContasReal += $compFiltrado->valorReais;
            $valorAcertoContasDolar += $compFiltrado->valorDolar;
        }

        foreach($historicosTodos as $historico){
            if($historico->av_id == $av->id){
                array_push($historicos, $historico);
            }
        }
        
        foreach($historicoPcAll as $hisPc){
            if($hisPc->av_id == $av->id && $hisPc->comentario == "Documento AV"){
                $valorRecebido = $hisPc;
            }
        }

        $arrayDiasValores = $this->geraArrayDiasValoresCerto($av);

        $medicoes = Medicao::all();
        $medicoesFiltradas = [];

        foreach($medicoes as $medicao){
            if($medicao->av_id == $av->id){
                array_push($medicoesFiltradas, $medicao); 
            }
        }

        $timezone = new \DateTimeZone('America/Sao_Paulo');
        $dataAtual = new DateTime('now', $timezone);
        //formate para o seguinte formato: 01/01/2021 00:00:00
        $dataFormatadaAtual = $dataAtual->format('d/m/Y H:i:s');

        //código para obter as reservas do usuário da AV -------------------------------------------------------------------------
        $reservas2 = [];
        $veiculos = [];

        $url = 'http://10.51.10.43/reservas/public/api/getVeiculosAPI';
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $veiculos = json_decode(curl_exec($ch));
        //crie uma coleção de $veiculos
        $veiculos = collect($veiculos);

        $url = 'http://10.51.10.43/reservas/public/api/getTodasReservasAPI';
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $reservas2 = json_decode(curl_exec($ch));
        //crie uma coleção de $reservas2
        $reservas2 = collect($reservas2);

        if(count($reservas2) > 0){
            //filtre as reservas de acordo com o $av->idReservaVeiculo e a coluna a ser filtrada é id de reserva
            $reservas2 = $reservas2->filter(function ($reserva) use ($av) {
                if($reserva->id == $av->idReservaVeiculo){
                    return true;
                }
                else{
                    return false;
                }
            });
        }

        if(count($veiculos) > 0){
            //verifique qual é o veículo da reserva pela coluna idVeiculo de $reservas2 e adicione uma nova coluna chamada veiculo
            $reservas2 = $reservas2->map(function ($reserva) use ($veiculos) {
                $veiculo = $veiculos->where('id', $reserva->idVeiculo)->first();
                $reserva->veiculo = $veiculo;
                return $reserva;
            });
        }

        //dd($av, $userAv, $objetivos, $historicos, $users, $valorRecebido, $valorAcertoContasReal, $valorAcertoContasDolar, $arrayDiasValores, $medicoesFiltradas, $dataFormatadaAtual, $reservas2);
        
        $options = new Options();
        $options->set('defaultFont', 'sans-serif');
        $dompdf = new Dompdf($options);

        $dompdf = new Dompdf();
        $dompdf->loadHtml(view('relatorioAcertoContas', compact('av', 'objetivos', 'historicos', 'users', 'userAv', 'valorRecebido', 
        'valorAcertoContasReal', 'valorAcertoContasDolar', 'arrayDiasValores', 'medicoesFiltradas', 'dataFormatadaAtual', 'reservas2')));
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
        $userAv = User::findOrFail($av->user_id);
        $isVeiculoEmpresa = false;
        foreach($historicosTodos as $historico){
            if($historico->av_id == $av->id){
                array_push($historicos, $historico);
            }
        }
        $arrayDiasValores =  $this->geraArrayDiasValoresCerto($av);
        
        $options = new Options();
        $options->set('defaultFont', 'sans-serif');
        $dompdf = new Dompdf($options);

        $dompdf = new Dompdf();
        $dompdf->loadHtml(view('relatorio', compact('avs', 'av', 'objetivos', 'historicos', 'users', 'userAv', 'arrayDiasValores', 'isVeiculoEmpresa')));
        $dompdf->render();

        return $dompdf->stream('relatorio.pdf');
    }

    public function geraArrayDiasValoresCerto($av){

        $rotas = $av->rotas;

        $dataInicio = date('Y-m-d', strtotime($rotas[0]->dataHoraSaida));
        $dataFim = date('Y-m-d', strtotime($rotas[sizeof($rotas)-1]->dataHoraChegada));
                       
        $arrayDiasValores = [];
                        
        $intervaloDatas = new DatePeriod(
            new DateTime($dataInicio),
            new DateInterval('P1D'),
            ($dataInicio != $dataFim ? (new DateTime($dataFim))->modify('+1 day') : (new DateTime($dataFim)))
        );

        //------------------------------------------------------------------------------------------------------------------------------------------------------
        
        foreach ($intervaloDatas as $data) {
            
            
            $dia = $data->format('Y-m-d');
            $valor = 0;
            $acumulado = 0;

            $rotasDoDia = [];
            $dataPrimeiraRota = null;
            $dataUltimaRota = null;

            //ITERE AS ROTAS E VERIFIQUE SE O DIA ESTÁ ENTRE A DATA DE SAÍDA E A DATA DE CHEGADA-----------------------------------
            for ($i=0; $i < sizeof($rotas) ; $i++) {
                    $dataSaida = DateTime::createFromFormat('Y-m-d H:i:s', $rotas[$i]->dataHoraSaida)->format('Y-m-d H:i:s');
                    $dataChegada = DateTime::createFromFormat('Y-m-d H:i:s', $rotas[$i]->dataHoraChegada)->format('Y-m-d H:i:s');

                    $dataSaidaFormatado = new DateTime($dataSaida);//Data de saída da rota 1
                    $dataChegadaFormatado = new DateTime($dataChegada);//Data de chegada da rota 1
            
                    //verifique se a data do dia está entre a data de saída e a data de chegada
                    if($dia >= $dataSaidaFormatado->format('Y-m-d') && $dia <= $dataChegadaFormatado->format('Y-m-d')){
                        $rotasDoDia[] = $rotas[$i];
                    }

                    //captura a data inicial da primeira rota
                    if($i == 0){
                        $dataPrimeiraRota = $dataSaidaFormatado->format('Y-m-d');
                    }
                    
                    //captura a data final da ultima rota
                    if($i == sizeof($rotas)-1){
                        $dataUltimaRota = $dataChegadaFormatado->format('Y-m-d');
                    }
            }//----------------------------------------------------------------------------------------------------------------------
            $arrayRotasDoDia = [];
            //ISSO TRATA SITUAÇÕES ONDE NÃO HÁ ROTA NO DIA, OU SEJA O DIA ESTÁ NO INTERVALO ENTRE DUAS VIAGENS-----------------------
            if(sizeof($rotasDoDia) == 0){
                //procure a rota em que a dataHoraChegada é anterior ao dia atual e atribua a $rota ao dia
                for ($i=sizeof($rotas)-1; $i >= 0 ; $i--) {
                    $dataChegada = DateTime::createFromFormat('Y-m-d H:i:s', $rotas[$i]->dataHoraChegada)->format('Y-m-d H:i:s');
                    $dataChegadaFormatado = new DateTime($dataChegada);
                    
                    if($dia > $dataChegadaFormatado->format('Y-m-d')){
                        $valor = $this->verificaValorRota($rotas[$i]);
                        $arrayRotasDoDia[] = $rotas[$i]->cidadeDestinoNacional;
                        break;
                    }
                }
            }//----------------------------------------------------------------------------------------------------------------------

            $temDiariaManha = false;
            $temDiariaTarde = false;
            $valorManha = 0;
            $valorTarde = 0;
            

            //AGORA VAMOS ANALISAR OS DIAS QUE POSSUEM ROTAS E CALCULAR O VALOR DA DIÁRIA-----------------------------------------------------------------
            foreach($rotasDoDia as $indice => $rota){
                
                //DECLARAÇÃO DE VARIÁVEIS ------------------------------------------------------------------------------------

                //Captura o valor para a diária da rota atual
                $valor = $this->verificaValorRota($rota);

                //Captura a rota anterior e a próxima rota
                $rotaAnterior = isset($rotasDoDia[$indice - 1]) ? $rotasDoDia[$indice - 1] : false;
                $proximaRota = isset($rotasDoDia[$indice + 1]) ? $rotasDoDia[$indice + 1] : null;

                //Captura a data de saída e formata para DateTime
                $dataSaida = DateTime::createFromFormat('Y-m-d H:i:s', $rota->dataHoraSaida)->format('Y-m-d H:i:s');
                $dataSaidaFormatado = new DateTime($dataSaida);

                //Captura a data de chegada e formata para DateTime
                $dataChegada = DateTime::createFromFormat('Y-m-d H:i:s', $rota->dataHoraChegada)->format('Y-m-d H:i:s');
                $dataChegadaFormatado = new DateTime($dataChegada);

                //Monta um array com as rotas do dia de IDA
                if ($dataSaidaFormatado->format('Y-m-d') == $dia) {
                    $arrayRotasDoDia[] = "Ida: " . $rota->cidadeOrigemNacional . " (" . $dataSaidaFormatado->format('H:i') . ")" . " >";
                }

                //Monta um array com as rotas do dia de VOLTA
                if ($dataChegadaFormatado->format('Y-m-d') == $dia) {
                    $arrayRotasDoDia[] = $rota->cidadeDestinoNacional . " (" . $dataChegadaFormatado->format('H:i') . ")" . "";
                }

                //Captura a data de chegada da rota anterior e formata para DateTime
                if($rotaAnterior != false){
                    $rotaAnteriorDataChegada = DateTime::createFromFormat('Y-m-d H:i:s', $rotaAnterior->dataHoraChegada)->format('Y-m-d H:i:s');
                    $rotaAnteriorDataChegadaFormatado = new DateTime($rotaAnteriorDataChegada);
                }

                //Captura a data de saída e chegada da próxima rota e formata para DateTime
                if($proximaRota != false){
                    
                    $proximaRotaDataSaida = DateTime::createFromFormat('Y-m-d H:i:s', $proximaRota->dataHoraSaida)->format('Y-m-d H:i:s');
                    $proximaRotaDataSaidaFormatado = new DateTime($proximaRotaDataSaida);

                    $proximaRotaDataChegada = DateTime::createFromFormat('Y-m-d H:i:s', $proximaRota->dataHoraChegada)->format('Y-m-d H:i:s');
                    $proximaRotaDataChegadaFormatado = new DateTime($proximaRotaDataChegada);
                }

                //-----------------------------------------------------------------------------------------------------------

                //APENAS SE COINDICIR O DIA DA ROTA COM O DIAS DO INTERVALO, NOS DIAS INTEMEDIÁRIOS O VALOR DA DIÁRIA É O DA ÚLTIMA ROTA
                if($dataSaidaFormatado->format('Y-m-d') == $dia){
                    
                    if($temDiariaManha == false){

                        if( ($dataSaidaFormatado->format('H:i:s') <= "12:00:00" && $dataChegadaFormatado->format('H:i:s') >= "13:01:00") && $dia != $dataUltimaRota){
                        //SE A HORA DE SAÍDA FOR MENOR QUE 12:00 E A HORA DE CHEGADA FOR MAIOR QUE 13:01
                            $valorManha = $valor/2;
                            $temDiariaManha = true;
                        }
                        else if($dataSaidaFormatado->format('H:i:s') <= "12:00:00" && $dataChegadaFormatado->format('H:i:s') < "19:00:00"
                                && $proximaRota == false && $dia != $dataUltimaRota){
                        //SE A HORA DE SAÍDA FOR MENOR QUE 12:00 E A HORA DE CHEGADA FOR MENOR QUE 13:00 E NÃO TIVER PRÓXIMA ROTA NO DIA, MAS NÃO ACABOU A VIAGEM
                            $valorManha = $valor/2;
                            $temDiariaManha = true;
                        }
                        else if($proximaRota != false && $proximaRotaDataSaidaFormatado->format('Y-m-d') == $dia && 
                                $dataSaidaFormatado->format('H:i:s') <= "12:00:00" && $dia != $dataUltimaRota){
                        //SE A PRÓXIMA ROTA FOR NO MESMO DIA, A HORA DE SAÍDA DELA FOR MAIOR QUE 13:01 E NÃO FOR A ÚLTIMA ROTA
                            $valorManha = $valor/2;
                            $temDiariaManha = true;
                        }
                        else if($dia != $dataPrimeiraRota && $dia != $dataUltimaRota){
                        //SE O DIA ATUAL NÃO FOR O DIA DA PRIMEIRA ROTA E A HORA DE SAÍDA FOR MAIOR QUE 12:01
                            $rotaImediatamenteAnterior = $this->buscarRotaAnterior($rota, $rotas);
                            $valor = $this->verificaValorRota($rotaImediatamenteAnterior);
                            $valorManha = $valor/2;
                            $temDiariaManha = true;
                        }
                        else if($dia == $dataUltimaRota && $dataChegadaFormatado->format('H:i:s') >= "13:01:00"){
                        //SE O DIA ATUAL FOR O DIA DA ÚLTIMA ROTA E A HORA DE SAÍDA FOR MENOR QUE 12:00
                            try {
                                $rotaImediatamenteAnterior = $this->buscarRotaAnterior($rota, $rotas);
                                $valor = $this->verificaValorRota($rotaImediatamenteAnterior);
                            } catch (\Throwable $th) {
                                $valor = $this->verificaValorRota($rota);
                            }
                            $valorManha = $valor/2;
                            $temDiariaManha = true;
                        }
                    }

                    if($temDiariaTarde == false){
                        
                        if($dataSaidaFormatado->format('H:i:s') >= "13:01:00" && $dataSaidaFormatado->format('H:i:s') < "19:00:00" 
                            && $dataChegadaFormatado->format('H:i:s') >= "19:01:00" && $dia != $dataUltimaRota){
                        //SE A HORA DE SAÍDA FOR MAIOR QUE 13:01 E MENOR QUE 19:00 E A HORA DE CHEGADA FOR MAIOR QUE 19:01
                            $valorTarde = $valor/2;
                            $temDiariaTarde = true;
                        }
                        else if($proximaRota != false && $dia != $dataUltimaRota &&
                                ($proximaRotaDataSaidaFormatado->format('Y-m-d') == $dia && $proximaRotaDataSaidaFormatado->format('H:i:s') >= "19:01:00" ||
                                 $proximaRotaDataChegadaFormatado->format('Y-m-d') == $dia && $proximaRotaDataChegadaFormatado->format('H:i:s') >= "19:01:00")){
                        //SE A PRÓXIMA ROTA FOR NO MESMO DIA E A HORA DE SAÍDA OU CHEGADA DELA FOR MAIOR QUE 19:01
                            $valorTarde = $valor/2;
                            $temDiariaTarde = true;
                        }
                        else if($proximaRota == false && $dia != $dataUltimaRota && $dataChegadaFormatado->format('H:i:s') < "24:00:00"){
                        //NÃO TEM MAIS ROTAS NO DIA, SIGNFICA QUE JÁ CHEGOU E VAI FICAR NA CIDADE
                            $valorTarde = $valor/2;
                            $temDiariaTarde = true;
                        }
                        else if($dia == $dataUltimaRota && $dataChegadaFormatado->format('H:i:s') >= "19:01:00"){
                        //SE O DIA ATUAL FOR O DIA DA ÚLTIMA ROTA E A HORA DE CHEGADA FOR MAIOR QUE 19:01
                            try {
                                $rotaImediatamenteAnterior = $this->buscarRotaAnterior($rota, $rotas);
                                $valor = $this->verificaValorRota($rotaImediatamenteAnterior);
                            } catch (\Throwable $th) {
                                $valor = $this->verificaValorRota($rota);
                            }
                            $valorTarde = $valor/2;
                            $temDiariaTarde = true;
                        }
                    }

                    if($temDiariaManha == true && $temDiariaTarde == true)
                        $valor = $valorManha + $valorTarde;
                    else if($temDiariaManha == true && $temDiariaTarde == false)
                        $valor = $valorManha;
                    else if($temDiariaManha == false && $temDiariaTarde == true)
                        $valor = $valorTarde;
                    else if($temDiariaManha == false && $temDiariaTarde == false)
                        $valor = 0;
                }
                else if($dia == $dataUltimaRota && $dataSaidaFormatado->format('Y-m-d') != $dia){
                    if($dataChegadaFormatado->format('H:i:s') >= "13:01:00" && $dataChegadaFormatado->format('H:i:s') < "19:00:00"){
                        $valorManha = $valor/2;
                    }
                    else if($dataChegadaFormatado->format('H:i:s') >= "19:01:00"){
                        $valorTarde = $valor/2;
                    }
                }

            }

            if(sizeof($rotasDoDia) == 0 && $dia != $dataUltimaRota){
                $valorManha = $valor/2;
                $valorTarde = $valor/2;
            }
            //se o dia for diferente do primeiro e do ultimo
            if($dia != $dataPrimeiraRota && $dia != $dataUltimaRota && $valorManha == 0 && $valorTarde == 0 && $valor != 0){
                $valorManha = $valor/2;
                $valorTarde = $valor/2;
            }
            //se for o ultimo dia
            if($dia == $dataUltimaRota && $valorManha == 0 && $valorTarde == 0 && $valor != 0){
                $valor = 0;
            }
            
            $diaFormatado = DateTime::createFromFormat('Y-m-d', $dia);
            $arrayDiasValores[] = [
                'dia' => $diaFormatado->format('d'),
                'arrayRotasDoDia' => $arrayRotasDoDia,
                'valorManha' => $valorManha,
                'valorTarde' => $valorTarde,
                'valor' => $valor,
            ];
        }

        //Se a viagem for somente no mesmo dia
        if(iterator_count($intervaloDatas) == 0){

            //Captura a data de $intervaloData e atribua a variável $data
            $data = new DateTime($dataInicio);

            $dia = $data->format('Y-m-d');
            $valor = 0;
            $acumulado = 0;

            $rotasDoDia = [];
            $dataPrimeiraRota = null;
            $dataUltimaRota = null;

            //ITERE AS ROTAS E VERIFIQUE SE O DIA ESTÁ ENTRE A DATA DE SAÍDA E A DATA DE CHEGADA-----------------------------------
            for ($i=0; $i < sizeof($rotas) ; $i++) {
                    $dataSaida = DateTime::createFromFormat('Y-m-d H:i:s', $rotas[$i]->dataHoraSaida)->format('Y-m-d H:i:s');
                    $dataChegada = DateTime::createFromFormat('Y-m-d H:i:s', $rotas[$i]->dataHoraChegada)->format('Y-m-d H:i:s');

                    $dataSaidaFormatado = new DateTime($dataSaida);//Data de saída da rota 1
                    $dataChegadaFormatado = new DateTime($dataChegada);//Data de chegada da rota 1
            
                    //verifique se a data do dia está entre a data de saída e a data de chegada
                    if($dia >= $dataSaidaFormatado->format('Y-m-d') && $dia <= $dataChegadaFormatado->format('Y-m-d')){
                        $rotasDoDia[] = $rotas[$i];
                    }

                    //captura a data inicial da primeira rota
                    if($i == 0){
                        $dataPrimeiraRota = $dataSaidaFormatado->format('Y-m-d');
                    }
                    
                    //captura a data final da ultima rota
                    if($i == sizeof($rotas)-1){
                        $dataUltimaRota = $dataChegadaFormatado->format('Y-m-d');
                    }
            }//----------------------------------------------------------------------------------------------------------------------
            $arrayRotasDoDia = [];
            //ISSO TRATA SITUAÇÕES ONDE NÃO HÁ ROTA NO DIA, OU SEJA O DIA ESTÁ NO INTERVALO ENTRE DUAS VIAGENS-----------------------
            if(sizeof($rotasDoDia) == 0){
                //procure a rota em que a dataHoraChegada é anterior ao dia atual e atribua a $rota ao dia
                for ($i=sizeof($rotas)-1; $i >= 0 ; $i--) {
                    $dataChegada = DateTime::createFromFormat('Y-m-d H:i:s', $rotas[$i]->dataHoraChegada)->format('Y-m-d H:i:s');
                    $dataChegadaFormatado = new DateTime($dataChegada);
                    
                    if($dia > $dataChegadaFormatado->format('Y-m-d')){
                        $valor = $this->verificaValorRota($rotas[$i]);
                        $arrayRotasDoDia[] = $rotas[$i]->cidadeDestinoNacional;
                        break;
                    }
                }
            }//----------------------------------------------------------------------------------------------------------------------

            $temDiariaManha = false;
            $temDiariaTarde = false;
            $valorManha = 0;
            $valorTarde = 0;
            

            //AGORA VAMOS ANALISAR OS DIAS QUE POSSUEM ROTAS E CALCULAR O VALOR DA DIÁRIA-----------------------------------------------------------------
            foreach($rotasDoDia as $indice => $rota){
                
                //DECLARAÇÃO DE VARIÁVEIS ------------------------------------------------------------------------------------

                //Captura o valor para a diária da rota atual
                $valor = $this->verificaValorRota($rota);

                //Captura a rota anterior e a próxima rota
                $rotaAnterior = isset($rotasDoDia[$indice - 1]) ? $rotasDoDia[$indice - 1] : false;
                $proximaRota = isset($rotasDoDia[$indice + 1]) ? $rotasDoDia[$indice + 1] : null;

                //Captura a data de saída e formata para DateTime
                $dataSaida = DateTime::createFromFormat('Y-m-d H:i:s', $rota->dataHoraSaida)->format('Y-m-d H:i:s');
                $dataSaidaFormatado = new DateTime($dataSaida);

                //Captura a data de chegada e formata para DateTime
                $dataChegada = DateTime::createFromFormat('Y-m-d H:i:s', $rota->dataHoraChegada)->format('Y-m-d H:i:s');
                $dataChegadaFormatado = new DateTime($dataChegada);

                //Monta um array com as rotas do dia de IDA
                if ($dataSaidaFormatado->format('Y-m-d') == $dia) {
                    $arrayRotasDoDia[] = "Ida: " . $rota->cidadeOrigemNacional . " (" . $dataSaidaFormatado->format('H:i') . ")" . " >";
                }

                //Monta um array com as rotas do dia de VOLTA
                if ($dataChegadaFormatado->format('Y-m-d') == $dia) {
                    $arrayRotasDoDia[] = $rota->cidadeDestinoNacional . " (" . $dataChegadaFormatado->format('H:i') . ")" . "";
                }

                //Captura a data de chegada da rota anterior e formata para DateTime
                if($rotaAnterior != false){
                    $rotaAnteriorDataChegada = DateTime::createFromFormat('Y-m-d H:i:s', $rotaAnterior->dataHoraChegada)->format('Y-m-d H:i:s');
                    $rotaAnteriorDataChegadaFormatado = new DateTime($rotaAnteriorDataChegada);
                }

                //Captura a data de saída e chegada da próxima rota e formata para DateTime
                if($proximaRota != false){
                    
                    $proximaRotaDataSaida = DateTime::createFromFormat('Y-m-d H:i:s', $proximaRota->dataHoraSaida)->format('Y-m-d H:i:s');
                    $proximaRotaDataSaidaFormatado = new DateTime($proximaRotaDataSaida);

                    $proximaRotaDataChegada = DateTime::createFromFormat('Y-m-d H:i:s', $proximaRota->dataHoraChegada)->format('Y-m-d H:i:s');
                    $proximaRotaDataChegadaFormatado = new DateTime($proximaRotaDataChegada);
                }

                //-----------------------------------------------------------------------------------------------------------

                //APENAS SE COINDICIR O DIA DA ROTA COM O DIAS DO INTERVALO, NOS DIAS INTEMEDIÁRIOS O VALOR DA DIÁRIA É O DA ÚLTIMA ROTA
                if($dataSaidaFormatado->format('Y-m-d') == $dia){
                    

                    if($temDiariaManha == false){

                        if( ($dataSaidaFormatado->format('H:i:s') <= "12:00:00" && $dataChegadaFormatado->format('H:i:s') >= "13:01:00") && $dia != $dataUltimaRota){
                        //SE A HORA DE SAÍDA FOR MENOR QUE 12:00 E A HORA DE CHEGADA FOR MAIOR QUE 13:01
                            $valorManha = $valor/2;
                            $temDiariaManha = true;
                        }
                        else if($dataSaidaFormatado->format('H:i:s') <= "12:00:00" && $dataChegadaFormatado->format('H:i:s') < "19:00:00"
                                && $proximaRota == false && $dia != $dataUltimaRota){
                        //SE A HORA DE SAÍDA FOR MENOR QUE 12:00 E A HORA DE CHEGADA FOR MENOR QUE 13:00 E NÃO TIVER PRÓXIMA ROTA NO DIA, MAS NÃO ACABOU A VIAGEM
                            $valorManha = $valor/2;
                            $temDiariaManha = true;
                        }
                        else if($proximaRota != false && $proximaRotaDataSaidaFormatado->format('Y-m-d') == $dia && 
                                $dataSaidaFormatado->format('H:i:s') <= "12:00:00" && $dia != $dataUltimaRota){
                        //SE A PRÓXIMA ROTA FOR NO MESMO DIA, A HORA DE SAÍDA DELA FOR MAIOR QUE 13:01 E NÃO FOR A ÚLTIMA ROTA
                            $valorManha = $valor/2;
                            $temDiariaManha = true;
                        }
                        else if($dia != $dataPrimeiraRota && $dia != $dataUltimaRota){
                        //SE O DIA ATUAL NÃO FOR O DIA DA PRIMEIRA ROTA E A HORA DE SAÍDA FOR MAIOR QUE 12:01
                            $rotaImediatamenteAnterior = $this->buscarRotaAnterior($rota, $rotas);
                            $valor = $this->verificaValorRota($rotaImediatamenteAnterior);
                            $valorManha = $valor/2;
                            $temDiariaManha = true;
                        }
                        else if($dia == $dataUltimaRota && $dataSaidaFormatado->format('H:i:s') <= "12:00:00" 
                                && $dataChegadaFormatado->format('H:i:s') >= "13:01:00"){
                        //SE O DIA ATUAL FOR O DIA DA ÚLTIMA ROTA E A HORA DE SAÍDA FOR MENOR QUE 12:00
                            try {
                                $rotaImediatamenteAnterior = $this->buscarRotaAnterior($rota, $rotas);
                                $valor = $this->verificaValorRota($rotaImediatamenteAnterior);
                            } catch (\Throwable $th) {
                                $valor = $this->verificaValorRota($rota);
                            }
                            
                            $valorManha = $valor/2;
                            $temDiariaManha = true;
                        }
                        else if($dia == $dataUltimaRota && $dataSaidaFormatado->format('H:i:s') <= "12:00:00" 
                                && $proximaRota != false && $proximaRotaDataChegadaFormatado->format('H:i:s') >= "13:01:00"){
                        //SE O DIA ATUAL FOR O DIA DA ÚLTIMA ROTA E A HORA DE SAÍDA FOR MENOR QUE 12:00
                            try {
                                $rotaImediatamenteAnterior = $this->buscarRotaAnterior($rota, $rotas);
                                $valor = $this->verificaValorRota($rotaImediatamenteAnterior);
                            } catch (\Throwable $th) {
                                $valor = $this->verificaValorRota($rota);
                            }
                            
                            $valorManha = $valor/2;
                            $temDiariaManha = true;
                        }
                    }

                    if($temDiariaTarde == false){
                        
                        if($dataSaidaFormatado->format('H:i:s') >= "13:01:00" && $dataSaidaFormatado->format('H:i:s') < "19:00:00" 
                            && $dataChegadaFormatado->format('H:i:s') >= "19:01:00" && $dia != $dataUltimaRota){
                        //SE A HORA DE SAÍDA FOR MAIOR QUE 13:01 E MENOR QUE 19:00 E A HORA DE CHEGADA FOR MAIOR QUE 19:01
                            $valorTarde = $valor/2;
                            $temDiariaTarde = true;
                        }
                        else if($proximaRota != false && $dia != $dataUltimaRota &&
                                ($proximaRotaDataSaidaFormatado->format('Y-m-d') == $dia && $proximaRotaDataSaidaFormatado->format('H:i:s') >= "19:01:00" ||
                                 $proximaRotaDataChegadaFormatado->format('Y-m-d') == $dia && $proximaRotaDataChegadaFormatado->format('H:i:s') >= "19:01:00")){
                        //SE A PRÓXIMA ROTA FOR NO MESMO DIA E A HORA DE SAÍDA OU CHEGADA DELA FOR MAIOR QUE 19:01
                            $valorTarde = $valor/2;
                            $temDiariaTarde = true;
                        }
                        else if($proximaRota == false && $dia != $dataUltimaRota && $dataChegadaFormatado->format('H:i:s') < "24:00:00"){
                        //NÃO TEM MAIS ROTAS NO DIA, SIGNFICA QUE JÁ CHEGOU E VAI FICAR NA CIDADE
                            $valorTarde = $valor/2;
                            $temDiariaTarde = true;
                        }
                        else if($dia == $dataUltimaRota && $dataChegadaFormatado->format('H:i:s') >= "19:01:00"){
                        //SE O DIA ATUAL FOR O DIA DA ÚLTIMA ROTA E A HORA DE CHEGADA FOR MAIOR QUE 19:01
                            try {
                                $rotaImediatamenteAnterior = $this->buscarRotaAnterior($rota, $rotas);
                                $valor = $this->verificaValorRota($rotaImediatamenteAnterior);
                            } catch (\Throwable $th) {
                                $valor = $this->verificaValorRota($rota);
                            }
                            $valorTarde = $valor/2;
                            $temDiariaTarde = true;
                        }
                    }

                    if($temDiariaManha == true && $temDiariaTarde == true)
                        $valor = $valorManha + $valorTarde;
                    else if($temDiariaManha == true && $temDiariaTarde == false)
                        $valor = $valorManha;
                    else if($temDiariaManha == false && $temDiariaTarde == true)
                        $valor = $valorTarde;
                    else if($temDiariaManha == false && $temDiariaTarde == false)
                        $valor = 0;
                }
                else if($dia == $dataUltimaRota && $dataSaidaFormatado->format('Y-m-d') != $dia){
                    if($dataChegadaFormatado->format('H:i:s') >= "13:01:00" && $dataChegadaFormatado->format('H:i:s') < "19:00:00"){
                        $valorManha = $valor/2;
                    }
                    else if($dataChegadaFormatado->format('H:i:s') >= "19:01:00"){
                        $valorTarde = $valor/2;
                    }
                }

            }

            if(sizeof($rotasDoDia) == 0 && $dia != $dataUltimaRota){
                $valorManha = $valor/2;
                $valorTarde = $valor/2;
            }
            //se o dia for diferente do primeiro e do ultimo
            if($dia != $dataPrimeiraRota && $dia != $dataUltimaRota && $valorManha == 0 && $valorTarde == 0 && $valor != 0){
                $valorManha = $valor/2;
                $valorTarde = $valor/2;
            }
            //se for o ultimo dia
            if($dia == $dataUltimaRota && $valorManha == 0 && $valorTarde == 0 && $valor != 0){
                $valor = 0;
            }
            
            $diaFormatado = DateTime::createFromFormat('Y-m-d', $dia);
            $arrayDiasValores[] = [
                'dia' => $diaFormatado->format('d'),
                'arrayRotasDoDia' => $arrayRotasDoDia,
                'valorManha' => $valorManha,
                'valorTarde' => $valorTarde,
                'valor' => $valor,
            ];
        }
        //CASO SÓ TENHA UMA ROTA
        if(sizeof($rotas) == 1){
            $valor = $this->verificaValorRota($rotas[0]);
            $arrayRotasDoDia = [];
            $arrayRotasDoDia[] = " [ " . $rotas[0]->cidadeOrigemNacional . " - " . $rotas[0]->cidadeDestinoNacional . " ] ";
            $arrayDiasValores[] = [
                'dia' => DateTime::createFromFormat('Y-m-d H:i:s', $rotas[0]->dataHoraSaida)->format('d'),
                'arrayRotasDoDia' => $arrayRotasDoDia,
                'valorManha' => $valor/2,
                'valorTarde' => $valor/2,
                'valor' => $valor,
            ];
        }

        return $arrayDiasValores;
    }

    public function verificaValorRota($rota){
        if($rota->continenteDestinoInternacional == 1 && $rota->paisDestinoInternacional !=30){//América Latina ou Amética Central
            $valor = 100;
        }
        else if($rota->continenteDestinoInternacional == 2){//América do Norte
            $valor = 150;
        }
        else if($rota->continenteDestinoInternacional == 3){//Europa
            $valor = 180;
        }
        else if($rota->continenteDestinoInternacional == 4){//África
            $valor = 140;
        }
        else if($rota->continenteDestinoInternacional == 5){//Ásia
            $valor = 190;
        }else if(($rota->cidadeDestinoNacional == "Curitiba" || $rota->cidadeDestinoNacional == "Foz do Iguaçu") ||
        ($rota->paisDestinoInternacional == 30 && $rota->estadoDestinoInternacional == "Paraná" && 
        ($rota->cidadeDestinoInternacional == "Curitiba" || $rota->cidadeDestinoInternacional == "Foz do Iguaçu"))){//Se for Curitiba ou Foz do Iguaçu
            $valor = 65;
        }
        else if($rota->estadoDestinoNacional == "Paraná" || ($rota->paisDestinoInternacional == 30 && $rota->estadoDestinoInternacional == "Paraná")){//Se for outra cidade do Paraná
            $valor = 55;
        }
        else if($rota->cidadeDestinoNacional == "Brasília" || ($rota->paisDestinoInternacional == 30 && $rota->cidadeDestinoInternacional == "Brasília")){//Se for Brasília
            $valor = 100;
        }
        else{//Se não entrou em nenhum if, então é uma capital ou cidade de outros estados
            $valor = 80;
        }

        return $valor;
    }

    public function buscarRotaAnterior($rota, $rotas){
        $rotaAnterior = null;
        for ($i=0; $i < sizeof($rotas) ; $i++) {
            if($rota->id == $rotas[$i]->id){
                $rotaAnterior = $rotas[$i-1];
                break;
            }
        }
        return $rotaAnterior;
    }
}