@extends('layouts.main')

@section('title', 'Editando: ' . $av->id)
@section('content')

<div class="row justify-content-start" style="padding-left: 5%">
    <div class="col-3">
        <a href="/avs/autSecretaria" type="submit" class="btn btn-active btn-ghost"> Voltar!</a>
    </div>
    <div class="col-6">
        @if($av->isCancelado == 1 && $av->isRealizadoReserva == 1)
            <div class="alert alert-warning">
                <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                <span><strong>A AV Atual foi cancelada pelo usuário. Verifique as reservas e realize os devidos cancelamentos.</strong></span>
            </div>
        @endif
    </div>
</div>
<div id="av-create-container" class="container">
    
        
        <h1 style="font-size: 24px"><strong>Autorização de viagem nº:</strong> {{ $av->id }}</h1>
        <h1 style="font-size: 24px"><strong>Status atual:</strong> {{ $av->status }}</h1>
        <p class="av-owner" style="font-size: 20px"><ion-icon name="chevron-forward-circle-outline">
        </ion-icon> <strong>Nome do usuário: </strong> 
        @foreach($users as $u)
                @if ($u->id == $av->user_id)
                    {{ $u->name }}
                @endif
        @endforeach
        </p>        
        <p class="av-owner" style="font-size: 20px"><ion-icon name="chevron-forward-circle-outline">
        </ion-icon> <strong>E-mail do usuário: </strong> 
        @foreach($users as $u)
                @if ($u->id == $av->user_id)
                    {{ $u->username }}
                @endif
        @endforeach
        </p>  

            <div >
                <label for="my-modal-3" class="btn">Histórico</label>
                <label for="my-modal-4" class="btn">Dados da AV</label>
                <label for="my-modal-5" class="btn">FLUXO</label>
                @if($av->objetivo_id == 3)
                    <label for="my-modal-14" class="btn" >Medições</label>
                @endif
                @foreach($av->rotas as $r)
                    @if($r->isVeiculoEmpresa == true)
                        <label for="my-modal-6" data-rota="{{ $r->id }}" class="btn btn-active btn-success">Alocar veículo</label>
                    @endif
                    @break
                @endforeach
            </div>
            <div class="divider"></div> 
        

        <div class="col-md-12 offset-md-0">
            <h1 style="font-size: 24px"><strong>Trajeto: </strong></h1>
            <table id="tabelaRota" class="display nowrap" style="width:100%">
                <thead>
                    <tr>
                        <th>Tipo</th>
                        <th>Cidade de saída</th>
                        <th>Data/Hora de saída</th>
                        <th>Cidade de chegada</th>
                        <th>Data/Hora de chegada</th>
                        <th>Hotel?</th>
                        <th>Tipo de transporte</th>
                        @foreach($av->rotas as $rota)
                                @if($rota->isVeiculoEmpresa == 1)
                                    <th>Veículo</th>
                                    @break
                                @endif
                        @endforeach
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($av->rotas as $rota)
                    <tr>
                        <td> {{$rota->isViagemInternacional == 1 ? "Internacional" : "Nacional"}} </td>
                        <td> 
                            @if($rota->isAereo == 1)
                                <img src="{{asset('/img/aviaosubindo.png')}}" style="width: 40px" >
                            @endif
        
                            @if($rota->isVeiculoProprio == 1 || $rota->isVeiculoEmpresa == 1)
                                <img src="{{asset('/img/carro.png')}}" style="width: 40px" >
                            @endif
        
                            @if($rota->isOnibusLeito == 1 || $rota->isOnibusConvencional == 1)
                                <img src="{{asset('/img/onibus.png')}}" style="width: 40px" >
                            @endif
        
                            {{$rota->isViagemInternacional == 0 ? $rota->cidadeOrigemNacional : $rota->cidadeOrigemInternacional}} 
                            
                        </td>
                        <td> {{ date('d/m/Y H:i', strtotime($rota->dataHoraSaida)) }} </td>
        
                        <td> 
                            @if($rota->isAereo == 1)
                                <img src="{{asset('/img/aviaodescendo.png')}}" style="width: 40px" >
                            @endif
        
                            @if($rota->isVeiculoProprio == 1 || $rota->isVeiculoEmpresa == 1)
                                <img src="{{asset('/img/carro.png')}}" style="width: 40px" >
                            @endif
        
                            @if($rota->isOnibusLeito == 1 || $rota->isOnibusConvencional == 1)
                                <img src="{{asset('/img/onibus.png')}}" style="width: 40px" >
                            @endif
        
                            {{$rota->isViagemInternacional == 0 ? $rota->cidadeDestinoNacional : $rota->cidadeDestinoInternacional}} 
                        </td>
        
                        <td> {{ date('d/m/Y H:i', strtotime($rota->dataHoraChegada)) }} </td>
                        <td> {{ $rota->isReservaHotel == 1 ? "Sim" : "Não"}}</td>
                        <td> 
                            {{ $rota->isOnibusLeito == 1 ? "Onibus leito" : ""}}
                            {{ $rota->isOnibusConvencional == 1 ? "Onibus convencional" : ""}}
                            @if($rota->isVeiculoProprio == 1)
                                {{"Veículo próprio: "}} <br>
                                @foreach ($veiculosProprios as $v)

                                    @if($v->id == $rota->veiculoProprio_id)
                                        {{$v->modelo . '-' . $v->placa}}
                                    @endif
                                    
                                @endforeach
                                
                                @if(count($veiculosProprios) == 0)
                                    {{"Não encontrado"}}
                                @endif
                            @endif
                            {{ $rota->isVeiculoEmpresa == 1 ? "Veículo empresa" : ""}}
                            {{ $rota->isAereo == 1 ? "Aéreo" : ""}}
                        </td>

                        @php
                            $achouVeiculo = false;
                        @endphp
                        @if($rota->isVeiculoEmpresa == 1)
                            @foreach($veiculosParanacidade as $v)
                                    @if($rota->veiculoParanacidade_id == $v->id)
                                        @php
                                            $achouVeiculo = true;
                                        @endphp
                                    @endif
                            @endforeach
                            @if($achouVeiculo == true)
                                <td>
                                    {{ $v->modelo }} ({{ $v->placa }})
                                </td>
                            @else
                                <td>
                                    A definir
                                </td>
                            @endif
                        @endif
                        
                        <td>
                            @if($rota->isReservaHotel == true || $rota->isOnibusLeito == 1 || $rota->isOnibusConvencional == 1 || $rota->isAereo ==1)
                                
                                    <a href="/avs/realizarReservas/{{ $rota->id }}" class="btn btn-active btn-success"
                                        > Gerenciar Reservas</a> 
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            
        </div>

        <div class="divider"></div> 
        
        <div class="flex flex-row">
            <form action="/avs/secretariaAprovarAv" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                    <input type="text" hidden="true" id="id" name="id" value="{{ $av->id }}">
                    <label for="comentario">Comentário no envio: </label>
                    <br>
                    <textarea type="text" class="textarea textarea-bordered h-24" 
                        name="comentario" style="width: 200px"
                        id="comentario" placeholder="Comentário"></textarea>
                    @if($av->isCancelado == 0)
                        <button type="submit" class="btn btn-active btn-success">Aprovar AV</button>
                    @else
                        <button type="submit" class="btn btn-active btn-success">Finalizar AV</button>
                    @endif
            </form>
            
            @if($av->isCancelado == 0)
                <form action="/avs/secretariaReprovarAv" method="POST" enctype="multipart/form-data" style="padding-left: 10px">
                    @csrf
                    @method('PUT')
                        <input type="text" hidden="true" id="id" name="id" value="{{ $av->id }}">
                        <label for="comentario">Voltar AV para o usuário: </label>
                        <br>
                        <textarea type="text" class="textarea textarea-bordered h-24 {{ $errors->has('comentario') ? 'is-invalid' :''}}" 
                            name="comentario" style="width: 200px"
                            id="comentario" placeholder="Comentário"></textarea>
                        <button type="submit" class="btn btn-active btn-error">Reprovar AV</button>
                        @if ($errors->has('comentario'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('comentario') }}
                                </div>
                        @endif
                </form>
            @endif
        </div>

    </div>

    <input type="checkbox" id="my-modal-3" class="modal-toggle" />

    <div class="modal">
        <div class="modal-box w-11/12 max-w-7xl">
            <div class="modal-content">
                <label for="my-modal-3" class="btn btn-sm btn-circle absolute right-0 top-0">✕</label>
                <br>
                <h3 class="text-lg font-bold" style="padding-left: 10%">Histórico</h3>
                <table id="minhaTabela" class="display nowrap">
                    <!-- head -->
                    <thead>
                    <tr>
                        <th>Id</th>
                        <th>Data</th>
                        <th>Ocorrência</th>
                        <th>Comentário</th>
                        <th>Perfil</th>
                        <th>Quem comentou?</th>
                    </tr>
                    </thead>
                    <tbody>
                    <!-- row 1 -->
    
                    @foreach ($historicos as $historico)
                            <tr>
                                <td>{{ $historico->id }}</td>
                                <td>{{ $historico->dataOcorrencia }}</td>
                                <td>{{ $historico->tipoOcorrencia }}</td>
                                <td>{{ $historico->comentario }}</td>
                                <td>{{ $historico->perfilDonoComentario }}</td>
                                
                                @foreach($users as $u)
                                    @if ($u->id == $historico->usuario_comentario_id)
                                        <td>{{ $u->name }}</td>
                                    @endif
                                @endforeach
                            </tr>
                    @endforeach
    
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <input type="checkbox" id="my-modal-4" class="modal-toggle" />

    <div class="modal">
        <div class="modal-box w-11/12 max-w-2xl">
            <div class="modal-content">
                <label for="my-modal-4" class="btn btn-sm btn-circle absolute right-2 top-2">✕</label>
                <br>
                <h3 class="text-lg font-bold" style="padding-left: 10%">Dados</h3>

                <br>
                <h1 class="text-lg font-bold">Dados básicos:</h1>
                <div class="stats stats-vertical shadow">
                    
                    <p class="av-owner" style="font-size: 20px"><ion-icon name="chevron-forward-circle-outline">
                        </ion-icon> <strong>Nome do usuário: </strong> 
                        @foreach($users as $u)
                                @if ($u->id == $av->user_id)
                                    {{ $u->name }}
                                @endif
                        @endforeach
                    </p>        
                    <p class="av-owner" style="font-size: 20px"><ion-icon name="chevron-forward-circle-outline">
                    </ion-icon> <strong>E-mail do usuário: </strong> 
                    @foreach($users as $u)
                            @if ($u->id == $av->user_id)
                                {{ $u->username }}
                            @endif
                    @endforeach
                    </p>     
                    <p class="av-owner" style="font-size: 20px"><ion-icon name="calendar-outline"></ion-icon> <strong>Data de criação: </strong> {{ date('d/m/Y', strtotime($av->dataCriacao)) }} </p>
                    <p class="av-owner" style="font-size: 20px"><ion-icon name="flag-outline">
                    </ion-icon> <strong>Objetivo:</strong> 

                    @for($i = 0; $i < count($objetivos); $i++)

                        @if ($av->objetivo_id == $objetivos[$i]->id )
                                {{$objetivos[$i]->nomeObjetivo}}     
                        @endif
    
                    @endfor

                    @if (isset($av->outroObjetivo))
                            {{$av->outroObjetivo }} 
                    @endif

                </p>
                    <p class="av-owner" style="font-size: 20px"><ion-icon name="pricetag-outline"></ion-icon> <strong>Comentário:</strong> {{ $av->comentario }} </p>
                    <p class="av-owner" style="font-size: 20px"><ion-icon name="chevron-forward-circle-outline"></ion-icon> <strong>Status:</strong>  {{ $av->status }} </p>
                </div>
                <br>
                <h1 class="text-lg font-bold">Dados bancários:</h1>
                
                <div class="stats stats-vertical shadow">
  
                    <p class="av-owner" style="font-size: 20px"><ion-icon name="business-outline"></ion-icon> <strong>Banco:</strong> {{ $av->banco }} </p>
                    <p class="av-owner" style="font-size: 20px"><ion-icon name="home-outline"></ion-icon> <strong>Agência:</strong> {{ $av->agencia }} </p>
                    <p class="av-owner" style="font-size: 20px"><ion-icon name="wallet-outline"></ion-icon> <strong>Conta:</strong> {{ $av->conta }} </p>
                    <p class="av-owner" style="font-size: 20px"><ion-icon name="cash-outline"></ion-icon> <strong>Pix:</strong> {{ $av->pix }} </p>
                    
                </div>
                <br>

                <h1 class="text-lg font-bold">Adiantamentos:</h1>
                <div class="stats stats-vertical shadow">
                    <p class="av-owner" style="font-size: 20px; color: black; background-color: chartreuse"><ion-icon name="cash-outline"></ion-icon> <strong>Valor em reais:</strong> R$ {{ $av->valorReais }}</p>
                    <p class="av-owner" style="font-size: 20px; color: black; background-color: chartreuse"><ion-icon name="cash-outline"></ion-icon> <strong>Valor em dolar:</strong> $ {{ $av->valorDolar }}</p>
                    <p class="av-owner" style="font-size: 20px; color: black; background-color: coral"><ion-icon name="cash-outline"></ion-icon> <strong>Valor extra em reais:</strong> R$ {{ $av->valorExtraReais }}</p>
                    <p class="av-owner" style="font-size: 20px; color: black; background-color: coral"><ion-icon name="cash-outline"></ion-icon> <strong>Valor extra em dólar:</strong> $ {{ $av->valorExtraDolar }}</p>
                    <p class="av-owner" style="font-size: 20px; color: black; background-color: rgb(255, 58, 98)"><ion-icon name="cash-outline"></ion-icon> <strong>Valor dedução em reais:</strong> R$ {{ $av->valorDeducaoReais }}</p>
                    <p class="av-owner" style="font-size: 20px; color: black; background-color: rgb(255, 58, 98)"><ion-icon name="cash-outline"></ion-icon> <strong>Valor dedução em dólar:</strong> $ {{ $av->valorDeducaoDolar }}</p>
                    <p class="av-owner" style="font-size: 20px; color: black; background-color: deepskyblue"><ion-icon name="chevron-forward-circle-outline"></ion-icon> <strong>Justificativa valor extra:</strong> {{ $av->justificativaValorExtra }}</p>
                    @if($av->autorizacao != null)
                        <a href="{{ asset('AVs/' . $userAv->name . '/autorizacaoAv' . '/' . $av->autorizacao) }}" 
                            target="_blank" class="btn btn-active btn-success btn-sm">Documento de Autorização</a>
                    @endif
                </div>

            </div>
        </div>
    </div>

    <input type="checkbox" id="my-modal-5" class="modal-toggle" />

    <div class="modal">
        <div class="modal-box w-11/12 max-w-7xl">
            <div class="modal-content">
                <label for="my-modal-5" class="btn btn-sm btn-circle absolute right-2 top-2">✕</label>
                <br>
                <h3 class="text-lg font-bold" style="padding-left: 10%">Fases</h3>
                <br>
                <div style="padding-left: 10px">
                    <div class="badge badge-warning gap-2">PC = Prestação de Contas</div>
                    <div class="badge badge-error gap-2">Se carro particular</div>
                </div>
                <br>
                <div style="padding-left: 10px">

                    <ol class="items-center w-full space-y-4 sm:flex sm:space-x-8 sm:space-y-0">
                        <li class="flex items-center text-blue-600 dark:text-blue-500 space-x-2.5">
                            @if($av->isEnviadoUsuario == 1)
                                <span class="flex items-center justify-center w-8 h-8 border border-blue-600 rounded-full shrink-0 dark:bg-green-900">
                                    <svg aria-hidden="true" class="w-5 h-5 text-green-500 dark:text-green-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                                </span>
                            @else
                                <span class="flex items-center justify-center w-8 h-8 border border-gray-500 rounded-full shrink-0 dark:border-gray-400">
                                    1
                                </span>
                            @endif
                            <span>
                                <h3 class="font-medium leading-tight">Usuário</h3>
                                <p class="text-sm">Preenchimento da AV</p>
                            </span>
                        </li>
                        <li class="flex items-center text-gray-500 dark:text-gray-400 space-x-2.5">
                            @if($av->isAprovadoGestor == 1)
                                <span class="flex items-center justify-center w-8 h-8 border border-blue-600 rounded-full shrink-0 dark:bg-green-900">
                                    <svg aria-hidden="true" class="w-5 h-5 text-green-500 dark:text-green-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                                </span>
                            @else
                                <span class="flex items-center justify-center w-8 h-8 border border-gray-500 rounded-full shrink-0 dark:border-gray-400">
                                    2
                                </span>
                            @endif
                            <span>
                                <h3 class="font-medium leading-tight">Gestor:</h3>
                                <div class="badge badge-outline">Avaliação inicial</div>
                            </span>
                        </li>
                        <li class="flex items-center text-gray-500 dark:text-gray-400 space-x-2.5">
                            @if($av->isVistoDiretoria == 1)
                                <span class="flex items-center justify-center w-8 h-8 border border-blue-600 rounded-full shrink-0 dark:bg-green-900">
                                    <svg aria-hidden="true" class="w-5 h-5 text-green-500 dark:text-green-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                                </span>
                            @else
                                <span class="flex items-center justify-center w-8 h-8 border border-gray-500 rounded-full shrink-0 dark:border-gray-400">
                                    3
                                </span>
                            @endif
                            <span>
                                <h3 class="font-medium leading-tight">DAF:</h3>
                                <div class="badge badge-error gap-2">Avalia pedido</div>
                            </span>
                        </li>
                        <li class="flex items-center text-gray-500 dark:text-gray-400 space-x-2.5 border">
                            @if($av->isRealizadoReserva == 1)
                                <span class="flex items-center justify-center w-8 h-8 border border-blue-600 rounded-full shrink-0 dark:bg-green-900">
                                    <svg aria-hidden="true" class="w-5 h-5 text-green-500 dark:text-green-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                                </span>
                            @else
                                <span class="flex items-center justify-center w-8 h-8 border border-gray-500 rounded-full shrink-0 dark:border-gray-400">
                                    4
                                </span>
                            @endif
                            <span>
                                <h3 class="font-medium leading-tight">CAD - Coordenadoria Administrativa:</h3>
                                <div class="badge badge-outline">Realiza reservas</div>
                            </span>
                        </li>
                        <li class="flex items-center text-gray-500 dark:text-gray-400 space-x-2.5">
                            @if($av->isAprovadoFinanceiro == 1)
                                <span class="flex items-center justify-center w-8 h-8 border border-blue-600 rounded-full shrink-0 dark:bg-green-900">
                                    <svg aria-hidden="true" class="w-5 h-5 text-green-500 dark:text-green-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                                </span>
                            @else
                                <span class="flex items-center justify-center w-8 h-8 border border-gray-500 rounded-full shrink-0 dark:border-gray-400">
                                    4
                                </span>
                            @endif
                            <span>
                                <h3 class="font-medium leading-tight">CFI - Coordenadoria Financeira:</h3>
                                <div class="badge badge-outline">Adiantamento</div>
                            </span>
                        </li>
                        
                    </ol>
                </div>
                <div class="divider"></div> 

                <div style="padding-left: 10px">
    
                    <ol class="items-center w-full space-y-4 sm:flex sm:space-x-8 sm:space-y-0">
                        <li class="flex items-center text-blue-600 dark:text-blue-500 space-x-2.5">
                            <span class="flex items-center justify-center w-8 h-8 border border-blue-600 rounded-full shrink-0 dark:border-gray-400">
                                5
                            </span>
                            <span>
                                <h3 class="font-medium leading-tight">Viagem</h3>
                            </span>
                        </li>
                        <li class="flex items-center text-gray-500 dark:text-gray-400 space-x-2.5">
                            @if($av->isPrestacaoContasRealizada == 1)
                                <span class="flex items-center justify-center w-8 h-8 border border-blue-600 rounded-full shrink-0 dark:bg-green-900">
                                    <svg aria-hidden="true" class="w-5 h-5 text-green-500 dark:text-green-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                                </span>
                            @else
                                <span class="flex items-center justify-center w-8 h-8 border border-gray-500 rounded-full shrink-0 dark:border-gray-400">
                                    6
                                </span>
                            @endif
                            <span>
                                <h3 class="font-medium leading-tight">Usuário:</h3>
                                <div class="badge badge-warning gap-2">Realiza PC</div>
                            </span>
                        </li>
                        <li class="flex items-center text-gray-500 dark:text-gray-400 space-x-2.5">
                            @if($av->isFinanceiroAprovouPC == 1)
                                <span class="flex items-center justify-center w-8 h-8 border border-blue-600 rounded-full shrink-0 dark:bg-green-900">
                                    <svg aria-hidden="true" class="w-5 h-5 text-green-500 dark:text-green-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                                </span>
                            @else
                                <span class="flex items-center justify-center w-8 h-8 border border-gray-500 rounded-full shrink-0 dark:border-gray-400">
                                    7
                                </span>
                            @endif
                            <span>
                                <h3 class="font-medium leading-tight">Financeiro:</h3>
                                <div class="badge badge-warning gap-2">Avalia PC</div>
                            </span>
                        </li>
                        <li class="flex items-center text-gray-500 dark:text-gray-400 space-x-2.5">
                            @if($av->isGestorAprovouPC == 1)
                                <span class="flex items-center justify-center w-8 h-8 border border-blue-600 rounded-full shrink-0 dark:bg-green-900">
                                    <svg aria-hidden="true" class="w-5 h-5 text-green-500 dark:text-green-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                                </span>
                            @else
                                <span class="flex items-center justify-center w-8 h-8 border border-gray-500 rounded-full shrink-0 dark:border-gray-400">
                                    8
                                </span>
                            @endif
                            <span>
                                <h3 class="font-medium leading-tight">Gestor:</h3>
                                <div class="badge badge-warning gap-2">Avalia PC</div>
                            </span>
                        </li>
                        <li class="flex items-center text-gray-500 dark:text-gray-400 space-x-2.5">
                            @if($av->isAcertoContasRealizado == 1)
                                <span class="flex items-center justify-center w-8 h-8 border border-blue-600 rounded-full shrink-0 dark:bg-green-900">
                                    <svg aria-hidden="true" class="w-5 h-5 text-green-500 dark:text-green-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                                </span>
                            @else
                                <span class="flex items-center justify-center w-8 h-8 border border-gray-500 rounded-full shrink-0 dark:border-gray-400">
                                    9
                                </span>
                            @endif
                            <span>
                                <h3 class="font-medium leading-tight">Financeiro:</h3>
                                <div class="badge badge-info gap-2">Acerto de Contas</div>
                            </span>
                        </li>
                    </ol>
                </div>
                <br>
            </div>
        </div>
    </div>

    <input type="checkbox" id="my-modal-6" class="modal-toggle" />

    <div class="modal">
        <div class="modal-box w-11/12 max-w-1xl">
            <div class="modal-content">
                <label for="my-modal-6" class="btn btn-sm btn-circle absolute right-2 top-2">✕</label>
                <br>
                <h3 class="text-lg font-bold" style="padding-left: 10%">Selecione um veículo:</h3>
                <div class="form-group" style="padding-left: 20px" id="selecaoVeiculoParanacidade">
                    <label for="veiculoParanacidade_id" class="control-label" required>Selecione o veículo do Paranacidade?</label>
                    <br>
                        <select class="select select-bordered w-full max-w-xs" 
                            id="veiculoParanacidade_id" name="veiculoParanacidade_id">
                            <option value="-" name="-"> Selecione</option>
                            <option value="0" name="0"> Nenhum</option>
                            @for($i = 0; $i < count($veiculosParanacidade); $i++)
                                @if($veiculosParanacidade[$i]->codigoRegional == $userAv->department)
                                    <div>
                                        <option value="{{ $veiculosParanacidade[$i]->id }}" 
                                            name="{{ $veiculosParanacidade[$i]->id }}"> {{ $veiculosParanacidade[$i]->modelo }}. Placa: {{ $veiculosParanacidade[$i]->placa }} </option>
                                    </div>
                                @endif
                                
                                @if($veiculosParanacidade[$i]->codigoRegional == "CWB" 
                                && $userAv->department != "ERCSC" && $userAv->department != "ERMGA" && $userAv->department != "ERFCB"
                                && $userAv->department != "ERGUA" && $userAv->department != "ERLDA" && $userAv->department != "ERPTG")
                                    <div>
                                        <option value="{{ $veiculosParanacidade[$i]->id }}" 
                                            name="{{ $veiculosParanacidade[$i]->id }}"> {{ $veiculosParanacidade[$i]->modelo }}. Placa: {{ $veiculosParanacidade[$i]->placa }} </option>
                                    </div>
                                @endif
                            @endfor
                        </select>
                        <br><br>
                        <a class="btn btn-warning btn-sm" id="btn-submit-modal"
                        style="width: 200px">  Vincular veículo</a>
                </div>
            </div>
        </div>
    </div>

    <input type="checkbox" id="my-modal-14" class="modal-toggle" />

    <div class="modal">
        <div class="modal-box w-11/12 max-w-7xl">
            <div class="modal-content">
                <label for="my-modal-14" class="btn btn-sm btn-circle absolute right-0 top-0">✕</label>
                <br>
                
                <h1 style="font-size: 24px; padding-bottom: 20px"><strong>Medições vinculadas:</strong></h1>
                        <table id="minhaTabela8" class="display nowrap" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Nome do município</th>
                                    <th>Número do projeto</th>
                                    <th>Número do lote</th>
                                    <th>Número da medição</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($medicoesFiltradas as $med)
                                    <tr>
                                        <td> {{$med->nome_municipio}} </td>
                                        <td> {{$med->numero_projeto}} </td>
                                        <td> {{$med->numero_lote}} </td>
                                        <td> {{$med->numero_medicao}} </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
            </div>
        </div>
    </div>
    
@endsection

{{-- Para implementação futura de AJAX --}} 
@section('javascript')
    <script type="text/javascript">

        $(function() {
                const modal = document.querySelector('.modal');
                const avLabels = document.querySelectorAll('.btn[data-rota]');
                
                avLabels.forEach(function(avLabel) {
                    avLabel.addEventListener('click', function() {
                    const rota = this.getAttribute('data-rota');
                    
                        $('#btn-submit-modal').click(function() {
                            
                            var veiculo = document.getElementById("veiculoParanacidade_id").value;
                            if(veiculo != '-'){
                                window.location.href = '/avs/escolherVeiculo/' + rota + '/' + veiculo;
                            }
                            else{
                                alert("Escolha um veículo!");
                            }
                        });
                    });
                });
        });

        $(document).ready(function(){
            $('#minhaTabela').DataTable({
                    scrollY: 500,
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

        $(document).ready(function(){
            $('#tabelaRota').DataTable({
                    scrollY: 300,
                    "language": {
                        "lengthMenu": "Mostrando _MENU_ registros por página",
                        "zeroRecords": "Nada encontrado",
                        "info": "Mostrando página _PAGE_ de _PAGES_",
                        "infoEmpty": "Nenhum registro disponível",
                        "infoFiltered": "(filtrado de _MAX_ registros no total)",
                        "search": "Procure uma AV"
                    }
            });
            $('#minhaTabela8').DataTable({
                    scrollY: 200,
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

    </script>
@endsection