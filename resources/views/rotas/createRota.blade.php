@extends('layouts.main')

@section('title', 'Cadastrar nova rota')
@section('content')

<div id="av-create-container" class="col-md-6 offset-md-3">
        <h2>Cadastrar nova rota!</h2>
        <form action="/rotas" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="form-group">
                <label for="isViagemInternacional" class="control-label">A viagem será internacional?</label>
                <br>
                    <select class="custom-select {{ $errors->has('isViagemInternacional') ? 'is-invalid' :''}}" 
                        id="isViagemInternacional" name="isViagemInternacional" >
                        <option value="0" name="0"> Não</option>
                        <option value="1" name="1" > Sim</option>
                    </select>

                    @if ($errors->has('isViagemInternacional'))
                    <div class="invalid-feedback">
                        {{ $errors->first('isViagemInternacional') }}
                    </div>
                    @endif
            </div>


            <div class="form-group">
                <label for="cidadeSaida" class="control-label">Cidade de saída: </label>
                <input type="text" class="form-control" name="cidadeSaida"
                id="cidadeSaida" placeholder="Cidade de saída">
            </div>

            <div class="form-group"> 
                <div id="dataHoraSaida" class="input-append date">
                    <label for="dataHoraSaida" class="control-label">Data/Hora de saída: </label>
                    <input data-format="dd/MM/yyyy hh:mm:ss" type="datetime-local" name="dataHoraSaida"
                        id="dataHoraSaida" placeholder="Data/Hora de saída">
                </div>
            </div>
            <br>
            <div class="form-group">
                <label for="cidadeChegada" class="control-label">Cidade de chegada: </label>
                <input type="text" class="form-control" name="cidadeChegada"
                id="cidadeChegada" placeholder="Cidade de chegada">
            </div>

            <div class="form-group"> 
                <div id="dataHoraChegada" class="input-append date">
                    <label for="dataHoraChegada" class="control-label">Data/Hora de chegada: </label>
                    <input data-format="dd/MM/yyyy hh:mm:ss" type="datetime-local" name="dataHoraChegada"
                        id="dataHoraChegada" placeholder="Data/Hora de chegada">
                </div>
            </div>
            <br>

            <div class="form-group">
                <label for="isReservaHotel" class="control-label">Você vai precisar de reserva de hotel?</label>
                <br>
                    <select class="custom-select {{ $errors->has('isReservaHotel') ? 'is-invalid' :''}}" 
                        id="isReservaHotel" name="isReservaHotel" >
                        <option value="0" name="0"> Não</option>
                        <option value="1" name="1" > Sim</option>
                    </select>

                    @if ($errors->has('isReservaHotel'))
                    <div class="invalid-feedback">
                        {{ $errors->first('isReservaHotel') }}
                    </div>
                    @endif
            </div>


            <div class="form-group">
                <label for="tipoTransporte" class="control-label">Qual o tipo de transporte?</label>
                <br>
                    <select class="custom-select {{ $errors->has('tipoTransporte') ? 'is-invalid' :''}}" 
                        id="tipoTransporte" name="tipoTransporte" onChange="ativarCampo()">
                        <option value="0" name="0"> Onibus Leito</option>
                        <option value="1" name="1" > Onibus convencional</option>
                        <option value="2" name="2" > Veículo próprio</option>
                        <option value="3" name="3" > Veículo do Paranacidade</option>
                        <option value="4" name="4" > Avião</option>
                    </select>

                    @if ($errors->has('tipoTransporte'))
                    <div class="invalid-feedback">
                        {{ $errors->first('tipoTransporte') }}
                    </div>
                    @endif
            </div>

            <div class="form-group" id="selecaoVeiculo" hidden="true">
                <label for="veiculoProprio_id" class="control-label" required>Selecione o veículo?</label>
                <br>
                    <select class="custom-select {{ $errors->has('veiculoProprio_id') ? 'is-invalid' :''}}" 
                        id="veiculoProprio_id" name="veiculoProprio_id">
                        <option value="" name=""> Selecione</option>
                        @for($i = 0; $i < count($veiculosProprios); $i++)
                            <div>
                                <option value="{{ $veiculosProprios[$i]->id }}" 
                                    name="{{ $veiculosProprios[$i]->id }}"> {{ $veiculosProprios[$i] ->modelo }} - {{ $veiculosProprios[$i] ->placa }} </option>
                            </div>
                        @endfor
                    </select>

                    @if ($errors->has('veiculoProprio_id'))
                    <div class="invalid-feedback">
                        {{ $errors->first('veiculoProprio_id') }}
                    </div>
                    @endif
            </div>

            <div id="btSalvarRota">
                <input style="font-size: 16px" type="submit" class="btn btn-primary btn-lg" value="Cadastrar Rota!">
            </div>
            
        </form>

    </div>
    
@endsection

{{-- Para implementação futura de AJAX --}} 
@section('javascript')
    <script type="text/javascript">

        function ativarCampo(){
            var tipoTransporte = document.getElementById("tipoTransporte")
            var veiculoProprio_id = document.getElementById("veiculoProprio_id")

            if(tipoTransporte.value=="2") {//Se for veículo próprio
                document.getElementById("selecaoVeiculo").hidden = false;               
            }
            else{
                document.getElementById("selecaoVeiculo").hidden = true;
                document.getElementById("veiculoProprio_id").value = "";
            }
        }

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