@extends('adminlte::page')

@section('title', 'Concluir Sol. AV')

@section('content_header')
    <h1>Concluir AV</h1>
    <label for="idav" > <strong>AV nº </strong> </label>
    <input style="width: 50px; font-size: 16px; font-weight: bold; color: green" type="text" value="{{ $av->id }}" id="idav" name="idav" disabled>
    <strong>  Data: {{ date('d/m/Y', strtotime($av->dataCriacao)) }}</strong>
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
            <a href="/rotas/rotas/{{ $av->id }}" type="submit" class="btn btn-active btn-warning"><i class="fas fa-arrow-left"></i></a>
        </div>

        <div class="col-6">
            <x-adminlte-button label="Dados Básicos da AV" data-toggle="modal" class="bg-purple" data-target="#my-modal-4"/>
            <x-adminlte-button label="Detalhar dias" data-toggle="modal" class="bg-blue" data-target="#my-modal-5"/>
            <x-adminlte-button label="Como é calculada a diária de alimentação?" data-toggle="modal" data-target="#my-modal-3"/>
        </div>
    </div>
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
</div>

<x-adminlte-modal id="my-modal-3" title="Cálculos" size="xl" theme="teal"
        icon="fas fa-bell" v-centered static-backdrop scrollable>

    <div>
        <br>
        <h3 class="text-lg font-bold" style="padding-left: 10%">A diária de alimentação é calculada da seguinte forma:</h3>
        <img src="/img/horarios.png" style="width: 100%;">
        
    </div>
    <x-slot name="footerSlot">
        <x-adminlte-button theme="danger" label="Fechar" data-dismiss="modal"/>
    </x-slot>

</x-adminlte-modal>

<x-adminlte-modal id="my-modal-4" title="Dados da AV" size="md" theme="teal"
        icon="fas fa-bell" v-centered static-backdrop scrollable>

    <div>
        <div class="form-group" id="nomeObjetivo" >
            <label for="objetivo_id" class="control-label">Objetivo da viagem:</label>
            <br>
            <select class="form-control" 
                id="objetivo_id" name="objetivo_id" disabled>
                <option value="" name=""> Selecione</option>

                @for($i = 0; $i < count($objetivos); $i++)
                    <div>
                        <option value="{{ $objetivos[$i]->id }}" {{ $av->objetivo_id == $objetivos[$i]->id ? "selected='selected'" : ""}}
                            name="{{ $objetivos[$i]->id }}"> {{ $objetivos[$i] ->nomeObjetivo }} </option>
                    </div>
                @endfor

            </select>
        </div>

        <div class="form-group" id="outroObjetivo">
            <label for="outro" class="control-label">Você seleciou um outro objetivo: </label>
            <div class="input-group">
                <input type="text" class="form-control" 
                name="outroObjetivo" disabled
                id="outroObjetivo" placeholder="Outro" value="{{$av->outroObjetivo}}">
            </div>
        </div>

        <div class="form-group">
            <label for="banco" class="control-label">Banco</label>
            <input type="text" class="form-control" name="banco"
            id="banco" placeholder="Banco" value="{{$av->banco}}" disabled> 
        </div>

        <div class="form-group">
            <label for="agencia" class="control-label">Agência</label>
            <div class="input-group">
                <input type="text" class="form-control" name="agencia"
                id="agencia" placeholder="Agência" value="{{$av->agencia}}" disabled>
            </div>
        </div>

        <div class="form-group">
            <label for="conta" class="control-label">Conta</label>
            <input type="text" class="form-control" name="conta"
            id="conta" placeholder="Conta" value="{{$av->conta}}" disabled>
        </div>

        <div class="form-group">
            <label for="pix" class="control-label">Pix</label>
            <input type="text" class="form-control" name="pix"
                id="pix" placeholder="Pix" value="{{$av->pix}}" disabled>
        </div>

        <div class="form-group">
            <label for="comentario" class="control-label">Comentários</label>
            <input type="text" class="form-control" name="comentario"
                id="comentario" placeholder="Comentário" value="{{$av->comentario}}" disabled>
        </div>
    </div>

    <x-slot name="footerSlot">
        <x-adminlte-button theme="danger" label="Fechar" data-dismiss="modal"/>
    </x-slot>

</x-adminlte-modal>


<x-adminlte-modal id="my-modal-5" title="Detalhamento" size="xl" theme="teal"
        icon="fas fa-bell" v-centered static-backdrop scrollable>

        <div class="row">
            <div class="col-md-6 col-12">
                <img src="/img/valores.png" style="width: 100%;">
            </div>
            <div class="col-md-6 col-12">
            
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
                            $cores = ['bg-blue', 'bg-success']; // Adicione mais cores conforme necessário
                            $corIndex = 0; // Índice inicial
                        @endphp
                        
                        @for($i = 0; $i <= sizeof($arrayDiasValores)-1; $i++)
                            <tr style="vertical-align: middle; text-align: center;">
                                <td style="vertical-align: middle; text-align: center;">
                                    {{$arrayDiasValores[$j]['dia']}}
                                </td>
                                <td style="vertical-align: middle">
                                    @foreach($arrayDiasValores[$j]['arrayRotasDoDia'] as $r)
                                        {{-- Verifique se $r começa com "ida" --}}
                                        @if(strpos($r, 'Ida:') !== false)
                                            <span class="badge {{$cores[$corIndex]}}">{{str_replace('Ida:', '', $r)}}</span>
                                        @else
                                            <span class="badge {{$cores[$corIndex]}}">{{$r}}</span><br>
                                            @php
                                                $corIndex = ($corIndex + 1) % count($cores); // Avança para a próxima cor, voltando ao início se necessário
                                            @endphp
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
            <br><br>
        </div>
    <x-slot name="footerSlot">
        <x-adminlte-button theme="danger" label="Fechar" data-dismiss="modal"/>
    </x-slot>

</x-adminlte-modal>

<x-adminlte-modal id="my-modal-1" title="Adicionar reserva" size="lg" theme="teal"
            icon="fas fa-bell" v-centered static-backdrop scrollable>

        <div class="row">
            <div class="col-md-12">
                <form action="{{ route('reservasVeiculo.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="col-4">
                        <label for="nrAv" > <strong>AV nº </strong> </label>
                        <input style="width: 50px; font-size: 16px; font-weight: bold; color: green" type="text" value="{{ $av->id }}" id="nrAv" name="nrAv" readonly>
                    </div>
    
                    <div class="form-group">
                        <label for="daterange">Intervalo de datas:</label>
                        <div class="d-flex justify-content-center">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" style="width: 40px">
                                        De
                                    </span>
                                </div>
                                <input type="text" id="daterange1" name="daterange1" value="{{ date('d/m/Y H:i:s', strtotime($rotas[0]->dataHoraSaida)) }}" class="form-control text-center" />
                            </div>
                        </div>
                        <div class="d-flex justify-content-center">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" style="width: 40px">
                                        Até
                                    </span>
                                </div>
                                <input type="text" id="daterange2" name="daterange2" value="{{ date('d/m/Y H:i:s', strtotime($rotas[count($rotas)-1]->dataHoraChegada)) }}" class="form-control text-center" />
                            </div>
                        </div>
                    </div>
    
                    <div class="form-group">
                        <label for="idVeiculo">Veículo:</label>
                        <select name="idVeiculo" id="idVeiculo" class="form-control" required>
                            {{-- Inserir opções para os veículos disponíveis --}}
                            @foreach ($veiculos as $veiculo)
                                @php
                                    $disponivel = 1;
                                @endphp
                                @foreach($eventos as $evento)
                                    @if($evento->placa == $veiculo->placa)
                                        @if ($evento->start <= date('Y-m-d H:i:s', strtotime($rotas[0]->dataHoraSaida)) && 
                                             $evento->end >= date('Y-m-d H:i:s', strtotime($rotas[count($rotas)-1]->dataHoraChegada)))
                                            @php
                                                $disponivel = 0;
                                            @endphp
                                        @endif
                                    @endif
                                @endforeach

                                @if($disponivel == 1)
                                    <option value="{{ $veiculo->id }}">{{ $veiculo->marca }} - {{ $veiculo->modelo }} - {{ $veiculo->placa }}</option>
                                @else
                                    <option value="{{ $veiculo->id }}" disabled>{{ $veiculo->marca }} - {{ $veiculo->modelo }} - {{ $veiculo->placa }}</option>
                                @endif
                            @endforeach
                            
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="idUsuario">Usuário:</label>
                        {{-- Inserir opções para os usuários disponíveis --}}

                        <p>{{ Auth::user()->name }}</p>

                    </div>
                    
                    <button type="submit" class="btn btn-primary" onclick="abrirOverlay()">Salvar</button>
                </form>
            </div>
        </div>
    <x-slot name="footerSlot">
        <x-adminlte-button theme="danger" label="Fechar" data-dismiss="modal"/>
    </x-slot>

</x-adminlte-modal>

<br>
<div >
        <span style="color: red"><h3>Rotas cadastradas:</h3></span>
        <table id="tabelaRota" class="table table-hover table-bordered" style="width:100%">
            <thead>
                <tr>
                    <th>Tipo</th>
                    <th>Cidade de saída</th>
                    <th>Data/Hora de saída</th>
                    <th>Cidade de chegada</th>
                    <th>Data/Hora de chegada</th>
                    <th>Hotel?</th>
                    <th>Tipo de transporte</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($rotas as $rota)
                    <tr>
                        <td> {{ $rota->isViagemInternacional == 1 ? 'Internacional' : 'Nacional' }} </td>
                        <td>
                            @if ($rota->isAereo == 1)
                                <img src="{{ asset('/img/aviaosubindo.png') }}" style="width: 40px">
                            @endif

                            @if ($rota->isVeiculoProprio == 1 || $rota->isVeiculoEmpresa == 1)
                                <img src="{{ asset('/img/carro.png') }}" style="width: 40px">
                            @endif

                            @if ($rota->isOnibusLeito == 1 || $rota->isOnibusConvencional == 1)
                                <img src="{{ asset('/img/onibus.png') }}" style="width: 40px">
                            @endif

                            @if($rota->isOutroMeioTransporte == 1)
                                <img src="{{asset('/img/outros.png')}}" style="width: 40px" >
                            @endif

                            {{ $rota->isViagemInternacional == 0 ? $rota->cidadeOrigemNacional : $rota->cidadeOrigemInternacional }}

                        </td>
                        <td> {{ date('d/m/Y H:i', strtotime($rota->dataHoraSaida)) }} </td>

                        <td>
                            @if ($rota->isAereo == 1)
                                <img src="{{ asset('/img/aviaodescendo.png') }}" style="width: 40px">
                            @endif

                            @if ($rota->isVeiculoProprio == 1 || $rota->isVeiculoEmpresa == 1)
                                <img src="{{ asset('/img/carro.png') }}" style="width: 40px">
                            @endif

                            @if ($rota->isOnibusLeito == 1 || $rota->isOnibusConvencional == 1)
                                <img src="{{ asset('/img/onibus.png') }}" style="width: 40px">
                            @endif

                            @if($rota->isOutroMeioTransporte == 1)
                                <img src="{{asset('/img/outros.png')}}" style="width: 40px" >
                            @endif

                            {{ $rota->isViagemInternacional == 0 ? $rota->cidadeDestinoNacional : $rota->cidadeDestinoInternacional }}
                        </td>

                        <td> {{ date('d/m/Y H:i', strtotime($rota->dataHoraChegada)) }} </td>
                        <td> {{ $rota->isReservaHotel == 1 ? 'Sim' : 'Não' }}</td>
                        <td>
                            {{ $rota->isOnibusLeito == 1 ? 'Onibus leito' : '' }}
                            {{ $rota->isOnibusConvencional == 1 ? 'Onibus convencional' : '' }}
                            @if ($rota->isVeiculoProprio == 1)
                                {{ 'Veículo próprio: ' }} <br>
                                @foreach ($veiculosProprios as $v)
                                    @if ($v->id == $rota->veiculoProprio_id)
                                        {{ $v->modelo . '-' . $v->placa }}
                                    @endif
                                @endforeach

                                @if (count($veiculosProprios) == 0)
                                    {{ 'Não encontrado' }}
                                @endif
                            @endif
                            {{ $rota->isVeiculoEmpresa == 1 ? 'Veículo empresa' : '' }}
                            {{ $rota->isAereo == 1 ? 'Aéreo' : '' }}
                            {{ $rota->isOutroMeioTransporte == 1 ? "Outros" : ""}}
                            {{ $rota->isOutroMeioTransporte == 2 ? "Carona" : ""}}
                        </td>
                        @php
                            $achouVeiculo = false;
                        @endphp

                    </tr>
                @endforeach
            </tbody>
        </table>

        @if(count($rotas) > 0 && $rotas[0]->isVeiculoEmpresa == 1)
            <hr>
            <span style="color: red"><h3>Realize a reserva do seu veículo:</h3></span>
            <br>
            <div class="row">
                <div class="col-md-6">

                    {{-- Faça uma lista com todos os veículos com um checkbox em cada um --}}
                    <h3>Veículos: </h3>
                    <div class="d-flex flex-wrap">
                        @foreach ($veiculos as $veiculo)
                            <div class="form-check d-inline mr-3">
                                <input type="checkbox" id="veiculo{{ $veiculo->id }}" name="veiculos[]"
                                    value="{{ $veiculo->marca }} - {{ $veiculo->modelo }} - {{ $veiculo->placa }}" checked>
                                {{ $veiculo->marca }} - {{ $veiculo->modelo }} - {{ $veiculo->placa }}
                            </div>
                        @endforeach
                    </div>

                </div>
                <div class="col-md-6 d-none d-sm-block" id="descricaoEvento"
                    style="border:solid 1px #ccc; min-height: 84px">
                </div>
            </div>
            <div class="row">
                <div class="col-md-7">
                    <div id='calendar'></div>
                </div>
                <div class="col-md-5 table-responsive" id="divTabelaPrincipal">
                    <br><br>
                    <h4 id="botaoAdicionarReserva">
                        <x-adminlte-button label="+" data-toggle="modal" data-target="#my-modal-1" class="bg-green"/>
                        <i class="fas fa-arrow-left" id="texto-reserva"> Faça sua reserva de veículo aqui</i>
                    </h4>                    
                    <h3><i class="fas fa-car"></i> Minhas reservas de veículos: </h3>
                    <table id="tabelaEventos" class="table table-hover table-bordered">
                        <thead>
                            <tr>
                                <th>Data de Início</th>
                                <th>Data de Fim</th>
                                <th>Veículo</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            
                        </tbody>
                    </table>
                </div>
            </div>

            <hr><br>
        @endif

        <span style="color: red"><h3>Revise os valores e envie para o Gestor:</h3></span>
        <form action="/avs/enviarGestor/{{ $av->id }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            @if(count($rotas) > 0 && $rotas[0]->isOutroMeioTransporte == 2)
                <div class="form-group" id="selecaoReserva">
                    <label for="reservaVeiculo_id" class="control-label" required>Selecione a reserva de veículo que você vai de carona:</label>
                    <br>
                        <select class="select select-bordered select-sm w-full max-w-xs {{ $errors->has('reservaVeiculo_id') ? 'is-invalid' :''}}" 
                            id="reservaVeiculo_id" name="reservaVeiculo_id">
                            <option value="" name=""> Selecione</option>
                            @foreach($reservas3 as $r)
                                <div>
                                    <option value="{{ $r->id }}" 
                                        name="{{ $r->id }}"> Reserva {{$r->id}} - {{date('d/m/Y H:i', strtotime($r->dataInicio))}} até {{date('d/m/Y H:i', strtotime($r->dataFim))}} - {{$r->observacoes}} - {{$r->veiculo->marca}} - {{$r->veiculo->modelo}} - {{$r->veiculo->placa}} - {{$r->usuario->name}} </option>
                                </div>
                            @endforeach
                        </select>

                        @if ($errors->has('reservaVeiculo_id'))
                        <div class="invalid-feedback">
                            {{ $errors->first('reservaVeiculo_id') }}
                        </div>
                        @endif
                </div>
            @endif
            
            <div class="row" style="padding-left: 1%">

                <div class="col-12 col-md-4" >
                    <div class="form-group">
                        <label for="valorExtraReais" class="control-label">Você vai precisar de valor extra em reais?</label><br>
                        <input type="text" class="form-control" name="valorExtraReais" oninput="calcular()"
                            id="valorExtraReais" placeholder="Valor Extra em reais" value="{{$av->valorExtraReais}}">
                    </div>

                    <div class="form-group">
                        <label for="valorDeducaoReais" class="control-label">Vai ter deduções em reais?</label>
                        <input type="text" class="form-control bg-yellow-300" name="valorDeducaoReais"
                            id="valorDeducaoReais" placeholder="Valor da dedução em reais" value="{{$av->valorDeducaoReais}}">
                    </div>

                    @if($isInternacional == true)
                    <hr>
                        <div class="form-group">
                            <label for="valorExtraDolar" class="control-label">Você vai precisar de valor extra em dólar?</label>
                            <input type="number" class="form-control" name="valorExtraDolar"
                                id="valorExtraDolar" placeholder="Valor Extra em dólar" value="{{$av->valorExtraDolar}}">
                        </div>
                    @endif

                    @if($isInternacional == true)
                        <div class="form-group">
                            <label for="valorDeducaoDolar" class="control-label">Vai ter deduções em dólar?</label>
                            <input type="number" class="form-control bg-yellow-300" name="valorDeducaoDolar" oninput="calcular()"
                                id="valorDeducaoDolar" placeholder="Valor da dedução em dólar">
                        </div>
                    @endif

                    <hr>

                    <div class="form-group">
                        <label for="justificativaValorExtra" class="control-label">Justificativas</label>
                        <input type="text" class="form-control" name="justificativaValorExtra"
                            id="justificativaValorExtra" placeholder="Justificativa" value="{{$av->justificativaValorExtra}}">
                    </div>
                    
                    <div>
        
                        <div class="alert alert-info">
                            
                            <h5><i class="icon fas fa-check"></i> Diárias de alimentação em reais: </h5>
                            <div id="valorReais" data-value="{{$av->valorReais}}"> <h2>R$ {{$av->valorReais}} </h2></div>

                            
                            <h5><i class="icon fas fa-check"></i> Total após cálculos em reais: </h5>
                            <h2 id="result1"></h2>
                        </div>
                        
                    </div>
                    @if($isInternacional == true)

                        <div class="alert alert-info">
                            
                            <h5><i class="icon fas fa-check"></i> Diárias de alimentação em dólar: </h5>
                            <div id="valorDolar" data-value="{{$av->valorDolar}}"> <h2>$ {{$av->valorDolar}} </h2></div>

                            
                            <h5><i class="icon fas fa-check"></i> Total após cálculos em dólar: </h5>
                            <h2 id="result2"></h2>
                        </div>
                        
                    @endif
                    
                    <br>
                    <div class="text-center">
                        <button type="submit" id="salvarBt" class="btn btn-active btn-primary btn-lg">Enviar <i class="fas fa-paper-plane"></i></button>
                    </div>
                    <br><br>
                </div>
            </div>
        </form>

</div>
    
@stop

@section('css')
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <link href="{{asset('DataTables/datatables.min.css')}}" rel="stylesheet"/>

    <style>
        @keyframes blink {
            0% { opacity: 1; }
            50% { opacity: 0; }
            100% { opacity: 1; }
        }
        
        .blink {
            animation: blink 1s infinite;
        }
    </style>
    
    
@stop

@section('js')
    <script src="{{asset('/js/moment.js')}}"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script src='https://cdn.jsdelivr.net/npm/@fullcalendar/core@6.1.9/index.global.min.js'></script>
    <script src='https://cdn.jsdelivr.net/npm/@fullcalendar/daygrid@6.1.9/index.global.min.js'></script>
    <script src="{{ asset('js/jquery.maskMoney.min.js') }}"></script>
<script type="text/javascript">

    @if(count($rotas) > 0 && $rotas[0]->isVeiculoEmpresa == 1)
        document.getElementById("texto-reserva").classList.add("blink");
    @endif

    $('#salvarBt').on('click', function() {
        // Altera o estilo da <div> para "block"
        $('#custom-tabs-five-overlay').css('display', 'block');
    });

    function desativarCampoObjetivo(){
        var seletor = document.getElementById("flexSwitchCheckDefault")

        if(seletor.checked == true) {
            document.getElementById("outroObjetivo").hidden = false;
            document.getElementById("nomeObjetivo").hidden = true;
            document.getElementById("outroObjetivo").value = "";
            document.getElementById("nomeObjetivo").name = null;
            document.getElementById("isSelecionado").value = "1";
        } else if(seletor.checked == false){
            document.getElementById("outroObjetivo").hidden = true;
            document.getElementById("nomeObjetivo").hidden = false;
            document.getElementById("outroObjetivo").value = "";
            document.getElementById("isSelecionado").value = "0";
        }
    }
    
    function ativarCampoObjetivoInicial(){
        document.getElementById("nomeObjetivo").hidden = false;
        document.getElementById("outroObjetivo").hidden = true;
    }

    function calcular(){

        //deixar o foco da página sempre no final
        window.scrollTo(0,document.body.scrollHeight);

        var valor1 = parseFloat(document.getElementById("valorReais").getAttribute('data-value'));
        var valor3 = document.getElementById('valorExtraReais').value;
        var valor5 = document.getElementById('valorDeducaoReais').value;
        if(document.getElementById('valorExtraReais').value == ""){
            valor3 = 0;
        }
        if(document.getElementById('valorDeducaoReais').value == ""){
            valor5 = 0;
        }

        valor3 = valor3.toString().replace(",", ".");
        valor3 = valor3.replace("R$ ", "");
        valor3 = valor3.replace(/\s/g, '');
        valor3 = parseFloat(valor3);

        valor5 = valor5.toString().replace(",", ".");
        valor5 = valor5.replace("R$ ", "");
        valor5 = valor5.replace(/\s/g, '');
        valor5 = parseFloat(valor5);

        var somaReais = valor1 + valor3 - valor5;
        document.getElementById('result1').innerHTML = "R$ " + somaReais;

        try {
            var valor2 = parseFloat(document.getElementById("valorDolar").getAttribute('data-value'));
            var valor4 = parseFloat(document.getElementById('valorExtraDolar').value);
            var valor6 = parseFloat(document.getElementById('valorDeducaoDolar').value);

            if(document.getElementById('valorExtraDolar').value == ""){
                valor4 = 0;
            }
            if(document.getElementById('valorDeducaoDolar').value == ""){
                valor6 = 0;
            }
            var somaDolar = valor2 + valor4 - valor6;
            
            document.getElementById('result2').innerHTML = "$ " + somaDolar;
        } catch (error) {
        }

        
        
    }

    function calcularInicial(){
        var valor1 = parseFloat(document.getElementById("valorReais").getAttribute('data-value'));
        var valor3 = document.getElementById('valorExtraReais').value;
        var valor5 = document.getElementById('valorDeducaoReais').value;
        if(document.getElementById('valorExtraReais').value == ""){
            valor3 = 0;
        }
        if(document.getElementById('valorDeducaoReais').value == ""){
            valor5 = 0;
        }

        valor3 = valor3.toString().replace(",", ".");
        valor3 = valor3.replace("R$ ", "");
        valor3 = valor3.replace(/\s/g, '');
        valor3 = parseFloat(valor3);

        valor5 = valor5.toString().replace(",", ".");
        valor5 = valor5.replace("R$ ", "");
        valor5 = valor5.replace(/\s/g, '');
        valor5 = parseFloat(valor5);

        var somaReais = valor1 + valor3 - valor5;
        document.getElementById('result1').innerHTML = "R$ " + somaReais;

        try {
            var valor2 = parseFloat(document.getElementById("valorDolar").getAttribute('data-value'));
            var valor4 = parseFloat(document.getElementById('valorExtraDolar').value);
            var valor6 = parseFloat(document.getElementById('valorDeducaoDolar').value);

            if(document.getElementById('valorExtraDolar').value == ""){
                valor4 = 0;
            }
            if(document.getElementById('valorDeducaoDolar').value == ""){
                valor6 = 0;
            }
            var somaDolar = valor2 + valor4 - valor6;
            
            document.getElementById('result2').innerHTML = "$ " + somaDolar;
        } catch (error) {
        }
    }

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': "{{ csrf_token() }}"
        }
    });
    
            //Assim que a tela carrega, aciona automaticamente essas funções ------------------------
    $(function(){
    //Se o campo de outro objetivo for vazio, ativa o campo de seleção de objetivo e desabilita o de outro objetivo
        //espera meio segundo
        
        calcularInicial();

        $('#valorExtraReais').maskMoney({
            prefix: 'R$ ', // Adiciona o prefixo 'R$'
            thousands: '.', // Usa ponto como separador de milhares
            decimal: ',', // Usa vírgula como separador decimal
            allowZero: true, // Permite que o valor comece com zero
            precision: 2, // Define 2 casas decimais
            allowNegative: false // Não permite valores negativos
        });
        $('#valorDeducaoReais').maskMoney({
            prefix: 'R$ ', // Adiciona o prefixo 'R$'
            thousands: '.', // Usa ponto como separador de milhares
            decimal: ',', // Usa vírgula como separador decimal
            allowZero: true, // Permite que o valor comece com zero
            precision: 2, // Define 2 casas decimais
            allowNegative: false // Não permite valores negativos
        });

        // Monitora o evento keyup no campo valorExtraReais
        $('#valorExtraReais').on('keyup', function() {
            calcular(); // Chama a função calcular() sempre que uma tecla for liberada no campo
        });

        // Monitora o evento keyup no campo valorDeducaoReais
        $('#valorDeducaoReais').on('keyup', function() {
            calcular(); // Chama a função calcular() sempre que uma tecla for liberada no campo
        });

        // @if(count($reservas2) > 0)
        //     //esconda o elemento de id botaoAdicionarReserva
        //     document.getElementById("botaoAdicionarReserva").hidden = true;
        // @endif

        if(document.getElementById("outroObjetivo").value == ""){
            ativarCampoObjetivoInicial();
        }else{//Se o campo de outro objetivo tiver algo, faz o contrário
            document.getElementById("nomeObjetivo").hidden = true;//Desabilita seleção de objetivo
            document.getElementById("outroObjetivo").hidden = false;// Habilita campo de outro objetivo
        }

        //Se veio objetivo do banco, habilita o campo de objetivo
        if(document.getElementById("objetivo_id").value != ""){
            document.getElementById("nomeObjetivo").hidden = false;
            document.getElementById("outroObjetivo").hidden = true;//Desabilita o campo de outro objetivo
        }
        
    })  
    @if(count($rotas) > 0 && $rotas[0]->isVeiculoEmpresa == 1)
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');

            var eventos = @json($eventos); // Convertendo a variável PHP para JSON
            var av = @json($av); // Convertendo a variável PHP para JSON

            var calendar = new FullCalendar.Calendar(calendarEl, {
                eventClick: function(info) {
                    var modal = document.getElementById('modalCustom');
                    if (modal) {
                        var descricao = document.getElementById('descricaoReserva');
                        descricao.innerHTML = '<p><strong>' + info.event.title + '</strong></p>';
                        descricao.innerHTML += '<p><strong>Observações: </strong>' +
                            (info.event.extendedProps.observacoes != null ? info.event.extendedProps
                                .observacoes : "") + '</p>';
                        console.log(info);
                        $(modal).modal('show');
                    }
                },
                locale: 'pt-br',
                initialView: 'dayGridMonth',
                selectable: true,
                headerToolbar: {
                    left: '',
                    center: 'title',
                    right: 'prev,next',
                },
                events: eventos.map(function(evento) {
                    return {
                        title: evento.title,
                        start: evento.start,
                        end: evento.end,
                        color: "#378006",
                        observacoes: evento.observacoes
                    };
                }),
                eventMouseEnter: function(info) {
                    //Adicione informações do evento em descricaoEvento
                    var descricao = document.getElementById('descricaoEvento');
                    descricao.innerHTML = '<p><strong>' + info.event.title + '</strong>';
                    descricao.innerHTML += '<p><strong>Observações: </strong>' +
                        (info.event.extendedProps.observacoes != null ? info.event.extendedProps
                            .observacoes : "") + '</p>';
                },
                eventMouseLeave: function(info) {
                    //Remova a borda da div descricaoEvento
                    var descricao = document.getElementById('descricaoEvento');
                    //remova o conteúdo da div descricaoEvento
                    descricao.innerHTML = '';
                },
                datesSet: function(info) {
                    var start = info.startStr; // Data de início do período exibido
                    var end = info.endStr; // Data de término do período exibido
                    var reservasFiltradas = filtrarReservasPorData(start, end);
                    atualizarTabelaEventos(reservasFiltradas);
                },
            });

            function filtrarReservasPorData(start, end) {
                var reservasFiltradas = [];

                @if(isset($reservas2))
                    @foreach ($reservas2 as $reserva)

                        var veiculo = null;
                        @foreach ($veiculos as $v)
                            if ("{{ $reserva->idVeiculo }}" == "{{ $v->id }}") {
                                veiculo = "{{ $v->marca }} - {{ $v->modelo }} - {{ $v->placa }}";
                            }
                        @endforeach

                        if ("{{ $reserva->dataInicio }}" >= start && "{{ $reserva->dataInicio }}" <= end) {
                            reservasFiltradas.push({
                                id: "{{ $reserva->id }}",
                                dataInicio: "{{ $reserva->dataInicio }}",
                                dataFim: "{{ $reserva->dataFim }}",
                                veiculo: {
                                    info: veiculo
                                },
                                usuario: {
                                    name: "{{ Auth::user()->name }}"
                                },
                                observacoes: "{{ $reserva->observacoes }}"
                            });
                        }
                    @endforeach
                @endif

                return reservasFiltradas;
            }

            function atualizarTabelaEventos(reservas) {
                var tabela = $('#tabelaEventos tbody');
                tabela.empty();

                reservas.forEach(function(reserva) {
                    observacao = reserva.observacoes;
                    av = "";
                    //Se a obervacao começar assim: [Reserva realizada pelo Sistema de Viagens, referente a AV:], extraia o numero da av que está na observação assim: "referente a AV: 46"
                    if(observacao != null){
                        if(observacao.includes("referente a AV:")){
                            //pegue somente o numero
                            av = observacao.split("referente a AV:")[1].trim();
                            //remova o ] do final
                            av = av.split("]")[0].trim();
                        }
                    }
                    if(av != ""){
                        var linha = `
                        <tr>
                            <td>${moment(reserva.dataInicio).format('DD/MM/YYYY HH:mm:ss')}</td>
                            <td>${moment(reserva.dataFim).format('DD/MM/YYYY HH:mm:ss')}</td>
                            <td>${reserva.veiculo.info}</td>
                            <td>`;
                            if(av == {{$av->id}}){
                                linha += `
                                <div class="d-flex">
                                    <form action="{{ url('reservasVeiculo/') }}/${reserva.id}/${av}" method="GET" style="display: inline-block;">
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Tem certeza que deseja excluir esta reserva?')"><i class="fas fa-trash-alt"></i></button>
                                    </form>
                                </div>
                                `;
                            }
                            linha += `
                            </td>
                        </tr>`;
                        tabela.append(linha);
                    }
                    else{
                        var linha = `
                        <tr>
                            <td>${moment(reserva.dataInicio).format('DD/MM/YYYY HH:mm:ss')}</td>
                            <td>${moment(reserva.dataFim).format('DD/MM/YYYY HH:mm:ss')}</td>
                            <td>${reserva.veiculo.info}</td>
                            <td>
                            </td>
                        </tr>`;
                        tabela.append(linha);
                    }
                    
                });
            }


            calendar.render();

            // Monitore o clique no checkbox e atualize o calendário
            $('input[type="checkbox"]').click(function() {
                var veiculos = $('input[type="checkbox"]:checked').map(function() {
                    return this.value;
                }).get();

                var eventosFiltrados = eventos.filter(function(evento) {
                    //verifique se cada item de veiculos tem em sua string o conteúdo de evento.placa
                    return veiculos.some(function(veiculo) {
                        return evento.title.indexOf(veiculo) >= 0;
                    });

                });

                calendar.removeAllEvents();
                calendar.addEventSource(eventosFiltrados.map(function(evento) {
                    return {
                        title: evento.title,
                        start: evento.start,
                        end: evento.end,
                        color: "#378006",
                        observacoes: evento.observacoes
                    };
                }));
            });
        });

        // Use moment.js para obter a data atual
        var dataHoje = moment().add(1, 'hours');
        //pega a dataHoje e adicione mais 2 horas e atribua a dataHoje2
        var dataHoje2 = moment().add(3, 'hours');

    // Configure o daterangepicker1
    $('input[name="daterange1"]').daterangepicker({
            opens: 'left',
            timePicker: true,
            timePicker24Hour: true,
            "singleDatePicker": true,
            locale: {
                format: 'DD/MM/YYYY HH:mm',
                applyLabel: 'Escolher',
                cancelLabel: 'Cancelar',
                fromLabel: 'De',
                toLabel: 'Até',
                weekLabel: 'S',
                customRangeLabel: 'Personalizado',
                daysOfWeek: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb'],
                monthNames: [
                    'Janeiro',
                    'Fevereiro',
                    'Março',
                    'Abril',
                    'Maio',
                    'Junho',
                    'Julho',
                    'Agosto',
                    'Setembro',
                    'Outubro',
                    'Novembro',
                    'Dezembro'
                ],
            },
        }, function(start, end, label) {
            console.log("Uma nova seleção de datas foi feita: " + start.format('YYYY-MM-DD HH:mm') +
                ' a ' + end.format('YYYY-MM-DD HH:mm'));
        });

        // Configure o daterangepicker2
        $('input[name="daterange2"]').daterangepicker({
            opens: 'left',
            timePicker: true,
            timePicker24Hour: true,
            "singleDatePicker": true,
            locale: {
                format: 'DD/MM/YYYY HH:mm',
                applyLabel: 'Escolher',
                cancelLabel: 'Cancelar',
                fromLabel: 'De',
                toLabel: 'Até',
                weekLabel: 'S',
                customRangeLabel: 'Personalizado',
                daysOfWeek: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb'],
                monthNames: [
                    'Janeiro',
                    'Fevereiro',
                    'Março',
                    'Abril',
                    'Maio',
                    'Junho',
                    'Julho',
                    'Agosto',
                    'Setembro',
                    'Outubro',
                    'Novembro',
                    'Dezembro'
                ],
            },
        }, function(start, end, label) {
            console.log("Uma nova seleção de datas foi feita: " + start.format('YYYY-MM-DD HH:mm') +
                ' a ' + end.format('YYYY-MM-DD HH:mm'));
        });
    @endif

    function abrirOverlay(){
        // Altera o estilo da <div> para "block"
        $('#custom-tabs-five-overlay').css('display', 'block');
    }
</script>
@stop