@extends('layouts.main')

@section('title', $av->id)
@section('content')

<div class="row justify-content-start" style="padding-left: 5%">
    <div class="col-3">
        <a href="/avs/avs" class="btn btn-primary">Voltar</a>
    </div>
</div>
    <div class="container">
        <div class="container text-center">
            <div id="info-container" class="col-12">
                <h3> Autorização de Viagem nº {{ $av->id }}</h3>
            </div>
            <div class="col-md-12" id="description-container">
                <br><br>
                @if($av->isRealizadoReserva)
                    <h3 style="color: red">A sua AV possui reservas realizadas pelo CAD, ao cancelar será enviado um aviso ao CAD para que seja feito o cancelamento das reservas.</h3>
                    <br><br>
                @endif
                @if($av->isAprovadoFinanceiro)
                    <h3 style="color: red">A sua AV possui adiantamentos realizados pelo CFI, ao cancelar a AV será enviada para Prestação de Contas para que seja realizada a devolução.</h3>
                    <br><br>
                @endif
                <h3>Você tem certeza que deseja cancelar a AV?</h3>
                <br><br>
                <form action="/avs/marcarComoCancelado/{{ $av->id }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <button type="submit" class="btn btn-active btn-accent btn-sm"> Cancelar AV</button>
                </form>
                <br>
            </div>
        </div>
        
    </div>

@endsection