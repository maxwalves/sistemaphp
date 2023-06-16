@extends('layouts.main')

@section('title', 'Editando: ' . $av->id)
@section('content')



<div style="padding-left: 50px, padding-right: 50px" class="container">
    <div class="row justify-content-between" style="padding-left: 5%">
        
        <div class="col-4">
            <a href="/rotas/rotas/{{ $av->id }}" type="submit" class="btn btn-active btn-ghost"> Voltar!</a>
        </div>

        <div class="col-4">
            <label for="my-modal-3" class="btn btn-active btn-ghost">Como é calculada a diária de alimentação?</label>
        </div>
    </div>
</div>

<input type="checkbox" id="my-modal-3" class="modal-toggle" />
<div class="modal fade bd-example-modal-lg">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
        <label for="my-modal-3" class="btn btn-sm btn-circle absolute right-2 top-2">✕</label>
        <br>
        <h3 class="text-lg font-bold" style="padding-left: 10%">A diária de alimentação é calculada da seguinte forma:</h3>
        <img src="/img/horarios.png" style="width: 100vw; height: 40vh">
    </div>
    
  </div>
</div>

<div id="av-create-container" class="container">
        <div class="col-4">
            <label for="idav" > <strong>AV nº </strong> </label>
            <input style="width: 50px; font-size: 16px; font-weight: bold; color: green" type="text" value="{{ $av->id }}" id="idav" name="idav" disabled>
            <h2> <strong>Data: {{ date('d/m/Y', strtotime($av->dataCriacao)) }}</strong> </h2>
        </div>
        <form action="/avs/enviarGestor/{{ $av->id }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="row justify-content-start" style="padding-left: 5%">

                <div class="col-4" >

                    <div class="form-group">
                        <label for="prioridade" class="control-label">Qual é a Prioridade da sua viagem?</label>
                        <input type="text" class="form-control" id="prioridade" name="prioridade" value="{{ $av->prioridade}}" disabled>
                    </div>

                    <div class="form-group" id="nomeObjetivo" >
                        <label for="objetivo_id" class="control-label">Objetivo da viagem:</label>
                        <br>
                            <select class="select select-bordered w-full max-w-xs" 
                                id="objetivo_id" name="objetivo_id" disabled>
                                <option value="" name=""> Selecione</option>
                                @for($i = 0; $i < count($objetivos); $i++)
                                    <div>
                                        <option value="{{ $objetivos[$i]->id }}" {{ $av->objetivo_id == $objetivos[$i]->id ? "selected='selected'" : ""}}
                                            name="{{ $objetivos[$i]->id }}"> {{ $objetivos[$i] ->nomeObjetivo }} </option>
                                    </div>
                                @endfor
                            </select>
                    </div>

                    <div class="form-group" id="outroObjetivo">
                        <label for="outro" class="control-label">Você seleciou um outro objetivo: </label>
                        <div class="input-group">
                            <input type="text" class="form-control {{ $errors->has('outroObjetivo') ? 'is-invalid' :''}}" 
                            name="outroObjetivo" disabled
                            id="outroObjetivo" placeholder="Outro" value="{{$av->outroObjetivo}}">
                        </div>
                    </div>

                </div>
                <div class="col-4" >

                    <div class="form-group">
                        <label for="banco" class="control-label">Banco</label>
                        <input type="text" class="form-control" name="banco"
                        id="banco" placeholder="Banco" value="{{$av->banco}}" disabled> 
                    </div>

                    <div class="form-group">
                        <label for="agencia" class="control-label">Agência</label>
                        <div class="input-group">
                            <input type="text" class="form-control" name="agencia"
                            id="agencia" placeholder="Agência" value="{{$av->agencia}}" disabled>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="conta" class="control-label">Conta</label>
                        <input type="text" class="form-control" name="conta"
                        id="conta" placeholder="Conta" value="{{$av->conta}}" disabled>
                    </div>

                    <div class="form-group">
                        <label for="pix" class="control-label">Pix</label>
                        <input type="text" class="form-control" name="pix"
                            id="pix" placeholder="Pix" value="{{$av->pix}}" disabled>
                    </div>

                    <div class="form-group">
                        <label for="comentario" class="control-label">Comentários</label>
                        <input type="text" class="form-control" name="comentario"
                            id="comentario" placeholder="Comentário" value="{{$av->comentario}}" disabled>
                    </div>
                </div>
                <div class="col-4" >
                    <div class="form-group">
                        <label for="valorExtraReais" class="control-label">Você vai precisar de valor extra em reais?</label>
                        <input type="number" class="form-control" name="valorExtraReais" onblur="calcular()"
                            id="valorExtraReais" placeholder="Valor Extra em reais" value="{{$av->valorExtraReais}}">
                    </div>

                    <div class="form-group">
                        <label for="valorExtraDolar" class="control-label">Você vai precisar de valor extra em dólar?</label>
                        <input type="number" class="form-control" name="valorExtraDolar" onblur="calcular()"
                            id="valorExtraDolar" placeholder="Valor Extra em dólar" value="{{$av->valorExtraDolar}}">
                    </div>

                    <div class="form-group">
                        <label for="justificativaValorExtra" class="control-label">Justificativa para o valor extra</label>
                        <input type="text" class="form-control" name="justificativaValorExtra"
                            id="justificativaValorExtra" placeholder="Justificativa" value="{{$av->justificativaValorExtra}}">
                    </div>
                    <a href="#" class="btn btn-active btn-warning btn-sm">Calcular</a>
                    <br><br>
                    <div>
                        <input type="submit" class="btn btn-active btn-primary btn-lg" value="Salvar e enviar para o Gestor">
                    </div>
                </div>
            </div>
            <div class="row justify-content-start" style="padding-left: 5%">

                <div class="col-11" >
                    <div class="stats shadow" >
        
                        <div class="stat" >
                        <div class="stat-title">Diárias de alimentação em reais: </div>
                        <div class="stat-value text-secondary" id="valorReais" data-value="{{$av->valorReais}}">R$ {{$av->valorReais}}</div>
                        </div>
                
                        <div class="stat">
                        <div class="stat-figure text-secondary">
                            <div class="avatar">
                              <div class="w-16 rounded-full">
                                <img src="/img/real.png">
                              </div>
                            </div>
                        </div>
                        <div class="stat-title">Total com valor extra em reais:</div>
                        
                        <div class="stat-value text-primary" id="result1"> R$ </div>
                        </div>
                        
                    </div>
                    <div class="stats shadow" >
        
                        <div class="stat">
                            <div class="stat-title">Diárias de alimentação em dólar:</div>
                            <div class="stat-value text-secondary" id="valorDolar" data-value="{{$av->valorDolar}}">$ {{$av->valorDolar}}</div>
                        </div>
                        
                        <div class="stat">
                        <div class="stat-figure text-secondary">
                            <div class="avatar">
                              <div class="w-16 rounded-full">
                                <img src="/img/dolar.png">
                              </div>
                            </div>
                        </div>
                        <div class="stat-title">Total com valor extra em dólar:</div>
                        
                        <div class="stat-value text-primary" id="result2"> $ </div>
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


        function ativaCampo(){
            if(document.getElementById("isVeiculoProprio").value == "1"){
                document.getElementById("selecaoVeiculo").hidden = false;
                document.getElementById("temVeiculoEmpresa").hidden = true;
                document.getElementById("isVeiculoEmpresa").value = 0;
            }else if(document.getElementById("isVeiculoProprio").value == "0"){//Se o campo de outro objetivo tiver algo, faz o contrário
                document.getElementById("selecaoVeiculo").hidden = true;
                document.getElementById("temVeiculoEmpresa").hidden = false;
            }
        }

        function desativarCampoObjetivo(){
            var seletor = document.getElementById("flexSwitchCheckDefault")

            if(seletor.checked == true) {
                document.getElementById("outroObjetivo").hidden = false;
                document.getElementById("nomeObjetivo").hidden = true;
                document.getElementById("outroObjetivo").value = "";
                document.getElementById("nomeObjetivo").name = null;
                document.getElementById("isSelecionado").value = "1";
            } else if(seletor.checked == false){
                document.getElementById("outroObjetivo").hidden = true;
                document.getElementById("nomeObjetivo").hidden = false;
                document.getElementById("outroObjetivo").value = "";
                document.getElementById("isSelecionado").value = "0";
            }
        }
        
        function ativarCampoObjetivoInicial(){
            document.getElementById("nomeObjetivo").hidden = false;
            document.getElementById("outroObjetivo").hidden = true;
        }

        function calcular(){
            var valor1 = parseFloat(document.getElementById("valorReais").getAttribute('data-value'));
            var valor2 = parseFloat(document.getElementById("valorDolar").getAttribute('data-value'));
            var valor3 = parseFloat(document.getElementById('valorExtraReais').value);
            var valor4 = parseFloat(document.getElementById('valorExtraDolar').value);
           
            if(document.getElementById('valorExtraReais').value != ""){
                var somaReais = valor1 + valor3;
            } else{
                var somaReais = 0;
            }
            
            if(document.getElementById('valorExtraDolar').value != ""){
                var somaDolar = valor2 + valor4;
            } else{
                var somaDolar = 0;
            }

            document.getElementById('result1').innerHTML = "R$ " + somaReais;
            document.getElementById('result2').innerHTML = "$ " + somaDolar;
        }  

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            }
        });
        
                //Assim que a tela carrega, aciona automaticamente essas funções ------------------------
        $(function(){
        //Se o campo de outro objetivo for vazio, ativa o campo de seleção de objetivo e desabilita o de outro objetivo
            calcular();
    
            if(document.getElementById("outroObjetivo").value == ""){
                ativarCampoObjetivoInicial();
            }else{//Se o campo de outro objetivo tiver algo, faz o contrário
                document.getElementById("nomeObjetivo").hidden = true;//Desabilita seleção de objetivo
                document.getElementById("outroObjetivo").hidden = false;// Habilita campo de outro objetivo
            }

            //Se veio objetivo do banco, habilita o campo de objetivo
            if(document.getElementById("objetivo_id").value != ""){
                document.getElementById("nomeObjetivo").hidden = false;
                document.getElementById("outroObjetivo").hidden = true;//Desabilita o campo de outro objetivo
            }

            var seletor = document.getElementById("flexSwitchCheckDefault")
            if(seletor.checked == true) {
                document.getElementById("isSelecionado").value = "1";
            } else if(seletor.checked == false){
                document.getElementById("isSelecionado").value = "0";
            }
            
        })  
    </script>
@endsection