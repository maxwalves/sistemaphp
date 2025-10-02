@extends('adminlte::page')

@section('title', 'Substituir Arquivo - Administração')

@section('content_header')
    <h1>Substituir Arquivo de AV</h1>
@stop

@section('content')

<div class="col-md-8 offset-md-2">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-exchange-alt"></i>
                Substituir Arquivo AVs
            </h3>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="alert alert-danger" role="alert">
                <i class="fas fa-exclamation-triangle"></i>
                <strong>Atenção!</strong> Esta funcionalidade substitui arquivos do sistema PERMANENTEMENTE. O arquivo original será removido e não poderá ser recuperado.
            </div>

            <!-- Navegador de Arquivos -->
            <div class="row mb-4">
                <div class="col-md-12">
                    <h5>
                        <i class="fas fa-folder-open"></i>
                        Navegador de Arquivos
                    </h5>
                    <div class="card">
                        <div class="card-header">
                            <div class="row align-items-center mb-2">
                                <div class="col">
                                    <button type="button" id="btnVoltar" class="btn btn-sm btn-outline-secondary" disabled>
                                        <i class="fas fa-arrow-left"></i> Voltar
                                    </button>
                                    <span id="caminhoAtual" class="ml-2 text-muted">Carregando...</span>
                                </div>
                                <div class="col-auto">
                                    <button type="button" id="btnAtualizar" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-sync-alt"></i> Atualizar
                                    </button>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="input-group input-group-sm">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <i class="fas fa-search"></i>
                                            </span>
                                        </div>
                                        <input type="text" id="buscaArquivo" class="form-control" placeholder="Buscar arquivos na pasta atual...">
                                        <div class="input-group-append">
                                            <button type="button" id="btnLimparBusca" class="btn btn-outline-secondary" style="display: none;">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <small class="text-muted">
                                        <span id="totalArquivos">0</span> item(s) - 
                                        <span id="resultadosBusca"></span>
                                    </small>
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <div id="loadingArquivos" class="text-center p-4">
                                <i class="fas fa-spinner fa-spin"></i> Carregando arquivos...
                            </div>
                            <div id="listaArquivos" style="max-height: 400px; overflow-y: auto;"></div>
                        </div>
                    </div>
                </div>
            </div>

            <form action="/admin/processar-substituicao-arquivo" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="form-group mb-3">
                    <label for="diretorio_arquivo" class="form-label">
                        <i class="fas fa-folder"></i>
                        Arquivo Selecionado:
                    </label>
                    <input 
                        type="text" 
                        class="form-control @error('diretorio_arquivo') is-invalid @enderror" 
                        id="diretorio_arquivo" 
                        name="diretorio_arquivo" 
                        placeholder="Selecione um arquivo usando o navegador acima ou digite o caminho completo"
                        value="{{ old('diretorio_arquivo') }}"
                        required
                        readonly
                    >
                    @error('diretorio_arquivo')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="form-text text-muted">
                        <i class="fas fa-info-circle"></i>
                        Use o navegador acima para selecionar o arquivo ou digite o caminho relativo à pasta /mnt/arquivos_viagem/
                    </small>
                    <button type="button" id="btnDigitarManual" class="btn btn-sm btn-link p-0 mt-1">
                        <i class="fas fa-keyboard"></i> Digitar caminho manualmente
                    </button>
                </div>

                <div class="form-group mb-3">
                    <label for="novo_arquivo" class="form-label">
                        <i class="fas fa-file-upload"></i>
                        Novo arquivo:
                    </label>
                    <input 
                        type="file" 
                        class="form-control @error('novo_arquivo') is-invalid @enderror" 
                        id="novo_arquivo" 
                        name="novo_arquivo" 
                        required
                    >
                    @error('novo_arquivo')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="form-text text-muted">
                        <i class="fas fa-info-circle"></i>
                        Selecione o arquivo que irá substituir o arquivo atual. Máximo: 10MB.
                    </small>
                </div>

                <div class="form-group">
                    <button type="submit" id="btnSubstituir" class="btn btn-danger" disabled onclick="return confirmarSubstituicao()">
                        <i class="fas fa-exchange-alt"></i> Substituir Arquivo (Permanente)
                    </button>
                    <a href="/admin" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Voltar ao Painel
                    </a>
                    <button type="button" id="btnLimpar" class="btn btn-outline-warning ml-2">
                        <i class="fas fa-eraser"></i> Limpar Seleção
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-header">
            <h5 class="card-title">
                <i class="fas fa-question-circle"></i>
                Como usar
            </h5>
        </div>
        <div class="card-body">
            <ol>
                <li>Copie o caminho completo do arquivo que deseja substituir</li>
                <li>Cole no campo "Diretório completo do arquivo"</li>
                <li>Selecione o novo arquivo</li>
                <li>Clique em "Substituir Arquivo"</li>
            </ol>
            
            <div class="alert alert-info mt-3">
                <strong><i class="fas fa-lightbulb"></i> Exemplo de caminho:</strong><br>
                <code>AVs/Joao Antenor Borges de Carvalho/1908/resumo/729892c1d61beda307e4dc66ccc7fe05.pdf</code>
                <br><small class="text-muted mt-1">
                    <i class="fas fa-info-circle"></i>
                    Caminho relativo à pasta base: /mnt/arquivos_viagem/
                </small>
            </div>

            <div class="alert alert-warning mt-3">
                <strong><i class="fas fa-exclamation-triangle"></i> Importante:</strong><br>
                <ul class="mb-0">
                    <li><strong>O arquivo original será REMOVIDO PERMANENTEMENTE</strong></li>
                    <li>Todas as operações são registradas em log</li>
                    <li>Certifique-se de ter um backup antes de prosseguir</li>
                    <li>Esta ação NÃO pode ser desfeita automaticamente</li>
                </ul>
            </div>
        </div>
    </div>
</div>

@stop

@section('css')
<style>
    .card {
        box-shadow: 0 0 10px rgba(0,0,0,0.1);
        border: none;
    }
    
    .form-label {
        font-weight: bold;
    }
    
    code {
        font-size: 0.9em;
        background-color: #f8f9fa;
        padding: 2px 4px;
        border-radius: 3px;
    }

    /* Estilos para o navegador de arquivos */
    #listaArquivos .table {
        margin-bottom: 0;
    }
    
    #listaArquivos .table td {
        vertical-align: middle;
        padding: 8px 12px;
    }
    
    #listaArquivos .table th {
        background-color: #f8f9fa;
        font-weight: 600;
        border-bottom: 2px solid #dee2e6;
    }
    
    .pasta-link {
        color: #007bff;
        text-decoration: none;
        cursor: pointer;
    }
    
    .pasta-link:hover {
        color: #0056b3;
        text-decoration: underline;
    }
    
    .table-hover tbody tr:hover {
        background-color: rgba(0,123,255,0.05);
    }
    
    .btn-group-sm > .btn, .btn-sm {
        font-size: 0.875rem;
        padding: 0.25rem 0.5rem;
    }
    
    #caminhoAtual {
        font-family: 'Courier New', monospace;
        font-size: 0.9em;
        background-color: #f8f9fa;
        padding: 2px 6px;
        border-radius: 3px;
        border: 1px solid #dee2e6;
    }
    
    .arquivo-selecionado {
        background-color: rgba(40, 167, 69, 0.1) !important;
        border-left: 3px solid #28a745;
    }
    
    /* Loading spinner personalizado */
    .fa-spin {
        animation: fa-spin 1s infinite linear;
    }
    
    /* Ícones de arquivo coloridos */
    .fas.fa-file-pdf { color: #dc3545; }
    .fas.fa-file-word { color: #007bff; }
    .fas.fa-file-excel { color: #28a745; }
    .fas.fa-file-powerpoint { color: #fd7e14; }
    .fas.fa-file-image { color: #6f42c1; }
    .fas.fa-file-archive { color: #ffc107; }
    .fas.fa-folder { color: #f39c12; }
</style>
@stop

@section('js')
<script>
    let caminhoAtual = '';
    let podeVoltar = false;

    $(document).ready(function() {
        // Auto-dismiss alerts after 5 seconds
        setTimeout(function() {
            $('.alert-success').fadeOut('slow');
        }, 5000);
        
        // File input validation
        $('#novo_arquivo').change(function() {
            const file = this.files[0];
            if (file) {
                const fileSize = file.size / 1024 / 1024; // MB
                if (fileSize > 10) {
                    alert('O arquivo deve ter no máximo 10MB.');
                    $(this).val('');
                }
            }
        });

        // Carregar arquivos iniciais
        carregarArquivos('');

        // Evento do botão voltar
        $('#btnVoltar').click(function() {
            if (podeVoltar) {
                const partesCarinho = caminhoAtual.split(/[\/\\]/);
                partesCarinho.pop();
                const novoCarinho = partesCarinho.join('/');
                carregarArquivos(novoCarinho);
            }
        });

        // Evento do botão atualizar
        $('#btnAtualizar').click(function() {
            carregarArquivos(caminhoAtual);
        });

        // Evento para digitar caminho manualmente
        $('#btnDigitarManual').click(function() {
            const campoInput = $('#diretorio_arquivo');
            if (campoInput.prop('readonly')) {
                campoInput.prop('readonly', false).focus();
                $(this).html('<i class="fas fa-folder-open"></i> Usar navegador');
            } else {
                campoInput.prop('readonly', true);
                $(this).html('<i class="fas fa-keyboard"></i> Digitar caminho manualmente');
            }
            verificarFormularioCompleto();
        });

        // Eventos para validação do formulário
        $('#diretorio_arquivo, #novo_arquivo').on('change keyup', function() {
            verificarFormularioCompleto();
        });

        // Evento para limpar seleção
        $('#btnLimpar').click(function() {
            $('#diretorio_arquivo').val('');
            $('#novo_arquivo').val('');
            $('.selecionar-arquivo').removeClass('btn-success').addClass('btn-outline-success');
            $('.arquivo-selecionado').removeClass('arquivo-selecionado');
            verificarFormularioCompleto();
            toastr.info('Seleção limpa');
        });

        // Eventos para busca de arquivos
        $('#buscaArquivo').on('keyup', function() {
            const termo = $(this).val();
            if (termo.length > 0) {
                const encontrados = buscarArquivo(termo);
                $('#resultadosBusca').text(encontrados + ' encontrado(s)');
                $('#btnLimparBusca').show();
            } else {
                limparBusca();
            }
        });

        $('#btnLimparBusca').click(function() {
            $('#buscaArquivo').val('');
            limparBusca();
        });
    });

    function carregarArquivos(caminho) {
        $('#loadingArquivos').show();
        $('#listaArquivos').hide();

        $.ajax({
            url: '/admin/navegar-arquivos',
            method: 'GET',
            data: { caminho: caminho },
            success: function(response) {
                caminhoAtual = response.caminhoAtual;
                podeVoltar = response.podeVoltar;
                
                $('#btnVoltar').prop('disabled', !podeVoltar);
                $('#caminhoAtual').text('/mnt/arquivos_viagem/' + (caminhoAtual || ''));
                
                montarListaArquivos(response.itens);
                
                $('#loadingArquivos').hide();
                $('#listaArquivos').show();
            },
            error: function(xhr) {
                let mensagem = 'Erro ao carregar arquivos';
                if (xhr.responseJSON && xhr.responseJSON.error) {
                    mensagem = xhr.responseJSON.error;
                }
                
                $('#loadingArquivos').html(
                    '<div class="alert alert-danger">' +
                    '<i class="fas fa-exclamation-triangle"></i> ' + mensagem +
                    '</div>'
                );
            }
        });
    }

    function montarListaArquivos(itens) {
        let html = '<table class="table table-hover table-sm mb-0">';
        html += '<thead><tr>';
        html += '<th><i class="fas fa-file"></i> Nome</th>';
        html += '<th><i class="fas fa-weight"></i> Tamanho</th>';
        html += '<th><i class="fas fa-clock"></i> Modificado</th>';
        html += '<th><i class="fas fa-cog"></i> Ações</th>';
        html += '</tr></thead><tbody>';

        if (itens.length === 0) {
            html += '<tr><td colspan="4" class="text-center text-muted p-4">';
            html += '<i class="fas fa-folder-open"></i> Esta pasta está vazia';
            html += '</td></tr>';
        }

        itens.forEach(function(item) {
            html += '<tr>';
            
            // Nome com ícone
            html += '<td>';
            if (item.tipo === 'pasta') {
                html += '<i class="fas fa-folder text-warning"></i> ';
                html += '<a href="#" class="pasta-link" data-caminho="' + item.caminho + '">';
                html += item.nome + '</a>';
            } else {
                const icone = getIconeArquivo(item.extensao);
                html += '<i class="' + icone.classe + '" style="color: ' + icone.cor + '"></i> ';
                html += item.nome;
            }
            html += '</td>';
            
            // Tamanho
            html += '<td>' + (item.tamanho || '-') + '</td>';
            
            // Data modificação
            html += '<td>' + item.modificado + '</td>';
            
            // Ações
            html += '<td>';
            if (item.tipo === 'pasta') {
                html += '<button type="button" class="btn btn-sm btn-outline-primary pasta-link" data-caminho="' + item.caminho + '">';
                html += '<i class="fas fa-folder-open"></i> Abrir';
                html += '</button>';
            } else {
                html += '<button type="button" class="btn btn-sm btn-success selecionar-arquivo" ';
                html += 'data-caminho="' + item.caminhoCompleto + '" data-nome="' + item.nome + '">';
                html += '<i class="fas fa-check"></i> Selecionar';
                html += '</button>';
            }
            html += '</td>';
            
            html += '</tr>';
        });

        html += '</tbody></table>';
        $('#listaArquivos').html(html);

        // Atualizar contadores
        atualizarContadores(itens);
        
        // Limpar busca anterior
        $('#buscaArquivo').val('');
        limparBusca();

        // Eventos para pastas
        $('.pasta-link').click(function(e) {
            e.preventDefault();
            const caminho = $(this).data('caminho');
            carregarArquivos(caminho);
        });

        // Eventos para selecionar arquivo
        $('.selecionar-arquivo').click(function() {
            const caminhoCompleto = $(this).data('caminho');
            const nome = $(this).data('nome');
            
            // Converter o caminho absoluto para relativo (remover /mnt/arquivos_viagem/)
            let caminhoRelativo = caminhoCompleto;
            if (caminhoCompleto.startsWith('/mnt/arquivos_viagem/')) {
                caminhoRelativo = caminhoCompleto.substring('/mnt/arquivos_viagem/'.length);
            }
            
            $('#diretorio_arquivo').val(caminhoRelativo);
            
            // Remover seleção anterior
            $('.selecionar-arquivo').removeClass('btn-success').addClass('btn-outline-success');
            $('.arquivo-selecionado').removeClass('arquivo-selecionado');
            
            // Destacar nova seleção
            $(this).removeClass('btn-outline-success').addClass('btn-success');
            $(this).closest('tr').addClass('arquivo-selecionado');
            
            // Verificar se o formulário está completo
            verificarFormularioCompleto();
            
            // Mostrar feedback
            toastr.success('Arquivo selecionado: ' + nome);
        });
    }

    function getIconeArquivo(extensao) {
        const icones = {
            'pdf': { classe: 'fas fa-file-pdf', cor: '#dc3545' },
            'doc': { classe: 'fas fa-file-word', cor: '#007bff' },
            'docx': { classe: 'fas fa-file-word', cor: '#007bff' },
            'xls': { classe: 'fas fa-file-excel', cor: '#28a745' },
            'xlsx': { classe: 'fas fa-file-excel', cor: '#28a745' },
            'ppt': { classe: 'fas fa-file-powerpoint', cor: '#fd7e14' },
            'pptx': { classe: 'fas fa-file-powerpoint', cor: '#fd7e14' },
            'jpg': { classe: 'fas fa-file-image', cor: '#6f42c1' },
            'jpeg': { classe: 'fas fa-file-image', cor: '#6f42c1' },
            'png': { classe: 'fas fa-file-image', cor: '#6f42c1' },
            'gif': { classe: 'fas fa-file-image', cor: '#6f42c1' },
            'txt': { classe: 'fas fa-file-alt', cor: '#6c757d' },
            'zip': { classe: 'fas fa-file-archive', cor: '#ffc107' },
            'rar': { classe: 'fas fa-file-archive', cor: '#ffc107' }
        };
        
        return icones[extensao] || { classe: 'fas fa-file', cor: '#6c757d' };
    }

    // Adicionar toastr se não existir
    if (typeof toastr === 'undefined') {
        window.toastr = {
            success: function(msg) { alert('✓ ' + msg); },
            error: function(msg) { alert('✗ ' + msg); },
            warning: function(msg) { alert('⚠ ' + msg); },
            info: function(msg) { alert('ℹ ' + msg); }
        };
    }

    function verificarFormularioCompleto() {
        const arquivoSelecionado = $('#diretorio_arquivo').val().trim();
        const novoArquivo = $('#novo_arquivo').val().trim();
        
        if (arquivoSelecionado && novoArquivo) {
            $('#btnSubstituir').prop('disabled', false).removeClass('btn-secondary').addClass('btn-danger');
        } else {
            $('#btnSubstituir').prop('disabled', true).removeClass('btn-danger').addClass('btn-secondary');
        }
    }

    function confirmarSubstituicao() {
        const arquivoSelecionado = $('#diretorio_arquivo').val();
        const nomeArquivo = arquivoSelecionado.split(/[\/\\]/).pop();
        
        return confirm(
            '⚠️ ATENÇÃO: SUBSTITUIÇÃO PERMANENTE ⚠️\n\n' +
            'Arquivo: ' + nomeArquivo + '\n' +
            'Caminho: ' + arquivoSelecionado + '\n\n' +
            '🚨 O arquivo original será REMOVIDO PERMANENTEMENTE!\n' +
            '❌ Esta ação NÃO pode ser desfeita automaticamente!\n' +
            '💾 Certifique-se de ter um backup se necessário!\n\n' +
            'Deseja realmente continuar com a substituição?'
        );
    }

    // Função para buscar arquivo por nome
    function buscarArquivo(termo) {
        const linhas = $('#listaArquivos tbody tr');
        let encontrados = 0;
        
        linhas.each(function() {
            const nomeArquivo = $(this).find('td:first').text().toLowerCase();
            if (nomeArquivo.includes(termo.toLowerCase())) {
                $(this).show();
                encontrados++;
            } else {
                $(this).hide();
            }
        });
        
        return encontrados;
    }

    function limparBusca() {
        $('#listaArquivos tbody tr').show();
        $('#btnLimparBusca').hide();
        $('#resultadosBusca').text('');
        const total = $('#listaArquivos tbody tr').length;
        $('#totalArquivos').text(total);
    }

    function atualizarContadores(itens) {
        const total = itens.length;
        const pastas = itens.filter(item => item.tipo === 'pasta').length;
        const arquivos = itens.filter(item => item.tipo === 'arquivo').length;
        
        $('#totalArquivos').text(total);
        $('#resultadosBusca').text(pastas + ' pasta(s), ' + arquivos + ' arquivo(s)');
    }
</script>
@stop