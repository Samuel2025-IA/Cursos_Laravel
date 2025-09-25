<x-app-layout>
    @section('title', 'Cursos - Diócesis de Apartadó')
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Cursos Disponibles') }}
        </h2>
        <p class="mt-1 text-sm text-gray-600">
            {{ __('Explora los cursos disponibles en el sistema') }}
        </p>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if($cursos->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($cursos as $curso)
                        <div class="bg-white shadow-lg rounded-xl overflow-hidden hover:shadow-xl transition-shadow duration-300">
                            <div class="bg-gradient-to-r from-[#2f9f37] to-[#2f9f37]/90 px-6 py-4">
                                <h3 class="text-lg font-semibold text-white">{{ $curso->nombre }}</h3>
                            </div>
                            <div class="p-6">
                                <p class="text-gray-600 mb-4">{{ $curso->descripcion ?? 'Sin descripción disponible' }}</p>
                                
                                <div class="space-y-2 mb-4">
                                    @if($curso->fecha_inicio)
                                        <div class="flex items-center text-sm text-gray-500">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                            Inicia: {{ \Carbon\Carbon::parse($curso->fecha_inicio)->format('d/m/Y') }}
                                        </div>
                                    @endif
                                    
                                    @if($curso->fecha_fin)
                                        <div class="flex items-center text-sm text-gray-500">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                            Termina: {{ \Carbon\Carbon::parse($curso->fecha_fin)->format('d/m/Y') }}
                                        </div>
                                    @endif
                                </div>

                                <div class="flex justify-between items-center">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-[#2f9f37]/10 text-[#2f9f37]">
                                        {{ ucfirst($curso->estado ?? 'Disponible') }}
                                    </span>
                                    
                                    <a href="{{ route('cursos.show', $curso) }}" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-[#273369] hover:bg-[#1e2a5a] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#273369] transition ease-in-out duration-150">
                                        Ver Detalles
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="bg-white shadow-lg rounded-xl overflow-hidden">
                    <div class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No hay cursos disponibles</h3>
                        <p class="mt-1 text-sm text-gray-500">Pronto se publicarán nuevos cursos.</p>
                        <div class="mt-6">
                            <a href="{{ route('dashboard') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-[#2f9f37] hover:bg-[#2f9f37]/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#2f9f37]">
                                <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                </svg>
                                Volver 
                            </a>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>


