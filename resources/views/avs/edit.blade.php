@extends('layouts.main')

@section('title', 'Editando: ' . $av->id)
@section('content')

<div id="av-create-container" class="col-md-6 offset-md-3">
        <h2>Editando: {{ $av->id }}</h2>
        <form action="/avs/update/{{ $av->id }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="objetivo_id" class="control-label">Qual é o Objetivo da viagem</label>

                    <select class="form-control" id="objetivo_id" name="objetivo_id" >
                        
                        @for($i = 0; $i < count($objetivos); $i++)
                            <div>
                                <option value="{{ $objetivos[$i]->id }}" {{ $av->objetivo_id == $objetivos[$i]->id ? "selected='selected'" : ""}}
                                    name="{{ $objetivos[$i]->id }}"> {{ $objetivos[$i] ->nomeObjetivo }} </option>
                            </div>
                        @endfor
                    </select>
                
            </div>

            <div class="form-group">
                <label for="prioridade" class="control-label">Qual é a Prioridade da sua viagem? (selecione)</label>
                    <select class="form-control" id="prioridade" name="prioridade">
                        <option value="Alta" {{ $av->prioridade == "Alta" ? "selected='selected'" : ""}} name="Alta"> Alta</option>
                        <option value="Média" {{ $av->prioridade == "Média" ? "selected='selected'" : ""}} name="Média"> Média</option>
                        <option value="Baixa" {{ $av->prioridade == "Baixa" ? "selected='selected'" : ""}} name="Baixa"> Baixa</option>
                    </select>
            </div>
            
            <div class="form-group">
                <label for="banco" class="control-label">Banco</label>
                <input type="number" class="form-control" name="banco"
                id="banco" placeholder="Banco" value="{{$av->banco}}"> 
            </div>

            <div class="form-group">
                <label for="agencia" class="control-label">Agência</label>
                <div class="input-group">
                    <input type="number" class="form-control" name="agencia"
                    id="agencia" placeholder="Agência" value="{{$av->agencia}}">
                </div>
            </div>

            <div class="form-group">
                <label for="conta" class="control-label">Conta</label>
                <input type="number" class="form-control" name="conta"
                id="conta" placeholder="Conta" value="{{$av->conta}}">
            </div>

            <div class="form-group">
                <label for="pix" class="control-label">Pix</label>
                <input type="number" class="form-control" name="pix"
                    id="pix" placeholder="Pix" value="{{$av->pix}}">
            </div>

            <div class="form-group">
                <label for="comentario" class="control-label">Comentários</label>
                <input type="text" class="form-control" name="comentario"
                    id="comentario" placeholder="Comentário" value="{{$av->comentario}}">
            </div>



            <input type="submit" class="btn btn-primary" value="Salvar">

        </form>

    </div>
    
@endsection

{{-- Para implementação futura de AJAX --}} 
@section('javascript')
    <script type="text/javascript">

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            }
        });
        
                //Assim que a tela carrega, aciona automaticamente essas duas funções ------------------------
        $(function(){
        
        })
    </script>
@endsection