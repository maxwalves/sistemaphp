@extends('adminlte::page')

@section('title', 'Realizar Reservas')

@section('content_header')
    
@stop

@section('content')

<div class="row justify-content-start" style="padding-left: 5%">
    <div class="col-md-9">
        <br>
        <h2>Realizar Reservas</h2>
    </div>
    <div class="col-md-3">
        <br>
        <a href="/avs/verFluxoSecretaria/{{ $av->id }}" type="submit" class="btn btn-active btn-warning"><i class="fas fa-arrow-left"></i></a>
    </div>
</div>
<div>

    <h1 style="font-size: 24px"><strong>Rota nº:</strong> {{ $rota->id }}</h1>
    <p class="av-owner" style="font-size: 24px"><ion-icon name="chevron-forward-circle-outline">
    </ion-icon> <strong >Nome do usuário: </strong> 
    @foreach($users as $u)
            @if ($u->id == $av->user_id)
                {{ $u->name }}
            @endif
    @endforeach
    </p>        
    <p class="av-owner" style="font-size: 24px"><ion-icon name="chevron-forward-circle-outline">
        </ion-icon> <strong>Objetivo: </strong>
        @for ($i = 0; $i < count($objetivos); $i++)
            @if ($av->objetivo_id == $objetivos[$i]->id)
                {{ $objetivos[$i]->nomeObjetivo }}
            @endif
        @endfor

        @if (isset($av->outroObjetivo))
            {{ $av->outroObjetivo }}
        @endif
    </p>
    <div class="divider"></div> 


        <div class="row">
            <div class="flex flex-row">
                <input type="text" style="display: none" id="paisOrigem" disabled value="{{ $rota->isViagemInternacional ? $rota->paisOrigemInternacional : "Brasil" }}">
            </div>
            <div class="flex flex-row">
                <input type="text" style="display: none" id="paisDestino" disabled value="{{ $rota->isViagemInternacional ? $rota->paisDestinoInternacional : "Brasil" }}">
            </div>
            <div class="col-md-12">
                {{-- mostre dados da próxima rota --}}
                <table id="tabelaRota" class="table table-hover table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th>Cidade de saída</th>
                            <th>Data/Hora de saída</th>
                            <th>Cidade de chegada</th>
                            <th>Data/Hora de chegada</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($rotasDaAv as $r)
                        <tr style="{{($rota->id == $r->id ? 'background-color:yellow' : "")}}">
                            <td> 
                                @if($r->isAereo == 1)
                                    <img src="{{asset('/img/aviaosubindo.png')}}" style="width: 40px" >
                                @endif
            
                                @if($r->isVeiculoProprio == 1 || $rota->isVeiculoEmpresa == 1)
                                    <img src="{{asset('/img/carro.png')}}" style="width: 40px" >
                                @endif
            
                                @if($r->isOnibusLeito == 1 || $rota->isOnibusConvencional == 1)
                                    <img src="{{asset('/img/onibus.png')}}" style="width: 40px" >
                                @endif

                                @if($r->isOutroMeioTransporte == 1)
                                    <img src="{{asset('/img/outros.png')}}" style="width: 40px" >
                                @endif
            
                                {{$r->isViagemInternacional == 0 ? $r->cidadeOrigemNacional : $r->cidadeOrigemInternacional}} 
                                
                            </td>
                            <td> {{ date('d/m/Y H:i', strtotime($r->dataHoraSaida)) }} </td>
            
                            <td> 
                                @if($r->isAereo == 1)
                                    <img src="{{asset('/img/aviaodescendo.png')}}" style="width: 40px" >
                                @endif
            
                                @if($r->isVeiculoProprio == 1 || $r->isVeiculoEmpresa == 1)
                                    <img src="{{asset('/img/carro.png')}}" style="width: 40px" >
                                @endif
            
                                @if($r->isOnibusLeito == 1 || $r->isOnibusConvencional == 1)
                                    <img src="{{asset('/img/onibus.png')}}" style="width: 40px" >
                                @endif

                                @if($r->isOutroMeioTransporte == 1)
                                    <img src="{{asset('/img/outros.png')}}" style="width: 40px" >
                                @endif
            
                                {{$r->isViagemInternacional == 0 ? $r->cidadeDestinoNacional : $r->cidadeDestinoInternacional}} 
                            </td>
            
                            <td> {{ date('d/m/Y H:i', strtotime($r->dataHoraChegada)) }} </td>
                            
                            <td>
                                @php
                                    $correspondenciaHotelEncontrada = false;
                                    $correspondenciaOnibusEncontrada = false;
                                    $correspondenciaAereoEncontrada = false;
                                @endphp
                               
                                @if($r->isReservaHotel ==1)
                                    @foreach($anexosRotas as $anexo)
                                        @if($anexo->rota_id == $r->id && $anexo->anexoHotel != null)
                                            @php
                                                $correspondenciaHotelEncontrada = true;
                                            @endphp
                                            <span class="badge bg-success badge-large"><i class="far fa-building"></i></span>
                                            @break
                                        @endif
                                    @endforeach
                                    @if(!$correspondenciaHotelEncontrada)
                                        <span class="badge bg-warning badge-large"><i class="far fa-building"></i></span>
                                    @endif
                                @else
                                    <span class="badge bg-danger badge-large"><i class="far fa-building"></i></span>
                                @endif

                                @if($r->isOnibusLeito == 1 || $r->isOnibusConvencional == 1)
                                    @foreach($anexosRotas as $anexo)
                                        @if($anexo->rota_id == $r->id && $anexo->anexoTransporte != null)
                                            @php
                                                $correspondenciaOnibusEncontrada = true;
                                            @endphp
                                            <span class="badge bg-success badge-large"><i class="fas fa-bus"></i></span>
                                            @break
                                        @endif
                                    @endforeach
                                    @if(!$correspondenciaOnibusEncontrada)
                                        <span class="badge bg-warning badge-large"><i class="fas fa-bus"></i></span>
                                    @endif
                                @else
                                    <span class="badge bg-danger badge-large"><i class="fas fa-bus"></i></span>
                                @endif

                                @if($r->isAereo == 1)
                                    @foreach($anexosRotas as $anexo)
                                        @if($anexo->rota_id == $r->id && $anexo->anexoTransporte != null)
                                            @php
                                                $correspondenciaAereoEncontrada = true;
                                            @endphp
                                            <span class="badge bg-success badge-large"><i class="fas fa-plane"></i></span>
                                            @break
                                        @endif
                                    @endforeach
                                    @if(!$correspondenciaAereoEncontrada)
                                        <span class="badge bg-warning badge-large"><i class="fas fa-plane"></i></span>
                                    @endif
                                @else
                                    <span class="badge bg-danger badge-large"><i class="fas fa-plane"></i></span>
                                @endif
                                
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <p>Legenda do status: <span class="badge bg-success">Reservado</span> <span class="badge bg-warning">Pendente</span> <span class="badge bg-danger">Não tem</span></p>
            </div>

        </div>
        <hr>
        <br>

        @if($rota->isReservaHotel == true)
            <div class="col-3">
                <x-adminlte-button label="+ Reserva Hotel" data-toggle="modal" data-target="#my-modal-3" class="bg-teal"/>
            </div>

            <div class="col-md-6 offset-md-0">
                <h1 style="font-size: 24px"><strong>Reserva de hotel: </strong></h1>
                <table id="minhaTabela1" class="display nowrap" style="width:100%">
                    <thead>
                        <tr>
                            <th>Descrição</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($anexos as $anexo)
                        @if($anexo->anexoHotel !=null)
                            <tr>
                                <td> {{$anexo->descricao}} </td>
                            
                                <td>
                                    <a href="{{ route('recuperaArquivo', [
                                        'name' => $userAv->name,
                                        'id' => $av->id,
                                        'pasta' => 'null',
                                        'anexoRelatorio' => $anexo->anexoHotel,
                                        ]) }}"
                                        target="_blank" class="btn btn-active btn-success btn-sm d-inline"><i class="far fa-eye"></i></a>

                                    <form action="/avs/deletarAnexoHotel/{{ $anexo->id }}/{{ $rota->id }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-active btn-danger btn-sm"><i class="far fa-trash-alt"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif

        @if($rota->isOnibusLeito == 1 || $rota->isOnibusConvencional == 1 || $rota->isAereo ==1)
            <div class="col-3">
                <x-adminlte-button label="Adicionar Reserva de Transporte" data-toggle="modal" data-target="#my-modal-4" class="bg-teal"/>
            </div>
            <div class="col-md-6 offset-md-0">
                <h1 style="font-size: 24px"><strong>Reserva de transporte: </strong></h1>
                <table id="minhaTabela2" class="display nowrap" style="width:100%">
                    <thead>
                        <tr>
                            <th>Número</th>
                            <th>Anexo</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($anexos as $anexo)
                        @if($anexo->anexoTransporte !=null)
                            <tr>
                                <td> {{$anexo->descricao}} </td>
                                
                                
                                <td>
                    
                                    <a href="{{ route('recuperaArquivo', [
                                        'name' => $userAv->name,
                                        'id' => $av->id,
                                        'pasta' => 'null',
                                        'anexoRelatorio' => $anexo->anexoTransporte,
                                        ]) }}"
                                        target="_blank" class="btn btn-active btn-success btn-sm">Abrir documento</a>
                                
                                </td>
                                
                                <td>   
                                    <form action="/avs/deletarAnexoTransporte/{{ $anexo->id }}/{{ $rota->id }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-active btn-danger btn-sm"
                                        style="width: 110px" > Deletar</button>
                                    </form>
                                </td>   
                            </tr>
                        @endif
                        @endforeach
                    </tbody>
                </table>
                
            </div>
        @endif

        <div class="divider"></div> 

</div>

<x-adminlte-modal id="my-modal-3" title="Reserva de Hotel" size="lg" theme="teal"
    icon="fas fa-bell" v-centered static-backdrop scrollable>
    
    <form action="/avs/gravarReservaHotel" method="POST" enctype="multipart/form-data">
        @csrf
            <input type="file" id="arquivo1" name="arquivo1" class="form-control-file">
            <input type="text" hidden="true" id="rotaId" name="rotaId" value="{{ $rota->id }}">
            <br><br>
            <label for="descricao">Descrição</label>
            <input type="text" id="descricao" name="descricao" class="input input-bordered input-secondary w-full max-w-xs">
            <br><br>
            <button type="submit" id="botaoEnviarArquivo1" class="btn btn-active btn-success" disabled>Gravar arquivo</button>
    </form>


    <x-slot name="footerSlot">
        <x-adminlte-button theme="danger" label="Fechar" data-dismiss="modal"/>
    </x-slot>
</x-adminlte-modal>

<x-adminlte-modal id="my-modal-4" title="Reserva de Transporte" size="lg" theme="teal"
    icon="fas fa-bell" v-centered static-backdrop scrollable>
    
    <form action="/avs/gravarReservaTransporte" method="POST" enctype="multipart/form-data">
        @csrf
            <input type="file" id="arquivo2" name="arquivo2" class="form-control-file">
            <input type="text" hidden="true" id="rotaId" name="rotaId" value="{{ $rota->id }}">
            <br><br>
            <label for="descricao">Descrição</label>
            <input type="text" id="descricao" name="descricao" class="input input-bordered input-secondary w-full max-w-xs">
            <br><br>
            <button type="submit" id="botaoEnviarArquivo2" class="btn btn-active btn-success" disabled>Gravar arquivo</button>
    </form>

    <x-slot name="footerSlot">
        <x-adminlte-button theme="danger" label="Fechar" data-dismiss="modal"/>
    </x-slot>
</x-adminlte-modal>

@stop

@section('css')
    <link href="{{ asset('DataTables/datatables.min.css') }}" rel="stylesheet" />
    <style>
        .badge-large {
            font-size: 1rem; /* Ajuste o tamanho conforme necessário */
            padding: 0.5rem 1rem; /* Ajuste o preenchimento conforme necessário */
        }
    </style>
@stop

@section('js')
    
    <script src="{{ asset('DataTables/datatables.min.js') }}"></script>
    <script src="{{ asset('/js/moment.js') }}"></script>
    <script type="text/javascript">

        $(document).ready(function(){
            $('#minhaTabela1').DataTable({
                    scrollY: 200,
                    "language": {
                        "search": "Procurar:",
                        "lengthMenu": "Mostrando _MENU_ registros por página",
                        "zeroRecords": "Nada encontrado",
                        "info": "Mostrando página _PAGE_ de _PAGES_",
                        "infoEmpty": "Nenhum registro disponível",
                        "infoFiltered": "(filtrado de _MAX_ registros no total)"
                    }
                });
        });

        $(document).ready(function(){
            $('#minhaTabela2').DataTable({
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
        });

        function carregarPaisOrigem(){
            
            var id = document.getElementById("paisOrigem").value;
            $("#paisOrigem").html('');

            if(id != "Brasil"){
                $.getJSON('/country/' + id, function(data){
                    document.getElementById("paisOrigem").value = data[0].name;
                    console.log(data[0].name);
                });
            }
            
                
        }

        function carregarPaisDestino(){

            var id = document.getElementById("paisDestino").value;
            $("#paisDestino").html('');

            if(id != "Brasil"){
                $.getJSON('/country/' + id, function(data){
                    document.getElementById("paisDestino").value = data[0].name;
                    console.log(data[0].name);
                });
            }
        }

                //Assim que a tela carrega, aciona automaticamente essas duas funções ------------------------
        $(function(){
            
            //carregarPaises();
            carregarPaisOrigem();
            carregarPaisDestino();

            const input = document.getElementById('arquivo1');
            const botaoEnviar = document.getElementById('botaoEnviarArquivo1');

            input.addEventListener('change', (event) => {
                if (event.target.value !== '') {
                botaoEnviar.removeAttribute('disabled');
                }
            });

            const input2 = document.getElementById('arquivo2');
            const botaoEnviar2 = document.getElementById('botaoEnviarArquivo2');

            input2.addEventListener('change', (event) => {
                if (event.target.value !== '') {
                botaoEnviar2.removeAttribute('disabled');
                }
            });
        })
    </script>

@stop

