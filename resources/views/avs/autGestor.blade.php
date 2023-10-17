@extends('adminlte::page')

@section('title', 'Aut Gestor')

@section('content_header')
    <h1>Aut Gestor</h1>
@stop

@section('content')
    
<div style="padding-left: 10%" class="container">
    
    <div class="row justify-content-between">
        
        <div class="col-12 col-md-7">
            <h4>AVs pendentes de autorização:</h4>
        </div>
        @if($usersFiltrados != null)
            <div class="col-12 col-md-5">
                <p>Ver pendências de AVs como se fosse o usuário: </p>
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
                        <a href="/avs/verFluxoGestor/{{ $av->id }}" class="btn btn-primary btn-sm"
                            style="width: 110px"> Ver</a> 
                        
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


        $(function(){

            $('#filter-button').click(function() {
                // Get selected user ID from dropdown
                const selectedUserId = $('#usuarioSelecionado').val();
                console.log(selectedUserId);
                // Perform the async requests
                const getUsersPromise = $.getJSON('/api/getAllUsers/');
                const getObjetivosPromise = $.getJSON('/api/getAllObjetivos/');
                const getRotasPromise = $.getJSON('/api/getAllRotas/');
                
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
                                    row += '<a href="/avs/verFluxoGestor/' + av.id + '" class="btn btn-secondary btn-sm" style="width: 110px">Ver</a>';
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
                const getUsersPromise = $.getJSON('/api/getAllUsers/');
                const getObjetivosPromise = $.getJSON('/api/getAllObjetivos/');
                const getRotasPromise = $.getJSON('/api/getAllRotas/');
                
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
                                    row += '<a href="/avs/verFluxoGestor/' + av.id + '" class="btn btn-secondary btn-sm" style="width: 110px">Ver</a>';
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
