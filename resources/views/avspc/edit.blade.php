@extends('layouts.main')

@section('title', 'Editando: ' . $av->id)
@section('content')

<div class="row justify-content-start" style="padding-left: 5%">
    <div class="col-3">
        <a href="/avs/fazerPrestacaoContas/{{ $av->id }}" type="submit" class="btn btn-active btn-ghost"> Voltar!</a>
    </div>
</div>
<div id="av-create-container" class="col-md-6 offset-md-3">
        <h2>Editando: {{ $av->id }}</h2>
        <form action="/avs/update/{{ $av->id }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <input type="text" hidden="true" value="sim" name="isPc" id="isPc">
            <div class="form-group" id="nomeObjetivo" >
                <label for="objetivo_id" class="control-label" required>Qual é o Objetivo da viagem? (selecione)</label>
                <br>
                    <select class="select select-bordered w-full max-w-xs {{ $errors->has('objetivo_id') ? 'is-invalid' :''}}" 
                        id="objetivo_id" name="objetivo_id">
                        <option value="" name=""> Selecione</option>
                        @for($i = 0; $i < count($objetivos); $i++)
                            <div>
                                <option value="{{ $objetivos[$i]->id }}" {{ $av->objetivo_id == $objetivos[$i]->id ? "selected='selected'" : ""}}
                                    name="{{ $objetivos[$i]->id }}"> {{ $objetivos[$i] ->nomeObjetivo }} </option>
                            </div>
                        @endfor
                    </select>

                    @if ($errors->has('objetivo_id'))
                    <div class="invalid-feedback">
                        {{ $errors->first('objetivo_id') }}
                    </div>
                    @endif
            </div>

            <div class="form-group" id="outroObjetivo">
                <label for="outro" class="control-label">Digite outro objetivo: </label>
                <div class="input-group">
                    <input type="text" class="form-control {{ $errors->has('outroObjetivo') ? 'is-invalid' :''}}" 
                    name="outroObjetivo"
                    id="outroObjetivo" placeholder="Outro" value="{{$av->outroObjetivo}}">
                </div>

                @if ($errors->has('outroObjetivo'))
                    <div class="invalid-feedback">
                        {{ $errors->first('outroObjetivo') }}
                    </div>
                @endif
            </div>

            <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDefault" 
                style="height: 20px; width: 40px" onChange="desativarCampoObjetivo()"  {{$av->outroObjetivo != "" ? "checked" : ""}}>
                <label class="form-check-label" for="flexSwitchCheckDefault" style="padding-left: 10px">Não achei o objetivo que desejo na lista!</label>
            </div>
            <input type="boolean" id="isSelecionado" name="isSelecionado" value="0" hidden="true">
            <br>
            <br>

            <div class="form-group">
                <label for="prioridade" class="control-label">Qual é a Prioridade da sua viagem? (selecione)</label><br>
                    <select class="select select-bordered w-full max-w-xs {{ $errors->has('prioridade') ? 'is-invalid' :''}}" 
                        id="prioridade" name="prioridade">
                        <option value="" name=""> Selecione</option>
                        <option value="Alta" {{ $av->prioridade == "Alta" ? "selected='selected'" : ""}} name="Alta"> Alta</option>
                        <option value="Média" {{ $av->prioridade == "Média" ? "selected='selected'" : ""}} name="Média"> Média</option>
                        <option value="Baixa" {{ $av->prioridade == "Baixa" ? "selected='selected'" : ""}} name="Baixa"> Baixa</option>
                    </select>

                    @if ($errors->has('prioridade'))
                    <div class="invalid-feedback">
                        {{ $errors->first('prioridade') }}
                    </div>
                    @endif
            </div>
            
            <div class="form-group">
                <label for="banco" class="control-label">Banco</label><br>
                <input type="number" class="input input-bordered input-secondary w-full max-w-xs" name="banco"
                id="banco" placeholder="Banco" value="{{$av->banco}}"> 
            </div>

            <div class="form-group">
                <label for="agencia" class="control-label">Agência</label><br>
                <div class="input-group">
                    <input type="number" class="input input-bordered input-secondary w-full max-w-xs" name="agencia"
                    id="agencia" placeholder="Agência" value="{{$av->agencia}}">
                </div>
            </div>

            <div class="form-group">
                <label for="conta" class="control-label">Conta</label><br>
                <input type="number" class="input input-bordered input-secondary w-full max-w-xs" name="conta"
                id="conta" placeholder="Conta" value="{{$av->conta}}">
            </div>

            <div class="form-group">
                <label for="pix" class="control-label">Pix</label><br>
                <input type="number" class="input input-bordered input-secondary w-full max-w-xs" name="pix"
                    id="pix" placeholder="Pix" value="{{$av->pix}}">
            </div>

            <div class="form-group">
                <label for="comentario" class="control-label">Comentários</label><br>
                <input type="text" class="input input-bordered input-secondary w-full max-w-xs" name="comentario"
                    id="comentario" placeholder="Comentário" value="{{$av->comentario}}">
            </div>



            <input type="submit" class="btn btn-primary" value="Salvar">

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

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            }
        });
        
                //Assim que a tela carrega, aciona automaticamente essas funções ------------------------
        $(function(){
        //Se o campo de outro objetivo for vazio, ativa o campo de seleção de objetivo e desabilita o de outro objetivo
            
            if(document.getElementById("isVeiculoProprio").value == "1"){
                document.getElementById("selecaoVeiculo").hidden = false;
                document.getElementById("temVeiculoEmpresa").hidden = true;
            }else{//Se o campo de outro objetivo tiver algo, faz o contrário
                document.getElementById("selecaoVeiculo").hidden = true;
            }



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