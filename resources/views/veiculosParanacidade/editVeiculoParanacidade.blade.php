@extends('layouts.main')

@section('title', 'Editando: ' . $veiculoParanacidade->modelo)
@section('content')

<div id="av-create-container" class="col-md-6 offset-md-3">
        <h2>Editando: {{ $veiculoParanacidade->modelo }}</h2>
        <form action="/veiculosProprios/update/{{ $veiculoParanacidade->id }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            
            <div class="form-group">
                <label for="marca" class="control-label">Marca</label>
                <input type="text" class="form-control" name="marca"
                id="marca" placeholder="Marca" value="{{$veiculoParanacidade->marca}}">
            </div>

            <div class="form-group">
                <label for="modelo" class="control-label">Modelo</label>
                <div class="input-group">
                    <input type="text" class="form-control" name="modelo"
                    id="modelo" placeholder="Modelo" value="{{$veiculoParanacidade->modelo}}">
                </div>
            </div>

            <div class="form-group">
                <label for="placa" class="control-label">Placa</label>
                <input type="placa" class="form-control" name="placa"
                id="placa" placeholder="Placa" value="{{$veiculoParanacidade->placa}}">
            </div>

            <div class="form-group" id="veiculoAtivo">
                <label for="isAtivo" class="control-label" required>O veículo está em condições de circulação? (selecione)</label>
                <br>
                    <select class="custom-select {{ $errors->has('isAtivo') ? 'is-invalid' :''}}" 
                        id="isAtivo" name="isAtivo">
                        <option value="0" name="0" {{ $veiculoParanacidade->isAtivo == "1" ? "selected='selected'" : ""}}> Não</option>
                        <option value="1" name="1" {{ $veiculoParanacidade->isAtivo == "0" ? "selected='selected'" : ""}}> Sim</option>
                    </select>

                    @if ($errors->has('isAtivo'))
                    <div class="invalid-feedback">
                        {{ $errors->first('isAtivo') }}
                    </div>
                    @endif
            </div>

            <div class="form-group">
                <label for="observacao" class="control-label">Observação: </label>
                <input type="observacao" class="form-control" name="observacao"
                id="observacao" placeholder="Observação">
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