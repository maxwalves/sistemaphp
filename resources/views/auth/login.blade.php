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
        <x-slot name="logo">
            <div class="clientes-logo">
                <img src="/img/1.png" alt="Paranacidade">
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
                <x-label for="email" value="{{ __('Email') }}" />
                <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            </div>

            <div class="mt-4">
                <x-label for="password" value="{{ __('Password') }}" />
                <x-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="current-password" />
            </div>

            <div class="block mt-4">
                <label for="remember_me" class="flex items-center">
                    <x-checkbox id="remember_me" name="remember" />
                    <span class="ml-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
                </label>
            </div>

            <div class="flex items-center justify-end mt-4">
                @if (Route::has('password.request'))
                    <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('password.request') }}">
                        {{ __('Forgot your password?') }}
                    </a>
                @endif

                <x-button class="ml-4">
                    {{ __('Log in') }}
                </x-button>
            </div>
        </form>
    </x-authentication-card>
</x-guest-layout>
