@extends('adminlte::page')

@section('title', 'Concluir Sol. AV')

@section('content_header')
    <h1>Concluir AV</h1>
    <label for="idav" > <strong>AV nº </strong> </label>
    <input style="width: 50px; font-size: 16px; font-weight: bold; color: green" type="text" value="{{ $av->id }}" id="idav" name="idav" disabled>
    <strong>  Data: {{ date('d/m/Y', strtotime($av->dataCriacao)) }}</strong>
@stop

@section('content')
<div>
    <div class="row">
        
        <div class="col-1">
            <a href="/rotas/rotas/{{ $av->id }}" type="submit" class="btn btn-active btn-warning"><i class="fas fa-arrow-left"></i></a>
        </div>

        <div class="col-6">
            <x-adminlte-button label="Dados Básicos da AV" data-toggle="modal" class="bg-purple" data-target="#my-modal-4"/>
            <x-adminlte-button label="Detalhar dias" data-toggle="modal" class="bg-blue" data-target="#my-modal-5"/>
            <x-adminlte-button label="Como é calculada a diária de alimentação?" data-toggle="modal" data-target="#my-modal-3"/>
        </div>
    </div>
</div>

<x-adminlte-modal id="my-modal-3" title="Cálculos" size="xl" theme="teal"
        icon="fas fa-bell" v-centered static-backdrop scrollable>

    <div>
        <br>
        <h3 class="text-lg font-bold" style="padding-left: 10%">A diária de alimentação é calculada da seguinte forma:</h3>
        <img src="/img/horarios.png" style="width: 100%;">
        
    </div>
    <x-slot name="footerSlot">
        <x-adminlte-button theme="danger" label="Fechar" data-dismiss="modal"/>
    </x-slot>

</x-adminlte-modal>

<x-adminlte-modal id="my-modal-4" title="Dados da AV" size="md" theme="teal"
        icon="fas fa-bell" v-centered static-backdrop scrollable>

    <div>
        <div class="form-group" id="nomeObjetivo" >
            <label for="objetivo_id" class="control-label">Objetivo da viagem:</label>
            <br>
            <select class="form-control" 
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
                <input type="text" class="form-control" 
                name="outroObjetivo" disabled
                id="outroObjetivo" placeholder="Outro" value="{{$av->outroObjetivo}}">
            </div>
        </div>

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

    <x-slot name="footerSlot">
        <x-adminlte-button theme="danger" label="Fechar" data-dismiss="modal"/>
    </x-slot>

</x-adminlte-modal>


<x-adminlte-modal id="my-modal-5" title="Detalhamento" size="md" theme="teal"
        icon="fas fa-bell" v-centered static-backdrop scrollable>

        <div class="container"> 
            <div class="stat-title">Controle de diárias:
            </div>
                Mês saída: {{$mesSaidaInicial}} 
                Mês chegada: {{$mesChegadaFinal}} <br>
                <table class="table table-hover table-bordered" style="width: 100%">
                    <thead>
                        <tr>
                            <th>Dias</th>
                            <th>Dados</th>
                        </tr>
                    </thead>
                    <tbody>
        
                        @if($mesSaidaInicial != $mesChegadaFinal)
        
                            @php
                                $data = "$anoSaidaInicial-$mesSaidaInicial-$diaSaidaInicial";
                                $ultimoDiaMes = date('t', strtotime($data));
                                $j=0;
                            @endphp
                            @for($i = $arrayDiasValores[0]['dia']; $i <= $ultimoDiaMes; $i++)
        
                                        @if($i ==  $diaSaidaInicial && $horaSaidaInicial < 12 )
                                            <tr>
                                                <td>
                                                    {{$i}}
                                                </td>
                                                <td> 
                                                    <span class="badge bg-danger">Diária Inteira</span>
                                                    <span class="badge bg-success">R${{$arrayDiasValores[$j]['valor']}}</span>
                                                </td>
                                            </tr>
                                        @endif
                                        @if($i ==  $diaSaidaInicial && $horaSaidaInicial >= 13 && $minutoSaidaInicial > 1)
                                            
                                            <tr>
                                                <td>
                                                    {{$i}}
                                                </td>
                                                <td> 
                                                    <span class="badge bg-warning">Meia Diária</span>
                                                    <span class="badge bg-success">R${{$arrayDiasValores[$j]['valor']}}</span>
                                                </td>
                                            </tr>
                                        @endif
                                        @if($i !=  $diaSaidaInicial && $i !=  $diaChegadaFinal)
                                             
                                            <tr>
                                                <td>
                                                    {{$i}}
                                                </td>
                                                <td> 
                                                    <span class="badge bg-danger">Diária Inteira</span>
                                                    <span class="badge bg-success">R${{$arrayDiasValores[$j]['valor']}}</span> 
                                                </td>
                                            </tr> 
                                        @endif
                                  
                                @php
                                    $j++;
                                @endphp
                            @endfor
                            @for($i = 1; $i <= $diaChegadaFinal; $i++)
        
                                        @if($i ==  $diaSaidaInicial && $horaSaidaInicial < 12 )
                                            <tr>
                                                <td>
                                                    {{$i}}
                                                </td>
                                                <td> 
                                                    <span class="badge bg-danger">Diária Inteira</span>
                                                    <span class="badge bg-success">R${{$arrayDiasValores[$j]['valor']}}</span> 
                                                </td>
                                            </tr>
                                        @endif
                                        @if($i ==  $diaSaidaInicial && $horaSaidaInicial >= 13 && $minutoSaidaInicial > 1)
                                            <tr>
                                                <td>
                                                    {{$i}}
                                                </td>
                                                <td> 
                                                    <span class="badge bg-warning">Meia Diária</span>
                                                    <span class="badge bg-success">R${{$arrayDiasValores[$j]['valor']}}</span> 
                                                </td>
                                            </tr>
                                        @endif
                                        @if($i !=  $diaSaidaInicial && $i !=  $diaChegadaFinal)
                                            <tr>
                                                <td>
                                                    {{$i}}
                                                </td>
                                                <td> 
                                                    <span class="badge bg-danger">Diária Inteira</span>
                                                    <span class="badge bg-success">R${{$arrayDiasValores[$j]['valor']}}</span> 
                                                </td>
                                            </tr>
                                        @endif
        
                                        @if($i ==  $diaChegadaFinal && $horaChegadaFinal >= 13 && $horaChegadaFinal <19)
                                            <tr>
                                                <td>
                                                    {{$i}}
                                                </td>
                                                <td> 
                                                    <span class="badge bg-warning">Meia Diária</span>
                                                    <span class="badge bg-success">R${{$arrayDiasValores[$j]['valor']}}</span> 
                                                </td>
                                            </tr>
                                        @endif
                                        @if($i ==  $diaChegadaFinal && $horaChegadaFinal >=19)
                                            <tr>
                                                <td>
                                                    {{$i}}
                                                </td>
                                                <td> 
                                                    <span class="badge bg-danger">Diária Inteira</span>
                                                    <span class="badge bg-success">R${{$arrayDiasValores[$j]['valor']}}</span> 
                                                </td>
                                            </tr>
                                        @endif
        
                                @php
                                    $j++;
                                @endphp
                            @endfor
                        @else
                                @php
                                    $j=0;
                                @endphp
                            @for($i = $arrayDiasValores[0]['dia']; $i <= $diaChegadaFinal; $i++)
                                
                                        @if($i ==  $diaSaidaInicial && $horaSaidaInicial < 12 )
                                            <tr>
                                                <td>
                                                    {{$i}}
                                                </td>
                                                <td> 
                                                    <span class="badge bg-danger">Diária Inteira</span>
                                                    <span class="badge bg-success">R${{$arrayDiasValores[$j]['valor']}}</span>
                                                </td>
                                            </tr>
                                        @endif
                                        @if(($i ==  $diaSaidaInicial && $horaSaidaInicial > 13) || ($i ==  $diaSaidaInicial && $horaSaidaInicial == 13 && $minutoSaidaInicial >= 1))
                                            <tr>
                                                <td>
                                                    {{$i}}
                                                </td>
                                                <td> 
                                                    <span class="badge bg-warning">Meia Diária</span>
                                                    <span class="badge bg-success">R${{$arrayDiasValores[$j]['valor']}}</span> 
                                                </td>
                                            </tr>
                                        @endif
                                        @if($i !=  $diaSaidaInicial && $i !=  $diaChegadaFinal)
                                            <tr>
                                                <td>
                                                    {{$i}}
                                                </td>
                                                <td> 
                                                    <span class="badge bg-danger">Diária Inteira</span>
                                                    <span class="badge bg-success">R${{$arrayDiasValores[$j]['valor']}}</span> 
                                                </td>
                                            </tr>
                                        @endif
        
                                        @if(($i ==  $diaChegadaFinal && $horaChegadaFinal > 13 && $horaChegadaFinal <=19 && $minutoChegadaFinal == 0) || 
                                        ($i ==  $diaChegadaFinal && $horaChegadaFinal > 13 && $horaChegadaFinal <19) ||
                                        ($i ==  $diaChegadaFinal && $horaChegadaFinal == 13 && $minutoChegadaFinal >= 1 && $horaChegadaFinal <19))
                                            <tr>
                                                <td>
                                                    {{$i}}
                                                </td>
                                                <td> 
                                                    <span class="badge bg-warning">Meia Diária</span>
                                                    <span class="badge bg-success">R${{$arrayDiasValores[$j]['valor']}}</span> 
                                                </td>
                                            </tr>
                                        @endif
                                        @if($diaSaidaInicial != $diaChegadaFinal)
                                            @if(($i ==  $diaChegadaFinal && $horaChegadaFinal >19) || ($i ==  $diaChegadaFinal && $horaChegadaFinal ==19 && $minutoChegadaFinal >= 1))
                                                <tr>
                                                    <td>
                                                        {{$i}}
                                                    </td>
                                                    <td> 
                                                        <span class="badge bg-danger">Diária Inteira</span>
                                                        <span class="badge bg-success">R${{$arrayDiasValores[$j]['valor']}}</span>
                                                    </td>
                                                </tr> 
                                            @endif
                                        @endif
                                    
                                    
                                @php
                                    $j++;
                                @endphp
                            @endfor
                        @endif
                    </tbody>
                </table>
                <br><br>
        </div>
    <x-slot name="footerSlot">
        <x-adminlte-button theme="danger" label="Fechar" data-dismiss="modal"/>
    </x-slot>

</x-adminlte-modal>


<br>
<div >
        <form action="/avs/enviarGestor/{{ $av->id }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="row" style="padding-left: 1%">

                <div class="col-12 col-md-6" >
                    <div class="form-group">
                        <label for="valorExtraReais" class="control-label">Você vai precisar de valor extra em reais?</label>
                        <input type="number" class="form-control" name="valorExtraReais" onblur="calcular()"
                            id="valorExtraReais" placeholder="Valor Extra em reais" value="{{$av->valorExtraReais}}">
                    </div>
                    
                    @if($isInternacional == true)
                        <div class="form-group">
                            <label for="valorExtraDolar" class="control-label">Você vai precisar de valor extra em dólar?</label>
                            <input type="number" class="form-control" name="valorExtraDolar" onblur="calcular()"
                                id="valorExtraDolar" placeholder="Valor Extra em dólar" value="{{$av->valorExtraDolar}}">
                        </div>
                    @endif

                    <div class="form-group">
                        <label for="valorDeducaoReais" class="control-label">Vai ter deduções em reais?</label>
                        <input type="number" class="form-control bg-yellow-300" name="valorDeducaoReais" onblur="calcular()"
                            id="valorDeducaoReais" placeholder="Valor da dedução em reais">
                    </div>

                    @if($isInternacional == true)
                        <div class="form-group">
                            <label for="valorDeducaoDolar" class="control-label">Vai ter deduções em dólar?</label>
                            <input type="number" class="form-control bg-yellow-300" name="valorDeducaoDolar" onblur="calcular()"
                                id="valorDeducaoDolar" placeholder="Valor da dedução em dólar">
                        </div>
                    @endif

                    <a href="#" class="btn btn-active btn-success">Calcular <i class="fas fa-calculator"></i></a>

                    <div class="form-group">
                        <label for="justificativaValorExtra" class="control-label">Justificativas</label>
                        <input type="text" class="form-control" name="justificativaValorExtra"
                            id="justificativaValorExtra" placeholder="Justificativa" value="{{$av->justificativaValorExtra}}">
                    </div>
                    
                    <div>
        
                        <div class="alert alert-info">
                            
                            <h5><i class="icon fas fa-check"></i> Diárias de alimentação em reais: </h5>
                            <div id="valorReais" data-value="{{$av->valorReais}}"> <h2>R$ {{$av->valorReais}} </h2></div>
                        </div>
                        <div class="alert alert-success">
                            
                            <h5><i class="icon fas fa-check"></i> Total após cálculos em reais: </h5>
                            <h3 id="result1"></h3>
                        </div>
                        
                    </div>
                    @if($isInternacional == true)

                        <div class="alert alert-info">
                            
                            <h5><i class="icon fas fa-check"></i> Diárias de alimentação em dólar: </h5>
                            <h3>$ {{$av->valorDolar}}</h3>
                        </div>
                        <div class="alert alert-success">
                            
                            <h5><i class="icon fas fa-check"></i> Total após cálculos em dólar: </h5>
                            <h3 id="result2"></h3>
                        </div>
                        
                    @endif
                    
                    <br>
                    <div class="text-center">
                        <button type="submit" class="btn btn-active btn-primary btn-lg">Enviar <i class="fas fa-paper-plane"></i></button>
                    </div>
                </div>
            </div>
        </form>

</div>
    
@stop

@section('css')
    
@stop

@section('js')
<script type="text/javascript">


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
        var valor3 = parseFloat(document.getElementById('valorExtraReais').value);
        var valor5 = parseFloat(document.getElementById('valorDeducaoReais').value);
        if(document.getElementById('valorExtraReais').value == ""){
            valor3 = 0;
        }
        if(document.getElementById('valorDeducaoReais').value == ""){
            valor5 = 0;
        }
        var somaReais = valor1 + valor3 - valor5;
        document.getElementById('result1').innerHTML = "R$ " + somaReais;

        try {
            var valor2 = parseFloat(document.getElementById("valorDolar").getAttribute('data-value'));
            var valor4 = parseFloat(document.getElementById('valorExtraDolar').value);
            var valor6 = parseFloat(document.getElementById('valorDeducaoDolar').value);
            if(document.getElementById('valorExtraDolar').value == ""){
                valor4 = 0;
            }
            if(document.getElementById('valorDeducaoDolar').value == ""){
                valor6 = 0;
            }
            var somaDolar = valor2 + valor4 - valor6;
            document.getElementById('result2').innerHTML = "$ " + somaDolar;
        } catch (error) {}
        
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
        
    })  
</script>
@stop