@extends('adminlte::page')

@section('title', 'Ver detalhes AV/PC')

@section('content_header')
    <h1>Ver detalhes AV/PC</h1>
@stop

@section('content')

<div>
    <a href="/avs/gerenciarAvs" class="btn btn-warning">Voltar</a>
    <x-adminlte-button label="Detalhar dias" data-toggle="modal" class="bg-blue" data-target="#my-modal-5"/>
    <x-adminlte-button label="Como é calculada a diária de alimentação?" data-toggle="modal" data-target="#my-modal-3"/>
</div>

<br>

<div class="col-md-12 col-sm-6">
    <div class="card card-primary card-outline card-tabs">
        <div class="card-header p-0 pt-1 border-bottom-0">
            <ul class="nav nav-tabs" id="custom-tabs-three-tab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="custom-tabs-three-home-tab" data-toggle="pill"
                        href="#custom-tabs-three-home" role="tab" aria-controls="custom-tabs-three-home"
                        aria-selected="true">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="custom-tabs-three-historico-tab" data-toggle="pill"
                        href="#custom-tabs-three-historico" role="tab" aria-controls="custom-tabs-three-historico"
                        aria-selected="false">Histórico</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="custom-tabs-three-dados-tab" data-toggle="pill"
                        href="#custom-tabs-three-dados" role="tab" aria-controls="custom-tabs-three-dados"
                        aria-selected="false">Dados básicos</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="custom-tabs-three-fases-tab" data-toggle="pill"
                        href="#custom-tabs-three-fases" role="tab" aria-controls="custom-tabs-three-fases"
                        aria-selected="false">Fases</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="custom-tabs-three-trajeto-tab" data-toggle="pill"
                        href="#custom-tabs-three-trajeto" role="tab" aria-controls="custom-tabs-three-trajeto"
                        aria-selected="false">Trajeto</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="custom-tabs-three-relatorio-tab" data-toggle="pill"
                        href="#custom-tabs-three-relatorio" role="tab" aria-controls="custom-tabs-three-relatorio"
                        aria-selected="false">Relatório</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="custom-tabs-three-hotel-tab" data-toggle="pill"
                        href="#custom-tabs-three-hotel" role="tab" aria-controls="custom-tabs-three-hotel"
                        aria-selected="false">Hotel</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="custom-tabs-three-transporte-tab" data-toggle="pill"
                        href="#custom-tabs-three-transporte" role="tab" aria-controls="custom-tabs-three-transporte"
                        aria-selected="false">Transporte</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="custom-tabs-three-adiantamentos-tab" data-toggle="pill"
                        href="#custom-tabs-three-adiantamentos" role="tab"
                        aria-controls="custom-tabs-three-adiantamentos" aria-selected="false">Adiantamentos</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="custom-tabs-three-despesas-tab" data-toggle="pill"
                        href="#custom-tabs-three-despesas" role="tab" aria-controls="custom-tabs-three-despesas"
                        aria-selected="false">Despesas</a>
                </li>
                @if ($av->objetivo_id == 3)
                    <li class="nav-item">
                        <a class="nav-link" id="custom-tabs-three-medicoes-tab" data-toggle="pill"
                            href="#custom-tabs-three-medicoes" role="tab" aria-controls="custom-tabs-three-medicoes"
                            aria-selected="false">Medições</a>
                    </li>
                @endif
            </ul>
        </div>
        <div class="card-body">
            <div class="tab-content" id="custom-tabs-three-tabContent">
                <div class="tab-pane fade active show" id="custom-tabs-three-home" role="tabpanel"
                    aria-labelledby="custom-tabs-three-home-tab">
                    <div>

                        <div class="containerAcertoContas">
                            <div class="box box-90">
                                <h1 style="font-size: 24px"><strong>Autorização de viagem nº:</strong>
                                    {{ $av->id }}</h1>
                                <h1 style="font-size: 24px"><strong>Status atual:</strong> {{ $av->status }}</h1>
                                @if($av->isCancelado)
                                    <h1 style="font-size: 24px"><strong>Motivo do cancelamento:</strong> {{ $av->justificativaCancelamento }}</h1>
                                @endif
                                <p class="av-owner" style="font-size: 20px"><ion-icon
                                        name="chevron-forward-circle-outline">
                                    </ion-icon> <strong>Nome do usuário: </strong>
                                    @foreach ($users as $u)
                                        @if ($u->id == $av->user_id)
                                            {{ $u->name }}
                                        @endif
                                    @endforeach
                                </p>
                                <p class="av-owner" style="font-size: 20px"><ion-icon
                                        name="chevron-forward-circle-outline">
                                    </ion-icon> <strong>E-mail do usuário: </strong>
                                    @foreach ($users as $u)
                                        @if ($u->id == $av->user_id)
                                            {{ $u->username }}
                                        @endif
                                    @endforeach
                                </p>
                            </div>
                        </div>
                    </div>

                    <div>
                        <div class="containerAcertoContas">
                            @if ($av->isPrestacaoContasRealizada == 1)
                                <div class="box box-40">
                                    <div class="col-md-12 offset-md-0">
                                        <h1 style="font-size: 24px"><strong>Acerto de contas: </strong></h1>
                                        <br>
                                        <p><strong> <span style="color: red">A:</span> Recebido antes da viagem</strong></p>
                                        <div class="callout callout-info">
                    
                                            <div class="stat">
                                                <div class="stat-title">Valor em Reais</div>
                                                <div class="stat-value text-primary">R$ {{ number_format($valorRecebido->valorReais, 2, ',', '.') }}</div>
                                            </div>
                                            <div class="stat">
                                                <div class="stat-title">Valor extra em Reais</div>
                                                <div class="stat-value text-primary">R$
                                                    {{ $valorRecebido->valorExtraReais != null ? $valorRecebido->valorExtraReais : 0 }}
                                                </div>
                                            </div>
                                            @if ($av->valorDeducaoReais > 0)
                                                <div class="stat">
                                                    <div class="stat-title">Dedução em Reais</div>
                                                    <div class="stat-value text-primary">- R$ {{ number_format($av->valorDeducaoReais, 2, ',', '.') }}</div>
                                                </div>
                                            @endif
                                            @if ($av->valorDeducaoDolar > 0 && $av->valorDeducaoDolar != null)
                                                <div class="stat">
                                                    <div class="stat-title">Dedução em dólar</div>
                                                    <div class="stat-value text-primary">$ {{ number_format($av->valorDeducaoDolar, 2, ',', '.') }}</div>
                                                </div>
                                            @endif
                                            @if ($valorRecebido->valorDolar > 0 && $valorRecebido->valorDolar != null)
                                                <div class="stat">
                                                    <div class="stat-title">Valor em dólar</div>
                                                    <div class="stat-value text-primary">$ {{ number_format($valorRecebido->valorDolar, 2, ',', '.') }}</div>
                                                </div>
                                            @endif
                                            @if ($valorRecebido->valorExtraDolar > 0 && $valorRecebido->valorExtraDolar != null)
                                                <div class="stat">
                                                    <div class="stat-title">Valor extra em dólar</div>
                                                    <div class="stat-value text-primary">$ {{ number_format($valorRecebido->valorExtraDolar, 2, ',', '.') }}</div>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="callout callout-success">
                                            <div class="stat">
                                                <div class="stat-title">Resultado em Reais</div>
                                                <div class="stat-value text-primary">R$
                                                    {{ number_format($valorRecebido->valorReais + $valorRecebido->valorExtraReais - $av->valorDeducaoReais, 2, ',', '.') }}
                                                </div>
                                            </div>
                                            @if ($valorRecebido->valorDolar + $valorRecebido->valorExtraDolar - $av->valorDeducaoDolar > 0)
                                                <div class="stat">
                                                    <div class="stat-title">Resultado em Dólar</div>
                                                    <div class="stat-value text-primary">R$
                                                        {{ number_format($valorRecebido->valorDolar + $valorRecebido->valorExtraDolar - $av->valorDeducaoDolar, 2, ',', '.') }}
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                        <br><br>
                                        <p><strong> <span style="color: green">B:</span> Informado na prestação de contas</strong></p>
                                        <div class="callout callout-info">
                    
                                            <div class="stat">
                                                <div class="stat-title">Valor em Reais</div>
                                                <div class="stat-value text-primary">R$ {{ number_format($av->valorReais, 2, ',', '.') }}
                                                    @if($av->isAprovadoCarroDiretoriaExecutiva == true)
                                                        + R$ {{ number_format($av->qtdKmVeiculoProprio * 0.49, 2, ',', '.') }}
                                                        <br>
                                                        <small>({{ $av->qtdKmVeiculoProprio }}km x R$0,49 = R$ {{ number_format($av->qtdKmVeiculoProprio * 0.49, 2, ',', '.') }})</small>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="stat">
                                                <div class="stat-title">Valor extra em Reais</div>
                                                <div class="stat-value text-primary">R$ {{ number_format($valorAcertoContasReal, 2, ',', '.') }}</div>
                                            </div>
                                            @if ($av->valorDeducaoReais > 0)
                                                <div class="stat">
                                                    <div class="stat-title">Dedução em Reais</div>
                                                    <div class="stat-value text-primary">- R$ {{ number_format($av->valorDeducaoReais, 2, ',', '.') }}</div>
                                                </div>
                                            @endif
                                            @if ($av->valorDeducaoDolar > 0)
                                                <div class="stat">
                                                    <div class="stat-title">Dedução em dólar</div>
                                                    <div class="stat-value text-primary">$ {{ number_format($av->valorDeducaoDolar, 2, ',', '.') }}</div>
                                                </div>
                                            @endif
                                            @if ($av->valorDolar > 0)
                                                <div class="stat">
                                                    <div class="stat-title">Valor em dólar</div>
                                                    <div class="stat-value text-primary">$ {{ number_format($av->valorDolar, 2, ',', '.') }}</div>
                                                </div>
                                            @endif
                                            @if ($valorAcertoContasDolar > 0)
                                                <div class="stat">
                                                    <div class="stat-title">Valor extra em dólar</div>
                                                    <div class="stat-value text-primary">$ {{ number_format($valorAcertoContasDolar, 2, ',', '.') }}</div>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="callout callout-success">
                                            <div class="stat">
                                                <div class="stat-title">Resultado em Reais</div>
                                                <div class="stat-value text-primary">R$
                                                    {{ number_format($av->valorReais + $av->valorExtraReais - $av->valorDeducaoReais, 2, ',', '.') }}</div>
                                            </div>
                                            @if ($av->valorDolar + $av->valorExtraDolar - $av->valorDeducaoDolar > 0)
                                                <div class="stat">
                                                    <div class="stat-title">Resultado em Dólar</div>
                                                    <div class="stat-value text-primary">R$
                                                        {{ number_format($av->valorDolar + $av->valorExtraDolar - $av->valorDeducaoDolar, 2, ',', '.') }}</div>
                                                </div>
                                            @endif
                                        </div>
                                        <br><br>
                                        <p><strong> <span style="color: red">A</span> - <span style="color: green">B</span>: Acerto de
                                                contas:</strong></p>
                                        <div class="callout callout-success">
                                            @if ($av->isAprovadoCarroDiretoriaExecutiva == true)
                                                <div class="stat">
                                                    <div class="stat-title" style="color: black">Valor em Reais</div>
                                                    <div class="stat-value text-gray-950">R$
                                                        {{ number_format($valorRecebido->valorReais - $av->valorReais - $av->qtdKmVeiculoProprio * 0.49, 2, ',', '.') }}
                                                    </div>
                                                </div>
                                            @else
                                                <div class="stat">
                                                    <div class="stat-title" style="color: black">Valor em Reais</div>
                                                    <div class="stat-value text-gray-950">R$
                                                        {{ number_format($valorRecebido->valorReais - $av->valorReais, 2, ',', '.') }}</div>
                                                </div>
                                            @endif
                    
                                            <div class="stat">
                                                <div class="stat-title" style="color: black">Valor extra em Reais</div>
                                                <div class="stat-value text-gray-950">R$
                                                    {{ number_format($valorRecebido->valorExtraReais - $valorAcertoContasReal, 2, ',', '.') }}</div>
                                            </div>
                    
                                            @if ($valorRecebido->valorDolar - $av->valorDolar > 0)
                                                <div class="stat">
                                                    <div class="stat-title" style="color: black">Valor em dólar</div>
                                                    <div class="stat-value text-gray-950">$
                                                        {{ number_format($valorRecebido->valorDolar - $av->valorDolar, 2, ',', '.') }}</div>
                                                </div>
                                            @endif
                                            @if ($valorRecebido->valorExtraDolar - $valorAcertoContasDolar > 0)
                                                <div class="stat">
                                                    <div class="stat-title" style="color: black">Valor extra em dólar</div>
                                                    <div class="stat-value text-gray-950">$
                                                        {{ number_format($valorRecebido->valorExtraDolar - $valorAcertoContasDolar, 2, ',', '.') }}</div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endif
                            <div class="box box-40">
                                <div>
                                    <h1 style="font-size: 24px"><strong>Comprovantes:</strong></h1>
                                    <table id="minhaTabela6" class="table table-hover table-bordered" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>Descrição</th>
                                                <th>Anexo</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($historicoPc as $hist)
                                                <tr>
                                                    <td> {{ $hist->comentario }} </td>
                                                    @if ($hist->comentario == 'AV Internacional gerada')
                                                        <td> <a href="{{ route('recuperaArquivo', [
                                                            'name' => $userAv->name,
                                                            'id' => $av->id,
                                                            'pasta' => 'internacional',
                                                            'anexoRelatorio' => $hist->anexoRelatorio,
                                                            ]) }}"
                                                                target="_blank" class="btn btn-active btn-success btn-sm">Abrir
                                                                documento</a> </td>
                                                    @else
                                                        <td>
                                                            <a href="{{ route('recuperaArquivo', [
                                                                'name' => $userAv->name,
                                                                'id' => $av->id,
                                                                'pasta' => 'resumo',
                                                                'anexoRelatorio' => $hist->anexoRelatorio,
                                                            ]) }}" target="_blank" class="btn btn-active btn-success btn-sm">
                                                                Abrir documento
                                                            </a>
                                                        </td>
                                                    @endif
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>

                                    @if($av->idReservaVeiculo != null)
                                        <h1 style="font-size: 24px"><strong>Reserva de veículo no sistema de reservas:</strong></h1>
                                        @if(count($reservas2) > 0)
                                            <table class="table table-hover table-bordered" style="width: 100%">
                                                <thead>
                                                    <tr>
                                                        <th>Nr Reserva</th>
                                                        <th>Data Início</th>
                                                        <th>Data Fim</th>
                                                        <th>Descrição</th>
                                                        <th>Veículo</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($reservas2 as $r)
                                                        <tr>
                                                            <td>{{$r->id}}</td>
                                                            <td>{{date('d/m/Y H:i', strtotime($r->dataInicio))}}</td>
                                                            <td>{{date('d/m/Y H:i', strtotime($r->dataFim))}}</td>
                                                            <td>{{$r->observacoes}}</td>
                                                            <td>{{$r->veiculo->marca}} - {{$r->veiculo->modelo}} - {{$r->veiculo->placa}}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        @else
                                            <p>Nenhuma reserva encontrada</p>
                                        @endif
                                    @endif
                                    
                                    @if ($av->isPrestacaoContasRealizada == 1)
                                        <p><strong>Resultado:</strong></p>
                                        <div class="callout callout-success">
                    
                                            <div class="stat">
                                                <div class="stat-title">
                                                    <p>
                                                        @if ($av->isAprovadoCarroDiretoriaExecutiva == true)
                                                            @if (
                                                                $valorRecebido->valorReais -
                                                                    $av->valorReais -
                                                                    $av->qtdKmVeiculoProprio * 0.49 +
                                                                    ($valorRecebido->valorExtraReais - $valorAcertoContasReal) <
                                                                    0)
                                                                Valor que o usuário deve receber em reais
                                                            @endif
                                                            @if (
                                                                $valorRecebido->valorReais -
                                                                    $av->valorReais -
                                                                    $av->qtdKmVeiculoProprio * 0.49 +
                                                                    ($valorRecebido->valorExtraReais - $valorAcertoContasReal) >
                                                                    0)
                                                                Valor que o usuário deve pagar em reais
                                                            @endif
                                                        @else
                                                            @if ($valorRecebido->valorReais - $av->valorReais + ($valorRecebido->valorExtraReais - $valorAcertoContasReal) < 0)
                                                                Valor que o usuário deve receber em reais
                                                            @endif
                                                            @if ($valorRecebido->valorReais - $av->valorReais + ($valorRecebido->valorExtraReais - $valorAcertoContasReal) > 0)
                                                                Valor que o usuário deve pagar em reais
                                                            @endif
                                                            @if($valorRecebido->valorReais - $av->valorReais + ($valorRecebido->valorExtraReais - $valorAcertoContasReal) == 0)
                                                                O somatório dos valores das diárias da Viagem informada na AV não foi alterado na PC.
                                                            @endif
                                                        @endif
                                                    </p>
                                                </div>
                    
                                                @if ($av->isAprovadoCarroDiretoriaExecutiva == true)
                                                    @if (
                                                        $valorRecebido->valorReais -
                                                            $av->valorReais +
                                                            ($valorRecebido->valorExtraReais - $valorAcertoContasReal) -
                                                            $av->qtdKmVeiculoProprio * 0.49 <
                                                            0)
                                                        <div class="stat-value text-green-500">
                                                            R$ {{ number_format(($valorRecebido->valorReais - $av->valorReais + ($valorRecebido->valorExtraReais - $valorAcertoContasReal) - $av->qtdKmVeiculoProprio * 0.49) * -1, 2, ',', '.') }}
                                                        </div>
                                                    @endif
                                                    @if (
                                                        $valorRecebido->valorReais -
                                                            $av->valorReais +
                                                            ($valorRecebido->valorExtraReais - $valorAcertoContasReal) -
                                                            $av->qtdKmVeiculoProprio * 0.49 >
                                                            0)
                                                        <div class="stat-value text-error">
                                                            R$ {{ number_format(($valorRecebido->valorReais - $av->valorReais + ($valorRecebido->valorExtraReais - $valorAcertoContasReal) - $av->qtdKmVeiculoProprio * 0.49) * -1, 2, ',', '.') }}
                                                        </div>
                                                    @endif
                                                @else
                                                    @if ($valorRecebido->valorReais - $av->valorReais + ($valorRecebido->valorExtraReais - $valorAcertoContasReal) < 0)
                                                        <div class="stat-value text-green-500">
                                                            R$ {{ number_format(($valorRecebido->valorReais - $av->valorReais + ($valorRecebido->valorExtraReais - $valorAcertoContasReal)) * -1, 2, ',', '.') }}
                                                        </div>
                                                    @endif
                                                    @if ($valorRecebido->valorReais - $av->valorReais + ($valorRecebido->valorExtraReais - $valorAcertoContasReal) > 0)
                                                        <div class="stat-value text-error">
                                                            R$ {{ number_format($valorRecebido->valorReais - $av->valorReais + ($valorRecebido->valorExtraReais - $valorAcertoContasReal), 2, ',', '.') }}
                                                        </div>
                                                    @endif
                                                @endif
                    
                                            </div>
                                            <div class="stat">
                                                <div class="stat-title">
                                                    <p>
                                                        @if ($valorRecebido->valorDolar - $av->valorDolar + ($valorRecebido->valorExtraDolar - $valorAcertoContasDolar) < 0)
                                                            Valor que o usuário deve receber em dólar
                                                        @endif
                                                        @if ($valorRecebido->valorDolar - $av->valorDolar + ($valorRecebido->valorExtraDolar - $valorAcertoContasDolar) > 0)
                                                            Valor que o usuário deve pagar em dólar
                                                        @endif
                                                    </p>
                                                </div>
                                                @if ($valorRecebido->valorDolar - $av->valorDolar + ($valorRecebido->valorExtraDolar - $valorAcertoContasDolar) < 0)
                                                    <div class="stat-value text-green-500">
                                                        $ {{ number_format(($valorRecebido->valorDolar - $av->valorDolar + ($valorRecebido->valorExtraDolar - $valorAcertoContasDolar)) * -1, 2, ',', '.') }}
                                                    </div>
                                                @endif
                                                @if ($valorRecebido->valorDolar - $av->valorDolar + ($valorRecebido->valorExtraDolar - $valorAcertoContasDolar) > 0)
                                                    <div class="stat-value text-error">
                                                        $ {{ number_format($valorRecebido->valorDolar - $av->valorDolar + ($valorRecebido->valorExtraDolar - $valorAcertoContasDolar), 2, ',', '.') }}
                                                    </div>
                                                @endif
                                            </div>
                    
                                        </div>
                                    @endif
                                </div>
                                <br><br>
                                @if ($av->horasExtras != null || $av->minutosExtras != null || $av->justificativaHorasExtras != null)
                                    <div class="callout callout-danger">
                                        <strong style="color: red">Foram realizadas horas extras:</strong> <br>
                                        <strong>Horas:</strong> {{ $av->horasExtras }} <br>
                                        <strong>Minutos:</strong> {{ $av->minutosExtras }} <br>
                                        <strong>Justificativa:</strong> {{ $av->justificativaHorasExtras }} <br>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="divider"></div>

                    </div>

                </div>
                <div class="tab-pane fade" id="custom-tabs-three-historico" role="tabpanel"
                    aria-labelledby="custom-tabs-three-historico-tab">
                    <h3 class="text-lg font-bold" style="padding-left: 10%; padding-bottom: 20px">Histórico</h3>
                    <table id="minhaTabela" class="table table-hover table-bordered">
                        <!-- head -->
                        <thead>
                            <tr>
                                <th>Data</th>
                                <th>Ocorrência</th>
                                <th>Comentário</th>
                                <th>Perfil</th>
                                <th>Autor</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- row 1 -->

                            @foreach ($historicos as $historico)
                                <tr>
                                    <td>{{ date('d/m/Y H:i', strtotime($historico->dataOcorrencia)) }}</td>
                                    <td>{{ $historico->tipoOcorrencia }}</td>
                                    <td>{{ $historico->comentario }}</td>
                                    <td>{{ $historico->perfilDonoComentario }}</td>

                                    @foreach ($users as $u)
                                        @if ($u->id == $historico->usuario_comentario_id)
                                            <td>{{ $u->name }}</td>
                                        @endif
                                    @endforeach
                                </tr>
                            @endforeach

                        </tbody>
                    </table>

                </div>
                <div class="tab-pane fade" id="custom-tabs-three-dados" role="tabpanel"
                    aria-labelledby="custom-tabs-three-dados-tab">
                    <br>
                    <h1 class="text-lg font-bold">Dados básicos:</h1>
                    <div class="stats stats-vertical shadow">

                        <p class="av-owner" style="font-size: 20px"><ion-icon name="chevron-forward-circle-outline">
                            </ion-icon> <strong>Nome do usuário: </strong>
                            @foreach ($users as $u)
                                @if ($u->id == $av->user_id)
                                    {{ $u->name }}
                                @endif
                            @endforeach
                        </p>
                        <p class="av-owner" style="font-size: 20px"><ion-icon name="chevron-forward-circle-outline">
                            </ion-icon> <strong>E-mail do usuário: </strong>
                            @foreach ($users as $u)
                                @if ($u->id == $av->user_id)
                                    {{ $u->username }}
                                @endif
                            @endforeach
                        </p>
                        <p class="av-owner" style="font-size: 20px"><ion-icon name="calendar-outline"></ion-icon>
                            <strong>Data de criação: </strong> {{ date('d/m/Y', strtotime($av->dataCriacao)) }}
                        </p>
                        <p class="av-owner" style="font-size: 20px"><ion-icon name="flag-outline">
                            </ion-icon> <strong>Objetivo:</strong>

                            @for ($i = 0; $i < count($objetivos); $i++)
                                @if ($av->objetivo_id == $objetivos[$i]->id)
                                    {{ $objetivos[$i]->nomeObjetivo }}
                                @endif
                            @endfor

                            @if (isset($av->outroObjetivo))
                                {{ $av->outroObjetivo }}
                            @endif

                        </p>
                        <p class="av-owner" style="font-size: 20px"><ion-icon name="pricetag-outline"></ion-icon>
                            <strong>Comentário:</strong> {{ $av->comentario }}
                        </p>
                        <p class="av-owner" style="font-size: 20px"><ion-icon
                                name="chevron-forward-circle-outline"></ion-icon> <strong>Status:</strong>
                            {{ $av->status }}
                        </p>
                    </div>
                    <br>
                    <h1 class="text-lg font-bold">Dados bancários:</h1>

                    <div class="stats stats-vertical shadow">

                        <p class="av-owner" style="font-size: 20px"><ion-icon name="business-outline"></ion-icon>
                            <strong>Banco:</strong> {{ $av->banco }}
                        </p>
                        <p class="av-owner" style="font-size: 20px"><ion-icon name="home-outline"></ion-icon>
                            <strong>Agência:</strong> {{ $av->agencia }}
                        </p>
                        <p class="av-owner" style="font-size: 20px"><ion-icon name="wallet-outline"></ion-icon>
                            <strong>Conta:</strong> {{ $av->conta }}
                        </p>
                        <p class="av-owner" style="font-size: 20px"><ion-icon name="cash-outline"></ion-icon>
                            <strong>Pix:</strong> {{ $av->pix }}
                        </p>

                    </div>
                    <br>

                    <h1 class="text-lg font-bold">Adiantamentos:</h1>
                    <div class="stats stats-vertical shadow">
                        <p class="av-owner" style="font-size: 20px; color: black;"><ion-icon
                                name="cash-outline"></ion-icon> <strong>Valor em reais:</strong> R$
                            {{ $av->valorReais }}</p>
                        @if($av->valorDolar > 0 && $av->valorDolar != null)
                            <p class="av-owner" style="font-size: 20px; color: black;"><ion-icon
                                    name="cash-outline"></ion-icon> <strong>Valor em dolar:</strong> $
                                {{ $av->valorDolar }}</p>
                        @endif
                        <p class="av-owner" style="font-size: 20px; color: black;"><ion-icon
                                name="cash-outline"></ion-icon> <strong>Valor extra em reais:</strong> R$
                            {{ $av->valorExtraReais }}</p>
                        @if($av->valorExtraDolar > 0 && $av->valorExtraDolar != null)
                            <p class="av-owner" style="font-size: 20px; color: black;"><ion-icon
                                    name="cash-outline"></ion-icon> <strong>Valor extra em dólar:</strong> $
                                {{ $av->valorExtraDolar }}</p>
                        @endif
                        <p class="av-owner" style="font-size: 20px; color: black;">
                            <ion-icon name="cash-outline"></ion-icon> <strong>Valor dedução em reais:</strong> R$
                            {{ $av->valorDeducaoReais }}
                        </p>
                        @if($av->valorDeducaoDolar > 0 && $av->valorDeducaoDolar != null)
                            <p class="av-owner" style="font-size: 20px; color: black;">
                                <ion-icon name="cash-outline"></ion-icon> <strong>Valor dedução em dólar:</strong> $
                                {{ $av->valorDeducaoDolar }}
                            </p>
                        @endif
                        <p class="av-owner" style="font-size: 20px; color: black;"><ion-icon
                                name="chevron-forward-circle-outline"></ion-icon> <strong>Justificativa valor
                                extra:</strong>
                            {{ $av->justificativaValorExtra }}</p>
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

                </div>
                <div class="tab-pane fade" id="custom-tabs-three-fases" role="tabpanel"
                    aria-labelledby="custom-tabs-three-fases-tab">

                    <div class="col-md-12">

                        <div class="timeline">

                            <div class="time-label">
                                <span class="bg-red">Fases da realização da viagem</span>
                            </div>


                            <div>
                                @if ($av->isEnviadoUsuario == 1)
                                    <i class="fas fa-caret-right bg-green"></i>
                                    <div class="timeline-item">
                                        <div class="timeline-header">
                                            <a class="btn btn-success btn-lg" @readonly(true)>1 - Usuário -
                                                Preenchimento da AV</a>
                                        </div>
                                    </div>
                                @else
                                    <i class="fas fa-caret-right bg-blue"></i>
                                    <div class="timeline-item">
                                        <div class="timeline-header">
                                            <a class="btn btn-primary btn-md" @readonly(true)>1 - Usuário -
                                                Preenchimento da AV</a>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            <div>
                                @if ($av->isAprovadoGestor == 1)
                                    <i class="fas fa-caret-right bg-green"></i>
                                    <div class="timeline-item">
                                        <div class="timeline-header">
                                            <a class="btn btn-success btn-lg" @readonly(true)>2 - Gestor - Avaliação
                                                inicial</a>
                                        </div>
                                    </div>
                                @else
                                    <i class="fas fa-caret-right bg-blue"></i>
                                    <div class="timeline-item">
                                        <div class="timeline-header">
                                            <a class="btn btn-primary btn-md" @readonly(true)>2 - Gestor - Avaliação
                                                inicial</a>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            <div>
                                @if ($av->isVistoDiretoria == 1)
                                    <i class="fas fa-caret-right bg-green"></i>
                                    <div class="timeline-item">
                                        <div class="timeline-header">
                                            <a class="btn btn-success btn-lg" @readonly(true)>3 - DAF - Avalia
                                                pedido</a>
                                            <span class="badge bg-warning float-right">Se carro particular ou viagem internacional</span>
                                        </div>
                                    </div>
                                @else
                                    <i class="fas fa-caret-right bg-blue"></i>
                                    <div class="timeline-item">
                                        <div class="timeline-header">
                                            <a class="btn btn-primary btn-md" @readonly(true)>3 - DAF - Avalia
                                                pedido</a>
                                            <span class="badge bg-warning float-right">Se carro particular ou viagem internacional</span>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            <div>
                                @if ($av->isRealizadoReserva == 1)
                                    <i class="fas fa-caret-right bg-green"></i>
                                    <div class="timeline-item">
                                        <div class="timeline-header">
                                            <a class="btn btn-success btn-lg" @readonly(true)>4 - CAD - Coordenadoria
                                                Administrativa - Realiza reservas</a>
                                        </div>
                                    </div>
                                @else
                                    <i class="fas fa-caret-right bg-blue"></i>
                                    <div class="timeline-item">
                                        <div class="timeline-header">
                                            <a class="btn btn-primary btn-md" @readonly(true)>4 - CAD -
                                                Coordenadoria
                                                Administrativa - Realiza reservas</a>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            <div>
                                @if ($av->isAprovadoFinanceiro == 1)
                                    <i class="fas fa-caret-right bg-green"></i>
                                    <div class="timeline-item">
                                        <div class="timeline-header">
                                            <a class="btn btn-success btn-lg" @readonly(true)>4 - CFI -
                                                Coordenadoria
                                                Financeira - Adiantamento</a>
                                        </div>
                                    </div>
                                @else
                                    <i class="fas fa-caret-right bg-blue"></i>
                                    <div class="timeline-item">
                                        <div class="timeline-header">
                                            <a class="btn btn-primary btn-md" @readonly(true)>4 - CFI -
                                                Coordenadoria Financeira - Adiantamento</a>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            <div>
                                @if ($av->isPrestacaoContasRealizada == 1)
                                    <i class="fas fa-caret-right bg-green"></i>
                                    <div class="timeline-item">
                                        <div class="timeline-header">
                                            <a class="btn btn-success btn-md" @readonly(true)>5 - Viagem</a>
                                        </div>
                                    </div>
                                @else
                                    <i class="fas fa-caret-right bg-blue"></i>
                                    <div class="timeline-item">
                                        <div class="timeline-header">
                                            <a class="btn btn-primary btn-md" @readonly(true)>5 - Viagem</a>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            <div>
                                @if ($av->isPrestacaoContasRealizada == 1)
                                    <i class="fas fa-caret-right bg-green"></i>
                                    <div class="timeline-item">
                                        <div class="timeline-header">
                                            <a class="btn btn-success btn-lg" @readonly(true)>6 - Usuário - Realiza
                                                PC</a>
                                        </div>
                                    </div>
                                @else
                                    <i class="fas fa-caret-right bg-blue"></i>
                                    <div class="timeline-item">
                                        <div class="timeline-header">
                                            <a class="btn btn-primary btn-md" @readonly(true)>6 - Usuário - Realiza
                                                PC</a>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            <div>
                                @if ($av->isFinanceiroAprovouPC == 1)
                                    <i class="fas fa-caret-right bg-green"></i>
                                    <div class="timeline-item">
                                        <div class="timeline-header">
                                            <a class="btn btn-success btn-lg" @readonly(true)>7 - Financeiro -
                                                Avalia PC</a>
                                        </div>
                                    </div>
                                @else
                                    <i class="fas fa-caret-right bg-blue"></i>
                                    <div class="timeline-item">
                                        <div class="timeline-header">
                                            <a class="btn btn-primary btn-md" @readonly(true)>7 - Financeiro -
                                                Avalia PC</a>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            <div>
                                @if ($av->isGestorAprovouPC == 1)
                                    <i class="fas fa-caret-right bg-green"></i>
                                    <div class="timeline-item">
                                        <div class="timeline-header">
                                            <a class="btn btn-success btn-lg" @readonly(true)>8 - Gestor - Avalia
                                                PC</a>
                                        </div>
                                    </div>
                                @else
                                    <i class="fas fa-caret-right bg-blue"></i>
                                    <div class="timeline-item">
                                        <div class="timeline-header">
                                            <a class="btn btn-primary btn-md" @readonly(true)>8 - Gestor - Avalia
                                                PC</a>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            <div>
                                @if ($av->isAcertoContasRealizado == 1)
                                    <i class="fas fa-caret-right bg-green"></i>
                                    <div class="timeline-item">
                                        <div class="timeline-header">
                                            <a class="btn btn-success btn-lg" @readonly(true)>9 - Financeiro -
                                                Acerto de Contas</a>
                                        </div>
                                    </div>
                                @else
                                    <i class="fas fa-caret-right bg-blue"></i>
                                    <div class="timeline-item">
                                        <div class="timeline-header">
                                            <a class="btn btn-primary btn-md" @readonly(true)>9 - Financeiro -
                                                Acerto de Contas</a>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <div>
                                <i class="far fa-check-circle bg-green"></i>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="tab-pane fade" id="custom-tabs-three-trajeto" role="tabpanel"
                    aria-labelledby="custom-tabs-three-trajeto-tab">
                    <h1 style="font-size: 24px; padding-bottom: 20px"><strong>Trajeto: </strong></h1>

                    <table id="tabelaRota" class="table table-hover table-bordered" style="width:100%">
                        <thead>
                                <tr>
                                    <th>Tipo</th>
                                    <th>Cidade de saída</th>
                                    <th>Estado de saída</th>
                                    <th style="border-right: 1px solid black;">Data/Hora de saída</th>
                                    <th>Cidade de chegada</th>
                                    <th>Estado de chegada</th>
                                    <th>Data/Hora de chegada</th>
                                    <th>Hotel?</th>
                                    <th>Tipo de transporte</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($av->rotas as $rota)
                                <tr>
                                    <td> {{ $rota->isViagemInternacional == 1 ? 'Internacional' : 'Nacional' }} </td>
                                    <td>
                                        @if ($rota->isAereo == 1)
                                            <img src="{{ asset('/img/aviaosubindo.png') }}" style="width: 40px">
                                        @endif

                                        @if ($rota->isVeiculoProprio == 1 || $rota->isVeiculoEmpresa == 1)
                                            <img src="{{ asset('/img/carro.png') }}" style="width: 40px">
                                        @endif

                                        @if ($rota->isOnibusLeito == 1 || $rota->isOnibusConvencional == 1)
                                            <img src="{{ asset('/img/onibus.png') }}" style="width: 40px">
                                        @endif

                                        @if($rota->isOutroMeioTransporte == 1)
                                            <img src="{{asset('/img/outros.png')}}" style="width: 40px" >
                                        @endif

                                        {{ $rota->isViagemInternacional == 0 ? $rota->cidadeOrigemNacional : $rota->cidadeOrigemInternacional }}

                                    </td>
                                    <td>
                                        {{ $rota->isViagemInternacional == 0 ? $rota->estadoOrigemNacional : $rota->estadoOrigemInternacional }}
                                    </td>
                                    <td style="border-right: 1px solid black;"> {{ date('d/m/Y H:i', strtotime($rota->dataHoraSaida)) }} </td>

                                    <td>
                                        @if ($rota->isAereo == 1)
                                            <img src="{{ asset('/img/aviaodescendo.png') }}" style="width: 40px">
                                        @endif

                                        @if ($rota->isVeiculoProprio == 1 || $rota->isVeiculoEmpresa == 1)
                                            <img src="{{ asset('/img/carro.png') }}" style="width: 40px">
                                        @endif

                                        @if ($rota->isOnibusLeito == 1 || $rota->isOnibusConvencional == 1)
                                            <img src="{{ asset('/img/onibus.png') }}" style="width: 40px">
                                        @endif

                                        @if($rota->isOutroMeioTransporte == 1)
                                            <img src="{{asset('/img/outros.png')}}" style="width: 40px" >
                                        @endif

                                        {{ $rota->isViagemInternacional == 0 ? $rota->cidadeDestinoNacional : $rota->cidadeDestinoInternacional }}
                                    </td>
                                    <td>
                                        {{ $rota->isViagemInternacional == 0 ? $rota->estadoDestinoNacional : $rota->estadoDestinoInternacional }}
                                    </td>

                                    <td> {{ date('d/m/Y H:i', strtotime($rota->dataHoraChegada)) }} </td>
                                    <td> {{ $rota->isReservaHotel == 1 ? 'Sim' : 'Não' }}</td>
                                    <td>
                                        {{ $rota->isOnibusLeito == 1 ? 'Onibus leito' : '' }}
                                        {{ $rota->isOnibusConvencional == 1 ? 'Onibus convencional' : '' }}
                                        @if ($rota->isVeiculoProprio == 1)
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
                                        {{ $rota->isVeiculoEmpresa == 1 ? 'Veículo empresa' : '' }}
                                        {{ $rota->isAereo == 1 ? 'Aéreo' : '' }}
                                        {{ $rota->isOutroMeioTransporte == 1 ? "Outros" : ""}}
                                        {{ $rota->isOutroMeioTransporte == 2 ? "Carona" : ""}}
                                    </td>
                                    @php
                                        $achouVeiculo = false;
                                    @endphp

                                </tr>
                            @endforeach
                        </tbody>
                    </table>
            </div>
            <div class="tab-pane fade" id="custom-tabs-three-relatorio" role="tabpanel"
                aria-labelledby="custom-tabs-three-relatorio-tab">
                <h1 style="font-size: 24px"><strong>Relatório:</strong></h1>

                <div class="form-group">
                    <label for="contatos" class="control-label">Contatos:</label><br>
                    <textarea type="textarea" class="textarea textarea-secondary textarea-lg" name="contatos" id="contatos"
                        placeholder="Contatos" style="width: 100%; height: 100px" disabled>{{ $av->contatos }}</textarea>
                </div>
                <div class="form-group">
                    <label for="atividades" class="control-label">Atividades:</label><br>
                    <textarea type="text" class="textarea textarea-secondary textarea-lg" name="atividades" id="atividades"
                        placeholder="Atividades" style="width: 100%; height: 100px" disabled>{{ $av->atividades }}</textarea>
                </div>
                <div class="form-group">
                    <label for="conclusoes" class="control-label">Conclusões:</label><br>
                    <textarea type="text" class="textarea textarea-secondary textarea-lg" name="conclusoes" id="conclusoes"
                        placeholder="Conclusões" style="width: 100%; height: 100px" disabled>{{ $av->conclusoes }}</textarea>
                </div>
            </div>
            <div class="tab-pane fade" id="custom-tabs-three-hotel" role="tabpanel"
                aria-labelledby="custom-tabs-three-hotel-tab">
                <h3 class="text-lg font-bold" style="padding-left: 10%; padding-bottom: 20px">Reserva de hotel
                </h3>
                <table id="minhaTabela1" class="table table-hover table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th>Descrição</th>
                            {{-- <th>IdRota</th> --}}
                            <th>Rota</th>
                            <th>Anexo</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($anexosRotas as $anexoHotel)
                            @if ($anexoHotel->anexoHotel != null)
                                <tr>
                                    <td> {{ $anexoHotel->descricao }} </td>

                                    {{-- <td>
                                        @for ($i = 0; $i < count($av->rotas); $i++)
                                            @if ($anexoHotel->rota_id == $av->rotas[$i]->id)
                                                {{ $av->rotas[$i]->id }}
                                            @endif
                                        @endfor
                                    </td> --}}
                                    <td>
                                        @for ($i = 0; $i < count($av->rotas); $i++)
                                            @if ($anexoHotel->rota_id == $av->rotas[$i]->id)
                                                @if ($av->rotas[$i]->isViagemInternacional == 0)
                                                    - {{ $av->rotas[$i]->cidadeOrigemNacional }}
                                                    -> {{ $av->rotas[$i]->cidadeDestinoNacional }}
                                                @endif

                                                @if ($av->rotas[$i]->isViagemInternacional == 1)
                                                    - {{ $av->rotas[$i]->cidadeOrigemInternacional }}
                                                    -> {{ $av->rotas[$i]->cidadeDestinoInternacional }}
                                                @endif
                                            @endif
                                        @endfor
                                    </td>
                                    <td> 
                                        <a href="{{ route('recuperaArquivo', [
                                        'name' => $userAv->name,
                                        'id' => $av->id,
                                        'pasta' => 'null',
                                        'anexoRelatorio' => $anexoHotel->anexoHotel,
                                        ]) }}"
                                        target="_blank" class="btn btn-active btn-success btn-sm">Abrir documento</a>
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>

            </div>
            <div class="tab-pane fade" id="custom-tabs-three-transporte" role="tabpanel"
                aria-labelledby="custom-tabs-three-transporte-tab">
                <h3 class="text-lg font-bold" style="padding-left: 10%; padding-bottom: 20px">Reservas de
                    transporte</h3>

                <table id="minhaTabela2" class="table table-hover table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th>Descrição</th>
                            {{-- <th>IdRota</th> --}}
                            <th>Rota</th>
                            <th>Anexo</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($anexosRotas as $anexoTransporte)
                            @if ($anexoTransporte->anexoTransporte != null)
                                <tr>
                                    <td> {{ $anexoTransporte->descricao }} </td>

                                    {{-- <td>
                                        @for ($i = 0; $i < count($av->rotas); $i++)
                                            @if ($anexoTransporte->rota_id == $av->rotas[$i]->id)
                                                {{ $av->rotas[$i]->id }}
                                            @endif
                                        @endfor
                                    </td> --}}
                                    <td>
                                        @for ($i = 0; $i < count($av->rotas); $i++)
                                            @if ($anexoTransporte->rota_id == $av->rotas[$i]->id)
                                                @if ($av->rotas[$i]->isViagemInternacional == 0)
                                                    - {{ $av->rotas[$i]->cidadeOrigemNacional }}
                                                    -> {{ $av->rotas[$i]->cidadeDestinoNacional }}
                                                @endif

                                                @if ($av->rotas[$i]->isViagemInternacional == 1)
                                                    - {{ $av->rotas[$i]->cidadeOrigemInternacional }}
                                                    -> {{ $av->rotas[$i]->cidadeDestinoInternacional }}
                                                @endif
                                            @endif
                                        @endfor
                                    </td>
                                    <td> 
                                        <a href="{{ route('recuperaArquivo', [
                                            'name' => $userAv->name,
                                            'id' => $av->id,
                                            'pasta' => 'null',
                                            'anexoRelatorio' => $anexoTransporte->anexoTransporte,
                                            ]) }}"
                                            target="_blank" class="btn btn-active btn-success btn-sm">Abrir documento</a>
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="tab-pane fade" id="custom-tabs-three-adiantamentos" role="tabpanel"
                aria-labelledby="custom-tabs-three-adiantamentos-tab">
                <h3 class="text-lg font-bold" style="padding-left: 10%; padding-bottom: 20px">Adiantamentos
                    realizados
                </h3>

                <table id="minhaTabela3" class="table table-hover table-bordered" style="width:100%;">
                    <thead>
                        <tr>
                            <th>Descrição</th>
                            <th>Anexo</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($anexosFinanceiro as $anexoFinanceiro)
                            <tr>
                                <td> {{ $anexoFinanceiro->descricao }} </td>

                                <td> 
                                    <a href="{{ route('recuperaArquivo', [
                                        'name' => $userAv->name,
                                        'id' => $av->id,
                                        'pasta' => 'adiantamentos',
                                        'anexoRelatorio' => $anexoFinanceiro->anexoFinanceiro,
                                        ]) }}"
                                        target="_blank" class="btn btn-active btn-success btn-sm">Abrir documento</a>
                                </td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="tab-pane fade" id="custom-tabs-three-despesas" role="tabpanel"
                aria-labelledby="custom-tabs-three-despesas-tab">
                <h1 style="font-size: 24px; padding-bottom: 20px"><strong>Comprovantes de despesa:</strong></h1>
                <table id="minhaTabela7" class="table table-hover table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th>Descrição</th>
                            <th>Valor reais</th>
                            @if($av->isAprovadoViagemInternacional)
                                <th>Valor dólar</th>
                            @endif
                            <th>Anexo</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($comprovantes as $comp)
                            <tr>
                                <td> {{ $comp->descricao }} </td>
                                <td> {{ $comp->valorReais }} </td>
                                @if($av->isAprovadoViagemInternacional)
                                    <td> {{ $comp->valorDolar }} </td>
                                @endif
                                <td> 
                                    
                                    <a href="{{ route('recuperaArquivo', [
                                        'name' => $userAv->name,
                                        'id' => $av->id,
                                        'pasta' => 'comprovantesDespesa',
                                        'anexoRelatorio' => $comp->anexoDespesa,
                                        ]) }}"
                                        target="_blank" class="btn btn-active btn-success btn-sm"><i class="fas fa-paperclip"></i></a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="tab-pane fade" id="custom-tabs-three-medicoes" role="tabpanel"
                aria-labelledby="custom-tabs-three-medicoes-tab">
                <h1 style="font-size: 24px; padding-bottom: 20px"><strong>Medições vinculadas:</strong></h1>
                <table id="minhaTabela8" class="table table-hover table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th>Nome do município</th>
                            <th>Número do projeto</th>
                            <th>Número do lote</th>
                            <th>Número da medição</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($medicoesFiltradas as $med)
                            <tr>
                                <td> {{ $med->nome_municipio }} </td>
                                <td> {{ $med->numero_projeto }} </td>
                                <td> {{ $med->numero_lote }} </td>
                                <td> {{ $med->numero_medicao }} </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
</div>

<x-adminlte-modal id="my-modal-5" title="Detalhamento" size="xl" theme="teal"
        icon="fas fa-bell" v-centered static-backdrop scrollable>

        <div class="row">
            <div class="col-md-6 col-12">
                <img src="/img/valores.png" style="width: 100%;">
            </div>
            <div class="col-md-6 col-12">
            
                <table class="table table-hover table-bordered" style="width: 100%">
                    <thead>
                        <tr>
                            <th style="vertical-align: middle; text-align: center;">Dias</th>
                            <th style="vertical-align: middle; text-align: center;">Trajeto Dia</th>
                            <th style="vertical-align: middle; text-align: center;">Diária Almoço</th>
                            <th style="vertical-align: middle; text-align: center;">Diária Jantar</th>
                            <th style="vertical-align: middle; text-align: center;">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $j=0;
                            $cores = ['bg-blue', 'bg-success']; // Adicione mais cores conforme necessário
                            $corIndex = 0; // Índice inicial
                        @endphp
                        
                        @for($i = 0; $i <= sizeof($arrayDiasValores)-1; $i++)
                            <tr style="vertical-align: middle; text-align: center;">
                                <td style="vertical-align: middle; text-align: center;">
                                    {{$arrayDiasValores[$j]['dia']}}
                                </td>
                                <td style="vertical-align: middle">
                                    @foreach($arrayDiasValores[$j]['arrayRotasDoDia'] as $r)
                                        {{-- Verifique se $r começa com "ida" --}}
                                        @if(strpos($r, 'Ida:') !== false)
                                            <span class="badge {{$cores[$corIndex]}}">{{str_replace('Ida:', '', $r)}}</span>
                                        @else
                                            <span class="badge {{$cores[$corIndex]}}">{{$r}}</span><br>
                                            @php
                                                $corIndex = ($corIndex + 1) % count($cores); // Avança para a próxima cor, voltando ao início se necessário
                                            @endphp
                                        @endif
                                    @endforeach
                                </td>
                                <td style="vertical-align: middle; text-align: center;"> 
                                    @if($arrayDiasValores[$j]['valorManha'] != 0)
                                        <span><strong>{{(
                                            in_array($arrayDiasValores[$j]['valor'], [190, 95, 180, 90, 140, 70, 150, 75, 100, 50]) &&
                                            !collect($arrayDiasValores[$j]['arrayRotasDoDia'])->contains(fn($rota) => strpos($rota, 'Brasília') !== false)
                                            ? "$"
                                            : "R$")
                                        }}{{ number_format($arrayDiasValores[$j]['valorManha'], 2, ',', '.') }}</strong></span>                                    
                                    @else
                                        -
                                    @endif
                                </td>
                                <td style="vertical-align: middle; text-align: center;">
                                    @if($arrayDiasValores[$j]['valorTarde'] != 0)
                                        <span><strong>{{(
                                            in_array($arrayDiasValores[$j]['valor'], [190, 95, 180, 90, 140, 70, 150, 75, 100, 50]) &&
                                            !collect($arrayDiasValores[$j]['arrayRotasDoDia'])->contains(fn($rota) => strpos($rota, 'Brasília') !== false)
                                            ? "$"
                                            : "R$")
                                        }}{{ number_format($arrayDiasValores[$j]['valorTarde'], 2, ',', '.') }}</strong></span>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td style="vertical-align: middle; text-align: center;"> 
                                    <span><strong>{{(
                                        in_array($arrayDiasValores[$j]['valor'], [190, 95, 180, 90, 140, 70, 150, 75, 100, 50]) &&
                                        !collect($arrayDiasValores[$j]['arrayRotasDoDia'])->contains(fn($rota) => strpos($rota, 'Brasília') !== false)
                                        ? "$"
                                        : "R$")
                                    }}{{ number_format($arrayDiasValores[$j]['valor'], 2, ',', '.') }}</strong></span>                                    
                                </td>
                            </tr>
                        
                            @php
                                $j++;
                            @endphp
                        @endfor
                    
                    </tbody>
                </table>
            </div>
            <br><br>
        </div>
    <x-slot name="footerSlot">
        <x-adminlte-button theme="danger" label="Fechar" data-dismiss="modal"/>
    </x-slot>

</x-adminlte-modal>

<x-adminlte-modal id="my-modal-3" title="Cálculos" size="xl" theme="teal"
        icon="fas fa-bell" v-centered static-backdrop scrollable>

    <div>
        <br>
        <h3 class="text-lg font-bold" style="padding-left: 10%">A diária de alimentação é calculada da seguinte forma:</h3>
        <img src="/img/horarios.png" style="width: 100%;">
        
    </div>
    <x-slot name="footerSlot">
        <x-adminlte-button theme="danger" label="Fechar" data-dismiss="modal"/>
    </x-slot>

</x-adminlte-modal>

@stop

@section('css')
    
@stop

@section('js')
    
@stop









