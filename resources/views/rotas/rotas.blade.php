@extends('layouts.main')

@section('title', 'Dashboard')
@section('content')

<div style="padding-left: 50px, padding-right: 50px" class="container">
    <div class="row justify-content-between" style="padding-left: 5%">
        <div class="btn-group" >
            <div class="col-4">
                <a href="/avs/avs/" type="submit" class="btn btn-active btn-ghost"> Voltar!</a>
            </div>
            <div class="col-4">
                <a href="/rotas/create/{{ $av->id }}" type="submit" class="btn btn-active btn-primary"> + CADASTRAR ROTA</a>
            </div>
            <div class="col-4">
                <a href="/avs/concluir/{{ $av->id }}" type="button" class="btn btn-active btn-secondary">Calcular diárias</a>
            </div>
        </div>
        <div class="col-4">
            <label for="idav" > <strong>AV nº </strong> </label>
            <input style="width: 50px" type="text" value="{{ $av->id }}" id="idav" name="idav" disabled>
            <h2> <strong>Data: {{ date('d/m/Y', strtotime($av->dataCriacao)) }}</strong> </h2>
        </div>
    </div>
    
    <br>
</div>
<div class="col-md-10 offset-md-1 dashboard-avs-container">
    @if(count($rotas) > 0 )
    <table id="tabelaRota" class="cell-border compact stripe">
        <thead>
            <tr>
                <th>Número Rota</th>
                <th>Tipo</th>
                <th>Cidade de saída</th>
                <th>Data/Hora de saída</th>
                <th>Cidade de chegada</th>
                <th>Data/Hora de chegada</th>
                <th>Hotel?</th>
                <th>Tipo de transporte</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            @foreach($rotas as $rota)
            <tr>
                <td> {{$rota->id}} </td>
                <td> {{$rota->isViagemInternacional == 1 ? "Internacional" : "Nacional"}} </td>
                <td> {{$rota->isViagemInternacional == 0 ? $rota->cidadeOrigemNacional : $rota->cidadeOrigemInternacional}} </td>
                <td> {{ date('d/m/Y H:m:s', strtotime($rota->dataHoraSaida)) }} </td>
                <td> {{$rota->isViagemInternacional == 0 ? $rota->cidadeDestinoNacional : $rota->cidadeDestinoInternacional}} </td>
                <td> {{ date('d/m/Y H:m:s', strtotime($rota->dataHoraChegada)) }} </td>
                <td> {{ $rota->isReservaHotel == 1 ? "Sim" : "Não"}}</td>
                <td> 
                    {{ $rota->isOnibusLeito == 1 ? "Onibus leito" : ""}}
                    {{ $rota->isOnibusConvencional == 1 ? "Onibus convencional" : ""}}
                    {{ $rota->isVeiculoProprio == 1 ? "Veículo próprio" : ""}}
                    {{ $rota->isVeiculoEmpresa == 1 ? "Veículo empresa" : ""}}
                    {{ $rota->isAereo == 1 ? "Aéreo" : ""}}
                </td>
                <td> 
                    <a href="/rotas/edit/{{ $rota->id }}" class="btn btn-success btn-sm" style="width: 85px"> Editar</a> 
                    <form action="/rotas/{{ $rota->id }}" style="width: 85px" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-error btn-sm"> Deletar</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <p>Você ainda não tem rotas, <a href="/rotas/create/{{ $av->id }}"> Criar nova rota</a></p>
    @endif
</div>

@endsection

@section('javascript')
    <script type="text/javascript">

        $(document).ready(function(){
            $('#tabelaRota').DataTable({
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

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            }
        });

        function gerenciaNacionalInternacional(){

            if(isViagemInternacional.value=="0") {

            }
            else if(isViagemInternacional.value=="1"){

            }
            else{

            }
        }
        function carregaCidade(){
            
        }

                //Assim que a tela carrega, aciona automaticamente essas duas funções ------------------------
        $(function(){
            carregaCidade();
            
        })
    </script>
@endsection