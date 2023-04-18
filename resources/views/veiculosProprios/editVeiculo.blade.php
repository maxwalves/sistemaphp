@extends('layouts.main')

@section('title', 'Editando: ' . $veiculoProprio->modelo)
@section('content')

<div id="av-create-container" class="col-md-6 offset-md-3">
        <h2>Editando: {{ $veiculoProprio->modelo }}</h2>
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