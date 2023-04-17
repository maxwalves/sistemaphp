@extends('layouts.main')

@section('title', 'Criar Autorização de viagem')
@section('content')

<div id="av-create-container" class="col-md-6 offset-md-3">
        <h2>Crie uma autorização de viagem!</h2>
        <form action="/avs" method="POST" enctype="multipart/form-data">
            @csrf


            <div class="form-group">
                <label for="objetivo_id" class="control-label">Qual é o Objetivo da viagem</label>

                    <select class="form-control" id="objetivo_id" name="objetivo_id">
                        
                        @for($i = 0; $i < count($objetivos); $i++)
                            <div>
                                <option value="{{ $objetivos[$i]->id }}" 
                                    name="{{ $objetivos[$i]->id }}"> {{ $objetivos[$i] ->nomeObjetivo }} </option>
                            </div>
                        @endfor
                    </select>
                
            </div>

            <div class="form-group">
                <label for="prioridade" class="control-label">Qual é a Prioridade da sua viagem? (selecione)</label>
                    <select class="form-control" id="prioridade" name="prioridade">
                        <option value="Alta" name="Alta"> Alta</option>
                        <option value="Média" name="Média"> Média</option>
                        <option value="Baixa" name="Baixa"> Baixa</option>
                    </select>
            </div>

            
            
            <div class="form-group">
                <label for="banco" class="control-label">Banco</label>
                <input type="number" class="form-control" name="banco"
                id="banco" placeholder="Banco">
            </div>

            <div class="form-group">
                <label for="agencia" class="control-label">Agência</label>
                <div class="input-group">
                    <input type="number" class="form-control" name="agencia"
                    id="agencia" placeholder="Agência">
                </div>
            </div>

            <div class="form-group">
                <label for="conta" class="control-label">Conta</label>
                <input type="number" class="form-control" name="conta"
                id="conta" placeholder="Conta">
            </div>

            <div class="form-group">
                <label for="pix" class="control-label">Pix</label>
                <input type="number" class="form-control" name="pix"
                    id="pix" placeholder="Pix">
            </div>

            <div class="form-group">
                <label for="comentario" class="control-label">Comentários</label>
                <input type="text" class="form-control" name="comentario"
                    id="comentario" placeholder="Comentário">
            </div>

            <input type="submit" class="btn btn-primary" value="Criar Av">
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