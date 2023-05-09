@extends('layouts.main')

@section('title', 'Dashboard')
@section('content')


<div style="padding-left: 50px, padding-right: 50px" class="container">
    <div class="row justify-content-between" >
        
        <div class="col-4">
            <a href="/setores/setores/" type="submit" class="btn btn-active btn-ghost"> Voltar!</a>
        </div>
    </div>
</div>
<div class="col-md-10 offset-md-1 dashboard-avs-container">
    @if(count($usersFiltrado) > 0 )
    <br>
    <h3> <strong> Usuários do Setor: {{$setor->nome}} </strong></h3>
    <table class="table table-hover table-sm table-responsive">
        <thead>
            <tr>
                <th>Id</th>
                <th>Nome</th>
                <th>Chefe</th>
                
            </tr>
        </thead>
        <tbody>
            @foreach($usersFiltrado as $u)
                @if($u->id != $setor->chefe_id)
                    <tr>
                        <td> {{$u->id}} </td>
                        <td> {{$u->name}} </td>
                        <td>
                        @foreach($users as $userAtual)
                            @if($userAtual->id == $setor->chefe_id)
                                {{$userAtual->name}} 
                            @endif
                        @endforeach
                        </td>
                    </tr>
                @endif
            @endforeach
        </tbody>
    </table>
    @endif
</div>

@endsection