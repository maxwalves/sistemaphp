@extends('layouts.main')

@section('title', 'Editando: ' . $av->id)
@section('content')

<nav class="bg-base-200">
    <div class="flex flex-wrap items-center justify-between mx-auto p-1">
        <a href="#" class="flex items-center">
            <img src="{{asset('/img/visualizar.png')}}" class="h-12 mr-3" />
            <span class="self-center text-2xl font-semibold whitespace-nowrap dark:text-black">Detalhes da AV</span>
        </a>
      <button data-collapse-toggle="navbar-dropdown" type="button" class="inline-flex items-center p-2 ml-3 text-sm text-gray-500 rounded-lg md:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600" aria-controls="navbar-dropdown" aria-expanded="false">
        <span class="sr-only">Open main menu</span>
        <svg class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"></path></svg>
      </button>
      <div class="hidden w-full md:block md:w-auto" id="navbar-dropdown">
        
        <ul class="flex flex-col font-medium p-4 md:p-0 rounded-lg bg-gray-50 md:flex-row md:space-x-8 md:mt-0 md:border-0 md:bg-white dark:bg-gray-800 md:dark:bg-gray-200 dark:border-gray-700">
          <li>
            <a href="/avs/avs" type="submit" style="padding-bottom: 40px" class="btn btn bg-slate-600"><ion-icon name="chevron-back-outline" size="large"></ion-icon> Voltar!</a>
          </li>
          <li>
            <label for="my-modal-3" class="btn btn-sm" style="padding-bottom: 40px"><ion-icon name="layers-outline" size="large"></ion-icon>Histórico</label>
          </li>
          <li>
              <button id="dropdownNavbarLink" style="padding-bottom: 40px" data-dropdown-toggle="dropdownNavbar" class="btn btn-sm"><ion-icon name="layers-outline" size="large"></ion-icon>AV <svg class="w-5 h-5 ml-1" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg></button>
              <!-- Dropdown menu -->
              <div id="dropdownNavbar" class="z-10 hidden font-normal bg-dark divide-y divide-gray-100 rounded-lg shadow w-44 dark:bg-gray-700 dark:divide-gray-600">
                  <ul class="py-2 text-sm text-gray-700 dark:text-gray-400" aria-labelledby="dropdownLargeButton">
                    <li>
                        <label for="my-modal-4" class="btn btn-sm btn-ghost block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white" >Dados atuais</label>
                    </li>
                    @if($isInternacional != true)
                        <li>
                            <label for="my-modal-5" class="btn btn-sm btn-ghost block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white" >FLUXO</label>
                        </li>
                    @endif
                    <li>
                        <label for="my-modal-10" class="btn btn-sm btn-ghost block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white" >Trajeto</label>
                    </li>
                    <li>
                        <label for="my-modal-13" class="btn btn-sm btn-ghost block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white" >Relatório</label>
                    </li>
                    @if($av->objetivo_id == 3)
                        <li>
                            <label for="my-modal-14" class="btn btn-sm btn-ghost block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white" >Medições</label>
                        </li>
                    @endif
                  </ul>
              </div>
          </li>
          
          <li>
            <button id="dropdownNavbarLink2" style="padding-bottom: 40px" data-dropdown-toggle="dropdownNavbar2" class="btn btn-sm"><ion-icon name="layers-outline" size="large"></ion-icon>Reservas <svg class="w-5 h-5 ml-1" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg></button>
            <!-- Dropdown menu -->
            <div id="dropdownNavbar2" class="z-10 hidden font-normal bg-dark divide-y divide-gray-100 rounded-lg shadow w-44 dark:bg-gray-700 dark:divide-gray-600">
                <ul class="py-2 text-sm text-gray-700 dark:text-gray-400" aria-labelledby="dropdownLargeButton">
                  <li>
                      <label for="my-modal-6" class="btn btn-sm btn-ghost block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white" >Hotel</label>
                  </li>
                  <li>
                      <label for="my-modal-7" class="btn btn-sm btn-ghost block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white" >Transporte</label>
                  </li>
                </ul>
            </div>
        </li>

        <li>
            <button id="dropdownNavbarLink3" style="padding-bottom: 40px" data-dropdown-toggle="dropdownNavbar3" class="btn btn-sm"><ion-icon name="layers-outline" size="large"></ion-icon>Financeiro <svg class="w-5 h-5 ml-1" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg></button>
            <!-- Dropdown menu -->
            <div id="dropdownNavbar3" class="z-10 hidden font-normal bg-dark divide-y divide-gray-100 rounded-lg shadow w-44 dark:bg-gray-700 dark:divide-gray-600">
                <ul class="py-2 text-sm text-gray-700 dark:text-gray-400" aria-labelledby="dropdownLargeButton">
                    <li>
                        <label for="my-modal-8" class="btn btn-sm btn-ghost block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white" >Adiantamentos</label>
                    </li>
                    <li>
                        <label for="my-modal-12" class="btn btn-sm btn-ghost block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white" >Despesas</label>
                    </li>
                </ul>
            </div>
        </li>
        </ul>
      </div>
    </div>
  </nav>

    <div class="container">

        <div class="containerAcertoContas">
            <div class="box box-90">
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
            </div>
        </div>
    </div>

    <div class="divider"></div> 

    <div>
            <div class="containerAcertoContas">
                @if($av->isPrestacaoContasRealizada==1)
                <div class="box box-40">
                    <div class="col-md-12 offset-md-0">
                        <h1 style="font-size: 24px"><strong>Acerto de contas: </strong></h1>
                        <br>
                        <p><strong> <span style="color: red">A:</span> Recebido antes da viagem</strong></p>
                        <div class="stats shadow">
              
                            <div class="stat">
                              <div class="stat-title">Valor em Reais</div>
                              <div class="stat-value text-primary">R$ {{$valorRecebido->valorReais}}</div>
                            </div>
                            <div class="stat">
                                <div class="stat-title">Valor extra em Reais</div>
                                <div class="stat-value text-primary">R$ {{$valorRecebido->valorExtraReais != null ? $valorRecebido->valorExtraReais : 0}}</div>
                              </div>
                            @if($av->valorDeducaoReais > 0)
                                <div class="stat">
                                    <div class="stat-title">Dedução em Reais</div>
                                    <div class="stat-value text-primary">- R$ {{$av->valorDeducaoReais}}</div>
                                </div>
                            @endif
                            @if($av->valorDeducaoDolar > 0)
                            <div class="stat">
                                <div class="stat-title">Dedução em dólar</div>
                                <div class="stat-value text-primary">$ {{$av->valorDeducaoDolar}}</div>
                            </div>
                            @endif
                            @if($valorRecebido->valorDolar > 0)
                                <div class="stat">
                                <div class="stat-title">Valor em dólar</div>
                                <div class="stat-value text-primary">$ {{$valorRecebido->valorDolar}}</div>
                                </div>
                            @endif
                            @if($valorRecebido->valorExtraDolar > 0)
                                <div class="stat">
                                    <div class="stat-title">Valor extra em dólar</div>
                                    <div class="stat-value text-primary">$ {{$valorRecebido->valorExtraDolar}}</div>
                                </div>
                            @endif
                        </div>
                        <div class="stats shadow bg-yellow-500">
                            <div class="stat">
                                <div class="stat-title">Resultado em Reais</div>
                                <div class="stat-value text-primary">R$ {{$valorRecebido->valorReais + $valorRecebido->valorExtraReais - $av->valorDeducaoReais}}</div>
                            </div>
                            @if(($valorRecebido->valorDolar + $valorRecebido->valorExtraDolar - $av->valorDeducaoDolar) > 0)
                                <div class="stat">
                                    <div class="stat-title">Resultado em Dólar</div>
                                    <div class="stat-value text-primary">R$ {{$valorRecebido->valorDolar + $valorRecebido->valorExtraDolar - $av->valorDeducaoDolar}}</div>
                                </div>
                            @endif
                        </div>
                        <br><br>
                        <p><strong> <span style="color: green">B:</span> Informado na prestação de contas</strong></p>
                        <div class="stats shadow">
              
                            <div class="stat">
                                <div class="stat-title">Valor em Reais</div>
                                <div class="stat-value text-primary">R$ {{$av->valorReais}} {{ $av->isAprovadoCarroDiretoriaExecutiva == true ? '+ R$ ' . $av->qtdKmVeiculoProprio * 0.49 : ""}} </div>
                            </div>
                            <div class="stat">
                                  <div class="stat-title">Valor extra em Reais</div>
                                  <div class="stat-value text-primary">R$ {{$valorAcertoContasReal}}</div>
                            </div>
                            @if($av->valorDeducaoReais > 0)
                                <div class="stat">
                                    <div class="stat-title">Dedução em Reais</div>
                                    <div class="stat-value text-primary">- R$ {{$av->valorDeducaoReais}}</div>
                                </div>
                            @endif
                            @if($av->valorDeducaoDolar > 0)
                                <div class="stat">
                                    <div class="stat-title">Dedução em dólar</div>
                                    <div class="stat-value text-primary">$ {{$av->valorDeducaoDolar}}</div>
                                </div>
                            @endif
                            @if($av->valorDolar > 0)
                                <div class="stat">
                                    <div class="stat-title">Valor em dólar</div>
                                    <div class="stat-value text-primary">$ {{$av->valorDolar}}</div>
                                </div>
                            @endif
                            @if($valorAcertoContasDolar > 0)
                                <div class="stat">
                                    <div class="stat-title">Valor extra em dólar</div>
                                    <div class="stat-value text-primary">$ {{$valorAcertoContasDolar}}</div>
                                </div>
                            @endif
                        </div>
                        <div class="stats shadow bg-yellow-500">
                            <div class="stat">
                                <div class="stat-title">Resultado em Reais</div>
                                <div class="stat-value text-primary">R$ {{$av->valorReais + $av->valorExtraReais - $av->valorDeducaoReais}}</div>
                            </div>
                            @if(($av->valorDolar + $av->valorExtraDolar - $av->valorDeducaoDolar) > 0)
                                <div class="stat">
                                    <div class="stat-title">Resultado em Dólar</div>
                                    <div class="stat-value text-primary">R$ {{$av->valorDolar + $av->valorExtraDolar - $av->valorDeducaoDolar}}</div>
                                </div>
                            @endif
                        </div>
                        <br><br>
                        <p><strong> <span style="color: red">A</span> - <span style="color: green">B</span>: Acerto de contas:</strong></p>
                        <div class="stats shadow">
                            @if($av->isAprovadoCarroDiretoriaExecutiva == true)
                                <div class="stat">
                                    <div class="stat-title" style="color: black">Valor em Reais</div>
                                    <div class="stat-value text-gray-950">R$ {{($valorRecebido->valorReais-$av->valorReais) - ($av->qtdKmVeiculoProprio * 0.49)}}</div>
                                </div>
                            @else
                                <div class="stat">
                                    <div class="stat-title" style="color: black">Valor em Reais</div>
                                    <div class="stat-value text-gray-950">R$ {{$valorRecebido->valorReais-$av->valorReais}}</div>
                                </div>
                            @endif
                            
                            <div class="stat">
                                  <div class="stat-title" style="color: black">Valor extra em Reais</div>
                                  <div class="stat-value text-gray-950">R$ {{$valorRecebido->valorExtraReais-$valorAcertoContasReal}}</div>
                            </div>
                              
                            @if(($valorRecebido->valorDolar-$av->valorDolar)>0)
                                <div class="stat">
                                    <div class="stat-title" style="color: black">Valor em dólar</div>
                                    <div class="stat-value text-gray-950">$ {{$valorRecebido->valorDolar-$av->valorDolar}}</div>
                                </div>
                            @endif
                            @if(($valorRecebido->valorExtraDolar-$valorAcertoContasDolar)>0)
                                <div class="stat">
                                    <div class="stat-title" style="color: black">Valor extra em dólar</div>
                                    <div class="stat-value text-gray-950">$ {{$valorRecebido->valorExtraDolar-$valorAcertoContasDolar}}</div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                @endif
                <div class="box box-40">
                    <div >
                        <h1 style="font-size: 24px"><strong>Comprovantes:</strong></h1>
                        <table id="minhaTabela6" class="display nowrap" style="width:100%">
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
                                            @if($hist->comentario == "AV Internacional gerada")
                                                <td> <a href="{{ asset('AVs/' . $userAv->name . '/' . $av->id . '/internacional' . '/' . $hist->anexoRelatorio) }}" 
                                                    target="_blank" class="btn btn-active btn-success btn-sm">Abrir documento</a> </td>
                                            @else
                                                <td> <a href="{{ asset('AVs/' . $userAv->name . '/' . $av->id . '/resumo' . '/' . $hist->anexoRelatorio) }}" 
                                                target="_blank" class="btn btn-active btn-success btn-sm">Abrir documento</a> </td>
                                            @endif
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @if($av->isPrestacaoContasRealizada==1)
                            <p><strong>Resultado:</strong></p>
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
                                            @endif
                                        </p>
                                    </div>
    
                                    @if($av->isAprovadoCarroDiretoriaExecutiva == true)
                                        @if( ( ($valorRecebido->valorReais-$av->valorReais)+ ($valorRecebido->valorExtraReais-$valorAcertoContasReal) - ($av->qtdKmVeiculoProprio * 0.49) <0))
                                            <div class="stat-value text-green-500">
                                                    R$ {{(($valorRecebido->valorReais-$av->valorReais) + ($valorRecebido->valorExtraReais-$valorAcertoContasReal) - (($av->qtdKmVeiculoProprio * 0.49))) * (-1) }}
                                            </div>
                                        @endif
                                        @if( ( ($valorRecebido->valorReais-$av->valorReais)+ ($valorRecebido->valorExtraReais-$valorAcertoContasReal) - ($av->qtdKmVeiculoProprio * 0.49) >0))
                                            <div class="stat-value text-error">
                                                    R$ {{(($valorRecebido->valorReais-$av->valorReais) + ($valorRecebido->valorExtraReais-$valorAcertoContasReal) - (($av->qtdKmVeiculoProprio * 0.49))) * (-1) }}
                                            </div>
                                        @endif
                                    @else
                                        @if((($valorRecebido->valorReais-$av->valorReais) + ($valorRecebido->valorExtraReais-$valorAcertoContasReal))<0)
                                            <div class="stat-value text-green-500">
                                                    R$ {{(($valorRecebido->valorReais-$av->valorReais) + ($valorRecebido->valorExtraReais-$valorAcertoContasReal)) * (-1)}}
                                            </div>
                                        @endif
                                        @if((($valorRecebido->valorReais-$av->valorReais) + ($valorRecebido->valorExtraReais-$valorAcertoContasReal))>0)
                                            <div class="stat-value text-error">
                                                    R$ {{(($valorRecebido->valorReais-$av->valorReais) + ($valorRecebido->valorExtraReais-$valorAcertoContasReal))}}
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
                        @endif
                    </div>
                </div>
            </div>

        <div class="divider"></div> 

    </div>

    <div class="dropdown dropdown-top dropdown-end">
        <label tabindex="0" class="btn" style="position: fixed; bottom: 20px; right: 20px;"><ion-icon name="cash-outline" size="large"></ion-icon>Ajuda</label>
        <ul tabindex="0" class="dropdown-content card card-compact w-80 p-2 shadow text-primary-content" style="position: fixed; bottom: 70px; right: 20px;">
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
                    Aqui nesta etapa você pode verificar o status da sua AV!
                  </div>
            </div>
            <div class="chat chat-end">
                <div class="chat-image avatar">
                    <div class="w-10 rounded-full">
                      <img src="{{asset('/img/user.png')}}" />
                    </div>
                  </div>
                  <div class="chat-bubble chat-bubble-success">
                    No menu é possível ver informações importantes, como o histórico e o fluxo da AV.
                  </div>
            </div>
            <div class="chat chat-end">
                <div class="chat-image avatar">
                    <div class="w-10 rounded-full">
                      <img src="{{asset('/img/user.png')}}" />
                    </div>
                  </div>
                  <div class="chat-bubble chat-bubble-success">
                    No menu também é possível verificar as reservas e o financeiro.
                  </div>
            </div>
    
        </ul>
    </div>

    <input type="checkbox" id="my-modal-3" class="modal-toggle" />

    <div class="modal">
        <div class="modal-box w-11/12 max-w-7xl">
            <div class="modal-content">
                <label for="my-modal-3" class="btn btn-sm btn-circle absolute right-0 top-0">✕</label>
                <br>
                <h3 class="text-lg font-bold" style="padding-left: 10%; padding-bottom: 20px">Histórico</h3>
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
                        <li class="flex items-center text-gray-500 dark:text-gray-400 space-x-2.5">
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
        <div class="modal-box w-11/12 max-w-7xl">
            <div class="modal-content">
                <label for="my-modal-6" class="btn btn-sm btn-circle absolute right-0 top-0">✕</label>
                <br>
                <h3 class="text-lg font-bold" style="padding-left: 10%; padding-bottom: 20px">Reserva de hotel</h3>
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
                <label for="my-modal-7" class="btn btn-sm btn-circle absolute right-0 top-0">✕</label>
                <br>
                <h3 class="text-lg font-bold" style="padding-left: 10%; padding-bottom: 20px">Reservas de transporte</h3>
                
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
                <label for="my-modal-8" class="btn btn-sm btn-circle absolute right-0 top-0">✕</label>
                <br>
                <h3 class="text-lg font-bold" style="padding-left: 10%; padding-bottom: 20px">Adiantamentos realizados</h3>

                <table id="minhaTabela3" class="display nowrap" style="width:100%;">
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

    <input type="checkbox" id="my-modal-10" class="modal-toggle" />

    <div class="modal">
        <div class="modal-box w-11/12 max-w-7xl">
            <div class="modal-content">
                <label for="my-modal-10" class="btn btn-sm btn-circle absolute right-0 top-0">✕</label>
                <br>
                <h1 style="font-size: 24px; padding-bottom: 20px"><strong>Trajeto: </strong></h1>
                
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
                            
                        </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>
        </div>
    </div>

    <input type="checkbox" id="my-modal-12" class="modal-toggle" />

    <div class="modal">
        <div class="modal-box w-11/12 max-w-7xl">
            <div class="modal-content">
                <label for="my-modal-12" class="btn btn-sm btn-circle absolute right-0 top-0">✕</label>
                <br>
                
                <h1 style="font-size: 24px; padding-bottom: 20px"><strong>Comprovantes de despesa:</strong></h1>
                        <table id="minhaTabela7" class="display nowrap" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Descrição</th>
                                    <th>Valor reais</th>
                                    <th>Valor dólar</th>
                                    <th>Anexo</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($comprovantes as $comp)
                                    <tr>
                                        <td> {{$comp->descricao}} </td>
                                        <td> {{$comp->valorReais}} </td>
                                        <td> {{$comp->valorDolar}} </td>
                                        <td> <a href="{{ asset('AVs/' . $userAv->name . '/' . $av->id . '/comprovantesDespesa' . '/' . $comp->anexoDespesa) }}" 
                                            target="_blank" class="btn btn-active btn-success btn-sm">Abrir documento</a> </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

            </div>
        </div>
    </div>

    <input type="checkbox" id="my-modal-13" class="modal-toggle" />

    <div class="modal">
        <div class="modal-box w-11/12 max-w-1xl">
            <div class="modal-content">
                <label for="my-modal-13" class="btn btn-sm btn-circle absolute right-2 top-2">✕</label>
                <br>
                
                <h1 style="font-size: 24px"><strong>Relatório:</strong></h1>

                    <div class="form-group">
                        <label for="contatos" class="control-label">Contatos:</label><br>
                        <textarea type="textarea" class="textarea textarea-secondary textarea-lg" name="contatos"
                        id="contatos" placeholder="Contatos" style="width: 400px; height: 100px" disabled>{{$av->contatos}}</textarea>
                    </div>
                    <div class="form-group">
                        <label for="atividades" class="control-label">Atividades:</label><br>
                        <textarea type="text" class="textarea textarea-secondary textarea-lg" name="atividades"
                        id="atividades" placeholder="Atividades" style="width: 400px; height: 100px" disabled>{{$av->atividades}}</textarea>
                    </div>
                    <div class="form-group">
                        <label for="conclusoes" class="control-label">Conclusões:</label><br>
                        <textarea type="text" class="textarea textarea-secondary textarea-lg" name="conclusoes"
                        id="conclusoes" placeholder="Conclusões" style="width: 400px; height: 100px" disabled>{{$av->conclusoes}}</textarea>
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
                        "infoFiltered": "(filtrado de _MAX_ registros no total)",
                        "search": "Pesquisar"
                    }
            });

            $('#minhaTabela2').DataTable({
                    scrollY: 300,
                    "language": {
                        "lengthMenu": "Mostrando _MENU_ registros por página",
                        "zeroRecords": "Nada encontrado",
                        "info": "Mostrando página _PAGE_ de _PAGES_",
                        "infoEmpty": "Nenhum registro disponível",
                        "infoFiltered": "(filtrado de _MAX_ registros no total)",
                        "search": "Pesquisar"
                    }
            });

            $('#minhaTabela3').DataTable({
                    scrollY: 300,
                    "language": {
                        "lengthMenu": "Mostrando _MENU_ registros por página",
                        "zeroRecords": "Nada encontrado",
                        "info": "Mostrando página _PAGE_ de _PAGES_",
                        "infoEmpty": "Nenhum registro disponível",
                        "infoFiltered": "(filtrado de _MAX_ registros no total)",
                        "search": "Pesquisar"
                    }
            });

            $('#minhaTabela4').DataTable({
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

            $('#minhaTabela5').DataTable({
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
            $('#minhaTabela6').DataTable({
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
            $('#minhaTabela7').DataTable({
                    scrollY: 100,
                    "language": {
                        "lengthMenu": "Mostrando _MENU_ registros por página",
                        "zeroRecords": "Nada encontrado",
                        "info": "Mostrando página _PAGE_ de _PAGES_",
                        "infoEmpty": "Nenhum registro disponível",
                        "infoFiltered": "(filtrado de _MAX_ registros no total)",
                        "search": "Pesquisar"
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

        $(function(){
            

            const input = document.getElementById('arquivo1');
            const botaoEnviar = document.getElementById('botaoEnviarArquivo1');

            input.addEventListener('change', (event) => {
                if (event.target.value !== '') {
                botaoEnviar.removeAttribute('disabled');
                }
            });

        });

    </script>
@endsection