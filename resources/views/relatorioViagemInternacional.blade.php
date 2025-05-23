<!DOCTYPE html>
<html>
    <head>
            <title>Relatório PDF</title>
    </head>
    <body>
        <main>
            <div >
                <div class="row">
                    <div style="position: relative;">
                        <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('/img/internacional.png'))) }}" alt="Paranacidade" width="100%">
                        <h1 style="position: absolute; top: 95; right: 20; text-align: right;">
                            {{ $av->id}}
                        </h1>
                    </div>
                    <h2 >Dados básicos:</h2>
                    <div style="border: 1px solid black; padding-left:10px">

                        <p><strong>Nome:</strong> {{ $userAv->name }} </p>
                        
                        <p><strong>Setor:</strong> {{ $userAv->department }} </p>
                        <p><strong>Matrícula:</strong> {{ $userAv->employeeNumber }} </p>
                        
                        <br>
                        <p><strong>Data de criação AV: </strong> {{ date('d/m/Y', strtotime($av->dataCriacao)) }} </p>
                        <p><strong>Objetivo:</strong> 

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
                    <br>
                    <h2 >Dados bancários:</h2>
                    
                    <div style="border: 1px solid black; padding-left:10px">
    
                        <p><strong>Banco:</strong> {{ $av->banco }} </p>
                        <p><strong>Agência:</strong> {{ $av->agencia }} </p>
                        <p><strong>Conta:</strong> {{ $av->conta }} </p>
                        <p><strong>Pix:</strong> {{ $av->pix }} </p>
                        
                    </div>
                    <br><br><br><br><br><br><br>

                    <h2 >Adiantamentos:</h2>
                    <div style="border: 1px solid black; padding-left:10px">
                        <p><strong>Valor em reais:</strong> R$ {{ $av->valorReais }}</p>
                        <p><strong>Valor extra em reais:</strong> R$ {{ $av->valorExtraReais }}</p>
                        <p><strong>Dedução em reais:</strong> R$ {{ $av->valorDeducaoReais }}</p>
                        <p><strong>Valor TOTAL reais:</strong> R$ {{ $av->valorReais + $av->valorExtraReais - $av->valorDeducaoReais }}</p>

                        @if($av->isAprovadoViagemInternacional == true)
                            <p><strong>Valor em dolar:</strong> $ {{ $av->valorDolar }}</p>
                            <p><strong>Valor extra em dólar:</strong> $ {{ $av->valorExtraDolar }}</p>
                            <p><strong>Dedução em dólar:</strong> $ {{ $av->valorDeducaoDolar }}</p>
                            <p><strong>Valor TOTAL dólar:</strong> $ {{ $av->valorDolar + $av->valorExtraDolar - $av->valorDeducaoDolar}}</p>
                            <p><strong>Justificativa valor extra:</strong> {{ $av->justificativaValorExtra }}</p>
                        @endif
                        
                    </div>
                </div>
            </div>
            <br><br>
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
            <div class="col-md-8 offset-md-0">
                <h1 style="font-size: 24px"><strong>Trajeto: </strong></h1>
                <table id="tabelaRota" class="comBordaSimples " >
                    <thead>
                        <tr>
                            {{-- <th>Número</th> --}}
                            <th>Tipo</th>
                            <th>Cidade de saída</th>
                            <th>Data/Hora de saída</th>
                            <th>-</th>
                            <th>Cidade de chegada</th>
                            <th>Data/Hora de chegada</th>
                            <th>Hotel?</th>
                            <th>Tipo de transporte</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($av->rotas as $rota)
                        <tr>
                            {{-- <td style="text-align: center"> {{$rota->id}} </td> --}}
                            <td style="text-align: center"> {{$rota->isViagemInternacional == 1 ? "Internacional" : "Nacional"}} </td>
                            <td style="text-align: center"> {{$rota->isViagemInternacional == 0 ? $rota->cidadeOrigemNacional : $rota->cidadeOrigemInternacional}} </td>
                            <td style="text-align: center"> {{ date('d/m/Y H:i', strtotime($rota->dataHoraSaida)) }} </td>

                            <td>-</td>
                            <td style="text-align: center">{{$rota->isViagemInternacional == 0 ? $rota->cidadeDestinoNacional : $rota->cidadeDestinoInternacional}} </td>
            
                            <td style="text-align: center"> {{ date('d/m/Y H:i', strtotime($rota->dataHoraChegada)) }} </td>
                            <td style="text-align: center"> {{ $rota->isReservaHotel == 1 ? "Sim" : "Não"}}</td>
                            <td style="text-align: center"> 
                                {{ $rota->isOnibusLeito == 1 ? "Onibus leito" : ""}}
                                {{ $rota->isOnibusConvencional == 1 ? "Onibus convencional" : ""}}
                                {{ $rota->isVeiculoProprio == 1 ? "Veículo próprio" : ""}}
                                {{ $rota->isVeiculoEmpresa == 1 ? "Veículo empresa" : ""}}
                                {{ $rota->isAereo == 1 ? "Aéreo" : ""}}
                                {{ $rota->isOutroMeioTransporte == 1 ? "Outros" : ""}}
                                {{ $rota->isOutroMeioTransporte == 2 ? "Carona" : ""}}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <h1 style="font-size: 24px"><strong>Detalhamento: </strong></h1>
                <table id="tabelaRota" class="comBordaSimples" >
                    <thead>
                        <tr>
                            @if($av->isAprovadoViagemInternacional)
                                <th>País de saída</th>
                            @endif
                            <th>Estado de saída</th>
                            <th>Cidade de saída</th>
                            <th>Data/Hora de saída</th>
                            <th>-</th>
                            @if($av->isAprovadoViagemInternacional)
                                <th>País de chegada</th>
                            @endif
                            <th>Estado de chegada</th>
                            <th>Cidade de chegada</th>
                            <th>Data/Hora de chegada</th>

                        </tr>
                    </thead>
                    <tbody>
                        @foreach($av->rotas as $rota)
                        <tr>
                            @if($av->isAprovadoViagemInternacional)
                                @foreach($paises as $p)
                                    @if ($p->id == $rota->paisOrigemInternacional)
                                        <td style="text-align: center"> {{$rota->isViagemInternacional == 0 ? "Brasil" : $p->name}} </td>
                                    @endif
                                @endforeach
                            @endif
                            <td style="text-align: center"> {{$rota->isViagemInternacional == 0 ? $rota->estadoOrigemNacional : $rota->estadoOrigemInternacional}} </td>
                            <td style="text-align: center"> {{$rota->isViagemInternacional == 0 ? $rota->cidadeOrigemNacional : $rota->cidadeOrigemInternacional}} </td>
                            <td style="text-align: center"> {{ date('d/m/Y H:i', strtotime($rota->dataHoraSaida)) }} </td>
                            <td>></td>
                            @if($av->isAprovadoViagemInternacional)
                                @foreach($paises as $p)
                                    @if ($p->id == $rota->paisDestinoInternacional)
                                        <td style="text-align: center"> {{$rota->isViagemInternacional == 0 ? "Brasil" : $p->name}} </td>
                                    @endif
                                @endforeach
                            @endif
                            <td style="text-align: center">{{$rota->isViagemInternacional == 0 ? $rota->estadoDestinoNacional : $rota->estadoDestinoInternacional}} </td>
                            <td style="text-align: center">{{$rota->isViagemInternacional == 0 ? $rota->cidadeDestinoNacional : $rota->cidadeDestinoInternacional}} </td>
                            <td style="text-align: center"> {{ date('d/m/Y H:i', strtotime($rota->dataHoraChegada)) }} </td>
                            
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                
                <h1 style="font-size: 24px"><strong>Histórico: </strong></h1>
                <table id="minhaTabela" class="comBordaSimples">
                    <!-- head -->
                    <thead>
                    <tr>
                        {{-- <th>Id</th> --}}
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
                                {{-- <td style="text-align: center">{{ $a = $i +1 }}</td> --}}
                                <td style="text-align: center">{{ $historicos[$i]->dataOcorrencia }}</td>
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
            </div>
        </main>
        
    </body>
</html>
