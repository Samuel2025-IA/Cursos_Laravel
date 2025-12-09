<section class="space-y-6">
    <!-- Advertencia de Eliminación -->
    <div class="bg-red-50 border border-red-200 rounded-lg p-6">
        <div class="flex items-start">
            <div class="flex-shrink-0">
                <svg class="w-6 h-6 text-red-600 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                </svg>
            </div>
            <div class="ml-4">
                <h4 class="text-lg font-semibold text-red-900 mb-2">
                    {{ __('Eliminar Cuenta Permanentemente') }}
                </h4>
                <p class="text-red-800">
                    {{ __('Una vez que tu cuenta sea eliminada, todos sus recursos y datos serán eliminados permanentemente. Antes de eliminar tu cuenta, por favor descarga cualquier dato o información que desees conservar.') }}
                </p>
            </div>
        </div>
    </div>

    <!-- Botón de Eliminación -->
    <div class="flex justify-end">
        <button type="button" 
                class="inline-flex items-center px-6 py-3 bg-red-600 border border-transparent rounded-lg font-semibold text-sm text-white uppercase tracking-widest hover:bg-red-500 active:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-lg hover:shadow-xl">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
            </svg>
            {{ __('Eliminar Cuenta') }}
        </button>
    </div>

    <!-- Formulario oculto para eliminar cuenta -->
    <form id="delete-account-form" method="post" action="{{ route('profile.destroy') }}" style="display: none;">
            @csrf
            @method('delete')
        <input type="password" id="delete-password" name="password" />
    </form>

    @vite('resources/js/views/profile/delete-user.js')

    <!-- Mostrar errores de validación si existen -->
    @if($errors->userDeletion->isNotEmpty())
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                let errorMessages = '';
                @foreach($errors->userDeletion->all() as $error)
                    errorMessages += '{{ $error }}\n';
                @endforeach
                
                showError('Error en eliminación de cuenta: ' + errorMessages.trim());
            });
        </script>
    @endif
</section>
