@extends('adminlte::page')

@section('title', 'Gerenciar Rotas')

@section('content_header')
    <div class="row">
        <div class="col-md-6">
            <h1>Gerenciar Rotas</h1>
        </div>
        <div class="col-md-6">
            <a href="/avs/avs/" type="submit" class="btn btn-warning btn-ghost"><i class="fas fa-arrow-left"></i></a>
        </div>
    </div>
    
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
    {{-- adicione uma exibição de alerta para msg se houver --}}
    @if (session('msg'))
        <div class="alert alert-warning">{{ session('msg') }}</div>
    @endif

@stop

@section('content')

<div class="tab-pane fade show active" id="custom-tabs-five-overlay" role="tabpanel" aria-labelledby="custom-tabs-five-overlay-tab" style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.5); z-index: 9999; display: none">
    <div class="overlay-wrapper" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); text-align: center; color: #ffffff;">
            <i class="fas fa-3x fa-sync-alt fa-spin" style="margin-bottom: 10px;"></i>
            <div class="text-bold pt-2">Carregando...</div>
    </div>
</div>

    <div>
        <div class="row">
            <div class="col-1">
                <a href="/rotas/create/{{ $av->id }}" type="submit" class="btn btn-active btn-success" style="width: 80px"><i class="fas fa-plus"></i> ROTA</a>
            </div>
            <div class="col-5">
                <label for="idav" > <strong>AV nº </strong> </label>
                <input style="width: 50px; font-size: 16px; font-weight: bold; color: green" type="text" value="{{ $av->id }}" id="idav" name="idav" disabled>
                <strong style="font-size: 18px">Data: {{ date('d/m/Y', strtotime($av->dataCriacao)) }}</strong>
            </div>
            <div class="col-3" style="display: flex; justify-content: space-between;">

                <form action="/avs/concluir/{{ $av->id }}/nao" enctype="multipart/form-data">
                    @if(count($rotas) > 0 )
                        <div id="btSalvarRota">
                            <button type="submit" class="btn btn-primary" id="salvarBt">Continuar <i class="fas fa-thumbs-up"></i></button>
                        </div>
                    @endif
                </form>
                
            </div>
        </div>
        
        <br>
    </div>
    <div class="col-md-10 dashboard-avs-container">
        @if(count($rotas) > 0 )
        <table id="tabelaRota" class="table table-hover display nowrap table-responsive" style="width:100%">
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
                        <div class="d-flex align-items-center">
                            @if($rota->isViagemInternacional == 1)
                                <a href="/rotas/editInternacional/{{ $rota->id }}" class="btn btn-warning btn-sm" title="Editar"><i class="far fa-edit"></i></a>
                            @else
                                <a href="/rotas/edit/{{ $rota->id }}" class="btn btn-warning btn-sm" title="Editar"><i class="far fa-edit"></i></a>
                            @endif
                            <form action="/rotas/{{ $rota->id }}" method="POST" onsubmit="return confirm('Tem certeza que deseja remover essa rota?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" title="Remover"><i class="fas fa-trash-alt"></i></button>
                            </form>
                        </div>
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
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <link href="{{asset('DataTables/datatables.min.css')}}" rel="stylesheet"/>
    <style>
        @media (max-width: 600px) {
        div {
            flex-direction: column;
        }
        }
    </style>
@stop

@section('js')
    <script src="{{asset('/js/moment.js')}}"></script>
    <script type="text/javascript">

        $('#salvarBt').on('click', function() {
                // Altera o estilo da <div> para "block"
                $('#custom-tabs-five-overlay').css('display', 'block');
        });

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            }
        });
    </script>
@stop
