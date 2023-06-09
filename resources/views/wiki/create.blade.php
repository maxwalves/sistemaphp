@extends('wiki.layouts.main')

@section('title', 'DSS')
@section('content')


<div >
    <h1 class="tituloSistema">Cadastrar novo arquivo</h1>
    <div class="container">
        <div class="col-md-6 offset-md-3">
            <form action="gravarArquivo" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="mb-3">
                    <label for="comentario" class="form-label">Nome do arquivo</label><br>
                    <div class="input-group mb-3">
                        <input type="text" class="form-control {{ $errors->has('nome') ? 'is-invalid' :''}}" name="nome"
                            id="nome" placeholder="Nome">
                        <span class="input-group-text" id="basic-addon2">Nome do arquivo</span>
                        @if ($errors->has('nome'))
                            <div class="invalid-feedback">
                                {{ $errors->first('nome') }}
                            </div>
                        @endif
                    </div>
                </div>
                <br>
                <div class="form-group">
                    <label for="tipo" class="control-label" required>Qual é a tipo do seu arquivo? (selecione)</label>
                    <br>
                        <select class="select select-bordered w-full max-w-xs {{ $errors->has('tipo') ? 'is-invalid' :''}}" 
                            id="tipo" name="tipo">
                            <option value="" name=""> Selecione</option>
                            <option value="Norma" name="Norma"> Norma</option>
                            <option value="Instrução Normativa" name="Instrução Normativa"> Instrução Normativa</option>
                            <option value="Legislação" name="Legislação"> Legislação</option>
                            <option value="Norma de Gestão" name="Norma de Gestão"> Norma de Gestão</option>
                        </select>
                        @if ($errors->has('tipo'))
                            <div class="invalid-feedback">
                                {{ $errors->first('tipo') }}
                            </div>
                        @endif
                </div>
                <br>
                <div class="mb-3">
                    <label for="comentario" class="form-label">Texto</label><br>
                    <div class="input-group mb-3" >

                        <div id="sample">
                            <script type="text/javascript" src="//js.nicedit.com/nicEdit-latest.js"></script> 
                            <script type="text/javascript">
                            bkLib.onDomLoaded(function() {
                                  new nicEditor({maxHeight : 500}).panelInstance('texto');
                            });
                            </script>
                        
                            <textarea style="height: 500px;" cols="100" id="texto" name="texto" class="{{ $errors->has('texto') ? 'is-invalid' :''}}"></textarea>
                            @if ($errors->has('texto'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('texto') }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                <br>
                <label for="arquivo1">Arquivo em PDF: </label>
                <input type="file" id="arquivo1" style="height: 150px" name="arquivo1" class="form-control form-control-lg {{ $errors->has('arquivo1') ? 'is-invalid' :''}}">
                @if ($errors->has('arquivo1'))
                    <div class="invalid-feedback">
                        {{ $errors->first('arquivo1') }}
                    </div>
                @endif
                <br>

                <div class="row justify-content-center" style="background-color: lightgrey">
                    <div id="btSalvarAv">
                        <input style="font-size: 14px" type="submit" class="btn btn-active btn-secondary" value="Salvar arquivo">
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
@section('javascript')
    <script type="text/javascript">


    </script>
@endsection