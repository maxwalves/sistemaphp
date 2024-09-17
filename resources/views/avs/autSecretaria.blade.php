@extends('adminlte::page')

@section('title', 'AV Pendente CAD')

@section('content_header')
    <h1>AV Pendente CAD</h1>
@stop

@section('content')

<div class="col-md-12 dashboard-avs-container">
    @if(count($avs) > 0 )
    <table id="minhaTabela" class="display nowrap">
        <thead>
            <tr>
                <th style="width: 50px">Número</th>
                <th>Nome funcionário</th>
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
                    @foreach($users as $u)
                        @if ($u->id == $av->user_id)
                                {{$u->name}}     
                        @endif
                    @endforeach
                </td>
                
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
                    @if($av->isCancelado == true)
                    <div class="opcoesGerenciarAv">
                        <a href="/avs/verFluxoSecretaria/{{ $av->id }}" class="btn btn-warning btn-sm"
                            title="Gerenciar cancelamento"><i class="far fa-calendar-times"></i></a> 
                    </div>
                    @else
                    <div class="opcoesGerenciarAv">
                        <a href="/avs/verFluxoSecretaria/{{ $av->id }}" class="btn btn-primary btn-sm"
                           title="Realizar Reservas"><i class="far fa-calendar-alt"></i></a> 
                    </div>
                    @endif
                    
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
        <p>Você não tem autorizações de viagens para avaliar</p>
    @endif
    
</div>

@stop

@section('css')
    <link href="{{asset('DataTables/datatables.min.css')}}" rel="stylesheet"/>
@stop

@section('js')
    <script src="{{asset('/js/moment.js')}}"></script>
    <script src="{{asset('DataTables/datatables.min.js')}}"></script>
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
                },
                "order": [[ 0, "desc" ]]
            });
        });

    </script>

@stop
