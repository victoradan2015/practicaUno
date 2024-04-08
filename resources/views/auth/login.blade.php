<x-guest-layout>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    @if ($errors->has('g-recaptcha-response'))
        <span class="help-block">
            <strong>{{ $errors->first('g-recaptcha-response') }}</strong>
        </span>
    @endif

    <form method="POST" action="{{ route('login') }}">
        <!-- @csrf -->


        <div>
            <x-input-label for="email" :value="__('Correo Electronico')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="password" :value="__('Contraseña')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="form-group mt-3">
            {!! NoCaptcha::renderJs('es', false, 'recaptchaCallback') !!}
            {!! NoCaptcha::display() !!}
        </div>

        <div class="flex items-center justify-end mt-4">

            <x-primary-button class="ms-3" id="loginButton" onclick="validateButton()">
                {{ __('Ingresar') }}
            </x-primary-button>
        </div>
    </form>

    <script>
        function validateButton() {
            document.getElementById('loginButton').disabled = true;
            document.querySelector('form').submit();

            setTimeout(function() {document.getElementById('loginButton').disabled = false;}, 3000);
        }
    </script>
</x-guest-layout>
