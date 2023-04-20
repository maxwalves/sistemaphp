@extends('layouts.main')

@section('title', 'Dashboard')
@section('content')

<div class="col-md-10 offset-md-1 dashboard-title-container">
    <h1>Minhas rotas</h1>
</div>
<div class="col-md-10 offset-md-1 dashboard-avs-container">
    @if(count($rotas) > 0 )
    <table class="table table-hover table-sm table-responsive">
        <thead>
            <tr>
                <th>Cidade de saída</th>
                <th>Data/Hora de saída</th>
                <th>Cidade de chegada</th>
                <th>Data/Hora de chegada</th>
                <th>Hospedagem?</th>
                <th>Tipo de transporte</th>
            </tr>
        </thead>
        <tbody>
            @foreach($rotas as $rota)
            <tr>
                <td> {{$rota->cidadeSaida}} </td>
                <td> {{ date('d/m/Y', strtotime($rota->dataHoraSaida)) }} </td>
                <td> {{$rota->cidadeChegada}} </td>
                <td> {{ date('d/m/Y', strtotime($rota->dataHoraChegada)) }} </td>
                <td> {{ $rota->isReservaHotel == 1 ? "Sim" : "Não"}}</td>
                <td> 
                    {{ $rota->isOnibusLeito == 1 ? "Onibus leito" : ""}}
                    {{ $rota->isOnibusConvencional == 1 ? "Onibus convencional" : ""}}
                    {{ $rota->isVeiculoProprio == 1 ? "Veículo próprio" : ""}}
                    {{ $rota->isVeiculoEmpresa == 1 ? "Veículo empresa" : ""}}
                    {{ $rota->isAereo == 1 ? "Aéreo" : ""}}
                </td>
                <td> 
                    <a href="/rotas/edit/{{ $rota->id }}" class="btn btn-info btn-sm edit-btn"> <ion-icon name="create-outline"></ion-icon> Editar</a> 
                    <form action="/rotas/{{ $rota->id }}" method="POST">
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
    <p>Você ainda não tem rotas, <a href="/rotas/create"> Criar nova rota</a></p>
    @endif
    <a style="font-size: 16px" href="/rotas/create" type="submit" class="btn btn-primary btn-lg"> Cadastrar nova rota!</a>
</div>

@endsection