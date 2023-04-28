@extends('layouts.main')

@section('title', 'Editando: ' . $rota->id)
@section('content')

<div id="container">
        
        <form action="/rotas/update/{{ $rota->id }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="row justify-content-start">
                <div class="col-3">
                    <a href="/rotas/rotas/{{ $av->id }}" type="submit" class="btn btn-active btn-ghost"> Voltar!</a>
                </div>
                <div class="col-8">
                    <label for="idav"> <strong>Edição da ROTA nº </strong> </label>
                    <input type="text" value="{{ $rota->id }}" id="id" name="id" readonly>
                    <label for="idav"> <strong>Autorização de Viagem nº </strong> </label>
                    <input type="text" style="width: 50px" value="{{ $av->id }}" id="idav" name="idav" readonly>
                    <h2 > <strong>Data: {{ date('d/m/Y', strtotime($av->dataCriacao)) }}</strong> </h2>
                </div>
            </div>

            <div style="padding-left: 100px" class="form-group">

                @if ($errors->has('isViagemInternacional') ||
                        $errors->has('selecaoContinenteOrigem') ||
                        $errors->has('selecaoPaisOrigem') ||
                        $errors->has('selecaoEstadoOrigem') ||
                        $errors->has('selecaoCidadeOrigem') ||
                        $errors->has('selecaoContinenteDestinoInternacional') ||
                        $errors->has('selecaoPaisDestinoInternacional') ||
                        $errors->has('selecaoEstadoDestinoInternacional') ||
                        $errors->has('selecaoCidadeDestinoInternacional') ||
                        $errors->has('selecaoEstadoOrigemNacional') ||
                        $errors->has('selecaoCidadeOrigemNacional') ||
                        $errors->has('selecaoEstadoDestinoNacional') ||
                        $errors->has('selecaoCidadeDestinoNacional') ||
                        $errors->has('tipoTransporte') ||
                        $errors->has('veiculoProprio_id'))
                    <div>
                        <p style="color: red"> <strong>Existem campos pendentes de preenchimento!  Selecione o tipo de viagem e verifique os campos!</strong></p>
                    </div>
                @endif
            </div>
            
            <div class="row justify-content-center">
                
                    <ul class="steps">
                        <li class="step step-primary">Origem</li>
                        <li class="step step-neutral" href=""> VOCÊ ESTÁ AQUI</li>
                        <li class="step step-primary" >Origem</li>
                    </ul>
                
                    <div class="form-group" style="padding-left: 10%">
                        <label for="isViagemInternacional" class="control-label">A viagem será internacional?</label>
                        <br>
                            <select class="select select-bordered w-full max-w-xs {{ $errors->has('isViagemInternacional') ? 'is-invalid' :''}}" 
                                id="isViagemInternacional" name="isViagemInternacional" onChange="gerenciaNacionalInternacional()" >
                                <option value="" name=""> Selecione</option>
                                <option value="0" name="0" {{ $rota->isViagemInternacional == "0" ? "selected='selected'" : ""}}> Não</option>
                                <option value="1" name="1" {{ $rota->isViagemInternacional == "1" ? "selected='selected'" : ""}}> Sim</option>
                            </select>

                            @if ($errors->has('isViagemInternacional'))
                            <div class="invalid-feedback">
                                {{ $errors->first('isViagemInternacional') }}
                            </div>
                            @endif
                    </div>
            </div>
            <div id="isInternacional">
                <br>
                        
                <div class="row justify-content-center">
                    <div class="col-5">
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
                                    <option value="1" name="1" {{ $rota->continenteOrigemInternacional == "1" ? "selected='selected'" : ""}}> América Latina ou América Central</option>
                                    <option value="2" name="2" {{ $rota->continenteOrigemInternacional == "2" ? "selected='selected'" : ""}}> América do Norte</option>
                                    <option value="3" name="3" {{ $rota->continenteOrigemInternacional == "3" ? "selected='selected'" : ""}}> Europa</option>
                                    <option value="4" name="4" {{ $rota->continenteOrigemInternacional == "4" ? "selected='selected'" : ""}}> África</option>
                                    <option value="5" name="5" {{ $rota->continenteOrigemInternacional == "5" ? "selected='selected'" : ""}}> Ásia</option>
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
                                id="selecaoPaisOrigem" name="selecaoPaisOrigem" >
                                <option value="{{ $rota->paisOrigemInternacional}}" selected></option>

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
                                id="selecaoEstadoOrigem" name="selecaoEstadoOrigem" value="{{ $rota->estadoOrigemInternacional}}">

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
                                id="selecaoCidadeOrigem" name="selecaoCidadeOrigem" value="{{ $rota->cidadeOrigemInternacional}}">
            
                                @if ($errors->has('selecaoCidadeOrigem'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('selecaoCidadeOrigem') }}
                                </div>
                                @endif
                        </div>

                        <div>
                            <input type="text" id="botaoResetOrigemInternacional"
                            class="btn btn-outline btn-secondary btn-xs" value="Resetar Origem!" onClick="resetarCampoOrigemInternacional()">
                        </div>

                        

                        <div class="form-group"> 
                            <div id="dataHoraSaidaInternacional" class="input-append date">
                                <label for="dataHoraSaidaInternacional" class="control-label">Data/Hora de saída: </label>
                                <input data-format="dd/MM/yyyy hh:mm:ss" type="datetime-local" name="dataHoraSaidaInternacional"
                                    id="dataHoraSaidaInternacional" placeholder="Data/Hora de saída" value="{{ $rota->isViagemInternacional == '1' ? $rota->dataHoraSaida : ''}}">
                            </div>
                        </div>
                    </div>
                    <div class="col-5">
                        {{-- CAMPOS DE DETINO INTERNACIONAL ---------------------------------}}
                        <br><br>
                        <h4 style="color: crimson"> Destino: </h4>
                        <div class="form-group">
                            <label for="selecaoContinenteDestinoInternacional" class="control-label">Selecione o continente destino</label>
                            <br>
                                <select class="select select-bordered select-sm w-full max-w-xs {{ $errors->has('selecaoContinenteDestinoInternacional') ? 'is-invalid' :''}}" 
                                    id="selecaoContinenteDestinoInternacional" name="selecaoContinenteDestinoInternacional" >
                                    <option value="0" name="0" > Selecione</option>
                                    <option value="1" name="1" {{ $rota->continenteDestinoInternacional == "1" ? "selected='selected'" : ""}}> América Latina ou América Central</option>
                                    <option value="2" name="2" {{ $rota->continenteDestinoInternacional == "2" ? "selected='selected'" : ""}}> América do Norte</option>
                                    <option value="3" name="3" {{ $rota->continenteDestinoInternacional == "3" ? "selected='selected'" : ""}}> Europa</option>
                                    <option value="4" name="4" {{ $rota->continenteDestinoInternacional == "4" ? "selected='selected'" : ""}}> África</option>
                                    <option value="5" name="5" {{ $rota->continenteDestinoInternacional == "5" ? "selected='selected'" : ""}}> Ásia</option>
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
                                <option value="{{ $rota->paisDestinoInternacional}}" selected></option>

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
                                id="selecaoEstadoDestinoInternacional" name="selecaoEstadoDestinoInternacional" value="{{ $rota->estadoDestinoInternacional}}">
            
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
                                id="selecaoCidadeDestinoInternacional" name="selecaoCidadeDestinoInternacional" value="{{ $rota->cidadeOrigemInternacional}}">
            
                                @if ($errors->has('selecaoCidadeDestinoInternacional'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('selecaoCidadeDestinoInternacional') }}
                                </div>
                                @endif
                        </div>

                        <div>
                            <input  type="text" id="botaoResetDestinoInternacional"
                            class="btn btn-outline btn-secondary btn-xs" value="Resetar Destino!" onClick="resetarCampoDestinoInternacional()">
                        </div>

                        

                        <div class="form-group"> 
                            <div id="dataHoraChegadaInternacional" class="input-append date">
                                <label for="dataHoraChegadaInternacional" class="control-label">Data/Hora de chegada: </label>
                                <input data-format="dd/MM/yyyy hh:mm:ss" type="datetime-local" name="dataHoraChegadaInternacional"
                                    id="dataHoraChegadaInternacional" placeholder="Data/Hora de chegada" value="{{ $rota->isViagemInternacional == '1' ? $rota->dataHoraChegada : ''}}">
                            </div>
                        </div>
                    

                    </div>
                </div>
            </div>


{{-- INÍCIO DOS CAMPOS PARA VIAGEM NACIONAL ------------------------------------------------------------}}
        
            <div id="isNacional">
                <br>    

                <div class="row justify-content-center">
                    <div class="col-5">
                        <h3 style="color: forestgreen"> <ion-icon name="bus-outline"></ion-icon> VIAGEM NACIONAL </h3>
                        <br>   
                        <h4 style="color: darkolivegreen"> Origem: </h4>
                        <div class="form-group">
                            <label for="selecaoEstadoOrigemNacional" class="control-label">Selecione o estado origem:</label>
                            <br>
                                <select class="select select-bordered w-full max-w-xs {{ $errors->has('selecaoEstadoOrigemNacional') ? 'is-invalid' :''}}" 
                                    id="selecaoEstadoOrigemNacional" name="selecaoEstadoOrigemNacional" onChange="carregarCidadesOrigemNacional()">
                                    <option value="{{ $rota->estadoOrigemNacional}}" selected></option>
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
                                    <option value="{{ $rota->cidadeOrigemNacional}}" selected></option>
                                </select>
            
                                @if ($errors->has('selecaoCidadeOrigemNacional'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('selecaoCidadeOrigemNacional') }}
                                </div>
                                @endif
                        </div>

                        <div class="form-group"> 
                            <div id="dataHoraSaidaNacional" class="input-append date">
                                <label for="dataHoraSaidaNacional" class="control-label">Data/Hora de saída: </label>
                                <input data-format="dd/MM/yyyy hh:mm:ss" type="datetime-local" name="dataHoraSaidaNacional"
                                    id="dataHoraSaidaNacional" placeholder="Data/Hora de saída" value="{{ $rota->isViagemInternacional == '0' ? $rota->dataHoraSaida : ''}}">
                            </div>
                        </div>

                        <div>
                            <input type="text" id="botaoResetOrigemNacional"
                            class="btn btn-outline btn-secondary btn-xs" value="Resetar Origem!" onClick="resetarCampoOrigemNacional()">
                        </div>
                    </div>

                        
                    <div class="col-5">    
                        <br><br>
                        <h4 style="color: darkolivegreen"> Destino: </h4>
                        <div class="form-group">
                            <label for="selecaoEstadoDestinoNacional" class="control-label">Selecione o estado destino</label>
                            <br>
                                <select class="select select-bordered w-full max-w-xs {{ $errors->has('selecaoEstadoDestinoNacional') ? 'is-invalid' :''}}" 
                                    id="selecaoEstadoDestinoNacional" name="selecaoEstadoDestinoNacional" onChange="carregarCidadesDestinoNacional()">
                                    <option value="{{ $rota->estadoDestinoNacional}}" selected></option>

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
                                    <option value="{{ $rota->cidadeDestinoNacional}}" selected></option>
                                </select>
            
                                @if ($errors->has('selecaoCidadeDestinoNacional'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('selecaoCidadeDestinoNacional') }}
                                </div>
                                @endif
                        </div>

                        <div class="form-group"> 
                            <div id="dataHoraChegadaNacional" class="input-append date">
                                <label for="dataHoraChegadaNacional" class="control-label">Data/Hora de chegada: </label>
                                <input data-format="dd/MM/yyyy hh:mm:ss" type="datetime-local" name="dataHoraChegadaNacional"
                                    id="dataHoraChegadaNacional" placeholder="Data/Hora de chegada" value="{{ $rota->isViagemInternacional == '0' ? $rota->dataHoraChegada : ''}}">
                            </div>
                        </div>

                        <div>
                            <input type="text" id="botaoResetDestinoNacional"
                            class="btn btn-outline btn-secondary btn-xs" value="Resetar Destino!" onClick="resetarCampoDestinoNacional()">
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
                                    <option value="0" name="0" {{ $rota->isReservaHotel == "0" ? "selected='selected'" : ""}}> Não</option>
                                    <option value="1" name="1" {{ $rota->isReservaHotel == "1" ? "selected='selected'" : ""}}> Sim</option>
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
                                    <option value="0" name="0" {{ $rota->isOnibusLeito == "1" ? "selected='selected'" : ""}}> Onibus Leito</option>
                                    <option value="1" name="1" {{ $rota->isOnibusConvencional == "1" ? "selected='selected'" : ""}}> Onibus convencional</option>
                                    <option value="2" name="2" {{ $rota->isVeiculoProprio == "1" ? "selected='selected'" : ""}}> Veículo próprio</option>
                                    <option value="3" name="3" {{ $rota->isVeiculoEmpresa == "1" ? "selected='selected'" : ""}}> Veículo do Paranacidade</option>
                                    <option value="4" name="4" {{ $rota->isAereo == "1" ? "selected='selected'" : ""}}> Avião</option>
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
                            <input style="font-size: 16px" type="submit" class="btn btn-primary btn-sm" value="Salvar Rota!">
                        </div>

                    </div>
                </div>
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

        function gerenciaNacionalInternacional(){
            var isViagemInternacional = document.getElementById("isViagemInternacional");
            var botaoConfirmaRota = document.getElementById("btConfirmaRota");

            if(isViagemInternacional.value=="0") {
                resetarCampoOrigemInternacional();
                resetarCampoDestinoInternacional();
                document.getElementById("isNacional").hidden = false;
                document.getElementById("isInternacional").hidden = true;
                botaoConfirmaRota.hidden = false;
                popularEstadoOrigemNacional();
                popularEstadoDestinoNacional();
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
        
        function carregarPaisOrigem(){

            var id = document.getElementById("selecaoPaisOrigem").value;
            $("#selecaoPaisOrigem").html('');

            $.getJSON('/country/' + id, function(data){

                    for(i=0; i<data.length; i++){
                        opcao = '<option value="' + data[i].id + '">' + data[i].name + '</option>';
                        
                        $('#selecaoPaisOrigem').append(opcao);
                    }
                });

                $.getJSON('/countries', function(data){


                    for(i=0; i<data.length; i++){
                        opcao = '<option value="' + data[i].id + '">' + data[i].name + '</option>';
                        
                        $('#selecaoPaisOrigem').append(opcao);
                    }
                });
        }

        function carregarPaisDestino(){

            var id = document.getElementById("selecaoPaisDestinoInternacional").value;
            $("#selecaoPaisDestinoInternacional").html('');

             $.getJSON('/country/' + id, function(data){

                for(i=0; i<data.length; i++){
                    opcao = '<option value="' + data[i].id + '">' + data[i].name + '</option>';
                    
                    $('#selecaoPaisDestinoInternacional').append(opcao);
                }
            });

            $.getJSON('/countries', function(data){


                for(i=0; i<data.length; i++){
                    opcao = '<option value="' + data[i].id + '">' + data[i].name + '</option>';
                    
                    $('#selecaoPaisDestinoInternacional').append(opcao);
                }
            });
        }

        function popularEstadoOrigemNacional(){

            var idPais = 30;
            
            var nomeEstado = document.getElementById("selecaoEstadoOrigemNacional").value;
            $("#selecaoEstadoOrigemNacional").html('');

            $.getJSON('/states', function(data){
                
                opcaoSelecione = '<option value=" "> Selecione </option>';
                $('#selecaoEstadoOrigemNacional').append(opcaoSelecione);

                for(i=0; i<data.length; i++){
                    if(data[i].country_id == idPais ){
                        if(data[i].name == nomeEstado){
                            var valor = "{'id':'" + data[i].id + "', 'name':'" + data[i].name + "'}";

                            opcao = '<option value="' + valor + '" selected>' + data[i].name + '</option>';

                            $('#selecaoEstadoOrigemNacional').append(opcao);
                            carregarCidadesOrigemNacional();
                        }
                        else{
                            var valor = "{'id':'" + data[i].id + "', 'name':'" + data[i].name + "'}";

                            opcao = '<option value="' + valor + '">' + data[i].name + '</option>';

                            $('#selecaoEstadoOrigemNacional').append(opcao);
                        }
                    }
                }
            });
        }

        function popularEstadoDestinoNacional(){
            
            var idPais = 30;

            var nomeEstado = document.getElementById("selecaoEstadoDestinoNacional").value;
            $("#selecaoEstadoDestinoNacional").html('');

            $.getJSON('/states', function(data){
                
                opcaoSelecione = '<option value=" "> Selecione </option>';
                $('#selecaoEstadoDestinoNacional').append(opcaoSelecione);

                for(i=0; i<data.length; i++){
                    if(data[i].country_id == idPais ){
                        if(data[i].name == nomeEstado){
                            var valor = "{'id':'" + data[i].id + "', 'name':'" + data[i].name + "'}";

                            opcao = '<option value="' + valor + '" selected>' + data[i].name + '</option>';

                            $('#selecaoEstadoDestinoNacional').append(opcao);
                            carregarCidadesDestinoNacional();
                        }
                        else{
                            var valor = "{'id':'" + data[i].id + "', 'name':'" + data[i].name + "'}";

                            opcao = '<option value="' + valor + '">' + data[i].name + '</option>';
                            
                            $('#selecaoEstadoDestinoNacional').append(opcao);
                        }
                    }
                }
            });
        }

        function carregarCidadesOrigemNacional(){

            document.getElementById("selecaoEstadoOrigemNacional").disabled = false;
            document.getElementById("selecaoCidadeOrigemNacional").disabled = false; 

            var nomeCidade = document.getElementById("selecaoCidadeOrigemNacional").value;//Recupera o valor que ta salvo no banco

            var idEstado = document.getElementById("selecaoEstadoOrigemNacional").value;//Recebe o valur como String
            var resultado = idEstado.replace(/'/g, "\"");//Adiciona as aspas para deixar no formato JSON
            var objeto = JSON.parse(resultado);//Transforma em JSON
            
            $("#selecaoCidadeOrigemNacional").html('');
            $("#selecaoCidadeOrigemNacional").html('<option value="">Selecione</option>');
            document.getElementById("selecaoCidadeOrigemNacional").value ="";

            $.getJSON('/cities', function(data){
                
                for(i=0; i<data.length; i++){

                    if(data[i].state_id == objeto.id ){
                        if(data[i].name == nomeCidade){
                            opcao = '<option value="' + data[i].name + '" selected>' + data[i].name + '</option>';
                            $('#selecaoCidadeOrigemNacional').append(opcao);
                        }
                        else{
                            opcao = '<option value="' + data[i].name + '">' + data[i].name + '</option>';
                            $('#selecaoCidadeOrigemNacional').append(opcao);
                        }
                    }
                }
            });
        }

        function carregarCidadesDestinoNacional(){

            document.getElementById("selecaoEstadoDestinoNacional").disabled = false;
            document.getElementById("selecaoCidadeDestinoNacional").disabled = false;

            var nomeCidade = document.getElementById("selecaoCidadeDestinoNacional").value;//Recupera o valor que ta salvo no banco

            var idEstado = document.getElementById("selecaoEstadoDestinoNacional").value;//Recebe o valur como String
            var resultado = idEstado.replace(/'/g, "\"");//Adiciona as aspas para deixar no formato JSON
            var objeto = JSON.parse(resultado);//Transforma em JSON

            $("#selecaoCidadeDestinoNacional").html('');
            $("#selecaoCidadeDestinoNacional").html('<option value="">Selecione</option>');
            document.getElementById("selecaoCidadeDestinoNacional").value ="";
            
            $.getJSON('/cities', function(data){
            
            for(i=0; i<data.length; i++){

                if(data[i].state_id == objeto.id ){
                    if(data[i].name == nomeCidade){
                            opcao = '<option value="' + data[i].name + '" selected>' + data[i].name + '</option>';
                            $('#selecaoCidadeDestinoNacional').append(opcao);
                    }
                    else{
                        opcao = '<option value="' + data[i].name + '">' + data[i].name + '</option>';
                        $('#selecaoCidadeDestinoNacional').append(opcao);
                    }
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
            popularEstadoOrigemNacional();
            popularEstadoDestinoNacional();
        }
        function resetarCampoDestinoNacional(){
            document.getElementById("selecaoEstadoDestinoNacional").value ="";
            $("#selecaoCidadeDestinoNacional").html('');
            $("#selecaoCidadeDestinoNacional").html('<option value="">Selecione</option>');
            document.getElementById("selecaoCidadeDestinoNacional").value ="";
            document.getElementById("selecaoEstadoDestinoNacional").disabled = false; 
            document.getElementById("selecaoCidadeDestinoNacional").disabled = true; 
            popularEstadoOrigemNacional();
            popularEstadoDestinoNacional();
        }

        function confirmaDados(){
            document.getElementById("camposFinais").hidden = false;
            document.getElementById("btConfirmaRota").hidden = true;
            document.getElementById("btSalvarRota").hidden = false;
        }

                //Assim que a tela carrega, aciona automaticamente essas duas funções ------------------------
        $(function(){
            
            //carregarPaises();
            carregarPaisOrigem();
            carregarPaisDestino();
            gerenciaNacionalInternacional();
            document.getElementById("btSalvarRota").hidden = true;
        })
    </script>
@endsection