@extends('layouts.main')

@section('title', 'Dashboard')
@section('content')

<div class="col-md-10 offset-md-1 dashboard-title-container">
    <h1>Minhas autorizações de viagens</h1>
</div>
<div class="col-md-10 offset-md-1 dashboard-avs-container">
    @if(count($avs) > 0 )
    <table class="table table-hover table-sm table-responsive">
        <thead>
            <tr>
                <th>Número</th>
                <th>Data</th>
                <th>Prioridade</th>
                <th>Status</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            @foreach($avs as $av)
            <tr>
                <td scropt="row">{{ $av->id }}</td>
                <td> <a> {{ date('d/m/Y', strtotime($av->dataCriacao)) }} </a></td>
                <td> {{$av->prioridade}} </td>
                <td> {{$av->status}} </td>
                <td> 
                    <div class="opcoesGerenciarAv">
                        <a href="/avs/edit/{{ $av->id }}" class="btn btn-info btn-sm edit-btn"> <ion-icon name="create-outline"></ion-icon> Editar</a> 
                        <form action="/avs/{{ $av->id }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm delete-btn"><ion-icon name="trash-outline"></ion-icon> Deletar</button>
                        </form>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <p>Você ainda não tem autorizações de viagens, <a href="/avs/create"> Criar Nova AV</a></p>
    @endif
    
</div>

@endsection