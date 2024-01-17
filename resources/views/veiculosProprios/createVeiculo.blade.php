@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
@stop

@section('content')

<div class="row">
    <div class="col-md-6">
        <br>
        <h3>Cadastrar novo veículo próprio</h3>
    </div>
    <div class="col-md-4">
        <br>
        <a href="/veiculosProprios/veiculosProprios" class="btn btn-warning"><i class="fas fa-arrow-left"></i></a>
    </div>
</div>
<br>
<div id="av-create-container" class="col-md-6">
    <h2>Cadastrar novo veículo!</h2>
    <form action="/veiculosProprios" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="form-group">
            <label for="marca" class="control-label">Marca</label>
            <input type="text" class="form-control" name="marca"
            id="marca" placeholder="Marca">
        </div>

        <div class="form-group">
            <label for="modelo" class="control-label">Modelo</label>
            <div class="input-group">
                <input type="text" class="form-control" name="modelo"
                id="modelo" placeholder="Modelo">
            </div>
        </div>

        <div class="form-group">
            <label for="placa" class="control-label">Placa</label>
            <input type="placa" class="form-control" name="placa"
            id="placa" placeholder="Placa">
        </div>

        <div id="btSalvarVeiculo">
            <input style="font-size: 16px" type="submit" class="btn btn-success btn-lg" value="Salvar!">
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
