@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
@stop

@section('content')

    <div class="col-md-12 dashboard-avs-container">
        @if (count($avs) > 0)
            <nav class="navbar navbar-expand-lg navbar-light bg-light rounded border" aria-label="Eleventh navbar example">

                <table class="inputs">
                    <tbody>
                        <tr>
                            <td style="padding-left: 10px"><strong>Data viagem:</strong></td>
                            <td>
                                <input data-format="dd/MM/yyyy hh:mm:ss" type="datetime-local" name="dataInicialFiltro1"
                                    style="border-width: 1px; border-color: black; width: 200px;" id="dataInicialFiltro1"
                                    placeholder="Data/Hora inicial">
                            </td>
                            <td style="padding-left: 10px"><strong>até: </strong></td>
                            <td>
                                <input data-format="dd/MM/yyyy hh:mm:ss" type="datetime-local" name="dataFinalFiltro1"
                                    style="border-width: 1px; border-color: black; width: 200px;" id="dataFinalFiltro1"
                                    placeholder="Data/Hora final">
                            </td>
                        </tr>
                        <tr>
                            <td style="padding-left: 10px"><strong>Data retorno:</strong></td>
                            <td>
                                <input data-format="dd/MM/yyyy hh:mm:ss" type="datetime-local" name="dataInicialFiltro2"
                                    style="border-width: 1px; border-color: black; width: 200px;" id="dataInicialFiltro2"
                                    placeholder="Data/Hora inicial">
                            </td>
                            <td style="padding-left: 10px"><strong>até: </strong></td>
                            <td>
                                <input data-format="dd/MM/yyyy hh:mm:ss" type="datetime-local" name="dataFinalFiltro2"
                                    style="border-width: 1px; border-color: black; width: 200px;" id="dataFinalFiltro2"
                                    placeholder="Data/Hora final">
                            </td>
                        </tr>
                    </tbody>
                </table>
                <table class="inputs">
                    <tbody>
                        <tr>
                            <td><strong>Objetivo:</strong></td>
                            <td>
                                <select id="obj" name="obj" style="border-width: 1px; border-color: black; width: 200px;">
                                    <option value="Todos">Todos</option>
                                    @for ($i = 0; $i < count($objetivos); $i++)
                                        <option value="{{ $objetivos[$i]->nomeObjetivo }}">{{ $objetivos[$i]->nomeObjetivo }}
                                        </option>
                                    @endfor
                                </select>
                            </td>
                            <td><strong>Status:</strong></td>
                            <td>
                                <select id="statusField" name="statusField" style="border-width: 1px; border-color: black; width: 400px;">
                                    <option value="Todos">Todos</option>
                                    <option value="Acerto de Contas aprovado pelo usuário - AV finalizada">Acerto de Contas aprovado pelo usuário - AV finalizada</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Usuário:</strong></td>
                            <td>
                                <select id="userField" name="userField" style="border-width: 1px; border-color: black; width: 200px;">
                                    <option value="Todos">Todos</option>
                                    @for ($i = 0; $i < count($users); $i++)
                                        <option value="{{ $users[$i]->name }}">{{ $users[$i]->name }}</option>
                                    @endfor
                                </select>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </nav>
            <nav class="navbar navbar-expand-lg navbar-light bg-light rounded border" aria-label="Eleventh navbar example">
                <div class="row">
                    <div class="col-12">
                        {{-- botao com a escrita Janeiro --}}
                        <button class="btn btn-primary" id="botaoJaneiro" onclick="filtrarJaneiro()">JAN {{ date('y') }}</button>

                        {{-- botao com a escrita Fevereiro --}}
                        <button class="btn btn-primary" id="botaoFevereiro" onclick="filtrarFevereiro()">FEV {{ date('y') }}</button>

                        {{-- botao com a escrita Março --}}
                        <button class="btn btn-primary" id="botaoMarco" onclick="filtrarMarco()">MAR {{ date('y') }}</button>

                        {{-- botao com a escrita Abril --}}
                        <button class="btn btn-primary" id="botaoAbril" onclick="filtrarAbril()">ABR {{ date('y') }}</button>

                        {{-- botao com a escrita Maio --}}
                        <button class="btn btn-primary" id="botaoMaio" onclick="filtrarMaio()">MAIO {{ date('y') }}</button>

                        {{-- botao com a escrita Junho --}}
                        <button class="btn btn-primary" id="botaoJunho" onclick="filtrarJunho()">JUN {{ date('y') }}</button>

                        {{-- botao com a escrita Julho --}}
                        <button class="btn btn-primary" id="botaoJulho" onclick="filtrarJulho()">JUL {{ date('y') }}</button>

                        {{-- botao com a escrita Agosto --}}
                        <button class="btn btn-primary" id="botaoAgosto" onclick="filtrarAgosto()">AGO {{ date('y') }}</button>

                        {{-- botao com a escrita Setembro --}}
                        <button class="btn btn-primary" id="botaoSetembro" onclick="filtrarSetembro()">SET {{ date('y') }}</button>

                        {{-- botao com a escrita Outubro --}}
                        <button class="btn btn-primary" id="botaoOutubro" onclick="filtrarOutubro()">OUT {{ date('y') }}</button>

                        {{-- botao com a escrita Novembro --}}
                        <button class="btn btn-primary" id="botaoNovembro" onclick="filtrarNovembro()">NOV {{ date('y') }}</button>

                        {{-- botao com a escrita Dezembro --}}
                        <button class="btn btn-primary" id="botaoDezembro" onclick="filtrarDezembro()">DEZ {{ date('y') }}</button>

                        {{-- botao com a escrita Todos --}}
                        <button class="btn btn-primary" id="botaoTodos" onclick="filtrarTodos()">TODOS</button>
                    </div>

                </div>
            </nav>
            <nav class="navbar navbar-expand-lg navbar-light bg-light rounded border" aria-label="Eleventh navbar example">
                <div class="row">
                    <div class="col-12 col-xl-2">
                        <strong>Valor em reais filtro: </strong>
                        <span id="totalValue"></span>
                    </div>
                    <div class="col-12 col-xl-2">
                        <strong>Valor em dólar filtro: </strong>
                        <span id="totalValueDolar"></span>
                    </div>
                    <div class="col-12 col-xl-2">

                    </div>
                    <div class="col-12 col-xl-3">
                        <strong>Valor TOTAL TODOS em reais: </strong>
                        <span id="totalValuePagina"></span>
                    </div>
                    <div class="col-12 col-xl-3">
                        <strong>Valor TOTAL TODOS em dólar: </strong>
                        <span id="totalValueDolarPagina"></span>
                    </div>
                </div>
            </nav>
            <br>

            <table id="minhaTabela" class="display nowrap">
                <thead>
                    <tr>
                        <th style="width: 50px">Nr</th>
                        <th>Ver</th>
                        <th>Data viagem</th>
                        <th>Data retorno</th>
                        <th>Nome</th>
                        <th>Status</th>
                        <th>Rota</th>
                        <th>Objetivo</th>
                        <th>Valor total em reais</th>
                        <th>Valor total em dólar</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($avs as $av)
                        <tr>
                            <td scropt="row">{{ $av->id }}</td>
                            <td>
                                <div class="opcoesGerenciarAv">
                                    <a href="/avs/verDetalhesAvGerenciarRh/{{ $av->id }}"
                                        class="btn btn-primary btn-sm"> Ver</a>
                                </div>
                            </td>
                            <td>
                                @if (isset($av->rotas[0]))
                                    {{ date('d/m/Y H:i', strtotime($av->rotas[0]->dataHoraSaida)) }}
                                @endif
                            </td>
                            <td>
                                @if (isset($av->rotas[count($av->rotas) - 1]))
                                    {{ date('d/m/Y h:m', strtotime($av->rotas[count($av->rotas) - 1]->dataHoraChegada)) }}
                                @endif
                            </td>
                            <td>
                                @for ($i = 0; $i < count($users); $i++)
                                    @if ($av->user_id == $users[$i]->id)
                                        {{ $users[$i]->name }}
                                    @endif
                                @endfor
                            </td>
                            <td> {{ $av->status }} </td>
                            <td>
                                @for ($i = 0; $i < count($av->rotas); $i++)
                                    @if ($av->rotas[$i]->isViagemInternacional == 0)
                                        - {{ $av->rotas[$i]->cidadeOrigemNacional }}
                                    @endif

                                    @if ($av->rotas[$i]->isViagemInternacional == 1)
                                        - {{ $av->rotas[$i]->cidadeOrigemInternacional }}
                                    @endif
                                @endfor

                            </td>
                            <td>
                                @for ($i = 0; $i < count($objetivos); $i++)
                                    @if ($av->objetivo_id == $objetivos[$i]->id)
                                        {{ $objetivos[$i]->nomeObjetivo }}
                                    @endif
                                @endfor

                                @if (isset($av->outroObjetivo))
                                    {{ $av->outroObjetivo }}
                                @endif
                            </td>
                            <td>
                                R$ {{ number_format($av->valorReais + $av->valorExtraReais, 2, ',', '.') }}
                            </td>
                            <td>
                                $ {{ $av->valorDolar + $av->valorExtraDolar }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif

    </div>

@stop

@section('css')
    <link href="{{ asset('DataTables/datatables.min.css') }}" rel="stylesheet" />
@stop

@section('js')

    <script src="{{ asset('/js/moment.js') }}"></script>
    <script src="{{ asset('DataTables/datatables.min.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            var tabela = $('#minhaTabela').DataTable({
                "lengthMenu": [30, 50, 100],
                scrollY: 500,
                order: [2, 'asc'],
                scrollX: true,
                "language": {
                    "lengthMenu": "Mostrando _MENU_ registros por página",
                    "zeroRecords": "Nada encontrado",
                    "info": "Mostrando página _PAGE_ de _PAGES_",
                    "infoEmpty": "Nenhum registro disponível",
                    "infoFiltered": "(filtrado de _MAX_ registros no total)",
                    "search": "Pesquisar"
                },
                footerCallback: function(row, data, start, end, display) {
                    var api = this.api();

                    // Remove the formatting to get integer data for summation
                    var intVal = function(i) {
                        return typeof i === 'string' ? i.replace(/[^\d.-]/g, '') * 1 : typeof i ===
                            'number' ? i : 0;
                    };

                    // Total over all pages
                    total = api
                        .column(8)
                        .data()
                        .reduce(function(a, b) {
                            return intVal(a) + intVal(b);
                        }, 0);

                    // Total over this page
                    pageTotal = api
                        .column(8, {
                            page: 'current'
                        })
                        .data()
                        .reduce(function(a, b) {
                            return intVal(a) + intVal(b);
                        }, 0);

                    // Format the totals
                    totalFormatted = 'R$' + (total / 100).toLocaleString('pt-BR', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    });

                    pageTotalFormatted = 'R$' + (pageTotal / 100).toLocaleString('pt-BR', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    });

                    // Update footer
                    $('#totalValue').html(pageTotalFormatted);
                    $('#totalValuePagina').html(totalFormatted);

                    var numericVal = function(i) {
                        return typeof i === 'string' ? parseFloat(i.replace(/[^0-9.-]+/g, '')) :
                            typeof i === 'number' ? i : 0;
                    };

                    // Total over all pages in dólar
                    totalDolar = api
                        .column(9)
                        .data()
                        .reduce(function(a, b) {
                            return numericVal(a) + numericVal(b);
                        }, 0);

                    // Total over this page in dólar
                    pageTotalDolar = api
                        .column(9, {
                            page: 'current'
                        })
                        .data()
                        .reduce(function(a, b) {
                            return numericVal(a) + numericVal(b);
                        }, 0);

                    // Format the total in dólar
                    totalFormattedDolar = new Intl.NumberFormat('en-US', {
                        style: 'currency',
                        currency: 'USD'
                    }).format(totalDolar);
                    pageTotalFormattedDolar = new Intl.NumberFormat('en-US', {
                        style: 'currency',
                        currency: 'USD'
                    }).format(pageTotalDolar);

                    // Update total value field for dólar
                    $('#totalValueDolar').html(pageTotalFormattedDolar);
                    $('#totalValueDolarPagina').html(totalFormattedDolar);
                },
            });
        });

        $(document).ready(function() {
            var minEl = $('#obj');

            // Custom range filtering function
            $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
                var min = minEl.val();
                var age = data[7] || 0; // use data for the age column

                if (min == age || min == "Todos") {
                    return true;
                }

                return false;
            });

            var table = $('#minhaTabela').DataTable();

            // Changes to the inputs will trigger a redraw to update the table
            minEl.on('input', function() {
                table.draw();
            });
        });

        $(document).ready(function() {
            var minEl = $('#userField');

            // Custom range filtering function
            $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
                var min = minEl.val();
                var age = data[4] || 0; // use data for the age column

                if (min == age || min == "Todos") {
                    return true;
                }

                return false;
            });

            var table = $('#minhaTabela').DataTable();

            // Changes to the inputs will trigger a redraw to update the table
            minEl.on('input', function() {
                table.draw();
            });
        });

        $(document).ready(function() {
            var minEl = $('#statusField');

            // Custom range filtering function
            $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
                var min = minEl.val();
                var age = data[5] || 0; // use data for the age column

                if (min == age || min == "Todos") {
                    return true;
                }

                return false;
            });

            var table = $('#minhaTabela').DataTable();

            // Changes to the inputs will trigger a redraw to update the table
            minEl.on('input', function() {
                table.draw();
            });
        });

        $(document).ready(function() {
            var dataInicialFiltro = $('#dataInicialFiltro1');
            //sete a data inicial para o primeiro dia do mês
            var data = new Date();
            var primeiroDia = new Date(data.getFullYear(), data.getMonth(), 1);
            dataInicialFiltro.val(primeiroDia.toISOString().substring(0, 16));

            // Custom range filtering function
            $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
                dataInicialFiltro = $('#dataInicialFiltro1');
                var data1 = dataInicialFiltro.val();
                var dataViagem = data[2] || 0; // use data for the age column

                data1 = moment(data1).format('DD/MM/YYYY H:m');

                if (isDataMaior(dataViagem, data1) || isDataIgual(dataViagem, data1) || data1 ==
                    "Invalid date") {
                    return true;
                }

                return false;
            });

            var table = $('#minhaTabela').DataTable();

            // Changes to the inputs will trigger a redraw to update the table
            dataInicialFiltro.on('input', function() {
                table.draw();
            });
        });

        $(document).ready(function() {
            var dataFinalFiltro = $('#dataFinalFiltro1');
            //sete a data inicial para o último dia do mês
            var data = new Date();
            var ultimoDia = new Date(data.getFullYear(), data.getMonth() + 1, 15);
            dataFinalFiltro.val(ultimoDia.toISOString().substring(0, 16));

            // Custom range filtering function
            $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {

                var data2 = dataFinalFiltro.val();
                var dataViagem = data[2] || 0; // use data for the age column

                data2 = moment(data2).format('DD/MM/YYYY H:m');

                if (isDataMaior(data2, dataViagem) || isDataIgual(data2, dataViagem) || data2 ==
                    "Invalid date") {
                    return true;
                }

                return false;
            });

            var table = $('#minhaTabela').DataTable();

            // Changes to the inputs will trigger a redraw to update the table
            dataFinalFiltro.on('input', function() {
                table.draw();
            });
        });

        $(document).ready(function() {
            var dataInicialFiltro = $('#dataInicialFiltro2');

            //sete a data inicial para o primeiro dia do mês
            var data = new Date();
            var primeiroDia = new Date(data.getFullYear(), data.getMonth(), 1);
            dataInicialFiltro.val(primeiroDia.toISOString().substring(0, 16));

            // Custom range filtering function
            $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
                var data1 = dataInicialFiltro.val();
                var dataViagem = data[3] || 0; // use data for the age column

                data1 = moment(data1).format('DD/MM/YYYY H:m');

                if (isDataMaior(dataViagem, data1) || isDataIgual(dataViagem, data1) || data1 ==
                    "Invalid date") {
                    return true;
                }

                return false;
            });

            var table = $('#minhaTabela').DataTable();

            // Changes to the inputs will trigger a redraw to update the table
            dataInicialFiltro.on('input', function() {
                table.draw();
            });
        });

        $(document).ready(function() {
            var dataFinalFiltro = $('#dataFinalFiltro2');

            //sete a data inicial para o último dia do mês
            var data = new Date();
            var ultimoDia = new Date(data.getFullYear(), data.getMonth() + 1, 15);
            dataFinalFiltro.val(ultimoDia.toISOString().substring(0, 16));

            // Custom range filtering function
            $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
                var data2 = dataFinalFiltro.val();
                var dataViagem = data[3] || 0; // use data for the age column

                data2 = moment(data2).format('DD/MM/YYYY H:m');

                if (isDataMaior(data2, dataViagem) || isDataIgual(data2, dataViagem) || data2 ==
                    "Invalid date") {
                    return true;
                }

                return false;
            });

            var table = $('#minhaTabela').DataTable();

            // Changes to the inputs will trigger a redraw to update the table
            dataFinalFiltro.on('input', function() {
                table.draw();
            });
        });

        function isDataMaior(data1, data2) {
            // Converter as strings de data para objetos Date
            const date1 = toDate(data1);
            const date2 = toDate(data2);

            // Comparar as datas
            if (date1 > date2) {
                return true;
            } else {
                return false;
            }
        }

        function isDataIgual(data1, data2) {
            // Converter as strings de data para objetos Date
            const date1 = toDate(data1);
            const date2 = toDate(data2);

            if (date1 == null || date2 == null) {
                return false;
            } else {
                // Comparar as datas
                if (date1.getFullYear() == date2.getFullYear()) {
                    if (date1.getMonth() == date2.getMonth()) {
                        if (date1.getDate() == date2.getDate()) {
                            return true;
                        }
                    }
                } else {
                    return false;
                }
            }
        }

        function toDate(data) {
            //verifique se data existe
            if (!data) {
                return null;
            } else {
                // Extrair os componentes da data e hora
                const partes = data.split(' ');
                const dataPartes = partes[0].split('/');
                const horaPartes = partes[1].split(':');

                // Criar o objeto Date com os componentes extraídos
                const ano = parseInt(dataPartes[2], 10);
                const mes = parseInt(dataPartes[1], 10) - 1; // Os meses no objeto Date começam em zero
                const dia = parseInt(dataPartes[0], 10);
                const hora = parseInt(horaPartes[0], 10);
                const minuto = parseInt(horaPartes[1], 10);
                const dataObj = new Date(ano, mes, dia, hora, minuto);

                return dataObj;
            }
        }

        function filtrarJaneiro() {
            var dataInicialFiltro = $('#dataInicialFiltro1');
            var dataFinalFiltro = $('#dataFinalFiltro1');
            var dataInicialFiltro2 = $('#dataInicialFiltro2');
            var dataFinalFiltro2 = $('#dataFinalFiltro2');

            var dataInicialJaneiro = moment('01/01/{{ date('Y') }} 00:00', 'DD/MM/YYYY HH:mm').format('YYYY-MM-DDTHH:mm');
            var dataFinalJaneiro = moment('31/01/{{ date('Y') }} 23:59', 'DD/MM/YYYY HH:mm').format('YYYY-MM-DDTHH:mm');

            dataInicialFiltro.val(dataInicialJaneiro);
            dataFinalFiltro.val(dataFinalJaneiro);
            dataInicialFiltro2.val(dataInicialJaneiro);
            dataFinalFiltro2.val(dataFinalJaneiro);

            $('#minhaTabela').DataTable().draw();
        }

        function filtrarFevereiro() {
            var dataInicialFiltro = $('#dataInicialFiltro1');
            var dataFinalFiltro = $('#dataFinalFiltro1');
            var dataInicialFiltro2 = $('#dataInicialFiltro2');
            var dataFinalFiltro2 = $('#dataFinalFiltro2');

            var dataInicialFevereiro = moment('01/02/{{ date('Y') }} 00:00', 'DD/MM/YYYY HH:mm').format('YYYY-MM-DDTHH:mm');
            var dataFinalFevereiro = moment('28/02/{{ date('Y') }} 23:59', 'DD/MM/YYYY HH:mm').format('YYYY-MM-DDTHH:mm');

            dataInicialFiltro.val(dataInicialFevereiro);
            dataFinalFiltro.val(dataFinalFevereiro);
            dataInicialFiltro2.val(dataInicialFevereiro);
            dataFinalFiltro2.val(dataFinalFevereiro);

            $('#minhaTabela').DataTable().draw();
        }

        function filtrarMarco() {
            var dataInicialFiltro = $('#dataInicialFiltro1');
            var dataFinalFiltro = $('#dataFinalFiltro1');
            var dataInicialFiltro2 = $('#dataInicialFiltro2');
            var dataFinalFiltro2 = $('#dataFinalFiltro2');

            var dataInicialMarco = moment('01/03/{{ date('Y') }} 00:00', 'DD/MM/YYYY HH:mm').format('YYYY-MM-DDTHH:mm');
            var dataFinalMarco = moment('31/03/{{ date('Y') }} 23:59', 'DD/MM/YYYY HH:mm').format('YYYY-MM-DDTHH:mm');

            dataInicialFiltro.val(dataInicialMarco);
            dataFinalFiltro.val(dataFinalMarco);
            dataInicialFiltro2.val(dataInicialMarco);
            dataFinalFiltro2.val(dataFinalMarco);

            $('#minhaTabela').DataTable().draw();
        }

        function filtrarAbril() {
            var dataInicialFiltro = $('#dataInicialFiltro1');
            var dataFinalFiltro = $('#dataFinalFiltro1');
            var dataInicialFiltro2 = $('#dataInicialFiltro2');
            var dataFinalFiltro2 = $('#dataFinalFiltro2');

            var dataInicialAbril = moment('01/04/{{ date('Y') }} 00:00', 'DD/MM/YYYY HH:mm').format('YYYY-MM-DDTHH:mm');
            var dataFinalAbril = moment('30/04/{{ date('Y') }} 23:59', 'DD/MM/YYYY HH:mm').format('YYYY-MM-DDTHH:mm');

            dataInicialFiltro.val(dataInicialAbril);
            dataFinalFiltro.val(dataFinalAbril);
            dataInicialFiltro2.val(dataInicialAbril);
            dataFinalFiltro2.val(dataFinalAbril);

            $('#minhaTabela').DataTable().draw();
        }

        function filtrarMaio() {
            var dataInicialFiltro = $('#dataInicialFiltro1');
            var dataFinalFiltro = $('#dataFinalFiltro1');
            var dataInicialFiltro2 = $('#dataInicialFiltro2');
            var dataFinalFiltro2 = $('#dataFinalFiltro2');

            var dataInicialMaio = moment('01/05/{{ date('Y') }} 00:00', 'DD/MM/YYYY HH:mm').format('YYYY-MM-DDTHH:mm');
            var dataFinalMaio = moment('31/05/{{ date('Y') }} 23:59', 'DD/MM/YYYY HH:mm').format('YYYY-MM-DDTHH:mm');

            dataInicialFiltro.val(dataInicialMaio);
            dataFinalFiltro.val(dataFinalMaio);
            dataInicialFiltro2.val(dataInicialMaio);
            dataFinalFiltro2.val(dataFinalMaio);

            $('#minhaTabela').DataTable().draw();
        }

        function filtrarJunho() {
            var dataInicialFiltro = $('#dataInicialFiltro1');
            var dataFinalFiltro = $('#dataFinalFiltro1');
            var dataInicialFiltro2 = $('#dataInicialFiltro2');
            var dataFinalFiltro2 = $('#dataFinalFiltro2');

            var dataInicialJunho = moment('01/06/{{ date('Y') }} 00:00', 'DD/MM/YYYY HH:mm').format('YYYY-MM-DDTHH:mm');
            var dataFinalJunho = moment('30/06/{{ date('Y') }} 23:59', 'DD/MM/YYYY HH:mm').format('YYYY-MM-DDTHH:mm');

            dataInicialFiltro.val(dataInicialJunho);
            dataFinalFiltro.val(dataFinalJunho);
            dataInicialFiltro2.val(dataInicialJunho);
            dataFinalFiltro2.val(dataFinalJunho);

            $('#minhaTabela').DataTable().draw();
        }

        function filtrarJulho() {
            var dataInicialFiltro = $('#dataInicialFiltro1');
            var dataFinalFiltro = $('#dataFinalFiltro1');
            var dataInicialFiltro2 = $('#dataInicialFiltro2');
            var dataFinalFiltro2 = $('#dataFinalFiltro2');

            var dataInicialJulho = moment('01/07/{{ date('Y') }} 00:00', 'DD/MM/YYYY HH:mm').format('YYYY-MM-DDTHH:mm');
            var dataFinalJulho = moment('31/07/{{ date('Y') }} 23:59', 'DD/MM/YYYY HH:mm').format('YYYY-MM-DDTHH:mm');

            dataInicialFiltro.val(dataInicialJulho);
            dataFinalFiltro.val(dataFinalJulho);
            dataInicialFiltro2.val(dataInicialJulho);
            dataFinalFiltro2.val(dataFinalJulho);

            $('#minhaTabela').DataTable().draw();
        }

        function filtrarAgosto() {
            var dataInicialFiltro = $('#dataInicialFiltro1');
            var dataFinalFiltro = $('#dataFinalFiltro1');
            var dataInicialFiltro2 = $('#dataInicialFiltro2');
            var dataFinalFiltro2 = $('#dataFinalFiltro2');

            var dataInicialAgosto = moment('01/08/{{ date('Y') }} 00:00', 'DD/MM/YYYY HH:mm').format('YYYY-MM-DDTHH:mm');
            var dataFinalAgosto = moment('31/08/{{ date('Y') }} 23:59', 'DD/MM/YYYY HH:mm').format('YYYY-MM-DDTHH:mm');

            dataInicialFiltro.val(dataInicialAgosto);
            dataFinalFiltro.val(dataFinalAgosto);
            dataInicialFiltro2.val(dataInicialAgosto);
            dataFinalFiltro2.val(dataFinalAgosto);

            $('#minhaTabela').DataTable().draw();
        }

        function filtrarSetembro() {
            var dataInicialFiltro = $('#dataInicialFiltro1');
            var dataFinalFiltro = $('#dataFinalFiltro1');
            var dataInicialFiltro2 = $('#dataInicialFiltro2');
            var dataFinalFiltro2 = $('#dataFinalFiltro2');

            var dataInicialSetembro = moment('01/09/{{ date('Y') }} 00:00', 'DD/MM/YYYY HH:mm').format('YYYY-MM-DDTHH:mm');
            var dataFinalSetembro = moment('30/09/{{ date('Y') }} 23:59', 'DD/MM/YYYY HH:mm').format('YYYY-MM-DDTHH:mm');

            dataInicialFiltro.val(dataInicialSetembro);
            dataFinalFiltro.val(dataFinalSetembro);
            dataInicialFiltro2.val(dataInicialSetembro);
            dataFinalFiltro2.val(dataFinalSetembro);

            $('#minhaTabela').DataTable().draw();
        }

        function filtrarOutubro() {
            var dataInicialFiltro = $('#dataInicialFiltro1');
            var dataFinalFiltro = $('#dataFinalFiltro1');
            var dataInicialFiltro2 = $('#dataInicialFiltro2');
            var dataFinalFiltro2 = $('#dataFinalFiltro2');

            var dataInicialOutubro = moment('01/10/{{ date('Y') }} 00:00', 'DD/MM/YYYY HH:mm').format('YYYY-MM-DDTHH:mm');
            var dataFinalOutubro = moment('31/10/{{ date('Y') }} 23:59', 'DD/MM/YYYY HH:mm').format('YYYY-MM-DDTHH:mm');

            dataInicialFiltro.val(dataInicialOutubro);
            dataFinalFiltro.val(dataFinalOutubro);
            dataInicialFiltro2.val(dataInicialOutubro);
            dataFinalFiltro2.val(dataFinalOutubro);

            $('#minhaTabela').DataTable().draw();
        }

        function filtrarNovembro() {
            var dataInicialFiltro = $('#dataInicialFiltro1');
            var dataFinalFiltro = $('#dataFinalFiltro1');
            var dataInicialFiltro2 = $('#dataInicialFiltro2');
            var dataFinalFiltro2 = $('#dataFinalFiltro2');

            var dataInicialNovembro = moment('01/11/{{ date('Y') }} 00:00', 'DD/MM/YYYY HH:mm').format('YYYY-MM-DDTHH:mm');
            var dataFinalNovembro = moment('30/11/{{ date('Y') }} 23:59', 'DD/MM/YYYY HH:mm').format('YYYY-MM-DDTHH:mm');

            dataInicialFiltro.val(dataInicialNovembro);
            dataFinalFiltro.val(dataFinalNovembro);
            dataInicialFiltro2.val(dataInicialNovembro);
            dataFinalFiltro2.val(dataFinalNovembro);

            $('#minhaTabela').DataTable().draw();
        }

        function filtrarDezembro() {
            var dataInicialFiltro = $('#dataInicialFiltro1');
            var dataFinalFiltro = $('#dataFinalFiltro1');
            var dataInicialFiltro2 = $('#dataInicialFiltro2');
            var dataFinalFiltro2 = $('#dataFinalFiltro2');

            var dataInicialDezembro = moment('01/12/{{ date('Y') }} 00:00', 'DD/MM/YYYY HH:mm').format('YYYY-MM-DDTHH:mm');
            var dataFinalDezembro = moment('31/12/{{ date('Y') }} 23:59', 'DD/MM/YYYY HH:mm').format('YYYY-MM-DDTHH:mm');

            dataInicialFiltro.val(dataInicialDezembro);
            dataFinalFiltro.val(dataFinalDezembro);
            dataInicialFiltro2.val(dataInicialDezembro);
            dataFinalFiltro2.val(dataFinalDezembro);

            $('#minhaTabela').DataTable().draw();
        }

        function filtrarTodos() {
            var dataInicialFiltro = $('#dataInicialFiltro1');
            var dataFinalFiltro = $('#dataFinalFiltro1');
            var dataInicialFiltro2 = $('#dataInicialFiltro2');
            var dataFinalFiltro2 = $('#dataFinalFiltro2');

            dataInicialFiltro.val('');
            dataFinalFiltro.val('');
            dataInicialFiltro2.val('');
            dataFinalFiltro2.val('');

            $('#minhaTabela').DataTable().draw();
        }

    </script>

@stop
