<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

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

                        <div class="dropdown text-end">
                            <a href="#" class="d-block link-dark text-decoration-none dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                <img src="{{asset('/img/user.png')}}" alt="mdo" width="42" height="42" class="rounded-circle">
                                {{$user->name}}
                            </a>
                            <ul class="dropdown-menu text-small">

                                @auth
                                    
                                    
                                
                                @can('view-users', $user)
                                    <li><a class="dropdown-item" href="/users/users">Gerenciar usuários</a></li>
                                    <li><a class="dropdown-item" href="/veiculosParanacidade/veiculosParanacidade">Veículos Paranacidade</a></li>
                                    <li><a class="dropdown-item" href="/objetivos/objetivos">Objetivos de viagem</a></li>
                                @endcan
                                @can('aprov-avs-frota', $user)
                                    <li><a class="dropdown-item" href="/objetivos/objetivos">Objetivos de viagem</a></li>
                                    <li><a class="dropdown-item" href="/veiculosParanacidade/veiculosParanacidade">Veículos Paranacidade</a></li>
                                @endcan

                                    <li><a class="dropdown-item" href="/veiculosProprios/veiculosProprios">Meus veículos</a></li>

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

                                </li>
                                <li class="nav-item">
                                <a class="btn btn-active btn-success rounded-none" href="/avs/avs">Autorizações de viagem</a>
                                </li>
                                <li class="nav-item">
                                <a class="btn btn-active btn-success rounded-none" href="/avs/prestacaoContasUsuario">Prestação de contas</a>
                                </li>
                                <li class="nav-item" hidden>
                                <a class="btn btn-active btn-success rounded-none" href="#">Relatórios gerenciais</a>
                                </li>
                                @can('aprov-avs-gestor', $user)
                                    <li class="nav-item">
                                        <a class="btn btn-active btn-warning rounded-none" href="/avs/autGestor">AV pendentes Gestor</a>
                                    </li>
                                @endcan
                                @can('aprov-avs-diretoria', $user)
                                    <li class="nav-item">
                                        <a class="btn btn-active btn-warning rounded-none" href="/avs/autDiretoria">AV pendentes DAF</a>
                                    </li>
                                @endcan
                                @can('aprov-avs-secretaria', $user)
                                    <li class="nav-item">
                                        <a class="btn btn-active btn-warning rounded-none" href="/avs/autSecretaria">AV pendentes CAD</a>
                                    </li>
                                @endcan
                                @can('aprov-avs-financeiro', $user)
                                    <li class="nav-item">
                                        <a class="btn btn-active btn-warning rounded-none" href="/avs/autFinanceiro">AV pendentes CFI</a>
                                    </li>
                                @endcan
                                @can('aprov-avs-financeiro', $user)
                                    <li class="nav-item">
                                        <a class="btn btn-active btn-warning rounded-none" href="/avs/autPcFinanceiro">PC pendentes CFI</a>
                                    </li>
                                @endcan
                                @can('aprov-avs-gestor', $user)
                                    <li class="nav-item">
                                        <a class="btn btn-active btn-warning rounded-none" href="/avs/autPcGestor">PC pendentes Gestor</a>
                                    </li>
                                @endcan
                                @can('aprov-avs-financeiro', $user)
                                    <li class="nav-item">
                                        <a class="btn btn-active btn-warning rounded-none" href="/avs/acertoContasFinanceiro">Acertos de Contas pendentes CFI</a>
                                    </li>
                                @endcan

                                @php
                                    use Spatie\Permission\Models\Permission;
                                    $permission1 = Permission::where('name', 'aprov avs diretoria')->first();
                                    $permission2 = Permission::where('name', 'aprov avs secretaria')->first();
                                    $permission3 = Permission::where('name', 'aprov avs gestor')->first();   
                                @endphp
                                
                                @can('aprov-avs-financeiro', $user)
                                    <li class="nav-item">
                                        <a class="btn btn-active btn-warning rounded-none" href="/avs/gerenciarAvs">GERENCIAR</a>
                                    </li>
                                @elseif($user->hasPermissionTo($permission1))
                                    <li class="nav-item">
                                        <a class="btn btn-active btn-warning rounded-none" href="/avs/gerenciarAvs">GERENCIAR</a>
                                    </li>
                                @elseif($user->hasPermissionTo($permission2))
                                    <li class="nav-item">
                                        <a class="btn btn-active btn-warning rounded-none" href="/avs/gerenciarAvs">GERENCIAR</a>
                                    </li>
                                @elseif($user->hasPermissionTo($permission3))
                                    <li class="nav-item">
                                        <a class="btn btn-active btn-warning rounded-none" href="/avs/gerenciarAvs">GERENCIAR</a>
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
                    @if(session('error'))
                        <div class="col-4">
                            <div class="alert alert-error shadow-lg" style="width: 70%">
                                <p > {{ session('error') }} </p>    
                            </div>
                        </div>
                    @endif
                    @yield('content')
                </div>
            </div>
        </main>

        @hasSection ('javascript')
            @yield('javascript')
        @endif
    </body>

    
</html>
