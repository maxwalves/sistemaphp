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
                    <select class="form-select" aria-label="Default select example" onChange="mostrarTabelaConformeSelecao()"
                    style="width: 100%" id="componenteSelect">
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
                            <th class="text-center" style="width: 20%"><small>Ações</small></th>
						</tr>
					</thead>
					<tbody>
                        <tr><td></td><td>Selecione um filtro</td><td></td><td>Selecione um filtro</td><td></td></tr>
					</tbody>
				</table>
                <div class="card-footer">
                    <a class="btn btn-sm btn-primary" 
                    role="button" onClick="novoRegistro()">Novo Registro</a>
                </div>
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

                            <label for="peppoa_id">Categoria PEPPOA: </label>
                            <select name="peppoa_id" id="peppoa_id">
                                @foreach ($categoriasPeppoa as $categoriaPeppoa)
                                    <option value="{{$categoriaPeppoa->id}}">{{$categoriaPeppoa->codigo . " - " . $categoriaPeppoa->nome}}</option>
                                @endforeach
                            </select>

                            <label for="pmr_id">Categoria PMR: </label>
                            <select name="pmr_id" id="pmr_id">
                                @foreach ($categoriasPmr as $categoriaPmr)
                                    <option value="{{$categoriaPmr->id}}">{{$categoriaPmr->codigo . " - " . $categoriaPmr->nome}}</option>
                                @endforeach
                            </select>

                            <input type="text" name="peppoa_idTemp" id="peppoa_idTemp" hidden>
                            <input type="text" name="pmr_idTemp" id="pmr_idTemp" hidden>
                            
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

    function novoRegistro() {
        $('#peppoa_id').val('');
        $('#pmr_id').val('');
        $('#dlgRegistros').modal('show')
    }

    $("#formRegistros").submit(function(event){
        event.preventDefault();

        //Verifica se está criando um novo ou editando
        if($("#id").val() != ''){
            salvarRegistro();
        }
        else{
            criarRegistro();
        }
        
        $("#dlgRegistros").modal('hide');
    });

    function salvarRegistro(){
        
        pepPoaPmr = {
            id: $('#id').val(),
            categoriaPeppoa_id: $('#peppoa_id').val(),
            categoriaPmr_id: $('#pmr_id').val(), 
        };

        var categoriaPeppoa_idTemp = $('#peppoa_idTemp').val();
        var categoriaPmr_idTemp = $('#pmr_idTemp').val();
        var codigoPepPoa = "";
        var codigoPmr = "";

        @for($i = 0; $i < count($categoriasPeppoa); $i++)
            if(categoriaPeppoa_idTemp == {{$categoriasPeppoa[$i]->id}}){
                codigoPepPoa = "{{$categoriasPeppoa[$i]->codigo}}";
            }
        @endfor

        @for($i = 0; $i < count($categoriasPmr); $i++)
            if(categoriaPmr_idTemp == {{$categoriasPmr[$i]->id}}){
                codigoPmr = "{{$categoriasPmr[$i]->codigo}}";
            }
        @endfor

        $.ajax({
            type: "PUT",
            url: "/api/pepPoaPmr/" + pepPoaPmr.id,
            context: this,
            data: pepPoaPmr,
            success: function(data){
                
                linhas = $("#tabelaPepPoaPmr>tbody>tr");
                e = linhas.filter(function(i, e) {
                    return (e.cells[0].textContent == codigoPepPoa && e.cells[2].textContent == codigoPmr);
                });
                var novaLinha = montarLinha(pepPoaPmr);
                $(novaLinha).insertBefore(e);
                $(e).remove();
            },
            error: function(error){
                console.log(error);
            }
        });
    }

    function criarRegistro(){
        pepPoaPmr = { 
            categoriaPeppoa_id: $('#peppoa_id').val(),
            categoriaPmr_id: $('#pmr_id').val(), 
        };

        $.post("/api/pepPoaPmr", pepPoaPmr, function(data){
            filtrarDadosTabela();

        });
    }

    function carregarPepPoaPmr(){

        $.getJSON('/api/pepPoaPmr/', function(data){
            
            for(i=0; i<data.length; i++){
                linha = montarLinha(data[i]);
                $('#tabelaPepPoaPmr>tbody').append(linha);
            }
        });
    }

    function montarLinha(a){
        var peppoaCodigo = 0;
        var peppoaNome = "";
        var pmrCodigo = 0;
        var pmrNome = "";

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

        var linha = "<tr>" +
                "<td>" + peppoaCodigo + "</td>" +
                "<td>" + peppoaNome +"</td>" +
                "<td>" + pmrCodigo + "</td>" +
                "<td>" + pmrNome + "</td>" +
                "<td>" +
                    '<button class="btn btn-xs btn-primary" onclick="editar('+ a.id +')"> Editar </button>' +
                    '<button class="btn btn-xs btn-danger" onclick="remover('+ a.id +')"> Apagar </button>' +
                "</td>" +
            "</tr>";
        return linha;
    }

    function editar(id){
        $.getJSON('/api/pepPoaPmr/' + id, function(data){
            $('#id').val(data.id);
            $('#peppoa_id').val(data.categoriaPeppoa_id);
            $('#pmr_id').val(data.categoriaPmr_id);

            $('#peppoa_idTemp').val(data.categoriaPeppoa_id);
            $('#pmr_idTemp').val(data.categoriaPmr_id);
            $('#dlgRegistros').modal('show')
        });
    }

    function remover(id) {
        $.ajax({
            type: "DELETE",
            url: "/api/pepPoaPmr/" + id,
            context: this,
            success: function(){
                console.log('Apagou OK');
                filtrarDadosTabela();
            },
            error: function(error){
                console.log(error);
            }
        });
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
                            var linha = montarLinha(data[i]);
                            $('#tabelaPepPoaPmr>tbody').append(linha);
                        }
                    }
                @endforeach
            }
        });
    }

    $(function(){
        
    })

</script>

</html>


