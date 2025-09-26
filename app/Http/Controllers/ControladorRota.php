<?php

namespace App\Http\Controllers;

use App\Models\Rota;
use App\Models\Av;
use App\Models\VeiculoParanacidade;
use App\Models\VeiculoProprio;
use Illuminate\Http\Request;
use DateTimeZone;
use DateTime;
use DatePeriod;
use DateInterval;

class ControladorRota extends Controller
{
    public function index($id)//Arrumar rota no web.php e fazer com que a view que está mandando envie a id da AV
    {
        $user = auth()->user();
        $av = Av::findOrFail($id);//Busca a AV com base no ID
        $rotas = $av->rotas;//Busca as rotas da AV

        $search = request('search');

        if ($search) {
            $rotas = $rotas::where([
                ['title', 'like', '%'.$search. '%']
            ])->get();
        }

        return view('rotas.rotas', ['rotas' => $rotas, 'search' => $search, 'user'=> $user]); 
    }

    public function rotas($id)//Id da AV
    {
        $user = auth()->user();
        $av = Av::findOrFail($id);//Busca a AV com base no ID
        $rotas = $av->rotas;//Busca as rotas da AV
        $veiculosProprios = VeiculoProprio::all();

        //obter dados da API
        $eventos = [];
        $reservas2 = [];
        $veiculos = [];

        $url = 'http://10.51.10.43/reservas/public/api/getReservasAPI';
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $eventos = json_decode(curl_exec($ch));

        $url = 'http://10.51.10.43/reservas/public/api/getReservasUsuarioAPI/' . $user->username;
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $reservas2 = json_decode(curl_exec($ch));

        $url = 'http://10.51.10.43/reservas/public/api/getVeiculosAPI';
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $veiculos = json_decode(curl_exec($ch));

        if($av->isEnviadoUsuario == 1){
            return redirect('/avs/avs/')->with('msg', 'Você não tem autorização para editar uma rota de AV que já foi enviada!');
        }

        return view('rotas.rotas', ['rotas' => $rotas, 'av' => $av, 'user'=> $user, 'veiculosProprios' => $veiculosProprios, 
        'eventos' => $eventos, 'reservas2' => $reservas2, 'veiculos' => $veiculos]);
    }

    public function registrarReservaVeiculo(Request $request){

        $user = auth()->user();
        $parametros = [
            "username" => $user->username,
            "daterange1" => $request->daterange1,
            "daterange2" => $request->daterange2,
            "idVeiculo" => $request->idVeiculo,
            "observacoes" => "[Reserva realizada pelo Sistema de Viagens, referente a AV: " . $request->nrAv . "]"
        ];
    
        $curl = curl_init();
    
        curl_setopt_array($curl, [
            CURLOPT_URL => "http://10.51.10.43/reservas/public/api/inserirReservaVeiculo",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => http_build_query($parametros), // Alteração aqui
            CURLOPT_HTTPHEADER => [
                "Authorization: Bearer 123",
                "Content-Type: application/x-www-form-urlencoded" // Alteração aqui
            ],
        ]);
    
        $response = curl_exec($curl);

        $err = curl_error($curl);
    
        curl_close($curl);

        //obtem dados e manda o usuário para a view novamente-------------------------------------
        $av = Av::findOrFail($request->nrAv);//Busca a AV com base no ID
        $rotas = $av->rotas;//Busca as rotas da AV
        $veiculosProprios = VeiculoProprio::all();

        //obter dados da API
        //$eventos = [];
        $reservas2 = [];
        //$veiculos = [];

        // $url = 'http://10.51.10.43/reservas/public/api/getReservasAPI';
        // $ch = curl_init($url);
        // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // $eventos = json_decode(curl_exec($ch));

        //espera 1 segundo
        sleep(1);
        
        $url = 'http://10.51.10.43/reservas/public/api/getReservasUsuarioAPI/' . $user->username;
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $reservas2 = json_decode(curl_exec($ch));
        //crie uma coleção de $reservas2
        $reservas2 = collect($reservas2);

        //procura a reserva e pega o id
        $idReserva = null;
        if(count($reservas2) > 0){
            foreach($reservas2 as $reserva){
                //verifique se na string $reserva->observacoes contém o $request->nrAv
                if(strpos($reserva->observacoes, $request->nrAv ) !== false){
                    $idReserva = $reserva->id;
                    break;
                }
            }
        }

        //atualiza a av na coluna idReservaVeiculo com o id da reserva
        $av->idReservaVeiculo = $idReserva;
        $av->save();

        // $url = 'http://10.51.10.43/reservas/public/api/getVeiculosAPI';
        // $ch = curl_init($url);
        // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // $veiculos = json_decode(curl_exec($ch));

        if($av->isEnviadoUsuario == 1){
            return redirect('/avs/avs/')->with('msg', 'Você não tem autorização para editar uma rota de AV que já foi enviada!');
        }
        //o response é um json com um atributo message, verifique se ele é Reserva de veículo criada com sucesso!
        $responseArray = json_decode($response, true);

        if(isset($responseArray['message']) && $responseArray['message'] == 'true'){
            return redirect('/avs/concluir/' . $request->nrAv . '/nao')->with('success', 'Reserva de veículo criada com sucesso!');
        } else {
            return redirect('/avs/concluir/' . $request->nrAv . '/nao')->with('error', $responseArray['message']);
        }
    }

    public function removerReservaVeiculo($idReserva, $av){
        $user = auth()->user();
        $parametros = [
            "username" => $user->username,
            "idReserva" => $idReserva
        ];
    
        $curl = curl_init();
    
        curl_setopt_array($curl, [
            CURLOPT_URL => "http://10.51.10.43/reservas/public/api/removerReservaVeiculo",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => http_build_query($parametros), // Alteração aqui
            CURLOPT_HTTPHEADER => [
                "Authorization: Bearer 123",
                "Content-Type: application/x-www-form-urlencoded" // Alteração aqui
            ],
        ]);
    
        $response = curl_exec($curl);
        $err = curl_error($curl);
    
        curl_close($curl);

        //marque o campo idReservaVeiculo da av como null
        $avEncontrada = Av::findOrFail($av);//Busca a AV com base no ID
        $avEncontrada->idReservaVeiculo = null;
        $avEncontrada->save();
            
        $responseArray = json_decode($response, true);

        if(isset($responseArray['message']) && $responseArray['message'] == 'true'){
            return redirect('/avs/concluir/' . $av . '/nao')->with('success', 'Reserva de veículo excluída com sucesso!');
        } else {
            return redirect('/avs/concluir/' . $av . '/nao')->with('error', $responseArray['message']);
        }
    }

    public function rotasEditData($id)//Id da AV
    {
        $user = auth()->user();
        $av = Av::findOrFail($id);//Busca a AV com base no ID
        $rotas = $av->rotas;//Busca as rotas da AV
        $veiculosProprios = VeiculoProprio::all();

        return view('rotas.rotasEditData', ['rotas' => $rotas, 'av' => $av, 'user'=> $user, 'veiculosProprios' => $veiculosProprios]);
    }

    public function rotaspc($id)//Id da AV
    {
        $user = auth()->user();
        $av = Av::findOrFail($id);//Busca a AV com base no ID
        $rotas = $av->rotas;//Busca as rotas da AV
        $isViagemInternacional = null;
        foreach($rotas as $r){
            if($r->isViagemInternacional == true){
                $isViagemInternacional = true;
            }
        }

        if(count($rotas) > 0){
            $arrayDiasValores = $this->geraArrayDiasValoresCerto($av);
        }
        else{
            $arrayDiasValores = null;
        }

        return view('rotaspc.rotas', ['rotas' => $rotas, 'av' => $av, 'user'=> $user, 'arrayDiasValores' => $arrayDiasValores, 'isViagemInternacional' => $isViagemInternacional]);
    }

    public function create($id)//Id da AV
    {
        $user = auth()->user();
        $avs = $user->avs;
        $av = null;
        foreach ($avs as $a){
            if ($a->id == $id){
                $av = $a;
            }
        }
        if($av == null){
            return redirect('/avs/avs')->with('msg', 'Você não tem autorização para criar uma rota de AV de outro usuário!');
        }
        $rotas = $av->rotas;

        $isOrigemNacional = null;
        $rotaOriginal = null;
        $ultimaRotaSetada = null;
        for($i = 0; $i < count($rotas); $i++){
            if($i == 0){
                $rotaOriginal = $rotas[$i];
                if($rotas[$i]->isViagemInternacional == false){
                    $isOrigemNacional = true;
                }
            }
            if($i == count($rotas)-1){
                $ultimaRotaSetada = $rotas[$i];
            }
        }
        $veiculosProprios = $user->veiculosProprios;

        $rotas = $av->rotas;
        
        return view('rotas.createRota', ['veiculosProprios' => $veiculosProprios, 'av' => $av, 'user'=> $user, 'isOrigemNacional' => $isOrigemNacional, 
        'rotaOriginal' => $rotaOriginal, 'ultimaRotaSetada' => $ultimaRotaSetada, 'rotas' => $rotas]);
    }

    public function createInternacional($id)//Id da AV
    {
        $user = auth()->user();
        $avs = $user->avs;
        $av = null;
        foreach ($avs as $a){
            if ($a->id == $id){
                $av = $a;
            }
        }
        if($av == null){
            return redirect('/avs/avs')->with('msg', 'Você não tem autorização para criar uma rota de AV de outro usuário!');
        }
        $rotas = $av->rotas;

        $isOrigemNacional = null;
        $rotaOriginal = null;
        $ultimaRotaSetada = null;
        for($i = 0; $i < count($rotas); $i++){
            if($i == 0){
                $rotaOriginal = $rotas[$i];
                if($rotas[$i]->isViagemInternacional == false){
                    $isOrigemNacional = true;
                }
            }
            if($i == count($rotas)-1){
                $ultimaRotaSetada = $rotas[$i];
            }
        }
        $veiculosProprios = $user->veiculosProprios;

        $rotas = $av->rotas;
        
        return view('rotas.createRotaInternacional', ['veiculosProprios' => $veiculosProprios, 'av' => $av, 'user'=> $user, 'isOrigemNacional' => $isOrigemNacional, 
        'rotaOriginal' => $rotaOriginal, 'ultimaRotaSetada' => $ultimaRotaSetada, 'rotas' => $rotas]);
    }

    public function createpc($id)//Id da AV
    {
        $user = auth()->user();
        $avs = $user->avs;
        $av = null;
        foreach ($avs as $a){
            if ($a->id == $id){
                $av = $a;
            }
        }
        if($av == null){
            return redirect('/avs/avs')->with('msg', 'Você não tem autorização para criar uma rota de AV de outro usuário!');
        }

        $rotas = $av->rotas;
        $isOrigemNacional = null;
        $rotaOriginal = null;
        $ultimaRotaSetada = null;
        for($i = 0; $i < count($rotas); $i++){
            if($i == 0){
                $rotaOriginal = $rotas[$i];
                if($rotas[$i]->isViagemInternacional == false){
                    $isOrigemNacional = true;
                }
            }
            if($i == count($rotas)-1){
                $ultimaRotaSetada = $rotas[$i];
            }
        }
        $veiculosProprios = $user->veiculosProprios;
        
        return view('rotaspc.createRota', ['veiculosProprios' => $veiculosProprios, 'av' => $av, 'user'=> $user, 'isOrigemNacional' => $isOrigemNacional, 
        'rotaOriginal' => $rotaOriginal, 'ultimaRotaSetada' => $ultimaRotaSetada, 'rotas' => $rotas]);
    }

    public function store(Request $request)
    {
        $rota = new Rota();
        $rota->isViagemInternacional = $request->isViagemInternacional;

        $regras = [
            'isReservaHotel' => 'required',
            'tipoTransporte' => 'required'
        ];
        
        if($request->get('tipoTransporte')=="2"){ // Se for veículo próprio, adiciona validação de campo
            $regras += ['veiculoProprio_id' => 'required'];
        }

        if($request->get('isViagemInternacional')=="1") //Verificar se funciona como String, ou tem que ser numeral
        {
            //Adicionar regra de validação para todos os campos de viagem internacional
            $regras += ['dataHoraSaidaInternacional' => 'required'];
            $regras += ['dataHoraChegadaInternacional' => 'required'];
            
            $regras += ['selecaoContinenteOrigem' => 'required'];
            $regras += ['selecaoPaisOrigem' => 'required'];
            $regras += ['selecaoEstadoOrigem' => 'required'];
            $regras += ['selecaoCidadeOrigem' => 'required'];

            $regras += ['selecaoContinenteDestinoInternacional' => 'required'];
            $regras += ['selecaoPaisDestinoInternacional' => 'required'];
            $regras += ['selecaoEstadoDestinoInternacional' => 'required'];
            $regras += ['selecaoCidadeDestinoInternacional' => 'required'];

            //Setar os campos para viagem internacional
            $rota->dataHoraSaida = $request->dataHoraSaidaInternacional;
            $rota->dataHoraChegada = $request->dataHoraChegadaInternacional;

            $rota->continenteOrigemInternacional = $request->selecaoContinenteOrigem;
            $rota->paisOrigemInternacional = $request->selecaoPaisOrigem;
            $rota->estadoOrigemInternacional = $request->selecaoEstadoOrigem;
            $rota->cidadeOrigemInternacional = $request->selecaoCidadeOrigem;

            $rota->continenteDestinoInternacional = $request->selecaoContinenteDestinoInternacional;
            $rota->paisDestinoInternacional = $request->selecaoPaisDestinoInternacional;
            $rota->estadoDestinoInternacional = $request->selecaoEstadoDestinoInternacional;
            $rota->cidadeDestinoInternacional = $request->selecaoCidadeDestinoInternacional;
        }
        else if($request->get('isViagemInternacional')=="0"){
            //Adicionar regra de validação para todos os campos de viagem nacional
            $regras += ['dataHoraSaidaNacional' => 'required'];
            $regras += ['dataHoraChegadaNacional' => 'required'];

            $regras += ['selecaoEstadoOrigemNacional' => 'required'];
            $regras += ['selecaoCidadeOrigemNacional' => 'required'];

            $regras += ['selecaoEstadoDestinoNacional' => 'required'];
            $regras += ['selecaoCidadeDestinoNacional' => 'required'];

            //Setar os campos para viagem nacional
            $timezone = new DateTimeZone('America/Sao_Paulo');
            $rota->dataHoraSaida = new DateTime('now', $timezone);
            $rota->dataHoraChegada = new DateTime('now', $timezone);
            
            $rota->dataHoraSaida = $request->dataHoraSaidaNacional;
            $rota->dataHoraChegada = $request->dataHoraChegadaNacional;

            //Recupera o value do campo e decompõe em um JSON para obter o name
            $objetoTransformar = str_replace("'", "\"", $request->get('selecaoEstadoOrigemNacional'));
            $obj = json_decode($objetoTransformar, true);
            try {
                $rota->estadoOrigemNacional = $obj['name'];
            } catch (\Throwable $th) {}//Tenta recuperar o valor, se não der certo a validação vai avisar o usuário
            

            //Recupera o value do campo e decompõe em um JSON para obter o name
            $objetoTransformar2 = str_replace("'", "\"", $request->get('selecaoEstadoDestinoNacional'));
            $obj2 = json_decode($objetoTransformar2, true);
            try {
                $rota->estadoDestinoNacional = $obj2['name'];
            } catch (\Throwable $th) {}//Tenta recuperar o valor, se não der certo a validação vai avisar o usuário

            $rota->cidadeOrigemNacional = $request->selecaoCidadeOrigemNacional;
            $rota->cidadeDestinoNacional = $request->selecaoCidadeDestinoNacional;
        }
        //DADOS DE HOTEL E TRANSPORTE
        
       
        $rota->isReservaHotel = $request->isReservaHotel;

        //Verifica o tipo de transporte e seta o campo corretamente
        if($request->get('tipoTransporte')==0){
            $rota->isOnibusLeito = 1;
        } else if($request->get('tipoTransporte')==1){
            $rota->isOnibusConvencional = 1;
        } else if($request->get('tipoTransporte')==2){
            $rota->isVeiculoProprio = 1;
        } else if($request->get('tipoTransporte')==3){
            $rota->isVeiculoEmpresa = 1;
        } else if($request->get('tipoTransporte')==4){
            $rota->isAereo = 1;
        } else if($request->get('tipoTransporte')==5){
            $rota->isOutroMeioTransporte = 1;
        } else if($request->get('tipoTransporte')==6){
            $rota->isOutroMeioTransporte = 2;
        }
        
        $mensagens = [
            'required' => 'Este campo não pode estar em branco',
        ];

        $request->validate($regras, $mensagens);

        //CHAVES ESTRANGEIRAS
        $rota->av_id = $request->idav;
        $rota->veiculoProprio_id = $request->veiculoProprio_id;
        //Não será informado o número de veículo do Paranacidade pq quem cadastra é a Secretaria
    
        $rota->save();
        if($request->get('isViagemInternacional')=="0"){
            if($request->get('flexSwitchCheckDefault')=="on"){
                $rota2 = new Rota();
                $rota2->isViagemInternacional = false;

                $timezone = new DateTimeZone('America/Sao_Paulo');
                $rota2->dataHoraSaida = new DateTime('now', $timezone);
                $rota2->dataHoraChegada = new DateTime('now', $timezone);
                $rota2->dataHoraSaida = $request->get('dataHoraSaidaVoltaNacional');
                $rota2->dataHoraChegada = $request->get('dataHoraChegadaVoltaNacional');


                //Recupera o value do campo e decompõe em um JSON para obter o name
                $objetoTransformar = str_replace("'", "\"", $request->get('selecaoEstadoOrigemNacional'));
                $obj = json_decode($objetoTransformar, true);
                try {
                    $rota2->estadoDestinoNacional = $obj['name'];
                } catch (\Throwable $th) {}//Tenta recuperar o valor, se não der certo a validação vai avisar o usuário
                

                //Recupera o value do campo e decompõe em um JSON para obter o name
                $objetoTransformar2 = str_replace("'", "\"", $request->get('selecaoEstadoDestinoNacional'));
                $obj2 = json_decode($objetoTransformar2, true);
                try {
                    $rota2->estadoOrigemNacional = $obj2['name'];
                } catch (\Throwable $th) {}//Tenta recuperar o valor, se não der certo a validação vai avisar o usuário

                $rota2->cidadeOrigemNacional = $request->selecaoCidadeDestinoNacional;
                $rota2->cidadeDestinoNacional = $request->selecaoCidadeOrigemNacional;
                $rota2->isReservaHotel = false;

                if($request->get('tipoTransporte')==0){
                    $rota2->isOnibusLeito = 1;
                } else if($request->get('tipoTransporte')==1){
                    $rota2->isOnibusConvencional = 1;
                } else if($request->get('tipoTransporte')==2){
                    $rota2->isVeiculoProprio = 1;
                } else if($request->get('tipoTransporte')==3){
                    $rota2->isVeiculoEmpresa = 1;
                } else if($request->get('tipoTransporte')==4){
                    $rota2->isAereo = 1;
                } else if($request->get('tipoTransporte')==5){
                    $rota2->isOutroMeioTransporte = 1;
                } else if($request->get('tipoTransporte')==6){
                    $rota2->isOutroMeioTransporte = 2;
                }

                $rota2->av_id = $request->idav;
                $rota2->veiculoProprio_id = $request->veiculoProprio_id;

                $rota2->save();
            }
        }

        if($request->isPc=="sim"){
            
            //return redirect('/rotaspc/rotas/' . $request->idav )->with('msg', 'Rota editada com sucesso!');
            $avId = $request->idav;

            // Chame <form action="/avspc/concluir/{{ $av->id }}/sim" enctype="multipart/form-data">
            // Construir o HTML para o formulário usando apenas GET
            $formHtml = '
                <form id="redirectForm" action="/avspc/concluir/' . $avId . '/sim" method="GET" enctype="multipart/form-data">
                </form>
            ';

            // Construir o JavaScript para fazer o submit automático do formulário
            $scriptHtml = '
                <script>
                    document.addEventListener("DOMContentLoaded", function() {
                        document.getElementById("redirectForm").submit();
                    });
                </script>
            ';

            // Retornar o HTML completo
            
            return $formHtml . $scriptHtml;
        }
        else{
            return redirect('/rotas/rotas/' . $request->idav )->with('success', 'Rota criada com sucesso!');
        }
    }

    public function show($id)
    {
        $user = auth()->user();
        $rota = Rota::findOrFail($id);

        //$avOwner = Av::where('id', $av->user_id)->first()->toArray();

        return view('rotas.show', ['rota' => $rota, 'user'=> $user]);
    }

    public function destroy($id)
    {
        $rota = Rota::findOrFail($id);
        $idAv = $rota->av_id;
        $rota->delete();

        return redirect('/rotas/rotas/'  . $idAv)->with('msg', 'Rota excluída com sucesso!');
    }

    public function destroyRotaPc($id)
    {
        $rota = Rota::findOrFail($id);
        $idAv = $rota->av_id;
        $rota->delete();

        // Chame <form action="/avspc/concluir/{{ $av->id }}/sim" enctype="multipart/form-data">
        // Construir o HTML para o formulário usando apenas GET
        $formHtml = '
            <form id="redirectForm" action="/avspc/concluir/' . $idAv . '/sim" method="GET" enctype="multipart/form-data">
            </form>
        ';

        // Construir o JavaScript para fazer o submit automático do formulário
        $scriptHtml = '
            <script>
                document.addEventListener("DOMContentLoaded", function() {
                    document.getElementById("redirectForm").submit();
                });
            </script>
        ';

        // Retornar o HTML completo
        
        return $formHtml . $scriptHtml;
    }

    public function edit($id)
    {
        $rota = Rota::findOrFail($id);
        $idAv = $rota->av_id;

        $user = auth()->user();
        $avs = $user->avs;
        $av = null;
        foreach ($avs as $a){
            if ($a->id == $idAv){
                $av = $a;
            }
        }
        if($av->isEnviadoUsuario == 1){
            return redirect('/avs/avs/')->with('msg', 'Você não tem autorização para editar uma rota de AV de outro usuário!');
        }

        $rotas = $av->rotas;

        $veiculosProprios = $user->veiculosProprios;

        return view('rotas.editRota', ['rota' => $rota, 'av' => $av, 'veiculosProprios' => $veiculosProprios, 'user'=> $user, 'rotas' => $rotas]);
    }

    public function editInternacional($id)
    {
        $rota = Rota::findOrFail($id);
        $idAv = $rota->av_id;

        $user = auth()->user();
        $avs = $user->avs;
        $av = null;
        foreach ($avs as $a){
            if ($a->id == $idAv){
                $av = $a;
            }
        }
        if($av->isEnviadoUsuario == 1){
            return redirect('/avs/avs/')->with('msg', 'Você não tem autorização para editar uma rota de AV de outro usuário!');
        }

        $rotas = $av->rotas;

        $veiculosProprios = $user->veiculosProprios;

        return view('rotas.editRotaInternacional', ['rota' => $rota, 'av' => $av, 'veiculosProprios' => $veiculosProprios, 'user'=> $user, 'rotas' => $rotas]);
    }

    public function editRotaPCInternacional($id){
        $rota = Rota::findOrFail($id);
        $idAv = $rota->av_id;

        $user = auth()->user();
        $avs = $user->avs;
        $av = null;
        foreach ($avs as $a){
            if ($a->id == $idAv){
                $av = $a;
            }
        }

        if($av->user_id != $user->id){
            return redirect('/avs/avs/')->with('msg', 'Você não tem autorização para editar uma rota de AV de outro usuário!');
        }

        $rotas = $av->rotas;

        $veiculosProprios = $user->veiculosProprios;

        return view('rotaspc.editRotaInternacional', ['rota' => $rota, 'av' => $av, 'veiculosProprios' => $veiculosProprios, 'user'=> $user, 'rotas' => $rotas]);
    }

    public function editNovaData($id)
    {
        $rota = Rota::findOrFail($id);
        $idAv = $rota->av_id;

        $user = auth()->user();
        $avs = $user->avs;
        $av = null;
        foreach ($avs as $a){
            if ($a->id == $idAv){
                $av = $a;
            }
        }

        return view('rotas.editNovaData', ['rota' => $rota, 'av' => $av, 'user'=> $user]);
    }

    public function editRotaPc($id)
    {
        $rota = Rota::findOrFail($id);
        $idAv = $rota->av_id;

        $user = auth()->user();
        $avs = $user->avs;
        $av = null;
        foreach ($avs as $a){
            if ($a->id == $idAv){
                $av = $a;
            }
        }
        if($av == null){
            return redirect('/rotas/rotas/' . $idAv)->with('msg', 'Você não tem autorização para editar uma rota de AV de outro usuário!');
        }

        $rotas = $av->rotas;
        $veiculosProprios = $user->veiculosProprios;

        return view('rotaspc.editRota', ['rota' => $rota, 'av' => $av, 'veiculosProprios' => $veiculosProprios, 'user'=> $user, 'rotas' => $rotas]);
    }

    public function update(Request $request)
    {
        $dados = array(
            "id"=> $request->id,
            "isViagemInternacional" => $request->isViagemInternacional,
            "estadoOrigemNacional" => "",
            "cidadeOrigemNacional" => "",
            "estadoDestinoNacional" => "",
            "cidadeDestinoNacional" => "",
            "continenteOrigemInternacional" => "",
            "paisOrigemInternacional" => "",
            "estadoOrigemInternacional" => "",
            "cidadeOrigemInternacional" => "",
            "continenteDestinoInternacional" => "",
            "paisDestinoInternacional" => "",
            "estadoDestinoInternacional" => "",
            "cidadeDestinoInternacional" => "",
            "dataHoraSaida" => "",
            "dataHoraChegada" => "",
            "isReservaHotel" => $request->isReservaHotel,
            "isOnibusLeito" => "",
            "isOnibusConvencional" => "",
            "isVeiculoProprio" => "",
            "isVeiculoEmpresa" => "",
            "isAereo" => "",
            "av_id" => $request->idav,
            "veiculoProprio_id" => $request->veiculoProprio_id,
            "isOutroMeioTransporte" => ""
        );

        $regras = [
            'isReservaHotel' => 'required',
            'tipoTransporte' => 'required'
        ];
        
        if($request->get('tipoTransporte')=="2"){ // Se for veículo próprio, adiciona validação de campo
            $regras += ['veiculoProprio_id' => 'required'];
        }

        if($request->get('isViagemInternacional')=="1") //Verificar se funciona como String, ou tem que ser numeral
        {
            //Adicionar regra de validação para todos os campos de viagem internacional
            $regras += ['dataHoraSaidaInternacional' => 'required'];
            $regras += ['dataHoraChegadaInternacional' => 'required'];
            
            $regras += ['selecaoContinenteOrigem' => 'required'];
            $regras += ['selecaoPaisOrigem' => 'required'];
            $regras += ['selecaoEstadoOrigem' => 'required'];
            $regras += ['selecaoCidadeOrigem' => 'required'];

            $regras += ['selecaoContinenteDestinoInternacional' => 'required'];
            $regras += ['selecaoPaisDestinoInternacional' => 'required'];
            $regras += ['selecaoEstadoDestinoInternacional' => 'required'];
            $regras += ['selecaoCidadeDestinoInternacional' => 'required'];

            //Setar os campos para viagem internacional
            $dados["dataHoraSaida"] = $request->dataHoraSaidaInternacional;
            $dados["dataHoraChegada"] = $request->dataHoraChegadaInternacional;

            $dados["continenteOrigemInternacional"] = $request->selecaoContinenteOrigem;
            $dados["paisOrigemInternacional"] = $request->selecaoPaisOrigem;
            $dados["estadoOrigemInternacional"] = $request->selecaoEstadoOrigem;
            $dados["cidadeOrigemInternacional"] = $request->selecaoCidadeOrigem;

            $dados["continenteDestinoInternacional"] = $request->selecaoContinenteDestinoInternacional;
            $dados["paisDestinoInternacional"] = $request->selecaoPaisDestinoInternacional;
            $dados["estadoDestinoInternacional"] = $request->selecaoEstadoDestinoInternacional;
            $dados["cidadeDestinoInternacional"] = $request->selecaoCidadeDestinoInternacional;
        }
        else if($request->get('isViagemInternacional')=="0"){
            //Adicionar regra de validação para todos os campos de viagem nacional
            $regras += ['dataHoraSaidaNacional' => 'required'];
            $regras += ['dataHoraChegadaNacional' => 'required'];

            $regras += ['selecaoEstadoOrigemNacional' => 'required'];
            $regras += ['selecaoCidadeOrigemNacional' => 'required'];

            $regras += ['selecaoEstadoDestinoNacional' => 'required'];
            $regras += ['selecaoCidadeDestinoNacional' => 'required'];

            //Setar os campos para viagem nacional
            $dados["dataHoraSaida"] = $request->dataHoraSaidaNacional;
            $dados["dataHoraChegada"] = $request->dataHoraChegadaNacional;

            //Recupera o value do campo e decompõe em um JSON para obter o name
            $objetoTransformar = str_replace("'", "\"", $request->get('selecaoEstadoOrigemNacional'));
            $obj = json_decode($objetoTransformar, true);
            $dados["estadoOrigemNacional"] = $obj['name'];

            //Recupera o value do campo e decompõe em um JSON para obter o name
            $objetoTransformar2 = str_replace("'", "\"", $request->get('selecaoEstadoDestinoNacional'));
            $obj2 = json_decode($objetoTransformar2, true);
            $dados["estadoDestinoNacional"] = $obj2['name'];

            $dados["cidadeOrigemNacional"] = $request->selecaoCidadeOrigemNacional;
            $dados["cidadeDestinoNacional"] = $request->selecaoCidadeDestinoNacional;
        }

        if($request->get('tipoTransporte')==0){
            $dados["isOnibusLeito"] = 1;
            $dados["isOnibusConvencional"] = 0;
            $dados["isVeiculoProprio"] = 0;
            $dados["isVeiculoEmpresa"] = 0;
            $dados["isAereo"] = 0;
            $dados["isOutroMeioTransporte"] = 0;
        } else if($request->get('tipoTransporte')==1){
            $dados["isOnibusLeito"] = 0;
            $dados["isOnibusConvencional"] = 1;
            $dados["isVeiculoProprio"] = 0;
            $dados["isVeiculoEmpresa"] = 0;
            $dados["isAereo"] = 0;
            $dados["isOutroMeioTransporte"] = 0;
        } else if($request->get('tipoTransporte')==2){
            $dados["isOnibusLeito"] = 0;
            $dados["isOnibusConvencional"] = 0;
            $dados["isVeiculoProprio"] = 1;
            $dados["isVeiculoEmpresa"] = 0;
            $dados["isAereo"] = 0;
            $dados["isOutroMeioTransporte"] = 0;
        } else if($request->get('tipoTransporte')==3){
            $dados["isOnibusLeito"] = 0;
            $dados["isOnibusConvencional"] = 0;
            $dados["isVeiculoProprio"] = 0;
            $dados["isVeiculoEmpresa"] = 1;
            $dados["isAereo"] = 0;
            $dados["isOutroMeioTransporte"] = 0;
        } else if($request->get('tipoTransporte')==4){
            $dados["isOnibusLeito"] = 0;
            $dados["isOnibusConvencional"] = 0;
            $dados["isVeiculoProprio"] = 0;
            $dados["isVeiculoEmpresa"] = 0;
            $dados["isAereo"] = 1;
            $dados["isOutroMeioTransporte"] = 0;
        } else if($request->get('tipoTransporte')==5){
            $dados["isOnibusLeito"] = 0;
            $dados["isOnibusConvencional"] = 0;
            $dados["isVeiculoProprio"] = 0;
            $dados["isVeiculoEmpresa"] = 0;
            $dados["isAereo"] = 0;
            $dados["isOutroMeioTransporte"] = 1;
        } else if($request->get('tipoTransporte')==6){
            $dados["isOnibusLeito"] = 0;
            $dados["isOnibusConvencional"] = 0;
            $dados["isVeiculoProprio"] = 0;
            $dados["isVeiculoEmpresa"] = 0;
            $dados["isAereo"] = 0;
            $dados["isOutroMeioTransporte"] = 2;
        }

        $mensagens = [
            'required' => 'Este campo não pode estar em branco',
        ];

        //$request->validate($regras, $mensagens);

        Rota::findOrFail($request->id)->update($dados);

        if($request->isPc=="sim"){
            
            //return redirect('/rotaspc/rotas/' . $request->idav )->with('msg', 'Rota editada com sucesso!');
            $avId = $request->idav;

            // Chame <form action="/avspc/concluir/{{ $av->id }}/sim" enctype="multipart/form-data">
            // Construir o HTML para o formulário usando apenas GET
            $formHtml = '
                <form id="redirectForm" action="/avspc/concluir/' . $avId . '/sim" method="GET" enctype="multipart/form-data">
                </form>
            ';

            // Construir o JavaScript para fazer o submit automático do formulário
            $scriptHtml = '
                <script>
                    document.addEventListener("DOMContentLoaded", function() {
                        document.getElementById("redirectForm").submit();
                    });
                </script>
            ';

            // Retornar o HTML completo
            
            return $formHtml . $scriptHtml;
        }
        else{
            return redirect('/rotas/rotas/' . $request->idav )->with('msg', 'Rota editada com sucesso!');
        }
    }

    public function updateData(Request $request)
    {
        $dados = array(
            "dataHoraSaida" => "",
            "dataHoraChegada" => ""
        );

        if($request->get('isViagemInternacional')=="1")
        {
            //Setar os campos para viagem internacional
            $dados["dataHoraSaida"] = $request->dataHoraSaidaInternacional;
            $dados["dataHoraChegada"] = $request->dataHoraChegadaInternacional;
        }
        else if($request->get('isViagemInternacional')=="0"){
           
            //Setar os campos para viagem nacional
            $dados["dataHoraSaida"] = $request->dataHoraSaidaNacional;
            $dados["dataHoraChegada"] = $request->dataHoraChegadaNacional;
        }

        $rota = Rota::findOrFail($request->id);
        
        $av = Av::findOrFail($rota->av_id);
        $rotas = $av->rotas;

        //Vamos tratar os casos de atualização, verificando quando não pode e atualizar em cascata as datas modificadas

        $date1 = new DateTime($rota->dataHoraSaida);//Data de saída atual
        $diaSaida1 = $date1->format('d');
        $mesSaida1 = $date1->format('m');

        $date2 = new DateTime($dados["dataHoraSaida"]);//Data de saída a ser atualizado
        $diaSaida2 = $date2->format('d');
        $mesSaida2 = $date2->format('m');
        
        $date3 = new DateTime($rota->dataHoraChegada);//Data de saída atual
        $diaSaida3 = $date3->format('d');
        $mesSaida3 = $date3->format('m');

        $date4 = new DateTime($dados["dataHoraChegada"]);//Data de saída a ser atualizado
        $diaSaida4 = $date4->format('d');
        $mesSaida4= $date4->format('m');

        $diferencaDiaSaida = $diaSaida2-$diaSaida1;
        $diferencaDiaChegada = $diaSaida4-$diaSaida3;

        $indiceRotaAtual = 0;
        for($i=0;$i<count($rotas);$i++){ //Percorre todas as rotas da AV
            if($rotas[$i]->id == $rota->id){ // Se a rota do índice for igual a rota atual
                if($i>0){
                    $dateAnterior = new DateTime($rotas[$i - 1]->dataHoraSaida);
                    $diaDateAnterior = $dateAnterior->format('d');
                    $mesDateAnterior= $dateAnterior->format('m');

                    if($diaSaida2 < $diaDateAnterior){
                        return redirect('/rotas/rotasEditData/' . $rota->av_id)->with('msg', 'Não é possível salvar a data anterior a última rota!');
                    }
                }
                $indiceRotaAtual = $i;
             }
        }

        $rota->update($dados);

        if($diferencaDiaSaida > 0 && $diferencaDiaChegada > 0){
            if($diferencaDiaSaida == $diferencaDiaChegada){
                for($i=$indiceRotaAtual + 1;$i<count($rotas);$i++){

                    $dateNewSaida = new DateTime($rotas[$i]->dataHoraSaida);
                    $dateNewSaida->modify('+' . $diferencaDiaSaida . ' day');
                    $rotas[$i]->dataHoraSaida = $dateNewSaida;
    
                    $dateNewChegada = new DateTime($rotas[$i]->dataHoraChegada);
                    $dateNewChegada->modify('+' . $diferencaDiaChegada .' day');
                    $rotas[$i]->dataHoraChegada = $dateNewChegada;
    
                    $dados["dataHoraSaida"] = $rotas[$i]->dataHoraSaida;
                    $dados["dataHoraChegada"] = $rotas[$i]->dataHoraChegada;

                    $rotas[$i]->update($dados);
                }
            }
            else if($diferencaDiaChegada>$diferencaDiaSaida){ // Se a diferença do dia da chegada for maior que o dia de saída

                for($i=$indiceRotaAtual + 1;$i<count($rotas);$i++){
                    $dateNewSaida = new DateTime($rotas[$i]->dataHoraSaida);
                    $dateNewSaida->modify('+' . $diferencaDiaChegada . ' day');
                    $rotas[$i]->dataHoraSaida = $dateNewSaida;

                    $dateNewChegada = new DateTime($rotas[$i]->dataHoraChegada);
                    $dateNewChegada->modify('+' . $diferencaDiaChegada .' day');
                    $rotas[$i]->dataHoraChegada = $dateNewChegada;

                    $dados["dataHoraSaida"] = $rotas[$i]->dataHoraSaida;
                    $dados["dataHoraChegada"] = $rotas[$i]->dataHoraChegada;

                    $rotas[$i]->update($dados);
                }
            }
        }
        else if($diferencaDiaChegada < 0 && $diferencaDiaSaida < 0){
            if($diferencaDiaSaida == $diferencaDiaChegada){
                for($i=$indiceRotaAtual + 1;$i<count($rotas);$i++){

                    $dateNewSaida = new DateTime($rotas[$i]->dataHoraSaida);
                    $dateNewSaida->modify('-' . $diferencaDiaSaida . ' day');
                    $rotas[$i]->dataHoraSaida = $dateNewSaida;
    
                    $dateNewChegada = new DateTime($rotas[$i]->dataHoraChegada);
                    $dateNewChegada->modify('-' . $diferencaDiaChegada .' day');
                    $rotas[$i]->dataHoraChegada = $dateNewChegada;
    
                    $dados["dataHoraSaida"] = $rotas[$i]->dataHoraSaida;
                    $dados["dataHoraChegada"] = $rotas[$i]->dataHoraChegada;

                    $rotas[$i]->update($dados);
                }
            } 
            else if($diferencaDiaChegada<$diferencaDiaSaida){
                for($i=$indiceRotaAtual + 1;$i<count($rotas);$i++){

                    $dateNewSaida = new DateTime($rotas[$i]->dataHoraSaida);
                    $dateNewSaida->modify('-' . $diferencaDiaChegada . ' day');
                    $rotas[$i]->dataHoraSaida = $dateNewSaida;

                    $dateNewChegada = new DateTime($rotas[$i]->dataHoraChegada);
                    $dateNewChegada->modify('-' . $diferencaDiaChegada .' day');
                    $rotas[$i]->dataHoraChegada = $dateNewChegada;

                    $dados["dataHoraSaida"] = $rotas[$i]->dataHoraSaida;
                    $dados["dataHoraChegada"] = $rotas[$i]->dataHoraChegada;

                    $rotas[$i]->update($dados);
                }
            }
        }

        return redirect('/rotas/rotasEditData/' . $rota->av_id)->with('msg', 'Data editada com sucesso!');
    }

    public function getAllRotas()
    {
        $rotas = Rota::all();
        return response(json_encode($rotas, JSON_PRETTY_PRINT), 200)->header('Content-Type', 'application/json');
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

                $rotaAnteriorDataChegadaFormatado = null;
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
                        else if($proximaRota != false 
                        && $proximaRotaDataSaidaFormatado->format('Y-m-d') == $dia 
                        && $proximaRotaDataSaidaFormatado->format('H:i:s') > "13:01:00"
                        && ((isset($rotaAnteriorDataSaidaFormatado) && $rotaAnteriorDataSaidaFormatado->format('Y-m-d') == $dia && $rotaAnteriorDataSaidaFormatado->format('H:i:s') <= "12:00:00") || (isset($rotaAnteriorDataChegadaFormatado) && $rotaAnteriorDataChegadaFormatado->format('Y-m-d') == $dia && $rotaAnteriorDataChegadaFormatado->format('H:i:s') <= "12:00:00"))
                        ){
                            // SE A PRÓXIMA ROTA FOR NO MESMO DIA, A HORA DE SAÍDA DELA FOR MAIOR QUE 13:01 E NÃO FOR A ÚLTIMA ROTA
                            //E A ROTA ANTERIOR TIVER HORÁRIO DE SAÍDA OU CHEGADA ANTES DE 12:00
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
                            try {
                                $rotaImediatamenteAnterior = $this->buscarRotaAnterior($rota, $rotas);
                                $valor = $this->verificaValorRota($rotaImediatamenteAnterior);
                            } catch (\Throwable $th) {
                                $valor = $this->verificaValorRota($rota);
                            }
                            $valorTarde = $valor/2;
                            $temDiariaTarde = true;
                        }
                        else if($proximaRota == false && $dia != $dataUltimaRota && $dia == $dataChegadaFormatado->format('Y-m-d') && $dataChegadaFormatado->format('H:i:s') < "24:00:00"){
                        //NÃO TEM MAIS ROTAS NO DIA, SIGNFICA QUE JÁ CHEGOU E VAI FICAR NA CIDADE, A HORA DE CHEGADA É MENOR QUE 24:00, NÃO É A ÚLTIMA ROTA E CHEGOU NA CIDADE NO MESMO DIA
                            $valorTarde = $valor/2;
                            $temDiariaTarde = true;
                        }
                        else if($proximaRota == false && $dia != $dataUltimaRota && $dia != $dataChegadaFormatado->format('Y-m-d') && $dataSaidaFormatado->format('H:i:s') < "19:00:00"){
                        //NÃO TEM MAIS ROTAS NO DIA, SIGNFICA QUE JÁ CHEGOU E VAI FICAR NA CIDADE, A HORA DE SAÍDA É MENOR QUE 19:00, NÃO É A ÚLTIMA ROTA E A CHEGADA NO DESTINO VAI SER NO DIA SEGUINTE
                            $valorTarde = $valor/2;
                            $temDiariaTarde = true;
                        }
                        else if($proximaRota == false && $dia != $dataUltimaRota && $dia != $dataChegadaFormatado->format('Y-m-d') && $dia != $dataPrimeiraRota){
                        //NÃO TEM MAIS ROTAS NO DIA, SIGNFICA QUE JÁ CHEGOU E VAI FICAR NA CIDADE, NÃO É A ÚLTIMA ROTA E A CHEGADA NO DESTINO VAI SER NO DIA SEGUINTE E NÃO É A PRIMEIRA ROTA
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
                    $valor = $valorManha + $valorTarde;
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
                $rotaAnteriorDataChegadaFormatado = null;
                if($rotaAnterior != false){
                    $rotaAnteriorDataChegada = DateTime::createFromFormat('Y-m-d H:i:s', $rotaAnterior->dataHoraChegada)->format('Y-m-d H:i:s');
                    $rotaAnteriorDataChegadaFormatado = new DateTime($rotaAnteriorDataChegada);

                    $rotaAnteriorDataSaida = DateTime::createFromFormat('Y-m-d H:i:s', $rotaAnterior->dataHoraSaida)->format('Y-m-d H:i:s');
                    $rotaAnteriorDataSaidaFormatado = new DateTime($rotaAnteriorDataSaida);
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
                        else if($proximaRota != false 
                        && $proximaRotaDataSaidaFormatado->format('Y-m-d') == $dia 
                        && $proximaRotaDataSaidaFormatado->format('H:i:s') > "13:01:00"
                        && ((isset($rotaAnteriorDataSaidaFormatado) && $rotaAnteriorDataSaidaFormatado->format('Y-m-d') == $dia && $rotaAnteriorDataSaidaFormatado->format('H:i:s') <= "12:00:00") || (isset($rotaAnteriorDataChegadaFormatado) && $rotaAnteriorDataChegadaFormatado->format('Y-m-d') == $dia && $rotaAnteriorDataChegadaFormatado->format('H:i:s') <= "12:00:00"))
                        ){
                            // SE A PRÓXIMA ROTA FOR NO MESMO DIA, A HORA DE SAÍDA DELA FOR MAIOR QUE 13:01 E NÃO FOR A ÚLTIMA ROTA
                            //E A ROTA ANTERIOR TIVER HORÁRIO DE SAÍDA OU CHEGADA ANTES DE 12:00
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
                        else if ($dia == $dataUltimaRota && $proximaRota == false && $rotaAnteriorDataChegadaFormatado != null &&
                        $rotaAnteriorDataChegadaFormatado->format('H:i:s') <= "13:01:00" &&
                        $rotaAnteriorDataSaidaFormatado->format('H:i:s') <= "13:01:00" &&
                        $dataChegadaFormatado->format('H:i:s') >= "13:01:00" ){
                            //SE O DIA ATUAL FOR O DIA DA ÚLTIMA ROTA E A ROTA ANTERIOR TIVER HORÁRIO DE SAÍDA OU CHEGADA ANTES DE 13:01 E A DATA DE CHEGADA DA ÚLTIMA ROTA FOR DEPOIS DE 13:01
                            try {
                                $rotaImediatamenteAnterior = $this->buscarRotaAnterior($rota, $rotas);
                                $valor = $this->verificaValorRota($rotaImediatamenteAnterior);
                            } catch (\Throwable $th) {
                                $valor = $this->verificaValorRota($rota);
                            }
                            $valorManha = $valor / 2;
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
                            try {
                                $rotaImediatamenteAnterior = $this->buscarRotaAnterior($rota, $rotas);
                                $valor = $this->verificaValorRota($rotaImediatamenteAnterior);
                            } catch (\Throwable $th) {
                                $valor = $this->verificaValorRota($rota);
                            }
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
                    $valor = $valorManha + $valorTarde;
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
        }
        else if($rota->cidadeDestinoNacional == "Brasília" || ($rota->paisDestinoInternacional == 30 && $rota->cidadeDestinoInternacional == "Brasília")){//Se for Brasília
            $valor = 140.43;
        }
        else if(($rota->cidadeDestinoNacional == "Curitiba") ||
        ($rota->paisDestinoInternacional == 30 && $rota->estadoDestinoInternacional == "Paraná" && 
        ($rota->cidadeDestinoInternacional == "Curitiba")) ||
        $rota->cidadeDestinoNacional == "Rio de Janeiro" || $rota->cidadeDestinoNacional == "São Paulo" ||
        $rota->cidadeDestinoNacional == "Belo Horizonte" || $rota->cidadeDestinoNacional == "Salvador" ||
        $rota->cidadeDestinoNacional == "Fortaleza" || $rota->cidadeDestinoNacional == "Recife" ||
        $rota->cidadeDestinoNacional == "Porto Alegre" || $rota->cidadeDestinoNacional == "Manaus" ||
        $rota->cidadeDestinoNacional == "Belém" || $rota->cidadeDestinoNacional == "Goiânia" ||
        $rota->cidadeDestinoNacional == "São Luís" || $rota->cidadeDestinoNacional == "Maceió" ||
        $rota->cidadeDestinoNacional == "Teresina" || $rota->cidadeDestinoNacional == "João Pessoa" ||
        $rota->cidadeDestinoNacional == "Aracaju" || $rota->cidadeDestinoNacional == "Natal" ||
        $rota->cidadeDestinoNacional == "Campo Grande" || $rota->cidadeDestinoNacional == "Cuiabá" ||
        $rota->cidadeDestinoNacional == "Florianópolis" || $rota->cidadeDestinoNacional == "Vitória" ||
        $rota->cidadeDestinoNacional == "Rio Branco" || $rota->cidadeDestinoNacional == "Porto Velho" ||
        $rota->cidadeDestinoNacional == "Boa Vista" || $rota->cidadeDestinoNacional == "Macapá" ||
        $rota->cidadeDestinoNacional == "Palmas"){//Se for capital de estado (incluindo Curitiba)
            $valor = 111.38;
        }
        else{//Demais cidades (incluindo Foz do Iguaçu e outras cidades do Paraná)
            $valor = 87.17;
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

    //bucar rota posterior
    public function buscarRotaPosterior($rota, $rotas){
        $rotaPosterior = null;
        for ($i=0; $i < sizeof($rotas) ; $i++) {
            if($rota->id == $rotas[$i]->id){
                $rotaPosterior = $rotas[$i+1];
                break;
            }
        }
        return $rotaPosterior;
    }
}
