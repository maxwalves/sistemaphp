@extends('adminlte::page')

@section('title', 'Cancelar AV')

@section('content_header')
    <h1>Cancelar AV</h1>
@stop

@section('content')
    <div class="row justify-content-start" style="padding-left: 5%">
        <div class="col-3">
            <a href="/avs/avs" class="btn btn-warning">Voltar</a>
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
                    <div class="form-group">
                        <label for="justificativa" class="control-label">Justificativa:</label><br>
                        <textarea type="textarea" class="textarea textarea-secondary textarea-lg" name="justificativa"
                        id="justificativa" placeholder="Justificativa" style="width: 400px; height: 100px"></textarea>
                    </div>
                    <button type="submit" class="btn btn-active btn-danger btn-sm"> Cancelar AV</button>
                </form>
                <br>
            </div>
        </div>
        
    </div>
@stop

@section('css')
    
@stop

@section('js')
    
@stop