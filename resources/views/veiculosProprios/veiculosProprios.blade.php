@extends('layouts.main')

@section('title', 'Dashboard')
@section('content')


<div class="col-md-10 offset-md-1 dashboard-avs-container">
    @if(count($veiculosProprios) > 0 )
    <h3> <strong> Meus veículos </strong></h3>
    <table class="table table-hover table-sm table-responsive">
        <thead>
            <tr>
                <th>Marca</th>
                <th>Modelo</th>
                <th>Placa</th>
            </tr>
        </thead>
        <tbody>
            @foreach($veiculosProprios as $veiculoProprio)
            <tr>
                <td> {{$veiculoProprio->marca}} </td>
                <td> {{$veiculoProprio->modelo}} </td>
                <td> {{$veiculoProprio->placa}} </td>
                <td> 
                    <a href="/veiculosProprios/edit/{{ $veiculoProprio->id }}" class="btn btn-success btn-sm"> <ion-icon name="create-outline"></ion-icon> Editar</a> 
                    <form action="/veiculosProprios/{{ $veiculoProprio->id }}" method="POST">
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
    <p>Você ainda não tem veículos, <a href="/veiculosProprios/create"> Criar novo veículo</a></p>
    @endif
    <a style="font-size: 16px" href="/veiculosProprios/create" type="submit" class="btn btn-primary btn-lg"> Cadastrar novo veículo!</a>
</div>

@endsection