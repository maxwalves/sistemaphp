@extends('layouts.main')

@section('title', $av->id)
@section('content')

    <div class="col-md-10 offset-md-1">
        <div class="row">

            <div id="info-container" class="col-md-6">
                <h1>{{ $av->id }}</h1>
                <p class="av-city"><ion-icon name="location-outline"></ion-icon> Status: {{ $av->status }} </p>
                <p class="avs.participants"><ion-icon name="people-outline"></ion-icon> Informações: </p>
                <p class="av-owner"><ion-icon name="star-outline"></ion-icon> Usuário: a</p>
                <p class="av-city"><ion-icon name="location-outline"></ion-icon> Data de criação: {{ $av->dataCriacao }} </p>
                <p class="av-city"><ion-icon name="location-outline"></ion-icon> Prioridade: {{ $av->prioridade }} </p>
                
                
            </div>
            <div class="col-md-12" id="description-container">
                <h3>Comentários sobre a AV:</h3>
                <p class="av-description">{{ $av->comentario }}</p>
                <a href="/" class="btn btn-primary">Voltar</a>
            </div>
            
        </div>
    </div>

@endsection