@extends('layouts.dashboard')

@section('title', 'Leer Protocolos - Diócesis de Apartadó')

@section('content')
<div class="space-y-6">
    <!-- Encabezado -->
    <div class="mb-6">
        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-2">Protocolos Diocesanos</h1>
        <p class="text-gray-600">Documento oficial de protocolos y normas de la Diócesis de Apartadó</p>
    </div>

    @php
        $seccionActual = request()->get('seccion', 0);
        $seccionActual = (int)$seccionActual;
        if ($seccionActual < 0 || $seccionActual >= count($secciones)) {
            $seccionActual = 0;
        }
        $seccionAnterior = $seccionActual > 0 ? $seccionActual - 1 : null;
        $seccionSiguiente = $seccionActual < count($secciones) - 1 ? $seccionActual + 1 : null;
    @endphp

    <!-- Navegación de secciones -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 mb-6">
        <div class="flex flex-wrap gap-2">
            @foreach($secciones as $index => $seccion)
                <a href="?seccion={{ $index }}" 
                   class="px-4 py-2 rounded-lg transition-all duration-200 text-sm font-medium seccion-link {{ $index == $seccionActual ? 'bg-[#2f9f37] text-white' : 'bg-gray-100 hover:bg-[#2f9f37] hover:text-white text-gray-700' }}" 
                   data-seccion="{{ $index }}">
                    {{ $seccion['titulo'] }}
                </a>
            @endforeach
        </div>
    </div>

    <!-- Botones de navegación -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 mb-6 flex justify-between items-center">
        <div>
            @if($seccionAnterior !== null)
                <a href="?seccion={{ $seccionAnterior }}" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-all duration-200 font-medium flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                    Anterior
                </a>
            @else
                <span class="px-6 py-2 bg-gray-300 text-gray-500 rounded-lg font-medium flex items-center gap-2 cursor-not-allowed">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                    Anterior
                </span>
            @endif
        </div>
        <div class="text-gray-600 font-medium">
            Sección {{ $seccionActual + 1 }} de {{ count($secciones) }}
        </div>
        <div>
            @if($seccionSiguiente !== null)
                <a href="?seccion={{ $seccionSiguiente }}" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-all duration-200 font-medium flex items-center gap-2">
                    Siguiente
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>
            @else
                <span class="px-6 py-2 bg-gray-300 text-gray-500 rounded-lg font-medium flex items-center gap-2 cursor-not-allowed">
                    Siguiente
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </span>
            @endif
        </div>
    </div>

    <!-- Contenido de las secciones -->
    <div class="space-y-8">
        @foreach($secciones as $index => $seccion)
            @if($index == $seccionActual)
            @php
                $isProhibiciones = $seccion['id'] === 'prohibiciones-trabajadores-pastorales';
                $isProteccion = $seccion['id'] === 'proteccion-ninos-adultos-vulnerables';
                $isPresentacion = $seccion['id'] === 'presentacion';
                $isCodigoConducta = $seccion['id'] === 'codigo-conducta';
                $isCodigoEtica = $seccion['id'] === 'codigo-etica';
                $isConflictoIntereses = $seccion['id'] === 'conflicto-intereses';
                $isAcosoLaboral = $seccion['id'] === 'acoso-laboral';
                $isComiteConvivencia = $seccion['id'] === 'comite-convivencia';
                $isDenunciaAcoso = $seccion['id'] === 'denuncia-acoso';
                $isEticaMedioAmbiental = $seccion['id'] === 'etica-medio-ambiental';
                $isImplementacion = $seccion['id'] === 'implementacion';
                $isAnexo1 = $seccion['id'] === 'anexo-1';
                $isSeHablaDe = $seccion['id'] === 'se-habla-de';
                $isImplicados = $seccion['id'] === 'implicados';
                $isPoliticaCooperacion = $seccion['id'] === 'politica-cooperacion-solidaria';
                $isProtocoloAnticorrupcion = $seccion['id'] === 'protocolo-anticorrupcion';
                $isProtocoloEquidadGenero = $seccion['id'] === 'protocolo-equidad-genero';
                $isManualRelacionamiento = $seccion['id'] === 'manual-relacionamiento-institucional';
                $isNormasFuerzasArmadas = $seccion['id'] === 'normas-fuerzas-armadas';
                $isPoliticaSeguridadSalud = $seccion['id'] === 'politica-seguridad-salud-trabajo';
                $isAzul = $isProteccion || $isPresentacion || $isCodigoConducta || $isCodigoEtica || $isConflictoIntereses || $isAcosoLaboral || $isComiteConvivencia || $isDenunciaAcoso || $isEticaMedioAmbiental || $isPoliticaCooperacion || $isProtocoloAnticorrupcion || $isProtocoloEquidadGenero || $isManualRelacionamiento || $isNormasFuerzasArmadas || $isPoliticaSeguridadSalud;
            @endphp
            <div id="seccion-{{ $seccion['id'] }}" class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                <!-- Encabezado de la sección -->
                @if($isAnexo1)
                    <div class="px-6 py-4 flex items-center gap-3">
                        <div class="bg-blue-600 px-3 py-1 rounded">
                            <span class="text-white font-bold">{{ $seccion['titulo'] }}</span>
                        </div>
                        <span class="text-blue-600 font-bold text-xl">+</span>
                        <span class="text-gray-700 font-bold text-lg">{{ isset($seccion['titulo_principal']) ? $seccion['titulo_principal'] : '' }}</span>
                    </div>
                @elseif($isImplementacion)
                    <div class="px-6 py-4 flex items-center">
                        <div class="w-1 h-8 bg-blue-600 mr-4"></div>
                        <h2 class="text-[20px] font-bold text-blue-600">{!! $seccion['titulo'] !!}</h2>
                    </div>
                @elseif($isProhibiciones)
                    <div class="px-6 py-4 flex items-center">
                        <div class="bg-gradient-to-r from-red-600 to-red-700 px-4 py-2 rounded">
                            <span class="text-xl font-bold text-white">8. Está estrictamente PROHIBIDO</span>
                        </div>
                        <span class="text-xl font-bold text-red-700 ml-2">para los trabajadores pastorales:</span>
                    </div>
                @elseif($isSeHablaDe || $isImplicados)
                    <div class="px-6 py-4" style="background-color: #1D4ED8;">
                        <h2 class="text-xl font-bold text-white">{!! $seccion['titulo'] !!}</h2>
                    </div>
                @else
                    <div class="{{ $isAzul ? 'bg-blue-900' : 'bg-gradient-to-r from-[#2f9f37] to-[#27842f]' }} px-6 py-4">
                        @php
                            // Detectar indicativos numéricos en títulos principales y resaltarlos en blanco y negrita
                            $tituloPrincipal = $seccion['titulo'];
                            $tituloPrincipalFormateado = preg_replace('/(\d+\.)/', '<span class="text-white font-bold">$1</span>', $tituloPrincipal);
                        @endphp
                        <h2 class="text-xl font-bold text-white">{!! $tituloPrincipalFormateado !!}</h2>
                    </div>
                @endif
                
                <!-- Imagen de la sección (si existe) -->
                @if(isset($seccion['imagen']))
                    <div class="flex justify-center py-6 bg-white">
                        <img src="{{ asset('img/' . $seccion['imagen']) }}" 
                             alt="{{ $seccion['titulo'] }}"
                             class="rounded-2xl shadow-lg border-4 border-gray-200 max-w-md w-full h-auto object-cover">
                    </div>
                @endif
                
                <!-- Contenido de la sección -->
                <div class="p-6 {{ $isProhibiciones ? 'bg-red-50' : '' }}">
                    @if(isset($seccion['contenido']))
                        <div class="prose max-w-none {{ $isProhibiciones ? 'border-l-4 border-red-600 pl-4' : '' }}">
                            @php
                                // Detectar indicativos alfabéticos (a., b., c., etc.) y resaltarlos en azul oscuro y negrita
                                $contenido = $seccion['contenido'];
                                
                                // Si es se-habla-de, aplicar estilos a los subtítulos (texto que termina en :)
                                if ($isSeHablaDe) {
                                    // Detectar cada subtítulo que comienza una línea seguido de su contenido
                                    // Usar preg_replace_callback para procesar cada coincidencia
                                    $contenidoFormateado = preg_replace_callback(
                                        '/^([A-ZÁÉÍÓÚÑ][^:\n]+:)\s*(.*?)(?=^[A-ZÁÉÍÓÚÑ][^:\n]+:|$)/ms',
                                        function($matches) {
                                            $subtitulo = trim($matches[1]);
                                            $contenidoSubtitulo = trim($matches[2]);
                                            
                                            $resultado = '<div class="pl-3 mb-4 mt-3" style="border-left: 4px solid #1D4ED8;">';
                                            $resultado .= '<span class="font-bold" style="color: #1D4ED8;">' . $subtitulo . '</span>';
                                            if (!empty($contenidoSubtitulo)) {
                                                $resultado .= ' <span class="text-blue-700">' . nl2br($contenidoSubtitulo) . '</span>';
                                            }
                                            $resultado .= '</div>';
                                            return $resultado;
                                        },
                                        $contenido
                                    );
                                } else {
                                    // Patrón mejorado para capturar indicativos alfabéticos en diferentes contextos
                                    $contenidoFormateado = preg_replace('/(^|\n|\r|•\s*)([a-z])\.\s/', '$1<span class="text-blue-800 font-bold">$2.</span> ', $contenido);
                                }
                                
                                // Si es anexo-1, separar el contenido de la nota
                                if ($isAnexo1 && isset($seccion['nota'])) {
                                    // El contenido termina en "El maltrato puede darse por acción o por omisión-negligencia."
                                    // Todo lo que sigue va en el recuadro azul
                                    $contenidoFormateado = $contenidoFormateado;
                                }
                            @endphp
                            <p class="{{ $isImplementacion ? 'text-blue-600' : ($isProhibiciones ? 'text-red-800 font-semibold' : (($isAzul || $isSeHablaDe || $isImplicados) ? 'text-blue-700' : 'text-gray-700')) }} leading-relaxed whitespace-pre-line">{!! $contenidoFormateado !!}</p>
                            
                            @if($isAnexo1 && isset($seccion['nota']))
                                <div class="bg-blue-700 text-white p-4 rounded-lg mt-4 whitespace-pre-line border-2 border-blue-800">
                                    <p class="text-white leading-relaxed">{!! $seccion['nota'] !!}</p>
                                </div>
                            @endif
                        </div>
                    @endif

                    @if(isset($seccion['subsecciones']))
                        <div class="space-y-6 mt-6">
                            @foreach($seccion['subsecciones'] as $subIndex => $subseccion)
                                <div class="border-l-4 {{ $isProhibiciones ? 'border-red-600' : ($isAzul ? 'border-blue-600' : 'border-[#2f9f37]') }} pl-4">
                                    @php
                                        // Detectar indicativos numéricos (1.1, 1.2, 2.3, etc.) y resaltarlos en azul oscuro y negrita
                                        $titulo = $subseccion['titulo'];
                                        // Primero detectar números simples al inicio (1., 2., 3., etc.)
                                        $titulo = preg_replace('/^(\d+)\.\s/', '<span class="text-blue-800 font-bold">$1.</span> ', $titulo);
                                        // Luego detectar números con decimales (1.1, 1.2, 2.3, etc.)
                                        $tituloFormateado = preg_replace('/(\d+\.\d+(?:\.\d+)?)/', '<span class="text-blue-800 font-bold">$1</span>', $titulo);
                                        // Ocultar el título si es "Compromiso de la Diócesis", "Compromisos que orientan...", "Cambio Climático" o "7.1. La importancia..." porque ya está en el recuadro
                                        $mostrarTitulo = $subseccion['titulo'] !== 'Compromiso de la Diócesis' && $subseccion['titulo'] !== 'Compromisos que orientan la implementación de Programas y Proyectos en los territorios' && $subseccion['titulo'] !== 'Cambio Climático' && $subseccion['titulo'] !== '7.1. La importancia de protección de niños, niñas, adolescentes y adultos vulnerables';
                                    @endphp
                                    @if($mostrarTitulo)
                                        @if($isProhibiciones && strpos($subseccion['titulo'], 'a) Para asegurar la concientización') !== false)
                                            <div class="bg-gradient-to-r from-red-600 to-red-700 px-4 py-2 rounded mb-3 inline-block">
                                                <h3 class="text-lg font-bold text-white">{!! $tituloFormateado !!}</h3>
                                            </div>
                                        @elseif($subseccion['titulo'] === 'Conductas que atentan contra la equidad de género')
                                            <div class="border-2 border-blue-800 px-4 py-2 rounded-xl mb-3 inline-block">
                                                <h3 class="text-lg font-bold text-blue-800">{!! $tituloFormateado !!}</h3>
                                            </div>
                                        @else
                                            <h3 class="text-lg font-bold {{ $isAzul ? 'text-blue-800' : 'text-gray-900' }} mb-3">{!! $tituloFormateado !!}</h3>
                                        @endif
                                    @endif
                                    <div class="prose max-w-none">
                                        @php
                                            // Detectar indicativos alfabéticos (a., b., c., etc.) y resaltarlos en azul oscuro y negrita
                                            $contenidoSubseccion = $subseccion['contenido'];
                                            
                                            // Detectar el texto específico del principio rector y ponerlo en recuadro azul
                                            $textoPrincipioRector = 'El principio rector que inspira todo comportamiento ético es el la dignidad de la persona humana "toda vida humana es sagrada desde la concepción hasta su muerte natural, su dignidad y la naturaleza social del individuo, son el cimiento y la inspiración de una visión moral de la sociedad que se logrará en la medida que se vivan los siguientes valores y principios:';
                                            
                                            if (strpos($contenidoSubseccion, $textoPrincipioRector) !== false) {
                                                $contenidoSubseccion = str_replace(
                                                    $textoPrincipioRector,
                                                    '<div class="bg-blue-700 text-white p-4 rounded-lg mb-4 font-semibold">' . $textoPrincipioRector . '</div>',
                                                    $contenidoSubseccion
                                                );
                                            }
                                            
                                            // Detectar el texto "Por último, la Ley considera como conductas que constituyen acoso laboral:" y ponerlo en recuadro azul
                                            $textoConductasAcoso = 'Por último, la Ley considera como conductas que constituyen acoso laboral:';
                                            
                                            if (strpos($contenidoSubseccion, $textoConductasAcoso) !== false) {
                                                $contenidoSubseccion = str_replace(
                                                    $textoConductasAcoso,
                                                    '<div class="bg-blue-700 text-white p-4 rounded-lg mb-4 font-semibold">' . $textoConductasAcoso . '</div>',
                                                    $contenidoSubseccion
                                                );
                                            }
                                            
                                            // Detectar el texto de la definición legal del acoso sexual y ponerlo en recuadro azul claro con negrita
                                            $textoDefinicionLegal = '"He quien, para su beneficio o el de un tercero, y valiéndose de su manifiesta superioridad o relaciones de autoridad o poder, edad, sexo, labor, social, familiar o económica, acose, persiga, hostigue o asedie, con fines sexuales no consentidos, a otra persona, incurrirá en prisión de uno (1) a tres (3) años."';
                                            
                                            if (strpos($contenidoSubseccion, $textoDefinicionLegal) !== false) {
                                                $textoFormateado = '<div class="bg-blue-100 text-blue-900 p-4 rounded-lg mb-4 whitespace-pre-line"><span class="font-bold">' . $textoDefinicionLegal . '</span></div>';
                                                $contenidoSubseccion = str_replace(
                                                    $textoDefinicionLegal,
                                                    $textoFormateado,
                                                    $contenidoSubseccion
                                                );
                                            }
                                            
                                            // Detectar "1. Quid Pro Quo (Una cosa por otra):" y "2. Ambiente laboral hostil:" y ponerlos en negrita
                                            $contenidoSubseccion = preg_replace(
                                                '/(1\.\s*Quid Pro Quo \(Una cosa por otra\):)/',
                                                '<span class="font-bold">$1</span>',
                                                $contenidoSubseccion
                                            );
                                            $contenidoSubseccion = preg_replace(
                                                '/(2\.\s*Ambiente laboral hostil:)/',
                                                '<span class="font-bold">$1</span>',
                                                $contenidoSubseccion
                                            );
                                            
                                            // Detectar "Laudato Sí, 13." y ponerlo en negrita
                                            $contenidoSubseccion = preg_replace(
                                                '/(Laudato Sí, 13\.)/',
                                                '<span class="font-bold">$1</span>',
                                                $contenidoSubseccion
                                            );
                                            
                                            // Detectar subtítulos en "Los criterios que orientan la implementación de esta directriz son" y ponerlos en negrita
                                            if ($subseccion['titulo'] === 'Los criterios que orientan la implementación de esta directriz son') {
                                                // Poner en negrita los números seguidos de texto hasta dos puntos (1. Asumir..., 2. El respeto..., etc.)
                                                $contenidoSubseccion = preg_replace(
                                                    '/^(\d+\.\s+[^:\n]+:)/m',
                                                    '<span class="font-bold">$1</span>',
                                                    $contenidoSubseccion
                                                );
                                                // Capturar subtítulos que terminan en punto y están solos en la línea (como el 4.)
                                                $contenidoSubseccion = preg_replace(
                                                    '/^(\d+\.\s+[^\n]+\.)$/m',
                                                    '<span class="font-bold">$1</span>',
                                                    $contenidoSubseccion
                                                );
                                                // Poner en negrita "Conservación de la biodiversidad y gestión sostenible de los recursos naturales:"
                                                $contenidoSubseccion = preg_replace(
                                                    '/(Conservación de la biodiversidad y gestión sostenible de los recursos naturales:)/',
                                                    '<span class="font-bold">$1</span>',
                                                    $contenidoSubseccion
                                                );
                                                
                                                // Poner "Nota: Decreto 2372..." y "Convenio Ramsar..." en recuadro azul
                                                $textoNota = 'Nota: Decreto 2372 de 2010, por el cual se reglamenta el Decreto Ley 2811 de 1974, Ley 99 de 1995, Ley 165 de 1994 y decreto Ley 216 de 2003; en relación con el Sistema Nacional de Áreas Protegidas, las categorías de manejo que lo conforman y se dictan otras disposiciones.';
                                                $textoRamsar = 'Convenio Ramsar protección de humedales, en su artículo número 1 del protocolo "define una zona húmeda o humedal como cualquier extensión de marisma, pantano o turbera, o superficie cubierta de aguas, sean estas de régimen natural o artificial, permanentes o temporales, estancadas o corrientes, dulces, salobres o saladas, incluidas las extensiones de aguas marinas cuya profundidad en marea baja no exceda de seis metros" (Ramsar, 1971).';
                                                
                                                // Reemplazar el texto de la Nota
                                                if (strpos($contenidoSubseccion, $textoNota) !== false) {
                                                    $contenidoSubseccion = str_replace(
                                                        $textoNota,
                                                        '<div class="bg-blue-700 text-white p-4 rounded-lg mb-4 whitespace-pre-line">' . $textoNota . '</div>',
                                                        $contenidoSubseccion
                                                    );
                                                }
                                                
                                                // Reemplazar el texto del Convenio Ramsar
                                                if (strpos($contenidoSubseccion, $textoRamsar) !== false) {
                                                    $contenidoSubseccion = str_replace(
                                                        $textoRamsar,
                                                        '<div class="bg-blue-700 text-white p-4 rounded-lg mb-4 whitespace-pre-line">' . $textoRamsar . '</div>',
                                                        $contenidoSubseccion
                                                    );
                                                }
                                            }
                                            
                                            // Detectar subtítulos de valores (La justicia:, El Bien Común:, etc.) y ponerlos en negrita azul oscuro
                                            $valores = ['La justicia:', 'El Bien Común:', 'El desarrollo humano integral:', 'La compasión:', 'La opción preferencial por los pobres y los oprimidos:', 'El respeto:', 'Solidaridad:'];
                                            foreach ($valores as $valor) {
                                                $contenidoSubseccion = preg_replace(
                                                    '/(' . preg_quote($valor, '/') . ')/',
                                                    '<span class="text-blue-800 font-bold">$1</span>',
                                                    $contenidoSubseccion
                                                );
                                            }
                                            
                                            // Detectar la sección "2.2.2. Principios" y poner desde inicio hasta letra "i" en recuadro azul
                                            if ($subseccion['titulo'] === '2.2.2. Principios') {
                                                // Dividir el contenido en líneas
                                                $lineas = explode("\n", $contenidoSubseccion);
                                                $contenidoRecuadro = '';
                                                $contenidoRestante = '';
                                                $enRecuadro = true;
                                                
                                                foreach ($lineas as $linea) {
                                                    // Detectar si llegamos a la letra "j" (después de "i")
                                                    if (preg_match('/^j\.\s/', trim($linea))) {
                                                        $enRecuadro = false;
                                                    }
                                                    
                                                    if ($enRecuadro) {
                                                        $contenidoRecuadro .= $linea . "\n";
                                                    } else {
                                                        $contenidoRestante .= $linea . "\n";
                                                    }
                                                }
                                                
                                                // Formatear el contenido del recuadro (letras en blanco para que se vean en fondo azul)
                                                $contenidoRecuadroFormateado = preg_replace('/(^|\n)([a-z])\.\s/', '$1<span class="text-white font-bold">$2.</span> ', trim($contenidoRecuadro));
                                                // Asegurar que todo el texto dentro del recuadro sea blanco
                                                $contenidoRecuadroFormateado = '<span class="text-white">' . $contenidoRecuadroFormateado . '</span>';
                                                // Formatear el contenido restante
                                                $contenidoRestanteFormateado = preg_replace('/(^|\n)([a-z])\.\s/', '$1<span class="text-blue-800 font-bold">$2.</span> ', trim($contenidoRestante));
                                                
                                                $contenidoSubseccionFormateado = '<div class="bg-blue-700 text-white p-4 rounded-lg mb-4 whitespace-pre-line">' . $contenidoRecuadroFormateado . '</div>' . "\n" . $contenidoRestanteFormateado;
                                            } else {
                                                // Detectar la subsección "Conductas que constituyen acoso laboral" y poner i) a n) en recuadro azul claro
                                                if ($subseccion['titulo'] === 'Conductas que constituyen acoso laboral') {
                                                    // Dividir el contenido en líneas
                                                    $lineas = explode("\n", $contenidoSubseccion);
                                                    $contenidoRecuadro = '';
                                                    $contenidoRestante = '';
                                                    $enRecuadro = false;
                                                    
                                                    foreach ($lineas as $linea) {
                                                        // Detectar si llegamos a la letra "i)"
                                                        if (preg_match('/^i\)\s/', trim($linea))) {
                                                            $enRecuadro = true;
                                                        }
                                                        // Detectar si llegamos a la letra "o)" (después de "n)")
                                                        if (preg_match('/^o\)\s/', trim($linea))) {
                                                            $enRecuadro = false;
                                                        }
                                                        
                                                        if ($enRecuadro) {
                                                            $contenidoRecuadro .= $linea . "\n";
                                                        } else {
                                                            $contenidoRestante .= $linea . "\n";
                                                        }
                                                    }
                                                    
                                                    // Formatear el contenido del recuadro (letras en azul oscuro para que se vean en fondo azul claro)
                                                    $contenidoRecuadroFormateado = preg_replace('/(^|\n)([a-z])\)\s/', '$1<span class="text-blue-800 font-bold">$2)</span> ', trim($contenidoRecuadro));
                                                    // Asegurar que todo el texto dentro del recuadro tenga color apropiado
                                                    $contenidoRecuadroFormateado = '<span class="text-blue-900">' . $contenidoRecuadroFormateado . '</span>';
                                                    // Formatear el contenido restante
                                                    $contenidoRestanteFormateado = preg_replace('/(^|\n|\r|•\s*)([a-z])\)\s/', '$1<span class="text-blue-800 font-bold">$2)</span> ', trim($contenidoRestante));
                                                    
                                                    $contenidoSubseccionFormateado = '<div class="bg-blue-200 text-blue-900 p-4 rounded-lg mb-4 whitespace-pre-line">' . $contenidoRecuadroFormateado . '</div>' . "\n" . $contenidoRestanteFormateado;
                                                } else {
                                                    // Detectar los puntos a) a f) de "7.3.9. Si un niño o joven comenta..." y ponerlos en recuadro azul
                                                    if ($subseccion['titulo'] === '7.3. Pautas positivas y límites que se deben tomar') {
                                                        // Buscar el bloque completo desde a) hasta f) en el contenido original
                                                        // Patrón que captura desde a) hasta el final del punto f) incluyendo "Evitar cualquier demora"
                                                        $patron = '/(a\)\s+Escuchar y aceptar[^\n]+.*?f\)\s+Mantener la confidencialidad[^\n]+Evitar cualquier demora[^\n]+)/s';
                                                        
                                                        if (preg_match($patron, $contenidoSubseccion, $matches)) {
                                                            $puntosCompletos = trim($matches[1]);
                                                            
                                                            // Formatear los indicadores en blanco para el recuadro azul
                                                            $puntosFormateados = preg_replace('/(^|\n)([a-f])\)\s/', '$1<span class="text-white font-bold">$2)</span> ', $puntosCompletos);
                                                            
                                                            // Reemplazar en el contenido
                                                            $contenidoSubseccion = preg_replace(
                                                                '/' . preg_quote($puntosCompletos, '/') . '/s',
                                                                '<div class="bg-blue-700 text-white p-4 rounded-lg mb-4 whitespace-pre-line">' . $puntosFormateados . '</div>',
                                                                $contenidoSubseccion
                                                            );
                                                        } else {
                                                            // Patrón alternativo más flexible que busca desde a) hasta antes de 7.3.10
                                                            $patronAlternativo = '/(a\)\s+Escuchar y aceptar.*?Evitar cualquier demora[^\n]+)/s';
                                                            if (preg_match($patronAlternativo, $contenidoSubseccion, $matches)) {
                                                                $puntosCompletos = trim($matches[1]);
                                                                $puntosFormateados = preg_replace('/(^|\n)([a-f])\)\s/', '$1<span class="text-white font-bold">$2)</span> ', $puntosCompletos);
                                                                $contenidoSubseccion = preg_replace(
                                                                    '/' . preg_quote($puntosCompletos, '/') . '/s',
                                                                    '<div class="bg-blue-700 text-white p-4 rounded-lg mb-4 whitespace-pre-line">' . $puntosFormateados . '</div>',
                                                                    $contenidoSubseccion
                                                                );
                                                            }
                                                        }
                                                    }
                                                    
                                                    // Detectar indicativos alfabéticos con paréntesis (a), b), c), etc.) en negrita azul oscuro
                                                    // Especialmente para "Circunstancias agravantes" y otras secciones
                                                    $contenidoSubseccionFormateado = preg_replace('/(^|\n|\r|•\s*)([a-z])\)\s/', '$1<span class="text-blue-800 font-bold">$2)</span> ', $contenidoSubseccion);
                                                    
                                                    // Detectar indicativos alfabéticos con punto (a., b., c., etc.) en diferentes contextos - azul oscuro y negrita
                                                    $contenidoSubseccionFormateado = preg_replace('/(^|\n|\r|•\s*)([a-z])\.\s/', '$1<span class="text-blue-800 font-bold">$2.</span> ', $contenidoSubseccionFormateado);
                                                }
                                            }
                                            
                                            // Detectar la subsección "Compromiso de la Diócesis" y poner título y contenido en recuadro azul (al final, después de todos los formateos)
                                            if ($subseccion['titulo'] === 'Compromiso de la Diócesis') {
                                                // Reemplazar clases de color para que el texto sea blanco dentro del recuadro azul
                                                $contenidoBlanco = preg_replace('/text-(blue|gray|red)-\d+/', 'text-white', $contenidoSubseccionFormateado);
                                                $contenidoSubseccionFormateado = '<div class="bg-blue-700 text-white p-4 rounded-lg mb-4 whitespace-pre-line"><h4 class="font-bold mb-2 text-white">' . $subseccion['titulo'] . '</h4><div class="text-white">' . $contenidoBlanco . '</div></div>';
                                            }
                                            
                                            // Detectar la subsección "Compromisos que orientan..." y poner título y contenido en recuadro blanco semi-transparente
                                            if ($subseccion['titulo'] === 'Compromisos que orientan la implementación de Programas y Proyectos en los territorios') {
                                                // Mantener el color de letras actual (azul si está en sección azul, gris si no)
                                                $colorTexto = $isAzul ? 'text-blue-700' : 'text-gray-700';
                                                $contenidoSubseccionFormateado = '<div class="bg-white/90 backdrop-blur-sm ' . $colorTexto . ' p-4 rounded-lg mb-4 border border-gray-200 shadow-sm whitespace-pre-line"><h4 class="font-bold mb-2 ' . $colorTexto . '">' . $subseccion['titulo'] . '</h4><div class="' . $colorTexto . '">' . $contenidoSubseccionFormateado . '</div></div>';
                                            }
                                            
                                            // Detectar la subsección "Cambio Climático" y aplicar estilo especial
                                            if ($subseccion['titulo'] === 'Cambio Climático') {
                                                // Separar el contenido: una parte va en el recuadro y otra fuera
                                                $textoFuera = 'Por lo anterior adoptamos esta política ambiental que hace referencia a establecer y revisar los objetivos y metas medioambientales:';
                                                
                                                // Dividir el contenido en dos partes
                                                $partes = explode($textoFuera, $contenidoSubseccionFormateado);
                                                $contenidoRecuadro = $partes[0];
                                                $contenidoFuera = isset($partes[1]) ? $textoFuera . $partes[1] : '';
                                                
                                                // Resaltar los bullet points en azul oscuro y negrita (solo en el contenido del recuadro)
                                                $contenidoRecuadro = preg_replace(
                                                    '/(•\s+)/',
                                                    '<span class="text-blue-800 font-bold">$1</span>',
                                                    $contenidoRecuadro
                                                );
                                                
                                                // Resaltar el texto "Por lo anterior adoptamos..." en negrita y tamaño grande (fuera del recuadro)
                                                $contenidoFuera = preg_replace(
                                                    '/(Por lo anterior adoptamos esta política ambiental que hace referencia a establecer y revisar los objetivos y metas medioambientales:)/',
                                                    '<span class="font-bold text-blue-800 text-[25px]">$1</span>',
                                                    $contenidoFuera
                                                );
                                                
                                                // Envolver solo el contenido principal en un recuadro con fondo azul claro, borde azul oscuro y sombra
                                                $contenidoSubseccionFormateado = '<div class="bg-blue-50 border-2 border-blue-600 rounded-lg p-5 mb-4 shadow-md whitespace-pre-line"><h4 class="text-blue-900 font-bold text-lg mb-3 border-b-2 border-blue-600 pb-2">' . $subseccion['titulo'] . '</h4><div class="text-blue-800 leading-relaxed">' . $contenidoRecuadro . '</div></div>';
                                                
                                                // Agregar el contenido que va fuera del recuadro
                                                if (!empty($contenidoFuera)) {
                                                    $contenidoSubseccionFormateado .= '<div class="text-blue-800 leading-relaxed whitespace-pre-line mt-4">' . $contenidoFuera . '</div>';
                                                }
                                            }
                                            
                                            // Detectar la subsección "Objetivos y metas medioambientales" y poner los números en negrita
                                            if ($subseccion['titulo'] === 'Objetivos y metas medioambientales') {
                                                // Poner en negrita los números seguidos de punto al inicio de línea (1., 2., 3., etc.)
                                                $contenidoSubseccionFormateado = preg_replace(
                                                    '/^(\d+\.\s+)/m',
                                                    '<span class="font-bold">$1</span>',
                                                    $contenidoSubseccionFormateado
                                                );
                                            }
                                            
                                            // Detectar la subsección "7.1. La importancia de protección..." y aplicar estilo de recuadro similar a "Cambio Climático"
                                            if ($subseccion['titulo'] === '7.1. La importancia de protección de niños, niñas, adolescentes y adultos vulnerables') {
                                                // Poner "Nota: Se ha redactado ajustando..." en recuadro azul oscuro
                                                $textoNotaSciaf = 'Nota: Se ha redactado ajustando la política que en este sentido tiene la diócesis de Apartadó, con aportes de la política de Sciaf.';
                                                
                                                if (strpos($contenidoSubseccionFormateado, $textoNotaSciaf) !== false) {
                                                    $contenidoSubseccionFormateado = str_replace(
                                                        $textoNotaSciaf,
                                                        '<div class="bg-blue-700 text-white p-4 rounded-lg mb-4 whitespace-pre-line">' . $textoNotaSciaf . '</div>',
                                                        $contenidoSubseccionFormateado
                                                    );
                                                }
                                                
                                                // Envolver todo en un recuadro con fondo azul claro, borde azul oscuro y sombra
                                                $contenidoSubseccionFormateado = '<div class="bg-blue-50 border-2 border-blue-600 rounded-lg p-5 mb-4 shadow-md whitespace-pre-line"><h4 class="text-blue-900 font-bold text-[25px] mb-3 border-b-2 border-blue-600 pb-2">' . $subseccion['titulo'] . '</h4><div class="text-blue-800 leading-relaxed">' . $contenidoSubseccionFormateado . '</div></div>';
                                            }
                                            
                                            // Detectar el punto 7.3.10 en la subsección "7.3. Pautas positivas..." y agregar la imagen al lado derecho
                                            if ($subseccion['titulo'] === '7.3. Pautas positivas y límites que se deben tomar' && isset($subseccion['imagen'])) {
                                                $texto7310 = '7.3.10. En caso de conocimiento fundado de conductas improcedentes (exhibicionismo, conversaciones impropias o relaciones personales inapropiadas) entre menores o adolescentes, se informará puntualmente a la familia que deberá hacerse cargo inmediatamente del menor.';
                                                
                                                // Buscar el texto en el contenido formateado (puede tener etiquetas HTML entre medio)
                                                // Patrón que busca 7.3.10 seguido del texto hasta el final de la oración
                                                if (preg_match('/(7\.3\.10\.\s+En caso de conocimiento fundado[^<]*?del menor\.)/', $contenidoSubseccionFormateado, $matches)) {
                                                    $textoEncontrado = $matches[1];
                                                    $imagenPath = asset('img/' . $subseccion['imagen']);
                                                    $textoConImagen = '<div class="flex items-start gap-4 mb-4"><div class="flex-1">' . $textoEncontrado . '</div><div class="flex-shrink-0"><img src="' . $imagenPath . '" alt="7.3.10" class="rounded-lg shadow-md border-2 border-gray-200 w-24 h-auto object-cover"></div></div>';
                                                    $contenidoSubseccionFormateado = preg_replace(
                                                        '/' . preg_quote($textoEncontrado, '/') . '/',
                                                        $textoConImagen,
                                                        $contenidoSubseccionFormateado,
                                                        1
                                                    );
                                                } else {
                                                    // Patrón alternativo más simple
                                                    if (strpos($contenidoSubseccionFormateado, '7.3.10.') !== false) {
                                                        // Buscar desde 7.3.10 hasta el final de la línea o hasta encontrar un punto seguido de espacio
                                                        if (preg_match('/(7\.3\.10\.[^<]*?del menor\.)/', $contenidoSubseccionFormateado, $matches)) {
                                                            $textoEncontrado = $matches[1];
                                                            $imagenPath = asset('img/' . $subseccion['imagen']);
                                                            $textoConImagen = '<div class="flex items-start gap-4 mb-4"><div class="flex-1">' . $textoEncontrado . '</div><div class="flex-shrink-0"><img src="' . $imagenPath . '" alt="7.3.10" class="rounded-lg shadow-md border-2 border-gray-200 w-24 h-auto object-cover"></div></div>';
                                                            $contenidoSubseccionFormateado = preg_replace(
                                                                '/' . preg_quote($textoEncontrado, '/') . '/',
                                                                $textoConImagen,
                                                                $contenidoSubseccionFormateado,
                                                                1
                                                            );
                                                        }
                                                    }
                                                }
                                            }
                                        @endphp
                                        <div class="{{ $isProhibiciones ? 'text-red-800 font-semibold' : ($isAzul ? 'text-blue-700' : 'text-gray-700') }} leading-relaxed whitespace-pre-line">{!! $contenidoSubseccionFormateado !!}</div>
                                        
                                        @if(isset($subseccion['nota']))
                                            <div class="bg-blue-700 text-white p-4 rounded-lg mt-4 whitespace-pre-line border-2 border-blue-800">
                                                <p class="text-white leading-relaxed">{!! $subseccion['nota'] !!}</p>
                                            </div>
                                        @endif
                                        
                                        @if(isset($subseccion['subsecciones']))
                                            <div class="space-y-4 mt-4 ml-4">
                                                @foreach($subseccion['subsecciones'] as $subSubIndex => $subSubseccion)
                                                    @php
                                                        $esPrincipio = $subseccion['titulo'] === 'Principios que fundamentan la propuesta';
                                                        $esAccion = $subseccion['titulo'] === 'Acciones';
                                                        $esAccionSub = $esAccion && in_array($subSubseccion['titulo'], ['Difusión y prevención', 'Formación y acompañamiento', 'Ruta de atención']);
                                                    @endphp
                                                    <div class="{{ ($esPrincipio || $esAccionSub) ? '' : 'border-l-4 ' }}{{ ($esPrincipio || $esAccionSub) ? '' : ($isProhibiciones ? 'border-red-600' : ($isAzul ? 'border-blue-600' : 'border-[#2f9f37]')) }}{{ ($esPrincipio || $esAccionSub) ? '' : ' pl-4' }}">
                                                        @php
                                                            $tituloSubSub = $subSubseccion['titulo'];
                                                            $tituloSubSubFormateado = preg_replace('/(\d+\.\d+\.\d+(?:\.\d+)?)/', '<span class="text-blue-800 font-bold">$1</span>', $tituloSubSub);
                                                        @endphp
                                                        @if($esPrincipio)
                                                            <div class="bg-blue-700 px-4 py-2 rounded mb-2 inline-block">
                                                                <h4 class="text-md font-bold text-white">{!! $tituloSubSub !!}</h4>
                                                            </div>
                                                        @elseif($esAccionSub)
                                                            <div class="bg-blue-700 px-4 py-2 rounded mb-2 text-center">
                                                                <h4 class="text-lg font-bold text-white">{!! $tituloSubSub !!}</h4>
                                                            </div>
                                                        @else
                                                            <h4 class="text-md font-bold {{ $isAzul ? 'text-blue-800' : 'text-gray-900' }} mb-2">{!! $tituloSubSubFormateado !!}</h4>
                                                        @endif
                                                        <div class="prose max-w-none">
                                                            @php
                                                                $contenidoSubSub = $subSubseccion['contenido'] ?? '';
                                                                $contenidoSubSubFormateado = preg_replace('/(^|\n|\r|•\s*)([a-z])\)\s/', '$1<span class="text-blue-800 font-bold">$2)</span> ', $contenidoSubSub);
                                                                $contenidoSubSubFormateado = preg_replace('/(^|\n|\r|•\s*)([a-z])\.\s/', '$1<span class="text-blue-800 font-bold">$2.</span> ', $contenidoSubSubFormateado);
                                                            @endphp
                                                            <div class="{{ $isProhibiciones ? 'text-red-800 font-semibold' : ($isAzul ? 'text-blue-700' : 'text-gray-700') }} leading-relaxed whitespace-pre-line">{!! $contenidoSubSubFormateado !!}</div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                    @if(isset($subseccion['imagen']) && $subseccion['titulo'] !== '7.3. Pautas positivas y límites que se deben tomar')
                                        <div class="mt-6 flex justify-center">
                                            <img src="{{ asset('img/' . $subseccion['imagen']) }}" 
                                                 alt="{{ $subseccion['titulo'] }}"
                                                 class="rounded-2xl shadow-lg border-4 border-gray-200 {{ $subseccion['titulo'] === 'Política' ? 'max-w-xs w-32 h-auto' : 'max-w-md w-full h-auto' }} object-cover">
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
            @endif
        @endforeach
    </div>


    <!-- Botón para volver arriba -->
    <div class="fixed bottom-8 right-8">
        <button onclick="window.scrollTo({top: 0, behavior: 'smooth'})" 
                class="bg-[#2f9f37] hover:bg-[#27842f] text-white p-3 rounded-full shadow-lg transition-all duration-200 hover:scale-110">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" />
            </svg>
        </button>
    </div>
</div>

<style>
.prose p {
    margin-bottom: 1rem;
}

.prose ul, .prose ol {
    margin-left: 1.5rem;
    margin-bottom: 1rem;
}

.prose li {
    margin-bottom: 0.5rem;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Scroll suave al inicio cuando se carga una nueva sección
    const urlParams = new URLSearchParams(window.location.search);
    const seccionParam = urlParams.get('seccion');
    if (seccionParam !== null) {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    }
});
</script>
@endsection

