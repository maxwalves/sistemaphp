@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
@stop

@section('content')

<div class="row">
    <div class="col-md-6">
        <br>
        <h3>Editar veículo próprio</h3>
    </div>
    <div class="col-md-4">
        <br>
        <a href="/veiculosProprios/veiculosProprios" class="btn btn-warning"><i class="fas fa-arrow-left"></i></a>
    </div>
</div>
<div id="av-create-container" class="col-md-6">
    <form action="/veiculosProprios/update/{{ $veiculoProprio->id }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        
        <div class="form-group">
            <label for="marca" class="control-label">Marca</label>
            <input type="text" class="form-control" name="marca"
            id="marca" placeholder="Marca" value="{{$veiculoProprio->marca}}">
        </div>

        <div class="form-group">
            <label for="modelo" class="control-label">Modelo</label>
            <div class="input-group">
                <input type="text" class="form-control" name="modelo"
                id="modelo" placeholder="Modelo" value="{{$veiculoProprio->modelo}}">
            </div>
        </div>

        <div class="form-group">
            <label for="placa" class="control-label">Placa</label>
            <input type="placa" class="form-control" name="placa"
            id="placa" placeholder="Placa" value="{{$veiculoProprio->placa}}">
        </div>


        <input type="submit" class="btn btn-success" value="Salvar">

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