@extends('layouts.main')

@section('title', 'Editando: ' . $av->id)
@section('content')

<div class="row justify-content-start" style="padding-left: 5%">
    <div class="col-1"></div>
    <div class="col-2">
        <a href="/avs/autPcFinanceiro" type="submit" class="btn btn-active btn-ghost"> Voltar!</a>
    </div>
    <div class="col-6"></div>
    <div class="col-1">
        <div class="dropdown dropdown-bottom dropdown-end">
            <label tabindex="0" class="btn m-1">Ajuda</label>
            <ul tabindex="0" class="dropdown-content card card-compact w-80 p-2 shadow text-primary-content">
                <div class="chat chat-end">
                    <div class="chat-image avatar">
                        <div class="w-10 rounded-full">
                          <img src="{{asset('/img/user.png')}}" />
                        </div>
                      </div>
                      <div class="chat-bubble chat-bubble-success">
                        Olá {{$user->name}}!
                      </div>
                </div>
                <div class="chat chat-end">
                    <div class="chat-image avatar">
                        <div class="w-10 rounded-full">
                          <img src="{{asset('/img/user.png')}}" />
                        </div>
                      </div>
                      <div class="chat-bubble chat-bubble-success">
                        Aqui nesta etapa você deve avaliar a Prestação de Contas do usuário!
                      </div>
                </div>
                <div class="chat chat-end">
                    <div class="chat-image avatar">
                        <div class="w-10 rounded-full">
                          <img src="{{asset('/img/user.png')}}" />
                        </div>
                      </div>
                      <div class="chat-bubble chat-bubble-success">
                        Para isso analise se os comprovantes emitidos são válidos e se ocorreu alguma edição na AV, assim como em suas rotas.
                      </div>
                </div>
                <div class="chat chat-end">
                    <div class="chat-image avatar">
                        <div class="w-10 rounded-full">
                          <img src="{{asset('/img/user.png')}}" />
                        </div>
                      </div>
                      <div class="chat-bubble chat-bubble-success">
                        Na opção "Ver documento AV" é possível verificar a AV em seu estado inicial.
                      </div>
                </div>

            </ul>
          </div>
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
                    {{ $u->email }}
                @endif
        @endforeach
        </p>  
        
            <div >
                <label for="my-modal-3" class="btn btn-sm" style="padding-bottom: 30px"><ion-icon name="layers-outline" size="large"></ion-icon>Histórico</label>
                <label for="my-modal-4" class="btn btn-sm" style="padding-bottom: 30px"><ion-icon name="cube-outline" size="large"></ion-icon>Dados atuais da AV</label>
                <label for="my-modal-5" class="btn btn-sm" style="padding-bottom: 30px"><ion-icon name="analytics-outline" size="large"></ion-icon>FLUXO</label>
                
                <br><br>

                <label for="my-modal-6" class="btn btn-sm" style="padding-bottom: 30px"><ion-icon name="bed-outline" size="large"></ion-icon>Reservas de hotel</label>
                <label for="my-modal-7" class="btn btn-sm" style="padding-bottom: 30px"><ion-icon name="car-outline" size="large"></ion-icon>Reservas de transporte</label>
                <label for="my-modal-8" class="btn btn-sm" style="padding-bottom: 30px"><ion-icon name="cash-outline" size="large"></ion-icon>Adiantamentos</label>
                <label for="my-modal-9" class="btn btn-sm btn-warning"> Ver Documento AV</label>

                
                <br><br>
                
            </div>
            <div class="divider"></div> 
        

        <div class="col-md-12 offset-md-0">
            <h1 style="font-size: 24px"><strong>Trajeto: </strong></h1>
            <table id="tabelaRota" class="display nowrap" style="width:100%">
                <thead>
                    <tr>
                        <th>Número</th>
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
                    @foreach($av->rotas as $rota)
                    <tr>
                        <td> {{$rota->id}} </td>
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
                        <td> {{ date('d/m/Y H:m', strtotime($rota->dataHoraSaida)) }} </td>
        
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
        
                        <td> {{ date('d/m/Y H:m', strtotime($rota->dataHoraChegada)) }} </td>
                        <td> {{ $rota->isReservaHotel == 1 ? "Sim" : "Não"}}</td>
                        <td> 
                            {{ $rota->isOnibusLeito == 1 ? "Onibus leito" : ""}}
                            {{ $rota->isOnibusConvencional == 1 ? "Onibus convencional" : ""}}
                            {{ $rota->isVeiculoProprio == 1 ? "Veículo próprio" : ""}}
                            {{ $rota->isVeiculoEmpresa == 1 ? "Veículo empresa" : ""}}
                            {{ $rota->isAereo == 1 ? "Aéreo" : ""}}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            
        </div>

        <div class="divider"></div> 

        <div class="col-md-6 offset-md-0">
            <h1 style="font-size: 24px"><strong>Comprovante de despesa: </strong></h1>
            <table id="minhaTabela5" class="display nowrap" style="width:100%">
                <thead>
                    <tr>
                        <th>Descrição</th>
                        <th>Valor em reais</th>
                        <th>Valor em dólar</th>
                        <th>Anexo</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($comprovantes as $comp)
                        <tr>
                            <td> {{$comp->descricao}} </td>
                            <td> R${{$comp->valorReais}},00 </td>
                            <td> ${{$comp->valorDolar}},00 </td>
                        
                            <td> <a href="{{ asset('AVs/' . $userAv->name . '/' . $av->id . '/comprovantesDespesa' . '/' . $comp->anexoDespesa) }}" 
                                target="_blank" class="btn btn-active btn-success btn-sm">Abrir documento</a> </td>
                            
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="divider"></div> 

        <div class="flex flex-row">
            <form action="/avs/financeiroAprovaPrestacaoContas" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                    <input type="text" hidden="true" id="id" name="id" value="{{ $av->id }}">
                    <label for="comentario">Enviar AV para aprovação do Gestor: </label>
                    <br>
                    <textarea type="text" class="textarea textarea-bordered h-24" 
                        name="comentario" style="width: 200px"
                        id="comentario" placeholder="Comentário"></textarea>

                    <button type="submit" class="btn btn-active btn-success">Aprovar AV</button>
            </form>

            <form action="/avs/financeiroReprovaPrestacaoContas" method="POST" enctype="multipart/form-data" style="padding-left: 10px">
                @csrf
                @method('PUT')
                    <input type="text" hidden="true" id="id" name="id" value="{{ $av->id }}">
                    <label for="comentario">Voltar AV para a prestação de contas do usuário: </label>
                    <br>
                    <textarea type="text" class="textarea textarea-bordered h-24" 
                        name="comentario" style="width: 200px"
                        id="comentario" placeholder="Comentário"></textarea>
                    <button type="submit" class="btn btn-active btn-error">Reprovar AV</button>
            </form>
            
        </div>

    </div>

    

    <input type="checkbox" id="my-modal-3" class="modal-toggle" />

    <div class="modal">
        <div class="modal-box w-11/12 max-w-7xl">
            <div class="modal-content">
                <label for="my-modal-3" class="btn btn-sm btn-circle absolute right-2 top-2">✕</label>
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
                        <th>Autor</th>
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
                                {{ $u->email }}
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
                    <p class="av-owner" style="font-size: 20px"><ion-icon name="alert-circle-outline"></ion-icon> <strong>Prioridade:</strong> {{ $av->prioridade }} </p>
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
                    <p class="av-owner" style="font-size: 20px"><ion-icon name="cash-outline"></ion-icon> <strong>Valor em reais:</strong> R$ {{ $av->valorReais }},00</p>
                    <p class="av-owner" style="font-size: 20px"><ion-icon name="cash-outline"></ion-icon> <strong>Valor em dolar:</strong> R$ {{ $av->valorDolar }},00</p>
                    <p class="av-owner" style="font-size: 20px"><ion-icon name="cash-outline"></ion-icon> <strong>Valor extra em reais:</strong> R$ {{ $av->valorExtraReais }},00</p>
                    <p class="av-owner" style="font-size: 20px"><ion-icon name="cash-outline"></ion-icon> <strong>Valor extra em dólar:</strong> R$ {{ $av->valorExtraDolar }},00</p>
                    <p class="av-owner" style="font-size: 20px"><ion-icon name="chevron-forward-circle-outline"></ion-icon> <strong>Justificativa valor extra:</strong> {{ $av->justificativaValorExtra }}</p>
                    
                    
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
                    <div class="badge badge-error gap-2">Se carro particular ou viagem internacional</div>
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
                                    -
                                </span>
                            @endif
                            <span>
                                <h3 class="font-medium leading-tight">Diretoria Executiva:</h3>
                                <div class="badge badge-error gap-2">Avalia pedido</div>
                            </span>
                        </li>
                        <li class="flex items-center text-gray-500 dark:text-gray-400 space-x-2.5 border-2 border-black">
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
                                <h3 class="font-medium leading-tight">Secretaria:</h3>
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
                                    5
                                </span>
                            @endif
                            <span>
                                <h3 class="font-medium leading-tight">Financeiro:</h3>
                                <div class="badge badge-outline">Adiantamento</div>
                            </span>
                        </li>
                        <li class="flex items-center text-gray-500 dark:text-gray-400 space-x-2.5">
                            @if($av->isReservadoVeiculoParanacidade == 1)
                                <span class="flex items-center justify-center w-8 h-8 border border-blue-600 rounded-full shrink-0 dark:bg-green-900">
                                    <svg aria-hidden="true" class="w-5 h-5 text-green-500 dark:text-green-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                                </span>
                            @else
                                <span class="flex items-center justify-center w-8 h-8 border border-gray-500 rounded-full shrink-0 dark:border-gray-400">
                                    6
                                </span>
                            @endif
                            <span>
                                <h3 class="font-medium leading-tight">Administração:</h3>
                                <div class="badge badge-error gap-2">Reserva de veículo</div>
                            </span>
                        </li>
                    </ol>
                </div>
                <div class="divider"></div> 

                <div style="padding-left: 10px">
    
                    <ol class="items-center w-full space-y-4 sm:flex sm:space-x-8 sm:space-y-0">
                        <li class="flex items-center text-blue-600 dark:text-blue-500 space-x-2.5">
                            <span class="flex items-center justify-center w-8 h-8 border border-blue-600 rounded-full shrink-0 dark:border-gray-400">
                                7
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
                                    8
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
                                    9
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
                                    10
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
                                    11
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
        <div class="modal-box w-11/12 max-w-7xl">
            <div class="modal-content">
                <label for="my-modal-6" class="btn btn-sm btn-circle absolute right-2 top-2">✕</label>
                <br>
                <h3 class="text-lg font-bold" style="padding-left: 10%">Reserva de hotel</h3>
                <table id="minhaTabela1" class="display nowrap" style="width:100%">
                    <thead>
                        <tr>
                            <th>Descrição</th>
                            <th>IdRota</th>
                            <th>Rota</th>
                            <th>Anexo</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($anexosRotas as $anexoHotel)
                        @if($anexoHotel->anexoHotel !=null)
                            <tr>
                                <td> {{$anexoHotel->descricao}} </td>
                                
                                <td>
                                    @for($i = 0; $i < count($av->rotas); $i++)

                                        @if($anexoHotel->rota_id == $av->rotas[$i]->id)
                                            {{$av->rotas[$i]->id}}
                                        @endif
                                    @endfor
                                </td>    
                                <td>
                                    @for($i = 0; $i < count($av->rotas); $i++)

                                        @if($anexoHotel->rota_id == $av->rotas[$i]->id)
                                            @if($av->rotas[$i]->isViagemInternacional == 0)
                                            - {{$av->rotas[$i]->cidadeOrigemNacional}}
                                            -> {{$av->rotas[$i]->cidadeDestinoNacional}}
                                            @endif
                                            
                                            @if($av->rotas[$i]->isViagemInternacional == 1)
                                            - {{$av->rotas[$i]->cidadeOrigemInternacional}} 
                                            -> {{$av->rotas[$i]->cidadeDestinoInternacional}} 
                                            @endif
                                        @endif
                                    @endfor
                                </td>
                                <td> <a href="{{ asset('AVs/' . $userAv->name . '/' . $av->id . '/' . $anexoHotel->anexoHotel) }}" 
                                    target="_blank" class="btn btn-active btn-success btn-sm">Abrir documento</a> </td>
                            </tr>
                        @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <input type="checkbox" id="my-modal-7" class="modal-toggle" />

    <div class="modal">
        <div class="modal-box w-11/12 max-w-7xl">
            <div class="modal-content">
                <label for="my-modal-7" class="btn btn-sm btn-circle absolute right-2 top-2">✕</label>
                <br>
                <h3 class="text-lg font-bold" style="padding-left: 10%">Reservas de transporte</h3>
                
                <table id="minhaTabela2" class="display nowrap" style="width:100%">
                    <thead>
                        <tr>
                            <th>Descrição</th>
                            <th>IdRota</th>
                            <th>Rota</th>
                            <th>Anexo</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($anexosRotas as $anexoTransporte)
                            @if($anexoTransporte->anexoTransporte !=null)
                                <tr>
                                    <td> {{$anexoTransporte->descricao}} </td>
                                    
                                    <td>
                                        @for($i = 0; $i < count($av->rotas); $i++)

                                            @if($anexoTransporte->rota_id == $av->rotas[$i]->id)
                                                {{$av->rotas[$i]->id}}
                                            @endif
                                        @endfor
                                    </td>    
                                    <td>
                                        @for($i = 0; $i < count($av->rotas); $i++)

                                            @if($anexoTransporte->rota_id == $av->rotas[$i]->id)
                                                @if($av->rotas[$i]->isViagemInternacional == 0)
                                                - {{$av->rotas[$i]->cidadeOrigemNacional}}
                                                -> {{$av->rotas[$i]->cidadeDestinoNacional}}
                                                @endif
                                                
                                                @if($av->rotas[$i]->isViagemInternacional == 1)
                                                - {{$av->rotas[$i]->cidadeOrigemInternacional}} 
                                                -> {{$av->rotas[$i]->cidadeDestinoInternacional}} 
                                                @endif
                                            @endif
                                        @endfor
                                    </td>
                                    <td> <a href="{{ asset('AVs/' . $userAv->name . '/' . $av->id . '/' . $anexoTransporte->anexoTransporte) }}" 
                                        target="_blank" class="btn btn-active btn-success btn-sm">Abrir documento</a> </td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>

            </div>
        </div>
    </div>

    <input type="checkbox" id="my-modal-8" class="modal-toggle" />

    <div class="modal">
        <div class="modal-box w-11/12 max-w-7xl">
            <div class="modal-content">
                <label for="my-modal-8" class="btn btn-sm btn-circle absolute right-2 top-2">✕</label>
                <br>
                <h3 class="text-lg font-bold" style="padding-left: 10%">Adiantamentos realizados</h3>

                <table id="minhaTabela3" class="display nowrap" style="width:100%">
                    <thead>
                        <tr>
                            <th>Descrição</th>
                            <th>Anexo</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($anexosFinanceiro as $anexoFinanceiro)
                            <tr>
                                <td> {{$anexoFinanceiro->descricao}} </td>
                            
                                <td> <a href="{{ asset('AVs/' . $userAv->name . '/' . $av->id . '/adiantamentos' . '/' . $anexoFinanceiro->anexoFinanceiro) }}" 
                                    target="_blank" class="btn btn-active btn-success btn-sm">Abrir documento</a> </td>
                                
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <input type="checkbox" id="my-modal-9" class="modal-toggle" />

    <div class="modal">
        <div class="modal-box w-11/12 max-w-7xl">
            <div class="modal-content">
                <label for="my-modal-9" class="btn btn-sm btn-circle absolute right-2 top-2">✕</label>
                <br>
                <h1 style="font-size: 24px"><strong>Documento AV gerado: </strong></h1>
                <table id="minhaTabela4" class="display nowrap" style="width:100%">
                    <thead>
                        <tr>
                            <th>Descrição</th>
                            <th>Anexo</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($historicoPc as $hist)
                            <tr>
                                <td> {{$hist->comentario}} </td>
                                <td> <a href="{{ asset('AVs/' . $userAv->name . '/' . $av->id . '/resumo' . '/' . $hist->anexoRelatorio) }}" 
                                    target="_blank" class="btn btn-active btn-success btn-sm">Abrir documento</a> </td>
                                
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

        $(document).ready(function(){
            $('#minhaTabela').DataTable({
                    scrollY: 500,
                    "language": {
                        "lengthMenu": "Mostrando _MENU_ registros por página",
                        "zeroRecords": "Nada encontrado",
                        "info": "Mostrando página _PAGE_ de _PAGES_",
                        "infoEmpty": "Nenhum registro disponível",
                        "infoFiltered": "(filtrado de _MAX_ registros no total)"
                    }
                });
        });

        $(document).ready(function(){
            $('#tabelaRota').DataTable({
                    scrollY: 200,
                    "language": {
                        "lengthMenu": "Mostrando _MENU_ registros por página",
                        "zeroRecords": "Nada encontrado",
                        "info": "Mostrando página _PAGE_ de _PAGES_",
                        "infoEmpty": "Nenhum registro disponível",
                        "infoFiltered": "(filtrado de _MAX_ registros no total)",
                        "search": "Procure uma AV"
                    }
            });

            $('#minhaTabela1').DataTable({
                    scrollY: 300,
                    "language": {
                        "lengthMenu": "Mostrando _MENU_ registros por página",
                        "zeroRecords": "Nada encontrado",
                        "info": "Mostrando página _PAGE_ de _PAGES_",
                        "infoEmpty": "Nenhum registro disponível",
                        "infoFiltered": "(filtrado de _MAX_ registros no total)"
                    }
            });

            $('#minhaTabela2').DataTable({
                    scrollY: 300,
                    "language": {
                        "lengthMenu": "Mostrando _MENU_ registros por página",
                        "zeroRecords": "Nada encontrado",
                        "info": "Mostrando página _PAGE_ de _PAGES_",
                        "infoEmpty": "Nenhum registro disponível",
                        "infoFiltered": "(filtrado de _MAX_ registros no total)"
                    }
            });

            $('#minhaTabela3').DataTable({
                    scrollY: 300,
                    "language": {
                        "lengthMenu": "Mostrando _MENU_ registros por página",
                        "zeroRecords": "Nada encontrado",
                        "info": "Mostrando página _PAGE_ de _PAGES_",
                        "infoEmpty": "Nenhum registro disponível",
                        "infoFiltered": "(filtrado de _MAX_ registros no total)"
                    }
            });

            $('#minhaTabela4').DataTable({
                    scrollY: 200,
                    "language": {
                        "lengthMenu": "Mostrando _MENU_ registros por página",
                        "zeroRecords": "Nada encontrado",
                        "info": "Mostrando página _PAGE_ de _PAGES_",
                        "infoEmpty": "Nenhum registro disponível",
                        "infoFiltered": "(filtrado de _MAX_ registros no total)"
                    }
            });

            $('#minhaTabela5').DataTable({
                    scrollY: 200,
                    "language": {
                        "lengthMenu": "Mostrando _MENU_ registros por página",
                        "zeroRecords": "Nada encontrado",
                        "info": "Mostrando página _PAGE_ de _PAGES_",
                        "infoEmpty": "Nenhum registro disponível",
                        "infoFiltered": "(filtrado de _MAX_ registros no total)"
                    }
            });
        });

        $(function(){
            

            const input = document.getElementById('arquivo1');
            const botaoEnviar = document.getElementById('botaoEnviarArquivo1');

            input.addEventListener('change', (event) => {
                if (event.target.value !== '') {
                botaoEnviar.removeAttribute('disabled');
                }
            });

        })

    </script>
@endsection