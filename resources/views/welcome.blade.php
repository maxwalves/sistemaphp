@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Viagens</h1>
@stop

@section('content')
    <div class="row">

        <div class="col-12 col-xl-4">
            <div class= "card">
                <div class="card-body">
                    <h5>Seja bem-vindo(a) ao Sistema de Controle de Viagens!</h5>
                </div>
                <div class="card-body">
                    <h5><strong>E-mail: </strong> {{ $user->username }}</h5>
                    <h5><strong>Setor/Regional: </strong>
                        @if ($isCuritiba)
                            Curitiba
                        @elseif($user->department == 'CMCAS')
                            Cascavel
                        @elseif($user->department == 'CMMGA')
                            Maringá
                        @elseif($user->department == 'ERFCB')
                            Francisco Beltrão
                        @elseif($user->department == 'CMGP')
                            Guarapuava
                        @elseif($user->department == 'CMLDR' || $user->department == 'CELDR')
                            Londrina
                        @elseif($user->department == 'CMPG')
                            Ponta Grossa
                        @else
                            Não encontrado
                        @endif
                    </h5>
                    <h5><strong>Matrícula: </strong>
                        {{ $user->employeeNumber != null ? $user->employeeNumber : '** NÃO CADASTRADO **. Entre em contato com o suporte!' }}
                    </h5>
                    <h5><strong>Setor: </strong> {{ $user->department }}</h5>
                    <h5><strong>Coordenador: </strong> {{ $managerName }}</h5>
                    <h5><strong>Quantidade de AVs cadastradas: </strong> {{ count($avs) }}</h5>
                </div>
            </div>
            <div class= "card">
                <div class="card-body">
                    <h5>Tutoriais: </h5>
                </div>
                <div class="card-body">
                    <h5><strong>1. Como cadastrar uma nova AV: </strong> <a
                            href="https://paranacidade-my.sharepoint.com/:v:/g/personal/maximiliano_alves_paranacidade_org_br/EZEG2wIEthdBmE-ML6XFdRUBIQLXm9byI6YE695DPpNJKA?e=d7LR6F&nav=eyJyZWZlcnJhbEluZm8iOnsicmVmZXJyYWxBcHAiOiJTdHJlYW1XZWJBcHAiLCJyZWZlcnJhbFZpZXciOiJTaGFyZURpYWxvZy1MaW5rIiwicmVmZXJyYWxBcHBQbGF0Zm9ybSI6IldlYiIsInJlZmVycmFsTW9kZSI6InZpZXcifX0%3D"
                            target="_blank">Clique aqui</a></h5>
                    <h5><strong>2. Como fazer a Prestação de Contas: </strong> <a
                            href="https://paranacidade-my.sharepoint.com/:v:/g/personal/maximiliano_alves_paranacidade_org_br/Eb9S9jOoly5Lgo9QWVIVk8sBlD3fl_PAB_Du4KpUtWUZiA?e=gdDcEg&nav=eyJyZWZlcnJhbEluZm8iOnsicmVmZXJyYWxBcHAiOiJTdHJlYW1XZWJBcHAiLCJyZWZlcnJhbFZpZXciOiJTaGFyZURpYWxvZy1MaW5rIiwicmVmZXJyYWxBcHBQbGF0Zm9ybSI6IldlYiIsInJlZmVycmFsTW9kZSI6InZpZXcifX0%3D"
                            target="_blank">Clique aqui</a></h5>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')

@stop

@section('js')
    <script>
        console.log('Hi!');
    </script>
@stop
