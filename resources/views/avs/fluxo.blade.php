@extends('adminlte::page')

@section('title', 'Fluxo')

@section('content_header')
    <h1>Fluxo</h1>
@stop

@section('content')
    

<div class="row justify-content-start" style="padding-left: 5%">
    <div class="col-3">
        <a href="/avs/avs/" type="submit" class="btn btn-active btn-warning"> Voltar!</a>
    </div>
</div>
<div id="av-create-container" class="col-md-10 offset-md-1">
        <h1 style="font-size: 24px"><strong>Autorização de viagem nº:</strong> {{ $av->id }}</h1>
        <h1 style="font-size: 24px"><strong>Status atual:</strong> {{ $av->status }}</h1>
        <h1 style="font-size: 24px"><strong>Trajeto: </strong></h1>


        <div class="col-md-6 offset-md-3">
            <table class="table w-full">
              <!-- head -->
              <thead>
                <tr>
                  <th>Rota</th>
                  <th>Cidade Origem</th>
                  <th>Cidade Destino</th>
                </tr>
              </thead>
              <tbody>
                <!-- row 1 -->

                @for($i = 0; $i < count($av->rotas); $i++)

                        <tr>
                            <th>Rota: {{$i+1}}</th>
                            <td>
                                @if($av->rotas[$i]->isViagemInternacional == 0)
                                    <strong>{{$av->rotas[$i]->cidadeOrigemNacional}}</strong> 
                                @endif
                                
                                @if($av->rotas[$i]->isViagemInternacional == 1)
                                    <strong>{{$av->rotas[$i]->cidadeOrigemInternacional}}</strong> 
                                @endif
                            </td>
                            <td>
                                @if($av->rotas[$i]->isViagemInternacional == 0)
                                    <strong>{{$av->rotas[$i]->cidadeDestinoNacional}}</strong> 
                                @endif
                                
                                @if($av->rotas[$i]->isViagemInternacional == 1)
                                    <strong>{{$av->rotas[$i]->cidadeDestinoInternacional}}</strong>  
                                @endif
                            </td>
                        </tr>

                @endfor

              </tbody>
            </table>
            
          </div>

          <div class="col-md-12">

            <div class="timeline">

                <div class="time-label">
                    <span class="bg-red">Fases da realização da viagem</span>
                </div>


                <div>
                    @if ($av->isEnviadoUsuario == 1)
                        <i class="fas fa-caret-right bg-green"></i>
                        <div class="timeline-item">
                            <div class="timeline-header">
                                <a class="btn btn-success btn-lg" @readonly(true)>1 - Usuário - Preenchimento da AV</a>
                            </div>
                        </div>
                    @else
                        <i class="fas fa-caret-right bg-blue"></i>
                        <div class="timeline-item">
                            <div class="timeline-header">
                                <a class="btn btn-primary btn-md" @readonly(true)>1 - Usuário - Preenchimento da AV</a>
                            </div>
                        </div>
                    @endif
                </div>
                <div>
                    @if ($av->isAprovadoGestor == 1)
                        <i class="fas fa-caret-right bg-green"></i>
                        <div class="timeline-item">
                            <div class="timeline-header">
                                <a class="btn btn-success btn-lg" @readonly(true)>2 - Gestor - Avaliação inicial</a>
                            </div>
                        </div>
                    @else
                        <i class="fas fa-caret-right bg-blue"></i>
                        <div class="timeline-item">
                            <div class="timeline-header">
                                <a class="btn btn-primary btn-md" @readonly(true)>2 - Gestor - Avaliação inicial</a>
                            </div>
                        </div>
                    @endif
                </div>
                <div>
                    @if ($av->isVistoDiretoria == 1)
                        <i class="fas fa-caret-right bg-green"></i>
                        <div class="timeline-item">
                            <div class="timeline-header">
                                <a class="btn btn-success btn-lg" @readonly(true)>3 - DAF - Avalia pedido</a>
                                <span class="badge bg-warning float-right">Se carro particular ou viagem internacional</span>
                            </div>
                        </div>
                    @else
                        <i class="fas fa-caret-right bg-blue"></i>
                        <div class="timeline-item">
                            <div class="timeline-header">
                                <a class="btn btn-primary btn-md" @readonly(true)>3 - DAF - Avalia pedido</a>
                                <span class="badge bg-warning float-right">Se carro particular ou viagem internacional</span>
                            </div>
                        </div>
                    @endif
                </div>
                <div>
                    @if ($av->isRealizadoReserva == 1)
                        <i class="fas fa-caret-right bg-green"></i>
                        <div class="timeline-item">
                            <div class="timeline-header">
                                <a class="btn btn-success btn-lg" @readonly(true)>4 - CAD - Coordenadoria Administrativa - Realiza reservas</a>
                            </div>
                        </div>
                    @else
                        <i class="fas fa-caret-right bg-blue"></i>
                        <div class="timeline-item">
                            <div class="timeline-header">
                                <a class="btn btn-primary btn-md" @readonly(true)>4 - CAD - Coordenadoria Administrativa - Realiza reservas</a>
                            </div>
                        </div>
                    @endif
                </div>
                <div>
                    @if ($av->isAprovadoFinanceiro == 1)
                        <i class="fas fa-caret-right bg-green"></i>
                        <div class="timeline-item">
                            <div class="timeline-header">
                                <a class="btn btn-success btn-lg" @readonly(true)>4 - CFI - Coordenadoria Financeira - Adiantamento</a>
                            </div>
                        </div>
                    @else
                        <i class="fas fa-caret-right bg-blue"></i>
                        <div class="timeline-item">
                            <div class="timeline-header">
                                <a class="btn btn-primary btn-md" @readonly(true)>4 - CFI - Coordenadoria Financeira - Adiantamento</a>
                            </div>
                        </div>
                    @endif
                </div>
                <div>
                    @if ($av->isPrestacaoContasRealizada == 1)
                        <i class="fas fa-caret-right bg-green"></i>
                        <div class="timeline-item">
                            <div class="timeline-header">
                                <a class="btn btn-success btn-lg" @readonly(true)>5 - Viagem</a>
                            </div>
                        </div>
                    @else
                        <i class="fas fa-caret-right bg-blue"></i>
                        <div class="timeline-item">
                            <div class="timeline-header">
                                <a class="btn btn-primary btn-md" @readonly(true)>5 - Viagem</a>
                            </div>
                        </div>
                    @endif
                </div>
                <div>
                    @if ($av->isPrestacaoContasRealizada == 1)
                        <i class="fas fa-caret-right bg-green"></i>
                        <div class="timeline-item">
                            <div class="timeline-header">
                                <a class="btn btn-success btn-lg" @readonly(true)>6 - Usuário - Realiza PC</a>
                            </div>
                        </div>
                    @else
                        <i class="fas fa-caret-right bg-blue"></i>
                        <div class="timeline-item">
                            <div class="timeline-header">
                                <a class="btn btn-primary btn-md" @readonly(true)>6 - Usuário - Realiza PC</a>
                            </div>
                        </div>
                    @endif
                </div>
                <div>
                    @if ($av->isFinanceiroAprovouPC == 1)
                        <i class="fas fa-caret-right bg-green"></i>
                        <div class="timeline-item">
                            <div class="timeline-header">
                                <a class="btn btn-success btn-lg" @readonly(true)>7 - Financeiro - Avalia PC</a>
                            </div>
                        </div>
                    @else
                        <i class="fas fa-caret-right bg-blue"></i>
                        <div class="timeline-item">
                            <div class="timeline-header">
                                <a class="btn btn-primary btn-md" @readonly(true)>7 - Financeiro - Avalia PC</a>
                            </div>
                        </div>
                    @endif
                </div>
                <div>
                    @if ($av->isGestorAprovouPC == 1)
                        <i class="fas fa-caret-right bg-green"></i>
                        <div class="timeline-item">
                            <div class="timeline-header">
                                <a class="btn btn-success btn-lg" @readonly(true)>8 - Gestor - Avalia PC</a>
                            </div>
                        </div>
                    @else
                        <i class="fas fa-caret-right bg-blue"></i>
                        <div class="timeline-item">
                            <div class="timeline-header">
                                <a class="btn btn-primary btn-md" @readonly(true)>8 - Gestor - Avalia PC</a>
                            </div>
                        </div>
                    @endif
                </div>
                <div>
                    @if ($av->isAcertoContasRealizado == 1)
                        <i class="fas fa-caret-right bg-green"></i>
                        <div class="timeline-item">
                            <div class="timeline-header">
                                <a class="btn btn-success btn-lg" @readonly(true)>9 - Financeiro - Acerto de Contas</a>
                            </div>
                        </div>
                    @else
                        <i class="fas fa-caret-right bg-blue"></i>
                        <div class="timeline-item">
                            <div class="timeline-header">
                                <a class="btn btn-primary btn-md" @readonly(true)>9 - Financeiro - Acerto de Contas</a>
                            </div>
                        </div>
                    @endif
                </div>

                <div>
                    <i class="far fa-check-circle bg-green"></i>
                </div>
            </div>
        </div>
        

    </div>
    <br>
    <br>

    
@stop

@section('css')
    
@stop

@section('js')
    
<script type="text/javascript">

    $(document).ready(function(){
        $('#minhaTabela').DataTable({
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

@stop