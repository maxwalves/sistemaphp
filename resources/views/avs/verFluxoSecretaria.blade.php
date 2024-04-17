@extends('adminlte::page')

@section('title', 'Aprovação CAD')

@section('content_header')
@stop

@section('content')

<div class="tab-pane fade show active" id="custom-tabs-five-overlay" role="tabpanel" aria-labelledby="custom-tabs-five-overlay-tab" style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.5); z-index: 9999; display: none">
    <div class="overlay-wrapper" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); text-align: center; color: #ffffff;">
            <i class="fas fa-3x fa-sync-alt fa-spin" style="margin-bottom: 10px;"></i>
            <div class="text-bold pt-2">Carregando...</div>
    </div>
</div>

    <div class="row justify-content-start" style="padding-left: 5%">
        <div class="col-9">
            <br>
            <h2>Avaliação CAD</h2>
            @if ($av->isCancelado == 1 && $av->isRealizadoReserva == 1)
                <div class="alert alert-warning">
                    <span><strong>A AV Atual foi cancelada pelo usuário. Verifique as reservas e realize os devidos
                            cancelamentos.</strong></span>
                </div>
            @endif
        </div>
        <div class="col-3">
            <br>
            <a href="/avs/autSecretaria" type="submit" class="btn btn-active btn-warning"><i class="fas fa-arrow-left"></i></a>
        </div>
    </div>
    <br>
    <div>

        <div class="col-md-12 col-sm-6">
            <div class="card card-primary card-outline card-tabs">
                <div class="card-header p-0 pt-1 border-bottom-0">
                    <ul class="nav nav-tabs" id="custom-tabs-three-tab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="custom-tabs-three-home-tab" data-toggle="pill"
                                href="#custom-tabs-three-home" role="tab" aria-controls="custom-tabs-three-home"
                                aria-selected="true">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="custom-tabs-three-historico-tab" data-toggle="pill"
                                href="#custom-tabs-three-historico" role="tab"
                                aria-controls="custom-tabs-three-historico" aria-selected="false">Histórico</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="custom-tabs-three-dados-tab" data-toggle="pill"
                                href="#custom-tabs-three-dados" role="tab" aria-controls="custom-tabs-three-dados"
                                aria-selected="false">Dados da AV</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="custom-tabs-three-fluxo-tab" data-toggle="pill"
                                href="#custom-tabs-three-fluxo" role="tab" aria-controls="custom-tabs-three-fluxo"
                                aria-selected="false">Fluxo</a>
                        </li>
                        @if ($av->objetivo_id == 3)
                            <li class="nav-item">
                                <a class="nav-link" id="custom-tabs-three-medicoes-tab" data-toggle="pill"
                                    href="#custom-tabs-three-medicoes" role="tab"
                                    aria-controls="custom-tabs-three-medicoes" aria-selected="false">Medições</a>
                            </li>
                        @endif
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content" id="custom-tabs-three-tabContent">
                        <div class="tab-pane fade show active" id="custom-tabs-three-home" role="tabpanel"
                            aria-labelledby="custom-tabs-three-home-tab">
                            <h4><strong>Autorização de viagem nº:</strong> {{ $av->id }}
                            </h4>
                            <h4><strong>Status atual:</strong> {{ $av->status }}</h4>
                            <p style="font-size: 22px"><strong>Nome do usuário: </strong>
                                @foreach ($users as $u)
                                    @if ($u->id == $av->user_id)
                                        {{ $u->name }}
                                    @endif
                                @endforeach
                            </p>
                            <p style="font-size: 22px"><strong>Objetivo: </strong>
                                @for ($i = 0; $i < count($objetivos); $i++)
                                    @if ($av->objetivo_id == $objetivos[$i]->id)
                                        {{ $objetivos[$i]->nomeObjetivo }}
                                    @endif
                                @endfor

                                @if (isset($av->outroObjetivo))
                                    {{ $av->outroObjetivo }}
                                @endif
                            </p>
                            <div class="col-md-12" style="overflow-x: auto;">

                                <h1 style="font-size: 24px"><strong>Trajeto: </strong></h1>
                                <table id="tabelaRota" class="table table-hover table-bordered" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>Tipo</th>
                                            <th>Cidade saída</th>
                                            <th>Estado saída</th>
                                            <th style="border-right: 1px solid black;">Data/Hora de saída</th>
                                            <th>Cidade chegada</th>
                                            <th>Estado chegada</th>
                                            <th>Data/Hora chegada</th>
                                            <th>Hotel?</th>
                                            <th>Tipo de transporte</th>
                                            <th>Status</th>
                                            <th>Ações</th>
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

                                                @if($rota->isOutroMeioTransporte == 1)
                                                    <img src="{{asset('/img/outros.png')}}" style="width: 40px" >
                                                @endif
                            
                                                {{$rota->isViagemInternacional == 0 ? $rota->cidadeOrigemNacional : $rota->cidadeOrigemInternacional}} 
                                                
                                            </td>
                                            <td>
                                                {{ $rota->isViagemInternacional == 0 ? $rota->estadoOrigemNacional : $rota->estadoOrigemInternacional }}
                                            </td>
                                            <td style="border-right: 1px solid black;"> {{ date('d/m/Y H:i', strtotime($rota->dataHoraSaida)) }} </td>
                            
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

                                                @if($rota->isOutroMeioTransporte == 1)
                                                    <img src="{{asset('/img/outros.png')}}" style="width: 40px" >
                                                @endif
                            
                                                {{$rota->isViagemInternacional == 0 ? $rota->cidadeDestinoNacional : $rota->cidadeDestinoInternacional}} 
                                            </td>
                                            <td>
                                                {{ $rota->isViagemInternacional == 0 ? $rota->estadoDestinoNacional : $rota->estadoDestinoInternacional }}
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
                                                {{ $rota->isOutroMeioTransporte == 1 ? "Outros" : ""}}
                                                {{ $rota->isOutroMeioTransporte == 2 ? "Carona" : ""}}
                                            </td>

                                            <td>
                                                @php
                                                    $correspondenciaHotelEncontrada = false;
                                                    $correspondenciaOnibusEncontrada = false;
                                                    $correspondenciaAereoEncontrada = false;
                                                @endphp
                                               
                                                @if($rota->isReservaHotel ==1)
                                                    @foreach($anexosRotas as $anexo)
                                                        @if($anexo->rota_id == $rota->id && $anexo->anexoHotel != null)
                                                            @php
                                                                $correspondenciaHotelEncontrada = true;
                                                            @endphp
                                                            <span class="badge bg-success badge-large"><i class="far fa-building"></i></span>
                                                            @break
                                                        @endif
                                                    @endforeach
                                                    @if(!$correspondenciaHotelEncontrada)
                                                        <span class="badge bg-warning badge-large"><i class="far fa-building"></i></span>
                                                    @endif
                                                @else
                                                    <span class="badge bg-danger badge-large"><i class="far fa-building"></i></span>
                                                @endif

                                                @if($rota->isOnibusLeito == 1 || $rota->isOnibusConvencional == 1)
                                                    @foreach($anexosRotas as $anexo)
                                                        @if($anexo->rota_id == $rota->id && $anexo->anexoTransporte != null)
                                                            @php
                                                                $correspondenciaOnibusEncontrada = true;
                                                            @endphp
                                                            <span class="badge bg-success badge-large"><i class="fas fa-bus"></i></span>
                                                            @break
                                                        @endif
                                                    @endforeach
                                                    @if(!$correspondenciaOnibusEncontrada)
                                                        <span class="badge bg-warning badge-large"><i class="fas fa-bus"></i></span>
                                                    @endif
                                                @else
                                                    <span class="badge bg-danger badge-large"><i class="fas fa-bus"></i></span>
                                                @endif

                                                @if($rota->isAereo == 1)
                                                    @foreach($anexosRotas as $anexo)
                                                        @if($anexo->rota_id == $rota->id && $anexo->anexoTransporte != null)
                                                            @php
                                                                $correspondenciaAereoEncontrada = true;
                                                            @endphp
                                                            <span class="badge bg-success badge-large"><i class="fas fa-plane"></i></span>
                                                            @break
                                                        @endif
                                                    @endforeach
                                                    @if(!$correspondenciaAereoEncontrada)
                                                        <span class="badge bg-warning badge-large"><i class="fas fa-plane"></i></span>
                                                    @endif
                                                @else
                                                    <span class="badge bg-danger badge-large"><i class="fas fa-plane"></i></span>
                                                @endif
                                                
                                            </td>
                                            
                                            <td>
                                                @if($rota->isReservaHotel == true || $rota->isOnibusLeito == 1 || $rota->isOnibusConvencional == 1 || $rota->isAereo ==1)
                                                    
                                                        <a href="/avs/realizarReservas/{{ $rota->id }}" class="btn btn-active btn-info"
                                                            title="Gerenciar Reservas"><i class="fas fa-calendar-alt"></i></a> 
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <p>Legenda do status: <span class="badge bg-success">Reservado</span> <span class="badge bg-warning">Pendente</span> <span class="badge bg-danger">Não tem</span></p>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    @if($av->idReservaVeiculo != null)
                                        <h1 style="font-size: 24px"><strong>Reserva de veículo no sistema de reservas:</strong></h1>
                                        @if(count($reservas2) > 0)
                                            <table class="table table-hover table-bordered" style="width: 100%">
                                                <thead>
                                                    <tr>
                                                        <th>Nr Reserva</th>
                                                        <th>Data Início</th>
                                                        <th>Data Fim</th>
                                                        <th>Descrição</th>
                                                        <th>Veículo</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($reservas2 as $r)
                                                        <tr>
                                                            <td>{{$r->id}}</td>
                                                            <td>{{date('d/m/Y H:i', strtotime($r->dataInicio))}}</td>
                                                            <td>{{date('d/m/Y H:i', strtotime($r->dataFim))}}</td>
                                                            <td>{{$r->observacoes}}</td>
                                                            <td>{{$r->veiculo->marca}} - {{$r->veiculo->modelo}} - {{$r->veiculo->placa}}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        @else
                                            <p>Nenhuma reserva encontrada</p>
                                        @endif
                                    @endif

                                    <h1 style="font-size: 24px"><strong>Controle de diárias:</strong></h1>
                                    <table class="table table-hover table-bordered" style="width: 100%">
                                        <thead>
                                            <tr>
                                                <th style="vertical-align: middle; text-align: center;">Dias</th>
                                                <th style="vertical-align: middle; text-align: center;">Trajeto Dia</th>
                                                <th style="vertical-align: middle; text-align: center;">Diária Almoço</th>
                                                <th style="vertical-align: middle; text-align: center;">Diária Jantar</th>
                                                <th style="vertical-align: middle; text-align: center;">Total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $j=0;
                                            @endphp
                                            @for($i = 0; $i <= sizeof($arrayDiasValores)-1; $i++)
                                                        
                                                <tr style="vertical-align: middle; text-align: center;">
                                                    <td style="vertical-align: middle; text-align: center;">
                                                        {{$arrayDiasValores[$j]['dia']}}
                                                    </td>
                                                    <td style="vertical-align: middle">
                                                        @foreach($arrayDiasValores[$j]['arrayRotasDoDia'] as $r)
                                                            {{-- verifique se $r começa com "ida" --}}
                                                            @if(strpos($r, 'Ida:') !== false)
                                                                <span>{{str_replace('Ida:', '', $r)}}</span>
                                                            @else
                                                                <span>{{$r}}</span><br>
                                                            @endif
                                                        @endforeach
                                                    </td>
                                                    <td style="vertical-align: middle; text-align: center;"> 
                                                        @if($arrayDiasValores[$j]['valorManha'] != 0)
                                                            <span><strong>R${{ number_format($arrayDiasValores[$j]['valorManha'], 2, ',', '.') }}</strong></span>
                                                        @else
                                                            -
                                                        @endif
                                                    </td>
                                                    <td style="vertical-align: middle; text-align: center;">
                                                        @if($arrayDiasValores[$j]['valorTarde'] != 0)
                                                            <span><strong>R${{ number_format($arrayDiasValores[$j]['valorTarde'], 2, ',', '.') }}</strong></span>
                                                        @else
                                                            -
                                                        @endif
                                                    </td>
                                                    <td style="vertical-align: middle; text-align: center;"> 
                                                        <span><strong>R${{ number_format($arrayDiasValores[$j]['valor'], 2, ',', '.') }}</strong></span>
                                                    </td>                                
                                                </tr>
                    
                                                @php
                                                    $j++;
                                                @endphp
                                            @endfor
                                        </tbody>
                                    </table>
                                </div>
                                <div class="col-md-6">
                                    <br><br>
                                    <div class="row">
                                        
                                        <div class="col-md-6" id="divAprovacao" >
                                            <form action="/avs/secretariaAprovarAv" method="POST" enctype="multipart/form-data">
                                                @csrf
                                                @method('PUT')
                                                <input type="text" hidden="true" id="id" name="id"
                                                    value="{{ $av->id }}">
                                                <label for="comentario">Registrar reservas: </label>
                                                <br>

                                                <div class="input-group mb-3">
                                                    <textarea type="text" class="textarea textarea-bordered h-24" name="comentario" style="width: 200px"
                                                    id="comentario" placeholder="Comentário"></textarea>
    
                                                    <span class="input-group-append">
                                                        @if ($av->isCancelado == 0)
                                                            <button type="submit" class="btn btn-active btn-success" onclick="exibirLoader()">Finalizar Reservas</button>
                                                        @else
                                                            <button type="submit" class="btn btn-active btn-success" onclick="exibirLoader()">Finalizar
                                                                AV</button>
                                                        @endif
                                                    </span>
                                                </div>
                                            </form>
                                        </div>
                                        <div class="col-md-6" id="divReprovacao" >
                                            @if ($av->isCancelado == 0)
                                                <form action="/avs/secretariaReprovarAv" method="POST"
                                                    enctype="multipart/form-data">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="text" hidden="true" id="id" name="id"
                                                        value="{{ $av->id }}">
                                                    <label for="comentario">Voltar AV para o usuário: </label>
                                                    <br>

                                                    <div class="input-group mb-3">
                                                        <textarea type="text" class="textarea textarea-bordered h-24 {{ $errors->has('comentario') ? 'is-invalid' : '' }}"
                                                            name="comentario" style="width: 200px" id="comentario" placeholder="Comentário"></textarea>
        
                                                        <span class="input-group-append">
                                                            <button type="submit" class="btn btn-active btn-danger" onclick="exibirLoader()">Reprovar AV</button>
                                                        </span>
                                                    </div>
                                                    @if ($errors->has('comentario'))
                                                        <div class="invalid-feedback">
                                                            {{ $errors->first('comentario') }}
                                                        </div>
                                                    @endif
                                                </form>
                                            @endif
                                        </div>
    
                                    </div>
                                </div>
                            </div>

                        </div>
                    
                        <div class="tab-pane fade" id="custom-tabs-three-historico" role="tabpanel"
                            aria-labelledby="custom-tabs-three-historico-tab" style="overflow-x: auto;">
                            <h3 class="text-lg font-bold">Histórico</h3>
                            <table id="minhaTabela" class="table table-hover table-bordered">
                                <!-- head -->
                                <thead>
                                    <tr>
                                        {{-- <th>Id</th> --}}
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
                                            {{-- <td>{{ $historico->id }}</td> --}}
                                            <td>{{ date('d/m/Y H:i', strtotime($historico->dataOcorrencia)) }}</td>
                                            <td>{{ $historico->tipoOcorrencia }}</td>
                                            <td>{{ $historico->comentario }}</td>
                                            <td>{{ $historico->perfilDonoComentario }}</td>

                                            @foreach ($users as $u)
                                                @if ($u->id == $historico->usuario_comentario_id)
                                                    <td>{{ $u->name }}</td>
                                                @endif
                                            @endforeach
                                        </tr>
                                    @endforeach

                                </tbody>
                            </table>

                        </div>
                        <div class="tab-pane fade" id="custom-tabs-three-dados" role="tabpanel"
                            aria-labelledby="custom-tabs-three-dados-tab">

                            <h1 class="text-lg font-bold">Dados básicos:</h1>
                            <div class="stats stats-vertical shadow">
                                <p class="av-owner" style="font-size: 20px"><ion-icon
                                        name="chevron-forward-circle-outline">
                                    </ion-icon> <strong>Nome do usuário: </strong>
                                    @foreach ($users as $u)
                                        @if ($u->id == $av->user_id)
                                            {{ $u->name }}
                                        @endif
                                    @endforeach
                                </p>
                                <p class="av-owner" style="font-size: 20px"><ion-icon
                                        name="chevron-forward-circle-outline">
                                    </ion-icon> <strong>E-mail do usuário: </strong>
                                    @foreach ($users as $u)
                                        @if ($u->id == $av->user_id)
                                            {{ $u->username }}
                                        @endif
                                    @endforeach
                                </p>
                                <p class="av-owner" style="font-size: 20px"><ion-icon name="calendar-outline"></ion-icon>
                                    <strong>Data de criação: </strong> {{ date('d/m/Y', strtotime($av->dataCriacao)) }}
                                </p>
                                <p class="av-owner" style="font-size: 20px"><ion-icon name="flag-outline">
                                    </ion-icon> <strong>Objetivo:</strong>

                                    @for ($i = 0; $i < count($objetivos); $i++)
                                        @if ($av->objetivo_id == $objetivos[$i]->id)
                                            {{ $objetivos[$i]->nomeObjetivo }}
                                        @endif
                                    @endfor

                                    @if (isset($av->outroObjetivo))
                                        {{ $av->outroObjetivo }}
                                    @endif

                                </p>
                                <p class="av-owner" style="font-size: 20px"><ion-icon name="pricetag-outline"></ion-icon>
                                    <strong>Comentário:</strong> {{ $av->comentario }}
                                </p>
                                <p class="av-owner" style="font-size: 20px"><ion-icon
                                        name="chevron-forward-circle-outline"></ion-icon> <strong>Status:</strong>
                                    {{ $av->status }}
                                </p>
                            </div>
                            <br>
                            <h1 class="text-lg font-bold">Dados bancários:</h1>

                            <div class="stats stats-vertical shadow">

                                <p class="av-owner" style="font-size: 20px"><ion-icon name="business-outline"></ion-icon>
                                    <strong>Banco:</strong> {{ $av->banco }}
                                </p>
                                <p class="av-owner" style="font-size: 20px"><ion-icon name="home-outline"></ion-icon>
                                    <strong>Agência:</strong> {{ $av->agencia }}
                                </p>
                                <p class="av-owner" style="font-size: 20px"><ion-icon name="wallet-outline"></ion-icon>
                                    <strong>Conta:</strong> {{ $av->conta }}
                                </p>
                                <p class="av-owner" style="font-size: 20px"><ion-icon name="cash-outline"></ion-icon>
                                    <strong>Pix:</strong> {{ $av->pix }}
                                </p>

                            </div>
                            <br>

                            <h1 class="text-lg font-bold">Adiantamentos:</h1>
                            <div class="stats stats-vertical shadow">
                                <p class="av-owner" style="font-size: 20px; color: black;"><ion-icon
                                        name="cash-outline"></ion-icon> <strong>Valor em reais:</strong> R$
                                    {{ $av->valorReais }}</p>
                                @if($av->valorDolar != null)
                                    <p class="av-owner" style="font-size: 20px; color: black;"><ion-icon
                                            name="cash-outline"></ion-icon> <strong>Valor em dólar:</strong> $
                                        {{ $av->valorDolar }}</p>
                                @endif
                                <p class="av-owner" style="font-size: 20px; color: black;"><ion-icon
                                        name="cash-outline"></ion-icon> <strong>Valor extra em reais:</strong> R$
                                    {{ $av->valorExtraReais }}</p>
                                @if($av->valorExtraDolar != null)
                                    <p class="av-owner" style="font-size: 20px; color: black;"><ion-icon
                                            name="cash-outline"></ion-icon> <strong>Valor extra em dólar:</strong> $
                                        {{ $av->valorExtraDolar }}</p>
                                @endif
                                <p class="av-owner" style="font-size: 20px; color: black;">
                                    <ion-icon name="cash-outline"></ion-icon> <strong>Valor dedução em reais:</strong>
                                    R$
                                    {{ $av->valorDeducaoReais }}
                                </p>
                                @if($av->valorDeducaoDolar != null)
                                    <p class="av-owner" style="font-size: 20px; color: black;">
                                        <ion-icon name="cash-outline"></ion-icon> <strong>Valor dedução em dólar:</strong>
                                        $
                                        {{ $av->valorDeducaoDolar }}
                                    </p>
                                @endif
                                <p class="av-owner" style="font-size: 20px; color: black;"><ion-icon
                                        name="chevron-forward-circle-outline"></ion-icon> <strong>Justificativa valor
                                        extra:</strong>
                                    {{ $av->justificativaValorExtra }}</p>
                                @if ($av->autorizacao != null)
                                    <a href="{{ route('recuperaArquivo', [
                                        'name' => $userAv->name,
                                        'id' => $av->id,
                                        'pasta' => 'autorizacaoAv',
                                        'anexoRelatorio' => $av->autorizacao,
                                        ]) }}"
                                        target="_blank" class="btn btn-active btn-success btn-sm">Documento de Autorização</a>
                                @endif
                            </div>

                        </div>
                        <div class="tab-pane fade" id="custom-tabs-three-fluxo" role="tabpanel"
                            aria-labelledby="custom-tabs-three-fluxo-tab">
                            <div class="col-md-12">

                                <div class="timeline">
        
                                    <div class="time-label">
                                        <span class="bg-red">Fases da realização da viagem</span>
                                    </div>
        
        
                                    <div>
                                        @if ($av->isEnviadoUsuario == 1)
                                            <i class="fas fa-caret-right bg-green"></i>
                                            <div class="timeline-item">
                                                <div class="timeline-header">
                                                    <a class="btn btn-success btn-lg" @readonly(true)>1 - Usuário -
                                                        Preenchimento da AV</a>
                                                </div>
                                            </div>
                                        @else
                                            <i class="fas fa-caret-right bg-blue"></i>
                                            <div class="timeline-item">
                                                <div class="timeline-header">
                                                    <a class="btn btn-primary btn-md" @readonly(true)>1 - Usuário -
                                                        Preenchimento da AV</a>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                    <div>
                                        @if ($av->isAprovadoGestor == 1)
                                            <i class="fas fa-caret-right bg-green"></i>
                                            <div class="timeline-item">
                                                <div class="timeline-header">
                                                    <a class="btn btn-success btn-lg" @readonly(true)>2 - Gestor - Avaliação
                                                        inicial</a>
                                                </div>
                                            </div>
                                        @else
                                            <i class="fas fa-caret-right bg-blue"></i>
                                            <div class="timeline-item">
                                                <div class="timeline-header">
                                                    <a class="btn btn-primary btn-md" @readonly(true)>2 - Gestor - Avaliação
                                                        inicial</a>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                    <div>
                                        @if ($av->isVistoDiretoria == 1)
                                            <i class="fas fa-caret-right bg-green"></i>
                                            <div class="timeline-item">
                                                <div class="timeline-header">
                                                    <a class="btn btn-success btn-lg" @readonly(true)>3 - DAF - Avalia
                                                        pedido</a>
                                                    <span class="badge bg-warning float-right">Se carro particular ou viagem internacional</span>
                                                </div>
                                            </div>
                                        @else
                                            <i class="fas fa-caret-right bg-blue"></i>
                                            <div class="timeline-item">
                                                <div class="timeline-header">
                                                    <a class="btn btn-primary btn-md" @readonly(true)>3 - DAF - Avalia
                                                        pedido</a>
                                                    <span class="badge bg-warning float-right">Se carro particular ou viagem internacional</span>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                    <div>
                                        @if ($av->isRealizadoReserva == 1)
                                            <i class="fas fa-caret-right bg-green"></i>
                                            <div class="timeline-item">
                                                <div class="timeline-header">
                                                    <a class="btn btn-success btn-lg" @readonly(true)>4 - CAD - Coordenadoria
                                                        Administrativa - Realiza reservas</a>
                                                </div>
                                            </div>
                                        @else
                                            <i class="fas fa-caret-right bg-blue"></i>
                                            <div class="timeline-item">
                                                <div class="timeline-header">
                                                    <a class="btn btn-primary btn-md" @readonly(true)>4 - CAD -
                                                        Coordenadoria
                                                        Administrativa - Realiza reservas</a>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                    <div>
                                        @if ($av->isAprovadoFinanceiro == 1)
                                            <i class="fas fa-caret-right bg-green"></i>
                                            <div class="timeline-item">
                                                <div class="timeline-header">
                                                    <a class="btn btn-success btn-lg" @readonly(true)>4 - CFI -
                                                        Coordenadoria
                                                        Financeira - Adiantamento</a>
                                                </div>
                                            </div>
                                        @else
                                            <i class="fas fa-caret-right bg-blue"></i>
                                            <div class="timeline-item">
                                                <div class="timeline-header">
                                                    <a class="btn btn-primary btn-md" @readonly(true)>4 - CFI -
                                                        Coordenadoria Financeira - Adiantamento</a>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                    <div>
                                        @if ($av->isPrestacaoContasRealizada == 1)
                                            <i class="fas fa-caret-right bg-green"></i>
                                            <div class="timeline-item">
                                                <div class="timeline-header">
                                                    <a class="btn btn-success btn-md" @readonly(true)>5 - Viagem</a>
                                                </div>
                                            </div>
                                        @else
                                            <i class="fas fa-caret-right bg-blue"></i>
                                            <div class="timeline-item">
                                                <div class="timeline-header">
                                                    <a class="btn btn-primary btn-md" @readonly(true)>5 - Viagem</a>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                    <div>
                                        @if ($av->isPrestacaoContasRealizada == 1)
                                            <i class="fas fa-caret-right bg-green"></i>
                                            <div class="timeline-item">
                                                <div class="timeline-header">
                                                    <a class="btn btn-success btn-lg" @readonly(true)>6 - Usuário - Realiza
                                                        PC</a>
                                                </div>
                                            </div>
                                        @else
                                            <i class="fas fa-caret-right bg-blue"></i>
                                            <div class="timeline-item">
                                                <div class="timeline-header">
                                                    <a class="btn btn-primary btn-md" @readonly(true)>6 - Usuário - Realiza
                                                        PC</a>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                    <div>
                                        @if ($av->isFinanceiroAprovouPC == 1)
                                            <i class="fas fa-caret-right bg-green"></i>
                                            <div class="timeline-item">
                                                <div class="timeline-header">
                                                    <a class="btn btn-success btn-lg" @readonly(true)>7 - Financeiro -
                                                        Avalia PC</a>
                                                </div>
                                            </div>
                                        @else
                                            <i class="fas fa-caret-right bg-blue"></i>
                                            <div class="timeline-item">
                                                <div class="timeline-header">
                                                    <a class="btn btn-primary btn-md" @readonly(true)>7 - Financeiro -
                                                        Avalia PC</a>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                    <div>
                                        @if ($av->isGestorAprovouPC == 1)
                                            <i class="fas fa-caret-right bg-green"></i>
                                            <div class="timeline-item">
                                                <div class="timeline-header">
                                                    <a class="btn btn-success btn-lg" @readonly(true)>8 - Gestor - Avalia
                                                        PC</a>
                                                </div>
                                            </div>
                                        @else
                                            <i class="fas fa-caret-right bg-blue"></i>
                                            <div class="timeline-item">
                                                <div class="timeline-header">
                                                    <a class="btn btn-primary btn-md" @readonly(true)>8 - Gestor - Avalia
                                                        PC</a>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                    <div>
                                        @if ($av->isAcertoContasRealizado == 1)
                                            <i class="fas fa-caret-right bg-green"></i>
                                            <div class="timeline-item">
                                                <div class="timeline-header">
                                                    <a class="btn btn-success btn-lg" @readonly(true)>9 - Financeiro -
                                                        Acerto de Contas</a>
                                                </div>
                                            </div>
                                        @else
                                            <i class="fas fa-caret-right bg-blue"></i>
                                            <div class="timeline-item">
                                                <div class="timeline-header">
                                                    <a class="btn btn-primary btn-md" @readonly(true)>9 - Financeiro -
                                                        Acerto de Contas</a>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
        
                                    <div>
                                        <i class="far fa-check-circle bg-green"></i>
                                    </div>
                                </div>
                            </div>

                        </div>


                        @if ($av->objetivo_id == 3)
                            <div class="tab-pane fade" id="custom-tabs-three-medicoes" role="tabpanel"
                                aria-labelledby="custom-tabs-three-medicoes-tab">
                                <h1 style="font-size: 24px; padding-bottom: 20px"><strong>Medições vinculadas:</strong>
                                </h1>
                                <table id="minhaTabela8" class="table table-hover table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Nome do município</th>
                                            <th>Número do projeto</th>
                                            <th>Número do lote</th>
                                            <th>Número da medição</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($medicoesFiltradas as $med)
                                            <tr>
                                                <td> {{ $med->nome_municipio }} </td>
                                                <td> {{ $med->numero_projeto }} </td>
                                                <td> {{ $med->numero_lote }} </td>
                                                <td> {{ $med->numero_medicao }} </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>

                            </div>
                        @endif
                </div>

            </div>
        </div>

    </div>
</div>

@stop

@section('css')
    <link href="{{ asset('DataTables/datatables.min.css') }}" rel="stylesheet" />
    <style>
        .badge-large {
            font-size: 1rem; /* Ajuste o tamanho conforme necessário */
            padding: 0.5rem 1rem; /* Ajuste o preenchimento conforme necessário */
        }
    </style>
@stop

@section('js')

    <script src="{{ asset('DataTables/datatables.min.js') }}"></script>
    <script src="{{ asset('/js/moment.js') }}"></script>
    <script type="text/javascript">

        function exibirLoader() {
            $('#custom-tabs-five-overlay').css('display', 'block');
        }

        $(function() {
            
        });
    </script>

@stop
