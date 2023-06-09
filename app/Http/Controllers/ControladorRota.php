<?php

namespace App\Http\Controllers;

use App\Models\Rota;
use App\Models\Av;
use App\Models\VeiculoParanacidade;
use App\Models\VeiculoProprio;
use Illuminate\Http\Request;
use DateTimeZone;
use DateTime;

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

        if($av->isEnviadoUsuario == 1){
            return redirect('/avs/avs/')->with('msg', 'Você não tem autorização para editar uma rota de AV que já foi enviada!');
        }

        return view('rotas.rotas', ['rotas' => $rotas, 'av' => $av, 'user'=> $user, 'veiculosProprios' => $veiculosProprios]);
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

        return view('rotaspc.rotas', ['rotas' => $rotas, 'av' => $av, 'user'=> $user]);
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
        
        return view('rotas.createRota', ['veiculosProprios' => $veiculosProprios, 'av' => $av, 'user'=> $user, 'isOrigemNacional' => $isOrigemNacional, 
        'rotaOriginal' => $rotaOriginal, 'ultimaRotaSetada' => $ultimaRotaSetada]);
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

        $veiculosProprios = $user->veiculosProprios;
        
        return view('rotaspc.createRota', ['veiculosProprios' => $veiculosProprios, 'av' => $av, 'user'=> $user]);
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
            if($request->get('dataHoraSaidaVoltaNacional')!=null){
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
                }

                $rota2->av_id = $request->idav;
                $rota2->veiculoProprio_id = $request->veiculoProprio_id;

                $rota2->save();
            }
        }

        if($request->isPc=="sim"){
            return redirect('/rotaspc/rotas/' . $request->idav )->with('msg', 'Rota criada com sucesso!');
        }
        else{
            return redirect('/rotas/rotas/' . $request->idav )->with('msg', 'Rota criada com sucesso!');
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

        return redirect('/rotaspc/rotas/'  . $idAv)->with('msg', 'Rota excluída com sucesso!');
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

        $veiculosProprios = $user->veiculosProprios;

        return view('rotas.editRota', ['rota' => $rota, 'av' => $av, 'veiculosProprios' => $veiculosProprios, 'user'=> $user]);
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

        $veiculosProprios = $user->veiculosProprios;

        return view('rotaspc.editRota', ['rota' => $rota, 'av' => $av, 'veiculosProprios' => $veiculosProprios, 'user'=> $user]);
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
            "veiculoProprio_id" => $request->veiculoProprio_id
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
        } else if($request->get('tipoTransporte')==1){
            $dados["isOnibusLeito"] = 0;
            $dados["isOnibusConvencional"] = 1;
            $dados["isVeiculoProprio"] = 0;
            $dados["isVeiculoEmpresa"] = 0;
            $dados["isAereo"] = 0;
        } else if($request->get('tipoTransporte')==2){
            $dados["isOnibusLeito"] = 0;
            $dados["isOnibusConvencional"] = 0;
            $dados["isVeiculoProprio"] = 1;
            $dados["isVeiculoEmpresa"] = 0;
            $dados["isAereo"] = 0;
        } else if($request->get('tipoTransporte')==3){
            $dados["isOnibusLeito"] = 0;
            $dados["isOnibusConvencional"] = 0;
            $dados["isVeiculoProprio"] = 0;
            $dados["isVeiculoEmpresa"] = 1;
            $dados["isAereo"] = 0;
        } else if($request->get('tipoTransporte')==4){
            $dados["isOnibusLeito"] = 0;
            $dados["isOnibusConvencional"] = 0;
            $dados["isVeiculoProprio"] = 0;
            $dados["isVeiculoEmpresa"] = 0;
            $dados["isAereo"] = 1;
        }

        $mensagens = [
            'required' => 'Este campo não pode estar em branco',
        ];

        //$request->validate($regras, $mensagens);

        Rota::findOrFail($request->id)->update($dados);

        if($request->isPc=="sim"){
            //return redirect('/rotaspc/rotas/' . $request->idav )->with('msg', 'Rota editada com sucesso!');
            $avId = $request->idav;
            $isPc = true;

            // Construir o HTML do formulário oculto
            $formHtml = '
                <form id="redirectForm" action="/avspc/concluir/' . $request->idav . '" method="POST">
                    <input type="hidden" name="avId" value="' . $avId . '">
                    <input type="hidden" name="isPc" value="sim">
                    ' . csrf_field() . '
                    <input type="hidden" name="_method" value="PUT">
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
}
