@extends('adminlte::page')

@section('title', 'Cadastrar nova rota')

@section('content_header')
    <h1>Cadastrar nova rota</h1>
@stop

@section('content')

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Criar Nova Rota</h3>
                    </div>
                    <div class="card-body">
                        <form action="/rotas" method="POST" enctype="multipart/form-data" id="formPrincipal">
                            @csrf
                            <div class="row">
                                <div class="col-5">
                                    <a href="/rotaspc/rotas/{{ $av->id }}" type="submit" class="btn btn-warning"><i
                                            class="fas fa-arrow-left"></i> VOLTAR PARA PC SEM SALVAR</a>
                                </div>
                                <div class="col-7">
                                    <label for="idav"> <strong>Cadastro de nova rota da AV nº
                                        </strong>
                                    </label>
                                    <input type="text" style="width: 50px" value="{{ $av->id }}" id="idav"
                                        name="idav" readonly>

                                    <h4> <strong>Data da AV:
                                            {{ date('d/m/Y', strtotime($av->dataCriacao)) }}</strong> </h4>
                                    <input type="text" hidden="true" value="sim" name="isPc" id="isPc">
                                </div>
                            </div>

                            @if (count($rotas) > 0)
                                <p>
                                <h4>Rotas já cadastradas</h4>
                                </p>
                                <table id="tabelaRota" class="table table-hover table-bordered" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>Tipo</th>
                                            <th>Cidade de saída</th>
                                            <th>Data/Hora de saída</th>
                                            <th>Cidade de chegada</th>
                                            <th>Data/Hora de chegada</th>
                                            <th>Hotel?</th>
                                            <th>Tipo de transporte</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($rotas as $rotaExibicao)
                                            <tr>
                                                <td> {{ $rotaExibicao->isViagemInternacional == 1 ? 'Internacional' : 'Nacional' }}
                                                </td>
                                                <td>
                                                    @if ($rotaExibicao->isAereo == 1)
                                                        <img src="{{ asset('/img/aviaosubindo.png') }}" style="width: 40px">
                                                    @endif

                                                    @if ($rotaExibicao->isVeiculoProprio == 1 || $rotaExibicao->isVeiculoEmpresa == 1)
                                                        <img src="{{ asset('/img/carro.png') }}" style="width: 40px">
                                                    @endif

                                                    @if ($rotaExibicao->isOnibusLeito == 1 || $rotaExibicao->isOnibusConvencional == 1)
                                                        <img src="{{ asset('/img/onibus.png') }}" style="width: 40px">
                                                    @endif

                                                    @if ($rotaExibicao->isOutroMeioTransporte == 1)
                                                        <img src="{{ asset('/img/outros.png') }}" style="width: 40px">
                                                    @endif

                                                    {{ $rotaExibicao->isViagemInternacional == 0 ? $rotaExibicao->cidadeOrigemNacional : $rotaExibicao->cidadeOrigemInternacional }}

                                                </td>
                                                <td> {{ date('d/m/Y H:i', strtotime($rotaExibicao->dataHoraSaida)) }} </td>

                                                <td>
                                                    @if ($rotaExibicao->isAereo == 1)
                                                        <img src="{{ asset('/img/aviaodescendo.png') }}"
                                                            style="width: 40px">
                                                    @endif

                                                    @if ($rotaExibicao->isVeiculoProprio == 1 || $rotaExibicao->isVeiculoEmpresa == 1)
                                                        <img src="{{ asset('/img/carro.png') }}" style="width: 40px">
                                                    @endif

                                                    @if ($rotaExibicao->isOnibusLeito == 1 || $rotaExibicao->isOnibusConvencional == 1)
                                                        <img src="{{ asset('/img/onibus.png') }}" style="width: 40px">
                                                    @endif

                                                    @if ($rotaExibicao->isOutroMeioTransporte == 1)
                                                        <img src="{{ asset('/img/outros.png') }}" style="width: 40px">
                                                    @endif

                                                    {{ $rotaExibicao->isViagemInternacional == 0 ? $rotaExibicao->cidadeDestinoNacional : $rotaExibicao->cidadeDestinoInternacional }}
                                                </td>

                                                <td> {{ date('d/m/Y H:i', strtotime($rotaExibicao->dataHoraChegada)) }}
                                                </td>
                                                <td> {{ $rotaExibicao->isReservaHotel == 1 ? 'Sim' : 'Não' }}</td>
                                                <td>
                                                    {{ $rotaExibicao->isOnibusLeito == 1 ? 'Onibus leito' : '' }}
                                                    {{ $rotaExibicao->isOnibusConvencional == 1 ? 'Onibus convencional' : '' }}
                                                    @if ($rotaExibicao->isVeiculoProprio == 1)
                                                        {{ 'Veículo próprio: ' }} <br>
                                                        @foreach ($veiculosProprios as $v)
                                                            @if ($v->id == $rota->veiculoProprio_id)
                                                                {{ $v->modelo . '-' . $v->placa }}
                                                            @endif
                                                        @endforeach

                                                        @if (count($veiculosProprios) == 0)
                                                            {{ 'Não encontrado' }}
                                                        @endif
                                                    @endif
                                                    {{ $rotaExibicao->isVeiculoEmpresa == 1 ? 'Veículo empresa' : '' }}
                                                    {{ $rotaExibicao->isAereo == 1 ? 'Aéreo' : '' }}
                                                    {{ $rotaExibicao->isOutroMeioTransporte == 1 ? 'Outros' : '' }}
                                                    {{ $rotaExibicao->isOutroMeioTransporte == 2 ? 'Carona' : '' }}
                                                </td>
                                                @php
                                                    $achouVeiculo = false;
                                                @endphp

                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <hr>
                            @endif

                            <input type="text" id="isViagemInternacional" name="isViagemInternacional" value="0"
                                hidden>

                            {{-- INÍCIO DOS CAMPOS PARA VIAGEM NACIONAL ---------------------------------------------------------- --}}

                            <div id="isNacional">
                                <h3 style="color: forestgreen"> <ion-icon name="bus-outline"></ion-icon> VIAGEM
                                    NACIONAL </h3>

                                <div class="row">
                                    <!-- Origem -->
                                    <div class="col-12 col-xl-6 mb-4">

                                        <h4 style="color: darkolivegreen"> Origem: </h4>
                                        <div class="form-group">
                                            <label for="selecaoEstadoOrigemNacional" class="control-label"><strong
                                                    style="color: red">* </strong>Selecione o estado origem:</label>
                                            <br>

                                            <select
                                                class="custom-select custom-select-lg {{ $errors->has('selecaoEstadoOrigemNacional') ? 'is-invalid' : '' }}"
                                                id="selecaoEstadoOrigemNacional" name="selecaoEstadoOrigemNacional"
                                                onChange="carregarCidadesOrigemNacional(); verificarCampo(this)">
                                                @if ($ultimaRotaSetada != null)
                                                    <option value="{{ $ultimaRotaSetada->estadoDestinoNacional }}"
                                                        selected>
                                                    </option>
                                                @else
                                                    <option value="Paraná" selected></option>
                                                @endif
                                            </select>

                                            @if ($errors->has('selecaoEstadoOrigemNacional'))
                                                <div class="invalid-feedback">
                                                    {{ $errors->first('selecaoEstadoOrigemNacional') }}
                                                </div>
                                            @endif
                                        </div>

                                        <div class="form-group">
                                            <label for="selecaoCidadeOrigemNacional" class="control-label"><strong
                                                    style="color: red">* </strong>Selecione a cidade origem:</label>
                                            <br>
                                            <select
                                                class="custom-select custom-select-lg {{ $errors->has('selecaoCidadeOrigemNacional') ? 'is-invalid' : '' }}"
                                                id="selecaoCidadeOrigemNacional" name="selecaoCidadeOrigemNacional" onChange="verificarCampo(this)">
                                                @if ($ultimaRotaSetada != null)
                                                    <option value="{{ $ultimaRotaSetada->cidadeDestinoNacional }}"
                                                        selected>
                                                    </option>
                                                @else
                                                    @if ($user->department == 'ERCSC')
                                                        <option value="Cascavel" selected></option>
                                                    @elseif($user->department == 'ERMGA')
                                                        <option value="Maringá" selected></option>
                                                    @elseif($user->department == 'ERFCB')
                                                        <option value="Francisco Beltrão" selected></option>
                                                    @elseif($user->department == 'ERGUA')
                                                        <option value="Guarapuava" selected></option>
                                                    @elseif($user->department == 'ERLDA')
                                                        <option value="Londrina" selected></option>
                                                    @elseif($user->department == 'ERPTG')
                                                        <option value="Ponta Grossa" selected></option>
                                                    @else
                                                        <option value="Curitiba" selected></option>
                                                    @endif
                                                @endif
                                            </select>

                                            @if ($errors->has('selecaoCidadeOrigemNacional'))
                                                <div class="invalid-feedback">
                                                    {{ $errors->first('selecaoCidadeOrigemNacional') }}
                                                </div>
                                            @endif
                                        </div>

                                        <div class="form-group">


                                            <label for="dataHoraSaidaNacional" class="control-label"><strong
                                                    style="color: red">* </strong>Data/Hora de saída: </label>
                                            <input data-format="dd/MM/yyyy hh:mm:ss" type="datetime-local"
                                                name="dataHoraSaidaNacional" id="dataHoraSaidaNacional"
                                                placeholder="Data/Hora de saída" onChange="verificarCampo(this)"
                                                class="form-control form-control-lg {{ $errors->has('dataHoraSaidaNacional') ? 'is-invalid' : '' }}">

                                            @if ($errors->has('dataHoraSaidaNacional'))
                                                <div class="invalid-feedback">
                                                    {{ $errors->first('dataHoraSaidaNacional') }}
                                                </div>
                                            @endif
                                            <div class="form-text" id="basic-addon4" style="color: green">Lembre-se de
                                                informar a hora de saída!</div>
                                        </div>

                                    </div>


                                    <!-- Destino -->
                                    <div class="col-12 col-xl-6 mb-4">
                                        <h4 style="color: darkolivegreen"> Destino: </h4>
                                        <div class="form-group">
                                            <label for="selecaoEstadoDestinoNacional" class="control-label"><strong
                                                    style="color: red">* </strong>Selecione o estado destino</label>
                                            <br>

                                            <select
                                                class="custom-select custom-select-lg {{ $errors->has('selecaoEstadoDestinoNacional') ? 'is-invalid' : '' }}"
                                                id="selecaoEstadoDestinoNacional" name="selecaoEstadoDestinoNacional"
                                                onChange="carregarCidadesDestinoNacional(); verificarCampo(this)">

                                                <option value="Paraná" selected></option>
                                            </select>

                                            @if ($errors->has('selecaoEstadoDestinoNacional'))
                                                <div class="invalid-feedback">
                                                    {{ $errors->first('selecaoEstadoDestinoNacional') }}
                                                </div>
                                            @endif
                                        </div>

                                        <div class="form-group">
                                            <label for="selecaoCidadeDestinoNacional" class="control-label"><strong
                                                    style="color: red">* </strong>Selecione a cidade destino</label>
                                            <br>
                                            <input type="text" id="cidadeOrigemGeral" name="cidadeOrigemGeral"
                                                value="{{ count($av->rotas) > 0 ? $rotaOriginal->cidadeOrigemNacional : '' }}"
                                                hidden="true">

                                            <select
                                                class="custom-select custom-select-lg {{ $errors->has('selecaoCidadeDestinoNacional') ? 'is-invalid' : '' }}"
                                                id="selecaoCidadeDestinoNacional" name="selecaoCidadeDestinoNacional"
                                                onChange="verificaSeCidadeOrigem(); verificarCampo(this)">

                                            </select>


                                            @if ($errors->has('selecaoCidadeDestinoNacional'))
                                                <div class="invalid-feedback">
                                                    {{ $errors->first('selecaoCidadeDestinoNacional') }}
                                                </div>
                                            @endif
                                        </div>

                                        <div class="form-group">

                                            <label for="dataHoraChegadaNacional" class="control-label"><strong
                                                    style="color: red">* </strong>Data/Hora de chegada: </label>
                                            <input data-format="dd/MM/yyyy hh:mm:ss" type="datetime-local"
                                                name="dataHoraChegadaNacional" id="dataHoraChegadaNacional"
                                                placeholder="Data/Hora de chegada" onChange="verificarCampo(this)"
                                                class="form-control form-control-lg {{ $errors->has('dataHoraChegadaNacional') ? 'is-invalid' : '' }}">

                                            @if ($errors->has('dataHoraChegadaNacional'))
                                                <div class="invalid-feedback">
                                                    {{ $errors->first('dataHoraChegadaNacional') }}
                                                </div>
                                            @endif

                                            <div class="form-text" id="basic-addon4" style="color: green">Lembre-se de
                                                informar a hora de chegada!</div>
                                        </div>

                                    </div>

                                </div>
                                <div class="divider"></div>

                            </div>
                            <div class="row">

                                <div class="col-md-6">

                                    <div>
                                        <div id="camposFinais">
                                            <div class="form-group" id="campoHotel">
                                                <label for="isReservaHotel" class="control-label"><strong
                                                        style="color: red">* </strong>Você vai precisar de reserva de hotel
                                                    no
                                                    destino?</label>
                                                <br>
                                                <select
                                                    class="custom-select custom-select-lg {{ $errors->has('isReservaHotel') ? 'is-invalid' : '' }}"
                                                    id="isReservaHotel" name="isReservaHotel">
                                                    <option value="0" name="0"> Não</option>
                                                    <option value="1" name="1"> Sim</option>
                                                </select>

                                                @if ($errors->has('isReservaHotel'))
                                                    <div class="invalid-feedback">
                                                        {{ $errors->first('isReservaHotel') }}
                                                    </div>
                                                @endif
                                            </div>


                                            <div class="form-group">
                                                <label for="tipoTransporte" class="control-label"><strong
                                                        style="color: red">* </strong>Qual o tipo de transporte?</label>
                                                <br>
                                                <select
                                                    class="custom-select custom-select-lg {{ $errors->has('tipoTransporte') ? 'is-invalid' : '' }}"
                                                    id="tipoTransporte" name="tipoTransporte" onChange="ativarCampo()">

                                                    @if ($ultimaRotaSetada != null)
                                                        <option value="0" name="0"
                                                            {{ $ultimaRotaSetada->isOnibusLeito == '1' ? "selected='selected'" : '' }}>
                                                            Onibus Leito</option>
                                                        <option value="1" name="1"
                                                            {{ $ultimaRotaSetada->isOnibusConvencional == '1' ? "selected='selected'" : '' }}>
                                                            Onibus convencional</option>
                                                        <option value="2" name="2"
                                                            {{ $ultimaRotaSetada->isVeiculoProprio == '1' ? "selected='selected'" : '' }}>
                                                            Veículo próprio</option>
                                                        <option value="3" name="3"
                                                            {{ $ultimaRotaSetada->isVeiculoEmpresa == '1' ? "selected='selected'" : '' }}>
                                                            Veículo do Paranacidade</option>
                                                        <option value="4" name="4"
                                                            {{ $ultimaRotaSetada->isAereo == '1' ? "selected='selected'" : '' }}>
                                                            Avião</option>
                                                        <option value="5" name="5"
                                                            {{ $ultimaRotaSetada->isOutroMeioTransporte == '1' ? "selected='selected'" : '' }}>
                                                            Outros</option>
                                                        <option value="6" name="6"
                                                            {{ $ultimaRotaSetada->isOutroMeioTransporte == '2' ? "selected='selected'" : '' }}>
                                                            Carona</option>
                                                    @else
                                                        <option value="0" name="0"> Onibus Leito</option>
                                                        <option value="1" name="1"> Onibus convencional
                                                        </option>
                                                        <option value="2" name="2"> Veículo próprio</option>
                                                        <option value="3" name="3"> Veículo do Paranacidade
                                                        </option>
                                                        <option value="4" name="4"> Avião</option>
                                                        <option value="5" name="5"> Outros</option>
                                                        <option value="6" name="6"> Carona</option>
                                                    @endif
                                                </select>

                                                @if ($errors->has('tipoTransporte'))
                                                    <div class="invalid-feedback">
                                                        {{ $errors->first('tipoTransporte') }}
                                                    </div>
                                                @endif
                                            </div>

                                            <div class="form-group" id="selecaoVeiculo"
                                                {{ isset($ultimaRotaSetada) && $ultimaRotaSetada->isVeiculoProprio == '1' ? '' : 'hidden="true"' }}>
                                                <label for="veiculoProprio_id" class="control-label" required>Selecione o
                                                    veículo?</label>
                                                <br>
                                                <select
                                                    class="select select-bordered select-sm w-full max-w-xs {{ $errors->has('veiculoProprio_id') ? 'is-invalid' : '' }}"
                                                    id="veiculoProprio_id" name="veiculoProprio_id">
                                                    <option value="" name=""> Selecione</option>
                                                    @for ($i = 0; $i < count($veiculosProprios); $i++)
                                                        <div>
                                                            @if ($ultimaRotaSetada != null)
                                                                <option value="{{ $veiculosProprios[$i]->id }}"
                                                                    {{ $ultimaRotaSetada->veiculoProprio_id == $veiculosProprios[$i]->id ? "selected='selected'" : '' }}
                                                                    name="{{ $veiculosProprios[$i]->id }}">
                                                                    {{ $veiculosProprios[$i]->modelo }} -
                                                                    {{ $veiculosProprios[$i]->placa }} </option>
                                                            @else
                                                                <option value="{{ $veiculosProprios[$i]->id }}"
                                                                    name="{{ $veiculosProprios[$i]->id }}">
                                                                    {{ $veiculosProprios[$i]->modelo }} -
                                                                    {{ $veiculosProprios[$i]->placa }} </option>
                                                            @endif
                                                        </div>
                                                    @endfor
                                                </select>

                                                @if ($errors->has('veiculoProprio_id'))
                                                    <div class="invalid-feedback">
                                                        {{ $errors->first('veiculoProprio_id') }}
                                                    </div>
                                                @endif
                                            </div>
                                            <br><br>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <div class="card-footer text-right">
                            <button onclick="verificaConsistencia()" id="salvarBt" class="btn btn-success">Salvar
                                Rota</button>
                            <a href="{{ url()->previous() }}" class="btn btn-secondary">Cancelar</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@stop

@section('css')

@stop

@section('js')

    <script type="text/javascript">
        var data1 = null;
        var data2 = null;

        function ativarCampo() {
            var tipoTransporte = document.getElementById("tipoTransporte")
            var veiculoProprio_id = document.getElementById("veiculoProprio_id")

            if (tipoTransporte.value == "2") { //Se for veículo próprio
                document.getElementById("selecaoVeiculo").hidden = false;
            } else {
                document.getElementById("selecaoVeiculo").hidden = true;
                document.getElementById("veiculoProprio_id").value = "";
            }
        }

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            }
        });

        function verificaSeCidadeOrigem() {
            var cidadeDestino = document.getElementById("selecaoCidadeDestinoNacional");
            var cidadeOrigemGeral = document.getElementById("cidadeOrigemGeral");

            if (cidadeDestino.value == cidadeOrigemGeral.value) {
                document.getElementById("campoHotel").hidden = true;
            } else {
                document.getElementById("campoHotel").hidden = false;
            }
        }

        function popularEstadoOrigemNacional() {

            var idPais = 30;

            var nomeEstado = document.getElementById("selecaoEstadoOrigemNacional").value;
            $("#selecaoEstadoOrigemNacional").html('');

            $.getJSON('/states', function(data) {

                opcaoSelecione = '<option value=" "> Selecione </option>';
                $('#selecaoEstadoOrigemNacional').append(opcaoSelecione);

                for (i = 0; i < data.length; i++) {
                    if (data[i].country_id == idPais) {
                        if (data[i].name == nomeEstado) {
                            var valor = "{'id':'" + data[i].id + "', 'name':'" + data[i].name + "'}";

                            opcao = '<option value="' + valor + '" selected>' + data[i].name + '</option>';

                            $('#selecaoEstadoOrigemNacional').append(opcao);
                            carregarCidadesOrigemNacional();
                        } else {
                            var valor = "{'id':'" + data[i].id + "', 'name':'" + data[i].name + "'}";

                            opcao = '<option value="' + valor + '">' + data[i].name + '</option>';

                            $('#selecaoEstadoOrigemNacional').append(opcao);
                        }
                    }
                }
            });
        }

        function popularEstadoDestinoNacional() {

            var idPais = 30;

            var nomeEstado = document.getElementById("selecaoEstadoDestinoNacional").value;
            $("#selecaoEstadoDestinoNacional").html('');

            $.getJSON('/states', function(data) {

                opcaoSelecione = '<option value=" "> Selecione </option>';
                $('#selecaoEstadoDestinoNacional').append(opcaoSelecione);

                for (i = 0; i < data.length; i++) {
                    if (data[i].country_id == idPais) {
                        if (data[i].name == nomeEstado) {
                            var valor = "{'id':'" + data[i].id + "', 'name':'" + data[i].name + "'}";

                            opcao = '<option value="' + valor + '" selected>' + data[i].name + '</option>';

                            $('#selecaoEstadoDestinoNacional').append(opcao);
                            carregarCidadesDestinoNacional();
                        } else {
                            var valor = "{'id':'" + data[i].id + "', 'name':'" + data[i].name + "'}";

                            opcao = '<option value="' + valor + '">' + data[i].name + '</option>';

                            $('#selecaoEstadoDestinoNacional').append(opcao);
                        }
                    }
                }
            });
        }

        function carregarCidadesOrigemNacional() {

            document.getElementById("selecaoEstadoOrigemNacional").disabled = false;
            document.getElementById("selecaoCidadeOrigemNacional").disabled = false;

            var nomeCidade = document.getElementById("selecaoCidadeOrigemNacional").value;

            var idEstado = document.getElementById("selecaoEstadoOrigemNacional").value; //Recebe o valor como String
            var resultado = idEstado.replace(/'/g, "\""); //Adiciona as aspas para deixar no formato JSON
            var objeto = JSON.parse(resultado); //Transforma em JSON

            $("#selecaoCidadeOrigemNacional").html('');
            $("#selecaoCidadeOrigemNacional").html('<option value="">Selecione</option>');
            document.getElementById("selecaoCidadeOrigemNacional").value = "";

            $.getJSON('/cities', function(data) {

                for (i = 0; i < data.length; i++) {

                    if (data[i].state_id == objeto.id) {
                        if (data[i].name == nomeCidade) {
                            opcao = '<option value="' + data[i].name + '" selected>' + data[i].name + '</option>';
                            $('#selecaoCidadeOrigemNacional').append(opcao);
                        } else {
                            opcao = '<option value="' + data[i].name + '">' + data[i].name + '</option>';
                            $('#selecaoCidadeOrigemNacional').append(opcao);
                        }
                    }
                }
            });
        }

        function carregarCidadesDestinoNacional() {

            document.getElementById("selecaoEstadoDestinoNacional").disabled = false;
            document.getElementById("selecaoCidadeDestinoNacional").disabled = false;

            var idEstado = document.getElementById("selecaoEstadoDestinoNacional").value; //Recebe o valur como String
            var resultado = idEstado.replace(/'/g, "\""); //Adiciona as aspas para deixar no formato JSON
            var objeto = JSON.parse(resultado); //Transforma em JSON

            $("#selecaoCidadeDestinoNacional").html('');
            $("#selecaoCidadeDestinoNacional").html('<option value="">Selecione</option>');
            document.getElementById("selecaoCidadeDestinoNacional").value = "";

            $.getJSON('/cities', function(data) {

                for (i = 0; i < data.length; i++) {

                    if (data[i].state_id == objeto.id) {
                        opcao = '<option value="' + data[i].name + '">' + data[i].name + '</option>';
                        $('#selecaoCidadeDestinoNacional').append(opcao);
                    }
                }
            });
        }

        function resetarCampoOrigemNacional() {
            document.getElementById("selecaoEstadoOrigemNacional").value = "";
            $("#selecaoCidadeOrigemNacional").html('');
            $("#selecaoCidadeOrigemNacional").html('<option value="">Selecione</option>');
            document.getElementById("selecaoCidadeOrigemNacional").value = "";
            document.getElementById("selecaoEstadoOrigemNacional").disabled = false;
            document.getElementById("selecaoCidadeOrigemNacional").disabled = true;
            popularEstadoOrigemNacional();
            popularEstadoDestinoNacional();
        }

        function resetarCampoDestinoNacional() {
            document.getElementById("selecaoEstadoDestinoNacional").value = "";
            $("#selecaoCidadeDestinoNacional").html('');
            $("#selecaoCidadeDestinoNacional").html('<option value="">Selecione</option>');
            document.getElementById("selecaoCidadeDestinoNacional").value = "";
            document.getElementById("selecaoEstadoDestinoNacional").disabled = false;
            document.getElementById("selecaoCidadeDestinoNacional").disabled = true;
            popularEstadoOrigemNacional();
            popularEstadoDestinoNacional();
        }

        //Assim que a tela carrega, aciona automaticamente essas duas funções ------------------------
        $(function() {

            popularEstadoOrigemNacional();
            popularEstadoDestinoNacional();

        })

        function verificarCampo(campo) {
            if (campo.value) {
                campo.style.backgroundColor = '#d4edda'; // Verde claro
            } else {
                campo.style.backgroundColor = '#f8d7da'; // Vermelho claro
            }
        }

        function verificaConsistencia() {
            var campos = document.querySelectorAll(
                '#tipoTransporte, #isReservaHotel, #selecaoEstadoOrigemNacional, #selecaoCidadeOrigemNacional, #dataHoraSaidaNacional, #selecaoEstadoDestinoNacional, #selecaoCidadeDestinoNacional, #dataHoraChegadaNacional'
            );

            var existemPendencias = [];
            campos.forEach(function(campo) {
                if (!campo.value) {
                    existemPendencias.push(campo.id);
                }
            });
            var nomeDosCampos = [];
            existemPendencias.forEach(element => {
                if (element == "tipoTransporte") {
                    nomeDosCampos.push("Tipo de transporte");
                }
                if (element == "isReservaHotel") {
                    nomeDosCampos.push("Reserva de hotel");
                }
                if (element == "selecaoEstadoOrigemNacional") {
                    nomeDosCampos.push("Estado de origem");
                }
                if (element == "selecaoCidadeOrigemNacional") {
                    nomeDosCampos.push("Cidade de origem");
                }
                if (element == "dataHoraSaidaNacional") {
                    nomeDosCampos.push("Data/Hora de saída");
                }
                if (element == "selecaoEstadoDestinoNacional") {
                    nomeDosCampos.push("Estado de destino");
                }
                if (element == "selecaoCidadeDestinoNacional") {
                    nomeDosCampos.push("Cidade de destino");
                }
                if (element == "dataHoraChegadaNacional") {
                    nomeDosCampos.push("Data/Hora de chegada");
                }
                if (element == "dataHoraSaidaVoltaNacional") {
                    nomeDosCampos.push("Data/Hora de saída na volta");
                }
                if (element == "dataHoraChegadaVoltaNacional") {
                    nomeDosCampos.push("Data/Hora de chegada na volta");
                }
            });

            if (existemPendencias.length > 0) {
                Swal.fire({
                    title: 'Preencha todos os campos obrigatórios!',
                    text: 'Campos pendentes: ' + nomeDosCampos.join(', '),
                });
            } else {
                document.getElementById('formPrincipal').submit();
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            var campos = document.querySelectorAll(
                '#tipoTransporte, #isReservaHotel, #selecaoEstadoOrigemNacional, #selecaoCidadeOrigemNacional, #dataHoraSaidaNacional, #selecaoEstadoDestinoNacional, #selecaoCidadeDestinoNacional, #dataHoraChegadaNacional'
            );
            campos.forEach(function(campo) {
                verificarCampo(campo);
            });
        });
    </script>
@stop
