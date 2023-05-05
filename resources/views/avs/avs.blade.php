@extends('layouts.main')

@section('title', 'Dashboard')
@section('content')

<div style="padding-left: 10%" class="container">
    
    <div class="row justify-content-between">
        
        <div class="col-8">
            <h4>Minhas autorizações de viagens</h4>
        </div>
        
        <div class="col-3">
            <strong>Adicionar AV</strong> 
            <a class="btn btn-success" type="button" href="/avs/create" > <ion-icon name="add-circle-outline" size="large"></ion-icon></a>
        </div>
    </div>
    <br>
</div> 

<div class="col-md-10 offset-md-1 dashboard-avs-container">
    @if(count($avs) > 0 )
    <table id="minhaTabela" class="display nowrap">
        <thead>
            <tr>
                <th style="width: 50px">Número</th>
                <th>Objetivo</th>
                <th>Rota</th>
                <th>Data criação</th>
                <th>Prioridade</th>
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
                <td> {{$av->prioridade}} </td>
                <td> {{$av->status}} </td>
                <td> 
                    <div class="opcoesGerenciarAv">
                        <a href="/avs/edit/{{ $av->id }}" class="btn btn-success btn-sm"
                            style="width: 110px">  Editar</a>
                        <a href="/rotas/rotas/{{ $av->id }}" class="btn btn-secondary btn-sm"
                            style="width: 110px">  Adm Rotas</a> 
                        <form action="/avs/{{ $av->id }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-active btn-accent btn-sm"
                            style="width: 110px" > Deletar</button>
                        </form>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <p>Você ainda não tem autorizações de viagens, <a href="/avs/create"> Criar Nova AV</a></p>
    @endif
    
</div>

@endsection

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