<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Información del Perfil') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __("Información completa de tu cuenta. Los datos mostrados son de solo lectura por seguridad del sistema.") }}
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
                    :value="$user->segundo_apellido ?: 'No especificado'" 
                />
            </div>
        </div>

        <!-- Información de Documento -->
        <div class="bg-gray-50 p-4 rounded-lg">
            <h3 class="text-md font-medium text-gray-900 mb-3">Información de Documento</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <x-readonly-field 
                    :label="__('Tipo de Documento')" 
                    :value="$user->tipo_documento ?: 'No especificado'" 
                />

                <x-readonly-field 
                    :label="__('Número de Documento')" 
                    :value="$user->numero_documento ?: 'No especificado'" 
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

        <!-- Información del Sistema -->
        <div class="bg-gray-50 p-4 rounded-lg">
            <h3 class="text-md font-medium text-gray-900 mb-3">Información del Sistema</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <x-readonly-field 
                    :label="__('Rol')" 
                    :value="ucfirst($user->rol)" 
                />

                <x-readonly-field 
                    :label="__('Miembro desde')" 
                    :value="$user->created_at->format('d/m/Y')" 
                />
            </div>
        </div>
    </div>
</section>
