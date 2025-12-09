<?php

namespace App\Http\Controllers;

use App\Models\Curso;
use App\Models\CursoRespuesta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CursoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cursos = Curso::orderByDesc('created_at')->paginate(5);
        return view('cursos.index', compact('cursos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Verificar que el usuario sea admin
        if (auth()->user()->rol !== 'admin') {
            abort(403, 'Solo los administradores pueden crear cursos');
        }
        
        return view('cursos.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Normalizar campos required antes de validar
        if ($request->has('fields') && is_array($request->fields)) {
            $normalizedFields = [];
            foreach ($request->fields as $index => $field) {
                $normalizedField = $field;
                
                // Convertir checkbox a booleano explícito
                if (!isset($field['required']) || $field['required'] === null || $field['required'] === '') {
                    $normalizedField['required'] = false;
                } elseif ($field['required'] === 'on' || $field['required'] === '1' || $field['required'] === 1 || $field['required'] === true) {
                    $normalizedField['required'] = true;
                } elseif ($field['required'] === 'off' || $field['required'] === '0' || $field['required'] === 0 || $field['required'] === false) {
                    $normalizedField['required'] = false;
                } else {
                    // Si ya es boolean, mantenerlo
                    $normalizedField['required'] = (bool) $field['required'];
                }
                
                $normalizedFields[$index] = $normalizedField;
            }
            $request->merge(['fields' => $normalizedFields]);
        }
        
        // Validaciones personalizadas para fechas
        $today = date('Y-m-d');
        
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'fecha_inicio' => [
                'nullable',
                'date',
                function ($attribute, $value, $fail) use ($today) {
                    if ($value && $value < $today) {
                        $fail('La fecha de inicio no puede ser anterior a hoy.');
                    }
                },
            ],
            'fecha_fin' => [
                'nullable',
                'date',
                function ($attribute, $value, $fail) use ($request, $today) {
                    if ($value) {
                        if ($value < $today) {
                            $fail('La fecha de finalización no puede ser anterior a hoy.');
                        }
                        if ($request->fecha_inicio && $value < $request->fecha_inicio) {
                            $fail('La fecha de finalización debe ser posterior o igual a la fecha de inicio.');
                        }
                    }
                },
            ],
            'duracion_horas' => 'nullable|integer|min:1',
            'lugar' => 'nullable|string|max:255',
            'costo' => 'nullable|numeric|min:0',
            'fields' => [
                'required',
                'array',
                'min:1',
                function ($attribute, $value, $fail) {
                    if (empty($value) || !is_array($value) || count($value) === 0) {
                        $fail('Debes agregar al menos un campo de pregunta para crear el curso.');
                    }
                },
            ],
            'fields.*.type' => 'required_with:fields|string',
            'fields.*.question' => 'nullable|string',
            'fields.*.options' => 'nullable|array',
            'fields.*.rows' => 'nullable|array',
            'fields.*.columns' => 'nullable|array',
            'fields.*.scale' => 'nullable|integer|min:1|max:10',
            'fields.*.required' => 'sometimes|boolean',
            'fields.*.image' => 'nullable|file|image|mimes:jpeg,jpg,png,gif,webp,jfif|max:10240',
            'fields.*.video' => 'nullable|file|mimes:mp4,mov,avi,webm|max:102400',
            'fields.*.video_url' => 'nullable|url|max:500'
        ], [
            'nombre.required' => 'El nombre del curso es obligatorio.',
            'fecha_inicio.date' => 'La fecha de inicio debe ser una fecha válida.',
            'fecha_fin.date' => 'La fecha de finalización debe ser una fecha válida.',
            'fields.required' => 'Debes agregar al menos un campo de pregunta para crear el curso.',
            'fields.min' => 'Debes agregar al menos un campo de pregunta para crear el curso.',
        ]);

        $curso = new Curso();
        $curso->nombre = $request->nombre;
        $curso->descripcion = $request->descripcion;
        $curso->fecha_inicio = $request->fecha_inicio;
        $curso->fecha_fin = $request->fecha_fin;
        $curso->duracion_horas = $request->duracion_horas;
        $curso->lugar = $request->lugar;
        $curso->costo = $request->costo;
        $curso->activo = true;

        // Procesar campos del formulario
        if ($request->has('fields') && !empty($request->fields)) {
            $formFields = [];
            
            foreach ($request->fields as $index => $field) {
                $fieldData = [
                    'type' => $field['type'],
                    'required' => isset($field['required']) ? true : false
                ];

                // Agregar datos específicos según el tipo de campo
                switch ($field['type']) {
                    case 'text-short':
                    case 'text-long':
                    case 'date':
                        $fieldData['question'] = $field['question'] ?? '';
                        break;
                    case 'multiple-choice':
                    case 'checkbox':
                    case 'dropdown':
                        $fieldData['question'] = $field['question'] ?? '';
                        $fieldData['options'] = $field['options'] ?? [];
                        // Guardar respuesta correcta
                        if (isset($field['correct_answer'])) {
                            $fieldData['correct_answer'] = $field['correct_answer'];
                        } elseif (isset($field['correct_answers'])) {
                            $fieldData['correct_answers'] = $field['correct_answers'];
                        }
                        break;
                    case 'grid':
                        $fieldData['question'] = $field['question'] ?? '';
                        $fieldData['rows'] = $field['rows'] ?? [];
                        $fieldData['columns'] = $field['columns'] ?? [];
                        break;
                    case 'rating':
                        $fieldData['question'] = $field['question'] ?? '';
                        $fieldData['scale'] = $field['scale'] ?? 5;
                        break;
                    case 'image':
                        $fieldData['question'] = $field['question'] ?? '';
                        
                        // Guardar dimensiones
                        $fieldData['image_width'] = $field['image_width'] ?? '100%';
                        $fieldData['image_height'] = $field['image_height'] ?? 'auto';
                        
                        // Si hay una imagen subida, guardarla
                        if ($request->hasFile("fields.{$index}.image")) {
                            $imageFile = $request->file("fields.{$index}.image");
                            $imagePath = $imageFile->store('cursos/images', 'public');
                            $fieldData['image_path'] = $imagePath;
                            $fieldData['image_name'] = $imageFile->getClientOriginalName();
                        }
                        break;
                    case 'video':
                        $fieldData['question'] = $field['question'] ?? '';
                        
                        // Guardar dimensiones
                        $fieldData['video_width'] = $field['video_width'] ?? '100%';
                        $fieldData['video_height'] = $field['video_height'] ?? 'auto';
                        
                        // Prioridad: URL primero, luego archivo subido
                        if (!empty($field['video_url'])) {
                            $fieldData['video_url'] = $field['video_url'];
                        } elseif ($request->hasFile("fields.{$index}.video")) {
                            $videoFile = $request->file("fields.{$index}.video");
                            $videoPath = $videoFile->store('cursos/videos', 'public');
                            $fieldData['video_path'] = $videoPath;
                            $fieldData['video_name'] = $videoFile->getClientOriginalName();
                        }
                        break;
                }

                $formFields[] = $fieldData;
            }

            $curso->form_fields = $formFields;
            $curso->has_form = true;
        } else {
            $curso->has_form = false;
        }

        try {
            $curso->save();
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['general' => 'Error al crear el curso: ' . $e->getMessage()]);
        }

        return redirect()->route('cursos.index')
            ->with('success', 'Curso creado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Curso $curso)
    {
        return view('cursos.show', compact('curso'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Curso $curso)
    {
        // Verificar que el usuario sea admin
        if (auth()->user()->rol !== 'admin') {
            abort(403, 'Solo los administradores pueden editar cursos');
        }
        
        return view('cursos.edit', compact('curso'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Curso $curso)
    {
        // Verificar que el usuario sea admin
        if (auth()->user()->rol !== 'admin') {
            abort(403, 'Solo los administradores pueden editar cursos');
        }

        // Normalizar campos required antes de validar
        if ($request->has('fields') && is_array($request->fields)) {
            $normalizedFields = [];
            foreach ($request->fields as $index => $field) {
                $normalizedField = $field;
                
                // Convertir checkbox a booleano explícito
                if (!isset($field['required']) || $field['required'] === null || $field['required'] === '') {
                    $normalizedField['required'] = false;
                } elseif ($field['required'] === 'on' || $field['required'] === '1' || $field['required'] === 1 || $field['required'] === true) {
                    $normalizedField['required'] = true;
                } elseif ($field['required'] === 'off' || $field['required'] === '0' || $field['required'] === 0 || $field['required'] === false) {
                    $normalizedField['required'] = false;
                } else {
                    // Si ya es boolean, mantenerlo
                    $normalizedField['required'] = (bool) $field['required'];
                }
                
                $normalizedFields[$index] = $normalizedField;
            }
            $request->merge(['fields' => $normalizedFields]);
        }
        
        // Validaciones personalizadas para fechas
        $today = date('Y-m-d');
        
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'fecha_inicio' => [
                'nullable',
                'date',
                function ($attribute, $value, $fail) use ($today) {
                    if ($value && $value < $today) {
                        $fail('La fecha de inicio no puede ser anterior a hoy.');
                    }
                },
            ],
            'fecha_fin' => [
                'nullable',
                'date',
                function ($attribute, $value, $fail) use ($request, $today) {
                    if ($value) {
                        if ($value < $today) {
                            $fail('La fecha de finalización no puede ser anterior a hoy.');
                        }
                        if ($request->fecha_inicio && $value < $request->fecha_inicio) {
                            $fail('La fecha de finalización debe ser posterior o igual a la fecha de inicio.');
                        }
                    }
                },
            ],
            'duracion_horas' => 'nullable|integer|min:1',
            'lugar' => 'nullable|string|max:255',
            'costo' => 'nullable|numeric|min:0',
            'activo' => 'nullable|boolean',
            'fields' => [
                'required',
                'array',
                'min:1',
                function ($attribute, $value, $fail) {
                    if (empty($value) || !is_array($value) || count($value) === 0) {
                        $fail('Debes agregar al menos un campo de pregunta para actualizar el curso.');
                    }
                },
            ],
            'fields.*.type' => 'required_with:fields|string',
            'fields.*.question' => 'nullable|string',
            'fields.*.options' => 'nullable|array',
            'fields.*.rows' => 'nullable|array',
            'fields.*.columns' => 'nullable|array',
            'fields.*.scale' => 'nullable|integer|min:1|max:10',
            'fields.*.required' => 'sometimes|boolean',
            'fields.*.image' => 'nullable|file|image|mimes:jpeg,jpg,png,gif,webp,jfif|max:10240',
            'fields.*.video' => 'nullable|file|mimes:mp4,mov,avi,webm|max:102400',
            'fields.*.video_url' => 'nullable|url|max:500'
        ], [
            'nombre.required' => 'El nombre del curso es obligatorio.',
            'fecha_inicio.date' => 'La fecha de inicio debe ser una fecha válida.',
            'fecha_fin.date' => 'La fecha de finalización debe ser una fecha válida.',
            'fields.required' => 'Debes agregar al menos un campo de pregunta para actualizar el curso.',
            'fields.min' => 'Debes agregar al menos un campo de pregunta para actualizar el curso.',
        ]);

        $curso->nombre = $request->nombre;
        $curso->descripcion = $request->descripcion;
        $curso->fecha_inicio = $request->fecha_inicio;
        $curso->fecha_fin = $request->fecha_fin;
        $curso->duracion_horas = $request->duracion_horas;
        $curso->lugar = $request->lugar;
        $curso->costo = $request->costo;
        $curso->activo = $request->has('activo') ? (bool)$request->activo : $curso->activo;

        // Procesar campos del formulario
        if ($request->has('fields') && !empty($request->fields)) {
            $formFields = [];
            $existingFields = $curso->form_fields ?? [];
            
            foreach ($request->fields as $index => $field) {
                $fieldData = [
                    'type' => $field['type'],
                    'required' => isset($field['required']) ? true : false
                ];

                // Agregar datos específicos según el tipo de campo
                switch ($field['type']) {
                    case 'text-short':
                    case 'text-long':
                    case 'date':
                        $fieldData['question'] = $field['question'] ?? '';
                        break;
                    case 'multiple-choice':
                    case 'checkbox':
                    case 'dropdown':
                        $fieldData['question'] = $field['question'] ?? '';
                        $fieldData['options'] = $field['options'] ?? [];
                        // Guardar respuesta correcta
                        if (isset($field['correct_answer'])) {
                            $fieldData['correct_answer'] = $field['correct_answer'];
                        } elseif (isset($field['correct_answers'])) {
                            $fieldData['correct_answers'] = $field['correct_answers'];
                        }
                        break;
                    case 'grid':
                        $fieldData['question'] = $field['question'] ?? '';
                        $fieldData['rows'] = $field['rows'] ?? [];
                        $fieldData['columns'] = $field['columns'] ?? [];
                        break;
                    case 'rating':
                        $fieldData['question'] = $field['question'] ?? '';
                        $fieldData['scale'] = $field['scale'] ?? 5;
                        break;
                    case 'image':
                        $fieldData['question'] = $field['question'] ?? '';
                        
                        // Guardar dimensiones
                        $fieldData['image_width'] = $field['image_width'] ?? '100%';
                        $fieldData['image_height'] = $field['image_height'] ?? 'auto';
                        
                        // Si hay una imagen subida, guardarla (mantener la anterior si no se sube nueva)
                        if ($request->hasFile("fields.{$index}.image")) {
                            // Buscar imagen anterior en los campos existentes
                            $existingField = $existingFields[$index] ?? null;
                            if ($existingField && isset($existingField['image_path']) && Storage::disk('public')->exists($existingField['image_path'])) {
                                Storage::disk('public')->delete($existingField['image_path']);
                            }
                            
                            $imageFile = $request->file("fields.{$index}.image");
                            $imagePath = $imageFile->store('cursos/images', 'public');
                            $fieldData['image_path'] = $imagePath;
                            $fieldData['image_name'] = $imageFile->getClientOriginalName();
                        } else {
                            // Mantener la imagen anterior si existe
                            $existingField = $existingFields[$index] ?? null;
                            if ($existingField && isset($existingField['image_path'])) {
                                $fieldData['image_path'] = $existingField['image_path'];
                                $fieldData['image_name'] = $existingField['image_name'] ?? '';
                            }
                        }
                        break;
                    case 'video':
                        $fieldData['question'] = $field['question'] ?? '';
                        
                        // Guardar dimensiones
                        $fieldData['video_width'] = $field['video_width'] ?? '100%';
                        $fieldData['video_height'] = $field['video_height'] ?? 'auto';
                        
                        // Prioridad: URL primero, luego archivo subido
                        if (!empty($field['video_url'])) {
                            $fieldData['video_url'] = $field['video_url'];
                        } elseif ($request->hasFile("fields.{$index}.video")) {
                            // Buscar video anterior en los campos existentes
                            $existingField = $existingFields[$index] ?? null;
                            if ($existingField && isset($existingField['video_path']) && Storage::disk('public')->exists($existingField['video_path'])) {
                                Storage::disk('public')->delete($existingField['video_path']);
                            }
                            
                            $videoFile = $request->file("fields.{$index}.video");
                            $videoPath = $videoFile->store('cursos/videos', 'public');
                            $fieldData['video_path'] = $videoPath;
                            $fieldData['video_name'] = $videoFile->getClientOriginalName();
                        } else {
                            // Mantener el video o URL anterior si existe
                            $existingField = $existingFields[$index] ?? null;
                            if ($existingField) {
                                if (isset($existingField['video_path'])) {
                                    $fieldData['video_path'] = $existingField['video_path'];
                                    $fieldData['video_name'] = $existingField['video_name'] ?? '';
                                } elseif (isset($existingField['video_url'])) {
                                    $fieldData['video_url'] = $existingField['video_url'];
                                }
                            }
                        }
                        break;
                }

                $formFields[] = $fieldData;
            }

            $curso->form_fields = $formFields;
            $curso->has_form = true;
        } else {
            $curso->has_form = false;
        }

        try {
            $curso->save();
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['general' => 'Error al actualizar el curso: ' . $e->getMessage()]);
        }

        return redirect()->route('cursos.index')
            ->with('success', 'Curso actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Curso $curso)
    {
        // Verificar que el usuario sea admin
        if (auth()->user()->rol !== 'admin') {
            abort(403, 'Solo los administradores pueden eliminar cursos');
        }

        try {
            $nombreCurso = $curso->nombre;
            $curso->delete();

            return redirect()->route('cursos.index')
                ->with('success', "El curso \"{$nombreCurso}\" ha sido eliminado exitosamente.");
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al eliminar el curso: ' . $e->getMessage());
        }
    }

    /**
     * Mostrar formulario para que el estudiante inicie el curso
     */
    public function iniciar(Curso $curso)
    {
        // Solo estudiantes pueden iniciar cursos
        if (auth()->user()->rol === 'admin') {
            return redirect()->route('cursos.show', $curso);
        }

        // Validar que el curso no esté expirado
        if ($curso->esta_finalizado) {
            return redirect()->route('cursos.index')
                ->with('error', 'Este curso ha expirado y ya no está disponible.');
        }

        // Validar que el curso esté activo
        if (!$curso->activo) {
            return redirect()->route('cursos.index')
                ->with('error', 'Este curso no está disponible actualmente.');
        }

        // Verificar si el estudiante ya completó el curso
        $respuestaExistente = CursoRespuesta::where('curso_id', $curso->id)
            ->where('user_id', auth()->id())
            ->first();

        if ($respuestaExistente) {
            // Redirigir sin alerta, la vista ya mostrará que está completado
            return redirect()->route('cursos.index');
        }

        return view('cursos.iniciar', compact('curso'));
    }

    /**
     * Procesar y guardar las respuestas del estudiante
     */
    public function completar(Request $request, Curso $curso)
    {
        // Solo estudiantes pueden completar cursos
        if (auth()->user()->rol === 'admin') {
            abort(403, 'Los administradores no pueden completar cursos');
        }

        // Validar que el curso no esté expirado
        if ($curso->esta_finalizado) {
            return redirect()->route('cursos.index')
                ->with('error', 'Este curso ha expirado y ya no está disponible.');
        }

        // Verificar si el estudiante ya completó el curso
        $respuestaExistente = CursoRespuesta::where('curso_id', $curso->id)
            ->where('user_id', auth()->id())
            ->first();

        if ($respuestaExistente) {
            // Redirigir sin alerta, la vista ya mostrará que está completado
            return redirect()->route('cursos.index');
        }

        // Validar que el curso tenga formulario
        if (!$curso->has_form || empty($curso->form_fields)) {
            return redirect()->back()
                ->with('error', 'Este curso no tiene formulario disponible.');
        }

        // Recopilar respuestas
        $respuestas = [];
        $totalPreguntas = 0;
        $preguntasCorrectas = 0;

        foreach ($curso->form_fields as $index => $field) {
            $fieldKey = "field_{$index}";
            $respuesta = null;

            // Solo contar preguntas que tienen respuesta correcta definida
            $tieneRespuestaCorrecta = false;
            if (in_array($field['type'], ['multiple-choice', 'checkbox', 'dropdown'])) {
                $tieneRespuestaCorrecta = isset($field['correct_answer']) || isset($field['correct_answers']);
            }

            if ($tieneRespuestaCorrecta) {
                $totalPreguntas++;
            }

            // Recopilar respuesta según el tipo de campo
            switch ($field['type']) {
                case 'text-short':
                case 'text-long':
                    $respuesta = $request->input($fieldKey);
                    break;
                case 'multiple-choice':
                    $respuesta = $request->input($fieldKey);
                    // Verificar si es correcta
                    if ($tieneRespuestaCorrecta && isset($field['correct_answer'])) {
                        if ($respuesta == $field['correct_answer']) {
                            $preguntasCorrectas++;
                        }
                    }
                    break;
                case 'checkbox':
                    $respuesta = $request->input($fieldKey, []);
                    // Verificar si son correctas
                    if ($tieneRespuestaCorrecta && isset($field['correct_answers'])) {
                        $correctas = is_array($field['correct_answers']) ? $field['correct_answers'] : [$field['correct_answers']];
                        $respondidas = is_array($respuesta) ? $respuesta : [$respuesta];
                        sort($correctas);
                        sort($respondidas);
                        if ($correctas === $respondidas) {
                            $preguntasCorrectas++;
                        }
                    }
                    break;
                case 'dropdown':
                    $respuesta = $request->input($fieldKey);
                    // Verificar si es correcta
                    if ($tieneRespuestaCorrecta && isset($field['correct_answer'])) {
                        if ($respuesta == $field['correct_answer']) {
                            $preguntasCorrectas++;
                        }
                    }
                    break;
                case 'date':
                    $respuesta = $request->input($fieldKey);
                    break;
                case 'rating':
                    $respuesta = $request->input($fieldKey);
                    break;
            }

            $respuestas[$index] = [
                'type' => $field['type'],
                'question' => $field['question'] ?? '',
                'answer' => $respuesta
            ];
        }

        // Calcular puntuación (porcentaje)
        $puntuacion = $totalPreguntas > 0 ? round(($preguntasCorrectas / $totalPreguntas) * 100) : 0;

        // Guardar respuesta
        try {
            CursoRespuesta::create([
                'curso_id' => $curso->id,
                'user_id' => auth()->id(),
                'respuestas' => $respuestas,
                'puntuacion' => $puntuacion,
                'total_preguntas' => $totalPreguntas,
                'preguntas_correctas' => $preguntasCorrectas,
                'completado_at' => now()
            ]);

            return redirect()->route('cursos.index')
                ->with('success', "¡Curso completado! Tu puntuación: {$puntuacion}% ({$preguntasCorrectas}/{$totalPreguntas})");
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al guardar las respuestas: ' . $e->getMessage());
        }
    }

    /**
     * Mostrar resultados del curso para administradores
     */
    public function resultados(Curso $curso)
    {
        // Solo administradores pueden ver resultados
        if (auth()->user()->rol !== 'admin') {
            abort(403, 'Solo los administradores pueden ver los resultados');
        }

        $respuestas = CursoRespuesta::where('curso_id', $curso->id)
            ->with('user')
            ->orderBy('puntuacion', 'desc')
            ->orderBy('completado_at', 'desc')
            ->get();

        return view('cursos.resultados', compact('curso', 'respuestas'));
    }
}
