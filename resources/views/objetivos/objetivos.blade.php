@extends('layouts.main')

@section('title', 'Dashboard')
@section('content')

<div class="col-md-10 offset-md-1 dashboard-title-container">
    <h1>Objetivos de viagens cadastrados</h1>
</div>
<div class="col-md-10 offset-md-1 dashboard-avs-container">
    @if(count($objetivos) > 0 )
    <table class="table table-hover table-sm table-responsive">
        <thead>
            <tr>
                <th>Nome Objetivo</th>
            </tr>
        </thead>
        <tbody>
            @foreach($objetivos as $objetivo)
            <tr>
                <td> {{$objetivo->nomeObjetivo}} </td>
                <td> 
                    <a href="/objetivos/edit/{{ $objetivo->id }}" class="btn btn-info btn-sm edit-btn"> <ion-icon name="create-outline"></ion-icon> Editar</a> 
                    <form action="/objetivos/{{ $objetivo->id }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm delete-btn"><ion-icon name="trash-outline"></ion-icon> Deletar</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <p>Ainda não há objetivos cadastrados, <a href="/objetivos/create"> Criar novo objetivo!</a></p>
    @endif
    <a style="font-size: 16px" href="/objetivos/create" type="submit" class="btn btn-primary btn-lg"> Cadastrar novo objetivo!</a>
</div>

@endsection