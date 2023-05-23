<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>@yield('title')</title>

        <link rel="shortcut icon" type="imagex/png" href="{{asset('/img/aviao.png')}}">

        <link href="https://fonts.googleapis.com/css2?family=Roboto" rel="stylesheet">

        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
        <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
        
        <!-- CSS Bootstrap -->
        <script src="{{asset('/js/bootstrap.bundle.min.js')}}"></script>
        <link href="{{asset('/css/bootstrap.min.css')}}" rel="stylesheet">
  
        
        
        <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.css" />
        <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.js"></script>
        <script src="//cdn.datatables.net/1.10.15/js/jquery.dataTables.min.js"></script>
        
        <link href="https://cdn.jsdelivr.net/npm/daisyui@2.51.6/dist/full.css" rel="stylesheet" type="text/css" />
        <script src="https://cdn.tailwindcss.com"></script>

        <!-- CSS da aplicação -->

        <link rel="stylesheet" href="{{asset('/css/styles.css')}}">
        <script src="{{asset('/js/scripts.js')}}"></script>
        <link href="{{asset('/css/headers.css')}}" rel="stylesheet">
        <link rel="canonical" href="https://getbootstrap.com/docs/5.3/examples/headers/">
        <link rel="canonical" href="https://getbootstrap.com/docs/5.3/examples/sidebars/">
        <link href="{{asset('/css/sidebars.css')}}" rel="stylesheet">
        <script src="{{asset('/js/sidebars.js')}}"></script>

        {{-- Tailwind CSS - Flowbite --}}
        <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.6.5/flowbite.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.6.5/datepicker.min.js"></script>
                
            
    </head>
    <body  >
        <!-- HEADER MODELO BOOTSTRAP-->
        <header class="p-3 mb-3 border-bottom">
                <div class="container">
                    <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
                        <a href="/" class="d-flex align-items-center mb-2 mb-lg-0 text-dark text-decoration-none">
                        <img src="{{asset('/img/1.png')}}" alt="Paranacidade" width="100" height="72">
                        </a>

                        <ul class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0">
                        <p class="tituloSistema justify-content-center mb-md-0">Sistema de Controle de Viagens</p></li>
                        </ul>

                        <div class="navbarMenu">
                            <ul class="navbar-nav me-auto mb-2 mb-lg-0">

                                @guest
                                <li class="nav-item">
                                <a class="nav-link" href="/login">Login</a>
                                </li>
                                <li class="nav-item">
                                <a class="nav-link" href="/register">Registre-se</a>
                                </li>
                                @endguest
                            </ul>
                        </div>

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
                                
                                    
                                    <li><a class="dropdown-item" href="/veiculosProprios/veiculosProprios">Meus veículos</a></li>
                                    <li><a class="dropdown-item" href="/veiculosParanacidade/veiculosParanacidade">Veículos Paranacidade</a></li>
                                    <li><a class="dropdown-item" href="/objetivos/objetivos">Objetivos de viagem</a></li>
                                    <li><a class="dropdown-item" href="#">Configurações</a></li>
                                    <li><a class="dropdown-item" href="#">Meu perfil</a></li>
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

                <div class="espaco"></div>

                <!-- NAVBAR COM OPÇÕES -->
                <nav class="navbar navbar-expand-lg bg-light rounded border" aria-label="Eleventh navbar example">
                    <div class="container-fluid">
                        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarsExample09" aria-controls="navbarsExample09" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                        </button>

                        <div class="collapse navbar-collapse" id="navbarsExample09">
                            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                                <li class="nav-item">
                                <a class="btn btn-active btn-success rounded-none" href="/">Início</a>
                                </li>
                                </li>
                                <li class="nav-item">
                                <a class="btn btn-active btn-success rounded-none" href="/avs/avs">Autorizações de viagem</a>
                                </li>
                                <li class="nav-item">
                                <a class="btn btn-active btn-success rounded-none" href="/avs/prestacaoContasUsuario">Prestação de contas</a>
                                </li>
                                <li class="nav-item">
                                <a class="btn btn-active btn-success rounded-none" href="#">Relatórios gerenciais</a>
                                </li>
                                @can('aprov-avs-gestor', $user)
                                    <li class="nav-item">
                                        <a class="btn btn-active btn-accent rounded-none" style="border-width: 2px; border-color: black" href="/avs/autGestor">Autorizações de AV pendentes Gestor</a>
                                    </li>
                                @endcan
                                @can('aprov-avs-diretoria', $user)
                                    <li class="nav-item">
                                        <a class="btn btn-active btn-accent rounded-none" href="/avs/autDiretoria">Autorizações de AV pendentes Diretoria</a>
                                    </li>
                                @endcan
                                @can('aprov-avs-secretaria', $user)
                                    <li class="nav-item">
                                        <a class="btn btn-active btn-accent bg-info rounded-none" href="/avs/autSecretaria">Autorizações de AV pendentes Secretaria</a>
                                    </li>
                                @endcan
                                @can('aprov-avs-financeiro', $user)
                                    <li class="nav-item">
                                        <a class="btn btn-active btn-accent rounded-none" style="border-width: 2px; border-color: black" href="/avs/autFinanceiro">Autorizações de AV pendentes Financeiro</a>
                                    </li>
                                @endcan
                                @can('aprov-avs-frota', $user)
                                    <li class="nav-item">
                                        <a class="btn btn-active btn-accent rounded-none" style="border-width: 2px; border-color: black" href="/avs/autAdmFrota">Autorizações de AV pendentes Adm Frota</a>
                                    </li>
                                @endcan
                                @can('aprov-avs-financeiro', $user)
                                    <li class="nav-item">
                                        <a class="btn btn-active btn-accent rounded-none" style="border-width: 2px; border-color: black" href="/avs/autPcFinanceiro">Prestações de contas pendentes Financeiro</a>
                                    </li>
                                @endcan
                                @can('aprov-avs-gestor', $user)
                                    <li class="nav-item">
                                        <a class="btn btn-active btn-accent rounded-none" style="border-width: 2px; border-color: black" href="/avs/autPcGestor">Prestações de contas pendentes Gestor</a>
                                    </li>
                                @endcan
                                @can('aprov-avs-financeiro', $user)
                                    <li class="nav-item">
                                        <a class="btn btn-active btn-accent rounded-none" style="border-width: 2px; border-color: black" href="/avs/acertoContasFinanceiro">Acertos de Contas pendentes</a>
                                    </li>
                                @endcan

                            </ul>
                        </div>
                    </div>
                </nav>
                
        </header>    
        
        <main data-theme="emerald">
            <div class="container-fluid">
                <div class="row">
                    @if(session('msg'))
                        <div class="col-4">
                            <div class="alert alert-info shadow-lg" style="width: 70%">
                                <p > {{ session('msg') }} </p>    
                            </div>
                        </div>
                    @endif
                    @yield('content')
                </div>
            </div>
        </main>

        <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
        <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
        <link rel="stylesheet" href="{{asset('/css/styles.css')}}">

        

        {{--
        <script src="{‌{ asset('js/app.js') }}" type="text/javascript"></script>
        <script src="https://code.jquery.com/jquery-3.4.1.js" integrity="sha256-WpOohJOqMqqyKL9FccASB9O0KwACQJpFTUBLTYOVvVU=" crossorigin="anonymous"></script>
        
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>

        <script src="{‌{ asset('js/app.js') }}" type="text/javascript"></script>
        
        <script src="//code.jquery.com/jquery-3.2.1.min.js"></script>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
        
        {{-- -------------------------------------- --}}
        <!-- Fonte do Google -->
        

        @hasSection ('javascript')
            @yield('javascript')
        @endif
    </body>

    
</html>
