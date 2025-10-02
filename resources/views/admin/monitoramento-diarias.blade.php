@extends('adminlte::page')

@section('title', 'Monitoramento de Diárias')

@section('content_header')
    <h1>
        <i class="fas fa-chart-line"></i> Monitoramento de Diárias
        <small class="text-muted">Comparação entre valores calculados vs valores armazenados</small>
    </h1>
@stop

@section('content')

<div class="row mb-3">
    <div class="col-md-12">
        <a href="/admin" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Voltar ao Painel Admin
        </a>
    </div>
</div>

<!-- Informações sobre a análise -->
<div class="row mb-3">
    <div class="col-12">
        <div class="alert alert-info">
            <h5><i class="fas fa-info-circle"></i> Sobre esta Análise</h5>
            <p class="mb-1">
                <strong>Objetivo:</strong> Verificar se os valores calculados automaticamente pelas rotas conferem com os valores armazenados nos campos <code>valorReais</code> e <code>valorDolar</code> da AV.
            </p>
            <p class="mb-1">
                <strong>Método:</strong> Utiliza a função <code>geraArrayDiasValoresCerto($av)</code> para recalcular os valores baseados nas rotas atuais.
            </p>
            <p class="mb-1">
                <strong>Status Analisados:</strong><br>
                <span class="badge badge-secondary mb-1">Aguardando reserva pela CAD e adiantamento pela CFI</span><br>
                <span class="badge badge-secondary mb-1">Aguardando aprovação da prestação de contas pelo Gestor</span><br>
                <span class="badge badge-secondary mb-1">Aguardando aprovação da Prestação de Contas pelo Financeiro</span><br>
                <span class="badge badge-secondary mb-1">Aguardando acerto de contas pelo financeiro</span>
            </p>
            <p class="mb-0">
                <strong>Tolerância:</strong> Diferenças de até R$ 5,00 são consideradas normais e não geram alertas.
            </p>
        </div>
    </div>
</div>

<!-- Cards de estatísticas -->
<div class="row mb-4">
    <div class="col-lg-4 col-md-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3>{{ $totalAnalisadas }}</h3>
                <p>AVs Analisadas</p>
            </div>
            <div class="icon">
                <i class="fas fa-search"></i>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4 col-md-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3>{{ $totalIrregularidades }}</h3>
                <p>Irregularidades Detectadas</p>
            </div>
            <div class="icon">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4 col-md-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>{{ number_format((($totalAnalisadas - $totalIrregularidades) / max($totalAnalisadas, 1)) * 100, 1) }}%</h3>
                <p>Taxa de Conformidade</p>
            </div>
            <div class="icon">
                <i class="fas fa-check-circle"></i>
            </div>
        </div>
    </div>
</div>

@if(count($irregularidades) > 0 && isset($estatisticas))
<!-- Estatísticas Detalhadas -->
<div class="row mb-4">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-chart-pie"></i> Distribuição por Severidade
                </h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 text-center">
                        <div class="description-block border-right">
                            <span class="description-percentage text-danger">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $estatisticas['severidade_alta'] }}
                            </span>
                            <h5 class="description-header">Alta</h5>
                            <span class="description-text">Severidade</span>
                        </div>
                    </div>
                    <div class="col-md-4 text-center">
                        <div class="description-block border-right">
                            <span class="description-percentage text-warning">
                                <i class="fas fa-exclamation-triangle"></i>
                                {{ $estatisticas['severidade_media'] }}
                            </span>
                            <h5 class="description-header">Média</h5>
                            <span class="description-text">Severidade</span>
                        </div>
                    </div>
                    <div class="col-md-4 text-center">
                        <div class="description-block">
                            <span class="description-percentage text-info">
                                <i class="fas fa-info-circle"></i>
                                {{ $estatisticas['severidade_baixa'] }}
                            </span>
                            <h5 class="description-header">Baixa</h5>
                            <span class="description-text">Severidade</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-chart-bar"></i> Tipos Mais Comuns
                </h3>
            </div>
            <div class="card-body">
                @if(!empty($estatisticas['tipos_irregularidades']))
                    @foreach(array_slice($estatisticas['tipos_irregularidades'], 0, 3, true) as $tipo => $quantidade)
                        <div class="progress-group">
                            {{ $tipo }}
                            <span class="float-right"><b>{{ $quantidade }}</b>/{{ $totalIrregularidades }}</span>
                            <div class="progress progress-sm">
                                <div class="progress-bar bg-primary" 
                                     style="width: {{ ($quantidade / max($totalIrregularidades, 1)) * 100 }}%"></div>
                            </div>
                        </div>
                    @endforeach
                @endif
                
                <div class="mt-3">
                    <strong>Total de Divergência:</strong> 
                    <span class="text-danger">R$ {{ number_format($estatisticas['valor_total_divergencia'], 2, ',', '.') }}</span>
                </div>
            </div>
        </div>
    </div>
</div>

@if(isset($estatisticas['avs_por_status']) && !empty($estatisticas['avs_por_status']))
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-list-alt"></i> Distribuição por Status
                </h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    @php $cores = ['bg-info', 'bg-success', 'bg-warning', 'bg-danger']; $i = 0; @endphp
                    @foreach($estatisticas['avs_por_status'] as $status => $quantidade)
                    <div class="col-lg-6 col-md-12 mb-3">
                        <div class="info-box {{ $cores[$i % count($cores)] }}">
                            <span class="info-box-icon"><i class="fas fa-clipboard-list"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">{{ $status }}</span>
                                <span class="info-box-number">{{ $quantidade }} AV{{ $quantidade != 1 ? 's' : '' }}</span>
                                <div class="progress">
                                    <div class="progress-bar" style="width: {{ ($quantidade / max($totalAnalisadas, 1)) * 100 }}%"></div>
                                </div>
                                <span class="progress-description">
                                    {{ number_format(($quantidade / max($totalAnalisadas, 1)) * 100, 1) }}% do total
                                </span>
                            </div>
                        </div>
                    </div>
                    @php $i++; @endphp
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endif

@if(!empty($estatisticas['usuarios_com_irregularidades']))
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-user-times"></i> Usuários com Mais Irregularidades
                </h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Usuário</th>
                                <th>Quantidade de Irregularidades</th>
                                <th>% do Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach(array_slice($estatisticas['usuarios_com_irregularidades'], 0, 10, true) as $usuario => $quantidade)
                            <tr>
                                <td>{{ $usuario }}</td>
                                <td>
                                    <span class="badge badge-warning">{{ $quantidade }}</span>
                                </td>
                                <td>
                                    <div class="progress progress-xs">
                                        <div class="progress-bar progress-bar-warning" 
                                             style="width: {{ ($quantidade / max($totalIrregularidades, 1)) * 100 }}%"></div>
                                    </div>
                                    <span class="badge badge-secondary">
                                        {{ number_format(($quantidade / max($totalIrregularidades, 1)) * 100, 1) }}%
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
@endif

@if(count($irregularidades) > 0)
<!-- Filtros -->
<div class="card mb-3">
    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-filter"></i>
            Filtros
        </h3>
        <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                <i class="fas fa-minus"></i>
            </button>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-3">
                <label for="filtroSeveridade">Severidade:</label>
                <select id="filtroSeveridade" class="form-control">
                    <option value="">Todas</option>
                    <option value="alta">Alta</option>
                    <option value="media">Média</option>
                    <option value="baixa">Baixa</option>
                </select>
            </div>
            <div class="col-md-3">
                <label for="filtroTipo">Tipo de Irregularidade:</label>
                <select id="filtroTipo" class="form-control">
                    <option value="">Todos</option>
                    <option value="Divergência no cálculo original">Cálculo Original</option>
                    <option value="Divergência na prestação de contas">Prestação de Contas</option>
                    <option value="Alteração de valor não justificada">Alteração Não Justificada</option>
                    <option value="Valor zerado indevidamente">Valor Zerado</option>
                    <option value="Alteração indevida em valores extras">Valores Extras</option>
                </select>
            </div>
            <div class="col-md-3">
                <label for="filtroValorMinimo">Diferença Mínima (R$):</label>
                <input type="number" id="filtroValorMinimo" class="form-control" placeholder="0.00" step="0.01" min="0">
            </div>
            <div class="col-md-3">
                <label>&nbsp;</label><br>
                <button id="btnLimparFiltros" class="btn btn-secondary">
                    <i class="fas fa-eraser"></i> Limpar Filtros
                </button>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-exclamation-triangle text-warning"></i>
            Irregularidades Detectadas
        </h3>
        <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                <i class="fas fa-minus"></i>
            </button>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table id="tabelaIrregularidades" class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>AV</th>
                        <th>Usuário</th>
                        <th>Data</th>
                        <th>Status</th>
                        <th>Severidade</th>
                        <th>Tipos de Irregularidade</th>
                        <th>Maior Diferença</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($irregularidades as $irreg)
                    <tr>
                        <td>
                            <strong class="text-primary">#{{ $irreg['av_id'] }}</strong>
                        </td>
                        <td>{{ $irreg['usuario'] }}</td>
                        <td>{{ $irreg['data_criacao'] }}</td>
                        <td>
                            <span class="badge badge-info">{{ $irreg['status'] }}</span>
                        </td>
                        <td>
                            @php
                                $severidadeGeral = $irreg['severidade_geral'] ?? 'baixa';
                                $severidadeBadge = 'badge-info';
                                $severidadeTexto = 'Baixa';
                                switch($severidadeGeral) {
                                    case 'alta': 
                                        $severidadeBadge = 'badge-danger'; 
                                        $severidadeTexto = 'Alta';
                                        break;
                                    case 'media': 
                                        $severidadeBadge = 'badge-warning';
                                        $severidadeTexto = 'Média';
                                        break;
                                    case 'baixa': 
                                        $severidadeBadge = 'badge-info';
                                        $severidadeTexto = 'Baixa';
                                        break;
                                }
                            @endphp
                            <span class="badge {{ $severidadeBadge }}">{{ $severidadeTexto }}</span>
                        </td>
                        <td>
                            @foreach($irreg['irregularidades'] as $item)
                                @php
                                    $badgeClass = 'badge-warning';
                                    if(isset($item['severidade'])) {
                                        switch($item['severidade']) {
                                            case 'alta': $badgeClass = 'badge-danger'; break;
                                            case 'media': $badgeClass = 'badge-warning'; break;
                                            case 'baixa': $badgeClass = 'badge-info'; break;
                                        }
                                    }
                                @endphp
                                <span class="badge {{ $badgeClass }} mb-1" 
                                      title="{{ $item['descricao'] ?? '' }}">
                                    {{ $item['tipo'] }}
                                    @if(isset($item['moeda']) && $item['moeda'] == 'USD')
                                        <i class="fas fa-dollar-sign" title="Dólar"></i>
                                    @endif
                                </span><br>
                            @endforeach
                        </td>
                        <td>
                            @php
                                $maiorDiferenca = 0;
                                foreach($irreg['irregularidades'] as $item) {
                                    if($item['diferenca'] > $maiorDiferenca) {
                                        $maiorDiferenca = $item['diferenca'];
                                    }
                                }
                            @endphp
                            <strong class="text-danger">R$ {{ number_format($maiorDiferenca, 2, ',', '.') }}</strong>
                        </td>
                        <td>
                            <button class="btn btn-sm btn-info btn-analisar" data-av-id="{{ $irreg['av_id'] }}">
                                <i class="fas fa-eye"></i> Analisar
                            </button>
                            <a href="/avs/verDetalhesAvGerenciar/{{ $irreg['av_id'] }}" class="btn btn-sm btn-primary" target="_blank">
                                <i class="fas fa-external-link-alt"></i> Ver AV
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@else
<div class="card">
    <div class="card-body text-center">
        <div class="alert alert-success">
            <h4><i class="fas fa-check-circle"></i> Excelente!</h4>
            <p>Nenhuma irregularidade foi detectada no cálculo das diárias das últimas 100 AVs analisadas.</p>
        </div>
    </div>
</div>
@endif

<!-- Modal para análise detalhada -->
<div class="modal fade" id="modalAnalise" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">
                    <i class="fas fa-chart-bar"></i> Análise Detalhada - AV #<span id="modalAvId"></span>
                </h4>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body" id="modalAnaliseContent">
                <div class="text-center">
                    <i class="fas fa-spinner fa-spin fa-2x"></i>
                    <p>Carregando análise...</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>

@stop

@section('css')
    <link href="{{ asset('DataTables/datatables.min.css') }}" rel="stylesheet" />
    <style>
        .table td {
            vertical-align: middle;
        }
        .badge {
            font-size: 0.8em;
        }
        .card-body {
            max-height: 600px;
            overflow-y: auto;
        }
        .irregularidade-item {
            border-left: 4px solid #ffc107;
            background-color: #fff3cd;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 4px;
        }
        .valor-divergente {
            background-color: #f8d7da;
            padding: 2px 6px;
            border-radius: 3px;
            color: #721c24;
            font-weight: bold;
        }
        .valor-esperado {
            background-color: #d4edda;
            padding: 2px 6px;
            border-radius: 3px;
            color: #155724;
            font-weight: bold;
        }
    </style>
@stop

@section('js')
    <script src="{{ asset('DataTables/datatables.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            // Inicializar DataTable
            var table = $('#tabelaIrregularidades').DataTable({
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.10.25/i18n/Portuguese-Brasil.json"
                },
                "order": [[0, "desc"]],
                "pageLength": 25,
                "responsive": true
            });

            // Filtros customizados
            $('#filtroSeveridade, #filtroTipo, #filtroValorMinimo').on('change keyup', function() {
                aplicarFiltros();
            });

            $('#btnLimparFiltros').on('click', function() {
                $('#filtroSeveridade').val('');
                $('#filtroTipo').val('');
                $('#filtroValorMinimo').val('');
                table.search('').columns().search('').draw();
            });

            function aplicarFiltros() {
                var severidade = $('#filtroSeveridade').val();
                var tipo = $('#filtroTipo').val();
                var valorMinimo = parseFloat($('#filtroValorMinimo').val()) || 0;

                // Aplicar filtro personalizado
                $.fn.dataTable.ext.search.push(
                    function(settings, data, dataIndex) {
                        // Verificar severidade
                        if (severidade && !data[4].toLowerCase().includes(severidade.toLowerCase())) {
                            return false;
                        }

                        // Verificar tipo de irregularidade
                        if (tipo && !data[5].includes(tipo)) {
                            return false;
                        }

                        // Verificar valor mínimo
                        var valorTexto = data[6]; // Coluna "Maior Diferença"
                        var valor = parseFloat(valorTexto.replace(/[R$\s.,]/g, '').replace(',', '.')) || 0;
                        if (valorMinimo > 0 && valor < valorMinimo) {
                            return false;
                        }

                        return true;
                    }
                );

                table.draw();
                
                // Remover o filtro após aplicar para não acumular
                $.fn.dataTable.ext.search.pop();
            }

            // Botão de análise detalhada
            $(document).on('click', '.btn-analisar', function() {
                const avId = $(this).data('av-id');
                $('#modalAvId').text(avId);
                $('#modalAnalise').modal('show');
                
                // Carregar análise detalhada via AJAX
                $.ajax({
                    url: `/admin/analisar-av/${avId}`,
                    method: 'GET',
                    success: function(data) {
                        renderAnaliseDetalhada(data);
                    },
                    error: function() {
                        $('#modalAnaliseContent').html(`
                            <div class="alert alert-danger">
                                <i class="fas fa-exclamation-triangle"></i>
                                Erro ao carregar análise detalhada.
                            </div>
                        `);
                    }
                });
            });

            function renderAnaliseDetalhada(data) {
                let html = `
                    <div class="row">
                        <div class="col-md-6">
                            <h5><i class="fas fa-info-circle"></i> Informações Gerais</h5>
                            <table class="table table-sm">
                                <tr><td><strong>Usuário:</strong></td><td>${data.usuario}</td></tr>
                                <tr><td><strong>Data Criação:</strong></td><td>${data.data_criacao}</td></tr>
                                <tr><td><strong>Status:</strong></td><td><span class="badge badge-info">${data.status}</span></td></tr>
                                <tr><td><strong>Total de Dias:</strong></td><td>${data.total_dias}</td></tr>
                                <tr><td><strong>Total de Rotas:</strong></td><td>${data.total_rotas}</td></tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h5><i class="fas fa-calculator"></i> Resumo de Valores</h5>
                            <table class="table table-sm">
                                <tr>
                                    <td><strong>Calculado pelas Rotas:</strong></td>
                                    <td class="text-right"><span class="valor-esperado">R$ ${data.valores.calculado_reais.toLocaleString('pt-BR', {minimumFractionDigits: 2})}</span></td>
                                </tr>
                                <tr>
                                    <td><strong>Armazenado na AV (R$):</strong></td>
                                    <td class="text-right"><span class="valor-divergente">R$ ${data.valores.armazenado_reais.toLocaleString('pt-BR', {minimumFractionDigits: 2})}</span></td>
                                </tr>
                                <tr>
                                    <td><strong>Armazenado na AV (US$):</strong></td>
                                    <td class="text-right">US$ ${data.valores.armazenado_dolar.toLocaleString('pt-BR', {minimumFractionDigits: 2})}</td>
                                </tr>
                                <tr>
                                    <td><strong>Diferença em Reais:</strong></td>
                                    <td class="text-right"><span class="text-danger"><strong>R$ ${data.valores.diferenca_reais.toLocaleString('pt-BR', {minimumFractionDigits: 2})}</strong></span></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <h5><i class="fas fa-exclamation-triangle text-warning"></i> Irregularidades Detectadas</h5>
                `;

                data.irregularidades.forEach(function(irreg) {
                    html += `
                        <div class="irregularidade-item">
                            <h6><strong>${irreg.tipo}</strong></h6>
                            <p>${irreg.descricao}</p>
                            <div class="row">
                                <div class="col-md-4">
                                    <small><strong>Valor Esperado:</strong></small><br>
                                    <span class="valor-esperado">R$ ${irreg.valor_esperado.toLocaleString('pt-BR', {minimumFractionDigits: 2})}</span>
                                </div>
                                <div class="col-md-4">
                                    <small><strong>Valor Encontrado:</strong></small><br>
                                    <span class="valor-divergente">R$ ${irreg.valor_encontrado.toLocaleString('pt-BR', {minimumFractionDigits: 2})}</span>
                                </div>
                                <div class="col-md-4">
                                    <small><strong>Diferença:</strong></small><br>
                                    <span class="text-danger"><strong>R$ ${irreg.diferenca.toLocaleString('pt-BR', {minimumFractionDigits: 2})}</strong></span>
                                </div>
                            </div>
                        </div>
                    `;
                });

                html += `
                    <hr>
                    <h5><i class="fas fa-calendar-alt"></i> Detalhes dos Dias</h5>
                    <div class="table-responsive">
                        <table class="table table-sm table-striped">
                            <thead>
                                <tr>
                                    <th>Data</th>
                                    <th>Valor Calculado</th>
                                    <th>Cidades</th>
                                </tr>
                            </thead>
                            <tbody>
                `;

                data.detalhes_dias.forEach(function(dia) {
                    html += `
                        <tr>
                            <td>${dia.dia}</td>
                            <td>R$ ${dia.valor.toLocaleString('pt-BR', {minimumFractionDigits: 2})}</td>
                            <td>${dia.cidades ? dia.cidades.join(', ') : 'N/A'}</td>
                        </tr>
                    `;
                });

                html += `
                            </tbody>
                        </table>
                    </div>
                `;

                $('#modalAnaliseContent').html(html);
            }
        });
    </script>
@stop