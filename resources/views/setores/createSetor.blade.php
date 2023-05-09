@extends('layouts.main')

@section('title', 'Cadastrar novo setor')
@section('content')

<div style="padding-left: 50px, padding-right: 50px" class="container">
    <div class="row justify-content-between" >
        
        <div class="col-4">
            <a href="/setores/setores/" type="submit" class="btn btn-active btn-ghost"> Voltar!</a>
        </div>
    </div>
</div>
<div id="av-create-container" class="col-md-6 offset-md-3">
        <h2>Cadastrar novo setor!</h2>
        <br>
        <form action="/setores" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="form-group">
                <label for="nome" class="control-label">Nome do setor</label>
                <div>
                    <input type="text" class="input input-bordered input-secondary w-full max-w-xs {{ $errors->has('nome') ? 'is-invalid' :''}}" 
                    name="nome" id="nome" placeholder="Nome">
                </div>

                @if ($errors->has('nome'))
                    <div class="invalid-feedback">
                        {{ $errors->first('nome') }}
                    </div>
                @endif
            </div>

            <div class="form-group">
                <label for="chefe_id" class="control-label" required>Chefe (selecione)</label>
                <br>
                    <select class="select select-bordered w-full max-w-xs {{ $errors->has('chefe_id') ? 'is-invalid' :''}}" 
                        id="chefe_id" name="chefe_id">
                        <option value="" name=""> Selecione</option>
                        @for($i = 0; $i < count($users); $i++)
                            <div>
                                <option value="{{ $users[$i]->id }}" 
                                    name="{{ $users[$i]->id }}"> {{ $users[$i] ->name }} </option>
                            </div>
                        @endfor
                    </select>

                    @if ($errors->has('chefe_id'))
                    <div class="invalid-feedback">
                        {{ $errors->first('chefe_id') }}
                    </div>
                    @endif
            </div>

            <div id="btSalvarSetor">
                <input style="font-size: 14px" type="submit" class="btn btn-active btn-primary btn-lg" value="Cadastrar setor!">
            </div>
            
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