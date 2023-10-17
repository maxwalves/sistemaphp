@extends('adminlte::page')

@section('title', 'Veículos Paranacidade')

@section('content_header')
    <h1>Veículos Paranacidade</h1>
@stop

@section('content')

<div class="col-md-10 offset-md-1 dashboard-title-container">
    <h5>Veículos do Paranacidade</h5>
</div>
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
                <th>Ações</th>
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
                <td> 
                    <a href="/veiculosParanacidade/edit/{{ $veiculoParanacidade->id }}" class="btn btn-success btn-sm"> <ion-icon name="create-outline"></ion-icon> Editar</a> 
                    <form action="/veiculosParanacidade/{{ $veiculoParanacidade->id }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm"><ion-icon name="trash-outline"></ion-icon> Deletar</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <p>Ainda não existem veículos cadastrados, <a href="/veiculosParanacidade/create"> Criar novo veículo do Paranacidade</a></p>
    @endif
    <a style="font-size: 16px" href="/veiculosParanacidade/create" type="submit" class="btn btn-primary btn-lg"> Cadastrar novo veículo!</a>
</div>

@stop

@section('css')
    
@stop

@section('js')
    
@stop