<x-guest-layout>

    <style>
        .tituloSistema {
            font-weight: bold;
            font-size: 1.8em;
            position: absolute;
            display:inline-block;
            top: 10%; 
            left: 50%;
            transform: translate(-50%, -50%);  
        }

        .clientes-logo {
            width: 250px;
            height: 200px;
            background-color: white; 
            display:inline-block;
            position: relative;

        }

        .clientes-logo img {
            position: absolute;
            top: 50%; 
            left: 50%;
            transform: translate(-50%, -50%);  
        }
    </style>
    <x-authentication-card>
        <link rel="shortcut icon" type="imagex/png" href="/img/aviao.png">
        <x-slot name="logo">
            <div class="clientes-logo">
                <img src="{{asset('/img/1.png')}}" alt="Paranacidade">
            </div>
            <div>
                <p class="tituloSistema">Sistema de Controle de Viagens</p></li>
            </div>
            <br>
            
        </x-slot>

        <x-validation-errors class="mb-4" />

        @if (session('status'))
            <div class="mb-4 font-medium text-sm text-green-600">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div>
                <x-label for="username" value="{{ __('Username') }}" />
                <x-input id="username" class="block mt-1 w-full" type="username" name="username" :value="old('username')" required autofocus autocomplete="username" />
            </div>

            <div class="mt-4">
                <x-label for="password" value="{{ __('Senha') }}" />
                <x-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="current-password" />
            </div>

            <div class="block mt-4">
                <label for="remember_me" class="flex items-center">
                    <x-checkbox id="remember_me" name="remember" />
                    <span class="ml-2 text-sm text-gray-600">{{ __('Lembrar-me') }}</span>
                </label>
            </div>

            <div class="flex items-center justify-end mt-4">

                <x-button class="ml-4">
                    {{ __('Log in') }}
                </x-button>
            </div>
            
        </form>
    </x-authentication-card>
</x-guest-layout>
