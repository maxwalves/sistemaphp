@extends('adminlte::page')

@section('title', 'Editar Rota')

@section('content_header')
    <h1>Editar Rota</h1>
@stop

@section('content')

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Editar Rota</h3>
                    </div>
                    <div class="card-body">
                        <form action="/rotaspc/update/{{ $rota->id }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <div class="col-md-5">
                                    <a href="/rotaspc/rotas/{{ $av->id }}" type="submit" class="btn btn-warning"><i
                                            class="fas fa-arrow-left"></i> VOLTAR PARA PC SEM SALVAR</a>
                                </div>
                                <div class="col-md-7">
                                    <label for="idav"> <strong>Edição de rota da AV nº </strong>
                                    </label>
                                    <input type="text" style="width: 50px" value="{{ $av->id }}" id="idav"
                                        name="idav" readonly>

                                    <h4> <strong>Data da AV:
                                            {{ date('d/m/Y', strtotime($av->dataCriacao)) }}</strong> </h4>
                                    <input type="text" hidden="true" value="sim" name="isPc" id="isPc">
                                </div>
                            </div>

                            <div style="padding-left: 100px" class="form-group">

                                @if (
                                    $errors->has('selecaoContinenteOrigem') ||
                                        $errors->has('selecaoPaisOrigem') ||
                                        $errors->has('selecaoEstadoOrigem') ||
                                        $errors->has('selecaoCidadeOrigem') ||
                                        $errors->has('selecaoEstadoOrigemNacional') ||
                                        $errors->has('selecaoCidadeOrigemNacional') ||
                                        $errors->has('selecaoEstadoDestinoNacional') ||
                                        $errors->has('selecaoCidadeDestinoNacional') ||
                                        $errors->has('tipoTransporte') ||
                                        $errors->has('veiculoProprio_id'))
                                    <div>
                                        <p style="color: red"> <strong>Existem campos pendentes de preenchimento! Selecione
                                                o tipo de viagem e verifique os campos!</strong></p>
                                    </div>
                                @endif
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
                                            <label for="selecaoEstadoOrigemNacional" class="control-label">Selecione o
                                                estado origem:</label>
                                            <br>
                                            <select
                                                class="custom-select custom-select-lg {{ $errors->has('selecaoEstadoOrigemNacional') ? 'is-invalid' : '' }}"
                                                id="selecaoEstadoOrigemNacional" name="selecaoEstadoOrigemNacional"
                                                onChange="carregarCidadesOrigemNacional()">
                                                <option value="{{ $rota->estadoOrigemNacional }}" selected></option>
                                            </select>

                                            @if ($errors->has('selecaoEstadoOrigemNacional'))
                                                <div class="invalid-feedback">
                                                    {{ $errors->first('selecaoEstadoOrigemNacional') }}
                                                </div>
                                            @endif
                                        </div>

                                        <div class="form-group">
                                            <label for="selecaoCidadeOrigemNacional" class="control-label">Selecione a
                                                cidade origem:</label>
                                            <br>
                                            <select
                                                class="custom-select custom-select-lg {{ $errors->has('selecaoCidadeOrigemNacional') ? 'is-invalid' : '' }}"
                                                id="selecaoCidadeOrigemNacional" name="selecaoCidadeOrigemNacional">
                                                <option value="{{ $rota->cidadeOrigemNacional }}" selected></option>
                                            </select>

                                            @if ($errors->has('selecaoCidadeOrigemNacional'))
                                                <div class="invalid-feedback">
                                                    {{ $errors->first('selecaoCidadeOrigemNacional') }}
                                                </div>
                                            @endif
                                        </div>

                                        <div class="form-group">
                                            <div id="dataHoraSaidaNacional" class="input-append date">
                                                <label for="dataHoraSaidaNacional" class="control-label">Data/Hora de saída:
                                                </label>
                                                <input data-format="dd/MM/yyyy hh:mm:ss" type="datetime-local"
                                                    name="dataHoraSaidaNacional" class="form-control form-control-lg"
                                                    id="dataHoraSaidaNacional" placeholder="Data/Hora de saída"
                                                    value="{{ $rota->isViagemInternacional == '0' ? $rota->dataHoraSaida : '' }}">
                                            </div>
                                        </div>

                                    </div>




                                    <!-- Destino -->
                                    <div class="col-12 col-xl-6 mb-4">
                                        <h4 style="color: darkolivegreen"> Destino: </h4>
                                        <div class="form-group">
                                            <label for="selecaoEstadoDestinoNacional" class="control-label">Selecione o
                                                estado destino</label>
                                            <br>
                                            <select
                                                class="custom-select custom-select-lg {{ $errors->has('selecaoEstadoDestinoNacional') ? 'is-invalid' : '' }}"
                                                id="selecaoEstadoDestinoNacional" name="selecaoEstadoDestinoNacional"
                                                onChange="carregarCidadesDestinoNacional()">
                                                <option value="{{ $rota->estadoDestinoNacional }}" selected></option>

                                            </select>

                                            @if ($errors->has('selecaoEstadoDestinoNacional'))
                                                <div class="invalid-feedback">
                                                    {{ $errors->first('selecaoEstadoDestinoNacional') }}
                                                </div>
                                            @endif
                                        </div>

                                        <div class="form-group">
                                            <label for="selecaoCidadeDestinoNacional" class="control-label">Selecione a
                                                cidade destino</label>
                                            <br>
                                            <select
                                                class="custom-select custom-select-lg {{ $errors->has('selecaoCidadeDestinoNacional') ? 'is-invalid' : '' }}"
                                                id="selecaoCidadeDestinoNacional" name="selecaoCidadeDestinoNacional">
                                                <option value="{{ $rota->cidadeDestinoNacional }}" selected></option>
                                            </select>

                                            @if ($errors->has('selecaoCidadeDestinoNacional'))
                                                <div class="invalid-feedback">
                                                    {{ $errors->first('selecaoCidadeDestinoNacional') }}
                                                </div>
                                            @endif
                                        </div>

                                        <div class="form-group">
                                            <div id="dataHoraChegadaNacional" class="input-append date">
                                                <label for="dataHoraChegadaNacional" class="control-label">Data/Hora de
                                                    chegada: </label>
                                                <input data-format="dd/MM/yyyy hh:mm:ss" type="datetime-local"
                                                    name="dataHoraChegadaNacional" class="form-control form-control-lg"
                                                    id="dataHoraChegadaNacional" placeholder="Data/Hora de chegada"
                                                    value="{{ $rota->isViagemInternacional == '0' ? $rota->dataHoraChegada : '' }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="row" style="background-color: lightgrey">

                                <div class="col-md-6">

                                    <div>
                                        <div id="camposFinais">
                                            <div class="form-group">
                                                <label for="isReservaHotel" class="control-label">Você vai precisar de
                                                    reserva de hotel?</label>
                                                <br>
                                                <select
                                                    class="custom-select custom-select-lg {{ $errors->has('isReservaHotel') ? 'is-invalid' : '' }}"
                                                    id="isReservaHotel" name="isReservaHotel">
                                                    <option value="0" name="0"
                                                        {{ $rota->isReservaHotel == '0' ? "selected='selected'" : '' }}>
                                                        Não</option>
                                                    <option value="1" name="1"
                                                        {{ $rota->isReservaHotel == '1' ? "selected='selected'" : '' }}>
                                                        Sim</option>
                                                </select>

                                                @if ($errors->has('isReservaHotel'))
                                                    <div class="invalid-feedback">
                                                        {{ $errors->first('isReservaHotel') }}
                                                    </div>
                                                @endif
                                            </div>


                                            <div class="form-group">
                                                <label for="tipoTransporte" class="control-label">Qual o tipo de
                                                    transporte?</label>
                                                <br>
                                                <select
                                                    class="custom-select custom-select-lg {{ $errors->has('tipoTransporte') ? 'is-invalid' : '' }}"
                                                    id="tipoTransporte" name="tipoTransporte" onChange="ativarCampo()">
                                                    <option value="0" name="0"
                                                        {{ $rota->isOnibusLeito == '1' ? "selected='selected'" : '' }}>
                                                        Onibus Leito</option>
                                                    <option value="1" name="1"
                                                        {{ $rota->isOnibusConvencional == '1' ? "selected='selected'" : '' }}>
                                                        Onibus convencional</option>
                                                    <option value="2" name="2"
                                                        {{ $rota->isVeiculoProprio == '1' ? "selected='selected'" : '' }}>
                                                        Veículo próprio</option>
                                                    <option value="3" name="3"
                                                        {{ $rota->isVeiculoEmpresa == '1' ? "selected='selected'" : '' }}>
                                                        Veículo do Paranacidade</option>
                                                    <option value="4" name="4"
                                                        {{ $rota->isAereo == '1' ? "selected='selected'" : '' }}> Avião
                                                    </option>
                                                    <option value="5" name="5"
                                                        {{ $rota->isOutroMeioTransporte == '1' ? "selected='selected'" : '' }}>
                                                        Outros</option>
                                                    <option value="6" name="6"
                                                        {{ $rota->isOutroMeioTransporte == '2' ? "selected='selected'" : '' }}>
                                                        Carona</option>
                                                </select>

                                                @if ($errors->has('tipoTransporte'))
                                                    <div class="invalid-feedback">
                                                        {{ $errors->first('tipoTransporte') }}
                                                    </div>
                                                @endif
                                            </div>

                                            <div class="form-group" id="selecaoVeiculo"
                                                {{ $rota->isVeiculoProprio == '1' ? '' : 'hidden="true"' }}>
                                                <label for="veiculoProprio_id" class="control-label" required>Selecione o
                                                    veículo?</label>
                                                <br>
                                                <select
                                                    class="select select-bordered select-sm w-full max-w-xs {{ $errors->has('veiculoProprio_id') ? 'is-invalid' : '' }}"
                                                    id="veiculoProprio_id" name="veiculoProprio_id">
                                                    <option value="" name=""> Selecione</option>
                                                    @for ($i = 0; $i < count($veiculosProprios); $i++)
                                                        <div>
                                                            <option value="{{ $veiculosProprios[$i]->id }}"
                                                                {{ $rota->veiculoProprio_id == $veiculosProprios[$i]->id ? "selected='selected'" : '' }}
                                                                name="{{ $veiculosProprios[$i]->id }}">
                                                                {{ $veiculosProprios[$i]->modelo }} -
                                                                {{ $veiculosProprios[$i]->placa }} </option>
                                                        </div>
                                                    @endfor
                                                </select>

                                                @if ($errors->has('veiculoProprio_id'))
                                                    <div class="invalid-feedback">
                                                        {{ $errors->first('veiculoProprio_id') }}
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer text-right">
                                <button type="submit" id="salvarBt" class="btn btn-success">SALVAR ROTA E VOLTAR PARA
                                    PC</button>
                                <a href="{{ url()->previous() }}" class="btn btn-secondary">Cancelar</a>
                            </div>
                        </form>
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

            var nomeCidade = document.getElementById("selecaoCidadeOrigemNacional")
                .value; //Recupera o valor que ta salvo no banco

            var idEstado = document.getElementById("selecaoEstadoOrigemNacional").value; //Recebe o valur como String
            var resultado = idEstado.replace(/'/g, "\""); //Adiciona as aspas para deixar no formato JSON
            var objeto = JSON.parse(resultado); //Transforma em JSON

            $("#selecaoCidadeOrigemNacional").html('');
            $("#selecaoCidadeOrigemNacional").html('<option value="">Selecione</option>');
            document.getElementById("selecaoCidadeOrigemNacional").value = "";

            $.getJSON('/cities', function(data) {

                data.sort(function(a, b) {
                    var nameA = a.name.toUpperCase(); // ignore upper and lowercase
                    var nameB = b.name.toUpperCase(); // ignore upper and lowercase
                    if (nameA < nameB) {
                        return -1;
                    }
                    if (nameA > nameB) {
                        return 1;
                    }
                    // names must be equal
                    return 0;
                });

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

            var nomeCidade = document.getElementById("selecaoCidadeDestinoNacional")
                .value; //Recupera o valor que ta salvo no banco

            var idEstado = document.getElementById("selecaoEstadoDestinoNacional").value; //Recebe o valur como String
            var resultado = idEstado.replace(/'/g, "\""); //Adiciona as aspas para deixar no formato JSON
            var objeto = JSON.parse(resultado); //Transforma em JSON

            $("#selecaoCidadeDestinoNacional").html('');
            $("#selecaoCidadeDestinoNacional").html('<option value="">Selecione</option>');
            document.getElementById("selecaoCidadeDestinoNacional").value = "";

            $.getJSON('/cities', function(data) {

                data.sort(function(a, b) {
                    var nameA = a.name.toUpperCase(); // ignore upper and lowercase
                    var nameB = b.name.toUpperCase(); // ignore upper and lowercase
                    if (nameA < nameB) {
                        return -1;
                    }
                    if (nameA > nameB) {
                        return 1;
                    }
                    // names must be equal
                    return 0;
                });

                for (i = 0; i < data.length; i++) {

                    if (data[i].state_id == objeto.id) {
                        if (data[i].name == nomeCidade) {
                            opcao = '<option value="' + data[i].name + '" selected>' + data[i].name + '</option>';
                            $('#selecaoCidadeDestinoNacional').append(opcao);
                        } else {
                            opcao = '<option value="' + data[i].name + '">' + data[i].name + '</option>';
                            $('#selecaoCidadeDestinoNacional').append(opcao);
                        }
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

            document.getElementById("isNacional").hidden = false;

            popularEstadoOrigemNacional();
            popularEstadoDestinoNacional();

            //-------------------------------------------Define a data mínima para a chegada logo no início-------------------------------------------
            var inputDatetimeLocal = document.querySelector('.classeDataHoraSaidaNacional');
            var inputDatetimeLocal2 = document.querySelector('.classeDataHoraChegadaNacional');

            // Obter o valor do campo datetime-local
            var valor = inputDatetimeLocal.value;

            // Definir a data mínima para dataHoraSaidaVoltaNacional com base no valor selecionado em dataHoraSaidaNacional
            inputDatetimeLocal2.min = valor;

            //-------------------------------------------Monitora dataHoraSaidaNacional e define data mínima para a chegada quando clicado -------------------
            var dataHoraSaidaNacional = document.getElementById('dataHoraSaidaNacional');
            dataHoraSaidaNacional.addEventListener('change', function() {
                var inputDatetimeLocal = document.querySelector('.classeDataHoraSaidaNacional');
                var inputDatetimeLocal2 = document.querySelector('.classeDataHoraChegadaNacional');

                // Obter o valor do campo datetime-local
                var valor = inputDatetimeLocal.value;

                // Definir a data mínima para dataHoraSaidaVoltaNacional com base no valor selecionado em dataHoraSaidaNacional
                inputDatetimeLocal2.min = valor;
            });
        })
    </script>

@stop
