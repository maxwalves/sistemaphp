@extends('adminlte::page')

@section('title', 'Editar rota')

@section('content_header')
    <h1>Editar rota</h1>
@stop

@section('content')

    <div id="container">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Editar Nova Rota</h3>
                        </div>
                        <div class="card-body">
                            <form action="/rotas/update/{{ $rota->id }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <a href="/rotas/rotas/{{ $av->id }}" type="submit"
                                                class="btn btn-warning"><i class="fas fa-arrow-left"></i></a>
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <label for="idav"> <strong>Edição de rota da Autorização de Viagem nº </strong>
                                        </label>
                                        <input type="text" style="width: 50px" value="{{ $av->id }}" id="idav"
                                            name="idav" readonly>

                                        <h4> <strong>Data da Autorização de Viagem:
                                                {{ date('d/m/Y', strtotime($av->dataCriacao)) }}</strong> </h4>
                                    </div>
                                </div>

                                <div style="padding-left: 100px" class="form-group">

                                    @if (
                                        $errors->has('isViagemInternacional') ||
                                            $errors->has('selecaoContinenteOrigem') ||
                                            $errors->has('selecaoPaisOrigem') ||
                                            $errors->has('selecaoEstadoOrigem') ||
                                            $errors->has('selecaoCidadeOrigem') ||
                                            $errors->has('selecaoContinenteDestinoInternacional') ||
                                            $errors->has('selecaoPaisDestinoInternacional') ||
                                            $errors->has('selecaoEstadoDestinoInternacional') ||
                                            $errors->has('selecaoCidadeDestinoInternacional') ||
                                            $errors->has('selecaoEstadoOrigemNacional') ||
                                            $errors->has('selecaoCidadeOrigemNacional') ||
                                            $errors->has('selecaoEstadoDestinoNacional') ||
                                            $errors->has('selecaoCidadeDestinoNacional') ||
                                            $errors->has('tipoTransporte') ||
                                            $errors->has('veiculoProprio_id'))
                                        <div>
                                            <p style="color: red"> <strong>Existem campos pendentes de preenchimento!
                                                    Selecione o tipo de viagem e verifique os campos!</strong></p>
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
                                                            <img src="{{ asset('/img/aviaosubindo.png') }}"
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

                                                        {{ $rotaExibicao->isViagemInternacional == 0 ? $rotaExibicao->cidadeOrigemNacional : $rotaExibicao->cidadeOrigemInternacional }}

                                                    </td>
                                                    <td> {{ date('d/m/Y H:i', strtotime($rotaExibicao->dataHoraSaida)) }}
                                                    </td>

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

                                <input type="text" id="isViagemInternacional" name="isViagemInternacional" value="1"
                                    hidden>

                                <div id="isInternacional">
                                    <br>
                                    <h3 style="color: brown"> <ion-icon name="airplane-outline"></ion-icon> VIAGEM
                                        INTERNACIONAL</h3>
                                    <div class="row">
                                        <div class="col-12 col-xl-6 mb-4">
                                            {{-- CAMPOS DE ORIGEM INTERNACIONAL ------------------------------- --}}
                                            
                                            <h4 style="color: crimson"> Origem: </h4>
                                            <div class="form-group">
                                                <label for="selecaoContinenteOrigem" class="control-label"><strong
                                                        style="color: red">* </strong>Selecione o continente origem</label>
                                                <br>
                                                <select
                                                    class="custom-select custom-select-lg {{ $errors->has('selecaoContinenteOrigem') ? 'is-invalid' : '' }}"
                                                    id="selecaoContinenteOrigem" name="selecaoContinenteOrigem">
                                                    <option value="0" name="0"> Selecione</option>
                                                    <option value="1" name="1"
                                                        {{ $rota->continenteOrigemInternacional == '1' ? "selected='selected'" : '' }}>
                                                        América Latina ou América Central</option>
                                                    <option value="2" name="2"
                                                        {{ $rota->continenteOrigemInternacional == '2' ? "selected='selected'" : '' }}>
                                                        América do Norte</option>
                                                    <option value="3" name="3"
                                                        {{ $rota->continenteOrigemInternacional == '3' ? "selected='selected'" : '' }}>
                                                        Europa</option>
                                                    <option value="4" name="4"
                                                        {{ $rota->continenteOrigemInternacional == '4' ? "selected='selected'" : '' }}>
                                                        África</option>
                                                    <option value="5" name="5"
                                                        {{ $rota->continenteOrigemInternacional == '5' ? "selected='selected'" : '' }}>
                                                        Ásia</option>
                                                </select>

                                                @if ($errors->has('selecaoContinenteOrigem'))
                                                    <div class="invalid-feedback">
                                                        {{ $errors->first('selecaoContinenteOrigem') }}
                                                    </div>
                                                @endif
                                            </div>

                                            <div class="form-group">
                                                <label for="selecaoPaisOrigem" class="control-label"><strong
                                                        style="color: red">* </strong>Selecione o país origem:</label>
                                                <br>

                                                <select
                                                    class="custom-select custom-select-lg {{ $errors->has('selecaoPaisOrigem') ? 'is-invalid' : '' }}"
                                                    id="selecaoPaisOrigem" name="selecaoPaisOrigem">
                                                    <option value="{{ $rota->paisOrigemInternacional }}" selected></option>

                                                </select>

                                                @if ($errors->has('selecaoPaisOrigem'))
                                                    <div class="invalid-feedback">
                                                        {{ $errors->first('selecaoPaisOrigem') }}
                                                    </div>
                                                @endif
                                            </div>

                                            <div class="form-group">
                                                <label for="selecaoEstadoOrigem" class="control-label"><strong
                                                        style="color: red">* </strong>Digite o nome do estado/província
                                                    origem:</label>
                                                <br>
                                                <input
                                                    class="form-control form-control-lg {{ $errors->has('selecaoEstadoOrigem') ? 'is-invalid' : '' }}"
                                                    type="text" id="selecaoEstadoOrigem" name="selecaoEstadoOrigem"
                                                    value="{{ $rota->estadoOrigemInternacional }}">

                                                @if ($errors->has('selecaoEstadoOrigem'))
                                                    <div class="invalid-feedback">
                                                        {{ $errors->first('selecaoEstadoOrigem') }}
                                                    </div>
                                                @endif
                                            </div>

                                            <div class="form-group">
                                                <label for="selecaoCidadeOrigem" class="control-label"><strong
                                                        style="color: red">* </strong>Digite o nome da cidade de
                                                    origem:</label>
                                                <br>

                                                <input
                                                    class="form-control form-control-lg {{ $errors->has('selecaoCidadeOrigem') ? 'is-invalid' : '' }}"
                                                    type="text" id="selecaoCidadeOrigem" name="selecaoCidadeOrigem"
                                                    value="{{ $rota->cidadeOrigemInternacional }}">

                                                @if ($errors->has('selecaoCidadeOrigem'))
                                                    <div class="invalid-feedback">
                                                        {{ $errors->first('selecaoCidadeOrigem') }}
                                                    </div>
                                                @endif
                                            </div>

                                            <div class="form-group">
                                                <div id="dataHoraSaidaInternacional" class="input-append date">
                                                    <label for="dataHoraSaidaInternacional" class="control-label"><strong
                                                            style="color: red">* </strong>Data/Hora de saída: </label>
                                                    <input data-format="dd/MM/yyyy hh:mm:ss" type="datetime-local"
                                                        name="dataHoraSaidaInternacional" id="dataHoraSaidaInternacional"
                                                        class="form-control form-control-lg"
                                                        placeholder="Data/Hora de saída"
                                                        value="{{ $rota->isViagemInternacional == '1' ? $rota->dataHoraSaida : '' }}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-xl-6 mb-4">
                                            {{-- CAMPOS DE DETINO INTERNACIONAL ------------------------------- --}}
                                            <h4 style="color: crimson"> Destino: </h4>
                                            <div class="form-group">
                                                <label for="selecaoContinenteDestinoInternacional"
                                                    class="control-label"><strong style="color: red">* </strong>Selecione
                                                    o continente destino</label>
                                                <br>
                                                <select
                                                    class="custom-select custom-select-lg {{ $errors->has('selecaoContinenteDestinoInternacional') ? 'is-invalid' : '' }}"
                                                    id="selecaoContinenteDestinoInternacional"
                                                    name="selecaoContinenteDestinoInternacional">
                                                    <option value="0" name="0"> Selecione</option>
                                                    <option value="1" name="1"
                                                        {{ $rota->continenteDestinoInternacional == '1' ? "selected='selected'" : '' }}>
                                                        América Latina ou América Central</option>
                                                    <option value="2" name="2"
                                                        {{ $rota->continenteDestinoInternacional == '2' ? "selected='selected'" : '' }}>
                                                        América do Norte</option>
                                                    <option value="3" name="3"
                                                        {{ $rota->continenteDestinoInternacional == '3' ? "selected='selected'" : '' }}>
                                                        Europa</option>
                                                    <option value="4" name="4"
                                                        {{ $rota->continenteDestinoInternacional == '4' ? "selected='selected'" : '' }}>
                                                        África</option>
                                                    <option value="5" name="5"
                                                        {{ $rota->continenteDestinoInternacional == '5' ? "selected='selected'" : '' }}>
                                                        Ásia</option>
                                                </select>

                                                @if ($errors->has('selecaoContinenteDestinoInternacional'))
                                                    <div class="invalid-feedback">
                                                        {{ $errors->first('selecaoContinenteDestinoInternacional') }}
                                                    </div>
                                                @endif
                                            </div>

                                            <div class="form-group">
                                                <label for="selecaoPaisDestinoInternacional" class="control-label"><strong
                                                        style="color: red">* </strong>Selecione o país destino:</label>
                                                <br>

                                                <select
                                                    class="custom-select custom-select-lg {{ $errors->has('selecaoPaisDestinoInternacional') ? 'is-invalid' : '' }}"
                                                    id="selecaoPaisDestinoInternacional"
                                                    name="selecaoPaisDestinoInternacional">
                                                    <option value="{{ $rota->paisDestinoInternacional }}" selected>
                                                    </option>

                                                </select>

                                                @if ($errors->has('selecaoPaisDestinoInternacional'))
                                                    <div class="invalid-feedback">
                                                        {{ $errors->first('selecaoPaisDestinoInternacional') }}
                                                    </div>
                                                @endif
                                            </div>

                                            <div class="form-group">
                                                <label for="selecaoEstadoDestinoInternacional"
                                                    class="control-label"><strong style="color: red">* </strong>Digite o
                                                    nome do estado/província destino:</label>
                                                <br>

                                                <input
                                                    class="form-control form-control-lg {{ $errors->has('selecaoEstadoDestinoInternacional') ? 'is-invalid' : '' }}"
                                                    type="text" id="selecaoEstadoDestinoInternacional"
                                                    name="selecaoEstadoDestinoInternacional"
                                                    value="{{ $rota->estadoDestinoInternacional }}">

                                                @if ($errors->has('selecaoEstadoDestinoInternacional'))
                                                    <div class="invalid-feedback">
                                                        {{ $errors->first('selecaoEstadoDestinoInternacional') }}
                                                    </div>
                                                @endif
                                            </div>

                                            <div class="form-group">
                                                <label for="selecaoCidadeDestinoInternacional"
                                                    class="control-label"><strong style="color: red">* </strong>Digite o
                                                    nome da cidade destino:</label>
                                                <br>

                                                <input
                                                    class="form-control form-control-lg {{ $errors->has('selecaoCidadeDestinoInternacional') ? 'is-invalid' : '' }}"
                                                    type="text" id="selecaoCidadeDestinoInternacional"
                                                    name="selecaoCidadeDestinoInternacional"
                                                    value="{{ $rota->cidadeDestinoInternacional }}">

                                                @if ($errors->has('selecaoCidadeDestinoInternacional'))
                                                    <div class="invalid-feedback">
                                                        {{ $errors->first('selecaoCidadeDestinoInternacional') }}
                                                    </div>
                                                @endif
                                            </div>

                                            <div class="form-group">
                                                <div id="dataHoraChegadaInternacional" class="input-append date">
                                                    <label for="dataHoraChegadaInternacional"
                                                        class="control-label"><strong style="color: red">*
                                                        </strong>Data/Hora de chegada: </label>
                                                    <input data-format="dd/MM/yyyy hh:mm:ss" type="datetime-local"
                                                        name="dataHoraChegadaInternacional"
                                                        id="dataHoraChegadaInternacional"
                                                        class="form-control form-control-lg"
                                                        placeholder="Data/Hora de chegada"
                                                        value="{{ $rota->isViagemInternacional == '1' ? $rota->dataHoraChegada : '' }}">
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
                                                    <label for="isReservaHotel" class="control-label"><strong
                                                            style="color: red">* </strong>Você vai precisar de reserva de
                                                        hotel?</label>
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
                                                    <label for="tipoTransporte" class="control-label"><strong
                                                            style="color: red">* </strong>Qual o tipo de
                                                        transporte?</label>
                                                    <br>
                                                    <select
                                                        class="custom-select custom-select-lg {{ $errors->has('tipoTransporte') ? 'is-invalid' : '' }}"
                                                        id="tipoTransporte" name="tipoTransporte"
                                                        onChange="ativarCampo()">
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
                                                    <label for="veiculoProprio_id" class="control-label"
                                                        required>Selecione o veículo?</label>
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

                                                <div id="btSalvarRota">
                                                    <input style="font-size: 16px" type="submit"
                                                        class="btn btn-active btn-primary" value="Salvar Rota!">
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </form>
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

        function carregarPaises() {

            $.getJSON('/countries', function(data) {

                opcaoSelecione = '<option value=" "> Selecione </option>';
                $('#selecaoPaisOrigem').append(opcaoSelecione);
                $('#selecaoPaisDestinoInternacional').append(opcaoSelecione);

                for (i = 0; i < data.length; i++) {
                    opcao = '<option value="' + data[i].id + '">' + data[i].name + '</option>';

                    $('#selecaoPaisOrigem').append(opcao);
                    $('#selecaoPaisDestinoInternacional').append(opcao);
                }
            });
        }

        function carregarPaisOrigem() {

            var id = document.getElementById("selecaoPaisOrigem").value;
            $("#selecaoPaisOrigem").html('');

            $.getJSON('/country/' + id, function(data) {

                for (i = 0; i < data.length; i++) {
                    opcao = '<option value="' + data[i].id + '">' + data[i].name + '</option>';

                    $('#selecaoPaisOrigem').append(opcao);
                }
            });

            $.getJSON('/countries', function(data) {


                for (i = 0; i < data.length; i++) {
                    opcao = '<option value="' + data[i].id + '">' + data[i].name + '</option>';

                    $('#selecaoPaisOrigem').append(opcao);
                }
            });
        }

        function carregarPaisDestino() {

            var id = document.getElementById("selecaoPaisDestinoInternacional").value;
            $("#selecaoPaisDestinoInternacional").html('');

            $.getJSON('/country/' + id, function(data) {

                for (i = 0; i < data.length; i++) {
                    opcao = '<option value="' + data[i].id + '">' + data[i].name + '</option>';

                    $('#selecaoPaisDestinoInternacional').append(opcao);
                }
            });

            $.getJSON('/countries', function(data) {


                for (i = 0; i < data.length; i++) {
                    opcao = '<option value="' + data[i].id + '">' + data[i].name + '</option>';

                    $('#selecaoPaisDestinoInternacional').append(opcao);
                }
            });
        }

        function carregarCidadesOrigemInternacional() {

            var idEstado = null;
            idEstado = document.getElementById("selecaoEstadoOrigem");

            $.getJSON('/cities', function(data) {

                for (i = 0; i < data.length; i++) {

                    if (data[i].state_id == idEstado.value) {
                        opcao = '<option value="' + data[i].id + '">' + data[i].name + '</option>';
                        $('#selecaoCidadeOrigem').append(opcao);
                    }
                }
            });
        }

        function resetarCampoOrigemInternacional() {
            document.getElementById("selecaoPaisOrigem").value = "";
            document.getElementById("selecaoEstadoOrigem").value = "";
            document.getElementById("selecaoContinenteOrigem").selectedIndex = 0;
            $("#selecaoEstadoOrigem").html('');
            $("#selecaoEstadoOrigem").html('<option value="">Selecione</option>');
            document.getElementById("selecaoCidadeOrigem").value = "";
            carregarPaises();
            document.getElementById("selecaoPaisOrigem").disabled = false;
        }

        function resetarCampoDestinoInternacional() {
            document.getElementById("selecaoPaisDestinoInternacional").value = "";
            document.getElementById("selecaoEstadoDestinoInternacional").value = "";
            document.getElementById("selecaoContinenteDestinoInternacional").selectedIndex = 0;
            $("#selecaoEstadoDestinoInternacional").html('');
            $("#selecaoEstadoDestinoInternacional").html('<option value="">Selecione</option>');
            document.getElementById("selecaoCidadeDestinoInternacional").value = "";
            carregarPaises();
            document.getElementById("selecaoPaisDestinoInternacional").disabled = false;
        }

        //Assim que a tela carrega, aciona automaticamente essas duas funções ------------------------
        $(function() {

            //carregarPaises();
            @if ($rota->isViagemInternacional == true)
                carregarPaisOrigem();
                carregarPaisDestino();
            @endif
        })
    </script>

@stop
