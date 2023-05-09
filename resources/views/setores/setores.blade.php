@extends('layouts.main')

@section('title', 'Dashboard')
@section('content')


<div style="padding-left: 50px, padding-right: 50px" class="container">
    <div class="row justify-content-between" >
        
        <div class="col-4">
            <a href="/users/users/" type="submit" class="btn btn-active btn-ghost"> Voltar!</a>
        </div>
    </div>
</div>
<div class="col-md-10 offset-md-1 dashboard-avs-container">
    @if(count($setores) > 0 )
    <br>
    <h1 style="font-size: 20px"> <strong> Setores cadastrados: </strong></h1>
    <table class="table table-hover table-sm table-responsive">
        <thead>
            <tr>
                <th>Id</th>
                <th>Nome</th>
                <th>Chefe</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            @foreach($setores as $setor)
            <tr>
                <td> {{$setor->id}} </td>
                <td> {{$setor->nome}} </td>
                <td>
                @foreach($users as $userAtual)
                    @if($userAtual->id == $setor->chefe_id)
                         {{$userAtual->name}} 
                    @endif
                @endforeach
                </td>
                <td> 
                    <a href="/setores/edit/{{ $setor->id }}" class="btn btn-success btn-sm"> <ion-icon name="create-outline"></ion-icon> Editar Setor</a>
                    <a href="/setores/funcSetor/{{ $setor->id }}" class="btn btn-success btn-sm"> <ion-icon name="create-outline"></ion-icon> Ver funcionários</a> 
                    <form action="/setores/{{ $setor->id }}" method="POST">
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
    <p>Você ainda não tem setores, <a href="/setores/create"> Criar novo setor</a></p>
    @endif

    <div class="row">
        <div class="col-md-6 offset-md-3">
            <a style="font-size: 16px" href="/setores/create" type="submit" class="btn btn-active btn-primary btn-lg"> Cadastrar novo setor!</a>
        </div>
    </div>
</div>

@endsection