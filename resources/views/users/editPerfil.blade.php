@extends('adminlte::page')

@section('title', 'Editar Usuário')

@section('content_header')
    <h1>Editar Usuário</h1>
@stop

@section('content')

<div >
    <a href="/users/users/" type="submit" class="btn btn-active btn-warning"> Voltar!</a>
</div>

<div id="av-create-container" class="col-md-6 offset-md-3">
        <h2>Editando: {{ $usuarioEditar->name }}</h2>

        <form action="/users/update/{{ $usuarioEditar->id }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="id" class="control-label">Id</label>
                <div class="input-group">
                    <input type="text" class="form-control" disabled="true"
                    name="id" id="id" placeholder="Id" value="{{$usuarioEditar->id}}">
                </div>
               
            </div>

            <div class="form-group">
                <label for="name" class="control-label">Nome</label>
                <div class="input-group">
                    <input type="text" class="form-control" disabled="true"
                    name="name" id="name" placeholder="Nome" value="{{$usuarioEditar->name}}">
                </div>

            </div>

            <div class="form-group">
                <label for="username" class="control-label">Email</label>
                <input type="text" class="form-control" disabled="true"
                name="username" id="username" placeholder="Email" value="{{$usuarioEditar->username}}">

            </div>

            <div class="divider"></div> 
            <div style="border-bottom: 2px; border-color: black">
                <p><strong>Perfis que usuário possui:</strong> </p>
                
                @if ($dados["permission1"] == 'true')
                    <a href="/users/editPerfil/{{ $usuarioEditar->id }}/desativarAdmin" class="btn btn-active btn-primary" id="btAdminPossui">Admin</a> 
                @endif
                @if ($dados["permission3"] == 'true')
                    <a href="/users/editPerfil/{{ $usuarioEditar->id }}/desativarGestor" class="btn btn-active btn-primary" id="btGestorPossui">Gestor</a> 
                @endif
                @if ($dados["permission4"] == 'true')
                    <a href="/users/editPerfil/{{ $usuarioEditar->id }}/desativarSecretaria" class="btn btn-active btn-primary" id="btSecretariaPossui">CAD</a> 
                @endif
                @if ($dados["permission5"] == 'true')
                    <a href="/users/editPerfil/{{ $usuarioEditar->id }}/desativarFinanceiro" class="btn btn-active btn-primary" id="btAdmFinanceiroPossui">CFI</a>
                @endif
                @if ($dados["permission7"] == 'true')
                    <a href="/users/editPerfil/{{ $usuarioEditar->id }}/desativarDiretoriaExecutiva" class="btn btn-active btn-primary" id="btDiretoriaExecutivaPossui">DAF</a>
                @endif
                
            </div>
            <div class="divider"></div> 
            <div>
                <p><strong>Perfis disponíveis no sistema:</strong></p>
                <p>Clique para adicionar.</p>
                @if ($dados["permission1"] == 'false')  
                    <a href="/users/editPerfil/{{ $usuarioEditar->id }}/ativarAdmin" class="btn btn-active btn-secondary" id="btAdminDisponivel">Admin</a>
                @endif
                @if ($dados["permission3"] == 'false')
                    <a href="/users/editPerfil/{{ $usuarioEditar->id }}/ativarGestor" class="btn btn-active btn-secondary" id="btGestorDisponivel">Gestor</a>
                @endif
                @if ($dados["permission4"] == 'false')
                    <a href="/users/editPerfil/{{ $usuarioEditar->id }}/ativarSecretaria" class="btn btn-active btn-secondary" id="btSecretariaDisponivel">CAD</a>
                @endif
                @if ($dados["permission5"] == 'false')
                    <a href="/users/editPerfil/{{ $usuarioEditar->id }}/ativarFinanceiro" class="btn btn-active btn-secondary" id="btAdmFinanceiroDisponivel">CFI</a>
                @endif
                @if ($dados["permission7"] == 'false')
                <a href="/users/editPerfil/{{ $usuarioEditar->id }}/ativarDiretoriaExecutiva" class="btn btn-active btn-secondary" id="btDiretoriaExecutivaDisponivel">DAF</a>
                @endif
                
            </div>
            <div class="divider"></div> 

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
    
            //Assim que a tela carrega, aciona automaticamente essas duas funções ------------------------
    $(function(){
        
    })
</script>

@stop