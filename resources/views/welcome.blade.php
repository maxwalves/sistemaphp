@extends('layouts.main')

@section('title', 'Paranacidade')
@section('content')


<div >
    
    <div class="row">
        @for($i = count($avs)-1; $i >=0; $i--)<!-- Mostra de trás pra frente -->
        <div class="col-12 col-xl-4">
            <div class= "card" >
                
                <div class="card-body">
                    <p class="card-date"> Data de criação: {{ date('d/m/Y', strtotime($avs[$i]->dataCriacao)) }}</p>
                    <h5 class="card-title"> Número da AV: {{ $avs[$i] ->id }}</h5>
                    <p class="card-participants"> <strong> Status: </strong> {{ $avs[$i]->status }} </p>
                    <p class="card-participants"> <strong> Comentários: </strong> {{ $avs[$i]->comentario }} </p>
                    <p class="card-participants"> <strong> Prioridade: </strong> {{ $avs[$i]->prioridade }} </p>
                    <a href="/avs/verDetalhesAv/{{ $avs[$i]->id }}" class="btn btn-primary">Saber Mais</a>
                </div> 
            </div>
        </div>
            
            <!-- Verifica se existem mais de 4 AV e mostra apenas 4 -->
            @if($i==(count($avs)-4))
            @break
            @endif
        @endfor
        @if(count($avs)==0)
            <p>Não há autorizações de viagens vinculadas ao seu usuário!</p> 
        @elseif(count($avs)==1)
        @endif       
    </div>
</div>

@endsection