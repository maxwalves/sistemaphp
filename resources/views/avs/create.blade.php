@extends('layouts.main')

@section('title', 'Criar Autorização de viagem')
@section('content')

<div id="av-create-container" class="col-md-6 offset-md-3">
        <h2>Crie uma autorização de viagem!</h2>
        <form action="/avs" method="POST" enctype="multipart/form-data">
            @csrf


            <div class="form-group">
                <label for="objetivo_id" class="control-label" required>Qual é o Objetivo da viagem? (selecione)</label>
                <br>
                    <select class="custom-select {{ $errors->has('objetivo_id') ? 'is-invalid' :''}}" 
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

            <div class="form-group">
                <label for="prioridade" class="control-label" required>Qual é a Prioridade da sua viagem? (selecione)</label>
                <br>
                    <select class="custom-select {{ $errors->has('objetivo_id') ? 'is-invalid' :''}}" 
                        id="prioridade" name="prioridade">
                        <option value="" name=""> Selecione</option>
                        <option value="Alta" name="Alta"> Alta</option>
                        <option value="Média" name="Média"> Média</option>
                        <option value="Baixa" name="Baixa"> Baixa</option>
                    </select>

                    @if ($errors->has('objetivo_id'))
                    <div class="invalid-feedback">
                        {{ $errors->first('objetivo_id') }}
                    </div>
                    @endif
            </div>

            <div class="form-group">
                <label for="isVeiculoProprio" class="control-label">Você vai utilizar veículo próprio? (selecione)</label>
                <br>
                    <select class="custom-select {{ $errors->has('objetivo_id') ? 'is-invalid' :''}}" 
                        id="isVeiculoProprio" name="isVeiculoProprio" onChange="desativarCampo()" required>
                        <option value="" name=""> Selecione</option>
                        <option value="1" name="1" > Sim</option>
                        <option value="0" name="0"> Não</option>
                    </select>

                    @if ($errors->has('objetivo_id'))
                    <div class="invalid-feedback">
                        {{ $errors->first('objetivo_id') }}
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
                        {{ $errors->first('objetivo_id') }}
                    </div>
                    @endif
            </div>





            <div class="form-group">
                <label for="isVeiculoEmpresa" class="control-label" required>Você vai utilizar veículo do Paranacidade? (selecione)</label>
                <br>
                    <select class="custom-select {{ $errors->has('objetivo_id') ? 'is-invalid' :''}}" 
                        id="isVeiculoEmpresa" name="isVeiculoEmpresa">
                        <option value="0" name="0"> Não</option>
                        <option value="1" name="1"> Sim</option>
                    </select>

                    @if ($errors->has('objetivo_id'))
                    <div class="invalid-feedback">
                        {{ $errors->first('objetivo_id') }}
                    </div>
                    @endif
            </div>
            
            <div class="form-group">
                <label for="banco" class="control-label">Banco</label>
                <input type="number" class="form-control" name="banco"
                id="banco" placeholder="Banco">
            </div>

            <div class="form-group">
                <label for="agencia" class="control-label">Agência</label>
                <div class="input-group">
                    <input type="number" class="form-control" name="agencia"
                    id="agencia" placeholder="Agência">
                </div>
            </div>

            <div class="form-group">
                <label for="conta" class="control-label">Conta</label>
                <input type="number" class="form-control" name="conta"
                id="conta" placeholder="Conta">
            </div>

            <div class="form-group">
                <label for="pix" class="control-label">Pix</label>
                <input type="number" class="form-control" name="pix"
                    id="pix" placeholder="Pix">
            </div>

            <div class="form-group">
                <label for="comentario" class="control-label">Comentários</label>
                <input type="text" class="form-control" name="comentario"
                    id="comentario" placeholder="Comentário">
            </div>
            <div id="btSalvarAv">
                <input style="font-size: 16px" type="submit" class="btn btn-primary btn-lg" value="Salvar e escolher itinerário!">
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

            if(isVeiculoProprio.value=="1") {
                document.getElementById("isVeiculoEmpresa").hidden = true;
                document.getElementById("selecaoVeiculo").hidden = false;
            } else if(isVeiculoProprio.value=="0"){
                document.getElementById("isVeiculoEmpresa").hidden = false;
                document.getElementById("selecaoVeiculo").hidden = true;
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