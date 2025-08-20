@extends('adminlte::page')

@section('title', 'Teste de Envio de E-mail')

@section('content_header')
    <h1>Teste de Envio de E-mail</h1>
@stop

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
            <h3><strong>Formulário de Envio de E-mail</strong></h3>
            @if (session('success'))
                <div class="alert alert-success" role="alert">
                    {{ session('success') }}
                </div>
            @endif

            <form method="POST" action="{{ route('email.sendTest') }}">
                @csrf

                <div class="form-group">
                    <label for="title">Título do E-mail:</label>
                    <input type="text" name="title" id="title" class="form-control" required>
                    @error('title')
                        <p class="text-danger">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="recipients">Destinatários (separados por vírgula):</label>
                    <input type="text" name="recipients" id="recipients" class="form-control" required>
                    @error('recipients')
                        <p class="text-danger">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="message">Mensagem:</label>
                    <textarea name="message" id="message" rows="10" class="form-control" required></textarea>
                    @error('message')
                        <p class="text-danger">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">Enviar E-mail</button>
            </form>
        </div>
    </div>
</div>
@stop
