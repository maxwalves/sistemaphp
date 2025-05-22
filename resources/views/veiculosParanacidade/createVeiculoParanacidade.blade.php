@extends('adminlte::page')

@section('title', 'Cadastrar novo veículo do Paranacidade')

@section('content_header')
    <h1>Cadastrar novo veículo do Paranacidade</h1>
@stop

@section('content')

    <div>
        <a href="/veiculosParanacidade/veiculosParanacidade" class="btn btn-warning">Voltar</a>
    </div>
    <br>
    <div id="av-create-container" class="col-md-6 offset-md-3">
        <h5>Cadastrar novo veículo do Paranacidade!</h5>
        <form action="/veiculosParanacidade" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="form-group">
                <label for="marca" class="control-label">Marca</label>
                <input type="text" class="form-control" name="marca" id="marca" placeholder="Marca">
            </div>

            <div class="form-group">
                <label for="modelo" class="control-label">Modelo</label>
                <div class="input-group">
                    <input type="text" class="form-control" name="modelo" id="modelo" placeholder="Modelo">
                </div>
            </div>

            <div class="form-group">
                <label for="placa" class="control-label">Placa</label>
                <input type="placa" class="form-control" name="placa" id="placa" placeholder="Placa">
            </div>

            <div class="form-group" id="veiculoAtivo">
                <label for="isAtivo" class="control-label" required>O veículo está em condições de circulação?
                    (selecione)</label>
                <br>
                <select class="form-control {{ $errors->has('isAtivo') ? 'is-invalid' : '' }}" id="isAtivo" name="isAtivo">
                    <option value="0" name="0"> Não</option>
                    <option value="1" name="1"> Sim</option>
                </select>

                @if ($errors->has('isAtivo'))
                    <div class="invalid-feedback">
                        {{ $errors->first('isAtivo') }}
                    </div>
                @endif
            </div>

            <div class="form-group" id="selecaoRegional">
                <label for="codigoRegional" class="control-label" required>O veículo pertence a qual regional?
                    (selecione)</label>
                <br>
                <select class="form-control {{ $errors->has('isAtivo') ? 'is-invalid' : '' }}" id="codigoRegional"
                    name="codigoRegional">
                    <option value="CWB" name="Curitiba"> Curitiba</option>
                    <option value="CMCAS" name="CMCAS"> Cascavel</option>
                    <option value="CMMGA" name="CMMGA"> Maringá</option>
                    <option value="ERFCB" name="ERFCB"> Francisco Beltrão</option>
                    <option value="CMGP" name="CMGP"> Guarapuava</option>
                    <option value="CMLDR" name="CMLDR"> Londrina</option>
                    <option value="CMPG" name="CMPG"> Ponta Grossa</option>
                </select>

                @if ($errors->has('codigoRegional'))
                    <div class="invalid-feedback">
                        {{ $errors->first('codigoRegional') }}
                    </div>
                @endif
            </div>

            <div class="form-group">
                <label for="observacao" class="control-label">Observação: </label>
                <input type="observacao" class="form-control" name="observacao" id="observacao" placeholder="Observação">
            </div>

            <div id="btSalvarVeiculo">
                <input style="font-size: 16px" type="submit" class="btn btn-primary btn-lg"
                    value="Cadastrar Veículo do Paranacidade!">
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
        $(function() {

        })
    </script>

@stop
