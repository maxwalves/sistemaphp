@extends('layouts.main')

@section('title', 'Cadastrar nova rota')
@section('content')

    <div id="container">
        
        <form action="/rotas" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row justify-content-start">
                <div class="col-3">
                    <a href="/rotas/rotas/{{ $av->id }}" type="submit" class="btn btn-success btn-sm"> Voltar!</a>
                </div>
                <div class="col-8">
                    <label for="idav"> <strong>Cadastro de nova rota da Autorização de Viagem nº </strong> </label>
                    <input type="text" style="width: 50px" value="{{ $av->id }}" id="idav" name="idav" readonly>
    
                    <h2> <strong>Objetivo da Autorização de Viagem: {{ date('d/m/Y', strtotime($av->dataCriacao)) }}</strong> </h2>
                </div>
            </div>
            <div style="padding-left: 50px" class="form-group">
                
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
                        $errors->has('dataHoraSaidaInternacional') ||
                        $errors->has('dataHoraChegadaInternacional') ||
                        $errors->has('dataHoraSaidaNacional') ||
                        $errors->has('dataHoraChegadaNacional') ||
                        $errors->has('veiculoProprio_id'))
                    <div>
                        <p style="color: red"> <strong>Alguns campos não foram preenchidos!</strong></p>
                        <p style="color: red"> <strong>Selecione o tipo de viagem e verifique os campos!</strong></p>
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
                                <option value="0" name="0" {{ $isOrigemNacional == true ? "selected='selected'" : ""}}> Não</option>
                                <option value="1" name="1" > Sim</option>
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
                                <h3 style="color: brown"> Obs: Caso não possua Estado/Província, preencha com o nome da cidade.</h3>

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
                            <input type="text" id="botaoResetOrigemInternacional"
                            class="btn btn-outline btn-secondary btn-xs" value="Resetar Origem!" onClick="resetarCampoOrigemInternacional()">
                        </div>

                        <br>

                        <div class="form-group"> 
                            <div id="dataHoraSaidaInternacional" class="input-append date" >
                                <label for="dataHoraSaidaInternacional" class="control-label">Data/Hora de saída: </label>
                                <input data-format="dd/MM/yyyy hh:mm:ss" type="datetime-local" name="dataHoraSaidaInternacional" style="border-width: 1px; border-color: black"
                                    id="dataHoraSaidaInternacional" placeholder="Data/Hora de saída" class="{{ $errors->has('dataHoraSaidaInternacional') ? 'is-invalid' :''}}">

                                @if ($errors->has('dataHoraSaidaInternacional'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('dataHoraSaidaInternacional') }}
                                </div>
                                @endif
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
                                <h3 style="color: brown"> Obs: Caso não possua Estado/Província, preencha com o nome da cidade.</h3>
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
                            <input type="text" id="botaoResetDestinoInternacional"
                            class="btn btn-outline btn-secondary btn-xs" value="Resetar Destino!" onClick="resetarCampoDestinoInternacional()">
                        </div>

                        <br>

                        <div class="form-group">
                            <div id="dataHoraChegadaInternacional" class="input-append date">
                                <label for="dataHoraChegadaInternacional" class="control-label">Data/Hora de chegada: </label>
                                <input data-format="dd/MM/yyyy hh:mm:ss" type="datetime-local" name="dataHoraChegadaInternacional" style="border-width: 1px; border-color: black"
                                    id="dataHoraChegadaInternacional" placeholder="Data/Hora de chegada" class="{{ $errors->has('dataHoraChegadaInternacional') ? 'is-invalid' :''}}">

                                @if ($errors->has('dataHoraChegadaInternacional'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('dataHoraChegadaInternacional') }}
                                </div>
                                @endif
                            </div>
                        </div>

                    </div>
                </div>
            </div>


{{-- INÍCIO DOS CAMPOS PARA VIAGEM NACIONAL ------------------------------------------------------------}}
        
            <div id="isNacional">
                <br>    

                <div class="row justify-content-center">
                    <div class="col-12 col-xl-5">
                        <h3 style="color: forestgreen"> <ion-icon name="bus-outline"></ion-icon> VIAGEM NACIONAL </h3>
                        <br>   
                        <h4 style="color: darkolivegreen"> Origem: </h4>
                        <div class="form-group">
                            <label for="selecaoEstadoOrigemNacional" class="control-label">Selecione o estado origem:</label>
                            <br>
                            
                                <select class="select select-bordered w-full max-w-xs {{ $errors->has('selecaoEstadoOrigemNacional') ? 'is-invalid' :''}}" 
                                    id="selecaoEstadoOrigemNacional" name="selecaoEstadoOrigemNacional" onChange="carregarCidadesOrigemNacional()">
                                    @if($ultimaRotaSetada !=null)
                                        <option value="{{ $ultimaRotaSetada->estadoDestinoNacional}}" selected></option>
                                    @endif
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
                                    @if($ultimaRotaSetada !=null)
                                        <option value="{{ $ultimaRotaSetada->cidadeDestinoNacional}}" selected></option>
                                    @endif
                                </select>
            
                                @if ($errors->has('selecaoCidadeOrigemNacional'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('selecaoCidadeOrigemNacional') }}
                                </div>
                                @endif
                        </div>

                        <br>

                        <div class="form-group">
                            
                            <div id="dataHoraSaidaNacional" class="input-append date">
                                <label for="dataHoraSaidaNacional" class="control-label">Data/Hora de saída: </label>
                                <input data-format="dd/MM/yyyy hh:mm:ss" type="datetime-local" name="dataHoraSaidaNacional" style="border-width: 1px; border-color: black"
                                    id="dataHoraSaidaNacional" placeholder="Data/Hora de saída" class="{{ $errors->has('dataHoraSaidaNacional') ? 'is-invalid' :''}}">

                                @if ($errors->has('dataHoraSaidaNacional'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('dataHoraSaidaNacional') }}
                                </div>
                                @endif
                            </div>
                            <div class="form-text" id="basic-addon4" style="color: green">Lembre-se de informar a hora de saída!</div>
                        </div>

                        <div>
                            <input type="text" id="botaoResetOrigemNacional"
                            class="btn btn-outline btn-secondary btn-xs" value="Resetar Origem!" onClick="resetarCampoOrigemNacional()">
                        </div>
                    </div>

                        
                    <div class="col-12 col-xl-5">    
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
                                <input type="text" id="cidadeOrigemGeral" name="cidadeOrigemGeral" value="{{ $rotaOriginal->cidadeOrigemNacional }}" hidden="true">

                                <select class="select select-bordered w-full max-w-xs {{ $errors->has('selecaoCidadeDestinoNacional') ? 'is-invalid' :''}}" 
                                    id="selecaoCidadeDestinoNacional" name="selecaoCidadeDestinoNacional" onChange="verificaSeCidadeOrigem()">

                                </select>
                                
            
                                @if ($errors->has('selecaoCidadeDestinoNacional'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('selecaoCidadeDestinoNacional') }}
                                </div>
                                @endif
                        </div>

                        <br>

                        <div class="form-group"> 
                            <div id="dataHoraChegadaNacional" class="input-append date">
                                <label for="dataHoraChegadaNacional" class="control-label">Data/Hora de chegada: </label>
                                <input data-format="dd/MM/yyyy hh:mm:ss" type="datetime-local" name="dataHoraChegadaNacional" style="border-width: 1px; border-color: black"
                                    id="dataHoraChegadaNacional" placeholder="Data/Hora de chegada" class="{{ $errors->has('dataHoraChegadaNacional') ? 'is-invalid' :''}}">

                                @if ($errors->has('dataHoraChegadaNacional'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('dataHoraChegadaNacional') }}
                                </div>
                                @endif
                            </div>
                            <div class="form-text" id="basic-addon4" style="color: green">Lembre-se de informar a hora de chegada!</div>
                        </div>

                        <div>
                            <input type="text" id="botaoResetDestinoNacional"
                            class="btn btn-outline btn-secondary btn-xs" value="Resetar Destino!" onClick="resetarCampoDestinoNacional()">
                        </div>

                    </div>

                </div>
                <div class="divider"></div> 
                @if(count( $av->rotas ) == 0 )
                    <div class="row justify-content-center">
                        <div class="col-10">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDefault" 
                                style="height: 20px; width: 40px" onChange="exibeCamposVolta()">
                                <label class="form-check-label" for="flexSwitchCheckDefault" style="padding-left: 10px">O itinerário de volta será igual ao de ida?</label>
                            </div>
                        </div>
                    </div>
                @endif
                <br><br>

                <div class="row justify-content-center" id="isViagemVoltaIgualIda">
                    <div class="col-12 col-xl-5">
                        <div class="form-group"> 
                            <div id="dataHoraSaidaVoltaNacionalDiv" class="input-append date">
                                <label for="dataHoraSaidaVoltaNacional" class="control-label">Data/Hora de saída na volta: </label>
                                <input data-format="dd/MM/yyyy hh:mm:ss" type="datetime-local" name="dataHoraSaidaVoltaNacional" style="border-width: 1px; border-color: black"
                                    id="dataHoraSaidaVoltaNacional" placeholder="Data/Hora de chegada">

                            </div>
                            <div class="form-text" id="basic-addon4" style="color: green">Lembre-se de informar a hora de saída na volta!</div>
                        </div>
                    </div>
                    
                    <div class="col-12 col-xl-5">
                        <div class="form-group"> 
                            <div id="dataHoraChegadaVoltaNacionalDiv" class="input-append date">
                                <label for="dataHoraChegadaVoltaNacional" class="control-label">Data/Hora prevista de chegada na volta: </label>
                                <input data-format="dd/MM/yyyy hh:mm:ss" type="datetime-local" name="dataHoraChegadaVoltaNacional" style="border-width: 1px; border-color: black"
                                    id="dataHoraChegadaVoltaNacional" placeholder="Data/Hora de chegada" >

                            </div>
                            <div class="form-text" id="basic-addon4" style="color: green">Lembre-se de informar a hora de chegada na volta!</div>
                        </div>
                    </div>
                </div>
            </div>
            <br>
            <div class="row justify-content-center" style="background-color: lightgrey">
                
                <div class="col-md-6 offset-md-3">
                                            
                    <div>
                        <div id="camposFinais" hidden="true">
                            <div class="form-group" id="campoHotel">
                                <label for="isReservaHotel" class="control-label">Você vai precisar de reserva de hotel no destino?</label>
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
                                        
                                        @if($ultimaRotaSetada !=null)
                                            <option value="0" name="0" {{ $ultimaRotaSetada->isOnibusLeito == "1" ? "selected='selected'" : ""}}> Onibus Leito</option>
                                            <option value="1" name="1" {{ $ultimaRotaSetada->isOnibusConvencional == "1" ? "selected='selected'" : ""}}> Onibus convencional</option>
                                            <option value="2" name="2" {{ $ultimaRotaSetada->isVeiculoProprio == "1" ? "selected='selected'" : ""}}> Veículo próprio</option>
                                            <option value="3" name="3" {{ $ultimaRotaSetada->isVeiculoEmpresa == "1" ? "selected='selected'" : ""}}> Veículo do Paranacidade</option>
                                            <option value="4" name="4" {{ $ultimaRotaSetada->isAereo == "1" ? "selected='selected'" : ""}}> Avião</option>
                                        @else
                                            <option value="0" name="0"> Onibus Leito</option>
                                            <option value="1" name="1" > Onibus convencional</option>
                                            <option value="2" name="2" > Veículo próprio</option>
                                            <option value="3" name="3" > Veículo do Paranacidade</option>
                                            <option value="4" name="4" > Avião</option>
                                        @endif
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
                                <input style="font-size: 16px" type="submit" class="btn btn-active btn-secondary" value="Cadastrar Rota!">
                            </div>

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
            
            document.getElementById("camposFinais").hidden = false;
            document.getElementById("btSalvarRota").hidden = false;

            if(isViagemInternacional.value=="0") {
                resetarCampoOrigemInternacional();
                resetarCampoDestinoInternacional();
                document.getElementById("isNacional").hidden = false;
                document.getElementById("isInternacional").hidden = true;
    
                popularEstadoOrigemNacional();
                popularEstadoDestinoNacional();
            }
            else if(isViagemInternacional.value=="1"){
                resetarCampoOrigemNacional();
                resetarCampoDestinoNacional();
                document.getElementById("isNacional").hidden = true;
                document.getElementById("isInternacional").hidden = false;
                
            }
            else{
                document.getElementById("isNacional").hidden = true;
                document.getElementById("isInternacional").hidden = true;
                
            }
        }

        function verificaSeCidadeOrigem(){
            var cidadeDestino = document.getElementById("selecaoCidadeDestinoNacional");
            var cidadeOrigemGeral = document.getElementById("cidadeOrigemGeral");

            if(cidadeDestino.value == cidadeOrigemGeral.value){
                document.getElementById("campoHotel").hidden = true;
            }
            else{
                document.getElementById("campoHotel").hidden = false;
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

            $.getJSON('/states', function(data){
                
                opcaoSelecione = '<option value=" "> Selecione </option>';
                $('#selecaoEstadoDestinoNacional').append(opcaoSelecione);

                for(i=0; i<data.length; i++){
                    if(data[i].country_id == idPais ){

                        var valor = "{'id':'" + data[i].id + "', 'name':'" + data[i].name + "'}";

                        opcao = '<option value="' + valor + '">' + data[i].name + '</option>';
                        
                        $('#selecaoEstadoDestinoNacional').append(opcao);
                    }
                }
            });
        }

        function carregarCidadesOrigemNacional(){

            document.getElementById("selecaoEstadoOrigemNacional").disabled = false;
            document.getElementById("selecaoCidadeOrigemNacional").disabled = false; 

            var nomeCidade = document.getElementById("selecaoCidadeOrigemNacional").value;

            var idEstado = document.getElementById("selecaoEstadoOrigemNacional").value;//Recebe o valur como String
            var resultado = idEstado.replace(/'/g, "\"");//Adiciona as aspas para deixar no formato JSON
            var objeto = JSON.parse(resultado);//Transforma em JSON
            
            $("#selecaoCidadeOrigemNacional").html('');
            $("#selecaoCidadeOrigemNacional").html('<option value="">Selecione</option>');
            document.getElementById("selecaoCidadeOrigemNacional").value ="";

            $.getJSON('/cities', function(data){
                
                for(i=0; i<data.length; i++){

                    if(data[i].name == nomeCidade){
                            opcao = '<option value="' + data[i].name + '" selected>' + data[i].name + '</option>';
                            $('#selecaoCidadeOrigemNacional').append(opcao);
                    }
                    else{
                        opcao = '<option value="' + data[i].name + '">' + data[i].name + '</option>';
                        $('#selecaoCidadeOrigemNacional').append(opcao);
                    }
                }
            });
        }

        function carregarCidadesDestinoNacional(){

            document.getElementById("selecaoEstadoDestinoNacional").disabled = false;
            document.getElementById("selecaoCidadeDestinoNacional").disabled = false;

            var idEstado = document.getElementById("selecaoEstadoDestinoNacional").value;//Recebe o valur como String
            var resultado = idEstado.replace(/'/g, "\"");//Adiciona as aspas para deixar no formato JSON
            var objeto = JSON.parse(resultado);//Transforma em JSON

            $("#selecaoCidadeDestinoNacional").html('');
            $("#selecaoCidadeDestinoNacional").html('<option value="">Selecione</option>');
            document.getElementById("selecaoCidadeDestinoNacional").value ="";
            
            $.getJSON('/cities', function(data){
            
            for(i=0; i<data.length; i++){

                if(data[i].state_id == objeto.id ){
                    opcao = '<option value="' + data[i].name + '">' + data[i].name + '</option>';
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

        function exibeCamposVolta(){
            if(document.getElementById("isViagemVoltaIgualIda").hidden == false){
                document.getElementById("isViagemVoltaIgualIda").hidden = true;
            }
            else{
                document.getElementById("isViagemVoltaIgualIda").hidden = false;
            }
        }

                //Assim que a tela carrega, aciona automaticamente essas duas funções ------------------------
        $(function(){
            var isViagemInternacional = document.getElementById("isViagemInternacional");

            carregarPaises();
            document.getElementById("isNacional").hidden = true;
            document.getElementById("isInternacional").hidden = true;
            document.getElementById("btSalvarRota").hidden = true;
            document.getElementById("isViagemVoltaIgualIda").hidden = true;

            if(isViagemInternacional.value=="0"){
                document.getElementById("isNacional").hidden = false;
                gerenciaNacionalInternacional();
            }
            else if(isViagemInternacional.value=="1"){
                document.getElementById("isInternacional").hidden = false;
                gerenciaNacionalInternacional();
            }
            else{
                document.getElementById("isNacional").hidden = true;
                document.getElementById("isInternacional").hidden = true;
            }
        })
    </script>
@endsection