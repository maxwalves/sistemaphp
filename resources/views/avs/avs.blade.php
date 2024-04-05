@extends('adminlte::page')

@section('title', 'Minhas autorizações de viagens')

@section('content_header')
@stop

@section('content')

<div class="tab-pane fade show active" id="custom-tabs-five-overlay" role="tabpanel" aria-labelledby="custom-tabs-five-overlay-tab" style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.5); z-index: 9999; display: none">
    <div class="overlay-wrapper" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); text-align: center; color: #ffffff;">
            <i class="fas fa-3x fa-sync-alt fa-spin" style="margin-bottom: 10px;"></i>
            <div class="text-bold pt-2">Carregando...</div>
    </div>
</div>

    <div>
        
        <div class="row justify-content-between">
            <div class="col-8">
                <br>
                <h4>Minhas autorizações de viagens</h4>
            </div>
        </div>
        <div class="col-3">
            <strong></strong> 
            @if($user->employeeNumber != null)
                <a class="btn btn-success btn-lg" type="button" href="/avs/create" ><i class="fas fa-plus"></i></a>
            @else
                <p ><h3><strong style="color: red">Matrícula não cadastrada, entre em contato com o suporte</strong></h3></p>
            @endif
        </div>
        <br>
    </div> 

    <div class="col-md-12 dashboard-avs-container">
        @if(count($avs) > 0 )
        <table id="minhaTabela" class="display nowrap">
            <thead>
                <tr>
                    <th style="width: 50px">Número</th>
                    <th>Objetivo</th>
                    <th>Rota</th>
                    <th>Data criação</th>
                    <th>Status</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach($avs as $av)
                <tr>
                    <td scropt="row">{{ $av->id }}</td>
                    
                    <td>
                        @for($i = 0; $i < count($objetivos); $i++)

                            @if ($av->objetivo_id == $objetivos[$i]->id )
                                    {{$objetivos[$i]->nomeObjetivo}}     
                            @endif

                        @endfor

                        @if (isset($av->outroObjetivo))
                                {{$av->outroObjetivo }} 
                        @endif
                    </td>

                    <td>
                        @for($i = 0; $i < count($av->rotas); $i++)

                            @if($av->rotas[$i]->isViagemInternacional == 0)
                            - {{$av->rotas[$i]->cidadeOrigemNacional}}
                            @endif
                            
                            @if($av->rotas[$i]->isViagemInternacional == 1)
                            - {{$av->rotas[$i]->cidadeOrigemInternacional}} 
                            @endif

                        @endfor

                    </td>

                    <td> <a> {{ date('d/m/Y', strtotime($av->dataCriacao)) }} </a></td>
                    <td> {{$av->status}} </td>
                    <td> 
                        @if($av->isEnviadoUsuario == 0 && $av->isCancelado == 0)
                            <div class="opcoesGerenciarAv d-flex align-items-center">
                                <a href="/avs/edit/{{ $av->id }}" class="btn btn-warning btn-sm" title="Editar AV">
                                    <i class="far fa-edit"></i></a>

                                <a href="/avs/verDetalhesAv/{{ $av->id }}" class="btn btn-info btn-sm" title="Ver AV">
                                    <i class="far fa-eye"></i></a> 

                                <a href="/rotas/rotas/{{ $av->id }}" class="btn btn-primary btn-sm" title="Rotas">
                                    <i class="fas fa-map-marked"></i></a>

                                <form action="/avs/{{ $av->id }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" title="Remover AV">
                                        <i class="fas fa-trash-alt"></i></button>
                                </form>
                            </div>
                        @else

                            @if($av->isEnviadoUsuario == 1 && $av->isAprovadoGestor == 0 && $av->isCancelado == 0)
                                <button class="btn btn-active btn-warning btn-sm" title="Voltar AV"
                                onclick="abrirModalVoltarAv({{$av}})"><i class="fas fa-arrow-left"></i></button>
                            @endif

                            @if($av->isEnviadoUsuario == 1 && $av->isAcertoContasRealizado == 0 && $av->isPrestacaoContasRealizada == 0 && $av->isCancelado == 0 
                            && $av->status != "AV Internacional cadastrada no sistema")

                                <a href="/avs/cancelarAv/{{ $av->id }}" class="btn btn-danger btn-error btn-sm" title="Cancelar AV">
                                    <i class="fas fa-window-close"></i></a>
                            @endif
                            <a href="/avs/verDetalhesAv/{{ $av->id }}" class="btn btn-info btn-sm" title="Ver AV" onclick="verBt()">
                                <i class="far fa-eye"></i></a> 
                            
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <p>Você ainda não tem autorizações de viagens, <a href="/avs/create"> Criar Nova AV</a></p>
        @endif
        
        <x-adminlte-modal id="modalVoltar" title="Voltar AV?" size="xl" theme="teal"
        icon="fas fa-bell" v-centered static-backdrop scrollable>

        <div>
            
            <h3 class="font-bold text-lg" id="tituloModal"></h3>
            <p class="py-4" id="textoModal"></p>
            <div class="modal-action">
            </div>
            
        </div>
        <x-slot name="footerSlot">
            <x-adminlte-button id="btn-submit-modal" style="width: 200px" theme="success" label="Voltar av e editar" />
            <x-adminlte-button theme="danger" label="Não" data-dismiss="modal"/>
        </x-slot>

        </x-adminlte-modal>       
        
    </div>
@stop

@section('css')
    <link href="{{asset('DataTables/datatables.min.css')}}" rel="stylesheet"/>
@stop

@section('js')

    <script src="{{asset('DataTables/datatables.min.js')}}"></script>
    <script src="{{asset('/js/moment.js')}}"></script>
    <script type="text/javascript">

        function verBt() {
            $('#custom-tabs-five-overlay').css('display', 'block');
        }

        function abrirModalVoltarAv(av) {

            //recupera o elemento h3 tituloModal
            let tituloModal = document.querySelector('#tituloModal');
            tituloModal.textContent = `Você tem certeza que deseja voltar a AV ${av.id} ?`;

            //recupera o elemento p textoModal
            let textoModal = document.querySelector('#textoModal');
            textoModal.textContent = `Após ela retornar ao estado inicial, a Autorização de Viagem terá que passar todas as etapas novamente!`;

            //recupera o elemento a btn-submit-modal
            let btnSubmitModal = document.querySelector('#btn-submit-modal');
            btnSubmitModal.textContent = `Voltar AV ${av.id} e editar`;

            let modal = new bootstrap.Modal(document.querySelector('#modalVoltar'));
    
            modal.show();

            $('#btn-submit-modal').click(function() {
                window.location.href = '/avs/voltarAv/' + av.id;
            });
        }

        $(document).ready(function(){
            $('#minhaTabela').DataTable({
                    scrollY: 500,
                    orderFixed: [0, 'desc'],
                    "language": {
                        "lengthMenu": "Mostrando _MENU_ registros por página",
                        "zeroRecords": "Nada encontrado",
                        "info": "Mostrando página _PAGE_ de _PAGES_",
                        "infoEmpty": "Nenhum registro disponível",
                        "infoFiltered": "(filtrado de _MAX_ registros no total)",
                        "search": "Pesquisar"
                    }
                });

            @if(session('error'))

                Swal.fire({
                    html: '<i class="fas fa-exclamation-triangle"></i> {{ session('error') }}',
                    title: 'Oops...',
                })
            @endif

            @if(session('msg'))

                Swal.fire({
                    title: '{{ session('msg') }}',
                    text: '',
                })
            @endif
        });
        

    </script>
@stop
