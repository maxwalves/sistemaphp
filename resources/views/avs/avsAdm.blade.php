@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Dashboard</h1>
@stop

@section('content')
    
<div class="col-md-12 dashboard-avs-container">
    @if(count($avs) > 0 )
    <nav class="navbar navbar-expand-lg navbar-light bg-light rounded border" aria-label="Eleventh navbar example">
        
            <table class="inputs">
                <tbody>
                    <tr>
                        <td style="padding-left: 10px"><strong>Data viagem:</strong></td>
                        <td>
                            <input data-format="dd/MM/yyyy hh:mm:ss" type="datetime-local" name="dataInicialFiltro1" style="border-width: 1px; border-color: black; width: 150px;"
                                    id="dataInicialFiltro1" placeholder="Data/Hora inicial">
                        </td>
                        <td style="padding-left: 10px"><strong>até: </strong></td>
                        <td>
                            <input data-format="dd/MM/yyyy hh:mm:ss" type="datetime-local" name="dataFinalFiltro1" style="border-width: 1px; border-color: black; width: 150px;"
                                    id="dataFinalFiltro1" placeholder="Data/Hora final">
                        </td>
                    </tr>
                    <tr>
                            <td style="padding-left: 10px"><strong>Data retorno:</strong></td>
                            <td>
                                <input data-format="dd/MM/yyyy hh:mm:ss" type="datetime-local" name="dataInicialFiltro2" style="border-width: 1px; border-color: black; width: 150px;"
                                        id="dataInicialFiltro2" placeholder="Data/Hora inicial">
                            </td>
                            <td style="padding-left: 10px"><strong>até: </strong></td>
                            <td>
                                <input data-format="dd/MM/yyyy hh:mm:ss" type="datetime-local" name="dataFinalFiltro2" style="border-width: 1px; border-color: black; width: 150px;"
                                        id="dataFinalFiltro2" placeholder="Data/Hora final">
                            </td>
                    </tr>
                </tbody>
            </table>
            <div class="row">
                <div class="col-12 col-xl-4">
                        <td style="padding-left: 10px"><strong>Objetivo:</strong></td>
                        <td>
                            <select id="obj" name="obj">
                                <option value="Todos">Todos</option>
                                @for($i = 0; $i < count($objetivos); $i++)

                                        <option value="{{$objetivos[$i]->nomeObjetivo}}">{{$objetivos[$i]->nomeObjetivo}}</option> 
                                    
                                @endfor
                            </select>
                        </td>
                </div>
                <div class="col-12 col-xl-4">
                        <td style="padding-left: 10px"><strong>Usuário:</strong></td>
                        <td>
                            <select id="userField" name="userField">
                                <option value="Todos">Todos</option>
                                @for($i = 0; $i < count($users); $i++)

                                        <option value="{{$users[$i]->name}}">{{$users[$i]->name}}</option> 
                                    
                                @endfor
                            </select>
                        </td>
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
                <th>Nome</th>
                <th>Objetivo</th>
                <th>Rota</th>
                <th>Data AV</th>
                <th>Data viagem</th>
                <th>Data retorno</th>
                <th>Valor total em reais</th>
                <th>Valor total em dólar</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($avs as $av)
            <tr>
                <td scropt="row">{{ $av->id }}</td>
                <td> 
                    <div class="opcoesGerenciarAv">
                        <a href="/avs/verDetalhesAvGerenciar/{{ $av->id }}" class="btn btn-primary btn-sm"> Ver</a> 
                    </div>
                </td>
                <td>
                    @for($i = 0; $i < count($users); $i++)

                        @if ($av->user_id == $users[$i]->id )
                                {{$users[$i]->name}}     
                        @endif

                    @endfor
                </td>
                <td>
                    @for($i = 0; $i < count($objetivos); $i++)

                        @if ($av->objetivo_id == $objetivos[$i]->id )
                                {{$objetivos[$i]->nomeObjetivo}}     
                        @endif

                    @endfor

                    @if (isset($av->outroObjetivo))
                            {{$av->outroObjetivo }} 
                    @endif
                </td>

                <td>
                    @for($i = 0; $i < count($av->rotas); $i++)

                        @if($av->rotas[$i]->isViagemInternacional == 0)
                           - {{$av->rotas[$i]->cidadeOrigemNacional}}
                        @endif
                        
                        @if($av->rotas[$i]->isViagemInternacional == 1)
                           - {{$av->rotas[$i]->cidadeOrigemInternacional}} 
                        @endif

                    @endfor

                </td>

                <td> <a> {{ date('d/m/Y', strtotime($av->dataCriacao)) }} </a></td>
                <td>
                    @if(isset($av->rotas[0]))
                        {{ date('d/m/Y H:i', strtotime($av->rotas[0]->dataHoraSaida)) }}
                    @endif
                </td>
                <td>  
                    @if(isset($av->rotas[count($av->rotas)-1]))
                        {{ date('d/m/Y h:m', strtotime($av->rotas[count($av->rotas)-1]->dataHoraSaida)) }}
                    @endif
                </td>
                <td>
                        R$ {{number_format($av->valorReais + $av->valorExtraReais, 2, ',', '.')}}
                </td>
                <td>
                        $ {{$av->valorDolar + $av->valorExtraDolar}}
                </td>
                <td> {{$av->status}} </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif
    
</div>

@stop

@section('css')
    <link href="{{asset('DataTables/datatables.min.css')}}" rel="stylesheet"/>
@stop

@section('js')
    
    <script src="{{asset('/js/moment.js')}}"></script>
    <script src="{{asset('DataTables/datatables.min.js')}}"></script>
    <script type="text/javascript">

        $(document).ready(function(){
                var tabela = $('#minhaTabela').DataTable({
                    "lengthMenu": [ 30, 50, 100 ],
                    scrollY: 400,
                    order: [0, 'desc'],
                    scrollX: true,
                    "language": {
                        "lengthMenu": "Mostrando _MENU_ registros por página",
                        "zeroRecords": "Nada encontrado",
                        "info": "Mostrando página _PAGE_ de _PAGES_",
                        "infoEmpty": "Nenhum registro disponível",
                        "infoFiltered": "(filtrado de _MAX_ registros no total)",
                        "search": "Pesquisar"
                    },
                    footerCallback: function (row, data, start, end, display) {
                        var api = this.api();

                        // Remove the formatting to get integer data for summation
                        var intVal = function (i) {
                            return typeof i === 'string' ? i.replace(/[^\d.-]/g, '') * 1 : typeof i === 'number' ? i : 0;
                        };

                        // Total over all pages
                        total = api
                            .column(8)
                            .data()
                            .reduce(function (a, b) {
                            return intVal(a) + intVal(b);
                            }, 0);

                        // Total over this page
                        pageTotal = api
                            .column(8, { page: 'current' })
                            .data()
                            .reduce(function (a, b) {
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

                        var numericVal = function (i) {
                        return typeof i === 'string' ? parseFloat(i.replace(/[^0-9.-]+/g, '')) : typeof i === 'number' ? i : 0;
                        };

                        // Total over all pages in dólar
                        totalDolar = api
                        .column(9)
                        .data()
                        .reduce(function (a, b) {
                            return numericVal(a) + numericVal(b);
                        }, 0);

                        // Total over this page in dólar
                        pageTotalDolar = api
                        .column(9, { page: 'current' })
                        .data()
                        .reduce(function (a, b) {
                            return numericVal(a) + numericVal(b);
                        }, 0);

                        // Format the total in dólar
                        totalFormattedDolar = new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format(totalDolar);
                        pageTotalFormattedDolar = new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format(pageTotalDolar);

                        // Update total value field for dólar
                        $('#totalValueDolar').html(pageTotalFormattedDolar);
                        $('#totalValueDolarPagina').html(totalFormattedDolar);
                    },
                });
        });

        $(document).ready(function () {
            var minEl = $('#obj');
        
            // Custom range filtering function
            $.fn.dataTable.ext.search.push(function (settings, data, dataIndex) {
                var min = minEl.val();
                var age = data[3] || 0; // use data for the age column

                if (min == age || min == "Todos"){
                    return true;
                }
        
                return false;
            });
        
            var table = $('#minhaTabela').DataTable();
        
            // Changes to the inputs will trigger a redraw to update the table
            minEl.on('input', function () {
                table.draw();
            });
        });

        $(document).ready(function () {
            var minEl = $('#userField');
        
            // Custom range filtering function
            $.fn.dataTable.ext.search.push(function (settings, data, dataIndex) {
                var min = minEl.val();
                var age = data[2] || 0; // use data for the age column

                if (min == age || min == "Todos"){
                    return true;
                }
        
                return false;
            });
        
            var table = $('#minhaTabela').DataTable();
        
            // Changes to the inputs will trigger a redraw to update the table
            minEl.on('input', function () {
                table.draw();
            });
        });

        $(document).ready(function () {
            var dataInicialFiltro = $('#dataInicialFiltro1');
        
            // Custom range filtering function
            $.fn.dataTable.ext.search.push(function (settings, data, dataIndex) {
                var data1 = dataInicialFiltro.val();
                var dataViagem = data[6] || 0; // use data for the age column

                data1 = moment(data1).format('DD/MM/YYYY H:m');
                
                if (isDataMaior(dataViagem, data1) || isDataIgual(dataViagem, data1) || data1 == "Invalid date"){
                    return true;
                }
        
                return false;
            });
        
            var table = $('#minhaTabela').DataTable();
        
            // Changes to the inputs will trigger a redraw to update the table
            dataInicialFiltro.on('input', function () {
                table.draw();
            });
        });

        $(document).ready(function () {
            var dataFinalFiltro = $('#dataFinalFiltro1');
            
            // Custom range filtering function
            $.fn.dataTable.ext.search.push(function (settings, data, dataIndex) {
                
                var data2 = dataFinalFiltro.val();
                var dataViagem = data[6] || 0; // use data for the age column

                data2 = moment(data2).format('DD/MM/YYYY H:m');
                
                if (isDataMaior(data2, dataViagem) || isDataIgual(data2, dataViagem)  || data2 == "Invalid date"){
                    return true;
                }
        
                return false;
            });
        
            var table = $('#minhaTabela').DataTable();
        
            // Changes to the inputs will trigger a redraw to update the table
            dataFinalFiltro.on('input', function () {
                table.draw();
            });
        });

        $(document).ready(function () {
            var dataInicialFiltro = $('#dataInicialFiltro2');
        
            // Custom range filtering function
            $.fn.dataTable.ext.search.push(function (settings, data, dataIndex) {
                var data1 = dataInicialFiltro.val();
                var dataViagem = data[7] || 0; // use data for the age column

                data1 = moment(data1).format('DD/MM/YYYY H:m');

                if (isDataMaior(dataViagem, data1) || isDataIgual(dataViagem, data1) || data1 == "Invalid date"){
                    return true;
                }
        
                return false;
            });
        
            var table = $('#minhaTabela').DataTable();
        
            // Changes to the inputs will trigger a redraw to update the table
            dataInicialFiltro.on('input', function () {
                table.draw();
            });
        });

        $(document).ready(function () {
            var dataFinalFiltro = $('#dataFinalFiltro2');
        
            // Custom range filtering function
            $.fn.dataTable.ext.search.push(function (settings, data, dataIndex) {
                var data2 = dataFinalFiltro.val();
                var dataViagem = data[7] || 0; // use data for the age column

                data2 = moment(data2).format('DD/MM/YYYY H:m');

                if (isDataMaior(data2, dataViagem) || isDataIgual(data2, dataViagem) || data2 == "Invalid date"){
                    return true;
                }
        
                return false;
            });
        
            var table = $('#minhaTabela').DataTable();
        
            // Changes to the inputs will trigger a redraw to update the table
            dataFinalFiltro.on('input', function () {
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

            // Comparar as datas
            if (date1.getFullYear() == date2.getFullYear()) {
                if(date1.getMonth() == date2.getMonth()){
                    if(date1.getDate() == date2.getDate()){
                        return true;
                    }
                }
            } else {
                return false;
            }
        }

        function toDate(data) {
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

    </script>

@stop