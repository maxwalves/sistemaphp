@extends('wiki.layouts.main')

@section('title', 'DSS')
@section('content')


<div >
    <h1 class="tituloSistema">Ver arquivo</h1>
    <div class="container">
        <div class="col-md-6 offset-md-3">
            
                <div class="mb-3">
                    <label for="comentario" class="form-label">Texto</label><br>
                    <div class="input-group mb-3" >

                        <div id="sample">
                            <script type="text/javascript" src="//js.nicedit.com/nicEdit-latest.js"></script> 
                            <script type="text/javascript">
                            bkLib.onDomLoaded(function() {
                                  new nicEditor({maxHeight : 700}).panelInstance('area5');
                            });
                            </script>
                        
                            <textarea style="height: 700px;" cols="100" id="area5" name="texto" id="texto">{{$arquivo->textoHtml}}</textarea>
                        </div>
                    </div>
                </div>
                
        </div>
    </div>
</div>

@endsection
@section('javascript')
    <script type="text/javascript">

    </script>
@endsection