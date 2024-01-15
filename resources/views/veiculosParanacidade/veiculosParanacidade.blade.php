@extends('adminlte::page')

@section('title', 'Veículos Paranacidade')

@section('content_header')
    <h1>Veículos do Paranacidade cadastrados no Sistema de Reservas</h1>
@stop

@section('content')

<div class="col-md-10 offset-md-1 dashboard-avs-container">
    @if(count($veiculosParanacidade) > 0 )
    <table class="table table-hover table-bordered" style="width: 100%">
        <thead>
            <tr>
                <th>Marca</th>
                <th>Modelo</th>
                <th>Placa</th>
                <th>Ativo?</th>
                <th>Observação</th>
                <th>Regional</th>
            </tr>
        </thead>
        <tbody>
            @foreach($veiculosParanacidade as $veiculoParanacidade)
            <tr>
                <td> {{$veiculoParanacidade->marca}} </td>
                <td> {{$veiculoParanacidade->modelo}} </td>
                <td> {{$veiculoParanacidade->placa}} </td>
                <td> {{$veiculoParanacidade->isAtivo == "1" ? "Sim" : "Não"}} </td>
                <td> {{$veiculoParanacidade->observacao}} </td>
                <td> {{$veiculoParanacidade->codigoRegional}} </td>
                
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <p>Ainda não existem veículos cadastrados.</p>
    @endif
</div>

@stop

@section('css')
    
@stop

@section('js')
    
@stop