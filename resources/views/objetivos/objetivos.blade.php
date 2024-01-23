@extends('adminlte::page')

@section('title', 'Objetivos')

@section('content_header')
@stop

@section('content')
    
<div class="col-md-10 offset-md-1 dashboard-title-container">
    <br>
    <h2>Objetivos de viagens cadastrados</h2>
</div>
<div class="col-md-10 offset-md-1 dashboard-avs-container">
    @if(count($objetivos) > 0 )
    <table class="table table-hover table-bordered" style="width: 100%">
        <thead>
            <tr>
                <th>Nome Objetivo</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            @foreach($objetivos as $objetivo)
            <tr>
                <td> {{$objetivo->nomeObjetivo}} </td>
                <td> 
                    <div class="d-flex">
                        <a href="/objetivos/edit/{{ $objetivo->id }}" class="btn btn-success btn-sm"><i class="fas fa-edit"></i></a> 
                        <form action="/objetivos/{{ $objetivo->id }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i></button>
                        </form>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <p>Ainda não há objetivos cadastrados, <a href="/objetivos/create"> Criar novo objetivo!</a></p>
    @endif
    <a style="font-size: 16px" href="/objetivos/create" type="submit" class="btn btn-primary btn-lg"><i class="fas fa-plus"></i></a>
</div>

@stop

@section('css')
    
@stop

@section('js')
    
@stop