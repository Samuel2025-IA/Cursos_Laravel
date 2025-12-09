@if($codes->count() > 0)
    {{-- Vista de Tabla para Desktop (oculta en móvil) --}}
    <div class="hidden md:block overflow-x-auto -mx-6 px-6">
        <div class="min-w-full inline-block align-middle">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-3 lg:px-6 py-2 lg:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                        <th class="px-3 lg:px-6 py-2 lg:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden lg:table-cell">Código</th>
                        <th class="px-3 lg:px-6 py-2 lg:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                        <th class="px-3 lg:px-6 py-2 lg:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden xl:table-cell">Expira</th>
                        <th class="px-3 lg:px-6 py-2 lg:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden xl:table-cell">Creado</th>
                        <th class="px-3 lg:px-6 py-2 lg:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($codes as $code)
                        <tr>
                            <td class="px-3 lg:px-6 py-2 lg:py-4 text-xs lg:text-sm text-gray-900 break-words max-w-xs truncate" title="{{ $code->email }}">{{ $code->email }}</td>
                            <td class="px-3 lg:px-6 py-2 lg:py-4 text-xs lg:text-sm font-mono text-gray-900 hidden lg:table-cell">{{ $code->code }}</td>
                            <td class="px-3 lg:px-6 py-2 lg:py-4 whitespace-nowrap">
                                @if($code->used)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                        Usado
                                    </span>
                                @elseif($code->expires_at->isPast())
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        Expirado
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-[#2f9f37]/10 text-[#2f9f37]">
                                        Activo
                                    </span>
                                @endif
                            </td>
                            <td class="px-3 lg:px-6 py-2 lg:py-4 text-xs lg:text-sm text-gray-500 whitespace-nowrap hidden xl:table-cell">{{ $code->expires_at->format('d/m/Y H:i') }}</td>
                            <td class="px-3 lg:px-6 py-2 lg:py-4 text-xs lg:text-sm text-gray-500 whitespace-nowrap hidden xl:table-cell">{{ $code->created_at->format('d/m/Y H:i') }}</td>
                            <td class="px-3 lg:px-6 py-2 lg:py-4 whitespace-nowrap text-xs lg:text-sm font-medium">
                                <form action="{{ route('admin.delete-invitation', $code->id) }}" method="POST" class="inline delete-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="text-red-600 hover:text-red-900 delete-btn text-xs lg:text-sm" data-email="{{ $code->email }}" data-status="{{ $code->used ? 'usado' : 'activo' }}">
                                        Eliminar
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- Vista de Cards para Móvil (oculta en desktop) --}}
    <div class="md:hidden space-y-4">
        @foreach($codes as $code)
            <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm hover:shadow-md transition-shadow">
                {{-- Email --}}
                <div class="mb-3">
                    <div class="text-xs font-medium text-gray-500 uppercase mb-1">Email</div>
                    <div class="text-sm font-medium text-gray-900 break-all">{{ $code->email }}</div>
                </div>

                {{-- Código y Estado en la misma fila --}}
                <div class="grid grid-cols-2 gap-3 mb-3">
                    <div>
                        <div class="text-xs font-medium text-gray-500 uppercase mb-1">Código</div>
                        <div class="text-sm font-mono text-gray-900">{{ $code->code }}</div>
                    </div>
                    <div>
                        <div class="text-xs font-medium text-gray-500 uppercase mb-1">Estado</div>
                        <div>
                            @if($code->used)
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                    Usado
                                </span>
                            @elseif($code->expires_at->isPast())
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    Expirado
                                </span>
                            @else
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-[#2f9f37]/10 text-[#2f9f37]">
                                    Activo
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Fechas --}}
                <div class="grid grid-cols-2 gap-3 mb-3">
                    <div>
                        <div class="text-xs font-medium text-gray-500 uppercase mb-1">Expira</div>
                        <div class="text-xs text-gray-700">{{ $code->expires_at->format('d/m/Y H:i') }}</div>
                    </div>
                    <div>
                        <div class="text-xs font-medium text-gray-500 uppercase mb-1">Creado</div>
                        <div class="text-xs text-gray-700">{{ $code->created_at->format('d/m/Y H:i') }}</div>
                    </div>
                </div>

                {{-- Botón de Acción --}}
                <div class="pt-3 border-t border-gray-200">
                    <form action="{{ route('admin.delete-invitation', $code->id) }}" method="POST" class="delete-form">
                        @csrf
                        @method('DELETE')
                        <button type="button" class="w-full inline-flex items-center justify-center px-4 py-2 bg-red-50 border border-red-200 rounded-md font-medium text-sm text-red-600 hover:bg-red-100 hover:text-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-all delete-btn" data-email="{{ $code->email }}" data-status="{{ $code->used ? 'usado' : 'activo' }}">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                            Eliminar Código
                        </button>
                    </form>
                </div>
            </div>
        @endforeach
    </div>
@else
    <div class="text-center py-8">
        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
        </svg>
        <h3 class="mt-2 text-sm font-medium text-gray-900">No se encontraron códigos</h3>
        <p class="mt-1 text-sm text-gray-500">Intenta con otros términos de búsqueda.</p>
    </div>
@endif






