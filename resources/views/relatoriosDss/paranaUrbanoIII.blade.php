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
        
        <script src="{{asset('/js/scripts.js')}}"></script>
        <link href="{{asset('/css/headers.css')}}" rel="stylesheet">

        <link href="{{asset('/css/sidebars.css')}}" rel="stylesheet">
        <script src="{{asset('/js/sidebars.js')}}"></script>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.6.5/flowbite.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.6.5/datepicker.min.js"></script>

        <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
        <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
        <link rel="stylesheet" href="{{asset('/css/styles.css')}}">
        <script src="{{asset('/js/moment.js')}}"></script>
        <style>
                .teste {
                    min-height: 100vh;
                }
        </style>
            
    </head>
</head>
<body>
    <div class="teste" data-theme="emerald">
        <header class="p-3 mb-3 border-bottom">
            <div class="container">
                <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
                    <a href="/" class="d-flex align-items-center mb-2 mb-lg-0 text-dark text-decoration-none">
                    <img src="{{asset('/img/1.png')}}" alt="Paranacidade" width="100" height="72">
                    </a>

                    <ul class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0">
                    <p class="tituloSistema justify-content-center mb-md-0">Paraná Urbano III</p></li>
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
        </header>
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
                            <a class="btn btn-active btn-primary rounded-none" href="/relatoriosDss/paranaUrbanoIII/">Gerenciar programa</a>
                        </li>
                        <li class="nav-item">
                            <a class="btn btn-active btn-primary rounded-none" href="/relatoriosDss/parametros/">Parâmetros do sistema</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        <br>
        <div class="container">
            <div class="row">
                <div class="col-3">
                    <select class="form-select" aria-label="Default select example" onChange="mostrarTabelaConformeSelecao()">
                        <option selected>Selecione um componente</option>
                        <option value="comp1">COMP 1. MODERNIZAÇÃO DA GESTÃO MUNICIPAL</option>
                        <option value="comp2">COMP 2. INFRAESTRUTURA</option>
                        <option value="comp3">COMP 3. FORTALECIMENTO do SFM</option>
                    </select>
                </div>
            </div>
        </div>
       
        <div id="comp1" class="container" style="display: none">
            <br>
            <p>
                   <strong>COMP 1. MODERNIZAÇÃO DA GESTÃO MUNICIPAL</strong> 
            </p>
            <br>
            <div id="divAba-escopo1">
				<table class="table table-striped table-hover table-bordered" id="tabela11">
					<thead>
						<tr>
							<th class="text-center" style="width: 5%"><small>Código PEP-POA</small></th>
                            <th class="text-center" style="width: 45%"><small>Nome PEP-POA</small></th>
                            <th class="text-center" style="width: 5%"><small>Código PMR</small></th>
                            <th class="text-center" style="width: 45%"><small>Nome PMR</small></th>
						</tr>
					</thead>
					<tbody>

                        <tr>
                            <td></td>
                            <td class="text-center">  
                               <strong>1.1. 32 municípios com população superior a 50.000 hab.</strong> 
                            </td>
                            <td></td>
                            <td></td>
                        </tr>

                        <tr>
                            <td class="text-center">010101</td>
                            <td class="text-center">  
                                Aprimoramento da gestão tributária financeira
                            </td>
                            <td class="text-center">1.1</td>
                            <td class="text-center">Cadastro Multifinalitário Atualizado  - G1</td>
                        </tr>

                        <tr>
                            <td class="text-center">010102</td>
                            <td class="text-center">  
                                Fortalecimento do planejamento e gestão urbana
                            </td>
                            <td class="text-center">1.3</td>
                            <td class="text-center">Plano Diretor Atualizado - G1</td>
                        </tr>

                        <tr>
                            <td class="text-center">010102</td>
                            <td class="text-center">  
                                Fortalecimento do planejamento e gestão urbana
                            </td>
                            <td class="text-center">1.5</td>
                            <td class="text-center">Plano Setorial (Saneamento e Mobilidade Urbana) - G1</td>
                        </tr>

                        <tr>
                            <td class="text-center">010103</td>
                            <td class="text-center">  
                                Modernização da área de governo eletrônico
                            </td>
                            <td class="text-center">1.7</td>
                            <td class="text-center">Serviços on line (IPTU, ISS, Consulta de Potencial Construtivo) - G1</td>
                        </tr>
                        <tr>
                            <td class="text-center">010104</td>
                            <td class="text-center">  
                                Capacitção de servidores
                            </td>
                            <td class="text-center">1.8</td>
                            <td class="text-center">Oficinas de Capacitação G1</td>
                        </tr>
                        
					</tbody>
				</table>
                <table class="table table-striped table-hover table-bordered" id="tabela12">
                    <thead>
						<tr>
							<th class="text-center" style="width: 5%"><small>Código PEP-POA</small></th>
                            <th class="text-center" style="width: 45%"><small>Nome PEP-POA</small></th>
                            <th class="text-center" style="width: 5%"><small>Código PMR</small></th>
                            <th class="text-center" style="width: 45%"><small>Nome PMR</small></th>
						</tr>
					</thead>
					<tbody>
                        <tr>
                            <td></td>
                            <td class="text-center">  
                                <strong>1.2. demais municípios</strong> 
                            </td>
                            <td></td>
                            <td></td>
                        </tr>

                        <tr>
                            <td class="text-center">010201</td>
                            <td class="text-center">  
                                Fortalecimento da gestão tributária
                            </td>
                            <td class="text-center">1.2</td>
                            <td class="text-center">Cadastro Fiscal Atualizado  - G2</td>
                        </tr>

                        <tr>
                            <td class="text-center">010202</td>
                            <td class="text-center">  
                                Fortalecimento do planejamento e gestão urbana - até 50 mil habitantes
                            </td>
                            <td class="text-center">1.4</td>
                            <td class="text-center">Plano Diretor Atualizado - G2</td>
                        </tr>

                        <tr>
                            <td class="text-center">010202</td>
                            <td class="text-center">  
                                Fortalecimento do planejamento e gestão urbana - até 50 mil habitantes
                            </td>
                            <td class="text-center">1.6</td>
                            <td class="text-center">Plano Setorial (Mobilidade Urbana) - G2</td>
                        </tr>

                        <tr>
                            <td class="text-center">010203</td>
                            <td class="text-center">  
                                Capacitação de servidores e conselheiros dos planos diretores
                            </td>
                            <td class="text-center">1.9</td>
                            <td class="text-center">Oficinas de Capacitação  G2</td>
                        </tr>
                    </tbody>
                </table>
			</div>
        </div>

        <div id="comp2" class="container" style="display: none">
            <br>
            <p>
                   <strong>COMP 2. INFRAESTRUTURA</strong> 
            </p>
            <br>
            <table class="table table-striped table-hover table-bordered" id="tabela21">
                <thead>
                    <tr>
                        <th class="text-center" style="width: 5%"><small>Código PEP-POA</small></th>
                        <th class="text-center" style="width: 45%"><small>Nome PEP-POA</small></th>
                        <th class="text-center" style="width: 5%"><small>Código PMR</small></th>
                        <th class="text-center" style="width: 45%"><small>Nome PMR</small></th>
                    </tr>
                </thead>
                <tbody>

                    <tr>
                        <td></td>
                        <td class="text-center">  
                           <strong>2.1. 32 municípios com população superior a 50.000 hab.</strong>
                        </td>
                        <td></td>
                        <td></td>
                    </tr>

                    <tr>
                        <td class="text-center">020101</td>
                        <td class="text-center">  
                            Desenvolvimento urbano integrado
                        </td>
                        <td class="text-center">2.1</td>
                        <td class="text-center">Pavimentação de Vias Urbanas - G1</td>
                    </tr>

                    <tr>
                        <td class="text-center">020102</td>
                        <td class="text-center">  
                            Mobilidade urbana - acima de 50 mil habitantes
                        </td>
                        <td class="text-center">2.3</td>
                        <td class="text-center">Equipamento Urbano (praça, unidade esportiva, parque e terminal rodoviário) - G1</td>
                    </tr>

                    <tr>
                        <td class="text-center">020103</td>
                        <td class="text-center">  
                            Apoio social integrado - acima de 50 mil habitantes
                        </td>
                        <td class="text-center">2.5</td>
                        <td class="text-center">Unidade básica de Saúde - G1</td>
                    </tr>

                    <tr>
                        <td class="text-center">020104</td>
                        <td class="text-center">  
                            Apoio social integrado - acima de 50 mil habitantes
                        </td>
                        <td class="text-center">2.7</td>
                        <td class="text-center">Centro Municipal de Educacao - G1</td>
                    </tr>
                    <tr>
                        <td class="text-center">020105</td>
                        <td class="text-center">  
                            Esporte e Lazer - acima de 50 mil habitantes
                        </td>
                        <td class="text-center">2.3</td>
                        <td class="text-center">Equipamento Urbano (praça, unidade esportiva, parque e terminal rodoviário) - G1</td>
                    </tr>
                    
                </tbody>
            </table>

            <table class="table table-striped table-hover table-bordered" id="tabela22">
                <thead>
                    <tr>
                        <th class="text-center" style="width: 5%"><small>Código PEP-POA</small></th>
                        <th class="text-center" style="width: 45%"><small>Nome PEP-POA</small></th>
                        <th class="text-center" style="width: 5%"><small>Código PMR</small></th>
                        <th class="text-center" style="width: 45%"><small>Nome PMR</small></th>
                    </tr>
                </thead>
                <tbody>

                    <tr>
                        <td></td>
                        <td class="text-center">  
                           <strong>2.2. demais municípios</strong>
                        </td>
                        <td></td>
                        <td></td>
                    </tr>

                    <tr>
                        <td class="text-center">020201</td>
                        <td class="text-center">  
                            Requalificação urbana
                        </td>
                        <td class="text-center">2.2</td>
                        <td class="text-center">Pavimentação de Vias Urbanas - G2</td>
                    </tr>

                    <tr>
                        <td class="text-center">020202</td>
                        <td class="text-center">  
                            Mobilidade urbana - até 50 mil habitantes
                        </td>
                        <td class="text-center">2.4</td>
                        <td class="text-center">Equipamento Urbano (praça, unidade esportiva, parque e terminal rodoviário) - G2</td>
                    </tr>

                    <tr>
                        <td class="text-center">020203</td>
                        <td class="text-center">  
                            Projetos ambientais - até 50 mil habitantes
                        </td>
                        <td class="text-center">2.4</td>
                        <td class="text-center">Equipamento Urbano (praça, unidade esportiva, parque e terminal rodoviário) - G2</td>
                    </tr>

                    <tr>
                        <td class="text-center">020204</td>
                        <td class="text-center">  
                            Apoio social integrado - até 50 mil habitantes
                        </td>
                        <td class="text-center">2.6</td>
                        <td class="text-center">Unidade básica de Saúde - G2</td>
                    </tr>

                    <tr>
                        <td class="text-center">020204</td>
                        <td class="text-center">  
                            Apoio social integrado - até 50 mil habitantes
                        </td>
                        <td class="text-center">2.8</td>
                        <td class="text-center">Centro Municipal de Educacao - G2</td>
                    </tr>

                    <tr>
                        <td class="text-center">020205</td>
                        <td class="text-center">  
                            Esporte e Lazer - até 50 mil habitantes
                        </td>
                        <td class="text-center">2.4</td>
                        <td class="text-center">Equipamento Urbano (praça, unidade esportiva, parque e terminal rodoviário) - G2</td>
                    </tr>
                    
                </tbody>
            </table>
        </div>

        <div id="comp3" class="container" style="display: none">
            <br>
            <p>
                   <strong>COMP 3. FORTALECIMENTO do SFM</strong> 
            </p>
            <br>
            <table class="table table-striped table-hover table-bordered" id="tabela31">
                <thead>
                    <tr>
                        <th class="text-center" style="width: 5%"><small>Código PEP-POA</small></th>
                        <th class="text-center" style="width: 45%"><small>Nome PEP-POA</small></th>
                        <th class="text-center" style="width: 5%"><small>Código PMR</small></th>
                        <th class="text-center" style="width: 45%"><small>Nome PMR</small></th>
                    </tr>
                </thead>
                <tbody>

                    <tr>
                        <td class="text-center">0301</td>
                        <td class="text-center">  
                            Atualização das bases cartográficas urbanas digitais
                        </td>
                        <td class="text-center">3.1</td>
                        <td class="text-center">Bases Cartográficas Urbanas Digitais </td>
                    </tr>

                    <tr>
                        <td class="text-center">0302</td>
                        <td class="text-center">  
                            Apoio institucional em gestão urbana ao Projeto PUIII
                        </td>
                        <td class="text-center">3.5</td>
                        <td class="text-center">Sistema de Gestão de Carteira de Projetos</td>
                    </tr>

                    <tr>
                        <td class="text-center">0303</td>
                        <td class="text-center">  
                            Revisão dos critérios econômicos de elegibilidade
                        </td>
                        <td class="text-center">3.5</td>
                        <td class="text-center">Sistema de Gestão de Carteira de Projetos</td>
                    </tr>

                    <tr>
                        <td class="text-center">0304</td>
                        <td class="text-center">  
                            Aperfeiçoamento do sistema de classificação de risco municipal
                        </td>
                        <td class="text-center">3.3</td>
                        <td class="text-center">Sistema de Classificação de Riscos dos Municípios</td>
                    </tr>

                    <tr>
                        <td class="text-center">0305</td>
                        <td class="text-center">  
                            Desenvolvimento de novos mecanismos de financiamento municipal
                        </td>
                        <td class="text-center">3.4</td>
                        <td class="text-center">Novos Mecanismos de Financiamento a Projetos Municipais</td>
                    </tr>

                    <tr>
                        <td class="text-center">0306</td>
                        <td class="text-center">  
                            Modernização do sistema de gestão de carteira de projetos
                        </td>
                        <td class="text-center">3.5</td>
                        <td class="text-center">Sistema de Gestão de Carteira de Projetos</td>
                    </tr>

                    <tr>
                        <td class="text-center">0307</td>
                        <td class="text-center">  
                            Implantação de sistema de diagnóstico de infraestrutura urbana
                        </td>
                        <td class="text-center">3.2</td>
                        <td class="text-center">Sistema de Diagnóstico de Infraestrutura e Serviços Públicos nos Municípios</td>
                    </tr>

                    <tr>
                        <td class="text-center">0308</td>
                        <td class="text-center">  
                            Avaliação e monitoramento do Programa
                        </td>
                        <td class="text-center">3.5</td>
                        <td class="text-center">Sistema de Gestão de Carteira de Projetos</td>
                    </tr>
                    
                </tbody>
            </table>

        </div>
    </div>
</body>
<script>

    
    function mostrarTabelaConformeSelecao() {
        var select = document.querySelector('.form-select');
        var divIds = ['comp1', 'comp2', 'comp3'];

        // Oculta todas as divs
        for (var i = 0; i < divIds.length; i++) {
            var div = document.getElementById(divIds[i]);
            if (div) {
                div.style.display = 'none';
            }
        }

        // Obtém o valor selecionado no campo select
        var selectedValue = select.value;

        // Exibe a div correspondente ao componente selecionado
        var divToShow = document.getElementById(selectedValue);
        if (divToShow) {
            divToShow.style.display = 'block';
        }
    }
</script>

</html>


