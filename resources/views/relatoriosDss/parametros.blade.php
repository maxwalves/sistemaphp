<!DOCTYPE html>
<html lang="en">
<head>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>@yield('title')</title>

        <link rel="shortcut icon" type="imagex/png" href="{{asset('/img/aviao.png')}}">

        <style>
            @font-face {
              font-family: 'Roboto';
              src: url('{{ asset('Roboto/Roboto-Regular.ttf') }}') format('truetype');
            }
        </style>
        
        <!-- CSS Bootstrap -->
        <script src="{{asset('/js/bootstrap.bundle.min.js')}}"></script>
        <link href="{{asset('/css/bootstrap.min.css')}}" rel="stylesheet">

        <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

        <!-- CSS da aplicação -->

        
        <script src="{{asset('/js/scripts.js')}}"></script>
        <link href="{{asset('/css/headers.css')}}" rel="stylesheet">

        <link href="{{asset('/css/sidebars.css')}}" rel="stylesheet">
        <script src="{{asset('/js/sidebars.js')}}"></script>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.6.5/flowbite.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.6.5/datepicker.min.js"></script>

        <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
        <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
        <link rel="stylesheet" href="{{asset('/css/styles.css')}}">
        <script src="{{asset('/js/moment.js')}}"></script>
        <style>
                .teste {
                    min-height: 100vh;
                }
        </style>
            
    </head>
</head>
<body>
    <div class="teste" data-theme="emerald">
        <header class="p-3 mb-3 border-bottom">
            <div class="container">
                <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
                    <a href="/" class="d-flex align-items-center mb-2 mb-lg-0 text-dark text-decoration-none">
                    <img src="{{asset('/img/1.png')}}" alt="Paranacidade" width="100" height="72">
                    </a>

                    <ul class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0">
                    <p class="tituloSistema justify-content-center mb-md-0">Paraná Urbano III</p></li>
                    </ul>

                    <div class="dropdown text-end">
                        <a href="#" class="d-block link-dark text-decoration-none dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                            <img src="{{asset('/img/user.png')}}" alt="mdo" width="42" height="42" class="rounded-circle">
                            {{$user->name}}
                        </a>
                        <ul class="dropdown-menu text-small">

                            @auth
                                
                            @can('view-users', $user)
                                <li><a class="dropdown-item" href="/users/users">Gerenciar usuários</a></li>
                            @endcan

                                <li><hr class="dropdown-divider"></li>
                                
                                <li>
                                    <form action="/logout" method="POST">
                                        @csrf
                                        <a class="dropdown-item" href="/logout" 
                                        onclick="event.preventDefault();
                                        this.closest('form').submit();">
                                        Sair
                                        </a>
                                    </form>
                                </li>
                            @endauth
                            
                        </ul>
                    </div>
                            
                </div>
            </div>
        </header>
        <nav class="navbar navbar-expand-lg bg-light rounded border" aria-label="Eleventh navbar example">
            <div class="container">
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarsExample09" aria-controls="navbarsExample09" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarsExample09">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                        <a class="btn btn-active btn-warning rounded-none" href="/">Voltar Início</a>
                        </li>
                        <li class="nav-item">
                            <a class="btn btn-active btn-primary rounded-none" href="/relatoriosDss/paranaUrbanoIII/">Gerenciar programa</a>
                        </li>
                        <li class="nav-item">
                            <a class="btn btn-active btn-primary rounded-none" href="/relatoriosDss/controlePepPoaPmr/">Parâmetros do programa</a>
                        </li>
                        <li class="nav-item">
                            <a class="btn btn-active btn-primary rounded-none" href="/relatoriosDss/parametros/">Parâmetros do sistema</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        <br>
        
        <div class="container">
            <ul>
                    <a class="btn btn-active btn-info rounded"  onclick="mostrarTelaAnos()" >Gerenciar Anos vigentes PPU III</a>
                    <a class="btn btn-active btn-primary rounded"  onclick="mostrarTelaComponentesPPUIII()" >Gerenciar Componente PPU III</a>
                    <a class="btn btn-active btn-primary rounded"  onclick="mostrarTelaSubcomponentesPPUIII()">Gerenciar Subcomponente PPU III</a>
                    <a class="btn btn-active btn-primary rounded" onclick="mostrarTelaCategoriaPEPPOA()">Gerenciar Categoria PEPOA</a>
                    <a class="btn btn-active btn-secondary rounded" onclick="mostrarTelaCategoriaPMR()">Gerenciar Categoria PMR</a>
            </ul>
        </div>
        <br><br>
        <div class="container" id="telaAnos" style="display: none">
            <div class="card border">
                <div class="card-body">
                    <h5 class="card-title">Cadastro de Anos</h5>
                    
                    @if (count($anos) >0)
                        <table class="table table-ordered table-hover" id="tabelaAnos">
                            <thead>
                                <th>Ano</th>
                                <th>Ações</th>
                            </thead>
                            <tbody>
         
                            </tbody>
                        </table>
                    @else
                        <h5>Não existem anos cadastrados!</h5>
                    @endif
                    <div class="card-footer">
                        <a class="btn btn-sm btn-primary" 
                        role="button" onClick="novoAno()">Novo Ano</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="container" id="telaComponentesPPUIII" style="display: none">
            <div class="card border">
                <div class="card-body">
                    <h5 class="card-title">Cadastro de Componentes PPU III</h5>
                    
                    @if (count($componentes) >0)
                        <table class="table table-ordered table-hover" id="tabelaComponentesPPUIII">
                            <thead>
                                <th>Nome</th>
                                <th>Ações</th>
                            </thead>
                            <tbody>
         
                            </tbody>
                        </table>
                    @else
                        <h5>Não existem componentes cadastrados!</h5>
                    @endif
                    <div class="card-footer">
                        <a class="btn btn-sm btn-primary" 
                        role="button" onClick="novoComponente()">Novo Componente</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="container" id="telaSubcomponentesPPUIII" style="display: none">
            <div class="card border">
                <div class="card-body">
                    <h5 class="card-title">Cadastro de Subcomponente PPU III</h5>
                    
                    @if (count($subcomponentes) >0)
                        <table class="table table-ordered table-hover" id="tabelaSubcomponentesPPUIII">
                            <thead>
                                <th>Nome</th>
                                <th>Componente</th>
                                <th>Ações</th>
                            </thead>
                            <tbody>
         
                            </tbody>
                        </table>
                    @else
                        <h5>Não existem subcomponentes cadastrados!</h5>
                    @endif
                    <div class="card-footer">
                        <a class="btn btn-sm btn-primary" 
                        role="button" onClick="novoSubcomponente()">Novo Subcomponente</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="container" id="telaCategoriaPeppoa" style="display: none">
            <div class="card border">
                <div class="card-body">
                    <h5 class="card-title">Cadastro de Categorias PEPPOA</h5>
                    
                    @if (count($categoriasPeppoa) >0)
                        <table class="table table-ordered table-hover" id="tabelaCategoriaPeppoa">
                            <thead>
                                <th>Nome</th>
                                <th>Código</th>
                                <th>Subcomponente</th>
                                <th>Ações</th>
                            </thead>
                            <tbody>
         
                            </tbody>
                        </table>
                    @else
                        <h5>Não existem categorias PEPPOA cadastradas!</h5>
                    @endif
                    <div class="card-footer">
                        <a class="btn btn-sm btn-primary" 
                        role="button" onClick="novaCategoriaPeppoa()">Nova Categoria PEPPOA</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="container" id="telaCategoriaPmr" style="display: none">
            <div class="card border">
                <div class="card-body">
                    <h5 class="card-title">Cadastro de Categorias PMR</h5>
                    
                    @if (count($categoriasPeppoa) >0)
                        <table class="table table-ordered table-hover" id="tabelaCategoriaPmr">
                            <thead>
                                <th>Nome</th>
                                <th>Código</th>
                                <th>Ações</th>
                            </thead>
                            <tbody>
         
                            </tbody>
                        </table>
                    @else
                        <h5>Não existem categorias PMR cadastradas!</h5>
                    @endif
                    <div class="card-footer">
                        <a class="btn btn-sm btn-primary" 
                        role="button" onClick="novaCategoriaPmr()">Nova Categoria PMR</a>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
    {{-- ---------------------------------------------------------------------------------- MODAL GERENCIAR ANOS --}}
    <div class="modal" tabindex="-1" role="dialog" id="dlgAnos" data-backdrop="static">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form class="form-horizontal" id="formAno">
                    <div class="modal-header">
                        <h5 class="modal-title">Novo Ano</h5>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="id" class="form-control">

                        <div class="form-group">
                            <label for="ano" class="control-label">Ano</label>
                            <div class="input-group">
                                <input type="number" class="form-control" 
                                id="ano" placeholder="Ano">
                            </div>
                        </div>
                        
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Salvar</button>
                        <button type="reset" class="btn btn-secondary" onclick="$('#dlgAnos').modal('hide')" >Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    {{-- ---------------------------------------------------------------------------------- MODAL GERENCIAR COMPONENTES PPU III --}}
    <div class="modal" tabindex="-1" role="dialog" id="dlgComponentesPPUIII" data-backdrop="static">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form class="form-horizontal" id="formComponente">
                    <div class="modal-header">
                        <h5 class="modal-title">Novo Componente</h5>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="id" class="form-control">

                        <div class="form-group">
                            <label for="nome" class="control-label">Nome</label>
                            <div class="input-group">
                                <input type="text" class="form-control" 
                                id="nome" placeholder="Nome">
                            </div>
                        </div>
                        
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Salvar</button>
                        <button type="reset" class="btn btn-secondary" onclick="$('#dlgComponentesPPUIII').modal('hide')" >Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    {{-- ---------------------------------------------------------------------------------- MODAL GERENCIAR SUBCOMPONENTES PPU III --}}
    <div class="modal" tabindex="-1" role="dialog" id="dlgSubcomponentesPPUIII" data-backdrop="static">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form class="form-horizontal" id="formSubcomponente">
                    <div class="modal-header">
                        <h5 class="modal-title">Novo Subcomponente</h5>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="id" class="form-control">

                        <div class="form-group">
                            <label for="nomeSubcomponente" class="control-label">Nome</label>
                            <div class="input-group">
                                <input type="text" class="form-control" 
                                id="nomeSubcomponente" placeholder="Nome">
                            </div>
                        </div>

                        <select name="componente_id" id="componente_id">
                            @foreach ($componentes as $componente)
                                <option value="{{$componente->id}}">{{$componente->nome}}</option>
                            @endforeach
                        </select>
                        
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Salvar</button>
                        <button type="reset" class="btn btn-secondary" onclick="$('#dlgSubcomponentesPPUIII').modal('hide')" >Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    {{-- ---------------------------------------------------------------------------------- MODAL GERENCIAR CATEGORIA PEPPOA --}}
    <div class="modal" tabindex="-1" role="dialog" id="dlgCategoriaPeppoa" data-backdrop="static">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form class="form-horizontal" id="formCategoriaPeppoa">
                    <div class="modal-header">
                        <h5 class="modal-title">Nova Categoria PEPPOA</h5>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="id" class="form-control">

                        <div class="form-group">
                            <label for="nomeCategoriaPeppoa" class="control-label">Nome</label>
                            <div class="input-group">
                                <input type="text" class="form-control" 
                                id="nomeCategoriaPeppoa" placeholder="Nome">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="codigoCategoriaPeppoa" class="control-label">Código</label>
                            <div class="input-group">
                                <input type="text" class="form-control" 
                                id="codigoCategoriaPeppoa" placeholder="Código">
                            </div>
                        </div>

                        <select name="subcomponente_id" id="subcomponente_id">
                            @foreach ($subcomponentes as $subcomponente)
                                <option value="{{$subcomponente->id}}">{{$subcomponente->nome}}</option>
                            @endforeach
                        </select>
                        
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Salvar</button>
                        <button type="reset" class="btn btn-secondary" onclick="$('#dlgCategoriaPeppoa').modal('hide')" >Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ---------------------------------------------------------------------------------- MODAL GERENCIAR CATEGORIA PMR --}}
    <div class="modal" tabindex="-1" role="dialog" id="dlgCategoriaPmr" data-backdrop="static">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form class="form-horizontal" id="formCategoriaPmr">
                    <div class="modal-header">
                        <h5 class="modal-title">Nova Categoria PMR</h5>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="id" class="form-control">

                        <div class="form-group">
                            <label for="nomeCategoriaPmr" class="control-label">Nome</label>
                            <div class="input-group">
                                <input type="text" class="form-control" 
                                id="nomeCategoriaPmr" placeholder="Nome">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="codigoCategoriaPmr" class="control-label">Código</label>
                            <div class="input-group">
                                <input type="text" class="form-control" 
                                id="codigoCategoriaPmr" placeholder="Código">
                            </div>
                        </div>
                        
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Salvar</button>
                        <button type="reset" class="btn btn-secondary" onclick="$('#dlgCategoriaPmr').modal('hide')" >Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>


<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': "{{ csrf_token() }}"
        }
    });
// --------------------------------------------------------------------------- FUNÇÕES PARA GERENCIAR ANOS ----------------------------------
    function mostrarTelaAnos(){
        $("#telaAnos").show();
        $("#telaComponentesPPUIII").hide();
        $("#telaCategoriaPmr").hide();
        $("#telaCategoriaPeppoa").hide();
        $("#telaSubcomponentesPPUIII").hide();
    }

    function novoAno() {
        $('#id').val('');
        $('#ano').val('');
        $('#dlgAnos').modal('show')
    }

    $("#formAno").submit(function(event){
        event.preventDefault();

        //Verifica se está criando um novo ou editando
        if($("#id").val() != ''){
            salvarAno();
        }
        else{
            criarAno();
        }
        
        $("#dlgAnos").modal('hide');
    });

    function salvarAno(){
        
        ano = {
            id: $('#id').val(),
            ano: $('#ano').val(), 
        };

        $.ajax({
            type: "PUT",
            url: "/api/anos/" + ano.id,
            context: this,
            data: ano,
            success: function(data){
                $('#tabelaAnos>tbody').empty();
                carregarAnos();
                
            },
            error: function(error){
                console.log(error);
            }
        });
    }

    function criarAno(){
        ano = { 
            ano: $('#ano').val()
        };

        $.post("/api/anos", ano, function(data){
        $('#tabelaAnos>tbody').empty();
        carregarAnos();

        });
    }

    function carregarAnos(){

        $.getJSON('/api/anos/', function(data){
            data.sort(function(a, b) {
                return a.ano - b.ano;
            });
            for(i=0; i<data.length; i++){
                linha = montarLinha(data[i]);
                $('#tabelaAnos>tbody').append(linha);
            }
        });
    }

    function montarLinha(a){
        var linha = "<tr>" +
                "<td>" + a.ano + "</td>" +
                "<td>" +
                    '<button class="btn btn-xs btn-primary" onclick="editar('+ a.id +')"> Editar </button>' +
                    '<button class="btn btn-xs btn-danger" onclick="remover('+ a.id +')"> Apagar </button>' +
                "</td>" +
            "</tr>";
        return linha;
    }

    function editar(id){
        $.getJSON('/api/anos/' + id, function(data){
            $('#id').val(data.id);
            $('#ano').val(data.ano);
            $('#dlgAnos').modal('show')
        });
    }

    function remover(id) {
        $.ajax({
            type: "DELETE",
            url: "/api/anos/" + id,
            context: this,
            success: function(){
                console.log('Apagou OK');
                linhas = $("#tabelaAnos>tbody>tr");
                $('#tabelaAnos>tbody').empty();
                carregarAnos();
            },
            error: function(error){
                console.log(error);
            }
        });
    }

// --------------------------------------------------------------------------- FUNÇÕES PARA GERENCIAR COMPONENTES PPU III ----------------------------------

    function mostrarTelaComponentesPPUIII(){
        carregarComponentes();
        $("#telaAnos").hide();
        $("#telaSubcomponentesPPUIII").hide();
        $("#telaCategoriaPmr").hide();
        $("#telaCategoriaPeppoa").hide();
        $("#telaComponentesPPUIII").show();
    }

    function novoComponente() {
        $('#id').val('');
        $('#nome').val('');
        $('#dlgComponentesPPUIII').modal('show')
    }

    $("#formComponente").submit(function(event){
        event.preventDefault();

        //Verifica se está criando um novo ou editando
        if($("#id").val() != ''){
            salvarComponente();
        }
        else{
            criarComponente();
        }
        
        $("#dlgComponentesPPUIII").modal('hide');
    });

    function salvarComponente(){
        
        componente = {
            id: $('#id').val(),
            nome: $('#nome').val(), 
        };

        $.ajax({
            type: "PUT",
            url: "/api/componentes/" + componente.id,
            context: this,
            data: componente,
            success: function(data){
                $('#tabelaComponentesPPUIII>tbody').empty();
                carregarComponentes();
            },
            error: function(error){
                console.log(error);
            }
        });
    }

    function criarComponente(){
        componente = { 
            nome: $('#nome').val()
        };

        $.post("/api/componentes", componente, function(data){
        $('#tabelaComponentesPPUIII>tbody').empty();
        carregarComponentes();
        });
    }

    function carregarComponentes(){

        $.getJSON('/api/componentes/', function(data){
            data.sort(function(a, b) {
                return a.nome - b.nome;
            });
            for(i=0; i<data.length; i++){
                linha = montarLinhaComponente(data[i]);
                $('#tabelaComponentesPPUIII>tbody').append(linha);
            }
        });
    }

    function montarLinhaComponente(a){
        var linha = "<tr>" +
                "<td>" + a.nome + "</td>" +
                "<td>" +
                    '<button class="btn btn-xs btn-primary" onclick="editarComponente('+ a.id +')"> Editar </button>' +
                    '<button class="btn btn-xs btn-danger" onclick="removerComponente('+ a.id +')"> Apagar </button>' +
                "</td>" +
            "</tr>";
        return linha;
    }

    function editarComponente(id){
        $.getJSON('/api/componentes/' + id, function(data){
            $('#id').val(data.id);
            $('#nome').val(data.nome);
            $('#dlgComponentesPPUIII').modal('show')
        });
    }

    function removerComponente(id) {
        $.ajax({
            type: "DELETE",
            url: "/api/componentes/" + id,
            context: this,
            success: function(){
                console.log('Apagou OK');
                linhas = $("#tabelaComponentesPPUIII>tbody>tr");
                $('#tabelaComponentesPPUIII>tbody').empty();
                carregarComponentes();
            },
            error: function(error){
                console.log(error);
            }
        });
    }
// --------------------------------------------------------------------------- FUNÇÕES PARA GERENCIAR SUBCOMPONENTES PPU III ----------------------------------

    function mostrarTelaSubcomponentesPPUIII(){
        carregarSubcomponentes();
        $("#telaComponentesPPUIII").hide();
        $("#telaAnos").hide();
        $("#telaCategoriaPmr").hide();
        $("#telaCategoriaPeppoa").hide();
        $("#telaSubcomponentesPPUIII").show();
    }

    function novoSubcomponente() {
        $('#id').val('');
        $('#nome').val('');
        $('#componente_id').val('');
        $('#dlgSubcomponentesPPUIII').modal('show')
    }

    $("#formSubcomponente").submit(function(event){
        event.preventDefault();

        //Verifica se está criando um novo ou editando
        if($("#id").val() != ''){
            salvarSubcomponente();
        }
        else{
            criarSubcomponente();
        }
        
        $("#dlgSubcomponentesPPUIII").modal('hide');
    });

    function salvarSubcomponente(){
        
        subcomponente = {
            id: $('#id').val(),
            nome: $('#nomeSubcomponente').val(), 
            componente_id: $('#componente_id').val()
        };
        
        $.ajax({
            type: "PUT",
            url: "/api/subcomponentes/" + subcomponente.id,
            context: this,
            data: subcomponente,
            success: function(data){
                $('#tabelaSubcomponentesPPUIII>tbody').empty();
                carregarSubcomponentes();
                $('#nomeSubcomponente').val("");
                $('#componente_id').val("");
            },
            error: function(error){
                console.log(error);
            }
        });
    }

    function criarSubcomponente(){
        subcomponente = { 
            nome: $('#nomeSubcomponente').val(),
            componente_id: $('#componente_id').val()
        };
        $.post("/api/subcomponentes", subcomponente, function(data){
            $('#tabelaSubcomponentesPPUIII>tbody').empty();
            carregarSubcomponentes();
            $('#nomeSubcomponente').val("");
            $('#componente_id').val("");
        });
    }

    function carregarSubcomponentes(){

        $.getJSON('/api/subcomponentes/', function(data){
            data.sort(function(a, b) {
                return a.nome - b.nome;
            });
            for(i=0; i<data.length; i++){
                linha = montarLinhaSubcomponente(data[i]);
                $('#tabelaSubcomponentesPPUIII>tbody').append(linha);
            }
        });
    }

    function montarLinhaSubcomponente(a){
        var componente = "";

        @for($i = 0; $i < count($componentes); $i++)
            if(a.componente_id == {{$componentes[$i]->id}})
                var componente = "{{$componentes[$i]->nome}}";
        @endfor
        
        var linha = "<tr>" +
                "<td>" + a.nome + "</td>" +
                "<td>" + componente + "</td>" +
                "<td>" +
                    '<button class="btn btn-xs btn-primary" onclick="editarSubcomponente('+ a.id +')"> Editar </button>' +
                    '<button class="btn btn-xs btn-danger" onclick="removerSubcomponente('+ a.id +')"> Apagar </button>' +
                "</td>" +
            "</tr>";
        return linha;
    }

    function editarSubcomponente(id){
        $.getJSON('/api/subcomponentes/' + id, function(data){
            $('#id').val(data.id);
            $('#nomeSubcomponente').val(data.nome);
            $('#componente_id').val(data.componente_id);
            $('#dlgSubcomponentesPPUIII').modal('show')
        });
    }

    function removerSubcomponente(id) {
        $.ajax({
            type: "DELETE",
            url: "/api/subcomponentes/" + id,
            context: this,
            success: function(){
                console.log('Apagou OK');
                linhas = $("#tabelaSubcomponentesPPUIII>tbody>tr");
                $('#tabelaSubcomponentesPPUIII>tbody').empty();
                carregarSubcomponentes();
            },
            error: function(error){
                console.log(error);
            }
        });
    }

// --------------------------------------------------------------------------- FUNÇÕES PARA GERENCIAR CATEGORIA PEPPOA ----------------------------------

    function mostrarTelaCategoriaPEPPOA(){
        carregarCategoriaPeppoa();
        $("#telaComponentesPPUIII").hide();
        $("#telaAnos").hide();
        $("#telaSubcomponentesPPUIII").hide();
        $("#telaCategoriaPmr").hide();
        $("#telaCategoriaPeppoa").show();
    }

    function novaCategoriaPeppoa() {
        $('#id').val('');
        $('#nomeCategoriaPeppoa').val('');
        $('#codigoCategoriaPeppoa').val('');
        $('#dlgCategoriaPeppoa').modal('show')
    }

    $("#formCategoriaPeppoa").submit(function(event){
        event.preventDefault();

        //Verifica se está criando um novo ou editando
        if($("#id").val() != ''){
            salvarCategoriaPeppoa();
        }
        else{
            criarCategoriaPeppoa();
        }
        
        $("#dlgCategoriaPeppoa").modal('hide');
    });

    function salvarCategoriaPeppoa(){
        
        categoriaPeppoa = {
            id: $('#id').val(),
            nome: $('#nomeCategoriaPeppoa').val(), 
            codigo: $('#codigoCategoriaPeppoa').val(),
            subcomponente_id: $('#subcomponente_id').val()
        };
        
        $.ajax({
            type: "PUT",
            url: "/api/categoriasPeppoa/" + categoriaPeppoa.id,
            context: this,
            data: categoriaPeppoa,
            success: function(data){
                $('#tabelaCategoriaPeppoa>tbody').empty();
                carregarCategoriaPeppoa();
                $('#nomeCategoriaPeppoa').val("");
                $('#codigoCategoriaPeppoa').val("");
                $('#subcomponente_id').val("");
            },
            error: function(error){
                console.log(error);
            }
        });
    }

    function criarCategoriaPeppoa(){
        categoriaPeppoa = { 
            nome: $('#nomeCategoriaPeppoa').val(),
            codigo: $('#codigoCategoriaPeppoa').val(),
            subcomponente_id: $('#subcomponente_id').val()
        };
        console.log(categoriaPeppoa);
        console.log(categoriaPeppoa);
        $.post("/api/categoriasPeppoa", categoriaPeppoa, function(data){
            $('#tabelaCategoriaPeppoa>tbody').empty();
            carregarCategoriaPeppoa();
            $('#nomeCategoriaPeppoa').val("");
            $('#codigoCategoriaPeppoa').val("");
            $('#subcomponente_id').val("");
        });
    }

    function carregarCategoriaPeppoa(){

        $.getJSON('/api/categoriasPeppoa/', function(data){
            data.sort(function(a, b) {
                return a.nome - b.nome;
            });
            console.log(data);
            for(i=0; i<data.length; i++){
                linha = montarLinhaCategoriaPeppoa(data[i]);
                $('#tabelaCategoriaPeppoa>tbody').append(linha);
            }
        });
    }

    function montarLinhaCategoriaPeppoa(a){
        var componente = "";
        @for($i = 0; $i < count($subcomponentes); $i++)
            if(a.subcomponente_id == {{$subcomponentes[$i]->id}})
                componente = "{{$subcomponentes[$i]->nome}}";
        @endfor

        var linha = "<tr>" +
                "<td>" + a.nome + "</td>" +
                "<td>" + a.codigo + "</td>" +
                "<td>" + componente + "</td>" +
                "<td>" +
                    '<button class="btn btn-xs btn-primary" onclick="editarCategoriaPeppoa('+ a.id +')"> Editar </button>' +
                    '<button class="btn btn-xs btn-danger" onclick="removerCategoriaPeppoa('+ a.id +')"> Apagar </button>' +
                "</td>" +
            "</tr>";
        return linha;
    }

    function editarCategoriaPeppoa(id){
        $.getJSON('/api/categoriasPeppoa/' + id, function(data){
            $('#id').val(data.id);
            $('#nomeCategoriaPeppoa').val(data.nome);
            $('#codigoCategoriaPeppoa').val(data.codigo);
            $('#subcomponente_id').val(data.subcomponente_id);
            $('#dlgCategoriaPeppoa').modal('show')
        });
    }

    function removerCategoriaPeppoa(id) {
        $.ajax({
            type: "DELETE",
            url: "/api/categoriasPeppoa/" + id,
            context: this,
            success: function(){
                console.log('Apagou OK');
                linhas = $("#tabelaCategoriaPeppoa>tbody>tr");
                $('#tabelaCategoriaPeppoa>tbody').empty();
                carregarCategoriaPeppoa();
            },
            error: function(error){
                console.log(error);
            }
        });
    }

// --------------------------------------------------------------------------- FUNÇÕES PARA GERENCIAR CATEGORIA PMR ----------------------------------

    function mostrarTelaCategoriaPMR(){
        carregarCategoriaPmr();
        $("#telaComponentesPPUIII").hide();
        $("#telaAnos").hide();
        $("#telaSubcomponentesPPUIII").hide();
        $("#telaCategoriaPeppoa").hide();
        $("#telaCategoriaPmr").show();
    }

    function novaCategoriaPmr() {
        $('#id').val('');
        $('#nomeCategoriaPmr').val('');
        $('#codigoCategoriaPmr').val('');
        $('#dlgCategoriaPmr').modal('show')
    }

    $("#formCategoriaPmr").submit(function(event){
        event.preventDefault();

        //Verifica se está criando um novo ou editando
        if($("#id").val() != ''){
            salvarCategoriaPmr();
        }
        else{
            criarCategoriaPmr();
        }
        
        $("#dlgCategoriaPmr").modal('hide');
    });

    function salvarCategoriaPmr(){
        
        categoriaPmr = {
            id: $('#id').val(),
            nome: $('#nomeCategoriaPmr').val(), 
            codigo: $('#codigoCategoriaPmr').val()
        };
        
        $.ajax({
            type: "PUT",
            url: "/api/categoriasPmr/" + categoriaPmr.id,
            context: this,
            data: categoriaPmr,
            success: function(data){
                $('#tabelaCategoriaPmr>tbody').empty();
                carregarCategoriaPmr();
                $('#nomeCategoriaPmr').val("");
                $('#codigoCategoriaPmr').val("");
            },
            error: function(error){
                console.log(error);
            }
        });
    }

    function criarCategoriaPmr(){
        categoriaPmr = { 
            nome: $('#nomeCategoriaPmr').val(),
            codigo: $('#codigoCategoriaPmr').val()
        };
        $.post("/api/categoriasPmr", categoriaPmr, function(data){
            $('#tabelaCategoriaPmr>tbody').empty();
            carregarCategoriaPmr();
            $('#nomeCategoriaPmr').val("");
            $('#codigoCategoriaPmr').val("");
        });
    }

    function carregarCategoriaPmr(){

        $.getJSON('/api/categoriasPmr/', function(data){
            data.sort(function(a, b) {
                return a.nome - b.nome;
            });
            for(i=0; i<data.length; i++){
                linha = montarLinhaCategoriaPmr(data[i]);
                $('#tabelaCategoriaPmr>tbody').append(linha);
            }
        });
    }

    function montarLinhaCategoriaPmr(a){
        var linha = "<tr>" +
                "<td>" + a.nome + "</td>" +
                "<td>" + a.codigo + "</td>" +
                "<td>" +
                    '<button class="btn btn-xs btn-primary" onclick="editarCategoriaPmr('+ a.id +')"> Editar </button>' +
                    '<button class="btn btn-xs btn-danger" onclick="removerCategoriaPmr('+ a.id +')"> Apagar </button>' +
                "</td>" +
            "</tr>";
        return linha;
    }

    function editarCategoriaPmr(id){
        $.getJSON('/api/categoriasPmr/' + id, function(data){
            $('#id').val(data.id);
            $('#nomeCategoriaPmr').val(data.nome);
            $('#codigoCategoriaPmr').val(data.codigo);
            $('#dlgCategoriaPmr').modal('show')
        });
    }

    function removerCategoriaPmr(id) {
        $.ajax({
            type: "DELETE",
            url: "/api/categoriasPmr/" + id,
            context: this,
            success: function(){
                console.log('Apagou OK');
                linhas = $("#tabelaCategoriaPmr>tbody>tr");
                $('#tabelaCategoriaPmr>tbody').empty();
                carregarCategoriaPmr();
            },
            error: function(error){
                console.log(error);
            }
        });
    }

    $(function(){
        carregarAnos();
    })
</script>

</html>


