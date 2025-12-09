@php
    $isPaginator = is_object($paginator) && method_exists($paginator, 'hasPages');
@endphp

@if ($isPaginator && $paginator->hasPages())
    <nav role="navigation" aria-label="Paginación del historial" class="flex items-center justify-center mt-6">
        <div class="flex flex-wrap items-center justify-center gap-1 sm:gap-2">
            {{-- Primera página --}}
            @if ($paginator->currentPage() > 2)
                <a href="{{ $paginator->url(1) }}" 
                   class="inline-flex items-center justify-center min-w-[2.25rem] h-9 px-2 sm:px-3 text-xs sm:text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 hover:border-gray-400 transition-all duration-200 shadow-sm hover:shadow">
                    <svg class="w-3 h-3 sm:w-4 sm:h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M15.707 15.707a1 1 0 01-1.414 0l-5-5a1 1 0 010-1.414l5-5a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 010 1.414zm-6 0a1 1 0 01-1.414 0l-5-5a1 1 0 010-1.414l5-5a1 1 0 011.414 1.414L5.414 10l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                    </svg>
                </a>
            @endif

            {{-- Botón Anterior --}}
            @if ($paginator->onFirstPage())
                <span class="inline-flex items-center justify-center min-w-[2.25rem] h-9 px-2 sm:px-3 text-xs sm:text-sm font-medium text-gray-400 bg-gray-100 border border-gray-200 rounded-lg cursor-not-allowed">
                    <svg class="w-3 h-3 sm:w-4 sm:h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                    </svg>
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" 
                   class="inline-flex items-center justify-center min-w-[2.25rem] h-9 px-2 sm:px-3 text-xs sm:text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 hover:border-gray-400 transition-all duration-200 shadow-sm hover:shadow">
                    <svg class="w-3 h-3 sm:w-4 sm:h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                    </svg>
                </a>
            @endif

            {{-- Números de página --}}
            @php
                $currentPage = $paginator->currentPage();
                $lastPage = $paginator->lastPage();
                $start = max(1, $currentPage - 1);
                $end = min($lastPage, $currentPage + 1);
                
                // Si estamos al principio, mostrar más páginas adelante
                if ($currentPage <= 2) {
                    $end = min($lastPage, 4);
                }
                
                // Si estamos al final, mostrar más páginas atrás
                if ($currentPage >= $lastPage - 1) {
                    $start = max(1, $lastPage - 3);
                }
            @endphp

            @for ($page = $start; $page <= $end; $page++)
                @if ($page == $currentPage)
                    <span class="inline-flex items-center justify-center min-w-[2.25rem] h-9 px-2 sm:px-3 text-xs sm:text-sm font-bold text-white bg-gradient-to-r from-[#2f9f37] to-[#27842f] border border-[#2f9f37] rounded-lg shadow-md">
                        {{ $page }}
                    </span>
                @else
                    <a href="{{ $paginator->url($page) }}" 
                       class="inline-flex items-center justify-center min-w-[2.25rem] h-9 px-2 sm:px-3 text-xs sm:text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-[#2f9f37] hover:text-white hover:border-[#2f9f37] transition-all duration-200 shadow-sm hover:shadow">
                        {{ $page }}
                    </a>
                @endif
            @endfor

            {{-- Botón Siguiente --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" 
                   class="inline-flex items-center justify-center min-w-[2.25rem] h-9 px-2 sm:px-3 text-xs sm:text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 hover:border-gray-400 transition-all duration-200 shadow-sm hover:shadow">
                    <svg class="w-3 h-3 sm:w-4 sm:h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                    </svg>
                </a>
            @else
                <span class="inline-flex items-center justify-center min-w-[2.25rem] h-9 px-2 sm:px-3 text-xs sm:text-sm font-medium text-gray-400 bg-gray-100 border border-gray-200 rounded-lg cursor-not-allowed">
                    <svg class="w-3 h-3 sm:w-4 sm:h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                    </svg>
                </span>
            @endif

            {{-- Última página --}}
            @if ($paginator->currentPage() < $lastPage - 1)
                <a href="{{ $paginator->url($lastPage) }}" 
                   class="inline-flex items-center justify-center min-w-[2.25rem] h-9 px-2 sm:px-3 text-xs sm:text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 hover:border-gray-400 transition-all duration-200 shadow-sm hover:shadow">
                    <svg class="w-3 h-3 sm:w-4 sm:h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10.293 15.707a1 1 0 010-1.414L14.586 10l-4.293-4.293a1 1 0 111.414-1.414l5 5a1 1 0 010 1.414l-5 5a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                        <path fill-rule="evenodd" d="M4.293 15.707a1 1 0 010-1.414L8.586 10 4.293 5.707a1 1 0 011.414-1.414l5 5a1 1 0 010 1.414l-5 5a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                    </svg>
                </a>
            @endif
        </div>
    </nav>

@endif

@if ($isPaginator)
    {{-- Información adicional --}}
    <div class="flex items-center justify-center mt-3 text-xs sm:text-sm text-gray-600">
        <span class="px-3 py-1.5 bg-gray-50 border border-gray-200 rounded-lg">
            Mostrando 
            <span class="font-semibold text-[#2f9f37]">{{ $paginator->firstItem() ?? 0 }}</span> 
            a 
            <span class="font-semibold text-[#2f9f37]">{{ $paginator->lastItem() ?? 0 }}</span> 
            de 
            <span class="font-semibold text-[#2f9f37]">{{ $paginator->total() }}</span> 
            registros
        </span>
    </div>
@endif






