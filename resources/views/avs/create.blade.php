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
                        id="objetivo_id" name="objetivo_id" onChange="verificarObjetivoViagem()">
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

            <div id="autorizacaoComissao">
                <h1>Lista de medições pendentes</h1>
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th>Nome do município</th>
                            <th>Número do projeto</th>
                            <th>Número do lote</th>
                            <th>Número da medição</th>
                            <th>Tipo medição</th>
                            <th>Valor da medição</th>
                            <th>Descrição do componente</th>
                            <th>Selecione</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($filtro as $item)
                        <tr>
                            <td> {{ $item->nome_municipio }} </td>
                            <td> {{ $item->numero_projeto }} </td>
                            <td> {{ $item->numero_lote }} </td>
                            <td> {{ $item->numero }} </td>
                            <td> {{ $item->tipo_medicao }} </td>
                            <td>R$ {{ number_format($item->valor_medicao_sam, 2, ',', '.') }}</td>
                            <td> {{ $item->descricao_componente }} </td>
                            <td> 
                                <input type="radio" name="radioBt" id="radioBt" class="radio radio-error" value="{{ $item->id }}"/>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                
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

            <div class="form-check form-switch" id="selecOutroObj">
                <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDefault" 
                style="height: 20px; width: 40px" onChange="desativarCampoObjetivo()">
                <label class="form-check-label" for="flexSwitchCheckDefault" style="padding-left: 10px">Não achei o objetivo que desejo na lista!</label>
            </div>
            <input type="boolean" id="isSelecionado" name="isSelecionado" value="0" hidden="true">
            <br>
            <br>
    
            <div class="form-group">
                <label for="isDiaria" class="control-label" required>Vai precisar de diária de alimentação? (selecione)</label>
                <br>
                    <select class="select select-bordered w-full max-w-xs {{ $errors->has('isDiaria') ? 'is-invalid' :''}}" 
                        id="isDiaria" name="isDiaria">
                        <option value="" name=""> Selecione</option>
                        <option value="Sim" name="Alta"> Sim</option>
                        <option value="Não" name="Média"> Não</option>
                    </select>

                    @if ($errors->has('isDiaria'))
                    <div class="invalid-feedback">
                        {{ $errors->first('isDiaria') }}
                    </div>
                    @endif
            </div>
            <div class="mb-3">
                <label for="banco" class="form-label">Banco</label>
                <div class="input-group mb-3">   
                    <input type="text" class="form-control  {{ $errors->has('banco') ? 'is-invalid' :''}}" name="banco"
                    id="banco" placeholder="Banco">
                    <span class="input-group-text" id="basic-addon2">Ex: Banco do Brasil</span>

                    @if ($errors->has('banco'))
                    <div class="invalid-feedback">
                        {{ $errors->first('banco') }}
                    </div>
                    @endif
                </div>
            </div>

            <div class="mb-3">
                <label for="agencia" class="form-label">Agência</label>
                <div class="input-group mb-3">
                    <input type="text" class="form-control  {{ $errors->has('agencia') ? 'is-invalid' :''}}" name="agencia"
                        id="agencia" placeholder="Agência">
                    <span class="input-group-text" id="basic-addon2">Ex: 1234-X</span>

                    @if ($errors->has('agencia'))
                    <div class="invalid-feedback">
                        {{ $errors->first('agencia') }}
                    </div>
                    @endif
                </div>
            </div>

            <div class="mb-3">
                <label for="conta" class="form-label">Conta</label>
                <div class="input-group mb-3">
                    <input type="text" class="form-control  {{ $errors->has('conta') ? 'is-invalid' :''}}" name="conta"
                    id="conta" placeholder="Conta">
                    <span class="input-group-text" id="basic-addon2">Ex: 12345-X</span>

                    @if ($errors->has('conta'))
                    <div class="invalid-feedback">
                        {{ $errors->first('conta') }}
                    </div>
                    @endif
                </div>
            </div>

            <div class="mb-3">
                <label for="pix" class="form-label">Pix</label><br>
                <div class="input-group mb-3">
                    <input type="text" class="form-control" name="pix"
                        id="pix" placeholder="Pix">
                    <span class="input-group-text" id="basic-addon2">Chave pix</span>
                </div>
            </div>

            <div class="mb-3">
                <label for="comentario" class="form-label">Comentários</label><br>
                <div class="input-group mb-3">
                    <input type="text" class="form-control" name="comentario"
                        id="comentario" placeholder="Comentário">
                    <span class="input-group-text" id="basic-addon2">Opcional</span>
                </div>
            </div>
            <input type="file" id="arquivo1" style="height: 150px" name="arquivo1" class="form-control form-control-lg">

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
        
        function verificarObjetivoViagem(){
            var objetivo = document.getElementById("objetivo_id");
            
            if(objetivo.value == 3){
                document.getElementById("autorizacaoComissao").hidden = false;
                document.getElementById("selecOutroObj").hidden = true;
            }
            else{
                document.getElementById("autorizacaoComissao").hidden = true;
                document.getElementById("selecOutroObj").hidden = false;
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
            document.getElementById("autorizacaoComissao").hidden = true;
        })
    </script>
@endsection