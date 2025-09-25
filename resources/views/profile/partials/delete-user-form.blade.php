<section class="space-y-6">
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Eliminar Cuenta') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __('Una vez que tu cuenta sea eliminada, todos sus recursos y datos serán eliminados permanentemente. Antes de eliminar tu cuenta, por favor descarga cualquier dato o información que desees conservar.') }}
        </p>
    </header>

    <button type="button" 
            class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 active:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
        {{ __('Eliminar Cuenta') }}
    </button>

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
