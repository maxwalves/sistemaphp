@extends('layouts.main')

@section('title', $av->id)
@section('content')

    <div class="container">
        <div class="container text-center">
            <div id="info-container" class="col-12">
                <h3> Autorização de Viagem nº {{ $av->id }}</h3>
            </div>
            <div class="col-md-12" id="description-container">
                <h3>Você tem cereteza que deseja cancelar a AV? É irreversível!</h3>
                <br><br>
                <form action="/avs/{{ $av->id }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-active btn-accent btn-sm"
                    style="width: 110px" > Deletar</button>
                </form>
                <br>
                <a href="/avs/avs" class="btn btn-primary">Voltar</a>
            </div>
        </div>
        
    </div>

@endsection