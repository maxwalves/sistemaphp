@extends('wiki.layouts.main')

@section('title', 'DSS')
@section('content')


<div >
    <h1 class="tituloSistema">Admin</h1>
    <div class="container">
        <div class="row justify-content-between">
        
            <div class="col-8">
                <h4 class="h3">Página de administração de arquivos</h4>
            </div>
            
            <div class="col-3">
                <strong>Adicionar Arquivo</strong> 
                <a class="btn btn-success" type="button" href="create" > <ion-icon name="add-circle-outline" size="large"></ion-icon></a>
            </div>
        </div>
        <br>
        <div class="input-group mb-3">

            <form action="{{ route('pesquisarAdmin') }}" method="GET" enctype="multipart/form-data" class="input-group mb-3">
                @csrf
                <span class="input-group-text" id="basic-addon1">Digite um termo para a pesquisa: </span>
                <input type="text" name="pesquisa" class="form-control" placeholder="Ex: férias">
                <button type="submit" class="btn btn-active btn-primary" >Pesquisar!</button>
            </form>
            
            <div class="col-md-12 dashboard-avs-container">
                @if (isset($resultados2) && count($resultados2) > 0)

                    <table id="minhaTabela" class="table table-striped-columns">
                        <!-- head -->
                        <thead>
                        <tr>
                            <th>Nome arquivo</th>
                            <th>Data</th>
                            <th>Tipo</th>
                            <th>Trecho</th>
                            <th>Ações</th>
                        </tr>
                        </thead>
                        <tbody>
                        <!-- row 1 -->
        
                            @foreach ($resultados2 as $resultado)
                                    <tr>
                                        <td>{{ $resultado->nome }}</td>
                                        <td>{{ $resultado->data }}</td>
                                        <td>{{ $resultado->tipo }}</td>
                                        <td>{{ $resultado->trecho }}</td>
                                        <td>
                                            <a href="show/{{$resultado->id}}" class="btn btn-active btn-success btn-sm">Ver</a>
                                            @if($resultado->anexo != null)
                                                <a href="{{ asset('arquivos/' . $resultado->anexo) }}" 
                                                    target="_blank" class="btn btn-active btn-success btn-sm">Acessar PDF</a>
                                            @endif
                                            <a href="edit/{{$resultado->id}}" class="btn btn-active btn-success btn-sm">Editar</a>
                                            <form action="delete/{{$resultado->id}}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-active btn-accent btn-sm"
                                                style="width: 110px" > Remover</button>
                                            </form>
                                        </td>
                                    </tr>
                            @endforeach
        
                        </tbody>
                    </table>
                @endif


            <div class="col-8">
                <h4 class="h4">Todos os arquivos</h4>
            </div>
            <div class="col-md-12 dashboard-avs-container">
                @if (isset($resultados) && count($resultados) > 0)

                    <table id="minhaTabela2" class="display nowrap">
                        <!-- head -->
                        <thead>
                        <tr>
                            <th>Nome arquivo</th>
                            <th>Data</th>
                            <th>Tipo</th>
                            <th>Ações</th>
                        </tr>
                        </thead>
                        <tbody>
                        <!-- row 1 -->
        
                            @foreach ($resultados as $resultado)
                                    <tr>
                                        <td>{{ $resultado->nome }}</td>
                                        <td>{{ date('d/m/Y', strtotime($resultado->data)) }}</td>
                                        <td>{{ $resultado->tipo }}</td>
                                        <td>
                                            <a href="show/{{$resultado->id}}" class="btn btn-active btn-success btn-sm">Ver</a>
                                            @if($resultado->anexo != null)
                                                <a href="{{ asset('arquivos/' . $resultado->anexo) }}" 
                                                    target="_blank" class="btn btn-active btn-success btn-sm">Acessar PDF</a>
                                            @endif
                                            <a href="edit/{{$resultado->id}}" class="btn btn-active btn-success btn-sm">Editar</a>
                                            <form action="delete/{{$resultado->id}}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-active btn-accent btn-sm"
                                                style="width: 110px" > Remover</button>
                                            </form>
                                        </td>
                                    </tr>
                            @endforeach
        
                        </tbody>
                    </table>
                @else
                    <p>Nenhum resultado encontrado.</p>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection
@section('javascript')
    <script type="text/javascript">

        $(document).ready(function(){
            $('#minhaTabela').DataTable({
                    scrollY: 200,
                    "language": {
                        "lengthMenu": "Mostrando _MENU_ registros por página",
                        "zeroRecords": "Nada encontrado",
                        "info": "Mostrando página _PAGE_ de _PAGES_",
                        "infoEmpty": "Nenhum registro disponível",
                        "infoFiltered": "(filtrado de _MAX_ registros no total)"
                    }
                });
        });

        $(document).ready(function(){
            $('#minhaTabela2').DataTable({
                    scrollY: 500,
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