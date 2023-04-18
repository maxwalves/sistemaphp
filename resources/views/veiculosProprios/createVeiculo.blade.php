@extends('layouts.main')

@section('title', 'Cadastrar novo veículo')
@section('content')

<div id="av-create-container" class="col-md-6 offset-md-3">
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
                <input style="font-size: 16px" type="submit" class="btn btn-primary btn-lg" value="Cadastrar Veículo!">
            </div>
            
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