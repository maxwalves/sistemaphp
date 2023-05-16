@extends('layouts.main')

@section('title', 'Criar Autorização de viagem')
@section('content')

<div class="row justify-content-start" style="padding-left: 5%">
    <div class="col-3">
        <a href="/avs/avs/" type="submit" class="btn btn-active btn-ghost"> Voltar!</a>
    </div>
</div>
<div id="av-create-container" class="col-md-6 offset-md-3">
        <h2>Crie uma autorização de viagem!</h2>
        <form action="/avs/gravarAv" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="form-group" id="nomeObjetivo">
                <label for="objetivo_id" class="control-label" required>Qual é o Objetivo da viagem? (selecione)</label>
                <br>
                    <select class="select select-bordered w-full max-w-xs {{ $errors->has('objetivo_id') ? 'is-invalid' :''}}" 
                        id="objetivo_id" name="objetivo_id">
                        <option value="" name=""> Selecione</option>
                        @for($i = 0; $i < count($objetivos); $i++)
                            <div>
                                <option value="{{ $objetivos[$i]->id }}" 
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

            <div class="form-group " id="outroObjetivoCampo">
                <label for="outro" class="control-label">Digite outro objetivo: </label>
                <div class="input-group">
                    <input type="text" class="form-control {{ $errors->has('outroObjetivo') ? 'is-invalid' :''}}" 
                    name="outroObjetivo"
                    id="outroObjetivo" placeholder="Outro">
                </div>

                @if ($errors->has('outroObjetivo'))
                    <div class="invalid-feedback">
                        {{ $errors->first('outroObjetivo') }}
                    </div>
                @endif
            </div>

            <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDefault" 
                style="height: 20px; width: 40px" onChange="desativarCampoObjetivo()">
                <label class="form-check-label" for="flexSwitchCheckDefault" style="padding-left: 10px">Não achei o objetivo que desejo na lista!</label>
            </div>
            <input type="boolean" id="isSelecionado" name="isSelecionado" value="0" hidden="true">
            <br>
            <br>
            <div class="form-group">
                <label for="prioridade" class="control-label" required>Qual é a Prioridade da sua viagem? (selecione)</label>
                <br>
                    <select class="select select-bordered w-full max-w-xs {{ $errors->has('prioridade') ? 'is-invalid' :''}}" 
                        id="prioridade" name="prioridade">
                        <option value="" name=""> Selecione</option>
                        <option value="Alta" name="Alta"> Alta</option>
                        <option value="Média" name="Média"> Média</option>
                        <option value="Baixa" name="Baixa"> Baixa</option>
                    </select>

                    @if ($errors->has('prioridade'))
                    <div class="invalid-feedback">
                        {{ $errors->first('prioridade') }}
                    </div>
                    @endif
            </div>

            <div class="form-group">
                <label for="banco" class="control-label">Banco</label><br>
                <input type="text" class="input input-bordered input-secondary w-full max-w-xs" name="banco"
                id="banco" placeholder="Banco">
            </div>

            <div class="form-group">
                <label for="agencia" class="control-label">Agência</label><br>
                <input type="text" class="input input-bordered input-secondary w-full max-w-xs" name="agencia"
                    id="agencia" placeholder="Agência">
                
            </div>

            <div class="form-group">
                <label for="conta" class="control-label">Conta</label><br>
                <input type="text" class="input input-bordered input-secondary w-full max-w-xs" name="conta"
                id="conta" placeholder="Conta">
            </div>

            <div class="form-group">
                <label for="pix" class="control-label">Pix</label><br>
                <input type="text" class="input input-bordered input-secondary w-full max-w-xs" name="pix"
                    id="pix" placeholder="Pix">
            </div>

            <div class="form-group">
                <label for="comentario" class="control-label">Comentários</label><br>
                <input type="text" class="input input-bordered input-secondary w-full max-w-xs" name="comentario"
                    id="comentario" placeholder="Comentário">
            </div>

            <br>

            <div class="row justify-content-center" style="background-color: lightgrey">
                <div id="btSalvarAv">
                    <input style="font-size: 14px" type="submit" class="btn btn-active btn-secondary" value="Salvar e escolher itinerário!">
                </div>
            </div>
        </form>

    </div>
    
@endsection




{{-- Para implementação futura de AJAX --}} 
@section('javascript')
    <script type="text/javascript">

        function desativarCampo(){
            var isVeiculoProprio = document.getElementById("isVeiculoProprio")
            var selecaoVeiculo = document.getElementById("selecaoVeiculo")

            if(isVeiculoProprio.value=="1") {//Se for veículo próprio
                document.getElementById("veiculoEmpresa").hidden = true;
                document.getElementById("selecaoVeiculo").hidden = false;
                document.getElementById("veiculoProprio_id").value = "";
                
            } else if(isVeiculoProprio.value=="0"){
                document.getElementById("veiculoEmpresa").hidden = false;
                document.getElementById("selecaoVeiculo").hidden = true;
                document.getElementById("isVeiculoEmpresa").value="0";
            }
        }

        function desativarCampoObjetivo(){
            var seletor = document.getElementById("flexSwitchCheckDefault")

            if(seletor.checked == true) {
                document.getElementById("outroObjetivoCampo").hidden = false;
                document.getElementById("nomeObjetivo").hidden = true;
                document.getElementById("outroObjetivo").value = "";
                document.getElementById("nomeObjetivo").value = null;
                document.getElementById("isSelecionado").value = "1";
            } else if(seletor.checked == false){
                document.getElementById("outroObjetivoCampo").hidden = true;
                document.getElementById("nomeObjetivo").hidden = false;
                document.getElementById("isSelecionado").value = "0";
            }
        }



        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            }
        });
        
                //Assim que a tela carrega, aciona automaticamente essas duas funções ------------------------
        $(function(){
            document.getElementById("outroObjetivoCampo").hidden = true;
        })
    </script>
@endsection