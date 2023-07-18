@extends('layouts.main')

@section('title', 'Editando: ' . $av->id)
@section('content')

<div class="row justify-content-start" style="padding-left: 5%">
    <div class="col-3">
        <a href="/avs/autGestor" type="submit" class="btn btn-active btn-ghost"> Voltar!</a>
    </div>
</div>
<div class="container">
        
    <div class="row">


        <div class="col-md-4 offset-md-0">
            <h1 style="font-size: 24px"><strong>Autorização de viagem nº:</strong> {{ $av->id }}</h1>
            <h1 style="font-size: 24px"><strong>Status atual:</strong> {{ $av->status }}</h1>
            <p class="av-owner" style="font-size: 20px"><ion-icon name="chevron-forward-circle-outline">
            </ion-icon> <strong>Nome do usuário: </strong> 
            @foreach($users as $u)
                    @if ($u->id == $av->user_id)
                        {{ $u->name }}
                    @endif
            @endforeach
            </p>        
            <p class="av-owner" style="font-size: 20px"><ion-icon name="chevron-forward-circle-outline">
            </ion-icon> <strong>E-mail do usuário: </strong> 
            @foreach($users as $u)
                    @if ($u->id == $av->user_id)
                        {{ $u->username }}
                    @endif
            @endforeach
            </p>  

                <div >
                    <label for="my-modal-3" class="btn">Histórico</label>
                    <label for="my-modal-4" class="btn">Dados da AV</label>
                    @if($isInternacional != true)
                        <label for="my-modal-5" class="btn">FLUXO</label>
                    @endif
                    @if($av->objetivo_id == 3)
                        <label for="my-modal-14" class="btn" >Medições</label>
                    @endif
                    <br>
                    
                </div>
                <div class="divider"></div> 
        </div>

        <div class="col-md-8 offset-md-0">
            <h1 style="font-size: 24px"><strong>Trajeto: </strong></h1>
            <table id="tabelaRota" class="display nowrap" style="width:100%">
                <thead>
                    <tr>
                        <th>Tipo</th>
                        <th>Cidade de saída</th>
                        <th>Data/Hora de saída</th>
                        <th>Cidade de chegada</th>
                        <th>Data/Hora de chegada</th>
                        <th>Hotel?</th>
                        <th>Tipo de transporte</th>
                        @foreach($av->rotas as $rota)
                                @if($rota->isVeiculoEmpresa == 1)
                                    <th>Veículo</th>
                                    @break
                                @endif
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach($av->rotas as $rota)
                    <tr>
                        <td> {{$rota->isViagemInternacional == 1 ? "Internacional" : "Nacional"}} </td>
                        <td> 
                            @if($rota->isAereo == 1)
                                <img src="{{asset('/img/aviaosubindo.png')}}" style="width: 40px" >
                            @endif
        
                            @if($rota->isVeiculoProprio == 1 || $rota->isVeiculoEmpresa == 1)
                                <img src="{{asset('/img/carro.png')}}" style="width: 40px" >
                            @endif
        
                            @if($rota->isOnibusLeito == 1 || $rota->isOnibusConvencional == 1)
                                <img src="{{asset('/img/onibus.png')}}" style="width: 40px" >
                            @endif
        
                            {{$rota->isViagemInternacional == 0 ? $rota->cidadeOrigemNacional : $rota->cidadeOrigemInternacional}} 
                            
                        </td>
                        <td> {{ date('d/m/Y H:i', strtotime($rota->dataHoraSaida)) }} </td>
        
                        <td> 
                            @if($rota->isAereo == 1)
                                <img src="{{asset('/img/aviaodescendo.png')}}" style="width: 40px" >
                            @endif
        
                            @if($rota->isVeiculoProprio == 1 || $rota->isVeiculoEmpresa == 1)
                                <img src="{{asset('/img/carro.png')}}" style="width: 40px" >
                            @endif
        
                            @if($rota->isOnibusLeito == 1 || $rota->isOnibusConvencional == 1)
                                <img src="{{asset('/img/onibus.png')}}" style="width: 40px" >
                            @endif
        
                            {{$rota->isViagemInternacional == 0 ? $rota->cidadeDestinoNacional : $rota->cidadeDestinoInternacional}} 
                        </td>
        
                        <td> {{ date('d/m/Y H:i', strtotime($rota->dataHoraChegada)) }} </td>
                        <td> {{ $rota->isReservaHotel == 1 ? "Sim" : "Não"}}</td>
                        <td> 
                            {{ $rota->isOnibusLeito == 1 ? "Onibus leito" : ""}}
                            {{ $rota->isOnibusConvencional == 1 ? "Onibus convencional" : ""}}
                            @if($rota->isVeiculoProprio == 1)
                                {{"Veículo próprio: "}} <br>
                                @foreach ($veiculosProprios as $v)

                                    @if($v->id == $rota->veiculoProprio_id)
                                        {{$v->modelo . '-' . $v->placa}}
                                    @endif
                                    
                                @endforeach
                                
                                @if(count($veiculosProprios) == 0)
                                    {{"Não encontrado"}}
                                @endif
                            @endif
                            {{ $rota->isVeiculoEmpresa == 1 ? "Veículo empresa" : ""}}
                            {{ $rota->isAereo == 1 ? "Aéreo" : ""}}
                        </td>
                        @php
                            $achouVeiculo = false;
                        @endphp
                        @if($rota->isVeiculoEmpresa == 1)
                            @foreach($veiculosParanacidade as $v)
                                    @if($rota->veiculoParanacidade_id == $v->id)
                                        @php
                                            $achouVeiculo = true;
                                        @endphp
                                    @endif
                            @endforeach
                            @if($achouVeiculo == true)
                                <td>
                                    {{ $v->modelo }} ({{ $v->placa }})
                                </td>
                            @else
                                <td>
                                    A definir
                                </td>
                            @endif
                        @endif
                    </tr>
                    @endforeach
                </tbody>
            </table>
            
        </div>
    </div>

        <div class="divider"></div> 

</div>

<div class="container d-none d-sm-block"> 
    <div class="stat-title">Controle de diárias:
    </div>
        Mês saída: {{$mesSaidaInicial}} 
        Mês chegada: {{$mesChegadaFinal}} <br>
        <ul class="steps">

            @if($mesSaidaInicial != $mesChegadaFinal)

                @php
                    $data = "$anoSaidaInicial-$mesSaidaInicial-$diaSaidaInicial";
                    $ultimoDiaMes = date('t', strtotime($data));
                    $j=0;
                @endphp
                @for($i = $arrayDiasValores[0]['dia']; $i <= $ultimoDiaMes; $i++)
                    <li class="step step-primary" data-content="{{$i}}">
                        @if($i == $diaSaidaInicial)
                            <div class="badge badge-outline">Hora saída: {{$horaSaidaInicial}}:{{$minutoSaidaInicial}}</div>
                        @endif
                        @if($i == $diaChegadaInicial)
                            <div class="badge badge-outline">Hora chegada: {{$horaSaidaInicial}}:{{$minutoSaidaInicial}}</div>
                        @endif
                        @if($i == $diaChegadaFinal)
                            <div class="badge badge-outline">Hora chegada: {{$horaChegadaFinal}}:{{$minutoChegadaFinal}}</div>
                        @endif
                        @if($i == $diaSaidaFinal)
                            <div class="badge badge-outline">Hora saída: {{$horaSaidaFinal}}:{{$minutoSaidaFinal}}</div>
                        @endif
                        <div>
                            @if($i ==  $diaSaidaInicial && $horaSaidaInicial < 12 )
                                <div class="stats stats-vertical bg-warning shadow rounded-none">Diária Inteira: <br>R${{$arrayDiasValores[$j]['valor']}}</div>
                            @endif
                            @if($i ==  $diaSaidaInicial && $horaSaidaInicial >= 13 && $minutoSaidaInicial > 1)
                                <div class="stats stats-vertical bg-green-500 shadow rounded-none">Meia Diária: <br>R${{$arrayDiasValores[$j]['valor']}}</div>
                            @endif
                            @if($i !=  $diaSaidaInicial && $i !=  $diaChegadaFinal)
                                <div class="stats stats-vertical bg-warning shadow rounded-none">Diária Inteira: <br>R${{$arrayDiasValores[$j]['valor']}}</div>    
                            @endif
                        </div>
                    </li>
                    @php
                        $j++;
                    @endphp
                @endfor
                @for($i = 1; $i <= $diaChegadaFinal; $i++)
                    <li class="step step-primary" data-content="{{$i}}">
                        @if($i == $diaSaidaFinal)
                            <div class="badge badge-outline">Hora saída: {{$horaSaidaFinal}}:{{$minutoSaidaFinal}}</div>
                        @endif
                        @if($i == $diaChegadaFinal)
                            <div class="badge badge-outline">Hora chegada: {{$horaChegadaFinal}}:{{$minutoChegadaFinal}}</div>
                        @endif
                        <div>
                            @if($i ==  $diaSaidaInicial && $horaSaidaInicial < 12 )
                                <div class="stats stats-vertical bg-warning shadow rounded-none">Diária Inteira: <br>R${{$arrayDiasValores[$j]['valor']}}</div>
                            @endif
                            @if($i ==  $diaSaidaInicial && $horaSaidaInicial >= 13 && $minutoSaidaInicial > 1)
                                <div class="stats stats-vertical bg-green-500 shadow rounded-none">Meia Diária: <br>R${{$arrayDiasValores[$j]['valor']}}</div>
                            @endif
                            @if($i !=  $diaSaidaInicial && $i !=  $diaChegadaFinal)
                                <div class="stats stats-vertical bg-warning shadow rounded-none">Diária Inteira: <br>R${{$arrayDiasValores[$j]['valor']}}</div>    
                            @endif

                            @if($i ==  $diaChegadaFinal && $horaChegadaFinal >= 13 && $horaChegadaFinal <19)
                                <div class="stats stats-vertical bg-green-500 shadow rounded-none">Meia Diária: <br>R${{$arrayDiasValores[$j]['valor']}}</div>
                            @endif
                            @if($i ==  $diaChegadaFinal && $horaChegadaFinal >=19)
                                <div class="stats stats-vertical bg-warning shadow rounded-none">Diária Inteira: <br>R${{$arrayDiasValores[$j]['valor']}}</div>    
                            @endif
                        </div>
                    </li>
                    @php
                        $j++;
                    @endphp
                @endfor
            @else
                    @php
                        $j=0;
                    @endphp
                @for($i = $arrayDiasValores[0]['dia']; $i <= $diaChegadaFinal; $i++)
                    
                    <li class="step step-primary" data-content="{{sprintf('%d', $i)}}">
                        @if($i == $diaSaidaInicial)
                            <div class="badge badge-outline">Hora saída: {{$horaSaidaInicial}}:{{$minutoSaidaInicial}}</div>
                        @endif
                        @if($i == $diaChegadaInicial)
                            <div class="badge badge-outline">Hora chegada: {{$horaChegadaInicial}}:{{$minutoChegadaInicial}}</div>
                        @endif
                        @if($i == $diaSaidaFinal)
                            <div class="badge badge-outline">Hora saída: {{$horaSaidaFinal}}:{{$minutoSaidaFinal}}</div>
                        @endif
                        @if($i == $diaChegadaFinal)
                            <div class="badge badge-outline">Hora chegada: {{$horaChegadaFinal}}:{{$minutoChegadaFinal}}</div>
                        @endif

                        <div>
                            @if($i ==  $diaSaidaInicial && $horaSaidaInicial < 12 )
                                <div class="stats stats-vertical bg-warning shadow rounded-none">Diária Inteira: <br>R${{$arrayDiasValores[$j]['valor']}}</div>
                            @endif
                            @if(($i ==  $diaSaidaInicial && $horaSaidaInicial > 13) || ($i ==  $diaSaidaInicial && $horaSaidaInicial == 13 && $minutoSaidaInicial >= 1))
                                <div class="stats stats-vertical bg-green-500 shadow rounded-none">Meia Diária: <br>R${{$arrayDiasValores[$j]['valor']}}</div>
                            @endif
                            @if($i !=  $diaSaidaInicial && $i !=  $diaChegadaFinal)
                                <div class="stats stats-vertical bg-warning shadow rounded-none">Diária Inteira: <br>R${{$arrayDiasValores[$j]['valor']}}</div>    
                            @endif

                            @if(($i ==  $diaChegadaFinal && $horaChegadaFinal > 13 && $horaChegadaFinal <=19 && $minutoChegadaFinal == 0) || 
                            ($i ==  $diaChegadaFinal && $horaChegadaFinal > 13 && $horaChegadaFinal <19) ||
                            ($i ==  $diaChegadaFinal && $horaChegadaFinal == 13 && $minutoChegadaFinal >= 1 && $horaChegadaFinal <19))
                                <div class="stats stats-vertical bg-green-500 shadow rounded-none">Meia Diária: <br>R${{$arrayDiasValores[$j]['valor']}}</div>
                            @endif
                            @if(($i ==  $diaChegadaFinal && $horaChegadaFinal >19) || ($i ==  $diaChegadaFinal && $horaChegadaFinal ==19 && $minutoChegadaFinal >= 1))
                                <div class="stats stats-vertical bg-warning shadow rounded-none">Diária Inteira: <br>R${{$arrayDiasValores[$j]['valor']}}</div>    
                            @endif
                        
                        </div>
                    </li>
                    @php
                        $j++;
                    @endphp
                @endfor
            @endif
        </ul>
</div>

<div class="divider"></div> 

<div class="container">
    <div class="flex flex-row">
        <form action="/avs/gestorAprovarAv" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
                <input type="text" hidden="true" id="id" name="id" value="{{ $av->id }}">
                <label for="comentario">Comentário na aprovação: </label>
                <br>
                <textarea type="text" class="textarea textarea-bordered h-24" 
                    name="comentario" style="width: 200px"
                    id="comentario" placeholder="Comentário"></textarea>
    
                <button type="submit" class="btn btn-active btn-success">Aprovar AV</button>
        </form>
        
        <form action="/avs/gestorReprovarAv" method="POST" enctype="multipart/form-data" style="padding-left: 10px">
            @csrf
            @method('PUT')
                <input type="text" hidden="true" id="id" name="id" value="{{ $av->id }}">
                <label for="comentario">Comentário na reprovação: </label>
                <br>
                <textarea type="text" class="textarea textarea-bordered h-24 {{ $errors->has('comentario') ? 'is-invalid' :''}}" 
                    name="comentario" style="width: 200px"
                    id="comentario" placeholder="Comentário"></textarea>
                <button type="submit" class="btn btn-active btn-error">Reprovar AV</button>
                @if ($errors->has('comentario'))
                        <div class="invalid-feedback">
                            {{ $errors->first('comentario') }}
                        </div>
                @endif
        </form>
    </div>
</div>


    <input type="checkbox" id="my-modal-3" class="modal-toggle"/>

    <div class="modal">
        <div class="modal-box w-11/12 max-w-7xl">
            <div class="modal-content">
                <label for="my-modal-3" class="btn btn-sm btn-circle absolute right-0 top-0" >✕</label>
                <br>
                <h3 class="text-lg font-bold" style="padding-left: 10%">Histórico</h3>
                <table id="minhaTabela" class="display nowrap">
                    <!-- head -->
                    <thead>
                    <tr>
                        <th>Id</th>
                        <th>Data</th>
                        <th>Ocorrência</th>
                        <th>Comentário</th>
                        <th>Perfil</th>
                        <th>Quem comentou?</th>
                    </tr>
                    </thead>
                    <tbody>
                    <!-- row 1 -->
    
                    @foreach ($historicos as $historico)
                            <tr>
                                <td>{{ $historico->id }}</td>
                                <td>{{ $historico->dataOcorrencia }}</td>
                                <td>{{ $historico->tipoOcorrencia }}</td>
                                <td>{{ $historico->comentario }}</td>
                                <td>{{ $historico->perfilDonoComentario }}</td>
                                
                                @foreach($users as $u)
                                    @if ($u->id == $historico->usuario_comentario_id)
                                        <td>{{ $u->name }}</td>
                                    @endif
                                @endforeach
                            </tr>
                    @endforeach
    
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <input type="checkbox" id="my-modal-4" class="modal-toggle" />

    <div class="modal">
        <div class="modal-box w-11/12 max-w-2xl">
            <div class="modal-content">
                <label for="my-modal-4" class="btn btn-sm btn-circle absolute right-2 top-2">✕</label>
                <br>
                <h3 class="text-lg font-bold" style="padding-left: 10%">Dados</h3>

                <br>
                <h1 class="text-lg font-bold">Dados básicos:</h1>
                <div class="stats stats-vertical shadow">
                    <p class="av-owner" style="font-size: 20px"><ion-icon name="chevron-forward-circle-outline">
                    </ion-icon> <strong>Nome do usuário: </strong> 
                    @foreach($users as $u)
                            @if ($u->id == $av->user_id)
                                {{ $u->name }}
                            @endif
                    @endforeach
                    </p>        
                    <p class="av-owner" style="font-size: 20px"><ion-icon name="chevron-forward-circle-outline">
                    </ion-icon> <strong>E-mail do usuário: </strong> 
                    @foreach($users as $u)
                            @if ($u->id == $av->user_id)
                                {{ $u->username }}
                            @endif
                    @endforeach
                    </p>
                    <p class="av-owner" style="font-size: 20px"><ion-icon name="calendar-outline"></ion-icon> <strong>Data de criação: </strong> {{ date('d/m/Y', strtotime($av->dataCriacao)) }} </p>
                    <p class="av-owner" style="font-size: 20px"><ion-icon name="flag-outline">
                    </ion-icon> <strong>Objetivo:</strong> 

                    @for($i = 0; $i < count($objetivos); $i++)

                        @if ($av->objetivo_id == $objetivos[$i]->id )
                                {{$objetivos[$i]->nomeObjetivo}}     
                        @endif
    
                    @endfor

                    @if (isset($av->outroObjetivo))
                            {{$av->outroObjetivo }} 
                    @endif

                </p>
                    <p class="av-owner" style="font-size: 20px"><ion-icon name="pricetag-outline"></ion-icon> <strong>Comentário:</strong> {{ $av->comentario }} </p>
                    <p class="av-owner" style="font-size: 20px"><ion-icon name="chevron-forward-circle-outline"></ion-icon> <strong>Status:</strong>  {{ $av->status }} </p>
                </div>
                <br>
                <h1 class="text-lg font-bold">Dados bancários:</h1>
                
                <div class="stats stats-vertical shadow">
  
                    <p class="av-owner" style="font-size: 20px"><ion-icon name="business-outline"></ion-icon> <strong>Banco:</strong> {{ $av->banco }} </p>
                    <p class="av-owner" style="font-size: 20px"><ion-icon name="home-outline"></ion-icon> <strong>Agência:</strong> {{ $av->agencia }} </p>
                    <p class="av-owner" style="font-size: 20px"><ion-icon name="wallet-outline"></ion-icon> <strong>Conta:</strong> {{ $av->conta }} </p>
                    <p class="av-owner" style="font-size: 20px"><ion-icon name="cash-outline"></ion-icon> <strong>Pix:</strong> {{ $av->pix }} </p>
                    
                </div>
                <br>

                <h1 class="text-lg font-bold">Adiantamentos:</h1>
                <div class="stats stats-vertical shadow">
                    <p class="av-owner" style="font-size: 20px; color: black; background-color: chartreuse"><ion-icon name="cash-outline"></ion-icon> <strong>Valor em reais:</strong> R$ {{ $av->valorReais }}</p>
                    <p class="av-owner" style="font-size: 20px; color: black; background-color: chartreuse"><ion-icon name="cash-outline"></ion-icon> <strong>Valor em dolar:</strong> $ {{ $av->valorDolar }}</p>
                    <p class="av-owner" style="font-size: 20px; color: black; background-color: coral"><ion-icon name="cash-outline"></ion-icon> <strong>Valor extra em reais:</strong> R$ {{ $av->valorExtraReais }}</p>
                    <p class="av-owner" style="font-size: 20px; color: black; background-color: coral"><ion-icon name="cash-outline"></ion-icon> <strong>Valor extra em dólar:</strong> $ {{ $av->valorExtraDolar }}</p>
                    <p class="av-owner" style="font-size: 20px; color: black; background-color: rgb(255, 58, 98)"><ion-icon name="cash-outline"></ion-icon> <strong>Valor dedução em reais:</strong> R$ {{ $av->valorDeducaoReais }}</p>
                    <p class="av-owner" style="font-size: 20px; color: black; background-color: rgb(255, 58, 98)"><ion-icon name="cash-outline"></ion-icon> <strong>Valor dedução em dólar:</strong> $ {{ $av->valorDeducaoDolar }}</p>
                    <p class="av-owner" style="font-size: 20px; color: black; background-color: deepskyblue"><ion-icon name="chevron-forward-circle-outline"></ion-icon> <strong>Justificativa valor extra:</strong> {{ $av->justificativaValorExtra }}</p>
                    
                    @if($av->autorizacao != null)
                        <a href="{{ asset('AVs/' . $userAv->name . '/autorizacaoAv' . '/' . $av->autorizacao) }}" 
                            target="_blank" class="btn btn-active btn-success btn-sm">Documento de Autorização</a>
                    @endif
                </div>

            </div>
        </div>
    </div>

    <input type="checkbox" id="my-modal-5" class="modal-toggle" />

    <div class="modal">
        <div class="modal-box w-11/12 max-w-7xl">
            <div class="modal-content">
                <label for="my-modal-5" class="btn btn-sm btn-circle absolute right-2 top-2">✕</label>
                <br>
                <h3 class="text-lg font-bold" style="padding-left: 10%">Fases</h3>
                <br>
                <div style="padding-left: 10px">
                    <div class="badge badge-warning gap-2">PC = Prestação de Contas</div>
                    <div class="badge badge-error gap-2">Se carro particular</div>
                </div>
                <br>
                <div style="padding-left: 10px">

                    <ol class="items-center w-full space-y-4 sm:flex sm:space-x-8 sm:space-y-0">
                        <li class="flex items-center text-blue-600 dark:text-blue-500 space-x-2.5">
                            @if($av->isEnviadoUsuario == 1)
                                <span class="flex items-center justify-center w-8 h-8 border border-blue-600 rounded-full shrink-0 dark:bg-green-900">
                                    <svg aria-hidden="true" class="w-5 h-5 text-green-500 dark:text-green-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                                </span>
                            @else
                                <span class="flex items-center justify-center w-8 h-8 border border-gray-500 rounded-full shrink-0 dark:border-gray-400">
                                    1
                                </span>
                            @endif
                            <span>
                                <h3 class="font-medium leading-tight">Usuário</h3>
                                <p class="text-sm">Preenchimento da AV</p>
                            </span>
                        </li>
                        <li class="flex items-center text-gray-500 dark:text-gray-400 space-x-2.5">
                            @if($av->isAprovadoGestor == 1)
                                <span class="flex items-center justify-center w-8 h-8 border border-blue-600 rounded-full shrink-0 dark:bg-green-900">
                                    <svg aria-hidden="true" class="w-5 h-5 text-green-500 dark:text-green-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                                </span>
                            @else
                                <span class="flex items-center justify-center w-8 h-8 border border-gray-500 rounded-full shrink-0 dark:border-gray-400">
                                    2
                                </span>
                            @endif
                            <span>
                                <h3 class="font-medium leading-tight">Gestor:</h3>
                                <div class="badge badge-outline">Avaliação inicial</div>
                            </span>
                        </li>
                        <li class="flex items-center text-gray-500 dark:text-gray-400 space-x-2.5">
                            @if($av->isVistoDiretoria == 1)
                                <span class="flex items-center justify-center w-8 h-8 border border-blue-600 rounded-full shrink-0 dark:bg-green-900">
                                    <svg aria-hidden="true" class="w-5 h-5 text-green-500 dark:text-green-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                                </span>
                            @else
                                <span class="flex items-center justify-center w-8 h-8 border border-gray-500 rounded-full shrink-0 dark:border-gray-400">
                                    3
                                </span>
                            @endif
                            <span>
                                <h3 class="font-medium leading-tight">DAF:</h3>
                                <div class="badge badge-error gap-2">Avalia pedido</div>
                            </span>
                        </li>
                        <li class="flex items-center text-gray-500 dark:text-gray-400 space-x-2.5">
                            @if($av->isRealizadoReserva == 1)
                                <span class="flex items-center justify-center w-8 h-8 border border-blue-600 rounded-full shrink-0 dark:bg-green-900">
                                    <svg aria-hidden="true" class="w-5 h-5 text-green-500 dark:text-green-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                                </span>
                            @else
                                <span class="flex items-center justify-center w-8 h-8 border border-gray-500 rounded-full shrink-0 dark:border-gray-400">
                                    4
                                </span>
                            @endif
                            <span>
                                <h3 class="font-medium leading-tight">CAD - Coordenadoria Administrativa:</h3>
                                <div class="badge badge-outline">Realiza reservas</div>
                            </span>
                        </li>
                        <li class="flex items-center text-gray-500 dark:text-gray-400 space-x-2.5">
                            @if($av->isAprovadoFinanceiro == 1)
                                <span class="flex items-center justify-center w-8 h-8 border border-blue-600 rounded-full shrink-0 dark:bg-green-900">
                                    <svg aria-hidden="true" class="w-5 h-5 text-green-500 dark:text-green-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                                </span>
                            @else
                                <span class="flex items-center justify-center w-8 h-8 border border-gray-500 rounded-full shrink-0 dark:border-gray-400">
                                    4
                                </span>
                            @endif
                            <span>
                                <h3 class="font-medium leading-tight">CFI - Coordenadoria Financeira:</h3>
                                <div class="badge badge-outline">Adiantamento</div>
                            </span>
                        </li>
                        
                    </ol>
                </div>
                <div class="divider"></div> 

                <div style="padding-left: 10px">
    
                    <ol class="items-center w-full space-y-4 sm:flex sm:space-x-8 sm:space-y-0">
                        <li class="flex items-center text-blue-600 dark:text-blue-500 space-x-2.5">
                            <span class="flex items-center justify-center w-8 h-8 border border-blue-600 rounded-full shrink-0 dark:border-gray-400">
                                5
                            </span>
                            <span>
                                <h3 class="font-medium leading-tight">Viagem</h3>
                            </span>
                        </li>
                        <li class="flex items-center text-gray-500 dark:text-gray-400 space-x-2.5">
                            @if($av->isPrestacaoContasRealizada == 1)
                                <span class="flex items-center justify-center w-8 h-8 border border-blue-600 rounded-full shrink-0 dark:bg-green-900">
                                    <svg aria-hidden="true" class="w-5 h-5 text-green-500 dark:text-green-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                                </span>
                            @else
                                <span class="flex items-center justify-center w-8 h-8 border border-gray-500 rounded-full shrink-0 dark:border-gray-400">
                                    6
                                </span>
                            @endif
                            <span>
                                <h3 class="font-medium leading-tight">Usuário:</h3>
                                <div class="badge badge-warning gap-2">Realiza PC</div>
                            </span>
                        </li>
                        <li class="flex items-center text-gray-500 dark:text-gray-400 space-x-2.5">
                            @if($av->isFinanceiroAprovouPC == 1)
                                <span class="flex items-center justify-center w-8 h-8 border border-blue-600 rounded-full shrink-0 dark:bg-green-900">
                                    <svg aria-hidden="true" class="w-5 h-5 text-green-500 dark:text-green-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                                </span>
                            @else
                                <span class="flex items-center justify-center w-8 h-8 border border-gray-500 rounded-full shrink-0 dark:border-gray-400">
                                    7
                                </span>
                            @endif
                            <span>
                                <h3 class="font-medium leading-tight">Financeiro:</h3>
                                <div class="badge badge-warning gap-2">Avalia PC</div>
                            </span>
                        </li>
                        <li class="flex items-center text-gray-500 dark:text-gray-400 space-x-2.5">
                            @if($av->isGestorAprovouPC == 1)
                                <span class="flex items-center justify-center w-8 h-8 border border-blue-600 rounded-full shrink-0 dark:bg-green-900">
                                    <svg aria-hidden="true" class="w-5 h-5 text-green-500 dark:text-green-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                                </span>
                            @else
                                <span class="flex items-center justify-center w-8 h-8 border border-gray-500 rounded-full shrink-0 dark:border-gray-400">
                                    8
                                </span>
                            @endif
                            <span>
                                <h3 class="font-medium leading-tight">Gestor:</h3>
                                <div class="badge badge-warning gap-2">Avalia PC</div>
                            </span>
                        </li>
                        <li class="flex items-center text-gray-500 dark:text-gray-400 space-x-2.5">
                            @if($av->isAcertoContasRealizado == 1)
                                <span class="flex items-center justify-center w-8 h-8 border border-blue-600 rounded-full shrink-0 dark:bg-green-900">
                                    <svg aria-hidden="true" class="w-5 h-5 text-green-500 dark:text-green-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                                </span>
                            @else
                                <span class="flex items-center justify-center w-8 h-8 border border-gray-500 rounded-full shrink-0 dark:border-gray-400">
                                    9
                                </span>
                            @endif
                            <span>
                                <h3 class="font-medium leading-tight">Financeiro:</h3>
                                <div class="badge badge-info gap-2">Acerto de Contas</div>
                            </span>
                        </li>
                    </ol>
                </div>
                <br>
            </div>
        </div>
    </div>

    <input type="checkbox" id="my-modal-14" class="modal-toggle" />

    <div class="modal">
        <div class="modal-box w-11/12 max-w-7xl">
            <div class="modal-content">
                <label for="my-modal-14" class="btn btn-sm btn-circle absolute right-0 top-0">✕</label>
                <br>
                
                <h1 style="font-size: 24px; padding-bottom: 20px"><strong>Medições vinculadas:</strong></h1>
                        <table id="minhaTabela8" class="display nowrap" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Nome do município</th>
                                    <th>Número do projeto</th>
                                    <th>Número do lote</th>
                                    <th>Número da medição</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($medicoesFiltradas as $med)
                                    <tr>
                                        <td> {{$med->nome_municipio}} </td>
                                        <td> {{$med->numero_projeto}} </td>
                                        <td> {{$med->numero_lote}} </td>
                                        <td> {{$med->numero_medicao}} </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
            </div>
        </div>
    </div>
    
@endsection

{{-- Para implementação futura de AJAX --}} 
@section('javascript')
    <script type="text/javascript">

        $(document).ready(function(){
            $('#minhaTabela').DataTable({
                    scrollY: 500,
                    "language": {
                        "lengthMenu": "Mostrando _MENU_ registros por página",
                        "zeroRecords": "Nada encontrado",
                        "info": "Mostrando página _PAGE_ de _PAGES_",
                        "infoEmpty": "Nenhum registro disponível",
                        "infoFiltered": "(filtrado de _MAX_ registros no total)",
                        "search": "Pesquisar"
                    }
                });
        });

        $(document).ready(function(){
            $('#tabelaRota').DataTable({
                    scrollY: 300,
                    scrollX: true,
                    "language": {
                        "lengthMenu": "Mostrando _MENU_ registros por página",
                        "zeroRecords": "Nada encontrado",
                        "info": "Mostrando página _PAGE_ de _PAGES_",
                        "infoEmpty": "Nenhum registro disponível",
                        "infoFiltered": "(filtrado de _MAX_ registros no total)",
                        "search": "Procure uma AV"
                    }
            });
            $('#minhaTabela8').DataTable({
                    scrollY: 200,
                    "language": {
                        "lengthMenu": "Mostrando _MENU_ registros por página",
                        "zeroRecords": "Nada encontrado",
                        "info": "Mostrando página _PAGE_ de _PAGES_",
                        "infoEmpty": "Nenhum registro disponível",
                        "infoFiltered": "(filtrado de _MAX_ registros no total)",
                        "search": "Pesquisar"
                    }
            });
        });

    </script>
@endsection