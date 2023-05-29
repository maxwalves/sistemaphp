@extends('layouts.main')

@section('title', 'Dashboard')
@section('content')

<div class="col-md-10 offset-md-1 dashboard-title-container">
    <h1>Veículos do Paranacidade</h1>
</div>
<div class="col-md-10 offset-md-1 dashboard-avs-container">
    @if(count($veiculosParanacidade) > 0 )
    <table class="table table-hover table-sm table-responsive">
        <thead>
            <tr>
                <th>Marca</th>
                <th>Modelo</th>
                <th>Placa</th>
                <th>Ativo?</th>
                <th>Observação</th>
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
                <td> 
                    <a href="/veiculosParanacidade/edit/{{ $veiculoParanacidade->id }}" class="btn btn-success btn-sm"> <ion-icon name="create-outline"></ion-icon> Editar</a> 
                    <form action="/veiculosParanacidade/{{ $veiculoParanacidade->id }}" method="POST">
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
    <p>Ainda não existem veículos cadastrados, <a href="/veiculosParanacidade/create"> Criar novo veículo do Paranacidade</a></p>
    @endif
    <a style="font-size: 16px" href="/veiculosParanacidade/create" type="submit" class="btn btn-primary btn-lg"> Cadastrar novo veículo!</a>
</div>

@endsection