@extends('adminlte::page')

@section('title', 'Criar Rota')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Editar Rota</h3>
                    </div>
                    <div class="card-body">
                        <form action="/rotas/update/{{ $rota->id }}" method="POST" enctype="multipart/form-data"
                            id="formPrincipal">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <br>
                                        <a href="/rotas/rotas/{{ $av->id }}" type="submit" class="btn btn-warning"><i
                                                class="fas fa-arrow-left"></i></a>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="idav">Autorização de Viagem nº</label>
                                        <input type="text" style="width: 50px" value="{{ $av->id }}" id="idav"
                                            name="idav" readonly>
                                    </div>
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

                            <div>
                                <h3 class="text-success"><i class="fas fa-bus"></i> VIAGEM NACIONAL</h3>

                                <div class="row">
                                    <!-- Origem -->
                                    <div class="col-12 col-xl-6 mb-4">
                                        <h4 class="text-darkolivegreen">Origem:</h4>

                                        <div class="form-group">
                                            <label for="selecaoEstadoOrigemNacional" class="form-label">
                                                <strong class="text-danger">*</strong> Selecione o estado de origem:
                                            </label>

                                            <select
                                                class="custom-select custom-select-lg {{ $errors->has('selecaoEstadoOrigemNacional') ? 'is-invalid' : '' }}"
                                                id="selecaoEstadoOrigemNacional" name="selecaoEstadoOrigemNacional"
                                                onChange="carregarCidadesOrigemNacional(); verificarCampo(this)">
                                                <option value="{{ $rota->estadoOrigemNacional }}" selected></option>
                                            </select>

                                            @if ($errors->has('selecaoEstadoOrigemNacional'))
                                                <div class="invalid-feedback">
                                                    {{ $errors->first('selecaoEstadoOrigemNacional') }}
                                                </div>
                                            @endif
                                        </div>

                                        <div class="form-group">
                                            <label for="selecaoCidadeOrigemNacional" class="form-label">
                                                <strong class="text-danger">*</strong> Selecione a cidade de origem:
                                            </label>
                                            <!-- Selecione a Cidade de Origem -->
                                            <select
                                                class="custom-select custom-select-lg {{ $errors->has('selecaoCidadeOrigemNacional') ? 'is-invalid' : '' }}"
                                                id="selecaoCidadeOrigemNacional" name="selecaoCidadeOrigemNacional"
                                                onChange="verificarCampo(this)">
                                                <option value="{{ $rota->cidadeOrigemNacional }}" selected></option>
                                            </select>

                                            @if ($errors->has('selecaoCidadeOrigemNacional'))
                                                <div class="invalid-feedback">
                                                    {{ $errors->first('selecaoCidadeOrigemNacional') }}
                                                </div>
                                            @endif
                                        </div>

                                        <div class="form-group">
                                            <label for="dataHoraSaidaNacional" class="form-label">
                                                <strong class="text-danger">*</strong> Data/Hora de saída:
                                            </label>
                                            <!-- Data/Hora de Saída Nacional -->
                                            <input type="datetime-local" id="dataHoraSaidaNacional"
                                                name="dataHoraSaidaNacional"
                                                class="form-control form-control-lg {{ $errors->has('dataHoraSaidaNacional') ? 'is-invalid' : '' }}"
                                                placeholder="Data/Hora de saída" onChange="verificarCampo(this)"
                                                value="{{ $rota->isViagemInternacional == '0' ? $rota->dataHoraSaida : '' }}">

                                            @if ($errors->has('dataHoraSaidaNacional'))
                                                <div class="invalid-feedback">
                                                    {{ $errors->first('dataHoraSaidaNacional') }}
                                                </div>
                                            @endif
                                            <div class="form-text text-success">Lembre-se de informar a hora de saída!</div>
                                        </div>
                                    </div>

                                    <!-- Destino -->
                                    <div class="col-12 col-xl-6 mb-4">
                                        <h4 class="text-darkolivegreen">Destino:</h4>

                                        <div class="form-group">
                                            <label for="selecaoEstadoDestinoNacional" class="form-label">
                                                <strong class="text-danger">*</strong> Selecione o estado de destino:
                                            </label>
                                            <!-- Selecione o Estado de Destino Nacional -->
                                            <select
                                                class="custom-select custom-select-lg {{ $errors->has('selecaoEstadoDestinoNacional') ? 'is-invalid' : '' }}"
                                                id="selecaoEstadoDestinoNacional" name="selecaoEstadoDestinoNacional"
                                                onChange="carregarCidadesDestinoNacional(); verificarCampo(this)">
                                                <option value="{{ $rota->estadoDestinoNacional }}" selected></option>

                                            </select>

                                            @if ($errors->has('selecaoEstadoDestinoNacional'))
                                                <div class="invalid-feedback">
                                                    {{ $errors->first('selecaoEstadoDestinoNacional') }}
                                                </div>
                                            @endif
                                        </div>

                                        <div class="form-group">
                                            <label for="selecaoCidadeDestinoNacional" class="form-label">
                                                <strong class="text-danger">*</strong> Selecione a cidade de destino:
                                            </label>
                                            <!-- Selecione a Cidade de Destino Nacional -->
                                            <select
                                                class="custom-select custom-select-lg {{ $errors->has('selecaoCidadeDestinoNacional') ? 'is-invalid' : '' }}"
                                                id="selecaoCidadeDestinoNacional" name="selecaoCidadeDestinoNacional"
                                                onChange="verificarCampo(this)">
                                                <option value="{{ $rota->cidadeDestinoNacional }}" selected></option>
                                            </select>

                                            @if ($errors->has('selecaoCidadeDestinoNacional'))
                                                <div class="invalid-feedback">
                                                    {{ $errors->first('selecaoCidadeDestinoNacional') }}
                                                </div>
                                            @endif
                                        </div>

                                        <div class="form-group">
                                            <label for="dataHoraChegadaNacional" class="form-label">
                                                <strong class="text-danger">*</strong> Data/Hora de chegada:
                                            </label>
                                            <!-- Data/Hora de Chegada Nacional -->
                                            <input type="datetime-local" id="dataHoraChegadaNacional"
                                                name="dataHoraChegadaNacional"
                                                class="form-control form-control-lg {{ $errors->has('dataHoraChegadaNacional') ? 'is-invalid' : '' }}"
                                                placeholder="Data/Hora de chegada" onChange="verificarCampo(this)"
                                                value="{{ $rota->isViagemInternacional == '0' ? $rota->dataHoraChegada : '' }}">

                                            @if ($errors->has('dataHoraChegadaNacional'))
                                                <div class="invalid-feedback">
                                                    {{ $errors->first('dataHoraChegadaNacional') }}
                                                </div>
                                            @endif
                                            <div class="form-text text-success">Lembre-se de informar a hora de chegada!
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <br>
                            <div class="row bg-light p-4 rounded">

                                <div class="col-md-6">

                                    <div>
                                        <div id="camposFinais">
                                            <!-- Reserva de Hotel -->
                                            <div class="form-group mb-4" id="campoHotel">
                                                <label for="isReservaHotel" class="form-label">
                                                    <strong class="text-danger">*</strong> Você vai precisar de reserva de
                                                    hotel no destino?
                                                </label>

                                                <select
                                                    class="custom-select custom-select-lg {{ $errors->has('isReservaHotel') ? 'is-invalid' : '' }}"
                                                    id="isReservaHotel" name="isReservaHotel"
                                                    onChange="verificarCampo(this)">
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

                                            <!-- Tipo de Transporte -->
                                            <div class="form-group mb-4">
                                                <label for="tipoTransporte" class="form-label">
                                                    <strong class="text-danger">*</strong> Qual o tipo de transporte?
                                                </label>

                                                <select
                                                    class="custom-select custom-select-lg {{ $errors->has('tipoTransporte') ? 'is-invalid' : '' }}"
                                                    id="tipoTransporte" name="tipoTransporte"
                                                    onChange="ativarCampo(); verificarCampo(this)">
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

                                            <!-- Seleção de Veículo -->
                                            <div class="form-group mb-4" id="selecaoVeiculo" hidden>
                                                <label for="veiculoProprio_id" class="form-label">
                                                    Selecione o veículo
                                                </label>

                                                <select
                                                    class="form-select {{ $errors->has('veiculoProprio_id') ? 'is-invalid' : '' }}"
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

    <!-- Overlay de carregamento -->
    <div class="modal fade" id="loadingOverlay" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="overlay-wrapper">
                <i class="fas fa-3x fa-sync-alt fa-spin mb-3"></i>
                <div class="text-bold">Carregando...</div>
            </div>
        </div>
    </div>

@endsection

@section('css')

    <link href="{{ asset('/chosen/chosen.min.css') }}" rel="stylesheet">

@stop

@section('js')
    <script src="{{ asset('/chosen/chosen.proto.min.js') }}"></script>
    <script src="{{ asset('/chosen/chosen.jquery.min.js') }}"></script>

    <script type="text/javascript">
        $('#custom-tabs-five-overlay').css('display', 'block');

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

            $('#custom-tabs-five-overlay').css('display', 'block');

            popularEstadoOrigemNacional();
            popularEstadoDestinoNacional();

            setTimeout(function() {
                $('#custom-tabs-five-overlay').css('display', 'none');
            }, 500);

            ativarCampo();
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
                '#dataHoraSaidaVoltaNacional, #dataHoraChegadaVoltaNacional, #tipoTransporte, #isReservaHotel, #selecaoEstadoOrigemNacional, #selecaoCidadeOrigemNacional, #dataHoraSaidaNacional, #selecaoEstadoDestinoNacional, #selecaoCidadeDestinoNacional, #dataHoraChegadaNacional'
            );
            campos.forEach(function(campo) {
                verificarCampo(campo);
            });
        });
    </script>
@stop
