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
                    <p class="tituloSistema justify-content-center mb-md-0">Sistema Integrado Paranacidade</p></li>
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
                            <li class="nav-item">
                                <a class="btn btn-active btn-warning rounded-none" href="/wiki">Sistema de Normas</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
        </header>
        <div class="col-md-10 offset-md-1 dashboard-avs-container">
            @if(count($users) > 0 )
            <h3> <strong> Usuários do sistema </strong></h3>
            <table id="tabelaRota" class="display nowrap" style="width:100%">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Nome</th>
                        <th>E-mail</th>
                        <th>Gerente</th>
                        <th>Setor</th>
                        <th>Departamento</th>
                        <th>Número</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $userLinha)
                    <tr>
                        <td> {{$userLinha->id}} </td>
                        <td> {{$userLinha->name}} </td>
                        <td> {{$userLinha->username}} </td>
                        <td> {{$userLinha->manager}} </td>
                        <td> {{$userLinha->nomeSetor}} </td>
                        <td> {{$userLinha->department}} </td>
                        <td> {{$userLinha->employeeNumber}} </td>
                        <td> 
                            <a href="/users/editPerfil/{{ $userLinha->id }}" class="btn btn-success btn-sm"> Gerenciar perfil</a> 
                            
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <p>Você ainda não tem usuários, <a href="/users/create"> Criar novo usuário</a></p>
            @endif
            <div class="row">
                <div class="col-12 col-xl-4">
                    <a class="btn btn-success btn-lg" href="/users/sincronizarGerentes">Sincronizar gerentes</a>
                </div>
                <div class="col-12 col-xl-4">
                    <a class="btn btn-success btn-lg" href="/users/sincronizarSetores">Sincronizar setores</a>
                </div>
            </div>
            
        </div>
    </main>
</body>
</html>


<script type="text/javascript">

        $(document).ready(function(){
            $('#tabelaRota').DataTable({
                    scrollY: 500,
                    "language": {
                        "lengthMenu": "Mostrando _MENU_ registros por página",
                        "zeroRecords": "Nada encontrado",
                        "info": "Mostrando página _PAGE_ de _PAGES_",
                        "infoEmpty": "Nenhum registro disponível",
                        "infoFiltered": "(filtrado de _MAX_ registros no total)",
                        "search": "Procure uma AV"
                    }
                });
        });

</script>
