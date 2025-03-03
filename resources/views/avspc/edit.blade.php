@extends('adminlte::page')

@section('title', 'Editar AV')

@section('content_header')
    
@stop

@section('content')

<br>
<div class="row">
    <div class="col-3">
        <a href="/avs/fazerPrestacaoContas/{{ $av->id }}" type="submit" class="btn btn-active btn-warning"><i class="fas fa-arrow-left"></i>VOLTAR PARA PRESTAÇÃO DE CONTAS SEM SALVAR</a>
    </div>
    <div class="col-4">
        <h3>Editar AV</h3>
    </div>
</div>

<div id="av-create-container" >
    
    <form action="/avs/update/{{ $av->id }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <input type="text" hidden="true" value="sim" name="isPc" id="isPc">
        <div class="form-group col-md-6 offset-md-3" id="nomeObjetivo">
            <label for="objetivo_id" class="control-label" required>Qual é o Objetivo da viagem? (selecione)</label>
            <p id="mensagemUsuario" style="color: red"></p>
            <br>
                <select class="select select-bordered w-full max-w-xs {{ $errors->has('objetivo_id') ? 'is-invalid' :''}}" 
                    id="objetivo_id" name="objetivo_id" onChange="verificarObjetivoViagem()">
                    <option value="" name=""> Selecione</option>
                    @for($i = 0; $i < count($objetivos); $i++)
                        <div>
                            <option value="{{ $objetivos[$i]->id }}" 
                                name="{{ $objetivos[$i]->id }}" {{$objetivos[$i]->id == $av->objetivo_id ? 'selected' : ''}}> {{ $objetivos[$i] ->nomeObjetivo }} </option>
                        </div>
                    @endfor
                </select>

                @if ($errors->has('objetivo_id'))
                <div class="invalid-feedback">
                    {{ $errors->first('objetivo_id') }}
                </div>
                @endif
        </div>
        <div class="form-group col-md-6 offset-md-3" id="isMostrarTodos">
            <span class="label-text">Mostrar todos</span> 
            <input type="checkbox" class="toggle" id="checkMostrarTodos" onChange="mostrarTodos()"/>
        </div>

        <div id="autorizacaoComissao">
            <h3>Lista de medições pendentes</h3>
            @if(count($filtro) == 0)
                <h5>Não há medições pendetes vinculadas ao seu usuário</h5>
            @else
            <table class="table table-sm">
                <thead>
                    <tr>
                        <th>Selecione</th>
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
                    @foreach($filtro as $item)
                    <tr>

                        @php
                            $achouMedicaoUser = false;
                        @endphp
                        @foreach($medicoesFiltradas as $medF)
                            @if($medF->numero_projeto == $item->numero_projeto && $medF->numero_lote == $item->numero_lote && $medF->numero_medicao == $item->numero)
                                <td> 
                                    <input type="checkbox" name="medicoesUsuarioSelecionadas[]" id="medicoesUsuarioSelecionadas" 
                                    class="checkbox checkbox-md" value="{{ $item->id }}" checked/>
                                    @php
                                        $achouMedicaoUser = true;
                                    @endphp
                                </td>
                            @endif
                        @endforeach
                        
                        @if($achouMedicaoUser == false)
                            <td> 
                                <input type="checkbox" name="medicoesUsuarioSelecionadas[]" id="medicoesUsuarioSelecionadas" 
                                class="checkbox checkbox-md" value="{{ $item->id }}"/>
                            </td>
                        @endif

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
            @endif
            
        </div>
        <div id="autorizacaoComissaoTodos">
            <h3>Lista de medições pendentes</h3>
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
                        @php
                            $achouMedicao = false;
                        @endphp
                        @foreach($medicoesFiltradas as $medF)
                            @if($medF->numero_projeto == $item->numero_projeto && $medF->numero_lote == $item->numero_lote && $medF->numero_medicao == $item->numero)
                                <td> 
                                    <input type="checkbox" name="todosSelecionados[]" id="todosSelecionados" 
                                    class="checkbox checkbox-md" value="{{ $item->id }}" checked/>
                                    @php
                                        $achouMedicao = true;
                                    @endphp
                                </td>
                            @endif
                        @endforeach
                        
                        @if($achouMedicao == false)
                            <td> 
                                <input type="checkbox" name="todosSelecionados[]" id="todosSelecionados" 
                                class="checkbox checkbox-md" value="{{ $item->id }}"/>
                            </td>
                        @endif
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

        <div class="form-group col-md-6 offset-md-3" id="outroObjetivoCampo">
            <label for="outro" class="control-label">Digite outro objetivo: </label>
            <div class="input-group">
                <input type="text" class="form-control {{ $errors->has('outroObjetivo') ? 'is-invalid' :''}}" 
                name="outroObjetivo"
                id="outroObjetivo" placeholder="Digite outro objetivo" value="{{$av->outroObjetivo != null ? $av->outroObjetivo : ''}}">
            </div>

            @if ($errors->has('outroObjetivo'))
                <div class="invalid-feedback">
                    {{ $errors->first('outroObjetivo') }}
                </div>
            @endif
        </div>

        <div class="form-check form-switch col-md-6 offset-md-3" id="selecOutroObj">
            <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDefault" 
            style="height: 20px; width: 40px" onChange="desativarCampoObjetivo()">
            <label class="form-check-label" for="flexSwitchCheckDefault" style="padding-left: 10px">Não achei o objetivo que desejo na lista!</label>
        </div>
        <input type="boolean" id="isSelecionado" name="isSelecionado" value="0" hidden="true">
        <br>
        <br>

        <div class="mb-3 col-md-6 offset-md-3">
            <label for="banco" class="form-label"><strong style="color: red">* </strong>Banco</label>
            <div class="input-group mb-3">   
                <input type="text" class="form-control" name="banco"
                id="banco" placeholder="Banco" value="{{$av->banco}}">
                <span class="input-group-text" id="basic-addon2">Ex: Banco do Brasil</span>
            </div>
        </div>

        <div class="mb-3 col-md-6 offset-md-3">
            <label for="agencia" class="form-label"><strong style="color: red">* </strong>Agência</label>
            <div class="input-group mb-3">
                <input type="text" class="form-control" name="agencia"
                    id="agencia" placeholder="Agência" value="{{$av->agencia}}">
                <span class="input-group-text" id="basic-addon2">Ex: 1234-X</span>
            </div>
        </div>

        <div class="mb-3 col-md-6 offset-md-3">
            <label for="conta" class="form-label"><strong style="color: red">* </strong>Conta</label>
            <div class="input-group mb-3">
                <input type="text" class="form-control" name="conta"
                id="conta" placeholder="Conta" value="{{$av->conta}}">
                <span class="input-group-text" id="basic-addon2">Ex: 12345-X</span>
            </div>
        </div>

        <div class="mb-3 col-md-6 offset-md-3">
            <label for="pix" class="form-label">Pix</label><br>
            <div class="input-group mb-3">
                <input type="text" class="form-control" name="pix"
                    id="pix" placeholder="Pix" value="{{$av->pix}}">
                <span class="input-group-text" id="basic-addon2">Chave pix</span>
            </div>
        </div>

        <div class="mb-3 col-md-6 offset-md-3">
            <label for="comentario" class="form-label">Comentários</label><br>
            <div class="input-group mb-3">
                <input type="text" class="form-control" name="comentario"
                    id="comentario" placeholder="Comentário" value="{{$av->comentario}}">
                <span class="input-group-text" id="basic-addon2">Opcional</span>
            </div>
        </div>
        
        <div class="mb-3 col-md-6 offset-md-3">
            @if ($av->autorizacao != null)
                <a href="{{ route('recuperaArquivo', [
                    'name' => $userAv->name,
                    'id' => $av->id,
                    'pasta' => 'autorizacaoAv',
                    'anexoRelatorio' => $av->autorizacao,
                    ]) }}"
                    target="_blank" class="btn btn-active btn-success btn-sm">Documento de Autorização</a>
            @endif
        </div>
        <input type="file" id="arquivo1" style="height: 150px" name="arquivo1" class="form-control form-control-lg col-md-6 offset-md-3">

        <br>

        <div class="col-md-6 offset-md-3">
            <div id="btSalvarAv">
                <input type="submit" class="btn btn-success" value="SALVAR E VOLTAR PARA PRESTAÇÃO DE CONTAS">
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

        @if($av->outroObjetivo != null)
            var select = document.getElementById("flexSwitchCheckDefault")
            select.checked = true;
            document.getElementById("outroObjetivoCampo").hidden = false;
            document.getElementById("nomeObjetivo").hidden = true;
            document.getElementById("nomeObjetivo").value = null;
            document.getElementById("isSelecionado").value = "1";
        @else
            document.getElementById("outroObjetivoCampo").hidden = true;
            document.getElementById("nomeObjetivo").hidden = false;
            document.getElementById("isSelecionado").value = "0";
        @endif
        
        @for($i = 0; $i < count($objetivos); $i++)
            @if($objetivos[$i]->id == $av->objetivo_id && $av->objetivo_id == 3)
                setTimeout(verificarObjetivoViagem, 500);
                document.getElementById("mensagemUsuario").innerHTML = "Confirme a medição";
                document.getElementById("mensagemUsuario").style.fontWeight = "bold";
                @if(count($filtro) == 0)
                    //deixe o checkbox checkMostrarTodos checado
                    document.getElementById("checkMostrarTodos").checked = true;
                    setTimeout(mostrarTodos, 500);
                @endif
            @endif
        @endfor

    });

    function desativarCampoObjetivo(){
        var seletor = document.getElementById("flexSwitchCheckDefault")

        if(seletor.checked == true) {
            document.getElementById("outroObjetivoCampo").hidden = false;
            document.getElementById("nomeObjetivo").hidden = true;
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
        console.log(objetivo.value);
        
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
        document.getElementById("autorizacaoComissao").hidden = true;
        document.getElementById("autorizacaoComissaoTodos").hidden = true;
        document.getElementById("isMostrarTodos").hidden = true;
    })
</script>

@stop