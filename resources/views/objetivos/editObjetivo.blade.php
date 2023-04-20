@extends('layouts.main')

@section('title', 'Editando: ' . $objetivo->nomeObjetivo)
@section('content')

<div id="av-create-container" class="col-md-6 offset-md-3">
        <h2>Editando: {{ $objetivo->nomeObjetivo }}</h2>
        <form action="/objetivos/update/{{ $objetivo->id }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            
            <div class="form-group" id="nomeObjetivo">
                <label for="nomeObjetivo" class="control-label">Nome do Objetivo: </label>
                <input type="text" class="form-control" name="nomeObjetivo"
                id="nomeObjetivo" placeholder="Nome do Objetivo" value="{{$objetivo->nomeObjetivo}}">
            </div>

            <input type="submit" class="btn btn-primary" value="Salvar">

        </form>

    </div>
    
@endsection

{{-- Para implementação futura de AJAX --}} 
@section('javascript')
    <script type="text/javascript">

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            }
        });
        
                //Assim que a tela carrega, aciona automaticamente essas duas funções ------------------------
        $(function(){
            
        })
    </script>
@endsection