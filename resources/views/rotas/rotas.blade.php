@extends('adminlte::page')

@section('title', 'Gerenciar Rotas')

@section('content_header')
    <h1>Gerenciar Rotas</h1>

    <a href="/avs/avs/" type="submit" class="btn btn-warning btn-ghost"><i class="fas fa-arrow-left"></i></a>
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
@stop

@section('content')
    <div>
        <div class="row">
            <div class="col-4">
                <a href="/rotas/create/{{ $av->id }}" type="submit" class="btn btn-active btn-success" style="width: 80px"> <i class="fas fa-plus"></i> ROTA</a>
            </div>
            <div class="col-4">
                <label for="idav" > <strong>AV nº </strong> </label>
                <input style="width: 50px; font-size: 16px; font-weight: bold; color: green" type="text" value="{{ $av->id }}" id="idav" name="idav" disabled>
                <strong style="font-size: 18px">Data: {{ date('d/m/Y', strtotime($av->dataCriacao)) }}</strong>
            </div>
            <div style="display: flex; justify-content: space-between;">

                <form action="/avs/concluir/{{ $av->id }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <input type="text" hidden="true" value="{{ $av->id }}" name="avId" id="avId">
                    @if(count($rotas) > 0 )
                        <div id="btSalvarRota">
                            <button type="submit" class="btn btn-primary">Finalizar <i class="fas fa-thumbs-up"></i></button>
                        </div>
                    @endif
                </form>
                
            </div>
        </div>
        
        <br>
    </div>
    <div class="col-md-10 offset-md-1 dashboard-avs-container">
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
                    </td>
                    <td> 
                        <div class="d-flex align-items-center">
                            <a href="/rotas/edit/{{ $rota->id }}" class="btn btn-warning btn-sm" title="Editar"><i class="far fa-edit"></i></a> 
                            <form action="/rotas/{{ $rota->id }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" title="Remover"><i class="fas fa-trash-alt"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <hr>
        <span style="color: red">Comunicação com o Sistema de Reservas:</span>
        <br>
        @if(count($rotas) > 0 && $rotas[0]->isVeiculoEmpresa == 1)
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
                    <x-adminlte-button label="+" data-toggle="modal" data-target="#my-modal-1" class="bg-green"/>
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
        @endif
        
        @else
        <p>Você ainda não tem rotas, <a href="/rotas/create/{{ $av->id }}"> Criar nova rota</a></p>
        @endif
    </div>
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
                                    <input type="text" id="daterange1" name="daterange1" class="form-control text-center" />
                                </div>
                            </div>
                            <div class="d-flex justify-content-center">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" style="width: 40px">
                                            Até
                                        </span>
                                    </div>
                                    <input type="text" id="daterange2" name="daterange2" class="form-control text-center" />
                                </div>
                            </div>
                        </div>
        
                        <div class="form-group">
                            <label for="idVeiculo">Veículo:</label>
                            <select name="idVeiculo" id="idVeiculo" class="form-control" required>
                                {{-- Inserir opções para os veículos disponíveis --}}
                                @foreach ($veiculos as $veiculo)
                                    <option value="{{ $veiculo->id }}">{{ $veiculo->marca }} - {{ $veiculo->modelo }} -
                                        {{ $veiculo->placa }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="idUsuario">Usuário:</label>
                            {{-- Inserir opções para os usuários disponíveis --}}

                            <p>{{ Auth::user()->name }}</p>

                        </div>
                        
                        <button type="submit" class="btn btn-primary">Salvar</button>
                    </form>
                </div>
            </div>
        <x-slot name="footerSlot">
            <x-adminlte-button theme="danger" label="Fechar" data-dismiss="modal"/>
        </x-slot>

    </x-adminlte-modal>
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

    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script src='https://cdn.jsdelivr.net/npm/@fullcalendar/core@6.1.9/index.global.min.js'></script>
    <script src='https://cdn.jsdelivr.net/npm/@fullcalendar/daygrid@6.1.9/index.global.min.js'></script>
    <script type="text/javascript">

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            }
        });

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
                            }
                        });
                    }
                @endforeach

                return reservasFiltradas;
            }

            function atualizarTabelaEventos(reservas) {
                var tabela = $('#tabelaEventos tbody');
                tabela.empty();

                reservas.forEach(function(reserva) {
                    var linha = `
            <tr>
                <td>${moment(reserva.dataInicio).format('DD/MM/YYYY HH:mm:ss')}</td>
                <td>${moment(reserva.dataFim).format('DD/MM/YYYY HH:mm:ss')}</td>
                <td>${reserva.veiculo.info}</td>
                <td>
                    <div class="d-flex">
                        <form action="{{ url('reservasVeiculo/') }}/${reserva.id}/${av.id}" method="POST" style="display: inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Tem certeza que deseja excluir esta reserva?')"><i class="fas fa-trash-alt"></i></button>
                        </form>
                    </div>
                </td>
            </tr>`;
                    tabela.append(linha);
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

        $('input[name="daterange1"]').daterangepicker({
            startDate: dataHoje,
            opens: 'left',
            timePicker: true,
            timePickerIncrement: 30,
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
            startDate: dataHoje2,
            opens: 'left',
            timePicker: true,
            timePickerIncrement: 30,
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
    </script>
@stop
