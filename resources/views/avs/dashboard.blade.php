@extends('layouts.main')

@section('title', 'Dashboard')
@section('content')

<div id="avs-container" class="col-md-12">
    @if ($search)
    <h2>Buscando por: {{ $search }}</h2>
    @else
    <h2 >Suas últimas autorizações de viagens:</h2>
    @endif
    
    <div id="cards-container" class="row">
        @for($i = 0; $i < count($avs); $i++)
            <div class= "card col-md-3" >
                <img src="/img/avs/{{ $avs[$i]->image }}" alt="{{ $avs[$i]->title }}">
                <div class="card-body">
                    <p class="card-date">{{ date('d/m/Y', strtotime($avs[$i]->date)) }}</p>
                    <h5 class="card-title">{{ $avs[$i] ->title }}</h5>
                    <p class="card-participants"> {{ $avs[$i] ->city }} </p>
                    <a href="/avs/{{ $avs[$i]->id }}" class="btn btn-primary">Saber Mais</a>
                </div> 
            </div>
            <!-- Verifica se existem mais de 4 AV e mostra apenas 4 -->
            @if($i==3)
            @break
            @endif
        @endfor
        @if(count($avs)==0 && $search)
            <p>Não foi possível encontrar nenhuma AV correspondente com {{ $search }}! <a href="/">Ver todos!</a></p>
        @elseif(count($avs)==0)
            <p>Não há autorizações de viagens vinculadas ao seu usuário!</p> 
        @elseif(count($avs)==1)
        @endif       
    </div>
</div>

@endsection