@extends('layouts.main')

@section('title', 'Dashboard')
@section('content')

<div class="col-md-10 offset-md-1 dashboard-title-container">
    <h4>Minhas autorizações de viagens</h4>
</div>
<div class="col-md-10 offset-md-1 dashboard-avs-container">
    @if(count($avs) > 0 )
    <table id="minhaTabela">
        <thead>
            <tr>
                <th>Número</th>
                <th>Data</th>
                <th>Prioridade</th>
                <th>Status</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            @foreach($avs as $av)
            <tr>
                <td scropt="row">{{ $av->id }}</td>
                <td> <a> {{ date('d/m/Y', strtotime($av->dataCriacao)) }} </a></td>
                <td> {{$av->prioridade}} </td>
                <td> {{$av->status}} </td>
                <td> 
                    <div class="opcoesGerenciarAv">
                        <a href="/avs/edit/{{ $av->id }}" class="btn btn-success btn-sm"
                            style="width: 80px">  Editar</a> 
                        <form action="/avs/{{ $av->id }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-active btn-accent btn-sm"
                            style="width: 80px" > Deletar</button>
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
                    scrollY: 400,
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