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
            onclick="confirmAccountDeletion()"
            class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 active:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
        {{ __('Eliminar Cuenta') }}
    </button>

    <!-- Formulario oculto para eliminar cuenta -->
    <form id="delete-account-form" method="post" action="{{ route('profile.destroy') }}" style="display: none;">
            @csrf
            @method('delete')
        <input type="password" id="delete-password" name="password" />
    </form>

    <script>
        function confirmAccountDeletion() {
            Swal.fire({
                title: '¿Eliminar tu cuenta?',
                text: 'Esta acción eliminará permanentemente todos tus datos y no se puede deshacer.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar',
                background: '#ffffff',
                color: '#374151'
            }).then((result) => {
                if (result.isConfirmed) {
                                        // Segunda confirmación con contraseña
                    Swal.fire({
                        title: 'Confirma tu contraseña',
                        input: 'password',
                        inputPlaceholder: 'Ingresa tu contraseña actual',
                        showCancelButton: true,
                        confirmButtonColor: '#dc2626',
                        cancelButtonColor: '#6b7280',
                        confirmButtonText: 'Confirmar eliminación',
                        cancelButtonText: 'Cancelar',
                        inputValidator: (value) => {
                            if (!value) {
                                return 'Debes ingresar tu contraseña'
                            }
                        }
                    }).then((passwordResult) => {
                        if (passwordResult.isConfirmed) {
                            // Enviar formulario con la contraseña
                            document.getElementById('delete-password').value = passwordResult.value;
                            document.getElementById('delete-account-form').submit();
                        }
                    });
                }
            });
        }
    </script>

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
