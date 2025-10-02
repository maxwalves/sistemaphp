@extends('adminlte::page')

@section('title', 'Monitoramento de Diárias')

@section('content_header')
    <h1>
        <i class="fas fa-chart-line"></i> Monitoramento de Diárias
        <small class="text-muted">Verificação de coerência nos cálculos</small>
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

@if(count($irregularidades) > 0)
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
                            @foreach($irreg['irregularidades'] as $item)
                                <span class="badge badge-warning mb-1">{{ $item['tipo'] }}</span><br>
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
            $('#tabelaIrregularidades').DataTable({
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.10.25/i18n/Portuguese-Brasil.json"
                },
                "order": [[0, "desc"]],
                "pageLength": 25,
                "responsive": true
            });

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
                                    <td><strong>Original Calculado:</strong></td>
                                    <td class="text-right">R$ ${data.valores.original_calculado.toLocaleString('pt-BR', {minimumFractionDigits: 2})}</td>
                                </tr>
                                <tr>
                                    <td><strong>Atual Calculado:</strong></td>
                                    <td class="text-right">R$ ${data.valores.atual_calculado.toLocaleString('pt-BR', {minimumFractionDigits: 2})}</td>
                                </tr>
                                <tr>
                                    <td><strong>Informado na PC:</strong></td>
                                    <td class="text-right">R$ ${data.valores.informado_pc.toLocaleString('pt-BR', {minimumFractionDigits: 2})}</td>
                                </tr>
                                <tr>
                                    <td><strong>Extra Original:</strong></td>
                                    <td class="text-right">R$ ${data.valores.extra_original.toLocaleString('pt-BR', {minimumFractionDigits: 2})}</td>
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