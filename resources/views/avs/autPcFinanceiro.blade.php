@extends('layouts.main')

@section('title', 'Dashboard')
@section('content')

<div style="padding-left: 10%" class="container">
    
    <div class="row justify-content-between">
        
        <div class="col-8">
            <h4>Minhas autorizações de viagens</h4>
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
                        <a href="/avs/avaliarPcFinanceiro/{{ $av->id }}" class="btn btn-secondary btn-sm"
                            style="width: 230px"> Avaliar Prestação de Contas</a> 
                        
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
        <p>Você não tem autorizações de viagens para avaliar</p>
    @endif
    
    <input type="checkbox" id="my-modal" class="modal-toggle" />
    <div class="modal">
        <div class="modal-box">
            <h3 class="font-bold text-lg"></h3>
            <p class="py-4"></p>
            <div class="modal-action">
            
            <a class="btn btn-error btn-sm" id="btn-submit-modal"
                style="width: 200px">  Voltar AV e editar</a>
            
            <label for="my-modal" class="btn btn-success btn-sm" style="width: 100px">Não</label>
            </div>
        </div>
    </div>
    
</div>

@endsection

@section('javascript')
    <script type="text/javascript">

        $(function(){
            const modal = document.querySelector('.modal');
            const avLabel = document.querySelector('.btn[data-av]');
            const av = JSON.parse(avLabel.getAttribute('data-av'));

            avLabel.addEventListener('click', () => {
                modal.querySelector('h3').textContent = `Você tem certeza que deseja voltar a AV ${av.id} ?`;
                modal.querySelector('p').textContent = `Após ela retornar ao estado inicial, a Autorização de Viagem terá que passar todas as etapas novamente!`;
                modal.querySelector('a').textContent = `Voltar AV ${av.id} e editar`;
            });

            $('#btn-submit-modal').click(function() {
                window.location.href = '/avs/voltarAv/' + av.id;
            });
        })

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