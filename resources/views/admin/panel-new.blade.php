@extends('layouts.dashboard')

@section('title', 'Panel de Administración - Diócesis de Apartadó')

@php
    $currentUser = auth()->user();
    
    // Obtener datos para el panel de administración
    $activeCodes = \App\Models\InvitationCode::where('used', false)
        ->where('expires_at', '>', now())
        ->orderBy('created_at', 'desc')
        ->paginate(10);
        
    // Para el historial, obtenemos códigos que ya no están activos (usados o expirados)
    $historyCodes = \App\Models\InvitationCode::where(function($query) {
        $query->where('used', true)
              ->orWhere('expires_at', '<', now());
    })
    ->orderBy('created_at', 'desc')
    ->paginate(10);
        
    $stats = [
        'total_users' => \App\Models\User::count(),
        'admin_users' => \App\Models\User::where('rol', 'admin')->count(),
        'regular_users' => \App\Models\User::where('rol', 'user')->count(),
        'active_codes' => $activeCodes->total(),
        'used_codes' => \App\Models\InvitationCode::where('used', true)->count(),
        'expired_codes' => \App\Models\InvitationCode::where('expires_at', '<', now())->count(),
        'total_codes' => \App\Models\InvitationCode::count()
    ];
@endphp

@section('content')
<div class="space-y-6">
    <!-- Mensaje de bienvenida para admin -->
    @if(session('welcome_message'))
        <div class="bg-gradient-to-r from-blue-600 to-blue-700 text-white p-4 rounded-lg shadow-lg">
            <div class="flex items-center space-x-3">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div>
                    <h3 class="font-semibold">¡Bienvenido, Administrador!</h3>
                    <p class="text-sm opacity-90">{{ session('welcome_message') }}</p>
                </div>
            </div>
        </div>
    @endif

    <!-- Estadísticas del panel de administración -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon primary">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                </svg>
            </div>
            <h3 class="stat-value">{{ $stats['total_users'] }}</h3>
            <p class="stat-label">Total Usuarios</p>
            <div class="stat-change positive">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                </svg>
                <span>{{ $stats['admin_users'] }} admins, {{ $stats['regular_users'] }} usuarios</span>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon success">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                </svg>
            </div>
            <h3 class="stat-value">{{ $stats['active_codes'] }}</h3>
            <p class="stat-label">Códigos Activos</p>
            <div class="stat-change positive">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                </svg>
                <span>Disponibles</span>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon warning">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
            </div>
            <h3 class="stat-value">{{ $stats['used_codes'] }}</h3>
            <p class="stat-label">Códigos Usados</p>
            <div class="stat-change positive">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                </svg>
                <span>Registros exitosos</span>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon error">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <h3 class="stat-value">{{ $stats['expired_codes'] }}</h3>
            <p class="stat-label">Códigos Expirados</p>
            <div class="stat-change negative">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6" />
                </svg>
                <span>Sin usar</span>
            </div>
        </div>
    </div>

    <!-- Gestión de Invitaciones -->
    <div class="dashboard-card">
        <div class="dashboard-card-header">
            <h3 class="dashboard-card-title flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
                Gestión de Invitaciones
            </h3>
        </div>
        <div class="dashboard-card-content">
            <form id="invitationForm" action="{{ route('admin.send-invitations') }}" method="POST" class="space-y-4" onsubmit="return validarFormulario()">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <div>
                        <label for="email1" class="form-label">Correo 1</label>
                        <input type="email" id="email1" name="emails[]" class="form-input email-input" placeholder="usuario1@ejemplo.com">
                    </div>
                    <div>
                        <label for="email2" class="form-label">Correo 2</label>
                        <input type="email" id="email2" name="emails[]" class="form-input email-input" placeholder="usuario2@ejemplo.com">
                    </div>
                    <div>
                        <label for="email3" class="form-label">Correo 3</label>
                        <input type="email" id="email3" name="emails[]" class="form-input email-input" placeholder="usuario3@ejemplo.com">
                    </div>
                    <div>
                        <label for="email4" class="form-label">Correo 4</label>
                        <input type="email" id="email4" name="emails[]" class="form-input email-input" placeholder="usuario4@ejemplo.com">
                    </div>
                    <div>
                        <label for="email5" class="form-label">Correo 5</label>
                        <input type="email" id="email5" name="emails[]" class="form-input email-input" placeholder="usuario5@ejemplo.com">
                    </div>
                </div>
                
                <div class="flex items-center justify-between pt-4 gap-4">
                    <p class="text-sm text-gray-500">
                        Los códigos de invitación expiran en 7 días
                    </p>
                    <button type="submit" id="submitBtn" class="btn btn-primary">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        Enviar Invitaciones
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Códigos Activos -->
    <div class="dashboard-card">
        <div class="dashboard-card-header">
            <h3 class="dashboard-card-title flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                Códigos de Invitación Activos
            </h3>
        </div>
        <div class="dashboard-card-content">
            <!-- Buscador de códigos activos -->
            <div class="mb-4">
                <div class="flex gap-2">
                    <input type="text" 
                           id="activeSearchInput"
                           placeholder="Buscar por email o código..." 
                           class="flex-1 form-input">
                    <button type="button" 
                            id="clearActiveSearch"
                            class="btn btn-secondary"
                            style="display: none;">
                        Limpiar
                    </button>
                </div>
                <div id="activeSearchResults" class="mt-2 text-sm text-gray-600" style="display: none;">
                    <!-- Los resultados aparecerán aquí -->
                </div>
            </div>
            
            <!-- Tabla de códigos activos -->
            @if($activeCodes->count() > 0)
                <div class="overflow-x-auto">
                    <table class="dashboard-table">
                        <thead>
                            <tr>
                                <th>Email</th>
                                <th>Código</th>
                                <th>Estado</th>
                                <th>Expira</th>
                                <th>Creado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($activeCodes as $code)
                                <tr>
                                    <td class="font-medium">{{ $code->email }}</td>
                                    <td>
                                        <code class="bg-gray-100 px-2 py-1 rounded text-sm">{{ $code->code }}</code>
                                    </td>
                                    <td>
                                        @if($code->used)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                Usado
                                            </span>
                                        @elseif($code->expires_at->isPast())
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                Expirado
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                Activo
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-sm text-gray-500">
                                        {{ $code->expires_at->format('d/m/Y H:i') }}
                                    </td>
                                    <td class="text-sm text-gray-500">
                                        {{ $code->created_at->format('d/m/Y H:i') }}
                                    </td>
                                    <td>
                                        <div class="flex space-x-2">
                                            <button onclick="copyToClipboard('{{ $code->code }}')" 
                                                    class="btn btn-sm btn-secondary"
                                                    title="Copiar código">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                                </svg>
                                            </button>
                                            <form method="POST" action="{{ route('admin.delete-invitation', $code) }}" 
                                                  style="display: inline;" 
                                                  onsubmit="return confirm('¿Estás seguro de que quieres eliminar este código?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="btn btn-sm btn-error"
                                                        title="Eliminar código">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <!-- Paginación -->
                <div class="mt-6">
                    {{ $activeCodes->links() }}
                </div>
            @else
                <div class="text-center py-8">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <h3 class="mt-2 text-lg font-medium text-gray-900">No hay códigos activos</h3>
                    <p class="mt-1 text-sm text-gray-500">Los códigos de invitación aparecerán aquí cuando los crees.</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Historial de Códigos Usados/Expirados -->
    @if($historyCodes->count() > 0)
    <div class="dashboard-card">
        <div class="dashboard-card-header">
            <h3 class="dashboard-card-title flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
                Historial de Códigos Usados/Expirados
            </h3>
        </div>
        <div class="dashboard-card-content">
            <!-- Buscador de historial -->
            <div class="mb-4">
                <div class="flex gap-2">
                    <input type="text" 
                           id="historySearchInput"
                           placeholder="Buscar en historial..." 
                           class="flex-1 form-input">
                    <button type="button" 
                            id="clearHistorySearch"
                            class="btn btn-secondary"
                            style="display: none;">
                        Limpiar
                    </button>
                </div>
            </div>
            
            <!-- Tabla de historial -->
            <div class="overflow-x-auto">
                <table class="dashboard-table">
                    <thead>
                        <tr>
                            <th>Email</th>
                            <th>Código</th>
                            <th>Estado</th>
                            <th>Actualizado</th>
                            <th>Creado</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($historyCodes as $code)
                            <tr>
                                <td class="font-medium">{{ $code->email }}</td>
                                <td>
                                    <code class="bg-gray-100 px-2 py-1 rounded text-sm">{{ $code->code }}</code>
                                </td>
                                <td>
                                    @if($code->used)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            Usado
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            No usado
                                        </span>
                                    @endif
                                </td>
                                    <td class="text-sm text-gray-500">
                                        {{ $code->updated_at->format('d/m/Y H:i') }}
                                    </td>
                                <td class="text-sm text-gray-500">
                                    {{ $code->created_at->format('d/m/Y H:i') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Paginación del historial -->
            <div class="mt-6">
                {{ $historyCodes->links() }}
            </div>
        </div>
    </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
    // Pasar rutas al JavaScript
    window.adminRoutes = {
        searchActiveCodes: '{{ route("admin.search-active-codes") }}',
        searchHistoryCodes: '{{ route("admin.search-history-codes") }}'
    };
    
    // Función para validar formulario de invitaciones
    function validarFormulario() {
        const emailInputs = document.querySelectorAll('.email-input');
        let hasValidEmail = false;
        
        emailInputs.forEach(input => {
            if (input.value.trim() !== '') {
                hasValidEmail = true;
            }
        });
        
        if (!hasValidEmail) {
            dashboardUtils.showAlert('Debe ingresar al menos un correo electrónico válido', 'error');
            return false;
        }
        
        // Mostrar loading en el botón
        const submitBtn = document.getElementById('submitBtn');
        dashboardUtils.setButtonLoading(submitBtn, true);
        
        return true;
    }
    
    // Función para copiar código al portapapeles
    function copyToClipboard(text) {
        navigator.clipboard.writeText(text).then(() => {
            dashboardUtils.showAlert('Código copiado al portapapeles', 'success');
        }).catch(err => {
            console.error('Error al copiar:', err);
            dashboardUtils.showAlert('Error al copiar al portapapeles', 'error');
        });
    }
    
    document.addEventListener('DOMContentLoaded', function() {
        // Auto-ocultar mensaje de bienvenida después de 5 segundos
        const welcomeMessage = document.querySelector('.bg-gradient-to-r.from-blue-600');
        if (welcomeMessage) {
            setTimeout(() => {
                welcomeMessage.style.opacity = '0';
                setTimeout(() => {
                    welcomeMessage.remove();
                }, 300);
            }, 5000);
        }
    });
</script>
@endpush
