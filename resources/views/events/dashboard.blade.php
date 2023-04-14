@extends('layouts.main')

@section('title', 'Dashboard')
@section('content')

<div id="events-container" class="col-md-12">
    @if ($search)
    <h2>Buscando por: {{ $search }}</h2>
    @else
    <h2 >Suas últimas autorizações de viagens:</h2>
    @endif
    
    <div id="cards-container" class="row">
        @for($i = 0; $i < count($events); $i++)
            <div class= "card col-md-3" >
                <img src="/img/events/{{ $events[$i]->image }}" alt="{{ $events[$i]->title }}">
                <div class="card-body">
                    <p class="card-date">{{ date('d/m/Y', strtotime($events[$i]->date)) }}</p>
                    <h5 class="card-title">{{ $events[$i] ->title }}</h5>
                    <p class="card-participants"> {{ $events[$i] ->city }} </p>
                    <a href="/events/{{ $events[$i]->id }}" class="btn btn-primary">Saber Mais</a>
                </div> 
            </div>
            <!-- Verifica se existem mais de 4 AV e mostra apenas 4 -->
            @if($i==3)
            @break
            @endif
        @endfor
        @if(count($events)==0 && $search)
            <p>Não foi possível encontrar nenhuma AV correspondente com {{ $search }}! <a href="/">Ver todos!</a></p>
        @elseif(count($events)==0)
            <p>Não há autorizações de viagens vinculadas ao seu usuário!</p> 
        @elseif(count($events)==1)
        @endif       
    </div>
</div>

@endsection