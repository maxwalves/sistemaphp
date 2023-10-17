@extends('adminlte::page')

@section('title', 'Editar nova data')

@section('content_header')
    <h1>Editar nova data</h1>
@stop

@section('content')
    
<div id="container">
    <div class="col-4" >
        <a href="/rotas/rotasEditData/{{ $rota->av_id }}" type="submit" class="btn btn-active btn-ghost" style="width: 180px"> Voltar!</a>
    </div>
    <form action="/rotas/updateData/{{ $rota->id }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="form-group" style="padding-left: 10%">
                <select class="select select-bordered w-full max-w-xs" hidden="true"
                    id="isViagemInternacional" name="isViagemInternacional" onChange="gerenciaNacionalInternacional()" >
                    <option value="" name=""> Selecione</option>
                    <option value="0" name="0" {{ $rota->isViagemInternacional == "0" ? "selected='selected'" : ""}}> Não</option>
                    <option value="1" name="1" {{ $rota->isViagemInternacional == "1" ? "selected='selected'" : ""}}> Sim</option>
                </select>
        </div>

        <div id="isInternacional">
            <br>
                    
            <div class="row justify-content-center">
                <div class="col-5">
                    {{-- CAMPOS DE ORIGEM INTERNACIONAL ---------------------------------}}
                    <h3 style="color: brown"> <ion-icon name="airplane-outline"></ion-icon> VIAGEM INTERNACIONAL</h3>
                    <br>
                    <h4 style="color: crimson"> Origem: </h4>

                    <div class="form-group"> 
                        <div id="dataHoraSaidaInternacional" class="input-append date">
                            <label for="dataHoraSaidaInternacional" class="control-label">Data/Hora de saída: </label>
                            <input data-format="dd/MM/yyyy hh:mm:ss" type="datetime-local" name="dataHoraSaidaInternacional"
                                id="dataHoraSaidaInternacional" placeholder="Data/Hora de saída" 
                                value="{{ $rota->isViagemInternacional == '1' ? $rota->dataHoraSaida : ''}}" min = "{{$rota->dataHoraSaida}}">
                        </div>
                    </div>
                </div>
                <div class="col-5">
                    {{-- CAMPOS DE DETINO INTERNACIONAL ---------------------------------}}
                    <br><br>
                    <h4 style="color: crimson"> Destino: </h4>

                    <div class="form-group"> 
                        <div id="dataHoraChegadaInternacional" class="input-append date">
                            <label for="dataHoraChegadaInternacional" class="control-label">Data/Hora de chegada: </label>
                            <input data-format="dd/MM/yyyy hh:mm:ss" type="datetime-local" name="dataHoraChegadaInternacional"
                                id="dataHoraChegadaInternacional" placeholder="Data/Hora de chegada" 
                                value="{{ $rota->isViagemInternacional == '1' ? $rota->dataHoraChegada : ''}}" min = "{{$rota->dataHoraChegada}}">
                        </div>
                    </div>

                </div>
            </div>
        </div>


{{-- INÍCIO DOS CAMPOS PARA VIAGEM NACIONAL ------------------------------------------------------------}}
    
        <div id="isNacional">
            <br>    

            <div class="row justify-content-center">
                <div class="col-5">
                    <h3 style="color: forestgreen"> <ion-icon name="bus-outline"></ion-icon> VIAGEM NACIONAL </h3>
                    <br>   
                    <h4 style="color: darkolivegreen"> Origem: </h4>

                    <div class="form-group"> 
                        <div id="dataHoraSaidaNacional" class="input-append date">
                            <label for="dataHoraSaidaNacional" class="control-label">Data/Hora de saída: </label>
                            <input data-format="dd/MM/yyyy hh:mm:ss" type="datetime-local" name="dataHoraSaidaNacional"
                                id="dataHoraSaidaNacional" placeholder="Data/Hora de saída" 
                                value="{{ $rota->isViagemInternacional == '0' ? $rota->dataHoraSaida : ''}}" >
                        </div>
                    </div>

                </div>
                    
                <div class="col-5">    
                    <br><br>
                    <h4 style="color: darkolivegreen"> Destino: </h4>

                    <div class="form-group"> 
                        <div id="dataHoraChegadaNacional" class="input-append date">
                            <label for="dataHoraChegadaNacional" class="control-label">Data/Hora de chegada: </label>
                            <input data-format="dd/MM/yyyy hh:mm:ss" type="datetime-local" name="dataHoraChegadaNacional"
                                id="dataHoraChegadaNacional" placeholder="Data/Hora de chegada" 
                                value="{{ $rota->isViagemInternacional == '0' ? $rota->dataHoraChegada : ''}}" >
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <br>
        <div class="col-md-6 offset-md-3">

            <div id="btSalvarRota">
                <input style="font-size: 16px" type="submit" class="btn btn-active btn-secondary" value="Salvar Rota!">
            </div>

        </div>

    </form>

</div>

@stop

@section('css')
    
@stop

@section('js')
    
<script type="text/javascript">

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': "{{ csrf_token() }}"
        }
    });

    function gerenciaNacionalInternacional(){
        var isViagemInternacional = document.getElementById("isViagemInternacional");

        if(isViagemInternacional.value=="0") {
            document.getElementById("isNacional").hidden = false;
            document.getElementById("isInternacional").hidden = true;
        }
        else if(isViagemInternacional.value=="1"){
            document.getElementById("isNacional").hidden = true;
            document.getElementById("isInternacional").hidden = false;
        }
        else{
            document.getElementById("isNacional").hidden = true;
            document.getElementById("isInternacional").hidden = true;
        }
    }
            //Assim que a tela carrega, aciona automaticamente essas duas funções ------------------------
    $(function(){
        gerenciaNacionalInternacional();
    })
</script>

@stop