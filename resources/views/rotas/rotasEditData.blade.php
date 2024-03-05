@extends('adminlte::page')

@section('title', 'Editar nova data')

@section('content_header')
    <h1>Editar nova data</h1>
@stop

@section('content')
    
<style>
    @media (max-width: 600px) {
      div {
        flex-direction: column;
      }
    }
  </style>

<div style="padding-left: 50px, padding-right: 50px" class="container">
    <div class="row justify-content-between" style="padding-left: 5%">
        <div style="display: flex; justify-content: space-between;">
            <div class="col-4" >
                <a href="/avs/avs/" type="submit" class="btn btn-active btn-ghost" style="width: 180px"> Voltar!</a>
            </div>
            
        </div>
        <div class="col-4">
            <label for="idav" > <strong>AV nº </strong> </label>
            <input style="width: 50px; font-size: 16px; font-weight: bold; color: green" type="text" value="{{ $av->id }}" id="idav" name="idav" disabled>
            <h2> <strong>Data: {{ date('d/m/Y', strtotime($av->dataCriacao)) }}</strong> </h2>
        </div>
        <div class="col-12">
            <label for="idav" style="color: red"> <strong>É permitido alterar os dias somente para um período exatamente igual ao solicitado inicialmente, o valor do adiantamento de alimentação não será modificado. </strong> </label>
        </div>
    </div>
    
    <br>
</div>
<div class="col-md-10 offset-md-1 dashboard-avs-container">
    @if(count($rotas) > 0 )
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
                    <a href="/rotas/editNovaData/{{ $rota->id }}" class="btn btn-success btn-sm" style="width: 85px"> Editar</a> 
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    @endif

</div>


@stop

@section('css')
    
@stop

@section('js')
    
<script type="text/javascript">

    $(document).ready(function(){
        $('#tabelaRota').DataTable({
                scrollY: 400,
                "language": {
                    "lengthMenu": "Mostrando _MENU_ registros por página",
                    "zeroRecords": "Nada encontrado",
                    "info": "Mostrando página _PAGE_ de _PAGES_",
                    "infoEmpty": "Nenhum registro disponível",
                    "infoFiltered": "(filtrado de _MAX_ registros no total)",
                    "search": "Procure uma AV"
                }
            });
    });

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

