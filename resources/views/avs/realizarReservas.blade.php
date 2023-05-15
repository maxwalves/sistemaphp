@extends('layouts.main')

@section('title', 'Editando: ' . $av->id)
@section('content')

<div class="row justify-content-start" style="padding-left: 5%">
    <div class="col-3">
        <a href="/avs/verFluxoSecretaria/{{ $av->id }}" type="submit" class="btn btn-active btn-ghost"> Voltar!</a>
    </div>
</div>
<div id="container_reserva" class="container">

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
    </ion-icon> <strong>E-mail do usuário: </strong> 
    @foreach($users as $u)
            @if ($u->id == $av->user_id)
                {{ $u->email }}
            @endif
    @endforeach
    </p>  
    <div class="divider"></div> 


        <div class="flex flex-row">
            <div>
                <div class="flex flex-row">
                    <label for="paisOrigem" style="font-size: 24px" ><strong>País de origem:</strong></label>
                    <input type="text" style="font-size: 24px; padding-left: 5px" id="paisOrigem" disabled value="{{ $rota->isViagemInternacional ? $rota->paisOrigemInternacional : "Brasil" }}">
                </div>
                <h1 style="font-size: 24px"><strong>Estado de origem: </strong> {{ $rota->isViagemInternacional ? $rota->estadoOrigemInternacional : $rota->estadoOrigemNacional }}</h1>
                <h1 style="font-size: 24px"><strong>Cidade de origem: </strong> {{ $rota->isViagemInternacional ? $rota->cidadeOrigemInternacional : $rota->cidadeOrigemNacional }}</h1>
            </div>

            <div>
                <div class="flex flex-row">
                    <label for="paisDestino" style="font-size: 24px"><strong>País de destino: </strong> </label>
                    <input type="text" style="font-size: 24px; padding-left: 5px" id="paisDestino" disabled value="{{ $rota->isViagemInternacional ? $rota->paisDestinoInternacional : "Brasil" }}">
                </div>
                <h1 style="font-size: 24px"><strong>Estado de destino: </strong> {{ $rota->isViagemInternacional ? $rota->estadoDestinoInternacional : $rota->estadoDestinoNacional }}</h1>
                <h1 style="font-size: 24px"><strong>Cidade de destino: </strong> {{ $rota->isViagemInternacional ? $rota->cidadeDestinoInternacional : $rota->cidadeDestinoNacional }}</h1>
            </div>
        </div>
        <div class="divider"></div> 

        <div class="col-3">
            <strong>Adicionar Reserva de Hotel</strong> 
            <label for="my-modal-3" class="btn btn-active btn-success"><ion-icon name="add-circle-outline" size="large"></ion-icon></label>
        </div>
        <div class="col-md-6 offset-md-0">
            <h1 style="font-size: 24px"><strong>Reserva de hotel: </strong></h1>
            <table id="minhaTabela1" class="display nowrap" style="width:100%">
                <thead>
                    <tr>
                        <th>Descrição</th>
                        <th>Anexo</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($anexos as $anexo)
                    @if($anexo->anexoHotel !=null)
                        <tr>
                            <td> {{$anexo->descricao}} </td>
                        
                            <td> <a href="{{ asset('AVs/' . $userAv->name . '/' . $av->id . '/' . $anexo->anexoHotel) }}" 
                                target="_blank" class="btn btn-active btn-success btn-sm">Abrir documento</a> </td>
                            
                            <td>
                                <form action="/avs/deletarAnexoHotel/{{ $anexo->id }}/{{ $rota->id }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-active btn-accent btn-sm"
                                    style="width: 110px" > Deletar</button>
                                </form>
                            </td>
                        </tr>
                    @endif
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="col-3">
            <strong>Adicionar Reserva de Transporte</strong>
            <label for="my-modal-4" class="btn btn-active btn-success"><ion-icon name="add-circle-outline" size="large"></ion-icon></label>
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
                            
                            
                            <td> <a href="{{ asset('AVs/' . $userAv->name . '/' . $av->id . '/' . $anexo->anexoTransporte) }}" 
                                    target="_blank" class="btn btn-active btn-success btn-sm">Abrir documento</a> </td>
                            
                            <td>   
                                <form action="/avs/deletarAnexoTransporte/{{ $anexo->id }}/{{ $rota->id }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-active btn-accent btn-sm"
                                    style="width: 110px" > Deletar</button>
                                </form>
                            </td>   
                        </tr>
                    @endif
                    @endforeach
                </tbody>
            </table>
            
        </div>

        <div class="divider"></div> 

</div>

<input type="checkbox" id="my-modal-3" class="modal-toggle" />

<div class="modal">
    <div class="modal-box w-11/12 max-w-1xl">
        <div class="modal-content">
            <label for="my-modal-3" class="btn btn-sm btn-circle absolute right-2 top-2">✕</label>
            <br>
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
        </div>
    </div>
</div>

<input type="checkbox" id="my-modal-4" class="modal-toggle" />

<div class="modal">
    <div class="modal-box w-11/12 max-w-1xl">
        <div class="modal-content">
            <label for="my-modal-4" class="btn btn-sm btn-circle absolute right-2 top-2">✕</label>
            <br>
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
        </div>
    </div>
</div>


@endsection

{{-- Para implementação futura de AJAX --}} 
@section('javascript')
    <script type="text/javascript">

        $(document).ready(function(){
            $('#minhaTabela1').DataTable({
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
@endsection