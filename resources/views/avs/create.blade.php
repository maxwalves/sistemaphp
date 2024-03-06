@extends('adminlte::page')

@section('title', 'Criar AV')

@section('content_header')
@stop

@section('content')

<div class="tab-pane fade show active" id="custom-tabs-five-overlay" role="tabpanel" aria-labelledby="custom-tabs-five-overlay-tab" style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.5); z-index: 9999; display: none">
    <div class="overlay-wrapper" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); text-align: center; color: #ffffff;">
            <i class="fas fa-3x fa-sync-alt fa-spin" style="margin-bottom: 10px;"></i>
            <div class="text-bold pt-2">Carregando...</div>
    </div>
</div>

<div id="av-create-container" >
    <br>
    <div class="row">
        <div class="col-md-6">
            <h2>Cadastro de autorização de viagem:</h2>
        </div>
        <div class="col-md-3">
            <a href="/avs/avs/" type="submit" class="btn btn-warning btn-ghost"><i class="fas fa-arrow-left"></i></a>
        </div>
    </div>
    <br>
    <form action="/avs/gravarAv" method="POST" enctype="multipart/form-data">
        @csrf
        
        <div class="form-group col-md-6" id="nomeObjetivo">
            <label for="objetivo_id" class="control-label" required>Qual é o Objetivo da viagem? (selecione)</label>
            <br>
                <select class="select form-control {{ $errors->has('objetivo_id') ? 'is-invalid' :''}}" 
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
        <div class="form-group col-md-6" id="isMostrarTodos">
            <span class="label-text">Mostrar todos</span> 
            <input type="checkbox" class="toggle" onChange="mostrarTodos()"/>
        </div>

        <div id="autorizacaoComissao">
            <h1>Lista de medições pendentes</h1>
            <table class="table table-sm">
                <thead>
                    <tr>
                        <th>Nome do supervisor</th>
                        <th>Regional</th>
                        <th>Município</th>
                        <th>Projeto</th>
                        <th>Lote</th>
                        <th>Medição</th>
                        <th>Tipo medição</th>
                        <th>Valor da medição</th>
                        <th>Descrição do componente</th>
                        <th>Selecione</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($filtro as $item)
                    <tr>
                        <td> {{ $item->nome_supervisor }} </td>
                        <td> {{ $item->codigo_regional }} </td>
                        <td> {{ $item->nome_municipio }} </td>
                        <td> {{ $item->numero_projeto }} </td>
                        <td> {{ $item->numero_lote }} </td>
                        <td> {{ $item->numero }} </td>
                        <td> {{ $item->tipo_medicao }} </td>
                        <td>R$ {{ number_format($item->valor_medicao_sam, 2, ',', '.') }}</td>
                        <td> {{ $item->descricao_componente }} </td>
                        <td> 
                            <input type="radio" name="medicoesUsuarioSelecionadas[]" id="medicoesUsuarioSelecionadas" 
                            class="radio radio-error" value="{{ $item->id }}"/>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            
        </div>
        <div id="autorizacaoComissaoTodos">
            <h1>Lista de medições pendentes</h1>
            <table class="display nowrap" id="minhaTabela">
                <thead>
                    <tr>
                        <th style="width: 5%">Selecione</th>
                        <th>Nome do supervisor</th>
                        <th>Regional</th>
                        <th>Município</th>
                        <th>Projeto</th>
                        <th>Lote</th>
                        <th>Medição</th>
                        <th>Tipo medição</th>
                        <th>Valor da medição</th>
                        <th>Descrição do componente</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($filtroTodos as $item)
                    <tr>
                        <td> 
                            <input type="checkbox" name="todosSelecionados[]" id="todosSelecionados" 
                            class="checkbox checkbox-md" value="{{ $item->id }}"/>
                        </td>
                        <td> {{ $item->nome_supervisor }} </td>
                        <td> {{ $item->codigo_regional }} </td>
                        <td> {{ $item->nome_municipio }} </td>
                        <td> {{ $item->numero_projeto }} </td>
                        <td> {{ $item->numero_lote }} </td>
                        <td> {{ $item->numero }} </td>
                        <td> {{ $item->tipo_medicao }} </td>
                        <td>R$ {{ number_format($item->valor_medicao_sam, 2, ',', '.') }}</td>
                        <td> {{ $item->descricao_componente }} </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            
        </div>

        <div class="form-group col-md-6" id="outroObjetivoCampo">
            <label for="outro" class="control-label">Digite outro objetivo: </label>
            <div class="input-group">
                <input type="text" class="form-control {{ $errors->has('outroObjetivo') ? 'is-invalid' :''}}" 
                name="outroObjetivo"
                id="outroObjetivo" placeholder="Digite outro objetivo">
            </div>

            @if ($errors->has('outroObjetivo'))
                <div class="invalid-feedback">
                    {{ $errors->first('outroObjetivo') }}
                </div>
            @endif
        </div>

        <div class="form-check form-switch col-md-6" id="selecOutroObj">
            <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDefault" 
            style="height: 20px; width: 40px" onChange="desativarCampoObjetivo()">
            <label class="form-check-label" for="flexSwitchCheckDefault" style="padding-left: 10px">Não achei o objetivo que desejo na lista!</label>
        </div>
        <input type="boolean" id="isSelecionado" name="isSelecionado" value="0" hidden="true">
        <br>
        <br>

        <div class="mb-3 col-md-6">

            <label for="banco" class="form-label"><strong style="color: red">* </strong>Banco</label>
            <div class="input-group mb-3">   

                <select class="form-control  {{ $errors->has('banco') ? 'is-invalid' :''}}" 
                    style="width: 20%" id="banco" name="banco" placeholder="Banco">
                    <option value="">Selecione</option>
                    @for($i = 0; $i < count($bancos); $i++)
                            <option value="{{ $bancos[$i] }}" 
                                name="{{ $bancos[$i] }}"> {{ $bancos[$i] }} </option>
                    @endfor
                    
                    <option value="outro">Outro</option>
                </select>

                <input type="text" id="outrobanco" name="outrobanco" style="width: 50%; display: none;">
                <span class="input-group-text" id="basic-addon2">Ex: Banco do Brasil</span>

                @if ($errors->has('banco'))
                    <div class="invalid-feedback">
                        {{ $errors->first('banco') }}
                    </div>
                @endif
            </div>
        </div>

        <div class="mb-3 col-md-6">
            <label for="agencia" class="form-label"><strong style="color: red">* </strong>Agência</label>
            <div class="input-group mb-3">

                <select class="form-control  {{ $errors->has('agencia') ? 'is-invalid' :''}}" name="agencia"
                    id="agencia" placeholder="Agência" style="width: 20%">
                    <option value="">Selecione</option>
                    @for($i = 0; $i < count($agencias); $i++)
                            <option value="{{ $agencias[$i] }}" 
                                name="{{ $agencias[$i] }}"> {{ $agencias[$i] }} </option>
                    @endfor
                    
                    <option value="outro">Outro</option>
                </select>

                <input type="text" id="outraagencia" name="outraagencia" style="width: 50%; display: none;">
                <span class="input-group-text" id="basic-addon2">Ex: 1234-X</span>

                @if ($errors->has('agencia'))
                    <div class="invalid-feedback">
                        {{ $errors->first('agencia') }}
                    </div>
                @endif
            </div>
        </div>

        <div class="mb-3 col-md-6">
            <label for="conta" class="form-label"><strong style="color: red">* </strong>Conta</label>
            <div class="input-group mb-3">

                <select class="form-control  {{ $errors->has('conta') ? 'is-invalid' :''}}" name="conta"
                    id="conta" placeholder="Conta" style="width: 20%">
                    <option value="">Selecione</option>
                    @for($i = 0; $i < count($contas); $i++)
                            <option value="{{ $contas[$i] }}" 
                                name="{{ $contas[$i] }}"> {{ $contas[$i] }} </option>
                    @endfor
                    
                    <option value="outro">Outro</option>
                </select>

                <input type="text" id="outraconta" name="outraconta" style="width: 50%; display: none;">
                <span class="input-group-text" id="basic-addon2">Ex: 12345-X</span>

                @if ($errors->has('conta'))
                    <div class="invalid-feedback">
                        {{ $errors->first('conta') }}
                    </div>
                @endif
            </div>
        </div>

        <div class="mb-3 col-md-6">
            <label for="pix" class="form-label">Pix</label><br>
            <div class="input-group mb-3">

                <select class="form-control" name="pix"
                id="pix" placeholder="Pix" style="width: 20%">
                    <option value="">Selecione</option>
                    @for($i = 0; $i < count($pixs); $i++)
                            <option value="{{ $pixs[$i] }}" 
                                name="{{ $pixs[$i] }}"> {{ $pixs[$i] }} </option>
                    @endfor
                    
                    <option value="outro">Outro</option>
                </select>

                <input type="text" id="outropix" name="outropix" style="width: 50%; display: none;">
                <span class="input-group-text" id="basic-addon2">Chave pix</span>

            </div>
        </div>

        <div class="mb-3 col-md-6">
            <label for="comentario" class="form-label">Comentários</label><br>
            <div class="input-group mb-3">
                <input type="text" class="form-control" name="comentario"
                    id="comentario" placeholder="Comentário">
                <span class="input-group-text" id="basic-addon2">Opcional</span>
            </div>
        </div>
        <input type="file" id="arquivo1" style="height: 150px" name="arquivo1" class="form-control form-control-lg col-md-6">

        <br>

        <div class="col-md-6">
            <div id="btSalvarAv">
                <input style="font-size: 14px" id="salvarAvBt" type="submit" class="btn btn-active btn-primary" value="Salvar e escolher itinerário!">
            </div>
        </div>
    </form>
    <br><br>

</div>

@stop

@section('css')
    <link href="{{asset('DataTables/datatables.min.css')}}" rel="stylesheet"/>
@stop

@section('js')
    <script src="{{asset('DataTables/datatables.min.js')}}"></script>
    <script src="{{asset('/js/moment.js')}}"></script>
    <script type="text/javascript">

        $('#salvarAvBt').on('click', function() {
                // Altera o estilo da <div> para "block"
                $('#custom-tabs-five-overlay').css('display', 'block');
        });

        $(document).ready(function(){
            $('#minhaTabela').DataTable({
                    scrollY: 500,
                    scrollX: true,
                    "language": {
                        "lengthMenu": "Mostrando _MENU_ registros por página",
                        "zeroRecords": "Nada encontrado",
                        "info": "Mostrando página _PAGE_ de _PAGES_",
                        "infoEmpty": "Nenhum registro disponível",
                        "infoFiltered": "(filtrado de _MAX_ registros no total)",
                        "search": "Pesquisar"
                    }
                });
        });

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
                document.getElementById("isMostrarTodos").hidden = false;
            }
            else{
                document.getElementById("autorizacaoComissao").hidden = true;
                document.getElementById("selecOutroObj").hidden = false;
                document.getElementById("isMostrarTodos").hidden = true;
                document.getElementById("autorizacaoComissaoTodos").hidden = true;
            }
        }

        function mostrarTodos(){
            if(document.getElementById("autorizacaoComissaoTodos").hidden == true){
                document.getElementById("autorizacaoComissaoTodos").hidden = false;
            }
            else{
                document.getElementById("autorizacaoComissaoTodos").hidden = true;
            }

            if(document.getElementById("autorizacaoComissao").hidden == true){
                document.getElementById("autorizacaoComissao").hidden = false;
            }
            else{
                document.getElementById("autorizacaoComissao").hidden = true;
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
            document.getElementById("autorizacaoComissaoTodos").hidden = true;
            document.getElementById("isMostrarTodos").hidden = true;
        })

        var selectBanco = document.getElementById("banco");
        var inputOutroBanco = document.getElementById("outrobanco");

        var selectAgencia = document.getElementById("agencia");
        var inputOutraAgencia = document.getElementById("outraagencia");

        var selectConta = document.getElementById("conta");
        var inputOutraConta = document.getElementById("outraconta");

        var selectPix = document.getElementById("pix");
        var inputOutroPix = document.getElementById("outropix");

        selectBanco.addEventListener("change", function () {
            if (selectBanco.value === "outro") {
                inputOutroBanco.style.display = "block";
                inputOutroBanco.value = ""; // Limpa o campo de entrada
            } else {
                inputOutroBanco.style.display = "none";
            }
        });

        selectAgencia.addEventListener("change", function () {
            if (selectAgencia.value === "outro") {
                inputOutraAgencia.style.display = "block";
                inputOutraAgencia.value = ""; // Limpa o campo de entrada
            } else {
                inputOutraAgencia.style.display = "none";
            }
        });

        selectConta.addEventListener("change", function () {
            if (selectConta.value === "outro") {
                inputOutraConta.style.display = "block";
                inputOutraConta.value = ""; // Limpa o campo de entrada
            } else {
                inputOutraConta.style.display = "none";
            }
        });

        selectPix.addEventListener("change", function () {
            if (selectPix.value === "outro") {
                inputOutroPix.style.display = "block";
                inputOutroPix.value = ""; // Limpa o campo de entrada
            } else {
                inputOutroPix.style.display = "none";
            }
        });
    </script>
@stop