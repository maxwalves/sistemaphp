@extends('layouts.main')

@section('title', 'Editando: ' . $av->id)
@section('content')

<div class="row justify-content-start" style="padding-left: 5%">
    <div class="col-3">
        <a href="/avs/avs/" type="submit" class="btn btn-active btn-ghost"> Voltar!</a>
    </div>
</div>
<div id="av-create-container" class="col-md-10 offset-md-1">
        <h1 style="font-size: 24px"><strong>Autorização de viagem nº:</strong> {{ $av->id }}</h1>
        <h1 style="font-size: 24px"><strong>Status atual:</strong> {{ $av->status }}</h1>
        <h1 style="font-size: 24px"><strong>Trajeto: </strong></h1>
        <label for="my-modal-3" class="btn">Histórico</label>
        <label for="my-modal-4" class="btn">Dados da AV</label>


        <div class="col-md-6 offset-md-3">
            <table class="table w-full">
              <!-- head -->
              <thead>
                <tr>
                  <th>Rota</th>
                  <th>Cidade Origem</th>
                  <th>Cidade Destino</th>
                </tr>
              </thead>
              <tbody>
                <!-- row 1 -->

                @for($i = 0; $i < count($av->rotas); $i++)

                        <tr>
                            <th>Rota: {{$i+1}}</th>
                            <td>
                                @if($av->rotas[$i]->isViagemInternacional == 0)
                                    <strong>{{$av->rotas[$i]->cidadeOrigemNacional}}</strong> 
                                @endif
                                
                                @if($av->rotas[$i]->isViagemInternacional == 1)
                                    <strong>{{$av->rotas[$i]->cidadeOrigemInternacional}}</strong> 
                                @endif
                            </td>
                            <td>
                                @if($av->rotas[$i]->isViagemInternacional == 0)
                                    <strong>{{$av->rotas[$i]->cidadeDestinoNacional}}</strong> 
                                @endif
                                
                                @if($av->rotas[$i]->isViagemInternacional == 1)
                                    <strong>{{$av->rotas[$i]->cidadeDestinoInternacional}}</strong>  
                                @endif
                            </td>
                        </tr>

                @endfor

              </tbody>
            </table>
            
          </div>

        <br>
        <div>
            <div class="badge badge-warning gap-2">PC = Prestação de Contas</div>
            <div class="badge badge-error gap-2">Apenas se envolver carro particular ou viagem internacional</div>
        </div>
        
        <div class="divider"></div> 
        
        <div>

            <ol class="items-center w-full space-y-4 sm:flex sm:space-x-8 sm:space-y-0">
                <li class="flex items-center text-blue-600 dark:text-blue-500 space-x-2.5">
                    <span class="flex items-center justify-center w-8 h-8 border border-blue-600 rounded-full shrink-0 dark:bg-green-900">
                        <svg aria-hidden="true" class="w-5 h-5 text-green-500 dark:text-green-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                    </span>
                    <span>
                        <h3 class="font-medium leading-tight">Usuário</h3>
                        <p class="text-sm">Preenchimento da AV</p>
                    </span>
                </li>
                <li class="flex items-center text-gray-500 dark:text-gray-400 space-x-2.5">
                    <span class="flex items-center justify-center w-8 h-8 border border-gray-500 rounded-full shrink-0 dark:border-gray-400">
                        2
                    </span>
                    <span>
                        <h3 class="font-medium leading-tight">Gestor:</h3>
                        <div class="badge badge-outline">Avaliação inicial</div>
                    </span>
                </li>
                <li class="flex items-center text-gray-500 dark:text-gray-400 space-x-2.5">
                    <span class="flex items-center justify-center w-8 h-8 border border-gray-500 rounded-full shrink-0 dark:border-gray-400">
                        3
                    </span>
                    <span>
                        <h3 class="font-medium leading-tight">Diretoria Executiva:</h3>
                        <div class="badge badge-error gap-2">Avalia pedido</div>
                    </span>
                </li>
                <li class="flex items-center text-gray-500 dark:text-gray-400 space-x-2.5">
                    <span class="flex items-center justify-center w-8 h-8 border border-gray-500 rounded-full shrink-0 dark:border-gray-400">
                        4
                    </span>
                    <span>
                        <h3 class="font-medium leading-tight">Secretaria:</h3>
                        <div class="badge badge-outline">Realiza reservas</div>
                    </span>
                </li>
                <li class="flex items-center text-gray-500 dark:text-gray-400 space-x-2.5">
                    <span class="flex items-center justify-center w-8 h-8 border border-gray-500 rounded-full shrink-0 dark:border-gray-400">
                        5
                    </span>
                    <span>
                        <h3 class="font-medium leading-tight">Financeiro:</h3>
                        <div class="badge badge-outline">Adiantamento</div>
                    </span>
                </li>
                <li class="flex items-center text-gray-500 dark:text-gray-400 space-x-2.5">
                    <span class="flex items-center justify-center w-8 h-8 border border-gray-500 rounded-full shrink-0 dark:border-gray-400">
                        6
                    </span>
                    <span>
                        <h3 class="font-medium leading-tight">Administração:</h3>
                        <div class="badge badge-error gap-2">Reserva de veículo</div>
                    </span>
                </li>
            </ol>
        </div>
        <div class="divider"></div> 
        <div>


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
                    <span class="flex items-center justify-center w-8 h-8 border border-gray-500 rounded-full shrink-0 dark:border-gray-400">
                        8
                    </span>
                    <span>
                        <h3 class="font-medium leading-tight">Usuário:</h3>
                        <div class="badge badge-warning gap-2">Realiza PC</div>
                    </span>
                </li>
                <li class="flex items-center text-gray-500 dark:text-gray-400 space-x-2.5">
                    <span class="flex items-center justify-center w-8 h-8 border border-gray-500 rounded-full shrink-0 dark:border-gray-400">
                        9
                    </span>
                    <span>
                        <h3 class="font-medium leading-tight">Financeiro:</h3>
                        <div class="badge badge-warning gap-2">Avalia PC</div>
                    </span>
                </li>
                <li class="flex items-center text-gray-500 dark:text-gray-400 space-x-2.5">
                    <span class="flex items-center justify-center w-8 h-8 border border-gray-500 rounded-full shrink-0 dark:border-gray-400">
                        10
                    </span>
                    <span>
                        <h3 class="font-medium leading-tight">Gestor:</h3>
                        <div class="badge badge-warning gap-2">Avalia PC</div>
                    </span>
                </li>
                <li class="flex items-center text-gray-500 dark:text-gray-400 space-x-2.5">
                    <span class="flex items-center justify-center w-8 h-8 border border-gray-500 rounded-full shrink-0 dark:border-gray-400">
                        11
                    </span>
                    <span>
                        <h3 class="font-medium leading-tight">Financeiro:</h3>
                        <div class="badge badge-info gap-2">Acerto de Contas</div>
                    </span>
                </li>
            </ol>
        </div>
        <div class="divider"></div> 
        

    </div>

    <input type="checkbox" id="my-modal-3" class="modal-toggle" />

    <div class="modal">
        <div class="modal-box w-11/12 max-w-5xl">
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
                                    @if ($u->id == $historico->usuario_id)
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
        <div class="modal-box w-11/12 max-w-5xl">
            <div class="modal-content">
                <label for="my-modal-4" class="btn btn-sm btn-circle absolute right-2 top-2">✕</label>
                <br>
                <h3 class="text-lg font-bold" style="padding-left: 10%">Histórico</h3>

                <p class="av-data"><ion-icon name="calendar-outline"></ion-icon> Data de criação: {{ date('d/m/Y', strtotime($av->dataCriacao)) }} </p>
                <p class="av-owner"><ion-icon name="flag-outline"></ion-icon> Objetivo: {{ isset($objetivo->nomeObjetivo) ? $objetivo->nomeObjetivo : $av->outroObjetivo }}</p>
                <p class="av-owner"><ion-icon name="alert-circle-outline"></ion-icon> Prioridade: {{ $av->prioridade }}</p>
                <p class="av-owner"><ion-icon name="business-outline"></ion-icon> Banco: {{ $av->banco }}</p>
                <p class="av-owner"><ion-icon name="home-outline"></ion-icon> Agência: {{ $av->agencia }}</p>
                <p class="av-owner"><ion-icon name="wallet-outline"></ion-icon> Conta: {{ $av->conta }}</p>
                <p class="av-owner"><ion-icon name="cash-outline"></ion-icon> Pix: {{ $av->pix }}</p>
                <p class="av-owner"><ion-icon name="pricetag-outline"></ion-icon> Comentário: {{ $av->comentario }}</p>
                <p class="av-owner"><ion-icon name="chevron-forward-circle-outline"></ion-icon> Status: {{ $av->status }}</p>

                <p class="av-owner"><ion-icon name="chevron-forward-circle-outline"></ion-icon> Valor em reais: {{ $av->valorReais }}</p>
                <p class="av-owner"><ion-icon name="chevron-forward-circle-outline"></ion-icon> Valor em dolar: {{ $av->valorDolar }}</p>
                <p class="av-owner"><ion-icon name="chevron-forward-circle-outline"></ion-icon> Valor extra em reais: {{ $av->valorExtraReais }}</p>
                <p class="av-owner"><ion-icon name="chevron-forward-circle-outline"></ion-icon> Valor extra em dólar: {{ $av->valorExtraDolar }}</p>
                <p class="av-owner"><ion-icon name="chevron-forward-circle-outline"></ion-icon> Justificativa valor extra: {{ $av->justificativaValorExtra }}</p>
                <br>
                <br>


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

    </script>
@endsection