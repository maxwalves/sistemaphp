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

        <link href="{{asset('DataTables/datatables.min.css')}}" rel="stylesheet"/>
        <script src="{{asset('DataTables/datatables.min.js')}}"></script>

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
            <div class="row">
                <div class="col-5">
                    <select class="form-select" aria-label="Default select example" onChange="habilitarComponenteSelect()"
                    style="width: 100%" id="anoSelect">
                        <option selected>Selecione um ano</option>
                        @foreach($anos->sortBy('ano') as $ano)
                            <option value="{{$ano->id}}">{{$ano->ano}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        <br>
        <div class="container">
            <div class="row">
                <div class="col-5">
                    <select class="form-select" aria-label="Default select example" onChange="mostrarTabelaConformeSelecao()"
                    style="width: 100%" id="componenteSelect" disabled>
                        <option selected>Selecione um componente</option>
                        @foreach($componentes as $componente)
                            <option value="{{$componente->id}}">{{$componente->nome}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-5">
                    <select class="form-select" aria-label="Default select example" onChange="filtrarDadosTabela()"
                    style="width: 100%" id="subcomponenteSelect" disabled>
                        <option selected>Selecione um subcomponente</option>
                        @foreach($subcomponentes as $subcomponente)
                            <option value="{{$subcomponente->id}}">{{$subcomponente->nome}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
       
        <div id="comp1" class="container">
            <br>
            <br>
            <div id="divAba-escopo1">
				<table class="table table-striped table-hover table-bordered" id="tabelaPepPoaPmr">
					<thead>
						<tr>
							<th class="text-center" style="width: 10%"><small>Código PEP-POA</small></th>
                            <th class="text-center" style="width: 30%"><small>Nome PEP-POA</small></th>
                            <th class="text-center" style="width: 10%"><small>Código PMR</small></th>
                            <th class="text-center" style="width: 30%"><small>Nome PMR</small></th>
                            <th class="text-center" style="width: 5%"><small>Meta Física BID</small></th>
                            <th class="text-center" style="width: 5%"><small>Und medida BID</small></th>
                            <th class="text-center" style="width: 5%"><small>Meta Física PRCID</small></th>
                            <th class="text-center" style="width: 5%"><small>Und medida PRCID</small></th>
                            <th class="text-center" style="width: 5%"><small>Meta Fin BID</small></th>
                            <th class="text-center" style="width: 5%"><small>Meta Fin PRCID</small></th>
                            <th class="text-center" style="width: 20%"><small>Ações</small></th>
						</tr>
					</thead>
					<tbody>
                        <tr><td></td><td>Selecione um filtro</td><td></td><td>Selecione um filtro</td><td></td></tr>
					</tbody>
				</table>
			</div>
        </div>

        {{-- ---------------------------------------------------------------------------------- MODAL GERENCIAR REGISTROS --}}
        <div class="modal" tabindex="-1" role="dialog" id="dlgRegistros" data-backdrop="static">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <form class="form-horizontal" id="formRegistros">
                        <div class="modal-header">
                            <h5 class="modal-title">Novo Registro</h5>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" id="id" class="form-control">

                            <div class="row justify-content-md-center">
                                <div class="col col-lg-6">
                                    <input type="hidden" id="idAnoPepPoaPmr" class="form-control">

                                    <label for="meta_fisica_bid">Meta Física BID: </label>
                                    <input type="text" class="form-control" id="meta_fisica_bid" name="meta_fisica_bid" placeholder="Ex: 1">

                                    <label for="und_medida_bid">Unidade de Medida BID: </label>
                                    <input type="text" class="form-control" id="und_medida_bid" name="und_medida_bid" placeholder="Ex: Km">

                                    <label for="meta_fisica_prcid">Meta Física PRCID: </label>
                                    <input type="text" class="form-control" id="meta_fisica_prcid" name="meta_fisica_prcid" placeholder="Ex: 1">

                                    <label for="und_medida_prcid">Unidade de Medida PRCID: </label>
                                    <input type="text" class="form-control" id="und_medida_prcid" name="und_medida_prcid" placeholder="Ex: Km">

                                    <label for="meta_fin_bid">Meta Financeira BID: </label>
                                    <input type="text" class="form-control" id="meta_fin_bid" name="meta_fin_bid" placeholder="Ex: R$1.000.000,00">

                                    <label for="meta_fin_prcid">Meta Financeira PRCID: </label>
                                    <input type="text" class="form-control" id="meta_fin_prcid" name="meta_fin_prcid" placeholder="Ex: R$1.000.000,00">

                                    <input type="hidden" id="peppoa_pmr_id" name="peppoa_pmr_id">
                                </div>
                            </div>
                            
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Salvar</button>
                            <button type="reset" class="btn btn-secondary" onclick="$('#dlgRegistros').modal('hide')" >Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <br><br>
</body>
<script>

    $("#formRegistros").submit(function(event){
        event.preventDefault();

        //Verifica se está criando um novo ou editando
        if($("#idAnoPepPoaPmr").val() != ''){
            salvarRegistroAnoPepPoaPmr();
        }
        else{
            criarRegistroPepPoaPmr();
        }
        
        $("#dlgRegistros").modal('hide');
    });

    function salvarRegistroAnoPepPoaPmr(){
        
        anoPepPoaPmr = {
            id: $('#idAnoPepPoaPmr').val(),
            ano_id: $('#anoSelect').val(),
            metaFisicaBid: $('#meta_fisica_bid').val(),
            unidadeMedidaBid: $('#und_medida_bid').val(),
            metaFisicaPrcid: $('#meta_fisica_prcid').val(),
            unidadeMedidaPrcid: $('#und_medida_prcid').val(),
            metaFinanceiraBid: $('#meta_fin_bid').val(),
            metaFinanceiraPrcid: $('#meta_fin_prcid').val(),
            peppoa_pmr_id: $('#peppoa_pmr_id').val()
        };
        var idCatPepPoa = 0;
        var idCatPmr = 0;
        var codigoPepPoa = "";
        var codigoPmr = "";
        var pepPoaPmr = null;

        @for($i = 0; $i < count($pepPoaPmrTodos); $i++)
            if(anoPepPoaPmr.peppoa_pmr_id == {{$pepPoaPmrTodos[$i]->id}}){
                idCatPepPoa = "{{$pepPoaPmrTodos[$i]->categoriaPeppoa_id}}";
                idCatPmr = "{{$pepPoaPmrTodos[$i]->categoriaPmr_id}}";
            }
        @endfor

        @for($i = 0; $i < count($categoriasPeppoa); $i++)
            if(idCatPepPoa == {{$categoriasPeppoa[$i]->id}}){
                codigoPepPoa = "{{$categoriasPeppoa[$i]->codigo}}";
            }
        @endfor

        @for($i = 0; $i < count($categoriasPmr); $i++)
            if(idCatPmr == {{$categoriasPmr[$i]->id}}){
                codigoPmr = "{{$categoriasPmr[$i]->codigo}}";
            }
        @endfor

        $.getJSON('/api/pepPoaPmr/' + anoPepPoaPmr.peppoa_pmr_id, function(data){
            pepPoaPmr = data;

            $.ajax({
                type: "PUT",
                url: "/api/anoPepPoaPmr/" + anoPepPoaPmr.id,
                context: this,
                data: anoPepPoaPmr,
                success: function(data){
                    
                    linhas = $("#tabelaPepPoaPmr>tbody>tr");
                    e = linhas.filter(function(i, e) {
                        return (e.cells[0].textContent == codigoPepPoa && e.cells[2].textContent == codigoPmr);
                    });
                    
                    var novaLinha = montarLinha2(pepPoaPmr, anoPepPoaPmr);

                    $(novaLinha).insertBefore(e);
                    $(e).remove();

                    $('#idAnoPepPoaPmr').val("");
                    $('#meta_fisica_bid').val("");
                    $('#und_medida_bid').val("");
                    $('#meta_fisica_prcid').val("");
                    $('#und_medida_prcid').val("");
                    $('#meta_fin_bid').val("");
                    $('#meta_fin_prcid').val("");
                    $('#peppoa_pmr_id').val("");
                },
                error: function(error){
                    console.log(error);
                }
            });
        });
        
    }

    function criarRegistroPepPoaPmr(){
        anoPepPoaPmr = {
            ano_id: $('#anoSelect').val(),
            metaFisicaBid: $('#meta_fisica_bid').val(),
            unidadeMedidaBid: $('#und_medida_bid').val(),
            metaFisicaPrcid: $('#meta_fisica_prcid').val(),
            unidadeMedidaPrcid: $('#und_medida_prcid').val(),
            metaFinanceiraBid: $('#meta_fin_bid').val(),
            metaFinanceiraPrcid: $('#meta_fin_prcid').val(),
            peppoa_pmr_id: $('#peppoa_pmr_id').val(),
        };

        var idCatPepPoa = 0;
        var idCatPmr = 0;
        var codigoPepPoa = "";
        var codigoPmr = "";
        var codigoPmr = "";
        var pepPoaPmr = null;

        @for($i = 0; $i < count($pepPoaPmrTodos); $i++)
            if(anoPepPoaPmr.peppoa_pmr_id == {{$pepPoaPmrTodos[$i]->id}}){
                idCatPepPoa = "{{$pepPoaPmrTodos[$i]->categoriaPeppoa_id}}";
                idCatPmr = "{{$pepPoaPmrTodos[$i]->categoriaPmr_id}}";
            }
        @endfor

        @for($i = 0; $i < count($categoriasPeppoa); $i++)
            if(idCatPepPoa == {{$categoriasPeppoa[$i]->id}}){
                codigoPepPoa = "{{$categoriasPeppoa[$i]->codigo}}";
            }
        @endfor

        @for($i = 0; $i < count($categoriasPmr); $i++)
            if(idCatPmr == {{$categoriasPmr[$i]->id}}){
                codigoPmr = "{{$categoriasPmr[$i]->codigo}}";
            }
        @endfor

        $.getJSON('/api/pepPoaPmr/' + anoPepPoaPmr.peppoa_pmr_id, function(data){
            pepPoaPmr = data;

            $.post("/api/anoPepPoaPmr", anoPepPoaPmr, function(data){
                anoPepPoaPmr = JSON.parse(data);
                linhas = $("#tabelaPepPoaPmr>tbody>tr");
                e = linhas.filter(function(i, e) {
                    return (e.cells[0].textContent == codigoPepPoa && e.cells[2].textContent == codigoPmr);
                });
                
                var novaLinha = montarLinha2(pepPoaPmr, anoPepPoaPmr);

                $(novaLinha).insertBefore(e);
                $(e).remove();

                $('#idAnoPepPoaPmr').val("");
                $('#meta_fisica_bid').val("");
                $('#und_medida_bid').val("");
                $('#meta_fisica_prcid').val("");
                $('#und_medida_prcid').val("");
                $('#meta_fin_bid').val("");
                $('#meta_fin_prcid').val("");
                $('#peppoa_pmr_id').val("");
            });
        });
    }

    function carregarPepPoaPmr(){

        $.getJSON('/api/pepPoaPmr/', function(data){
            
            for(i=0; i<data.length; i++){
                montarLinha(data[i])
                .then(function(linha) {
                    $('#tabelaPepPoaPmr>tbody').append(linha);
                })
                .catch(function(error) {
                    console.log(error); // Tratar erros, se houver
                });
                $('#tabelaPepPoaPmr>tbody').append(linha);
            }
        });
    }

    async function obterDadosAnoPepPoaPmr() {
        try {
            var response = await fetch('/api/anoPepPoaPmr/');
            var data = await response.json();
            return data;
        } catch (error) {
            throw new Error('Erro ao obter os dados do anoPepPoaPmr: ' + error.message);
        }
    }

    async function montarLinha(a){
        try {
        var peppoaCodigo = 0;
        var peppoaNome = "";
        var pmrCodigo = 0;
        var pmrNome = "";

        var idAnoPepPoaPmr = 0;
        var ano_id = 0;
        var anoNome = "";
        var metaFisicaBid = 0;
        var unidadeMedidaBid = "";
        var metaFisicaPrcid = 0;
        var unidadeMedidaPrcid = "";
        var metaFinanceiraBid = 0;
        var metaFinanceiraPrcid = 0;

        @for($i = 0; $i < count($categoriasPeppoa); $i++)
            if(a.categoriaPeppoa_id == {{$categoriasPeppoa[$i]->id}}){
                peppoaCodigo = "{{$categoriasPeppoa[$i]->codigo}}";
                peppoaNome = "{{$categoriasPeppoa[$i]->nome}}";
            }
        @endfor

        @for($i = 0; $i < count($categoriasPmr); $i++)
            if(a.categoriaPmr_id == {{$categoriasPmr[$i]->id}}){
                pmrCodigo = "{{$categoriasPmr[$i]->codigo}}";
                pmrNome = "{{$categoriasPmr[$i]->nome}}";
            }
        @endfor

        var anoSelect = document.getElementById('anoSelect');
        
        var anoPepPoaPmrTodos = await obterDadosAnoPepPoaPmr();

        for(var i = 0; i < anoPepPoaPmrTodos.length; i++) {
            if(a.id == anoPepPoaPmrTodos[i].peppoa_pmr_id && anoSelect.value == anoPepPoaPmrTodos[i].ano_id){
                ano_id = anoPepPoaPmrTodos[i].ano_id;

                idAnoPepPoaPmr = anoPepPoaPmrTodos[i].id;
                metaFisicaBid = anoPepPoaPmrTodos[i].metaFisicaBid;
                unidadeMedidaBid = anoPepPoaPmrTodos[i].unidadeMedidaBid;
                metaFisicaPrcid = anoPepPoaPmrTodos[i].metaFisicaPrcid;
                unidadeMedidaPrcid = anoPepPoaPmrTodos[i].unidadeMedidaPrcid;
                metaFinanceiraBid = anoPepPoaPmrTodos[i].metaFinanceiraBid;
                metaFinanceiraPrcid = anoPepPoaPmrTodos[i].metaFinanceiraPrcid;
            }
        }

        @for($i = 0; $i < count($anos); $i++)
            if(ano_id == {{$anos[$i]->id}}){
                anoNome = "{{$anos[$i]->nome}}";
            }
        @endfor
        
        var linha = "<tr>" +
            "<td>" + peppoaCodigo + "</td>" +
            "<td>" + peppoaNome +"</td>" +
            "<td>" + pmrCodigo + "</td>" +
            "<td>" + pmrNome + "</td>" +
            "<td>" + metaFisicaBid + "</td>" +
            "<td>" + unidadeMedidaBid + "</td>" +
            "<td>" + metaFisicaPrcid + "</td>" +
            "<td>" + unidadeMedidaPrcid + "</td>" +
            "<td>" + metaFinanceiraBid + "</td>" +
            "<td>" + metaFinanceiraPrcid + "</td>" +
            "<td>" +
                '<button class="btn btn-xs btn-primary" onclick="editar('+ a.id + "," + idAnoPepPoaPmr +')"> Editar </button>' +
            "</td>" +
        "</tr>";
        return linha;
    } catch (error) {
        console.log(error);
        throw error;
    }
    }

    function montarLinha2(a, b){
        var peppoaCodigo = 0;
        var peppoaNome = "";
        var pmrCodigo = 0;
        var pmrNome = "";

        var idAnoPepPoaPmr = b.id;
        
        var ano_id = b.ano_id;
        var anoNome = "";
        var metaFisicaBid = b.metaFisicaBid;
        var unidadeMedidaBid = b.unidadeMedidaBid;
        var metaFisicaPrcid = b.metaFisicaPrcid;
        var unidadeMedidaPrcid = b.unidadeMedidaPrcid;
        var metaFinanceiraBid = b.metaFinanceiraBid;
        var metaFinanceiraPrcid = b.metaFinanceiraPrcid;

        @for($i = 0; $i < count($categoriasPeppoa); $i++)
            if(a.categoriaPeppoa_id == {{$categoriasPeppoa[$i]->id}}){
                peppoaCodigo = "{{$categoriasPeppoa[$i]->codigo}}";
                peppoaNome = "{{$categoriasPeppoa[$i]->nome}}";
            }
        @endfor

        @for($i = 0; $i < count($categoriasPmr); $i++)
            if(a.categoriaPmr_id == {{$categoriasPmr[$i]->id}}){
                pmrCodigo = "{{$categoriasPmr[$i]->codigo}}";
                pmrNome = "{{$categoriasPmr[$i]->nome}}";
            }
        @endfor

        @for($i = 0; $i < count($anos); $i++)
            if(ano_id == {{$anos[$i]->id}}){
                anoNome = "{{$anos[$i]->nome}}";
            }
        @endfor

        var linha = "<tr>" +
                "<td>" + peppoaCodigo + "</td>" +
                "<td>" + peppoaNome +"</td>" +
                "<td>" + pmrCodigo + "</td>" +
                "<td>" + pmrNome + "</td>" +
                "<td>" + metaFisicaBid + "</td>" +
                "<td>" + unidadeMedidaBid + "</td>" +
                "<td>" + metaFisicaPrcid + "</td>" +
                "<td>" + unidadeMedidaPrcid + "</td>" +
                "<td>" + metaFinanceiraBid + "</td>" +
                "<td>" + metaFinanceiraPrcid + "</td>" +
                "<td>" +
                    '<button class="btn btn-xs btn-primary" onclick="editar('+ a.id + "," + idAnoPepPoaPmr +')"> Editar </button>' +
                "</td>" +
            "</tr>";
        return linha;
    }

    function editar(idPepPoaPmr, id){
        if(id != 0){
            $.getJSON('/api/anoPepPoaPmr/' + id, function(data){
                
                    $('#idAnoPepPoaPmr').val(data.id);
                    $('#meta_fisica_bid').val(data.metaFisicaBid);
                    $('#und_medida_bid').val(data.unidadeMedidaBid);
                    $('#meta_fisica_prcid').val(data.metaFisicaPrcid);
                    $('#und_medida_prcid').val(data.unidadeMedidaPrcid);
                    $('#meta_fin_bid').val(data.metaFinanceiraBid);
                    $('#meta_fin_prcid').val(data.metaFinanceiraPrcid);
                    $('#peppoa_pmr_id').val(data.peppoa_pmr_id);

                    $('#dlgRegistros').modal('show');
                    
            });
        }
        else{
            $('#peppoa_pmr_id').val(idPepPoaPmr);
            $('#dlgRegistros').modal('show');
        }
    }

    function mostrarTabelaConformeSelecao() {
        var componenteSelect = document.getElementById('componenteSelect');
        var subcomponenteSelect = document.getElementById('subcomponenteSelect');
        var componenteId = componenteSelect.value;

        // Limpar as opções do segundo select
        subcomponenteSelect.innerHTML = '<option selected>Selecione um subcomponente</option>';
        subcomponenteSelect.disabled = true;

        if (componenteId) {
            // Fazer a solicitação AJAX para obter os subcomponentes do componente selecionado
            $.getJSON('/api/subcomponentes/', function(data){
            
            // Verificar se a resposta é um array
                if (Array.isArray(data)) {
                    data.forEach(function(subcomponente) {
                        if(componenteId == subcomponente.componente_id){
                            var option = document.createElement('option');
                            option.value = subcomponente.id;
                            option.text = subcomponente.nome;
                            subcomponenteSelect.appendChild(option);
                        }
                    });
                }
                // Habilitar o segundo select
                subcomponenteSelect.disabled = false;
            });   
        }
    }

    function filtrarDadosTabela(){
        var subcomponenteSelect = document.getElementById('subcomponenteSelect');
        var subcomponenteId = subcomponenteSelect.value;

        // Limpar a tabela existente
        $('#tabelaPepPoaPmr>tbody').empty();

        // Fazer a solicitação AJAX para obter os dados atualizados da tabela com base no subcomponente selecionado
        $.getJSON('/api/pepPoaPmr/', function(data){

            for (var i = 0; i < data.length; i++) {
                @foreach($categoriasPeppoa as $categoriaPeppoa)
                    if(data[i].categoriaPeppoa_id == {{$categoriaPeppoa->id}}){
                        if({{$categoriaPeppoa->subcomponente_id}} == subcomponenteId){
                            montarLinha(data[i])
                            .then(function(linha) {
                                $('#tabelaPepPoaPmr>tbody').append(linha);
                            })
                            .catch(function(error) {
                                console.log(error); // Tratar erros, se houver
                            });
                        }
                    }
                @endforeach
            }
        });
    }

    function habilitarComponenteSelect() {
        $('#tabelaPepPoaPmr>tbody').empty();
        $('#idAnoPepPoaPmr').val("");
        $('#meta_fisica_bid').val("");
        $('#und_medida_bid').val("");
        $('#meta_fisica_prcid').val("");
        $('#und_medida_prcid').val("");
        $('#meta_fin_bid').val("");
        $('#meta_fin_prcid').val("");
        $('#peppoa_pmr_id').val("");
        var anoSelect = document.getElementById('anoSelect');
        var componenteSelect = document.getElementById('componenteSelect');
        
        if (anoSelect.value !== 'Selecione um ano') {
            componenteSelect.disabled = false;
            componenteSelect.value = '';
            subcomponenteSelect.value = '';
        } else {
            componenteSelect.disabled = true;
            componenteSelect.value = '';
            subcomponenteSelect.disabled = true;
            subcomponenteSelect.value = '';
        }
    }

    $(function(){
        
    })

</script>

</html>


