@extends('adminlte::page')

@section('title', 'Prestação de Contas')

@section('content_header')
    <h1>Prestação de Contas</h1>
@stop

@section('content')
    
<div style="padding-left: 10%" class="container">
    
    <div class="row justify-content-between">
        
        <div class="col-8">
            <h4>Minhas Prestações de Contas</h4>
        </div>
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
                <th>Data retorno</th>
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
                <td>
                    @for($i = 0; $i < count($av->rotas); $i++)
                        @if($i == (count($av->rotas)-1))
                            {{date('d/m/Y H:i', strtotime($av->rotas[$i]->dataHoraChegada))}}
                        @endif
                    @endfor
                </td>
                <td> {{$av->status}} </td>
                <td> 
                    @php
                        date_default_timezone_set('America/Sao_Paulo');
                    @endphp
                    @if(($av->isEnviadoUsuario==1 && $av->isAprovadoGestor ==1 && $av->isRealizadoReserva ==1 && $av->isAprovadoFinanceiro ==1
                        && $av->isPrestacaoContasRealizada == 0 && $av->isCancelado == 0) ||
                        ($av->isCancelado == 1 && $av->isAprovadoFinanceiro == 1 && $av->isPrestacaoContasRealizada == 0))

                                @if(true)
                                    <div class="opcoesGerenciarAv">
                                        <a href="/avs/fazerPrestacaoContas/{{ $av->id }}" class="btn btn-warning btn-sm"
                                            title="Prestar contas"><i class="fas fa-file-invoice-dollar"></i></a> 
                                    </div>
                                @else
                                    Ainda não finalizou
                                @endif
                            
                    @elseif(($av->isEnviadoUsuario==1 && $av->isAprovadoGestor ==1 && $av->isRealizadoReserva ==1 && $av->isAprovadoFinanceiro ==1
                            && $av->isPrestacaoContasRealizada == 1) ||
                            ($av->isCancelado == 1 && $av->isAprovadoFinanceiro == 1 && $av->isPrestacaoContasRealizada == 1))

                        <a href="/avs/verDetalhesPc/{{ $av->id }}" class="btn btn-primary btn-sm"
                            title="Ver"><i class="fas fa-eye"></i></a>

                        @if($av->isAcertoContasRealizado == 1 && $av->isUsuarioAprovaAcertoContas != 1)
                            <a href="/avs/validarAcertoContasUsuario/{{ $av->id }}" class="btn btn-warning btn-sm"
                            title="Validar PC"><i class="fas fa-exclamation-triangle"></i></a>
                        @endif
                    @endif
                    @if(($av->isEnviadoUsuario==1 
                            && $av->isAprovadoGestor ==1 
                            && $av->isRealizadoReserva ==1 
                            && $av->isAprovadoFinanceiro ==1
                            && $av->isPrestacaoContasRealizada == 1
                            && $av->isFinanceiroAprovouPC == 1
                            && $av->isGestorAprovouPC == 1
                            && $av->status == "Aguardando envio de comprovante de devolução pelo usuário")
                            )
                        <a href="/avs/verPaginaDevolucaoPc/{{ $av->id }}" class="btn btn-danger btn-sm"
                            title="Devolver valor não utilizado"><i class="fas fa-dollar-sign"></i></i></a>

                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
        <p>Você não tem prestação de contas para avaliar</p>
    @endif
    
</div>

@stop

@section('css')
    <link href="{{ asset('DataTables/datatables.min.css') }}" rel="stylesheet" />
@stop

@section('js')

    <script src="{{ asset('DataTables/datatables.min.js') }}"></script>
    <script src="{{ asset('/js/moment.js') }}"></script>
    <script type="text/javascript">

        $(document).ready(function(){
            $('#minhaTabela').DataTable({
                    scrollY: 500,
                    "order": [ 0, 'desc' ],
                    "language": {
                        "search": "Buscar",
                        "lengthMenu": "Mostrando _MENU_ registros por página",
                        "zeroRecords": "Nada encontrado",
                        "info": "Mostrando página _PAGE_ de _PAGES_",
                        "infoEmpty": "Nenhum registro disponível",
                        "infoFiltered": "(filtrado de _MAX_ registros no total)"
                    }
                });
        });

    </script>

@stop
