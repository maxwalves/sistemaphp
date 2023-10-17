@extends('adminlte::page')

@section('title', 'Editar Objetivo')

@section('content_header')
    <h1>Editar Objetivo</h1>
@stop

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

@stop

@section('css')
    
@stop

@section('js')
    
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

@stop