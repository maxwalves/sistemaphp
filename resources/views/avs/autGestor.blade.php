@extends('adminlte::page')

@section('title', 'Aut Gestor')

@section('content_header')
@stop

@section('content')
    
<div>
    
    <br>
    <div class="row">
        
        <div class="col-12 col-md-7">
            <h4>AVs pendentes de autorização:</h4>
        </div>
        
        @if($user->username == "camila.ms@paranacidade.org.br")
            <div class="col-12 col-md-5">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="filtrarMinhasAvs">
                    <label class="form-check-label" for="filtrarMinhasAvs">
                        Mostrar apenas AVs onde sou gestora
                    </label>
                </div>
            </div>
        @endif
        
        @if($usersFiltrados != null && $user->username != "camila.ms@paranacidade.org.br")
            <div class="col-12 col-md-5">
                <p>Ver pendências de AVs dos gestores subordinados: </p>
                <select name="users" id="usuarioSelecionado" class="select select-bordered w-full max-w-xs" >
                    <option value="Selecione">Selecione</option>
                    @foreach ($usersFiltrados as $u)
                        <option value="{{ $u->id }}">{{ $u->name }}</option>
                    @endforeach
                </select>
                <button id="filter-button" class="btn btn-primary">Filtrar</button>
                <button id="reset-button" class="btn btn-secondary">Resetar</button>
            </div>
        @endif
    </div>
    <br>
</div> 

<div class="col-md-12 dashboard-avs-container">
    <table id="minhaTabela" class="table table-striped">
        <thead>
            <tr>
                <th style="width: 50px">Número</th>
                <th>Nome funcionário</th>
                @if($user->username == "camila.ms@paranacidade.org.br")
                    <th>Gestor</th>
                @endif
                <th>Objetivo</th>
                <th>Rota</th>
                <th>Data criação</th>
                <th style="width: 250px">Status</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            @foreach($avs as $av)
            <tr>
                <td scropt="row">{{ $av->id }}</td>
                
                <td>
                    @foreach($users as $u)
                        @if ($u->id == $av->user_id)
                                {{$u->name}}     
                        @endif
                    @endforeach
                </td>

                @if($user->username == "camila.ms@paranacidade.org.br")
                    <td>
                        {{$av->managerName}}
                    </td>
                @endif

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
                <td> {{$av->status}} </td>
                <td> 
                    <div class="opcoesGerenciarAv">
                        <a href="/avs/verFluxoGestor/{{ $av->id }}" class="btn btn-primary btn-sm" title="Verificar pendência"><i class="fas fa-user-check"></i></a>
                        
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    
</div>

@stop

@section('css')
    <link href="{{asset('DataTables/datatables.min.css')}}" rel="stylesheet"/>
@stop

@section('js')

    <script src="{{asset('/js/moment.js')}}"></script>
    <script src="{{asset('DataTables/datatables.min.js')}}"></script>
    <script type="text/javascript">
        // Variáveis para armazenar os dados originais da tabela
        let originalTableData = [];
        let currentUserName = @json($user->name);

        $(function(){
            
            // Armazenar dados originais da tabela quando a página carrega
            $('#minhaTabela tbody tr').each(function() {
                originalTableData.push($(this)[0].outerHTML);
            });

            // Event listener para o filtro da Camila
            $('#filtrarMinhasAvs').change(function() {
                if ($(this).is(':checked')) {
                    // Filtrar apenas AVs onde Camila é gestora
                    $('#minhaTabela tbody tr').each(function() {
                        let gestorColumn = $(this).find('td:nth-child(3)').text().trim(); // Coluna do gestor
                        if (gestorColumn !== currentUserName) {
                            $(this).hide();
                        } else {
                            $(this).show(); // Garantir que as AVs da Camila estão visíveis
                        }
                    });
                } else {
                    // Mostrar todas as AVs novamente
                    $('#minhaTabela tbody tr').show();
                }
            });

            // Função para restaurar estado original da tabela (útil para a Camila)
            function restoreOriginalTable() {
                $('#minhaTabela tbody').html(originalTableData.join(''));
                $('#filtrarMinhasAvs').prop('checked', false);
            }

            // Disponibilizar função globalmente se necessário
            window.restoreOriginalTable = restoreOriginalTable;

            $('#filter-button').click(function() {
                // Get selected user ID from dropdown
                const selectedUserId = $('#usuarioSelecionado').val();
                console.log(selectedUserId);
                // Perform the async requests
                const getUsersPromise = $.getJSON('https://viagem.paranacidade.org.br/api/getAllUsers');
                const getObjetivosPromise = $.getJSON('https://viagem.paranacidade.org.br/api/getAllObjetivos');
                const getRotasPromise = $.getJSON('https://viagem.paranacidade.org.br/api/getAllRotas');

                //mostre um loading
                $('#minhaTabela tbody').append('<tr><td colspan="7">Carregando...</td></tr>');
                
                // Wait for all promises to resolve
                $.when(getUsersPromise, getObjetivosPromise, getRotasPromise)
                    .done(function(usersData, objetivosData, rotasData) {
                        // Send AJAX request to get AVs for selected user

                        usersData = usersData[0];
                        objetivosData = objetivosData[0];
                        rotasData = rotasData[0];
                        $.ajax({
                            url: '/api/getAvsByManager/' + selectedUserId,
                            type: 'GET',
                            success: function(response) {
                                // Clear existing table rows
                                $('#minhaTabela tbody').empty();

                                // Append new table rows for each AV
                                response.forEach(function(av) {
                                    const user = av.user;

                                    let row = '<tr>';
                                    row += '<td>' + av.id + '</td>';

                                    row += '<td>';
                                    for (let i = 0; i < usersData.length; i++) {
                                        if (av.user_id == usersData[i].id) {
                                            row += usersData[i].name;
                                            break;
                                        }
                                    }
                                    row += '</td>';

                                    row += '<td>';
                                    for (let i = 0; i < objetivosData.length; i++) {
                                        if (av.objetivo_id == objetivosData[i].id) {
                                            row += objetivosData[i].nomeObjetivo;
                                            break;
                                        }
                                    }
                                    if (av.outroObjetivo) {
                                        row += av.outroObjetivo;
                                    }
                                    row += '</td>';

                                    row += '<td>';
                                    for (let i = 0; i < rotasData.length; i++) {
                                        if (rotasData[i].av_id == av.id) {
                                            if (rotasData[i].isViagemInternacional == 0) {
                                                row += ' - ' + rotasData[i].cidadeOrigemNacional;
                                            } else {
                                                row += ' - ' + rotasData[i].cidadeOrigemInternacional;
                                            }
                                        }
                                    }
                                    row += '</td>';

                                    row += '<td>' + moment(av.dataCriacao).format('DD/MM/YYYY') + '</td>';
                                    row += '<td>' + av.status + '</td>';
                                    row += '<td>';
                                    row += '<div class="opcoesGerenciarAv">';
                                    row += '<a href="/avs/verFluxoGestor/' + av.id + '" class="btn btn-primary btn-sm"><i class="fas fa-user-check"></i></a>';
                                    row += '</div>';
                                    row += '</td>';
                                    row += '</tr>';

                                    $('#minhaTabela tbody').append(row);
                                });
                            },
                            error: function(error) {
                                console.log(error);
                            }
                        });
                    })
                    .fail(function(error) {
                        console.log(error);
                    });
            });

            $('#reset-button').click(function() {

                $('#usuarioSelecionado').html('');
                $('#usuarioSelecionado').append('<option value="" selected>Selecione</option>');
                @foreach ($usersFiltrados as $u)
                    $('#usuarioSelecionado').append('<option value="{{ $u->id }}">{{ $u->name }}</option>');
                @endforeach

                const selectedUserId = '{{$user->id}}';
                console.log(selectedUserId);
                // Perform the async requests
                const getUsersPromise = $.getJSON('https://viagem.paranacidade.org.br/api/getAllUsers');
                const getObjetivosPromise = $.getJSON('https://viagem.paranacidade.org.br/api/getAllObjetivos');
                const getRotasPromise = $.getJSON('https://viagem.paranacidade.org.br/api/getAllRotas');

                $('#minhaTabela tbody').append('<tr><td colspan="7">Carregando...</td></tr>');
                
                // Wait for all promises to resolve
                $.when(getUsersPromise, getObjetivosPromise, getRotasPromise)
                    .done(function(usersData, objetivosData, rotasData) {
                        // Send AJAX request to get AVs for selected user

                        usersData = usersData[0];
                        objetivosData = objetivosData[0];
                        rotasData = rotasData[0];
                        $.ajax({
                            url: '/api/getAvsByManager/' + selectedUserId,
                            type: 'GET',
                            success: function(response) {
                                // Clear existing table rows
                                $('#minhaTabela tbody').empty();

                                // Append new table rows for each AV
                                response.forEach(function(av) {
                                    const user = av.user;

                                    let row = '<tr>';
                                    row += '<td>' + av.id + '</td>';

                                    row += '<td>';
                                    for (let i = 0; i < usersData.length; i++) {
                                        if (av.user_id == usersData[i].id) {
                                            row += usersData[i].name;
                                            break;
                                        }
                                    }
                                    row += '</td>';

                                    row += '<td>';
                                    for (let i = 0; i < objetivosData.length; i++) {
                                        if (av.objetivo_id == objetivosData[i].id) {
                                            row += objetivosData[i].nomeObjetivo;
                                            break;
                                        }
                                    }
                                    if (av.outroObjetivo) {
                                        row += av.outroObjetivo;
                                    }
                                    row += '</td>';

                                    row += '<td>';
                                    for (let i = 0; i < rotasData.length; i++) {
                                        if (rotasData[i].av_id == av.id) {
                                            if (rotasData[i].isViagemInternacional == 0) {
                                                row += ' - ' + rotasData[i].cidadeOrigemNacional;
                                            } else {
                                                row += ' - ' + rotasData[i].cidadeOrigemInternacional;
                                            }
                                        }
                                    }
                                    row += '</td>';

                                    row += '<td>' + moment(av.dataCriacao).format('DD/MM/YYYY') + '</td>';
                                    row += '<td>' + av.status + '</td>';
                                    row += '<td>';
                                    row += '<div class="opcoesGerenciarAv">';
                                    row += '<a href="/avs/verFluxoGestor/' + av.id + '" class="btn btn-primary btn-sm"><i class="fas fa-user-check"></i></a>';
                                    row += '</div>';
                                    row += '</td>';
                                    row += '</tr>';

                                    $('#minhaTabela tbody').append(row);
                                });
                            },
                            error: function(error) {
                                console.log(error);
                            }
                        });
                    })
                    .fail(function(error) {
                        console.log(error);
                    });
            });
        })

    </script>

@stop
