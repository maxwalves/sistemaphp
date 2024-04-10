<!DOCTYPE html>
<html>
    
    <head>
            <title>Relatório PDF</title>
            <style>
        
            table.comBordaSimples {
                border-collapse: collapse; /* CSS2 */
                background: #FFFFF0;
            }
            
            table.comBordaSimples td {
                border: 1px solid black;
            }
            
            table.comBordaSimples th {
                border: 1px solid black;
                background: #F0FFF0;
            }
        </style>
    </head>
    <body>
        <div style="position: relative;">
            <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('/img/headerpc.png'))) }}" alt="Paranacidade" width="100%">
            <h1 style="position: absolute; top: 95; right: 20; text-align: right;">
                {{ $av->id}}
            </h1>
        </div>
        <main style="font-size: 12px">
            
            <div style="font-size: 12px">
                
                <div class="row">
                    <br><br>
                    <h3 >Dados básicos:</h3>
                    <div style="border: 1px solid black; padding-left:10px">

                        <p><strong>Nome:</strong> {{ $userAv->name }} <strong> Setor:</strong> {{ $userAv->department }} </p>

                        <p><strong>Matrícula:</strong> {{ $userAv->employeeNumber }} </p>

                        <p><strong>Data de criação AV: </strong> {{ date('d/m/Y', strtotime($av->dataCriacao)) }} 
                            <strong>Objetivo:</strong> 

                            @for($i = 0; $i < count($objetivos); $i++)
    
                                @if ($av->objetivo_id == $objetivos[$i]->id )
                                        {{$objetivos[$i]->nomeObjetivo}}     
                                @endif
            
                            @endfor
    
                            @if (isset($av->outroObjetivo))
                                    {{$av->outroObjetivo }} 
                            @endif
                        </p>

                        <p><strong>Comentário:</strong> {{ $av->comentario }} </p>
                        
                    </div>
                    
                    <h3 >Dados bancários:</h3>
                    
                    <div style="border: 1px solid black; padding-left:10px">
    
                        <p><strong>Banco:</strong> {{ $av->banco }} <strong> Agência:</strong> {{ $av->agencia }} <strong> Conta:</strong> {{ $av->conta }} <strong> Pix:</strong> {{ $av->pix }}</p>
                        
                    </div>

                    <h3 >Relatório:</h3>
                    
                    <div style="border: 1px solid black; padding-left:10px">
    
                        <p><strong>Contatos:</strong> {{ $av->contatos }}</p>
                        <p><strong> Atividades:</strong> {{ $av->atividades }}</p>
                        <p><strong> Conclusões:</strong> {{ $av->conclusoes }}</p>
                        
                    </div>

                    <h3 >Pagamento realizado no acerto de contas:</h3>
                    <div style="border: 1px solid black; padding-left:10px">
                        <p><strong>Valor:</strong> R$ {{ number_format($av->valorReais, 2, ',', '.') }}</p>
                        <p><strong>Valor extra:</strong> R$ {{ number_format($av->valorExtraReais, 2, ',', '.') }}</p>
                        <p><strong>Dedução:</strong> R$ {{ number_format($av->valorDeducaoReais, 2, ',', '.') }}</p>

                        @if($av->isAprovadoCarroDiretoriaExecutiva == true)
                            <p><strong>Valor referente a {{$av->qtdKmVeiculoProprio}} Km: </strong> R$ {{ number_format($av->qtdKmVeiculoProprio * 0.49, 2, ',', '.') }}</p>
                            <p><strong>Valor TOTAL reais:</strong> R$ {{ number_format($av->valorReais + $av->valorExtraReais - $av->valorDeducaoReais + ($av->qtdKmVeiculoProprio * 0.49), 2, ',', '.') }}</p>
                        @else
                            <p><strong>Valor TOTAL reais:</strong> R$ {{ number_format($av->valorReais + $av->valorExtraReais - $av->valorDeducaoReais, 2, ',', '.') }}</p>
                        @endif

                        {{-- <p><strong>Valor em dolar:</strong> $ {{ $av->valorDolar }}</p>
                        <p><strong>Valor extra em dólar:</strong> $ {{ $av->valorExtraDolar }}</p>
                        <p><strong>Dedução em dólar:</strong> $ {{ $av->valorDeducaoDolar }}</p>
                        <p><strong>Valor TOTAL dólar:</strong> $ {{ $av->valorDolar + $av->valorExtraDolar - $av->valorDeducaoDolar }}</p>
                        <p><strong>Justificativa valor extra:</strong> {{ $av->justificativaValorExtra }}</p> --}}
                        
                    </div>
                </div>
            </div>
            <h3>Resultado:</h3>
            <div style="border: 1px solid black; padding-left:10px">

                <div class="stats shadow">
              
                    <div class="stat">
                        <div class="stat-title">
                            <p>
                                @if($av->isAprovadoCarroDiretoriaExecutiva == true)
                                    @if(( ($valorRecebido->valorReais-$av->valorReais - ($av->qtdKmVeiculoProprio * 0.49)) +($valorRecebido->valorExtraReais-$valorAcertoContasReal) )<0)
                                        Valor que o usuário deve receber em reais
                                    @endif
                                    @if(( ($valorRecebido->valorReais-$av->valorReais - ($av->qtdKmVeiculoProprio * 0.49)) +($valorRecebido->valorExtraReais-$valorAcertoContasReal) )>0)
                                        Valor que o usuário deve pagar em reais
                                    @endif
                                @else
                                    @if((($valorRecebido->valorReais-$av->valorReais) + ($valorRecebido->valorExtraReais-$valorAcertoContasReal))<0)
                                        Valor que o usuário deve receber em reais
                                    @endif
                                    @if((($valorRecebido->valorReais-$av->valorReais) + ($valorRecebido->valorExtraReais-$valorAcertoContasReal))>0)
                                        Valor que o usuário deve pagar em reais
                                    @endif
                                    @if((($valorRecebido->valorReais-$av->valorReais) + ($valorRecebido->valorExtraReais-$valorAcertoContasReal))==0)
                                        O somatório dos valores das diárias da Viagem informada na AV não foi alterado na PC.
                                    @endif
                                @endif
                            </p>
                        </div>

                        @if($av->isAprovadoCarroDiretoriaExecutiva == true)
                            @if( ( ($valorRecebido->valorReais-$av->valorReais)+ ($valorRecebido->valorExtraReais-$valorAcertoContasReal) - ($av->qtdKmVeiculoProprio * 0.49) <0))
                                <div class="stat-value text-green-500">
                                        R$ {{number_format((($valorRecebido->valorReais-$av->valorReais) + ($valorRecebido->valorExtraReais-$valorAcertoContasReal) - (($av->qtdKmVeiculoProprio * 0.49))) * (-1), 2, ',', '.') }}
                                </div>
                            @endif
                            @if( ( ($valorRecebido->valorReais-$av->valorReais)+ ($valorRecebido->valorExtraReais-$valorAcertoContasReal) - ($av->qtdKmVeiculoProprio * 0.49) >0))
                                <div class="stat-value text-error">
                                        R$ {{number_format((($valorRecebido->valorReais-$av->valorReais) + ($valorRecebido->valorExtraReais-$valorAcertoContasReal) - (($av->qtdKmVeiculoProprio * 0.49))) * (-1), 2, ',', '.') }}
                                </div>
                            @endif
                        @else
                            @if((($valorRecebido->valorReais-$av->valorReais) + ($valorRecebido->valorExtraReais-$valorAcertoContasReal))<0)
                                <div class="stat-value text-green-500">
                                        R$ {{number_format((($valorRecebido->valorReais-$av->valorReais) + ($valorRecebido->valorExtraReais-$valorAcertoContasReal)) * (-1), 2, ',', '.')}}
                                </div>
                            @endif
                            @if((($valorRecebido->valorReais-$av->valorReais) + ($valorRecebido->valorExtraReais-$valorAcertoContasReal))>0)
                                <div class="stat-value text-error">
                                        R$ {{number_format((($valorRecebido->valorReais-$av->valorReais) + ($valorRecebido->valorExtraReais-$valorAcertoContasReal)), 2, ',', '.')}}
                                </div>
                            @endif
                        @endif
                                
                    </div>
                    <div class="stat">
                        <div class="stat-title">
                            <p>
                                @if((($valorRecebido->valorDolar-$av->valorDolar) + ($valorRecebido->valorExtraDolar-$valorAcertoContasDolar))<0)
                                    Valor que o usuário deve receber em dólar
                                @endif
                                @if((($valorRecebido->valorDolar-$av->valorDolar) + ($valorRecebido->valorExtraDolar-$valorAcertoContasDolar))>0)
                                    Valor que o usuário deve pagar em dólar
                                @endif
                            </p>
                        </div>
                        @if((($valorRecebido->valorDolar-$av->valorDolar) + ($valorRecebido->valorExtraDolar-$valorAcertoContasDolar))<0)
                            <div class="stat-value text-green-500">
                                            
                                    $ {{(($valorRecebido->valorDolar-$av->valorDolar) + ($valorRecebido->valorExtraDolar-$valorAcertoContasDolar)) * (-1)}}
                            </div>
                        @endif
                        @if((($valorRecebido->valorDolar-$av->valorDolar) + ($valorRecebido->valorExtraDolar-$valorAcertoContasDolar))>0)
                            <div class="stat-value text-error">
                                    $ {{(($valorRecebido->valorDolar-$av->valorDolar) + ($valorRecebido->valorExtraDolar-$valorAcertoContasDolar))}}
                            </div>
                        @endif
                    </div>
                            
                </div>
            </div>
            <br><br>
            
            <div class="col-md-8 offset-md-0" style="font-size: 12px">
                <h3><strong>Detalhamento: </strong></h3>
                <table id="tabelaRota" class="comBordaSimples" style="width: 100%">
                    <thead>
                        <tr>
                            <th>Estado de saída</th>
                            <th>Cidade de saída</th>
                            <th>Data/Hora de saída</th>
                            <th>-</th>
                            <th>Estado de chegada</th>
                            <th>Cidade de chegada</th>
                            <th>Data/Hora de chegada</th>

                        </tr>
                    </thead>
                    <tbody>
                        @foreach($av->rotas as $rota)
                        <tr>
                            <td style="text-align: center"> {{$rota->isViagemInternacional == 0 ? $rota->estadoOrigemNacional : $rota->estadoOrigemInternacional}} </td>
                            <td style="text-align: center"> {{$rota->isViagemInternacional == 0 ? $rota->cidadeOrigemNacional : $rota->cidadeOrigemInternacional}} </td>
                            <td style="text-align: center"> {{ date('d/m/Y H:i', strtotime($rota->dataHoraSaida)) }} </td>
                            <td>-</td>
                            <td style="text-align: center">{{$rota->isViagemInternacional == 0 ? $rota->estadoDestinoNacional : $rota->estadoDestinoInternacional}} </td>
                            <td style="text-align: center">{{$rota->isViagemInternacional == 0 ? $rota->cidadeDestinoNacional : $rota->cidadeDestinoInternacional}} </td>
                            <td style="text-align: center"> {{ date('d/m/Y H:i', strtotime($rota->dataHoraChegada)) }} </td>
                            
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <br><br><br><br>
                <h3><strong>Histórico: </strong></h3>
                <table id="minhaTabela" class="comBordaSimples" style="width: 100%">
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

                    @for($i = 0; $i < count($historicos); $i++)
                            <tr>
                                <td style="text-align: center">{{ date('d/m/Y H:i', strtotime($historicos[$i]->dataOcorrencia)) }}</td>
                                <td style="text-align: center">{{ $historicos[$i]->tipoOcorrencia }}</td>
                                <td style="text-align: center">{{ $historicos[$i]->comentario }}</td>
                                <td style="text-align: center">{{ $historicos[$i]->perfilDonoComentario }}</td>
                                
                                @foreach($users as $u)
                                    @if ($u->id == $historicos[$i]->usuario_comentario_id)
                                        <td style="text-align: center">{{ $u->name }}</td>
                                    @endif
                                @endforeach
                            </tr>
                    @endfor
    
                    </tbody>
                </table>

                <h3><strong>Detalhamento de valores: </strong></h3>
                <table class="comBordaSimples" style="width: 100%">
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
                        @endphp
                        @for($i = 0; $i <= sizeof($arrayDiasValores)-1; $i++)
                                    
                            <tr style="vertical-align: middle; text-align: center;">
                                <td style="vertical-align: middle; text-align: center;">
                                    {{$arrayDiasValores[$j]['dia']}}
                                </td>
                                <td style="vertical-align: middle">
                                    @foreach($arrayDiasValores[$j]['arrayRotasDoDia'] as $r)
                                        {{-- verifique se $r começa com "ida" --}}
                                        @if(strpos($r, 'Ida:') !== false)
                                            {{str_replace('Ida:', '', $r)}}
                                        @else
                                            {{$r}}<br>
                                        @endif
                                    @endforeach
                                </td>
                                <td style="vertical-align: middle; text-align: center;"> 
                                    @if($arrayDiasValores[$j]['valorManha'] != 0)
                                        R${{ number_format($arrayDiasValores[$j]['valorManha'], 2, ',', '.') }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td style="vertical-align: middle; text-align: center;">
                                    @if($arrayDiasValores[$j]['valorTarde'] != 0)
                                        R${{ number_format($arrayDiasValores[$j]['valorTarde'], 2, ',', '.') }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td style="vertical-align: middle; text-align: center;"> 
                                    R${{ number_format($arrayDiasValores[$j]['valor'], 2, ',', '.') }}
                                </td>                                
                            </tr>

                            @php
                                $j++;
                            @endphp
                        @endfor
                    </tbody>
                </table>
                <br><br>
                @if(count($medicoesFiltradas) > 0)
                    <h3><strong>Medições: </strong></h3>
                    <table class="comBordaSimples" style="width: 100%">
                        <thead>
                            <tr>
                                <th style="vertical-align: middle; text-align: center;">Nome do município</th>
                                <th style="vertical-align: middle; text-align: center;">Número do projeto</th>
                                <th style="vertical-align: middle; text-align: center;">Número do lote</th>
                                <th style="vertical-align: middle; text-align: center;">Número da medição</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($medicoesFiltradas as $med)
                                <tr style="vertical-align: middle; text-align: center;">
                                    <td style="vertical-align: middle; text-align: center;"> {{ $med->nome_municipio }} </td>
                                    <td style="vertical-align: middle; text-align: center;"> {{ $med->numero_projeto }} </td>
                                    <td style="vertical-align: middle; text-align: center;"> {{ $med->numero_lote }} </td>
                                    <td style="vertical-align: middle; text-align: center;"> {{ $med->numero_medicao }} </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif

                <br><br><br>
                @if($av->idReservaVeiculo != null)
                    <h3 style="font-size: 12px"><strong>Reserva de veículo no sistema de reservas:</strong></h3>
                    @if(count($reservas2) > 0)
                        <table class="comBordaSimples" style="width: 100%">
                            <thead>
                                <tr>
                                    <th style="vertical-align: middle; text-align: center;">Nr Reserva</th>
                                    <th style="vertical-align: middle; text-align: center;">Data Início</th>
                                    <th style="vertical-align: middle; text-align: center;">Data Fim</th>
                                    <th style="vertical-align: middle; text-align: center;">Descrição</th>
                                    <th style="vertical-align: middle; text-align: center;">Veículo</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($reservas2 as $r)
                                    <tr>
                                        <td style="vertical-align: middle; text-align: center;">{{$r->id}}</td>
                                        <td style="vertical-align: middle; text-align: center;">{{date('d/m/Y H:i', strtotime($r->dataInicio))}}</td>
                                        <td style="vertical-align: middle; text-align: center;">{{date('d/m/Y H:i', strtotime($r->dataFim))}}</td>
                                        <td style="vertical-align: middle; text-align: center;">{{$r->observacoes}}</td>
                                        <td style="vertical-align: middle; text-align: center;">{{$r->veiculo->marca}} - {{$r->veiculo->modelo}} - {{$r->veiculo->placa}}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <p>Nenhuma reserva encontrada</p>
                    @endif
                @endif

                <h3 style="font-size: 12px"><strong>Data de geração do documento PC: {{$dataFormatadaAtual}} </strong></h3>
            </div>
            
        </main>
        
    </body>
</html>
