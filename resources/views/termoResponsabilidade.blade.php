<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

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
    <title>Document</title>
</head>
<body>
    <header class="p-3 mb-3 border-bottom">
        <div class="container">
            <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
                <a href="/" class="d-flex align-items-center mb-2 mb-lg-0 text-dark text-decoration-none">
                <img src="{{asset('/img/1.png')}}" alt="Paranacidade" width="100" height="72">
                </a>

                <ul class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0">
                <p class="tituloSistema justify-content-center mb-md-0">Sistema de Controle de Viagens</p></li>
                </ul>

                        
            </div>
        </div>
    </header>

    <main data-theme="emerald">
        <div class="container text-center">
            <h1 style="font-size: 24px"><strong>Termo de responsabilidade para uso do Sistema</strong></h1>
            <br>
            <div class="col-md-6 offset-md-3">
                <p style="text-align: justify; color: black">
                    Ao assinar o presente documento, afirmo que estou ciente da responsabilidade de uso do Sistema de Controle de Viagens e comprometo-me a 
                    prestar informações corretas que refletem a realidade, atendendo aos princípios constitucionais de legalidade, impessoalidade, moralidade, 
                    publicidade e eficiência. Afirmo também que a senha para acesso do sistema será utilizada exclusivamente por mim e os atos e fatos administrativos 
                    que sejam consequência do seu uso serão de minha responsabilidade.</p>
            </div>
            <br>
            <div class="col-md-6 offset-md-5">
                <div class="flex flex-row">
                    <form action="/aprovarTermoResponsabilidade" method="POST" enctype="multipart/form-data" style="padding-right: 20px">
                        @csrf
                        @method('PUT')
                            <button type="submit" class="btn btn-active btn-success">Assinar</button>
                    </form>
                    
                    <form action="/logout" method="POST">
                        @csrf
                        <a class="btn btn-active btn-error" href="/logout" 
                        onclick="event.preventDefault();
                        this.closest('form').submit();">
                        Cancelar
                        </a>
                    </form>
                    
                </div>
            </div>
        </div>
    </main>



    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
    <link rel="stylesheet" href="{{asset('/css/styles.css')}}">
</body>
</html>