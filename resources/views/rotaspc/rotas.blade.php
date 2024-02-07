@extends('adminlte::page')

@section('title', 'Editar Rotas')

@section('content_header')
    <h1>Editar Rotas</h1>
@stop

@section('content')

<style>
    @media (max-width: 600px) {
      div {
        flex-direction: column;
      }
    }
  </style>

<div>
    <div>
        <div class="row">
            <div class="col-8" >
                
            </div>
            <div class="col-3" >
                <a href="/avs/fazerPrestacaoContas/{{ $av->id }}" type="submit" class="btn btn-active btn-warning"><i class="fas fa-arrow-left"></i></a>
            </div>
        </div>
        <br>
            
        <div class="col-md-6">
            <label for="idav" style="font-size: 24px; color: green"> <strong>AV nº </strong> </label>
            <input style="width: 50px; font-size: 24px; font-weight: bold; color: green" type="text" value="{{ $av->id }}" id="idav" name="idav" disabled>
            <h2 style="font-size: 24px"> <strong>Data: {{ date('d/m/Y', strtotime($av->dataCriacao)) }}</strong> </h2>
            <table class="table table-hover table-bordered" style="width: 100%">
                <tr>
                    <td>
                        <strong style="font-size: 18px">Valor adiantamento em reais: </strong>
                    </td>
                    <td>
                        <h2 style="font-size: 18px"> <strong><span style="color: green"> R${{ $av->valorReais}} + Extra: {{$av->valorExtraReais != null ? $av->valorExtraReais : 0 }} </span></strong> </h2>
                    </td>
                </tr>
                <tr>
                    <td>
                        <strong style="font-size: 18px">Valor adiantamento em dólar:  </strong>
                    </td>
                    <td>
                        <h2 style="font-size: 18px"> <strong><span style="color: green"> ${{ $av->valorDolar}} + Extra: {{$av->valorExtraDolar  != null ? $av->valorExtraDolar : 0}} </span></strong> </h2>
                    </td>
                </tr>
                <tr>
                    <td>
                        <strong style="font-size: 18px">Valor dedução em reais:  </strong>
                    </td>
                    <td>
                        <h2 style="font-size: 18px"> <strong> <span style="color: green"> R${{ $av->valorDeducaoReais != null ? $av->valorDeducaoReais : 0 }} </span></strong> </h2>
                    </td>
                </tr>
                <tr>
                    <td>
                        <strong style="font-size: 18px">Valor dedução em dólar:  </strong>
                    </td>
                    <td>
                        <h2 style="font-size: 18px"> <strong> <span style="color: green"> ${{ $av->valorDeducaoDolar != null ? $av->valorDeducaoDolar : 0 }} </span></strong> </h2>
                    </td>
                </tr>
                <tr>
                    <td>
                        <strong style="font-size: 18px">Saldo em reais:  </strong>
                    </td>
                    <td>
                        <h2 style="font-size: 18px"> <strong><span style="color: green"> R${{ $av->valorReais + $av->valorExtraReais - $av->valorDeducaoReais }} </span></strong> </h2>
                    </td>
                </tr>
                <tr>
                    <td>
                        <strong style="font-size: 18px">Saldo em dólar: </strong>
                    </td>
                    <td>
                        <h2 style="font-size: 18px"> <strong> <span style="color: green"> ${{ $av->valorDolar + $av->valorExtraDolar - $av->valorDeducaoDolar }} </span></strong> </h2>
                    </td>
                </tr>
            </table>
        </div>

        <div class="row">
            <div class="col-4" >
                <a href="/rotaspc/create/{{ $av->id }}" type="submit" class="btn btn-active btn-primary"><i class="fas fa-plus"></i> ROTA</a>
            </div>
            {{-- <div class="col-4" >
                <form action="/avspc/concluir/{{ $av->id }}/sim" enctype="multipart/form-data">
                    <div id="btSalvarRota">
                        <input style="font-size: 16px; width: 180px" type="submit" class="btn btn-active btn-warning" value="Calcular diárias">
                    </div>
                </form>
            </div> --}}
        </div>
    </div>
    
    <br>
</div>
<div class="col-md-10">
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
<div class="col-md-10">
    @if(count($rotas) > 0 )
    <table id="tabelaRota" class="table table-hover table-bordered table-responsive-sm table-responsive-md table-responsive-lg table-responsive-xl" style="width: 100%">
        <thead>
            <tr>
                <th>Tipo</th>
                <th>Cidade de saída</th>
                <th>Data/Hora de saída</th>
                <th>Cidade de chegada</th>
                <th>Data/Hora de chegada</th>
                <th>Hotel?</th>
                <th>Tipo de transporte</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            @foreach($rotas as $rota)
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

                    @if($rota->isOutroMeioTransporte == 1)
                        <img src="{{asset('/img/outros.png')}}" style="width: 40px" >
                    @endif

                    {{$rota->isViagemInternacional == 0 ? $rota->cidadeDestinoNacional : $rota->cidadeDestinoInternacional}} 
                </td>

                <td> {{ date('d/m/Y H:i', strtotime($rota->dataHoraChegada)) }} </td>
                <td> {{ $rota->isReservaHotel == 1 ? "Sim" : "Não"}}</td>
                <td> 
                    {{ $rota->isOnibusLeito == 1 ? "Onibus leito" : ""}}
                    {{ $rota->isOnibusConvencional == 1 ? "Onibus convencional" : ""}}
                    {{ $rota->isVeiculoProprio == 1 ? "Veículo próprio" : ""}}
                    {{ $rota->isVeiculoEmpresa == 1 ? "Veículo empresa" : ""}}
                    {{ $rota->isAereo == 1 ? "Aéreo" : ""}}
                    {{ $rota->isOutroMeioTransporte == 1 ? "Outros" : ""}}
                </td>
                <td> 
                    <div class="d-flex align-items-center">
                        <a href="/rotaspc/edit/{{ $rota->id }}" class="btn btn-success btn-sm"><i class="far fa-edit"></i></a> 
                        <form action="/rotaspc/{{ $rota->id }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i></button>
                        </form>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    
    @else
    <p>Você ainda não tem rotas, <a href="/rotas/create/{{ $av->id }}"> Criar nova rota</a></p>
    @endif
</div>

@stop

@section('css')
    
@stop

@section('js')
    
<script type="text/javascript">

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': "{{ csrf_token() }}"
        }
    });

    function gerenciaNacionalInternacional(){

        if(isViagemInternacional.value=="0") {

        }
        else if(isViagemInternacional.value=="1"){

        }
        else{

        }
    }
    function carregaCidade(){
        
    }

            //Assim que a tela carrega, aciona automaticamente essas duas funções ------------------------
    $(function(){
        carregaCidade();
        
    })
</script>

@stop
