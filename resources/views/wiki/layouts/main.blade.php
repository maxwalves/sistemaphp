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

        <script src="//js.nicedit.com/nicEdit-latest.js" type="text/javascript"></script>
        <script type="text/javascript">bkLib.onDomLoaded(nicEditors.allTextAreas);</script>
                
    {{-- -------------------------------------------------------------------------------------------------------------------------------------------- --}}

    <title>Wiki de Normas - Paranacidade</title>

    {{-- -------------------------------------------------------------------------------------------------------------------------------------------- --}}
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
                        <p class="tituloSistema justify-content-center mb-md-0">DSS - Wiki de Normas - Paranacidade</p></li>
                        </ul>
                                
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
                                <a class="btn btn-active btn-warning rounded-none" href="/">Sistema de Viagens</a>
                                </li>
                                <li class="nav-item">
                                    <a class="btn btn-active btn-success rounded-none" href="/wiki">Início</a>
                                    </li>
                                </li>
                                <li class="nav-item">
                                    <a class="btn btn-active btn-success rounded-none" href="/normas">Normas</a>
                                </li>
                                <li class="nav-item">
                                    <a class="btn btn-active btn-success rounded-none" href="/instrucoesNormativas">Instruções Normativas</a>
                                </li>
                                <li class="nav-item">
                                    <a class="btn btn-active btn-success rounded-none" href="/legislacao">Legislação</a>
                                </li>
                                <li class="nav-item">
                                    <a class="btn btn-active btn-success rounded-none" href="/normasGestao">Normas de Gestão</a>
                                </li>
                                <li class="nav-item">
                                    <a class="btn btn-active btn-success rounded-none" href="/admin">Admin</a>
                                </li>
                                
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
        

        @hasSection ('javascript')
            @yield('javascript')
        @endif
    </body>

    
</html>
