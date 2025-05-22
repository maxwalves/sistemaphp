@extends('adminlte::page')

@section('title', 'Aut Financeiro')

@section('content_header')
    <h1>Realização de Adiantamentos pelo Financeiro</h1>
@stop

@section('content')


<div class="row">
    
    <div class="col-md-6">
        <p>Escritórios visíveis:</p>
        <div class="badge bg-info" id="escCuritiba" name="escCuritiba" {{$isFinanceiroCuritiba == false ? "hidden='true'" : ""}}>Curitiba</div>
        <div class="badge bg-info" id="escCascavel" name="escCascavel" {{$temFinanceiroCascavel == false ? "hidden='true'" : ""}}>Cascavel</div>
        <div class="badge bg-info" id="escMaringa" name="escMaringa" {{$temFinanceiroMaringa == false ? "hidden='true'" : ""}}>Maringá</div>
        <div class="badge bg-info" id="escFrancisco" name="escFrancisco" {{$temFinanceiroFrancisco == false ? "hidden='true'" : ""}}>Francisco Beltrão</div>
        <div class="badge bg-info" id="escGuarapuava" name="escGuarapuava" {{$temFinanceiroGuarapuava == false ? "hidden='true'" : ""}}>Guarapuava</div>
        <div class="badge bg-info" id="escLondrina" name="escLondrina" {{$temFinanceiroLondrina == false ? "hidden='true'" : ""}}>Londrina</div>
        <div class="badge bg-info" id="escPontaGrossa" name="escPontaGrossa" {{$temFinanceiroPontaGrossa == false ? "hidden='true'" : ""}}>Ponta Grossa</div>
    </div>
</div>
<br>

<div class="col-md-12 dashboard-avs-container">
    @if(count($avs) > 0 )
    <div class="table-responsive">
        <table id="minhaTabela" class="display nowrap">
            <thead>
                <tr>
                    <th>Ações</th>
                    <th style="width: 50px">Número</th>
                    <th>Nome funcionário</th>
                    <th>Objetivo</th>
                    <th>Rota</th>
                    <th>Data criação</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($avs as $av)
                <tr>
                    <td> 
                        <div class="opcoesGerenciarAv">
                            <a href="/avs/verFluxoFinanceiro/{{ $av->id }}" class="btn btn-primary btn-sm"
                                title="Fazer adiantamento"><i class="fas fa-hand-holding-usd"></i></a> 
                        </div>
                    </td>
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
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
        <p>Você não tem autorizações de viagens para avaliar</p>
    @endif
    
</div>

@stop

@section('css')
    <link href="{{asset('DataTables/datatables.min.css')}}" rel="stylesheet"/>
    <style>
        .table-responsive {
            overflow-x: auto;
        }
        .dataTables_scrollHead {
            position: sticky;
            top: 0;
            z-index: 1;
            background: white;
        }
        #minhaTabela {
            width: 100% !important;
        }
        #minhaTabela th, #minhaTabela td {
            white-space: nowrap;
            padding: 8px;
        }
        #minhaTabela th:nth-child(1) { width: 80px; }
        #minhaTabela th:nth-child(2) { width: 80px; }
        #minhaTabela th:nth-child(3) { width: 200px; }
        #minhaTabela th:nth-child(4) { width: 150px; }
        #minhaTabela th:nth-child(5) { width: 200px; }
        #minhaTabela th:nth-child(6) { width: 120px; }
        #minhaTabela th:nth-child(7) { width: 120px; }
        
        /* Estilo para limitar o texto do objetivo */
        #minhaTabela td:nth-child(4) {
            max-width: 150px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
    </style>
@stop

@section('js')
    <script src="{{asset('/js/moment.js')}}"></script>
    <script src="{{asset('DataTables/datatables.min.js')}}"></script>
    <script type="text/javascript">
        $(document).ready(function(){
            $('#minhaTabela').DataTable({
                scrollY: 500,
                scrollX: true,
                fixedHeader: true,
                autoWidth: false,
                "language": {
                    "lengthMenu": "Mostrando _MENU_ registros por página",
                    "zeroRecords": "Nada encontrado",
                    "info": "Mostrando página _PAGE_ de _PAGES_",
                    "infoEmpty": "Nenhum registro disponível",
                    "infoFiltered": "(filtrado de _MAX_ registros no total)",
                    "search": "Pesquisar"
                },
                "order": [[ 1, "desc" ]]
            });
        });
    </script>
@stop