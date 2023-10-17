@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Dashboard</h1>
@stop

@section('content')

<div id="av-create-container" class="col-md-6 offset-md-3">
    <h3>Cadastrar novo objetivo de viagem!</h3>
    <form action="/objetivos" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="form-group" id="nomeObjetivo">
            <label for="nomeObjetivo" class="control-label">Nome do Objetivo: </label>
            <input type="text" class="form-control" name="nomeObjetivo"
            id="nomeObjetivo" placeholder="Nome do Objetivo">
        </div>

        <div id="btSalvarObjetivo">
            <input style="font-size: 16px" type="submit" class="btn btn-primary btn-lg" value="Cadastrar Objetivo!">
        </div>
        
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
