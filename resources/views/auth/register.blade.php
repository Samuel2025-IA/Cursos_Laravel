<x-guest-layout>
    @section('title', 'Registro - Diócesis de Apartadó')

    <!-- Loading Overlay -->
    <x-loading-overlay id="register-loading" text="Enviando registro..." />


    <form method="POST" action="{{ route('register') }}" id="register-form" class="register-form">
        @csrf
        
        <!-- Mensaje de error general -->
        @if($errors->has('general'))
            <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-md" role="alert" aria-live="assertive">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-red-800">{{ $errors->first('general') }}</p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Primera fila: 3 columnas - Nombres -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <!-- Primer Nombre -->
            <div>
                <x-input-label for="primer_nombre" :value="__('Primer Nombre')" />
                <x-text-input id="primer_nombre" 
                             class="block mt-2 w-full" 
                             type="text" 
                             name="primer_nombre" 
                             :value="old('primer_nombre')" 
                             autofocus 
                             autocomplete="given-name"
                             aria-describedby="primer_nombre_error"
                             aria-invalid="{{ $errors->has('primer_nombre') ? 'true' : 'false' }}" />
                <x-input-error :messages="$errors->get('primer_nombre')" class="mt-1" id="primer_nombre_error" aria-live="polite" />
            </div>

            <!-- Segundo Nombre (Opcional) -->
            <div>
                <x-input-label for="segundo_nombre" :value="__('Segundo Nombre')" />
                <x-text-input id="segundo_nombre" class="block mt-2 w-full" type="text" name="segundo_nombre" :value="old('segundo_nombre')" autocomplete="additional-name" placeholder="Opcional" />
                <x-input-error :messages="$errors->get('segundo_nombre')" class="mt-1" />
            </div>

            <!-- Primer Apellido -->
            <div>
                <x-input-label for="primer_apellido" :value="__('Primer Apellido')" />
                <x-text-input id="primer_apellido" class="block mt-2 w-full" type="text" name="primer_apellido" :value="old('primer_apellido')" autocomplete="family-name" />
                <x-input-error :messages="$errors->get('primer_apellido')" class="mt-1" />
            </div>
        </div>

        <!-- Segunda fila: 3 columnas - Apellido, Documento y Email -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mt-4">
            <!-- Segundo Apellido -->
            <div>
                <x-input-label for="segundo_apellido" :value="__('Segundo Apellido')" />
                <x-text-input id="segundo_apellido" class="block mt-2 w-full" type="text" name="segundo_apellido" :value="old('segundo_apellido')" autocomplete="family-name" />
                <x-input-error :messages="$errors->get('segundo_apellido')" class="mt-1" />
            </div>

            <!-- Tipo de Documento -->
            <div>
                <x-input-label for="tipo_documento" :value="__('Tipo de Documento')" />
                <select id="tipo_documento" name="tipo_documento" class="block mt-2 w-full border-gray-300 focus:border-[#2f9f37] focus:ring-[#2f9f37] rounded-md shadow-sm" required>
                    <option value="">Selecciona el tipo</option>
                    <option value="CC" {{ old('tipo_documento') == 'CC' ? 'selected' : '' }}>Cédula de Ciudadanía</option>
                    <option value="CE" {{ old('tipo_documento') == 'CE' ? 'selected' : '' }}>Cédula de Extranjería</option>
                    <option value="TI" {{ old('tipo_documento') == 'TI' ? 'selected' : '' }}>Tarjeta de Identidad</option>
                    <option value="PP" {{ old('tipo_documento') == 'PP' ? 'selected' : '' }}>Pasaporte</option>
                    <option value="NIT" {{ old('tipo_documento') == 'NIT' ? 'selected' : '' }}>NIT</option>
                </select>
                <x-input-error :messages="$errors->get('tipo_documento')" class="mt-1" />
            </div>

            <!-- Correo electrónico -->
            <div>
                <x-input-label for="email" :value="__('Correo Electrónico')" />
                <div class="relative">
                    <x-text-input id="email" class="block mt-2 w-full pr-10" type="email" name="email" :value="old('email', $invitation_email ?? '')" required autocomplete="username" readonly />
                    <!-- Indicador de email preseleccionado -->
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center text-green-600" title="Email verificado por código de invitación">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
                <x-input-error :messages="$errors->get('email')" class="mt-1" />
                <p class="text-sm text-green-600 mt-1">
                    ✓ Email verificado por código de invitación
                </p>
            </div>
        </div>

        <!-- Tercera fila: 2 columnas - Número de Documento y Entidad -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
            <!-- Número de Documento -->
            <div>
                <x-input-label for="numero_documento" :value="__('Número de Documento')" />
                <div class="relative">
                    <x-text-input id="numero_documento" class="block mt-2 w-full pr-10" type="text" name="numero_documento" :value="old('numero_documento')" required maxlength="20" placeholder="Sin espacios ni guiones" />
                    <!-- Indicador de validación -->
                    <div id="document-status" class="absolute inset-y-0 right-0 pr-3 flex items-center hidden">
                        <svg class="h-4 w-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
                <x-input-error :messages="$errors->get('numero_documento')" class="mt-1" />
                <p class="text-sm text-gray-500 mt-1">
                    <span id="document-hint">Ingresa tu número de documento</span>
                </p>
            </div>

            <!-- Entidad -->
            <div>
                <x-input-label for="entidad" :value="__('Elije tu entidad')" />
                <select id="entidad" name="entidad" class="block mt-2 w-full border-gray-300 focus:border-[#2f9f37] focus:ring-[#2f9f37] rounded-md shadow-sm" required>
                    <option value="">Selecciona una entidad</option>
                    <option value="funadpas" {{ old('entidad') == 'funadpas' ? 'selected' : '' }}>Funadpas</option>
                    <option value="fundacion_isaias" {{ old('entidad') == 'fundacion_isaias' ? 'selected' : '' }}>Fundación Isaias Duarte Cancino</option>
                    <option value="diocesis_apartado" {{ old('entidad') == 'diocesis_apartado' ? 'selected' : '' }}>Diócesis de Apartadó</option>
                    <option value="pastoral_social" {{ old('entidad') == 'pastoral_social' ? 'selected' : '' }}>Pastoral Social</option>
                </select>
                <x-input-error :messages="$errors->get('entidad')" class="mt-1" />
            </div>
        </div>

        <!-- Cuarta fila: 2 columnas - Contraseña y Confirmar Contraseña -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
            <!-- Password -->
            <div>
                <x-input-label for="password" :value="__('Contraseña')" />
                <div class="relative">
                    <x-text-input id="password" class="block mt-2 w-full pr-12"
                                 type="password"
                                 name="password"
                                 required autocomplete="new-password" />

                    <!-- Botón para mostrar/ocultar contraseña -->
                    <button type="button" 
                            class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 transition-colors"
                            onclick="togglePassword('password')">
                        <svg id="eye-icon-password" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        <svg id="eye-slash-icon-password" class="h-5 w-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21" />
                        </svg>
                    </button>
                </div>
                <x-input-error :messages="$errors->get('password')" class="mt-1" />
                
                <!-- Indicador de requisitos de contraseña -->
                <div id="password-requirements" class="mt-2 text-sm">
                    <ul class="space-y-1">
                        <li id="req-length" class="flex items-center text-gray-500">
                            <span>Mínimo 8 caracteres</span>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Confirm Password -->
            <div>
                <x-input-label for="password_confirmation" :value="__('Confirmar Contraseña')" />
                <div class="relative">
                    <x-text-input id="password_confirmation" class="block mt-2 w-full pr-12"
                                 type="password"
                                 name="password_confirmation" required autocomplete="new-password" />

                    <!-- Botón para mostrar/ocultar confirmación de contraseña -->
                    <button type="button" 
                            class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 transition-colors"
                            onclick="togglePassword('password_confirmation')">
                        <svg id="eye-icon-password_confirmation" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        <svg id="eye-slash-icon-password_confirmation" class="h-5 w-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21" />
                        </svg>
                    </button>
                </div>
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1" />
            </div>
        </div>

        <!-- Botones de acción -->
        <div class="flex items-center justify-between mt-6">
            <a class="underline text-sm text-[#2f9f37] hover:text-[#2f9f37]/80 font-medium rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#2f9f37] transition-colors" href="{{ route('login') }}">
                {{ __('¿Ya tienes una cuenta?') }}
            </a>
            
            <x-primary-button class="px-8" type="submit">
                {{ __('Registrarse') }}
            </x-primary-button>
        </div>
    </form>

    @vite(['resources/js/global/toggle-password-unified.js', 'resources/js/auth/register.js'])
</x-guest-layout>