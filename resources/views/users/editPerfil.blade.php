<!DOCTYPE html>
<html lang="en">
<head>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>@yield('title')</title>

        <link rel="shortcut icon" type="imagex/png" href="{{asset('/img/aviao.png')}}">

        <style>
            @font-face {
              font-family: 'Roboto';
              src: url('{{ asset('Roboto/Roboto-Regular.ttf') }}') format('truetype');
            }
        </style>
        
        <!-- CSS Bootstrap -->
        <script src="{{asset('/js/bootstrap.bundle.min.js')}}"></script>
        <link href="{{asset('/css/bootstrap.min.css')}}" rel="stylesheet">

        <link href="{{asset('DataTables/datatables.min.css')}}" rel="stylesheet"/>
        <script src="{{asset('DataTables/datatables.min.js')}}"></script>

        <!-- CSS da aplicação -->

        <link rel="stylesheet" href="{{asset('/css/styles.css')}}">
        <script src="{{asset('/js/scripts.js')}}"></script>
        <link href="{{asset('/css/headers.css')}}" rel="stylesheet">

        <link href="{{asset('/css/sidebars.css')}}" rel="stylesheet">
        <script src="{{asset('/js/sidebars.js')}}"></script>

        <link href="https://cdn.jsdelivr.net/npm/daisyui@2.51.6/dist/full.css" rel="stylesheet" type="text/css" />
        <script src="https://cdn.tailwindcss.com"></script>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.6.5/flowbite.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.6.5/datepicker.min.js"></script>

        <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
        <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
        <link rel="stylesheet" href="{{asset('/css/styles.css')}}">
        <script src="{{asset('/js/moment.js')}}"></script>
                
            
    </head>
</head>
<body>
    <main data-theme="emerald">
        <header class="p-3 mb-3 border-bottom">
            <div class="container">
                <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
                    <a href="/" class="d-flex align-items-center mb-2 mb-lg-0 text-dark text-decoration-none">
                    <img src="{{asset('/img/1.png')}}" alt="Paranacidade" width="100" height="72">
                    </a>

                    <ul class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0">
                    <p class="tituloSistema justify-content-center mb-md-0">Sistema de Controle de Viagens</p></li>
                    </ul>

                    <div class="dropdown text-end">
                        <a href="#" class="d-block link-dark text-decoration-none dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                            <img src="{{asset('/img/user.png')}}" alt="mdo" width="42" height="42" class="rounded-circle">
                            {{$user->name}}
                        </a>
                        <ul class="dropdown-menu text-small">

                            @auth
                                
                                
                            
                            @can('view-users', $user)
                                <li><a class="dropdown-item" href="/users/users">Gerenciar usuários</a></li>
                            @endcan

                                <li><hr class="dropdown-divider"></li>
                                
                                <li>
                                    <form action="/logout" method="POST">
                                        @csrf
                                        <a class="dropdown-item" href="/logout" 
                                        onclick="event.preventDefault();
                                        this.closest('form').submit();">
                                        Sair
                                        </a>
                                    </form>
                                </li>
                            @endauth
                            
                        </ul>
                    </div>
                            
                </div>
            </div>
            <nav class="navbar navbar-expand-lg bg-light rounded border" aria-label="Eleventh navbar example">
                <div class="container">
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarsExample09" aria-controls="navbarsExample09" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                    </button>

                    <div class="collapse navbar-collapse" id="navbarsExample09">
                        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                            <li class="nav-item">
                            <a class="btn btn-active btn-warning rounded-none" href="/">Voltar Início</a>
                            </li>
                            <li class="nav-item">
                                <a class="btn btn-active btn-primary rounded-none" href="/avs/avs">Sistema de Viagens</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
        </header>

        <div style="padding-left: 50px, padding-right: 50px" class="container">
            <div class="row justify-content-between" >
                
                <div class="col-4">
                    <a href="/users/users/" type="submit" class="btn btn-active btn-ghost"> Voltar!</a>
                </div>
            </div>
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


        
    </main>
</body>
</html>


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



