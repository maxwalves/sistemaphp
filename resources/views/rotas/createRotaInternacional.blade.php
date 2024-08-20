@extends('adminlte::page')

@section('title', 'Criar Rota')

@section('content_header')
@stop

@section('content')

    <div class="tab-pane fade show active" id="custom-tabs-five-overlay" role="tabpanel"
        aria-labelledby="custom-tabs-five-overlay-tab"
        style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.5); z-index: 9999; display: none">
        <div class="overlay-wrapper"
            style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); text-align: center; color: #ffffff;">
            <i class="fas fa-3x fa-sync-alt fa-spin" style="margin-bottom: 10px;"></i>
            <div class="text-bold pt-2">Carregando...</div>
        </div>
    </div>

    <div id="container">

        <form action="/rotas" method="POST" enctype="multipart/form-data">
            @csrf

            <input type="text" id="isViagemInternacional" name="isViagemInternacional" value="1" hidden>

            <div class="row justify-content-start">
                <div class="col-8">
                    <label for="idav"> <strong>NOVA ROTA - Autorização de Viagem nº </strong> </label>
                    <input type="text" style="width: 50px" value="{{ $av->id }}" id="idav" name="idav"
                        readonly>
                    <br>
                    <span><strong>Data da Autorização de Viagem:
                            {{ date('d/m/Y', strtotime($av->dataCriacao)) }}</strong></span>
                </div>
                <div class="col-3">
                    <br>
                    <a href="/rotas/rotas/{{ $av->id }}" type="submit" class="btn btn-warning"><i
                            class="fas fa-arrow-left"></i></a>
                </div>
            </div>
            <hr>
            <div style="padding-left: 50px" class="form-group">

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
                        $errors->has('dataHoraSaidaInternacional') ||
                        $errors->has('dataHoraChegadaInternacional') ||
                        $errors->has('dataHoraSaidaNacional') ||
                        $errors->has('dataHoraChegadaNacional') ||
                        $errors->has('veiculoProprio_id'))
                    <div>
                        <p style="color: red"> <strong>Alguns campos não foram preenchidos!</strong></p>
                        <p style="color: red"> <strong>Selecione o tipo de viagem e verifique os campos!</strong></p>
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
                                <td> {{ $rotaExibicao->isViagemInternacional == 1 ? 'Internacional' : 'Nacional' }} </td>
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
                                        <img src="{{ asset('/img/aviaodescendo.png') }}" style="width: 40px">
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

                                <td> {{ date('d/m/Y H:i', strtotime($rotaExibicao->dataHoraChegada)) }} </td>
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

            <div id="isInternacional">
                <br>

                <div class="row">
                    <div class="col-12 col-md-4">
                        {{-- CAMPOS DE ORIGEM INTERNACIONAL ------------------------------- --}}
                        <h3 style="color: brown"> <ion-icon name="airplane-outline"></ion-icon> VIAGEM INTERNACIONAL</h3>
                        <br>
                        <h4 style="color: crimson"> Origem: </h4>
                        <div class="form-group">
                            <label for="selecaoContinenteOrigem" class="control-label"><strong style="color: red">*
                                </strong>Selecione o continente origem</label>
                            <br>
                            <select
                                class="select select-bordered select-sm w-full max-w-xs {{ $errors->has('selecaoContinenteOrigem') ? 'is-invalid' : '' }}"
                                id="selecaoContinenteOrigem" name="selecaoContinenteOrigem">
                                <option value="0" name="0"> Selecione</option>
                                <option value="1" name="1"> América Latina ou América Central</option>
                                <option value="2" name="2"> América do Norte</option>
                                <option value="3" name="3"> Europa</option>
                                <option value="4" name="4"> África</option>
                                <option value="5" name="5"> Ásia</option>
                            </select>

                            @if ($errors->has('selecaoContinenteOrigem'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('selecaoContinenteOrigem') }}
                                </div>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="selecaoPaisOrigem" class="control-label"><strong style="color: red">*
                                </strong>Selecione o país origem:</label>
                            <br>

                            <select
                                class="select select-bordered select-sm w-full max-w-xs {{ $errors->has('selecaoPaisOrigem') ? 'is-invalid' : '' }}"
                                id="selecaoPaisOrigem" name="selecaoPaisOrigem">


                            </select>

                            @if ($errors->has('selecaoPaisOrigem'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('selecaoPaisOrigem') }}
                                </div>
                            @endif
                        </div>

                        <div class="form-group">

                            <label for="selecaoEstadoOrigem" class="control-label"><strong style="color: red">*
                                </strong>Digite o nome do estado/província origem:</label>
                            <br>
                            <input
                                class="input input-bordered input-primary w-full max-w-xs {{ $errors->has('selecaoEstadoOrigem') ? 'is-invalid' : '' }}"
                                type="text" id="selecaoEstadoOrigem" name="selecaoEstadoOrigem">
                            <h4 style="color: brown"> Obs: Caso não possua Estado/Província, preencha com o nome da cidade.
                            </h4>

                            @if ($errors->has('selecaoEstadoOrigem'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('selecaoEstadoOrigem') }}
                                </div>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="selecaoCidadeOrigem" class="control-label"><strong style="color: red">*
                                </strong>Digite o nome da cidade de origem:</label>
                            <br>

                            <input
                                class="input input-bordered input-primary w-full max-w-xs {{ $errors->has('selecaoCidadeOrigem') ? 'is-invalid' : '' }}"
                                type="text" id="selecaoCidadeOrigem" name="selecaoCidadeOrigem">

                            @if ($errors->has('selecaoCidadeOrigem'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('selecaoCidadeOrigem') }}
                                </div>
                            @endif
                        </div>

                        <br>
                        @php
                            if (count($av->rotas) > 0) {
                                $minDate = date('Y-m-d\TH:i', strtotime($rotas[count($rotas) - 1]->dataHoraChegada));
                            } else {
                                $minDate = date('Y-m-d\TH:i');
                            }
                        @endphp

                        <div class="form-group">
                            <div id="dataHoraSaidaInternacional" class="input-append date">
                                <label for="dataHoraSaidaInternacional" class="control-label"><strong
                                        style="color: red">* </strong>Data/Hora de saída: </label>
                                <input data-format="dd/MM/yyyy hh:mm:ss" type="datetime-local"
                                    name="dataHoraSaidaInternacional" style="border-width: 1px; border-color: black"
                                    id="dataHoraSaidaInternacional" placeholder="Data/Hora de saída"
                                    class="{{ $errors->has('dataHoraSaidaInternacional') ? 'is-invalid' : '' }}"
                                    min="{{ $minDate }}">

                                @if ($errors->has('dataHoraSaidaInternacional'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('dataHoraSaidaInternacional') }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        {{-- CAMPOS DE DETINO INTERNACIONAL ------------------------------- --}}
                        <br><br>
                        <h4 style="color: crimson"> Destino: </h4>
                        <div class="form-group">
                            <label for="selecaoContinenteDestinoInternacional" class="control-label"><strong
                                    style="color: red">* </strong>Selecione o continente destino</label>
                            <br>
                            <select
                                class="select select-bordered select-sm w-full max-w-xs {{ $errors->has('selecaoContinenteDestinoInternacional') ? 'is-invalid' : '' }}"
                                id="selecaoContinenteDestinoInternacional" name="selecaoContinenteDestinoInternacional">
                                <option value="0" name="0"> Selecione</option>
                                <option value="1" name="1"> América Latina ou América Central</option>
                                <option value="2" name="2"> América do Norte</option>
                                <option value="3" name="3"> Europa</option>
                                <option value="4" name="4"> África</option>
                                <option value="5" name="5"> Ásia</option>
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
                                class="select select-bordered select-sm w-full max-w-xs {{ $errors->has('selecaoPaisDestinoInternacional') ? 'is-invalid' : '' }}"
                                id="selecaoPaisDestinoInternacional" name="selecaoPaisDestinoInternacional">


                            </select>

                            @if ($errors->has('selecaoPaisDestinoInternacional'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('selecaoPaisDestinoInternacional') }}
                                </div>
                            @endif
                        </div>

                        <div class="form-group">

                            <label for="selecaoEstadoDestinoInternacional" class="control-label"><strong
                                    style="color: red">* </strong>Digite o nome do estado/província destino:</label>
                            <br>

                            <input
                                class="input input-bordered input-primary w-full max-w-xs {{ $errors->has('selecaoEstadoDestinoInternacional') ? 'is-invalid' : '' }}"
                                type="text" id="selecaoEstadoDestinoInternacional"
                                name="selecaoEstadoDestinoInternacional">
                            <h4 style="color: brown"> Obs: Caso não possua Estado/Província, preencha com o nome da cidade.
                            </h4>
                            @if ($errors->has('selecaoEstadoDestinoInternacional'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('selecaoEstadoDestinoInternacional') }}
                                </div>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="selecaoCidadeDestinoInternacional" class="control-label"><strong
                                    style="color: red">* </strong>Digite o nome da cidade destino:</label>
                            <br>

                            <input
                                class="input input-bordered input-primary w-full max-w-xs {{ $errors->has('selecaoCidadeDestinoInternacional') ? 'is-invalid' : '' }}"
                                type="text" id="selecaoCidadeDestinoInternacional"
                                name="selecaoCidadeDestinoInternacional">

                            @if ($errors->has('selecaoCidadeDestinoInternacional'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('selecaoCidadeDestinoInternacional') }}
                                </div>
                            @endif
                        </div>

                        <br>
                        <?php
                        $minDate = date('Y-m-d\TH:i');
                        ?>

                        <div class="form-group">
                            <div id="dataHoraChegadaInternacional" class="input-append date">
                                <label for="dataHoraChegadaInternacional" class="control-label"><strong
                                        style="color: red">* </strong>Data/Hora de chegada: </label>
                                <input data-format="dd/MM/yyyy hh:mm:ss" type="datetime-local"
                                    name="dataHoraChegadaInternacional" style="border-width: 1px; border-color: black"
                                    id="dataHoraChegadaInternacional" placeholder="Data/Hora de chegada"
                                    class="{{ $errors->has('dataHoraChegadaInternacional') ? 'is-invalid' : '' }}"
                                    min="{{ $minDate }}">

                                @if ($errors->has('dataHoraChegadaInternacional'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('dataHoraChegadaInternacional') }}
                                    </div>
                                @endif
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <br>
            <div class="row" style="background-color: lightgrey">

                <div class="col-md-6">

                    <div>
                        <div id="camposFinais" hidden="true">
                            <div class="form-group" id="campoHotel">
                                <label for="isReservaHotel" class="control-label"><strong style="color: red">*
                                    </strong>Você vai precisar de reserva de hotel no destino?</label>
                                <br>
                                <select
                                    class="select select-bordered select-sm w-full max-w-xs {{ $errors->has('isReservaHotel') ? 'is-invalid' : '' }}"
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
                                <label for="tipoTransporte" class="control-label"><strong style="color: red">*
                                    </strong>Qual o tipo de transporte?</label>
                                <br>
                                <select
                                    class="select select-bordered select-sm w-full max-w-xs {{ $errors->has('tipoTransporte') ? 'is-invalid' : '' }}"
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
                                            {{ $ultimaRotaSetada->isAereo == '1' ? "selected='selected'" : '' }}> Avião
                                        </option>
                                        <option value="5" name="5"
                                            {{ $ultimaRotaSetada->isOutroMeioTransporte == '1' ? "selected='selected'" : '' }}>
                                            Outros</option>
                                        <option value="6" name="6"
                                            {{ $ultimaRotaSetada->isOutroMeioTransporte == '2' ? "selected='selected'" : '' }}>
                                            Carona</option>
                                    @else
                                        <option value="0" name="0"> Onibus Leito</option>
                                        <option value="1" name="1"> Onibus convencional</option>
                                        <option value="2" name="2"> Veículo próprio</option>
                                        <option value="3" name="3"> Veículo do Paranacidade</option>
                                        <option value="4" name="4"> Avião</option>
                                        <option value="5" name="5">Outros</option>
                                        <option value="6" name="6">Carona</option>
                                    @endif
                                </select>

                                @if ($errors->has('tipoTransporte'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('tipoTransporte') }}
                                    </div>
                                @endif
                            </div>

                            <div class="form-group" id="selecaoVeiculo" hidden="true">
                                <label for="veiculoProprio_id" class="control-label" required>Selecione o veículo?</label>
                                <br>
                                <select
                                    class="select select-bordered select-sm w-full max-w-xs {{ $errors->has('veiculoProprio_id') ? 'is-invalid' : '' }}"
                                    id="veiculoProprio_id" name="veiculoProprio_id">
                                    <option value="" name=""> Selecione</option>
                                    @for ($i = 0; $i < count($veiculosProprios); $i++)
                                        <div>
                                            <option value="{{ $veiculosProprios[$i]->id }}"
                                                name="{{ $veiculosProprios[$i]->id }}">
                                                {{ $veiculosProprios[$i]->modelo }} - {{ $veiculosProprios[$i]->placa }}
                                            </option>
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
                                <input style="font-size: 16px" type="submit" id="salvarBt"
                                    class="btn btn-active btn-primary" value="Cadastrar Rota!">
                            </div>
                            <br><br>
                        </div>
                    </div>
                </div>
            </div>
        </form>

    </div>
@stop

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

        function gerenciaNacionalInternacional() {
            $('#custom-tabs-five-overlay').css('display', 'block');

            document.getElementById("camposFinais").hidden = false;
            document.getElementById("btSalvarRota").hidden = false;
            document.getElementById("isInternacional").hidden = false;

            setTimeout(function() {
                $('#custom-tabs-five-overlay').css('display', 'none');
            }, 500);
        }

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

        function exibeCamposVolta() {
            if (document.getElementById("isViagemVoltaIgualIda").hidden == false) {
                document.getElementById("isViagemVoltaIgualIda").hidden = true;
            } else {
                document.getElementById("isViagemVoltaIgualIda").hidden = false;
            }
        }

        $(function() {

            carregarPaises();
            document.getElementById("isInternacional").hidden = true;
            document.getElementById("btSalvarRota").hidden = true;


            document.getElementById("isInternacional").hidden = false;
            gerenciaNacionalInternacional();

            ativarCampo();

        })
    </script>
@stop
