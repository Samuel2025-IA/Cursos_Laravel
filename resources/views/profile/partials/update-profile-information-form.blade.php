<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Información del Perfil') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __("Información de tu cuenta. El nombre, apellido y correo electrónico no se pueden modificar por seguridad.") }}
        </p>
    </header>

    <div class="mt-6 space-y-6">
        <!-- Información Personal -->
        <div class="bg-gray-50 p-4 rounded-lg">
            <h3 class="text-md font-medium text-gray-900 mb-3">Información Personal</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <x-readonly-field 
                    :label="__('Primer Nombre')" 
                    :value="$user->primer_nombre" 
                />

                <x-readonly-field 
                    :label="__('Segundo Nombre')" 
                    :value="$user->segundo_nombre ?: 'No especificado'" 
                />
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                <x-readonly-field 
                    :label="__('Primer Apellido')" 
                    :value="$user->primer_apellido" 
                />

                <x-readonly-field 
                    :label="__('Segundo Apellido')" 
                    :value="$user->segundo_apellido" 
                />
            </div>
        </div>

        <!-- Información de Contacto -->
        <div class="bg-gray-50 p-4 rounded-lg">
            <h3 class="text-md font-medium text-gray-900 mb-3">Información de Contacto</h3>
            
            <x-readonly-field 
                :label="__('Correo Electrónico')" 
                :value="$user->email" 
            />

            <x-readonly-field 
                :label="__('Entidad')" 
                :value="ucfirst(str_replace('_', ' ', $user->entidad))" 
                class="mt-4"
            />
        </div>
    </div>
</section>
