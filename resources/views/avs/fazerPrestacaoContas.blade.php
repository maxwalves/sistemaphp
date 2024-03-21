@extends('adminlte::page')

@section('title', 'Fazer prestação de contas')

@section('content_header')
    <h1>Fazer prestação de contas</h1>
@stop

@section('content')

<div class="tab-pane fade show active" id="custom-tabs-five-overlay" role="tabpanel" aria-labelledby="custom-tabs-five-overlay-tab" style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.5); z-index: 9999; display: none">
    <div class="overlay-wrapper" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); text-align: center; color: #ffffff;">
            <i class="fas fa-3x fa-sync-alt fa-spin" style="margin-bottom: 10px;"></i>
            <div class="text-bold pt-2">Carregando...</div>
    </div>
</div>

    <div class="col-md-12 col-sm-6">
        <div class="card card-primary card-outline card-tabs">
            <div class="card-header p-0 pt-1 border-bottom-0">
                <ul class="nav nav-tabs" id="custom-tabs-three-tab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="custom-tabs-three-home-tab" data-toggle="pill"
                            href="#custom-tabs-three-home" role="tab" aria-controls="custom-tabs-three-home"
                            aria-selected="true">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="custom-tabs-three-historico-tab" data-toggle="pill"
                            href="#custom-tabs-three-historico" role="tab" aria-controls="custom-tabs-three-historico"
                            aria-selected="false">Histórico</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="custom-tabs-three-dados-tab" data-toggle="pill"
                            href="#custom-tabs-three-dados" role="tab" aria-controls="custom-tabs-three-dados"
                            aria-selected="false">Dados básicos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="custom-tabs-three-fases-tab" data-toggle="pill"
                            href="#custom-tabs-three-fases" role="tab" aria-controls="custom-tabs-three-fases"
                            aria-selected="false">Fases</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="custom-tabs-three-trajeto-tab" data-toggle="pill"
                            href="#custom-tabs-three-trajeto" role="tab" aria-controls="custom-tabs-three-trajeto"
                            aria-selected="false">Trajeto</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="custom-tabs-three-relatorio-tab" data-toggle="pill"
                            href="#custom-tabs-three-relatorio" role="tab" aria-controls="custom-tabs-three-relatorio"
                            aria-selected="false">Relatório</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="custom-tabs-three-hotel-tab" data-toggle="pill"
                            href="#custom-tabs-three-hotel" role="tab" aria-controls="custom-tabs-three-hotel"
                            aria-selected="false">Hotel</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="custom-tabs-three-transporte-tab" data-toggle="pill"
                            href="#custom-tabs-three-transporte" role="tab" aria-controls="custom-tabs-three-transporte"
                            aria-selected="false">Transporte</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="custom-tabs-three-adiantamentos-tab" data-toggle="pill"
                            href="#custom-tabs-three-adiantamentos" role="tab"
                            aria-controls="custom-tabs-three-adiantamentos" aria-selected="false">Adiantamentos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="custom-tabs-three-despesas-tab" data-toggle="pill"
                            href="#custom-tabs-three-despesas" role="tab" aria-controls="custom-tabs-three-despesas"
                            aria-selected="false">Despesas</a>
                    </li>
                    @if ($av->objetivo_id == 3)
                        <li class="nav-item">
                            <a class="nav-link" id="custom-tabs-three-medicoes-tab" data-toggle="pill"
                                href="#custom-tabs-three-medicoes" role="tab" aria-controls="custom-tabs-three-medicoes"
                                aria-selected="false">Medições</a>
                        </li>
                    @endif
                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content" id="custom-tabs-three-tabContent">
                    <div class="tab-pane fade active show" id="custom-tabs-three-home" role="tabpanel"
                        aria-labelledby="custom-tabs-three-home-tab">
                        <div>

                            <div class="containerAcertoContas">
                                <div class="box box-90">
                                    <h1 style="font-size: 24px"><strong>Autorização de viagem nº:</strong>
                                        {{ $av->id }}</h1>
                                    <h1 style="font-size: 24px"><strong>Status atual:</strong> {{ $av->status }}</h1>
                                    <p class="av-owner" style="font-size: 20px"><ion-icon
                                            name="chevron-forward-circle-outline">
                                        </ion-icon> <strong>Nome do usuário: </strong>
                                        @foreach ($users as $u)
                                            @if ($u->id == $av->user_id)
                                                {{ $u->name }}
                                            @endif
                                        @endforeach
                                    </p>
                                    <p class="av-owner" style="font-size: 20px">
                                        <strong>Objetivo: </strong>
                                        @for ($i = 0; $i < count($objetivos); $i++)
                                            @if ($av->objetivo_id == $objetivos[$i]->id)
                                                {{ $objetivos[$i]->nomeObjetivo }}
                                            @endif
                                        @endfor
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div>
                            <div class="containerAcertoContas">
                                
                                <div class="box box-40">
                                    <div class="row">

                                        <div class="col-12 col-md-6">
                                            <h1 style="font-size: 24px"><strong>AV:</strong></h1>

                                            <table id="minhaTabela6" class="table table-hover table-bordered"
                                                style="width:100%">
                                                <thead>
                                                    <tr>
                                                        <th>Descrição</th>
                                                        <th>Anexo</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($historicoPc as $hist)
                                                        <tr>
                                                            <td> {{ $hist->comentario }} </td>
                                                            @if ($hist->comentario == 'AV Internacional gerada')
                                                                <td> <a href="{{ route('recuperaArquivo', [
                                                                    'name' => $userAv->name,
                                                                    'id' => $av->id,
                                                                    'pasta' => 'internacional',
                                                                    'anexoRelatorio' => $hist->anexoRelatorio,
                                                                    ]) }}"
                                                                        target="_blank" class="btn btn-active btn-success btn-sm">Abrir
                                                                        documento</a> </td>
                                                            @else
                                                                <td>
                                                                    <a href="{{ route('recuperaArquivo', [
                                                                        'name' => $userAv->name,
                                                                        'id' => $av->id,
                                                                        'pasta' => 'resumo',
                                                                        'anexoRelatorio' => $hist->anexoRelatorio,
                                                                    ]) }}" target="_blank" class="btn btn-active btn-success btn-sm">
                                                                        <i class="fas fa-eye"></i>
                                                                    </a>
                                                                </td>
                                                            @endif
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="col-12 col-md-6">
                                            @if ($av->horasExtras != null || $av->minutosExtras != null || $av->justificativaHorasExtras != null)
                                                <div style="border: 1px solid black;; color: black; width: 50%; padding-left: 10%">
                                                    <strong style="color: red">Foram realizadas horas extras:</strong> <br>
                                                    <strong>Horas:</strong> {{ $av->horasExtras }} <br>
                                                    <strong>Minutos:</strong> {{ $av->minutosExtras }} <br>
                                                    <strong>Justificativa:</strong> {{ $av->justificativaHorasExtras }} <br>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                        </div>

                        <div >

                            @if ($av['isCancelado'] == false)
                                <div>
                                    <a href="/avspc/edit/{{ $av->id }}" class="btn btn-success btn-sm"><i class="fas fa-edit"></i> AV</a>
                                    <a href="/rotaspc/rotas/{{ $av->id }}" class="btn btn-secondary btn-sm"><i class="fas fa-edit"></i> ROTAS</a>
                        
                                </div>
                            @endif
                        <hr>
                        <br>
                            <div class="row">
                                <div class="col-3">
                                    <x-adminlte-button label="Adicionar" data-toggle="modal" data-target="#modalAdd" class="bg-teal"/>
                                </div>
                                @if ($av->isCancelado == true)
                                    <div class="col-8">
                                        <div class="alert alert-warning">
                                            <span>A AV está cancelada. Justifique o cancelamento no campo de comentário. Se necessário, anexo um
                                                arquivo!</span>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        
                            <div class="row" style="overflow-x: auto;">
                                <div class="col-12 col-xl-6" style="overflow-x: auto;">
                                    
                                    @if ($av['isCancelado'] == false)
                                        <h1 style="font-size: 24px"><strong>Comprovante de despesa: </strong></h1>
                                    @else
                                        <h1 style="font-size: 24px"><strong>Comprovante de cancelamento: </strong></h1>
                                    @endif
                                    @if(count($comprovantes) >0)
                                    <table id="minhaTabela5" class="table table-hover table-bordered" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>Descrição</th>
                                                @if ($av['isCancelado'] == false)
                                                    <th>Valor em reais</th>
                                                @endif
                                                <th>Ações</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($comprovantes as $comp)
                                                <tr>
                                                    <td> {{ $comp->descricao }} </td>
                                                    @if ($av['isCancelado'] == false)
                                                        <td> R${{ $comp->valorReais }}</td>
                                                    @endif
                                                    <td>
                                                        <div class="d-flex">
                                                            <a href="{{ route('recuperaArquivo', [
                                                                'name' => $userAv->name,
                                                                'id' => $av->id,
                                                                'pasta' => 'comprovantesDespesa',
                                                                'anexoRelatorio' => $comp->anexoDespesa,
                                                                ]) }}"
                                                                target="_blank" class="btn btn-active btn-success btn-sm"><i class="fas fa-paperclip"></i></a>
                                                        
                                                            <form action="/avs/deletarComprovante/{{ $comp->id }}/{{ $av->id }}"
                                                                method="POST">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-active btn-danger btn-sm"><i class="fas fa-trash"></i></button>
                                                            </form>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    @else
                                        <p>Nenhum comprovante adicionado</p>
                                    @endif
                                    <hr>
                                    <h1 style="font-size: 24px"><strong>Trajeto: </strong></h1>
                                    <div class="table-responsive">
                                        <table id="tabelaRota" class="table table-hover table-bordered" style="width:100%">
                                            <thead>
                                                    <tr>
                                                        {{-- <th>Tipo</th> --}}
                                                        <th>Cidade de saída</th>
                                                        <th>Data/Hora de saída</th>
                                                        <th>Cidade de chegada</th>
                                                        <th>Data/Hora de chegada</th>
                                                        <th>Hotel?</th>
                                                        <th>Tipo de transporte</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($av->rotas as $rota)
                                                    <tr>
                                                        {{-- <td> {{ $rota->isViagemInternacional == 1 ? 'Internacional' : 'Nacional' }} </td> --}}
                                                        <td>
                                                            @if ($rota->isAereo == 1)
                                                                <img src="{{ asset('/img/aviaosubindo.png') }}" style="width: 40px">
                                                            @endif
                    
                                                            @if ($rota->isVeiculoProprio == 1 || $rota->isVeiculoEmpresa == 1)
                                                                <img src="{{ asset('/img/carro.png') }}" style="width: 40px">
                                                            @endif
                    
                                                            @if ($rota->isOnibusLeito == 1 || $rota->isOnibusConvencional == 1)
                                                                <img src="{{ asset('/img/onibus.png') }}" style="width: 40px">
                                                            @endif

                                                            @if($rota->isOutroMeioTransporte == 1)
                                                                <img src="{{asset('/img/outros.png')}}" style="width: 40px" >
                                                            @endif
                    
                                                            {{ $rota->isViagemInternacional == 0 ? $rota->cidadeOrigemNacional : $rota->cidadeOrigemInternacional }}
                    
                                                        </td>
                                                        <td> {{ date('d/m/Y H:i', strtotime($rota->dataHoraSaida)) }} </td>
                    
                                                        <td>
                                                            @if ($rota->isAereo == 1)
                                                                <img src="{{ asset('/img/aviaodescendo.png') }}" style="width: 40px">
                                                            @endif
                    
                                                            @if ($rota->isVeiculoProprio == 1 || $rota->isVeiculoEmpresa == 1)
                                                                <img src="{{ asset('/img/carro.png') }}" style="width: 40px">
                                                            @endif
                    
                                                            @if ($rota->isOnibusLeito == 1 || $rota->isOnibusConvencional == 1)
                                                                <img src="{{ asset('/img/onibus.png') }}" style="width: 40px">
                                                            @endif

                                                            @if($rota->isOutroMeioTransporte == 1)
                                                                <img src="{{asset('/img/outros.png')}}" style="width: 40px" >
                                                            @endif
                    
                                                            {{ $rota->isViagemInternacional == 0 ? $rota->cidadeDestinoNacional : $rota->cidadeDestinoInternacional }}
                                                        </td>
                    
                                                        <td> {{ date('d/m/Y H:i', strtotime($rota->dataHoraChegada)) }} </td>
                                                        <td> {{ $rota->isReservaHotel == 1 ? 'Sim' : 'Não' }}</td>
                                                        <td>
                                                            {{ $rota->isOnibusLeito == 1 ? 'Onibus leito' : '' }}
                                                            {{ $rota->isOnibusConvencional == 1 ? 'Onibus convencional' : '' }}
                                                            @if ($rota->isVeiculoProprio == 1)
                                                                {{ 'Veículo próprio: ' }} <br>
                                                                @foreach ($veiculosProprios as $v)
                                                                    @if ($v->id == $rota->veiculoProprio_id)
                                                                        {{ $v->modelo . '-' . $v->placa }}
                                                                    @endif
                                                                @endforeach
                    
                                                                @if (count($veiculosProprios) == 0)
                                                                    {{ 'Não encontrado' }}
                                                                @endif
                                                            @endif
                                                            {{ $rota->isVeiculoEmpresa == 1 ? 'Veículo empresa' : '' }}
                                                            {{ $rota->isAereo == 1 ? 'Aéreo' : '' }}
                                                            {{ $rota->isOutroMeioTransporte == 1 ? "Outros" : ""}}
                                                            {{ $rota->isOutroMeioTransporte == 2 ? "Carona" : ""}}
                                                        </td>
                                                        @php
                                                            $achouVeiculo = false;
                                                        @endphp
                    
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="col-12 col-xl-6">
                                    <form action="/avs/usuarioEnviarPrestacaoContas" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        @method('PUT')
                        
                        
                                        @if ($av['isCancelado'] == false)
                        
                                            @foreach ($av->rotas as $r)
                                                @if ($r->isVeiculoProprio == true)
                                                        <p style="color: red">DIGITAR APENAS NÚMEROS, SEM PONTOS.</p>
                                                        <div class="form-group">
                                                            <label for="odometroIda"
                                                                class="control-label {{ $errors->has('odometroIda') ? 'is-invalid' : '' }}">Odômetro
                                                                ida:</label><br>
                                                            <input type="number" class="form-control" name="odometroIda" id="odometroIda"
                                                                placeholder="Odômetro ida" style="width: 100%">
                                                            @if ($errors->has('odometroIda'))
                                                                <div class="invalid-feedback">
                                                                    {{ $errors->first('odometroIda') }}
                                                                </div>
                                                            @endif
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="odometroVolta"
                                                                class="control-label {{ $errors->has('odometroVolta') ? 'is-invalid' : '' }}">Odômetro
                                                                volta:</label><br>
                                                            <input type="number" class="form-control" name="odometroVolta" id="odometroVolta"
                                                                placeholder="Odômetro volta" style="width: 100%">
                                                            @if ($errors->has('odometroVolta'))
                                                                <div class="invalid-feedback">
                                                                    {{ $errors->first('odometroVolta') }}
                                                                </div>
                                                            @endif
                                                        </div>
                                                    @break
                                                @endif
                                            @endforeach
                        
                                            <div style="padding-left: 50px" class="form-group">
                        
                                                @if ($errors->has('horas') || $errors->has('minutos') || $errors->has('justificativa'))
                                                    <div>
                                                        <p style="color: red"> <strong>Os campos de hora extra não foram preenchidos!</strong>
                                                        </p>
                                                    </div>
                                                @endif
                                            </div>
                        
                                            <div class="form-check form-switch" id="isHorasExtras" style="display: none">
                                                <input class="form-check-input" type="checkbox" role="switch" id="isSelecionado"
                                                    name="isSelecionado" style="height: 20px; width: 40px"
                                                    onChange="mostrarCampoJustificativa()">
                                                <label class="form-check-label" for="isSelecionado" style="padding-left: 10px">Foram realizadas
                                                    horas extras?</label>
                                            </div>
                                            <div class="form-group" id="justificativaHorasExtras" style="display: none;">
                        
                                                <label for="horas_extras">* Horas Extras:</label>
                                                <div class="row">
                                                    <div class="col">
                                                        <input type="number" name="horas" id="horas"
                                                            class="form-control {{ $errors->has('horas') ? 'is-invalid' : '' }}"
                                                            placeholder="Horas">
                        
                                                        @if ($errors->has('horas'))
                                                            <div class="invalid-feedback">
                                                                {{ $errors->first('horas') }}
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div class="col">
                                                        <input type="number" name="minutos" id="minutos"
                                                            class="form-control {{ $errors->has('minutos') ? 'is-invalid' : '' }}"
                                                            placeholder="Minutos">
                        
                                                        @if ($errors->has('minutos'))
                                                            <div class="invalid-feedback">
                                                                {{ $errors->first('minutos') }}
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                        
                                                <label for="justificativa" class="control-label">* Digite a justificativa: </label>
                                                <div class="input-group">
                                                    <input type="text"
                                                        class="form-control {{ $errors->has('justificativa') ? 'is-invalid' : '' }}"
                                                        name="justificativa" id="justificativa" placeholder="Digite a justificativa">
                                                </div>
                        
                                                @if ($errors->has('justificativa'))
                                                    <div class="invalid-feedback">
                                                        {{ $errors->first('justificativa') }}
                                                    </div>
                                                @endif
                                            </div>
                        
                                            <div class="form-group">
                                                <label for="contatos"
                                                    class="control-label {{ $errors->has('contatos') ? 'is-invalid' : '' }}">*
                                                    Contatos:</label><br>
                                                <textarea type="textarea" class="textarea textarea-secondary textarea-lg" name="contatos" id="contatos"
                                                    placeholder="Contatos" style="width: 100%; height: 100px">{{ $av->contatos }}</textarea>
                        
                                                @if ($errors->has('contatos'))
                                                    <div class="invalid-feedback">
                                                        {{ $errors->first('contatos') }}
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="form-group">
                                                <label for="atividades"
                                                    class="control-label {{ $errors->has('atividades') ? 'is-invalid' : '' }}">*
                                                    Atividades:</label><br>
                                                <textarea type="text" class="textarea textarea-secondary textarea-lg" name="atividades" id="atividades"
                                                    placeholder="Atividades" style="width: 100%; height: 100px">{{ $av->atividades }}</textarea>
                        
                                                @if ($errors->has('atividades'))
                                                    <div class="invalid-feedback">
                                                        {{ $errors->first('atividades') }}
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="form-group">
                                                <label for="conclusoes"
                                                    class="control-label {{ $errors->has('conclusoes') ? 'is-invalid' : '' }}">*
                                                    Conclusões:</label><br>
                                                <textarea type="text" class="textarea textarea-secondary textarea-lg" name="conclusoes" id="conclusoes"
                                                    placeholder="Conclusões" style="width: 100%; height: 100px">{{ $av->conclusoes }}</textarea>
                        
                                                @if ($errors->has('conclusoes'))
                                                    <div class="invalid-feedback">
                                                        {{ $errors->first('conclusoes') }}
                                                    </div>
                                                @endif
                                            </div>
                                            <p style="color: red">* Preenchimento obrigatório</p>
                                        @endif
                        
                                        <input type="text" hidden="true" id="id" name="id" value="{{ $av->id }}">
                                        <label for="comentario">Enviar PC para aprovação do Financeiro: </label>
                                        <br>
                                        
                                        <div class="input-group mb-3">
                                            <textarea type="text" class="textarea textarea-bordered h-24" name="comentario" style="width: 200px"
                                            id="comentario" placeholder="Comentário"></textarea>

                                            <span class="input-group-append">
                                                <button type="submit" class="btn btn-active btn-success" onclick="exibirLoader()">Enviar PC</button>
                                            </span>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="custom-tabs-three-historico" role="tabpanel"
                        aria-labelledby="custom-tabs-three-historico-tab" style="overflow-x: auto;">
                        <h3 class="text-lg font-bold" style="padding-left: 10%; padding-bottom: 20px">Histórico</h3>
                        <table id="minhaTabela" class="table table-hover table-bordered">
                            <!-- head -->
                            <thead>
                                <tr>
                                    {{-- <th>Id</th> --}}
                                    <th>Data</th>
                                    <th>Ocorrência</th>
                                    <th>Comentário</th>
                                    <th>Perfil</th>
                                    <th>Autor</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- row 1 -->

                                @foreach ($historicos as $historico)
                                    <tr>
                                        {{-- <td>{{ $historico->id }}</td> --}}
                                        <td>{{ date('d/m/Y H:i', strtotime($historico->dataOcorrencia)) }}</td>
                                        <td>{{ $historico->tipoOcorrencia }}</td>
                                        <td>{{ $historico->comentario }}</td>
                                        <td>{{ $historico->perfilDonoComentario }}</td>

                                        @foreach ($users as $u)
                                            @if ($u->id == $historico->usuario_comentario_id)
                                                <td>{{ $u->name }}</td>
                                            @endif
                                        @endforeach
                                    </tr>
                                @endforeach

                            </tbody>
                        </table>

                    </div>
                    <div class="tab-pane fade" id="custom-tabs-three-dados" role="tabpanel"
                        aria-labelledby="custom-tabs-three-dados-tab">
                        <br>
                        <h1 class="text-lg font-bold">Dados básicos:</h1>
                        <div class="stats stats-vertical shadow">

                            <p class="av-owner" style="font-size: 20px"><ion-icon name="chevron-forward-circle-outline">
                                </ion-icon> <strong>Nome do usuário: </strong>
                                @foreach ($users as $u)
                                    @if ($u->id == $av->user_id)
                                        {{ $u->name }}
                                    @endif
                                @endforeach
                            </p>
                            <p class="av-owner" style="font-size: 20px"><ion-icon name="chevron-forward-circle-outline">
                                </ion-icon> <strong>E-mail do usuário: </strong>
                                @foreach ($users as $u)
                                    @if ($u->id == $av->user_id)
                                        {{ $u->username }}
                                    @endif
                                @endforeach
                            </p>
                            <p class="av-owner" style="font-size: 20px"><ion-icon name="calendar-outline"></ion-icon>
                                <strong>Data de criação: </strong> {{ date('d/m/Y', strtotime($av->dataCriacao)) }}
                            </p>
                            <p class="av-owner" style="font-size: 20px"><ion-icon name="flag-outline">
                                </ion-icon> <strong>Objetivo:</strong>

                                @for ($i = 0; $i < count($objetivos); $i++)
                                    @if ($av->objetivo_id == $objetivos[$i]->id)
                                        {{ $objetivos[$i]->nomeObjetivo }}
                                    @endif
                                @endfor

                                @if (isset($av->outroObjetivo))
                                    {{ $av->outroObjetivo }}
                                @endif

                            </p>
                            <p class="av-owner" style="font-size: 20px"><ion-icon name="pricetag-outline"></ion-icon>
                                <strong>Comentário:</strong> {{ $av->comentario }}
                            </p>
                            <p class="av-owner" style="font-size: 20px"><ion-icon
                                    name="chevron-forward-circle-outline"></ion-icon> <strong>Status:</strong>
                                {{ $av->status }}
                            </p>
                        </div>
                        <br>
                        <h1 class="text-lg font-bold">Dados bancários:</h1>

                        <div class="stats stats-vertical shadow">

                            <p class="av-owner" style="font-size: 20px"><ion-icon name="business-outline"></ion-icon>
                                <strong>Banco:</strong> {{ $av->banco }}
                            </p>
                            <p class="av-owner" style="font-size: 20px"><ion-icon name="home-outline"></ion-icon>
                                <strong>Agência:</strong> {{ $av->agencia }}
                            </p>
                            <p class="av-owner" style="font-size: 20px"><ion-icon name="wallet-outline"></ion-icon>
                                <strong>Conta:</strong> {{ $av->conta }}
                            </p>
                            <p class="av-owner" style="font-size: 20px"><ion-icon name="cash-outline"></ion-icon>
                                <strong>Pix:</strong> {{ $av->pix }}
                            </p>

                        </div>
                        <br>

                        <h1 class="text-lg font-bold">Adiantamentos:</h1>
                        <div class="stats stats-vertical shadow">
                            <p class="av-owner" style="font-size: 20px; color: black;"><ion-icon
                                    name="cash-outline"></ion-icon> <strong>Valor em reais:</strong> R$
                                {{ $av->valorReais }}</p>
                            <p class="av-owner" style="font-size: 20px; color: black;"><ion-icon
                                    name="cash-outline"></ion-icon> <strong>Valor extra em reais:</strong> R$
                                {{ $av->valorExtraReais }}</p>
                            <p class="av-owner" style="font-size: 20px; color: black;">
                                <ion-icon name="cash-outline"></ion-icon> <strong>Valor dedução em reais:</strong> R$
                                {{ $av->valorDeducaoReais }}
                            </p>
                            <p class="av-owner" style="font-size: 20px; color: black;"><ion-icon
                                    name="chevron-forward-circle-outline"></ion-icon> <strong>Justificativa valor
                                    extra:</strong>
                                {{ $av->justificativaValorExtra }}</p>
                            @if ($av->autorizacao != null)
                                <a href="{{ route('recuperaArquivo', [
                                    'name' => $userAv->name,
                                    'id' => $av->id,
                                    'pasta' => 'autorizacaoAv',
                                    'anexoRelatorio' => $av->autorizacao,
                                    ]) }}"
                                    target="_blank" class="btn btn-active btn-success btn-sm">Documento de Autorização</a>
                            @endif
                        </div>

                    </div>
                    <div class="tab-pane fade" id="custom-tabs-three-fases" role="tabpanel"
                        aria-labelledby="custom-tabs-three-fases-tab">

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
                                                <a class="btn btn-success btn-lg" @readonly(true)>1 - Usuário -
                                                    Preenchimento da AV</a>
                                            </div>
                                        </div>
                                    @else
                                        <i class="fas fa-caret-right bg-blue"></i>
                                        <div class="timeline-item">
                                            <div class="timeline-header">
                                                <a class="btn btn-primary btn-md" @readonly(true)>1 - Usuário -
                                                    Preenchimento da AV</a>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                <div>
                                    @if ($av->isAprovadoGestor == 1)
                                        <i class="fas fa-caret-right bg-green"></i>
                                        <div class="timeline-item">
                                            <div class="timeline-header">
                                                <a class="btn btn-success btn-lg" @readonly(true)>2 - Gestor - Avaliação
                                                    inicial</a>
                                            </div>
                                        </div>
                                    @else
                                        <i class="fas fa-caret-right bg-blue"></i>
                                        <div class="timeline-item">
                                            <div class="timeline-header">
                                                <a class="btn btn-primary btn-md" @readonly(true)>2 - Gestor - Avaliação
                                                    inicial</a>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                <div>
                                    @if ($av->isVistoDiretoria == 1)
                                        <i class="fas fa-caret-right bg-green"></i>
                                        <div class="timeline-item">
                                            <div class="timeline-header">
                                                <a class="btn btn-success btn-lg" @readonly(true)>3 - DAF - Avalia
                                                    pedido</a>
                                            </div>
                                        </div>
                                    @else
                                        <i class="fas fa-caret-right bg-blue"></i>
                                        <div class="timeline-item">
                                            <div class="timeline-header">
                                                <a class="btn btn-primary btn-md" @readonly(true)>3 - DAF - Avalia
                                                    pedido</a>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                <div>
                                    @if ($av->isRealizadoReserva == 1)
                                        <i class="fas fa-caret-right bg-green"></i>
                                        <div class="timeline-item">
                                            <div class="timeline-header">
                                                <a class="btn btn-success btn-lg" @readonly(true)>4 - CAD - Coordenadoria
                                                    Administrativa - Realiza reservas</a>
                                            </div>
                                        </div>
                                    @else
                                        <i class="fas fa-caret-right bg-blue"></i>
                                        <div class="timeline-item">
                                            <div class="timeline-header">
                                                <a class="btn btn-primary btn-md" @readonly(true)>4 - CAD - Coordenadoria
                                                    Administrativa - Realiza reservas</a>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                <div>
                                    @if ($av->isAprovadoFinanceiro == 1)
                                        <i class="fas fa-caret-right bg-green"></i>
                                        <div class="timeline-item">
                                            <div class="timeline-header">
                                                <a class="btn btn-success btn-lg" @readonly(true)>4 - CFI - Coordenadoria
                                                    Financeira - Adiantamento</a>
                                            </div>
                                        </div>
                                    @else
                                        <i class="fas fa-caret-right bg-blue"></i>
                                        <div class="timeline-item">
                                            <div class="timeline-header">
                                                <a class="btn btn-primary btn-md" @readonly(true)>4 - CFI -
                                                    Coordenadoria Financeira - Adiantamento</a>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                <div>
                                    <i class="fas fa-caret-right bg-green"></i>
                                    <div class="timeline-item">
                                        <div class="timeline-header">
                                            <a class="btn btn-success btn-lg" @readonly(true)>5 - Viagem</a>
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    @if ($av->isPrestacaoContasRealizada == 1)
                                        <i class="fas fa-caret-right bg-green"></i>
                                        <div class="timeline-item">
                                            <div class="timeline-header">
                                                <a class="btn btn-success btn-lg" @readonly(true)>6 - Usuário - Realiza
                                                    PC</a>
                                            </div>
                                        </div>
                                    @else
                                        <i class="fas fa-caret-right bg-blue"></i>
                                        <div class="timeline-item">
                                            <div class="timeline-header">
                                                <a class="btn btn-primary btn-md" @readonly(true)>6 - Usuário - Realiza
                                                    PC</a>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                <div>
                                    @if ($av->isFinanceiroAprovouPC == 1)
                                        <i class="fas fa-caret-right bg-green"></i>
                                        <div class="timeline-item">
                                            <div class="timeline-header">
                                                <a class="btn btn-success btn-lg" @readonly(true)>7 - Financeiro -
                                                    Avalia PC</a>
                                            </div>
                                        </div>
                                    @else
                                        <i class="fas fa-caret-right bg-blue"></i>
                                        <div class="timeline-item">
                                            <div class="timeline-header">
                                                <a class="btn btn-primary btn-md" @readonly(true)>7 - Financeiro -
                                                    Avalia PC</a>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                <div>
                                    @if ($av->isGestorAprovouPC == 1)
                                        <i class="fas fa-caret-right bg-green"></i>
                                        <div class="timeline-item">
                                            <div class="timeline-header">
                                                <a class="btn btn-success btn-lg" @readonly(true)>8 - Gestor - Avalia
                                                    PC</a>
                                            </div>
                                        </div>
                                    @else
                                        <i class="fas fa-caret-right bg-blue"></i>
                                        <div class="timeline-item">
                                            <div class="timeline-header">
                                                <a class="btn btn-primary btn-md" @readonly(true)>8 - Gestor - Avalia
                                                    PC</a>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                <div>
                                    @if ($av->isAcertoContasRealizado == 1)
                                        <i class="fas fa-caret-right bg-green"></i>
                                        <div class="timeline-item">
                                            <div class="timeline-header">
                                                <a class="btn btn-success btn-lg" @readonly(true)>9 - Financeiro -
                                                    Acerto de Contas</a>
                                            </div>
                                        </div>
                                    @else
                                        <i class="fas fa-caret-right bg-blue"></i>
                                        <div class="timeline-item">
                                            <div class="timeline-header">
                                                <a class="btn btn-primary btn-md" @readonly(true)>9 - Financeiro -
                                                    Acerto de Contas</a>
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
                    <div class="tab-pane fade" id="custom-tabs-three-trajeto" role="tabpanel"
                        aria-labelledby="custom-tabs-three-trajeto-tab" style="overflow-x: auto;">
                        <h1 style="font-size: 24px; padding-bottom: 20px"><strong>Trajeto: </strong></h1>

                        <table id="tabelaRota" class="table table-hover table-bordered" style="width:100%">
                            <thead>
                                <tr>
                                    {{-- <th>Tipo</th> --}}
                                    <th>Cidade de saída</th>
                                    <th>Data/Hora de saída</th>
                                    <th>Cidade de chegada</th>
                                    <th>Data/Hora de chegada</th>
                                    <th>Hotel?</th>
                                    <th>Tipo de transporte</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($av->rotas as $rota)
                                <tr>
                                    {{-- <td> {{ $rota->isViagemInternacional == 1 ? 'Internacional' : 'Nacional' }} </td> --}}
                                    <td>
                                        @if ($rota->isAereo == 1)
                                            <img src="{{ asset('/img/aviaosubindo.png') }}" style="width: 40px">
                                        @endif

                                        @if ($rota->isVeiculoProprio == 1 || $rota->isVeiculoEmpresa == 1)
                                            <img src="{{ asset('/img/carro.png') }}" style="width: 40px">
                                        @endif

                                        @if ($rota->isOnibusLeito == 1 || $rota->isOnibusConvencional == 1)
                                            <img src="{{ asset('/img/onibus.png') }}" style="width: 40px">
                                        @endif

                                        @if($rota->isOutroMeioTransporte == 1)
                                            <img src="{{asset('/img/outros.png')}}" style="width: 40px" >
                                        @endif

                                        {{ $rota->isViagemInternacional == 0 ? $rota->cidadeOrigemNacional : $rota->cidadeOrigemInternacional }}

                                    </td>
                                    <td> {{ date('d/m/Y H:i', strtotime($rota->dataHoraSaida)) }} </td>

                                    <td>
                                        @if ($rota->isAereo == 1)
                                            <img src="{{ asset('/img/aviaodescendo.png') }}" style="width: 40px">
                                        @endif

                                        @if ($rota->isVeiculoProprio == 1 || $rota->isVeiculoEmpresa == 1)
                                            <img src="{{ asset('/img/carro.png') }}" style="width: 40px">
                                        @endif

                                        @if ($rota->isOnibusLeito == 1 || $rota->isOnibusConvencional == 1)
                                            <img src="{{ asset('/img/onibus.png') }}" style="width: 40px">
                                        @endif

                                        @if($rota->isOutroMeioTransporte == 1)
                                            <img src="{{asset('/img/outros.png')}}" style="width: 40px" >
                                        @endif

                                        {{ $rota->isViagemInternacional == 0 ? $rota->cidadeDestinoNacional : $rota->cidadeDestinoInternacional }}
                                    </td>

                                    <td> {{ date('d/m/Y H:i', strtotime($rota->dataHoraChegada)) }} </td>
                                    <td> {{ $rota->isReservaHotel == 1 ? 'Sim' : 'Não' }}</td>
                                    <td>
                                        {{ $rota->isOnibusLeito == 1 ? 'Onibus leito' : '' }}
                                        {{ $rota->isOnibusConvencional == 1 ? 'Onibus convencional' : '' }}
                                        @if ($rota->isVeiculoProprio == 1)
                                            {{ 'Veículo próprio: ' }} <br>
                                            @foreach ($veiculosProprios as $v)
                                                @if ($v->id == $rota->veiculoProprio_id)
                                                    {{ $v->modelo . '-' . $v->placa }}
                                                @endif
                                            @endforeach

                                            @if (count($veiculosProprios) == 0)
                                                {{ 'Não encontrado' }}
                                            @endif
                                        @endif
                                        {{ $rota->isVeiculoEmpresa == 1 ? 'Veículo empresa' : '' }}
                                        {{ $rota->isAereo == 1 ? 'Aéreo' : '' }}
                                        {{ $rota->isOutroMeioTransporte == 1 ? "Outros" : ""}}
                                        {{ $rota->isOutroMeioTransporte == 2 ? "Carona" : ""}}
                                    </td>
                                    @php
                                        $achouVeiculo = false;
                                    @endphp

                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="tab-pane fade" id="custom-tabs-three-relatorio" role="tabpanel"
                    aria-labelledby="custom-tabs-three-relatorio-tab">
                    <h1 style="font-size: 24px"><strong>Relatório:</strong></h1>

                    <div class="form-group">
                        <label for="contatos" class="control-label">Contatos:</label><br>
                        <textarea type="textarea" class="textarea textarea-secondary textarea-lg" name="contatos" id="contatos"
                            placeholder="Contatos" style="width: 100%; height: 100px" disabled>{{ $av->contatos }}</textarea>
                    </div>
                    <div class="form-group">
                        <label for="atividades" class="control-label">Atividades:</label><br>
                        <textarea type="text" class="textarea textarea-secondary textarea-lg" name="atividades" id="atividades"
                            placeholder="Atividades" style="width: 100%; height: 100px" disabled>{{ $av->atividades }}</textarea>
                    </div>
                    <div class="form-group">
                        <label for="conclusoes" class="control-label">Conclusões:</label><br>
                        <textarea type="text" class="textarea textarea-secondary textarea-lg" name="conclusoes" id="conclusoes"
                            placeholder="Conclusões" style="width: 100%; height: 100px" disabled>{{ $av->conclusoes }}</textarea>
                    </div>
                </div>
                <div class="tab-pane fade" id="custom-tabs-three-hotel" role="tabpanel"
                    aria-labelledby="custom-tabs-three-hotel-tab">
                    <h3 class="text-lg font-bold" style="padding-left: 10%; padding-bottom: 20px">Reserva de hotel
                    </h3>
                    <table id="minhaTabela1" class="table table-hover table-bordered" style="width:100%">
                        <thead>
                            <tr>
                                <th>Descrição</th>
                                <th>Rota</th>
                                <th>Anexo</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($anexosRotas as $anexoHotel)
                                @if ($anexoHotel->anexoHotel != null)
                                    <tr>
                                        <td> {{ $anexoHotel->descricao }} </td>

                                        <td>
                                            @for ($i = 0; $i < count($av->rotas); $i++)
                                                @if ($anexoHotel->rota_id == $av->rotas[$i]->id)
                                                    @if ($av->rotas[$i]->isViagemInternacional == 0)
                                                        - {{ $av->rotas[$i]->cidadeOrigemNacional }}
                                                        -> {{ $av->rotas[$i]->cidadeDestinoNacional }}
                                                    @endif

                                                    @if ($av->rotas[$i]->isViagemInternacional == 1)
                                                        - {{ $av->rotas[$i]->cidadeOrigemInternacional }}
                                                        -> {{ $av->rotas[$i]->cidadeDestinoInternacional }}
                                                    @endif
                                                @endif
                                            @endfor
                                        </td>
                                        <td> 
                                            <a href="{{ route('recuperaArquivo', [
                                                'name' => $userAv->name,
                                                'id' => $av->id,
                                                'pasta' => 'null',
                                                'anexoRelatorio' => $anexoHotel->anexoHotel,
                                                ]) }}"
                                                target="_blank" class="btn btn-active btn-success btn-sm">Abrir documento</a>
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>

                </div>
                <div class="tab-pane fade" id="custom-tabs-three-transporte" role="tabpanel"
                    aria-labelledby="custom-tabs-three-transporte-tab">
                    <h3 class="text-lg font-bold" style="padding-left: 10%; padding-bottom: 20px">Reservas de
                        transporte</h3>

                    <table id="minhaTabela2" class="table table-hover table-bordered" style="width:100%">
                        <thead>
                            <tr>
                                <th>Descrição</th>
                                <th>Rota</th>
                                <th>Anexo</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($anexosRotas as $anexoTransporte)
                                @if ($anexoTransporte->anexoTransporte != null)
                                    <tr>
                                        <td> {{ $anexoTransporte->descricao }} </td>
                                        <td>
                                            @for ($i = 0; $i < count($av->rotas); $i++)
                                                @if ($anexoTransporte->rota_id == $av->rotas[$i]->id)
                                                    @if ($av->rotas[$i]->isViagemInternacional == 0)
                                                        - {{ $av->rotas[$i]->cidadeOrigemNacional }}
                                                        -> {{ $av->rotas[$i]->cidadeDestinoNacional }}
                                                    @endif

                                                    @if ($av->rotas[$i]->isViagemInternacional == 1)
                                                        - {{ $av->rotas[$i]->cidadeOrigemInternacional }}
                                                        -> {{ $av->rotas[$i]->cidadeDestinoInternacional }}
                                                    @endif
                                                @endif
                                            @endfor
                                        </td>
                                        <td>
                                            <a href="{{ route('recuperaArquivo', [
                                                'name' => $userAv->name,
                                                'id' => $av->id,
                                                'pasta' => 'null',
                                                'anexoRelatorio' => $anexoTransporte->anexoTransporte,
                                                ]) }}"
                                                target="_blank" class="btn btn-active btn-success btn-sm">Abrir documento</a>
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="tab-pane fade" id="custom-tabs-three-adiantamentos" role="tabpanel"
                    aria-labelledby="custom-tabs-three-adiantamentos-tab">
                    <h3 class="text-lg font-bold" style="padding-left: 10%; padding-bottom: 20px">Adiantamentos
                        realizados
                    </h3>

                    <table id="minhaTabela3" class="table table-hover table-bordered" style="width:100%;">
                        <thead>
                            <tr>
                                <th>Descrição</th>
                                <th>Anexo</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($anexosFinanceiro as $anexoFinanceiro)
                                <tr>
                                    <td> {{ $anexoFinanceiro->descricao }} </td>

                                    <td>
                                        <a href="{{ route('recuperaArquivo', [
                                            'name' => $userAv->name,
                                            'id' => $av->id,
                                            'pasta' => 'adiantamentos',
                                            'anexoRelatorio' => $anexoFinanceiro->anexoFinanceiro,
                                            ]) }}"
                                            target="_blank" class="btn btn-active btn-success btn-sm">Abrir documento</a>
                                    </td>

                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="tab-pane fade" id="custom-tabs-three-despesas" role="tabpanel"
                    aria-labelledby="custom-tabs-three-despesas-tab">
                    <h1 style="font-size: 24px; padding-bottom: 20px"><strong>Comprovantes de despesa:</strong></h1>
                    <table id="minhaTabela7" class="table table-hover table-bordered" style="width:100%">
                        <thead>
                            <tr>
                                <th>Descrição</th>
                                <th>Valor reais</th>
                                <th>Anexo</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($comprovantes as $comp)
                                <tr>
                                    <td> {{ $comp->descricao }} </td>
                                    <td> {{ $comp->valorReais }} </td>
                                    <td>
                                        <a href="{{ route('recuperaArquivo', [
                                            'name' => $userAv->name,
                                            'id' => $av->id,
                                            'pasta' => 'comprovantesDespesa',
                                            'anexoRelatorio' => $comp->anexoDespesa,
                                            ]) }}"
                                            target="_blank" class="btn btn-active btn-success btn-sm"><i class="fas fa-paperclip"></i></a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @if ($av->objetivo_id == 3)
                    <div class="tab-pane fade" id="custom-tabs-three-medicoes" role="tabpanel"
                        aria-labelledby="custom-tabs-three-medicoes-tab">
                        <h1 style="font-size: 24px; padding-bottom: 20px"><strong>Medições vinculadas:</strong></h1>
                        <table id="minhaTabela8" class="table table-hover table-bordered" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Nome do município</th>
                                    <th>Número do projeto</th>
                                    <th>Número do lote</th>
                                    <th>Número da medição</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($medicoesFiltradas as $med)
                                    <tr>
                                        <td> {{ $med->nome_municipio }} </td>
                                        <td> {{ $med->numero_projeto }} </td>
                                        <td> {{ $med->numero_lote }} </td>
                                        <td> {{ $med->numero_medicao }} </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>

    </div>
</div>

<div class="row justify-content-start" style="padding-left: 5%">

</div>


<x-adminlte-modal id="modalAdd" title="Adicionar comprovante de despesa" size="lg" theme="teal"
    icon="fas fa-bell" v-centered static-backdrop scrollable>

    <form action="/avs/gravarComprovante" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="file" id="arquivo1" name="arquivo1" class="form-control-file">
        <input type="text" hidden="true" id="avId" name="avId"
            value="{{ $av->id }}">
        <br><br>
        <label for="descricao">Descrição</label><br>
        <input type="text" id="descricao" name="descricao"
            class="input input-bordered input-secondary w-full max-w-xs"><br>
        @if ($av['isCancelado'] == false)
            <label for="valorReais">Valor em reais utilizado: </label><br>
            <input type="text" id="valorReais" name="valorReais"
                class="input input-bordered input-secondary w-full max-w-xs"><br>
        @endif
        <br><br>
        <button type="submit" id="botaoEnviarArquivo1" class="btn btn-active btn-success" disabled>Gravar
            arquivo</button>
    </form>

    <x-slot name="footerSlot">
        <x-adminlte-button theme="danger" label="Fechar" data-dismiss="modal"/>
    </x-slot>
</x-adminlte-modal>

@stop

@section('css')

@stop

@section('js')

<script type="text/javascript">

    function exibirLoader() {
        $('#custom-tabs-five-overlay').css('display', 'block');
    }

    $(document).ready(function() {
        
        @if(session('error'))

            Swal.fire({
                html: '<i class="fas fa-exclamation-triangle"></i> {{ session('error') }}',
                title: 'Oops...',
            })
        @endif

        @if(session('msg'))

            Swal.fire({
                title: '{{ session('msg') }}',
                text: '',
            })
        @endif
    });


    $(function() {


        const input = document.getElementById('arquivo1');
        const botaoEnviar = document.getElementById('botaoEnviarArquivo1');

        input.addEventListener('change', (event) => {
            if (event.target.value !== '') {
                botaoEnviar.removeAttribute('disabled');
            }
        });

    })

    function mostrarCampoJustificativa() {
        var checkbox = document.getElementById("isSelecionado");
        var divJustificativa = document.getElementById("justificativaHorasExtras");

        if (checkbox.checked) {
            divJustificativa.style.display = "block";
        } else {
            divJustificativa.style.display = "none";
        }
    }
</script>

@stop
