@extends('adminlte::page')

@section('title', 'Veículos cadastrados')

@section('content_header')
    <h1>Veículos cadastrados</h1>
@stop

@section('content')
    
<div class="col-md-10 offset-md-1 dashboard-avs-container">
    @if(count($veiculosProprios) > 0 )
    <h3> <strong> Meus veículos </strong></h3>
    <table class="table table-hover table-bordered" style="width: 100%">
        <thead>
            <tr>
                <th>Marca</th>
                <th>Modelo</th>
                <th>Placa</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            @foreach($veiculosProprios as $veiculoProprio)
            <tr>
                <td> {{$veiculoProprio->marca}} </td>
                <td> {{$veiculoProprio->modelo}} </td>
                <td> {{$veiculoProprio->placa}} </td>
                <td class="d-flex"> 
                    <a href="/veiculosProprios/edit/{{ $veiculoProprio->id }}" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></a> 
                    <form action="/veiculosProprios/{{ $veiculoProprio->id }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i></button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <p>Você ainda não tem veículos, <a href="/veiculosProprios/create"> Criar novo veículo</a></p>
    @endif
    <a style="font-size: 16px" href="/veiculosProprios/create" type="submit" class="btn btn-success btn-lg"><i class="fas fa-plus"></i></a>
</div>

@stop

@section('css')
    
@stop

@section('js')
    
@stop