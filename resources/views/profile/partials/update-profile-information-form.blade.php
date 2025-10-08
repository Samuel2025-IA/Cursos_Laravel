<section>
    <div class="space-y-6">
        <!-- Información Personal -->
        <div class="bg-gradient-to-r from-gray-50 to-gray-100 p-6 rounded-xl border border-gray-200">
            <div class="flex items-center mb-4">
                <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900">Información Personal</h3>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <x-readonly-field 
                    :label="__('Primer Nombre')" 
                    :value="$user->primer_nombre" 
                />

                <x-readonly-field 
                    :label="__('Segundo Nombre')" 
                    :value="$user->segundo_nombre ?: 'No especificado'" 
                />

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
        <div class="bg-gradient-to-r from-gray-50 to-gray-100 p-6 rounded-xl border border-gray-200">
            <div class="flex items-center mb-4">
                <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900">Información de Documento</h3>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
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
        <div class="bg-gradient-to-r from-gray-50 to-gray-100 p-6 rounded-xl border border-gray-200">
            <div class="flex items-center mb-4">
                <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center mr-3">
                    <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900">Información de Contacto</h3>
            </div>
            
            <div class="space-y-4">
                <x-readonly-field 
                    :label="__('Correo Electrónico')" 
                    :value="$user->email" 
                />

                <x-readonly-field 
                    :label="__('Entidad')" 
                    :value="ucfirst(str_replace('_', ' ', $user->entidad))" 
                />
            </div>
        </div>

        <!-- Información del Sistema -->
        <div class="bg-gradient-to-r from-gray-50 to-gray-100 p-6 rounded-xl border border-gray-200">
            <div class="flex items-center mb-4">
                <div class="w-8 h-8 bg-orange-100 rounded-lg flex items-center justify-center mr-3">
                    <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900">Información del Sistema</h3>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
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
