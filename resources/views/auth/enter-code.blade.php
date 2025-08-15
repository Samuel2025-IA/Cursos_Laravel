<x-guest-layout>
    <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
        {{ __('Se ha enviado un código de verificación a tu correo electrónico. Por favor, ingresa el código de 8 dígitos que recibiste.') }}
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.code.verify') }}">
        @csrf

        <!-- Email Address (hidden) -->
        <input type="hidden" name="email" value="{{ $email }}">

        <!-- Verification Code -->
        <div>
            <x-input-label for="code" :value="__('Código de Verificación')" />
            <x-text-input id="code" class="block mt-1 w-full text-center text-2xl tracking-widest" 
                         type="text" name="code" maxlength="8" required autofocus 
                         placeholder="00000000" />
            <x-input-error :messages="$errors->get('code')" class="mt-2" />
            <p class="mt-2 text-sm text-gray-500">Ingresa el código de 8 dígitos que recibiste por correo</p>
        </div>

        <div class="flex items-center justify-end mt-4">
            <x-primary-button>
                {{ __('Verificar Código') }}
            </x-primary-button>
        </div>
    </form>

    <div class="mt-6 text-center space-y-2">
        <p class="text-sm text-gray-600 dark:text-gray-400">
            ¿No recibiste el código? 
            <a href="{{ route('password.request') }}" class="text-blue-600 hover:text-blue-500 underline">
                Solicitar nuevo código
            </a>
        </p>
        <a href="{{ route('login') }}" class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 underline">
            {{ __('Volver al inicio de sesión') }}
        </a>
    </div>

    <script>
        // Auto-focus y formato del código
        document.getElementById('code').addEventListener('input', function(e) {
            // Solo permite números
            this.value = this.value.replace(/[^0-9]/g, '');
            
            // Auto-avance al siguiente campo si se completa
            if (this.value.length === 8) {
                this.form.submit();
            }
        });
    </script>
</x-guest-layout>
