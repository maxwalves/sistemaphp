@extends('layouts.main')

@section('title', $av->id)
@section('content')

    <div class="col-md-10 offset-md-1">
        <div class="row">

            <div id="info-container" class="col-md-10">
                <h1> Autorização de Viagem nº {{ $av->id }}</h1>
                <p class="av-data"><ion-icon name="calendar-outline"></ion-icon> Data de criação: {{ date('d/m/Y', strtotime($av->dataCriacao)) }} </p>
                <p class="av-owner"><ion-icon name="flag-outline"></ion-icon> Objetivo: {{ isset($objetivo->nomeObjetivo) ? $objetivo->nomeObjetivo : $av->outroObjetivo }}</p>
                <p class="av-owner"><ion-icon name="alert-circle-outline"></ion-icon> Prioridade: {{ $av->prioridade }}</p>
                <p class="av-owner"><ion-icon name="business-outline"></ion-icon> Banco: {{ $av->banco }}</p>
                <p class="av-owner"><ion-icon name="home-outline"></ion-icon> Agência: {{ $av->agencia }}</p>
                <p class="av-owner"><ion-icon name="wallet-outline"></ion-icon> Conta: {{ $av->conta }}</p>
                <p class="av-owner"><ion-icon name="cash-outline"></ion-icon> Pix: {{ $av->pix }}</p>
                <p class="av-owner"><ion-icon name="pricetag-outline"></ion-icon> Comentário: {{ $av->comentario }}</p>
                <p class="av-owner"><ion-icon name="chevron-forward-circle-outline"></ion-icon> Status: {{ $av->status }}</p>
                <p class="av-owner"><ion-icon name="car-sport-outline"></ion-icon> Veículo próprio: {{ $av->isVeiculoProprio == '1' ? "" : "Não"}} {{ $av->isVeiculoProprio == '1' ? $veiculoProprio->modelo  : ""}} -
                    {{ $av->isVeiculoProprio == '1' ? $veiculoProprio->placa  : ""}} </p>
                <p class="av-owner"><ion-icon name="car-outline"></ion-icon> Veículo do Paranacidade: {{ $av->isVeiculoEmpresa == '1' ? "Sim" : "Não"}}</p>
                <br>
            </div>
            <div class="col-md-12" id="description-container">
                <h3>Comentários sobre a AV:</h3>
                <p class="av-description">{{ $av->comentario }}</p>
                <a href="/" class="btn btn-primary">Voltar</a>
            </div>
            
        </div>
    </div>

@endsection