@extends('adminlte::page')

@section('title', 'Editar veículo do Paranacidade')

@section('content_header')
    <h1>Editar veículo do Paranacidade</h1>
@stop

@section('content')

    <div>
        <a href="/veiculosParanacidade/veiculosParanacidade" class="btn btn-warning">Voltar</a>
    </div>
    <br>
    <div id="av-create-container" class="col-md-6 offset-md-3">
        <h2>Editando: {{ $veiculoParanacidade->modelo }}</h2>
        <form action="/veiculosParanacidade/update/{{ $veiculoParanacidade->id }}" method="POST"
            enctype="multipart/form-data">
            @csrf
            @method('PUT')


            <div class="form-group">
                <label for="marca" class="control-label">Marca</label>
                <input type="text" class="form-control" name="marca" id="marca" placeholder="Marca"
                    value="{{ $veiculoParanacidade->marca }}">
            </div>

            <div class="form-group">
                <label for="modelo" class="control-label">Modelo</label>
                <div class="input-group">
                    <input type="text" class="form-control" name="modelo" id="modelo" placeholder="Modelo"
                        value="{{ $veiculoParanacidade->modelo }}">
                </div>
            </div>

            <div class="form-group">
                <label for="placa" class="control-label">Placa</label>
                <input type="placa" class="form-control" name="placa" id="placa" placeholder="Placa"
                    value="{{ $veiculoParanacidade->placa }}">
            </div>

            <div class="form-group" id="veiculoAtivo">
                <label for="isAtivo" class="control-label" required>O veículo está em condições de circulação?
                    (selecione)</label>
                <br>
                <select class="custom-select" id="isAtivo" name="isAtivo">
                    <option value="0" name="0"
                        {{ $veiculoParanacidade->isAtivo == '0' ? "selected='selected'" : '' }}> Não</option>
                    <option value="1" name="1"
                        {{ $veiculoParanacidade->isAtivo == '1' ? "selected='selected'" : '' }}> Sim</option>
                </select>
            </div>

            <div class="form-group" id="selecaoRegional">
                <label for="codigoRegional" class="control-label" required>O veículo pertence a qual regional?
                    (selecione)</label>
                <br>
                <select class="form-control {{ $errors->has('isAtivo') ? 'is-invalid' : '' }}" id="codigoRegional"
                    name="codigoRegional">
                    <option value="CWB" name="Curitiba"
                        {{ $veiculoParanacidade->codigoRegional == 'CWB' ? "selected='selected'" : '' }}> Curitiba</option>
                    <option value="CMCAS" name="CMCAS"
                        {{ $veiculoParanacidade->codigoRegional == 'CMCAS' ? "selected='selected'" : '' }}> Cascavel
                    </option>
                    <option value="CMMGA" name="CMMGA"
                        {{ $veiculoParanacidade->codigoRegional == 'CMMGA' ? "selected='selected'" : '' }}> Maringá
                    </option>
                    <option value="ERFCB" name="ERFCB"
                        {{ $veiculoParanacidade->codigoRegional == 'ERFCB' ? "selected='selected'" : '' }}> Francisco
                        Beltrão</option>
                    <option value="CMGP" name="CMGP"
                        {{ $veiculoParanacidade->codigoRegional == 'CMGP' ? "selected='selected'" : '' }}> Guarapuava
                    </option>
                    <option value="CMLDR" name="CMLDR"
                        {{ $veiculoParanacidade->codigoRegional == 'CMLDR' ? "selected='selected'" : '' }}> Londrina
                    </option>
                    <option value="CMPG" name="CMPG"
                        {{ $veiculoParanacidade->codigoRegional == 'CMPG' ? "selected='selected'" : '' }}> Ponta Grossa
                    </option>
                </select>

                @if ($errors->has('codigoRegional'))
                    <div class="invalid-feedback">
                        {{ $errors->first('codigoRegional') }}
                    </div>
                @endif
            </div>

            <div class="form-group">
                <label for="observacao" class="control-label">Observação: </label>
                <input type="observacao" class="form-control" name="observacao" id="observacao" placeholder="Observação"
                    value="{{ $veiculoParanacidade->observacao }}">
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
        $(function() {

        })
    </script>

@stop
