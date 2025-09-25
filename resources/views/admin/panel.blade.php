<x-app-layout class="with-sidebar">
    @section('title', 'Panel de Administración - Diócesis de Apartadó')
    
    <x-slot name="header">
        <div class="header-content" style="margin-left: 280px; transition: margin-left 0.25s ease;">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Panel de Administración') }}
            </h2>
            <p class="mt-1 text-sm text-gray-600">
                {{ __('Gestiona usuarios y códigos de invitación') }}
            </p>
        </div>
    </x-slot>

    @if(Auth::user()->rol === 'admin')
    @vite(['resources/css/sidebar.css'])
    <div class="app-sidebar">
        <div class="section-title">Panel General</div>
        <ul class="nav-list">
            <li class="nav-item">
                <a href="{{ route('dashboard') }}" class="nav-link">
                    <svg class="ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9.5l9-7 9 7V20a2 2 0 0 1-2 2h-3a2 2 0 0 1-2-2v-4H10v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V9.5z"/></svg>
                    <span>Hogar</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link">
                    <svg class="ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16v16H4z"/><path d="M4 9h16"/></svg>
                    <span>Bandeja de entrada</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link">
                    <svg class="ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                    <span>Calendario</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link">
                    <svg class="ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                    <span>Buscar</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link">
                    <svg class="ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V22a2 2 0 1 1-4 0v-.09a1.65 1.65 0 0 0-1-1.51 1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H2a2 2 0 1 1 0-4h.09a1.65 1.65 0 0 0 1.51-1 1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06a1.65 1.65 0 0 0 1.82.33H8a1.65 1.65 0 0 0 1-1.51V2a2 2 0 1 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V8c0 .66.26 1.3.73 1.77.47.47 1.11.73 1.77.73H22a2 2 0 1 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>
                    <span>Ajustes</span>
                </a>
            </li>
        </ul>
    </div>
    @vite(['resources/js/sidebar.js'])
    @endif

    <!-- Botón de colapso flotante -->
    @if(Auth::user()->rol === 'admin')
    <button class="sidebar-toggle-btn" type="button" title="Colapsar sidebar" data-toggle-sidebar style="background: transparent; border: none; box-shadow: none;">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <rect x="3" y="3" width="18" height="18" rx="2" ry="2"/>
            <line x1="9" y1="3" x2="9" y2="21"/>
        </svg>
    </button>
    @endif

    <!-- Loading Overlay Estándar -->
    <x-loading-overlay id="invitation-loading" text="Enviando invitaciones..." />

    <div class="py-12 dashboard-content" style="margin-top: 80px; margin-left: 280px; transition: margin-left 0.25s ease;">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Gestión de Usuarios -->
            <div class="bg-white shadow-lg rounded-xl overflow-hidden">
                <div class="bg-gradient-to-r from-[#2f9f37] to-[#2f9f37]/90 px-6 py-4">
                    <h3 class="text-lg font-semibold text-white flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        Gestión de Usuarios
                    </h3>
                </div>
                <div class="p-6">
                    <form id="invitationForm" action="{{ route('admin.send-invitations') }}" method="POST" class="space-y-4" onsubmit="return validarFormulario()">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            <div>
                                <label for="email1" class="block text-sm font-medium text-gray-700 mb-1">Correo 1</label>
                                <input type="email" id="email1" name="emails[]" class="email-input w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#2f9f37] focus:border-[#2f9f37]" placeholder="usuario1@ejemplo.com">
                            </div>
                            <div>
                                <label for="email2" class="block text-sm font-medium text-gray-700 mb-1">Correo 2</label>
                                <input type="email" id="email2" name="emails[]" class="email-input w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#2f9f37] focus:border-[#2f9f37]" placeholder="usuario2@ejemplo.com">
                            </div>
                            <div>
                                <label for="email3" class="block text-sm font-medium text-gray-700 mb-1">Correo 3</label>
                                <input type="email" id="email3" name="emails[]" class="email-input w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#2f9f37] focus:border-[#2f9f37]" placeholder="usuario3@ejemplo.com">
                            </div>
                            <div>
                                <label for="email4" class="block text-sm font-medium text-gray-700 mb-1">Correo 4</label>
                                <input type="email" id="email4" name="emails[]" class="email-input w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#2f9f37] focus:border-[#2f9f37]" placeholder="usuario4@ejemplo.com">
                            </div>
                            <div>
                                <label for="email5" class="block text-sm font-medium text-gray-700 mb-1">Correo 5</label>
                                <input type="email" id="email5" name="emails[]" class="email-input w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#2f9f37] focus:border-[#2f9f37]" placeholder="usuario5@ejemplo.com">
                            </div>
                        </div>
                        
                        <div class="flex items-center justify-between pt-4">
                            <p class="text-sm text-gray-500">
                                Los códigos de invitación expiran en 7 días
                            </p>
                            <button type="submit" id="submitBtn" class="inline-flex items-center px-4 py-2 bg-[#2f9f37] border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-[#2f9f37]/90 focus:bg-[#2f9f37]/90 active:bg-[#2f9f37]/80 focus:outline-none focus:ring-2 focus:ring-[#2f9f37] focus:ring-offset-2 transition ease-in-out duration-150">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                                Enviar Invitaciones
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Historial de Códigos de Invitación -->
            <div class="bg-white shadow-lg rounded-xl overflow-hidden">
                <div class="px-6 py-4" style="background: linear-gradient(to right, #2A3658, #1e2a3f);">
                    <h3 class="text-lg font-semibold text-white flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Historial de Códigos de Invitación
                    </h3>
                </div>
                <div class="p-6">
                    @if($invitationCodes->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Código</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Expira</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Creado</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($invitationCodes as $code)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $code->email }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-mono text-gray-900">{{ $code->code }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if(isset($code->is_active) && !$code->is_active)
                                                    {{-- Código del historial --}}
                                                    @if($code->status === 'deleted')
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                            Eliminado
                                                        </span>
                                                    @elseif($code->status === 'used')
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                                            Usado
                                                        </span>
                                                    @elseif($code->status === 'expired')
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                            Expirado
                                                        </span>
                                                    @endif
                                                @else
                                                    {{-- Código activo --}}
                                                    @if($code->used)
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                                            Usado
                                                        </span>
                                                    @elseif($code->expires_at->isPast())
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                            Expirado
                                                        </span>
                                                    @else
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-[#2f9f37]/10 text-[#2f9f37]">
                                                            Activo
                                                        </span>
                                                    @endif
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $code->expires_at->format('d/m/Y H:i') }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $code->created_at->format('d/m/Y H:i') }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                @if(isset($code->is_active) && !$code->is_active)
                                                    {{-- Código del historial - no se puede eliminar --}}
                                                    <span class="text-gray-400">-</span>
                                                @else
                                                    {{-- Código activo - se puede eliminar --}}
                                                    <form action="{{ route('admin.delete-invitation', $code->id) }}" method="POST" class="inline delete-form">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="button" class="text-red-600 hover:text-red-900 delete-btn" data-email="{{ $code->email }}" data-status="{{ $code->used ? 'usado' : 'activo' }}">
                                                            Eliminar
                                                        </button>
                                                    </form>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="mt-4">
                            {{ $invitationCodes->links() }}
                        </div>
                    @else
                        <div class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No hay códigos de invitación</h3>
                            <p class="mt-1 text-sm text-gray-500">Los códigos de invitación aparecerán aquí cuando los envíes.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <!-- JavaScript SIMPLE del Panel -->
    <script>
        // Función para alertas con SweetAlert2
        function mostrarAlerta(mensaje, tipo = 'error') {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: tipo,
                    title: tipo === 'success' ? '¡Éxito!' : tipo === 'warning' ? '¡Atención!' : '¡Error!',
                    text: mensaje,
                    confirmButtonText: '¡Perfecto!',
                    confirmButtonColor: '#2f9f37', // Verde
                    background: '#ffffff',
                    color: '#374151'
                });
            } else {
                alert(tipo.toUpperCase() + ': ' + mensaje);
            }
        }

        // Validar formulario al enviar - función global
        window.validarFormulario = function() {
            const inputs = document.querySelectorAll('.email-input');
            const emails = [];
            const emailsInvalidos = [];
            
            // Recopilar y validar emails
            inputs.forEach(input => {
                const email = input.value.trim();
                if (email) {
                    if (validarEmail(email)) {
                        emails.push(email.toLowerCase());
                    } else {
                        emailsInvalidos.push(email);
                    }
                }
            });

            // ERROR: No hay emails válidos
            if (emails.length === 0 && emailsInvalidos.length === 0) {
                mostrarAlerta('Por favor, ingresa al menos un correo electrónico válido.', 'error');
                return false;
            }

            // ERROR: Emails con formato inválido
            if (emailsInvalidos.length > 0) {
                mostrarAlerta(`Los siguientes emails no son válidos: ${emailsInvalidos.join(', ')}`, 'error');
                return false;
            }

            // ERROR: Verificar duplicados
            const emailsUnicos = [...new Set(emails)];
            if (emailsUnicos.length !== emails.length) {
                const duplicados = emails.filter((email, index) => emails.indexOf(email) !== index);
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Emails Duplicados',
                        html: `No puedes enviar el mismo correo múltiples veces:<br><strong>${duplicados.join(', ')}</strong>`,
                        confirmButtonColor: '#dc2626',
                        confirmButtonText: 'Aceptar',
                        background: '#ffffff',
                        iconColor: '#dc2626'
                    });
                } else {
                    alert('ERROR: Se detectaron correos duplicados: ' + duplicados.join(', '));
                }
                return false;
            }

            // ✅ TODOS LOS EMAILS SON VÁLIDOS - Mostrar confirmación
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: '¿Enviar invitaciones?',
                    text: `Se enviarán ${emailsUnicos.length} invitación(es) por correo electrónico`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Sí, enviar',
                    cancelButtonText: 'Cancelar',
                    confirmButtonColor: '#2f9f37',
                    cancelButtonColor: '#6b7280',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        // ✅ MOSTRAR LOADING SOLO SI CONFIRMA
                        mostrarLoadingOverlay();
                        enviarInvitacionesAjax();
                    }
                });
                return false;
            } else {
                if (confirm(`¿Enviar ${emailsUnicos.length} invitación(es)?`)) {
                    mostrarLoadingOverlay();
                    enviarInvitacionesAjax();
                    return false;
                }
                return false;
            }
        };

        // Función para validar formato de email
        function validarEmail(email) {
            const regex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
            return regex.test(email);
        }

        // Función para mostrar loading overlay estándar
        function mostrarLoadingOverlay() {
            showLoading('invitation-loading', 'Enviando invitaciones...');
        }

        // Función para enviar invitaciones por AJAX
        function enviarInvitacionesAjax() {
            const form = document.getElementById('invitationForm');
            const formData = new FormData(form);
            
            // 🔍 DEBUG: Verificar qué datos se están enviando
            console.log('🔍 DEBUG: Contenido del FormData:');
            for (let [key, value] of formData.entries()) {
                console.log(`   ${key}: ${value}`);
            }
            
            // Verificar CSRF token
            const csrfToken = document.querySelector('meta[name="csrf-token"]');
            if (!csrfToken) {
                console.error('❌ CSRF token no encontrado');
                ocultarLoadingOverlay();
                mostrarAlerta('Error de seguridad. Por favor, recarga la página.', 'error');
                return;
            }

            console.log('📤 Enviando invitaciones por AJAX...');
            console.log('🔐 CSRF Token:', csrfToken.getAttribute('content').substring(0, 10) + '...');

            fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => {
                console.log('📥 Respuesta recibida:', response.status);
                
                // Verificar si la respuesta es exitosa
                if (!response.ok) {
                    throw new Error(`HTTP Error: ${response.status}`);
                }
                
                // Intentar parsear como JSON
                const contentType = response.headers.get('content-type');
                if (contentType && contentType.includes('application/json')) {
                    return response.json();
                } else {
                    // Si no es JSON, asumir éxito (probablemente una redirección)
                    console.log('⚠️ Respuesta no es JSON, asumiendo éxito');
                    return { success: true, message: 'Invitaciones enviadas correctamente' };
                }
            })
            .then(data => {
                console.log('📊 Datos de respuesta:', data);
                
                // Ocultar loading overlay
                ocultarLoadingOverlay();

                if (data.success) {
                    // Mostrar mensaje de éxito
                    mostrarAlerta(data.message || 'Invitaciones enviadas correctamente', 'success');
                    
                    // Limpiar el formulario
                    const inputs = document.querySelectorAll('.email-input');
                    inputs.forEach(input => input.value = '');
                    
                    // Recargar la página después de 2 segundos para ver los códigos actualizados
                    setTimeout(() => {
                        window.location.reload();
                    }, 2000);
                } else {
                    // Mostrar mensaje de error
                    mostrarAlerta(data.message || 'Error al enviar las invitaciones', 'error');
                }
            })
            .catch(error => {
                console.error('❌ Error en AJAX:', error);
                console.error('❌ Stack trace:', error.stack);
                
                // Ocultar loading overlay
                ocultarLoadingOverlay();
                
                // Mostrar mensaje de error más específico
                let errorMessage = 'Error al enviar las invitaciones. Por favor, intenta de nuevo.';
                if (error.message && error.message.includes('HTTP Error')) {
                    errorMessage = `Error del servidor: ${error.message}`;
                } else if (error.message) {
                    errorMessage = `Error: ${error.message}`;
                }
                
                mostrarAlerta(errorMessage, 'error');
            });
        }

        // Función para ocultar loading overlay estándar
        function ocultarLoadingOverlay() {
            hideLoading('invitation-loading');
        }

        // Manejar clics en botones de eliminación
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('delete-btn')) {
                e.preventDefault();
                e.stopPropagation();
                
                const email = e.target.getAttribute('data-email');
                const form = e.target.closest('.delete-form');
                
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        title: '¿Eliminar código?',
                        text: `${email}`,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Eliminar',
                        cancelButtonText: 'Cancelar',
                        confirmButtonColor: '#dc2626',
                        cancelButtonColor: '#6b7280',
                        reverseButtons: true
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                } else {
                    if (confirm(`¿Eliminar código para ${email}?`)) {
                        form.submit();
                    }
                }
            }
        });
        
        // Event listeners directos como backup
        function registrarEventListenersDirectos() {
            const deleteButtons = document.querySelectorAll('.delete-btn');
            deleteButtons.forEach((btn) => {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    
                    const email = this.getAttribute('data-email');
                    const form = this.closest('.delete-form');
                    
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            title: '¿Eliminar código?',
                            text: `${email}`,
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonText: 'Eliminar',
                            cancelButtonText: 'Cancelar',
                            confirmButtonColor: '#dc2626',
                            cancelButtonColor: '#6b7280',
                            reverseButtons: true
                        }).then((result) => {
                            if (result.isConfirmed) {
                                form.submit();
                            }
                        });
                    } else {
                        if (confirm(`¿Eliminar código para ${email}?`)) {
                            form.submit();
                        }
                    }
                });
            });
        }

        // Funciones de prueba disponibles en consola
        window.probarAlertas = function() {
            console.log('Probando alertas...');
            mostrarAlerta('Esta es una alerta de éxito', 'success');
            setTimeout(() => mostrarAlerta('Esta es una alerta de error', 'error'), 2000);
            setTimeout(() => mostrarAlerta('Esta es una alerta de advertencia', 'warning'), 4000);
        };

        window.probarValidacion = function() {
            console.log('🧪 Probando validación con emails duplicados...');
            // Simular emails duplicados
            const inputs = document.querySelectorAll('.email-input');
            if (inputs.length >= 2) {
                inputs[0].value = 'test@test.com';
                inputs[1].value = 'test@test.com';
                console.log('📝 Emails de prueba establecidos');
                return validarFormulario();
            } else {
                console.log('❌ No se encontraron suficientes inputs de email');
            }
        };

        // Función para probar sin duplicados
        window.probarValidacionSinDuplicados = function() {
            console.log('🧪 Probando validación sin duplicados...');
            const inputs = document.querySelectorAll('.email-input');
            if (inputs.length >= 2) {
                inputs[0].value = 'test1@test.com';
                inputs[1].value = 'test2@test.com';
                console.log('📝 Emails únicos establecidos');
                return validarFormulario();
            } else {
                console.log('❌ No se encontraron suficientes inputs de email');
            }
        };

        // Función para probar el loading overlay SIMPLE
        window.probarLoading = function() {
            console.log('🧪 Probando loading overlay SIMPLE...');
            const loadingOverlay = document.getElementById('invitation-loading');
            if (loadingOverlay) {
                // Mostrar el loading
                loadingOverlay.style.display = 'block';
                console.log('✅ Loading overlay mostrado - deberías verlo AHORA');
                
                // Ocultar después de 3 segundos para la prueba
                setTimeout(() => {
                    loadingOverlay.style.display = 'none';
                    console.log('✅ Loading overlay ocultado');
                }, 3000);
            } else {
                console.log('❌ No se encontró el loading overlay');
                console.log('📋 Todos los elementos con ID:', Array.from(document.querySelectorAll('[id]')).map(el => el.id));
            }
        };

        // Función para probar la confirmación de eliminación
        window.probarConfirmacionEliminacion = function() {
            console.log('🧪 Probando confirmación de eliminación...');
            
            // Verificar elementos en la página
            const deleteBtns = document.querySelectorAll('.delete-btn');
            const deleteForms = document.querySelectorAll('.delete-form');
            
            console.log('🔍 Botones de eliminar encontrados:', deleteBtns.length);
            console.log('🔍 Formularios de eliminar encontrados:', deleteForms.length);
            
            if (deleteBtns.length > 0) {
                console.log('✅ Botones de eliminar encontrados:');
                deleteBtns.forEach((btn, index) => {
                    console.log(`  ${index + 1}. Email: ${btn.getAttribute('data-email')}, Status: ${btn.getAttribute('data-status')}`);
                });
                
                console.log('🎯 Simulando clic en el primer botón...');
                deleteBtns[0].click();
            } else {
                console.log('❌ No se encontraron botones de eliminar');
                console.log('💡 Verificando si hay códigos en la tabla...');
                
                const tableRows = document.querySelectorAll('tbody tr');
                console.log('📊 Filas en la tabla:', tableRows.length);
                
                if (tableRows.length === 0) {
                    console.log('💡 La tabla está vacía - no hay códigos de invitación para mostrar');
                } else {
                    console.log('⚠️ Hay filas pero no botones de eliminar - posible problema en el HTML');
                    tableRows.forEach((row, index) => {
                        console.log(`  Fila ${index + 1}:`, row.innerHTML);
                    });
                }
            }
        };

        // Inicializar panel administrativo
        document.addEventListener('DOMContentLoaded', function() {
            // Registrar event listeners directos como backup
            registrarEventListenersDirectos();
            
            @if(session('success'))
                mostrarAlerta('{{ session('success') }}', 'success');
            @endif

            @if(session('error'))
                mostrarAlerta('{{ session('error') }}', 'error');
            @endif
        });

        // Ajustar contenido cuando el sidebar se colapsa/expande
        function adjustContentForSidebar(collapsed = null) {
            const sidebar = document.querySelector('.app-sidebar');
            const content = document.querySelector('.dashboard-content');
            const headerContent = document.querySelector('.header-content');
            
            if (sidebar && content) {
                const isCollapsed = collapsed !== null ? collapsed : sidebar.classList.contains('collapsed');
                
                if (isCollapsed) {
                    content.style.marginLeft = '84px';
                    if (headerContent) {
                        headerContent.style.marginLeft = '84px';
                    }
                } else {
                    content.style.marginLeft = '280px';
                    if (headerContent) {
                        headerContent.style.marginLeft = '280px';
                    }
                }
            }
        }

        // Escuchar el evento personalizado del sidebar
        document.addEventListener('sidebarToggle', function(event) {
            adjustContentForSidebar(event.detail.collapsed);
        });

        // Ajustar inicialmente cuando el DOM esté listo
        document.addEventListener('DOMContentLoaded', function() {
            adjustContentForSidebar();
        });
    </script>
</x-app-layout>
