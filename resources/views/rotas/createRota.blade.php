@extends('layouts.main')

@section('title', 'Cadastrar nova rota')
@section('content')

    <div id="container">
        <h2>Cadastrao de nova rota!</h2>
        <form action="/rotas" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row justify-content-center">
                <div class="col-4">
                    <ul class="steps">
                        <li class="step step-primary">Curitiba</li>
                        <li class="step step-neutral" href=""> VOCÊ ESTÁ AQUI</li>
                        <li class="step step-primary" >Curitiba</li>
                    </ul>
                </div>
                <div class="col-4">
                    <div class="form-group">
                        <label for="isViagemInternacional" class="control-label">A viagem será internacional?</label>
                        <br>
                            <select class="select select-bordered w-full max-w-xs {{ $errors->has('isViagemInternacional') ? 'is-invalid' :''}}" 
                                id="isViagemInternacional" name="isViagemInternacional" onChange="gerenciaNacionalInternacional()" >
                                <option value="" name=""> Selecione</option>
                                <option value="0" name="0"> Não</option>
                                <option value="1" name="1" > Sim</option>
                            </select>

                            @if ($errors->has('isViagemInternacional'))
                            <div class="invalid-feedback">
                                {{ $errors->first('isViagemInternacional') }}
                            </div>
                            @endif
                    </div>
                </div>
            </div>
            <div id="isInternacional">
                <br>
                        
                <div class="row justify-content-center">
                    <div class="col-4">
                        {{-- CAMPOS DE ORIGEM INTERNACIONAL ---------------------------------}}
                        <h3 style="color: brown"> <ion-icon name="airplane-outline"></ion-icon> VIAGEM INTERNACIONAL</h3>
                        <br>
                        <h4 style="color: crimson"> Origem: </h4>
                        <div class="form-group">
                            <label for="selecaoContinenteOrigem" class="control-label">Selecione o continente origem</label>
                            <br>
                                <select class="select select-bordered select-sm w-full max-w-xs {{ $errors->has('selecaoContinenteOrigem') ? 'is-invalid' :''}}" 
                                    id="selecaoContinenteOrigem" name="selecaoContinenteOrigem" >
                                    <option value="0" name="0"> Selecione</option>
                                    <option value="1" name="1"> América Latina ou América Central</option>
                                    <option value="2" name="2" > América do Norte</option>
                                    <option value="3" name="3" > Europa</option>
                                    <option value="4" name="4" > África</option>
                                    <option value="5" name="5" > Ásia</option>
                                </select>
            
                                @if ($errors->has('selecaoContinenteOrigem'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('selecaoContinenteOrigem') }}
                                </div>
                                @endif
                        </div>

                        <div class="form-group">
                            <label for="selecaoPaisOrigem" class="control-label">Selecione o país origem:</label>
                            <br>
                    
                            <select class="select select-bordered select-sm w-full max-w-xs {{ $errors->has('selecaoPaisOrigem') ? 'is-invalid' :''}}" 
                                id="selecaoPaisOrigem" name="selecaoPaisOrigem">
                                

                            </select>
            
                                @if ($errors->has('selecaoPaisOrigem'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('selecaoPaisOrigem') }}
                                </div>
                                @endif
                        </div>

                        <div class="form-group">
                            <label for="selecaoEstadoOrigem" class="control-label">Digite o nome do estado/província origem:</label>
                            <br>
                                <input class="input input-bordered input-primary w-full max-w-xs {{ $errors->has('selecaoEstadoOrigem') ? 'is-invalid' :''}}" type="text"
                                id="selecaoEstadoOrigem" name="selecaoEstadoOrigem">
            
                                @if ($errors->has('selecaoEstadoOrigem'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('selecaoEstadoOrigem') }}
                                </div>
                                @endif
                        </div>

                        <div class="form-group">
                            <label for="selecaoCidadeOrigem" class="control-label">Digite o nome da cidade de origem:</label>
                            <br>

                                <input class="input input-bordered input-primary w-full max-w-xs {{ $errors->has('selecaoCidadeOrigem') ? 'is-invalid' :''}}" type="text"
                                id="selecaoCidadeOrigem" name="selecaoCidadeOrigem">
            
                                @if ($errors->has('selecaoCidadeOrigem'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('selecaoCidadeOrigem') }}
                                </div>
                                @endif
                        </div>

                        <div>
                            <input style="font-size: 12px" type="text" id="botaoResetOrigemInternacional"
                            class="btn btn-outline btn-secondary btn-sm" value="Resetar Origem!" onClick="resetarCampoOrigemInternacional()">
                        </div>

                        

                        <div class="form-group"> 
                            <div id="dataHoraSaida" class="input-append date">
                                <label for="dataHoraSaida" class="control-label">Data/Hora de saída: </label>
                                <input data-format="dd/MM/yyyy hh:mm:ss" type="datetime-local" name="dataHoraSaida"
                                    id="dataHoraSaida" placeholder="Data/Hora de saída">
                            </div>
                        </div>
                    </div>
                    <div class="col-4">
                        {{-- CAMPOS DE DETINO INTERNACIONAL ---------------------------------}}
                        <br><br>
                        <h4 style="color: crimson"> Destino: </h4>
                        <div class="form-group">
                            <label for="selecaoContinenteDestinoInternacional" class="control-label">Selecione o continente destino</label>
                            <br>
                                <select class="select select-bordered select-sm w-full max-w-xs {{ $errors->has('selecaoContinenteDestinoInternacional') ? 'is-invalid' :''}}" 
                                    id="selecaoContinenteDestinoInternacional" name="selecaoContinenteDestinoInternacional" >
                                    <option value="0" name="0"> Selecione</option>
                                    <option value="1" name="1"> América Latina ou América Central</option>
                                    <option value="2" name="2" > América do Norte</option>
                                    <option value="3" name="3" > Europa</option>
                                    <option value="4" name="4" > África</option>
                                    <option value="5" name="5" > Ásia</option>
                                </select>
            
                                @if ($errors->has('selecaoContinenteDestinoInternacional'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('selecaoContinenteDestinoInternacional') }}
                                </div>
                                @endif
                        </div>

                        <div class="form-group">
                            <label for="selecaoPaisDestinoInternacional" class="control-label">Selecione o país destino:</label>
                            <br>
                    
                            <select class="select select-bordered select-sm w-full max-w-xs {{ $errors->has('selecaoPaisDestinoInternacional') ? 'is-invalid' :''}}" 
                                id="selecaoPaisDestinoInternacional" name="selecaoPaisDestinoInternacional" >
                                

                            </select>
            
                                @if ($errors->has('selecaoPaisDestinoInternacional'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('selecaoPaisDestinoInternacional') }}
                                </div>
                                @endif
                        </div>

                        <div class="form-group">
                            <label for="selecaoEstadoDestinoInternacional" class="control-label">Digite o nome do estado/província destino:</label>
                            <br>

                                <input class="input input-bordered input-primary w-full max-w-xs {{ $errors->has('selecaoEstadoDestinoInternacional') ? 'is-invalid' :''}}" type="text"
                                id="selecaoEstadoDestinoInternacional" name="selecaoEstadoDestinoInternacional">
            
                                @if ($errors->has('selecaoEstadoDestinoInternacional'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('selecaoEstadoDestinoInternacional') }}
                                </div>
                                @endif
                        </div>

                        <div class="form-group">
                            <label for="selecaoCidadeDestinoInternacional" class="control-label">Digite o nome da cidade destino:</label>
                            <br>

                                <input class="input input-bordered input-primary w-full max-w-xs {{ $errors->has('selecaoCidadeDestinoInternacional') ? 'is-invalid' :''}}" type="text"
                                id="selecaoCidadeDestinoInternacional" name="selecaoCidadeDestinoInternacional">
            
                                @if ($errors->has('selecaoCidadeDestinoInternacional'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('selecaoCidadeDestinoInternacional') }}
                                </div>
                                @endif
                        </div>

                        <div>
                            <input style="font-size: 12px" type="text" id="botaoResetDestinoInternacional"
                            class="btn btn-outline btn-secondary btn-sm" value="Resetar Destino!" onClick="resetarCampoDestinoInternacional()">
                        </div>

                        

                        <div class="form-group"> 
                            <div id="dataHoraChegada" class="input-append date">
                                <label for="dataHoraChegada" class="control-label">Data/Hora de chegada: </label>
                                <input data-format="dd/MM/yyyy hh:mm:ss" type="datetime-local" name="dataHoraChegada"
                                    id="dataHoraChegada" placeholder="Data/Hora de chegada">
                            </div>
                        </div>
                    

                    </div>
                </div>
            </div>


{{-- INÍCIO DOS CAMPOS PARA VIAGEM NACIONAL ------------------------------------------------------------}}
        
            <div id="isNacional">
                <br>    

                <div class="row justify-content-center">
                    <div class="col-4">
                        <h3 style="color: forestgreen"> <ion-icon name="bus-outline"></ion-icon> VIAGEM NACIONAL </h3>
                        <br>   
                        <h4 style="color: darkolivegreen"> Origem: </h4>
                        <div class="form-group">
                            <label for="selecaoEstadoOrigemNacional" class="control-label">Selecione o estado origem:</label>
                            <br>
                                <select class="select select-bordered w-full max-w-xs {{ $errors->has('selecaoEstadoOrigemNacional') ? 'is-invalid' :''}}" 
                                    id="selecaoEstadoOrigemNacional" name="selecaoEstadoOrigemNacional" onChange="carregarCidadesOrigemNacional()">

                                </select>
            
                                @if ($errors->has('selecaoEstadoOrigemNacional'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('selecaoEstadoOrigemNacional') }}
                                </div>
                                @endif
                        </div>

                        <div class="form-group">
                            <label for="selecaoCidadeOrigemNacional" class="control-label">Selecione a cidade origem:</label>
                            <br>
                                <select class="select select-bordered w-full max-w-xs {{ $errors->has('selecaoCidadeOrigemNacional') ? 'is-invalid' :''}}" 
                                    id="selecaoCidadeOrigemNacional" name="selecaoCidadeOrigemNacional" >

                                </select>
            
                                @if ($errors->has('selecaoCidadeOrigemNacional'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('selecaoCidadeOrigemNacional') }}
                                </div>
                                @endif
                        </div>

                        <div class="form-group"> 
                            <div id="dataHoraSaida" class="input-append date">
                                <label for="dataHoraSaida" class="control-label">Data/Hora de saída: </label>
                                <input data-format="dd/MM/yyyy hh:mm:ss" type="datetime-local" name="dataHoraSaida"
                                    id="dataHoraSaida" placeholder="Data/Hora de saída">
                            </div>
                        </div>

                        <div>
                            <input style="font-size: 12px" type="text" id="botaoResetOrigemNacional"
                            class="btn btn-outline btn-secondary btn-sm" value="Resetar Origem!" onClick="resetarCampoOrigemNacional()">
                        </div>
                    </div>

                        
                    <div class="col-4">    
                        <br><br>
                        <h4 style="color: darkolivegreen"> Destino: </h4>
                        <div class="form-group">
                            <label for="selecaoEstadoDestinoNacional" class="control-label">Selecione o estado destino</label>
                            <br>
                                <select class="select select-bordered w-full max-w-xs {{ $errors->has('selecaoEstadoDestinoNacional') ? 'is-invalid' :''}}" 
                                    id="selecaoEstadoDestinoNacional" name="selecaoEstadoDestinoNacional" onChange="carregarCidadesDestinoNacional()">


                                </select>
            
                                @if ($errors->has('selecaoEstadoDestinoNacional'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('selecaoEstadoDestinoNacional') }}
                                </div>
                                @endif
                        </div>

                        <div class="form-group">
                            <label for="selecaoCidadeDestinoNacional" class="control-label">Selecione a cidade destino</label>
                            <br>
                                <select class="select select-bordered w-full max-w-xs {{ $errors->has('selecaoCidadeDestinoNacional') ? 'is-invalid' :''}}" 
                                    id="selecaoCidadeDestinoNacional" name="selecaoCidadeDestinoNacional" >

                                </select>
            
                                @if ($errors->has('selecaoCidadeDestinoNacional'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('selecaoCidadeDestinoNacional') }}
                                </div>
                                @endif
                        </div>

                        <div class="form-group"> 
                            <div id="dataHoraChegada" class="input-append date">
                                <label for="dataHoraChegada" class="control-label">Data/Hora de chegada: </label>
                                <input data-format="dd/MM/yyyy hh:mm:ss" type="datetime-local" name="dataHoraChegada"
                                    id="dataHoraChegada" placeholder="Data/Hora de chegada">
                            </div>
                        </div>

                        <div>
                            <input type="text" id="botaoResetDestinoNacional"
                            class="btn btn-outline btn-secondary btn-sm" value="Resetar Destino!" onClick="resetarCampoDestinoNacional()">
                        </div>

                    </div>
                </div>
            </div>
            <br>
            <div class="row justify-content-center">
                <div class="col-4">

                    <div id="btConfirmaRota">
                        <input type="text" 
                            class="btn btn-secondary btn-sm" value="Confirma dados!" onClick="confirmaDados()">
                    </div>

                </div>
                <div class="col-4">
                    <div id="camposFinais" hidden="true">
                        <div class="form-group" >
                            <label for="isReservaHotel" class="control-label">Você vai precisar de reserva de hotel?</label>
                            <br>
                                <select class="select select-bordered select-sm w-full max-w-xs {{ $errors->has('isReservaHotel') ? 'is-invalid' :''}}" 
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
                                <select class="select select-bordered select-sm w-full max-w-xs {{ $errors->has('tipoTransporte') ? 'is-invalid' :''}}" 
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
                                <select class="select select-bordered select-sm w-full max-w-xs {{ $errors->has('veiculoProprio_id') ? 'is-invalid' :''}}" 
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
                            <input style="font-size: 16px" type="submit" class="btn btn-primary btn-sm" value="Cadastrar Rota!">
                        </div>

                    </div>
                </div>
            </div>
        </form>
        
    </div>
    
@endsection


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

        function gerenciaNacionalInternacional(){
            var isViagemInternacional = document.getElementById("isViagemInternacional");
            var botaoConfirmaRota = document.getElementById("btConfirmaRota");

            if(isViagemInternacional.value=="0") {
                resetarCampoOrigemInternacional();
                resetarCampoDestinoInternacional();
                document.getElementById("isNacional").hidden = false;
                document.getElementById("isInternacional").hidden = true;
                botaoConfirmaRota.hidden = false;
                selecaoEstadoOrigemNacional();
                selecaoEstadoDestinoNacional();
                document.getElementById("camposFinais").hidden = true;
                document.getElementById("btSalvarRota").hidden = true;
            }
            else if(isViagemInternacional.value=="1"){
                resetarCampoOrigemNacional();
                resetarCampoDestinoNacional();
                document.getElementById("isNacional").hidden = true;
                document.getElementById("isInternacional").hidden = false;
                botaoConfirmaRota.hidden = false;
                document.getElementById("camposFinais").hidden = true;
                document.getElementById("btSalvarRota").hidden = true;
            }
            else{
                document.getElementById("isNacional").hidden = true;
                document.getElementById("isInternacional").hidden = true;
                botaoConfirmaRota.hidden = true;
                document.getElementById("camposFinais").hidden = true;
                document.getElementById("btSalvarRota").hidden = true;
            }
        }

        function carregarPaises(){

            $.getJSON('/countries', function(data){

                opcaoSelecione = '<option value=" "> Selecione </option>';
                $('#selecaoPaisOrigem').append(opcaoSelecione);
                $('#selecaoPaisDestinoInternacional').append(opcaoSelecione);

                for(i=0; i<data.length; i++){
                    opcao = '<option value="' + data[i].id + '">' + data[i].name + '</option>';
                    
                    $('#selecaoPaisOrigem').append(opcao);
                    $('#selecaoPaisDestinoInternacional').append(opcao);
                }
            });
        }

        function selecaoEstadoOrigemNacional(){

            var idPais = 30;
            
            $.getJSON('/states', function(data){
                
                opcaoSelecione = '<option value=" "> Selecione </option>';
                $('#selecaoEstadoOrigemNacional').append(opcaoSelecione);

                for(i=0; i<data.length; i++){
                    if(data[i].country_id == idPais ){
                        opcao = '<option value="' + data[i].id + '">' + data[i].name + '</option>';
                        
                        $('#selecaoEstadoOrigemNacional').append(opcao);
                    }
                }
            });
        }

        function selecaoEstadoDestinoNacional(){
            
            var idPais = 30;

            $.getJSON('/states', function(data){
                
                opcaoSelecione = '<option value=" "> Selecione </option>';
                $('#selecaoEstadoDestinoNacional').append(opcaoSelecione);

                for(i=0; i<data.length; i++){
                    if(data[i].country_id == idPais ){
                        opcao = '<option value="' + data[i].id + '">' + data[i].name + '</option>';
                        
                        $('#selecaoEstadoDestinoNacional').append(opcao);
                    }
                }
            });
        }

        function carregarCidadesOrigemNacional(){

            document.getElementById("selecaoEstadoOrigemNacional").disabled = true;
            document.getElementById("selecaoCidadeOrigemNacional").disabled = false; 

            var idEstado = document.getElementById("selecaoEstadoOrigemNacional");

            $.getJSON('/cities', function(data){
                
                for(i=0; i<data.length; i++){

                    if(data[i].state_id == idEstado.value ){
                        opcao = '<option value="' + data[i].id + '">' + data[i].name + '</option>';
                        $('#selecaoCidadeOrigemNacional').append(opcao);
                    }
                }
            });
        }

        function carregarCidadesDestinoNacional(){

            document.getElementById("selecaoEstadoDestinoNacional").disabled = true;
            document.getElementById("selecaoCidadeDestinoNacional").disabled = false;

            var idEstado = document.getElementById("selecaoEstadoDestinoNacional");

            $.getJSON('/cities', function(data){
            
            for(i=0; i<data.length; i++){

                if(data[i].state_id == idEstado.value ){
                    opcao = '<option value="' + data[i].id + '">' + data[i].name + '</option>';
                    $('#selecaoCidadeDestinoNacional').append(opcao);
                }
            }
        });
        }

        function carregarCidadesOrigemInternacional(){

            var idEstado = null;
            idEstado = document.getElementById("selecaoEstadoOrigem");

            $.getJSON('/cities', function(data){
                
                for(i=0; i<data.length; i++){

                    if(data[i].state_id == idEstado.value ){
                        opcao = '<option value="' + data[i].id + '">' + data[i].name + '</option>';
                        $('#selecaoCidadeOrigem').append(opcao);
                    }
                }
            });
        }

        function resetarCampoOrigemInternacional(){
            document.getElementById("selecaoPaisOrigem").value ="";
            document.getElementById("selecaoEstadoOrigem").value ="";
            document.getElementById("selecaoContinenteOrigem").selectedIndex = 0;
            $("#selecaoEstadoOrigem").html('');
            $("#selecaoEstadoOrigem").html('<option value="">Selecione</option>');
            document.getElementById("selecaoCidadeOrigem").value ="";
            carregarPaises();
            document.getElementById("selecaoPaisOrigem").disabled = false;
        }

        function resetarCampoDestinoInternacional(){
            document.getElementById("selecaoPaisDestinoInternacional").value ="";
            document.getElementById("selecaoEstadoDestinoInternacional").value ="";
            document.getElementById("selecaoContinenteDestinoInternacional").selectedIndex = 0;
            $("#selecaoEstadoDestinoInternacional").html('');
            $("#selecaoEstadoDestinoInternacional").html('<option value="">Selecione</option>');
            document.getElementById("selecaoCidadeDestinoInternacional").value ="";
            carregarPaises();
            document.getElementById("selecaoPaisDestinoInternacional").disabled = false;
        }

        function resetarCampoOrigemNacional(){
            document.getElementById("selecaoEstadoOrigemNacional").value ="";
            $("#selecaoCidadeOrigemNacional").html('');
            $("#selecaoCidadeOrigemNacional").html('<option value="">Selecione</option>');
            document.getElementById("selecaoCidadeOrigemNacional").value ="";
            document.getElementById("selecaoEstadoOrigemNacional").disabled = false; 
            document.getElementById("selecaoCidadeOrigemNacional").disabled = true; 
            selecaoEstadoOrigemNacional();
            selecaoEstadoDestinoNacional();
        }
        function resetarCampoDestinoNacional(){
            document.getElementById("selecaoEstadoDestinoNacional").value ="";
            $("#selecaoCidadeDestinoNacional").html('');
            $("#selecaoCidadeDestinoNacional").html('<option value="">Selecione</option>');
            document.getElementById("selecaoCidadeDestinoNacional").value ="";
            document.getElementById("selecaoEstadoDestinoNacional").disabled = false; 
            document.getElementById("selecaoCidadeDestinoNacional").disabled = true; 
            selecaoEstadoOrigemNacional();
            selecaoEstadoDestinoNacional();
        }

        function confirmaDados(){
            document.getElementById("camposFinais").hidden = false;
            document.getElementById("btConfirmaRota").hidden = true;
            document.getElementById("btSalvarRota").hidden = false;
        }

                //Assim que a tela carrega, aciona automaticamente essas duas funções ------------------------
        $(function(){
            
            carregarPaises();
            document.getElementById("isNacional").hidden = true;
            document.getElementById("isInternacional").hidden = true;
            document.getElementById("btSalvarRota").hidden = true;
            document.getElementById("btConfirmaRota").hidden = true;
        })
    </script>
@endsection