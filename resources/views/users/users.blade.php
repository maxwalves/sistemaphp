@extends('layouts.main')

@section('title', 'Dashboard')
@section('content')


<div class="col-md-10 offset-md-1 dashboard-avs-container">
    @if(count($users) > 0 )
    <h3> <strong> Usuários do sistema </strong></h3>
    <table class="table table-hover table-sm table-responsive">
        <thead>
            <tr>
                <th>Id</th>
                <th>Nome</th>
                <th>E-mail</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $userLinha)
            <tr>
                <td> {{$userLinha->id}} </td>
                <td> {{$userLinha->name}} </td>
                <td> {{$userLinha->email}} </td>
                <td> 
                    <a href="/users/edit/{{ $userLinha->id }}" class="btn btn-success btn-sm"> <ion-icon name="create-outline"></ion-icon> Editar</a>
                    <a href="/users/editPerfil/{{ $userLinha->id }}" class="btn btn-success btn-sm"> <ion-icon name="create-outline"></ion-icon> Gerenciar perfil</a> 
                    <form action="/users/{{ $userLinha->id }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-error btn-sm"><ion-icon name="trash-outline"></ion-icon> Deletar</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <p>Você ainda não tem usuários, <a href="/users/create"> Criar novo usuário</a></p>
    @endif
    <div class="row">
        <div class="col-md-6 offset-md-3">
            <a style="font-size: 16px" href="/users/create" type="submit" class="btn btn-active btn-primary btn-lg"><ion-icon name="add-circle-outline" size="large"></ion-icon>  Novo usuário!</a>
            <a style="font-size: 16px" href="/setores/setores" type="submit" class="btn btn-active btn-secondary btn-lg"> Gerenciar setores!</a>
        </div>
    </div>
    
</div>

@endsection