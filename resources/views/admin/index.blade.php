@extends('adminlte::page')

@section('title', 'Painel de Administração')

@section('content_header')
    <div class="row">
        <div class="col-sm-6">
            <h1>
                <i class="fas fa-cogs"></i>
                Painel de Administração
            </h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item active">Administração</li>
            </ol>
        </div>
    </div>
@stop

@section('content')

<div class="row">
    <div class="col-12">
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
    </div>
</div>

<!-- Informações do Usuário -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-user-shield"></i>
                    Informações do Administrador
                </h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <strong><i class="fas fa-user"></i> Nome:</strong> {{ $user->name }}<br>
                        <strong><i class="fas fa-envelope"></i> Email:</strong> {{ $user->username }}<br>
                    </div>
                    <div class="col-md-6">
                        <strong><i class="fas fa-building"></i> Departamento:</strong> {{ $user->department ?? 'N/A' }}<br>
                        <strong><i class="fas fa-clock"></i> Último Acesso:</strong> {{ now()->format('d/m/Y H:i') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Ferramentas de Administração -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-tools"></i>
                    Ferramentas de Administração
                </h3>
            </div>
            <div class="card-body">
                <div class="row">
                    
                    <!-- Gestão de Arquivos -->
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="card h-100 border-left-primary">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                            Gestão de Arquivos
                                        </div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                                            <i class="fas fa-file-alt fa-2x text-primary"></i>
                                        </div>
                                        <p class="text-muted small mt-2">
                                            Substitua arquivos AVs de forma segura e controlada.
                                        </p>
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <a href="/admin/substituir-arquivo" class="btn btn-primary btn-block">
                                        <i class="fas fa-exchange-alt"></i>
                                        Substituir Arquivos
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Gestão de Usuários -->
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="card h-100 border-left-success">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                            Gestão de Usuários
                                        </div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                                            <i class="fas fa-users fa-2x text-success"></i>
                                        </div>
                                        <p class="text-muted small mt-2">
                                            Gerencie usuários, permissões e perfis do sistema.
                                        </p>
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <a href="/users/users" class="btn btn-success btn-block">
                                        <i class="fas fa-users-cog"></i>
                                        Gerenciar Usuários
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Relatórios e Logs -->
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="card h-100 border-left-info">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                            Relatórios & Logs
                                        </div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                                            <i class="fas fa-chart-bar fa-2x text-info"></i>
                                        </div>
                                        <p class="text-muted small mt-2">
                                            Visualize relatórios e logs do sistema.
                                        </p>
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <button class="btn btn-info btn-block" disabled>
                                        <i class="fas fa-chart-line"></i>
                                        Em Desenvolvimento
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Configurações do Sistema -->
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="card h-100 border-left-warning">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                            Configurações
                                        </div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                                            <i class="fas fa-cog fa-2x text-warning"></i>
                                        </div>
                                        <p class="text-muted small mt-2">
                                            Configure parâmetros gerais do sistema.
                                        </p>
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <button class="btn btn-warning btn-block" disabled>
                                        <i class="fas fa-sliders-h"></i>
                                        Em Desenvolvimento
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Backup e Manutenção -->
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="card h-100 border-left-secondary">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-secondary text-uppercase mb-1">
                                            Backup & Manutenção
                                        </div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                                            <i class="fas fa-database fa-2x text-secondary"></i>
                                        </div>
                                        <p class="text-muted small mt-2">
                                            Ferramentas de backup e manutenção do sistema.
                                        </p>
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <button class="btn btn-secondary btn-block" disabled>
                                        <i class="fas fa-tools"></i>
                                        Em Desenvolvimento
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Monitoramento -->
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="card h-100 border-left-danger">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                            Monitoramento
                                        </div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                                            <i class="fas fa-heartbeat fa-2x text-danger"></i>
                                        </div>
                                        <p class="text-muted small mt-2">
                                            Monitor do sistema e estatísticas de uso.
                                        </p>
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <a href="/admin/monitoramento-diarias" class="btn btn-danger btn-block">
                                        <i class="fas fa-chart-line"></i>
                                        Monitoramento de Diárias
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<!-- Estatísticas Rápidas -->
<div class="row">
    <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3 id="totalUsuarios">-</h3>
                <p>Total de Usuários</p>
            </div>
            <div class="icon">
                <i class="fas fa-users"></i>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3 id="usuariosAtivos">-</h3>
                <p>Usuários Ativos Hoje</p>
            </div>
            <div class="icon">
                <i class="fas fa-user-check"></i>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3 id="avsAbertas">-</h3>
                <p>AVs em Andamento</p>
            </div>
            <div class="icon">
                <i class="fas fa-file-alt"></i>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-6">
        <div class="small-box bg-danger">
            <div class="inner">
                <h3 id="sistemaStatus">OK</h3>
                <p>Status do Sistema</p>
            </div>
            <div class="icon">
                <i class="fas fa-heartbeat"></i>
            </div>
        </div>
    </div>
</div>

@stop

@section('css')
<style>
    .border-left-primary {
        border-left: 0.25rem solid #007bff !important;
    }
    
    .border-left-success {
        border-left: 0.25rem solid #28a745 !important;
    }
    
    .border-left-info {
        border-left: 0.25rem solid #17a2b8 !important;
    }
    
    .border-left-warning {
        border-left: 0.25rem solid #ffc107 !important;
    }
    
    .border-left-secondary {
        border-left: 0.25rem solid #6c757d !important;
    }
    
    .border-left-danger {
        border-left: 0.25rem solid #dc3545 !important;
    }
    
    .card.h-100 {
        height: 100% !important;
    }
    
    .text-xs {
        font-size: 0.75rem;
    }
    
    .small-box .inner h3 {
        font-size: 2.2rem;
        font-weight: bold;
        margin: 0 0 10px 0;
        white-space: nowrap;
        padding: 0;
    }
    
    .card-body {
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }
    
    .alert-dismissible .btn-close {
        position: absolute;
        top: 0;
        right: 0;
        z-index: 2;
        padding: 0.75rem 1.25rem;
        color: inherit;
    }
</style>
@stop

@section('js')
<script>
    $(document).ready(function() {
        // Auto-dismiss alerts after 5 seconds
        setTimeout(function() {
            $('.alert-success, .alert-info').fadeOut('slow');
        }, 5000);
        
        // Carregar estatísticas (simulado por enquanto)
        carregarEstatisticas();
    });
    
    function carregarEstatisticas() {
        // Por enquanto, valores simulados
        // No futuro, fazer chamadas AJAX para buscar dados reais
        $('#totalUsuarios').text('{{ \App\Models\User::count() ?? "N/A" }}');
        $('#usuariosAtivos').text(Math.floor(Math.random() * 50) + 10);
        $('#avsAbertas').text(Math.floor(Math.random() * 100) + 20);
        $('#sistemaStatus').text('Online');
    }
</script>
@stop