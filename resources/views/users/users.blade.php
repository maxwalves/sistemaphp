@extends('adminlte::page')

@section('title', 'Administração de Usuários')

@section('content_header')
    <h1>Administração de Usuários</h1>
@stop

@section('content')

<div class="col-md-10 offset-md-1 dashboard-avs-container">
    @if(count($users) > 0 )
    <h3> <strong> Usuários do sistema </strong></h3>
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    <table id="tabelaRota" class="display nowrap" style="width:100%">
        <thead>
            <tr>
                <th>Id</th>
                <th>Nome</th>
                <th>E-mail</th>
                <th>Gerente</th>
                <th>Setor</th>
                <th>Departamento</th>
                <th>Número</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $userLinha)
            <tr>
                <td> {{$userLinha->id}} </td>
                <td> {{$userLinha->name}} </td>
                <td> {{$userLinha->username}} </td>
                <td> {{$userLinha->manager}} </td>
                <td> {{$userLinha->nomeSetor}} </td>
                <td> {{$userLinha->department}} </td>
                <td> {{$userLinha->employeeNumber}} </td>
                <td> 
                    <a href="/users/editPerfil/{{ $userLinha->id }}" class="btn btn-success btn-sm"> Gerenciar perfil</a> 
                    
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <p>Você ainda não tem usuários, <a href="/users/create"> Criar novo usuário</a></p>
    @endif
    <div class="row">
        <div class="col-12 col-xl-4">
            <a class="btn btn-success btn-lg" href="/users/sincronizarGerentes">Sincronizar gerentes</a>
        </div>
        <div class="col-12 col-xl-4">
            <a class="btn btn-success btn-lg" href="/users/sincronizarSetores">Sincronizar setores</a>
        </div>

        <div class="col-12 col-xl-4">
            <a class="btn btn-success btn-lg" href="/users/sincronizarAD">Sincronizar com AD</a>
        </div>
    </div>
    
</div>

@stop

@section('css')
    <link href="{{asset('DataTables/datatables.min.css')}}" rel="stylesheet"/>
@stop

@section('js')

    <script src="{{asset('DataTables/datatables.min.js')}}"></script>
    <script src="{{asset('/js/moment.js')}}"></script>
    <script type="text/javascript">

        $(document).ready(function(){
            $('#tabelaRota').DataTable({
                    scrollY: 500,
                    "language": {
                        "lengthMenu": "Mostrando _MENU_ registros por página",
                        "zeroRecords": "Nada encontrado",
                        "info": "Mostrando página _PAGE_ de _PAGES_",
                        "infoEmpty": "Nenhum registro disponível",
                        "infoFiltered": "(filtrado de _MAX_ registros no total)",
                        "search": "Procure um usuário:"
                    }
                });
        });

    </script>

@stop