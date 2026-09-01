<?php

namespace App\Http\Livewire;

use App\Models\Curso;
use App\Models\ExamenFinal;
use App\Models\Material;
use App\Models\Modulo;
use App\Models\Cuestionario;
use App\Models\PreguntaCuestionario;
use App\Models\OpcionPregunta;
use App\Models\PreguntaExamenFinal;
use App\Models\OpcionExamenFinal;
use App\Services\ImageService;
use Livewire\Component;
use Livewire\WithFileUploads;

class EditCurso extends Component
{
    use WithFileUploads;

    public $curso_id;
    public $paso = 1;
    public $titulo;
    public $descripcion;
    public $imagen;
    public $imagenActual;
    public $audiencia = 'docente';
    public $horas;

    public $modulos = [];

    public $examenFinal = [
        'id' => null,
        'titulo' => 'Examen Final',
        'preguntas' => [],
    ];

    protected $rules = [
        'titulo' => 'required|string|max:255',
        'descripcion' => 'required|string',
        'audiencia' => 'required|in:docente,estudiante',
        'imagen' => 'nullable|image|max:2048|dimensions:min_width=200,min_height=100',
        'horas' => 'required|integer|min:1',
    ];

    protected $messages = [
        'titulo.required' => 'El titulo del curso es obligatorio.',
        'titulo.max' => 'El titulo no debe exceder los 255 caracteres.',
        'descripcion.required' => 'La descripcion es obligatoria.',
        'audiencia.required' => 'Selecciona la audiencia del curso.',
        'imagen.image' => 'El archivo debe ser una imagen.',
        'imagen.max' => 'La imagen no debe superar los 2MB.',
        'imagen.dimensions' => 'La imagen debe tener al menos 200x100 pixeles.',
        'horas.required' => 'La duracion en horas es obligatoria.',
        'horas.integer' => 'La duracion debe ser un numero entero.',
        'horas.min' => 'La duracion minima es 1 hora.',
    ];

    public function mount(Curso $curso)
    {
        $this->curso_id = $curso->id;
        $curso->load([
            'modulos.materiales',
            'modulos.cuestionario.preguntas.opciones',
            'examenFinal.preguntas.opciones',
        ]);

        $this->titulo = $curso->titulo;
        $this->descripcion = $curso->descripcion;
        $this->audiencia = $curso->audiencia;
        $this->horas = $curso->horas;
        $this->imagenActual = $curso->imagen;

        foreach ($curso->modulos->sortBy('orden') as $modulo) {
            $modData = [
                'id' => $modulo->id,
                'titulo' => $modulo->titulo,
                'descripcion' => $modulo->descripcion ?? '',
                'materiales' => [],
                'cuestionario' => [
                    'id' => null,
                    'titulo' => '',
                    'preguntas' => [],
                ],
            ];

            foreach ($modulo->materiales->sortBy('orden') as $material) {
                $modData['materiales'][] = [
                    'id' => $material->id,
                    'titulo' => $material->titulo,
                    'tipo' => $material->tipo,
                    'url' => $material->url ?? '',
                    'archivo' => $material->archivo,
                    'archivo_nuevo' => null,
                ];
            }

            if ($modulo->cuestionario) {
                $cuestionario = $modulo->cuestionario;
                $modData['cuestionario']['id'] = $cuestionario->id;
                $modData['cuestionario']['titulo'] = $cuestionario->titulo;

                foreach ($cuestionario->preguntas->sortBy('orden') as $pregunta) {
                    $pregData = [
                        'id' => $pregunta->id,
                        'texto' => $pregunta->texto,
                        'opciones' => [],
                    ];

                    foreach ($pregunta->opciones->sortBy('orden') as $opcion) {
                        $pregData['opciones'][] = [
                            'id' => $opcion->id,
                            'texto' => $opcion->texto,
                            'es_correcta' => $opcion->es_correcta,
                        ];
                    }

                    $modData['cuestionario']['preguntas'][] = $pregData;
                }
            }

            $this->modulos[] = $modData;
        }

        if ($curso->examenFinal) {
            $this->examenFinal['id'] = $curso->examenFinal->id;
            $this->examenFinal['titulo'] = $curso->examenFinal->titulo;

            foreach ($curso->examenFinal->preguntas->sortBy('orden') as $pregunta) {
                $pregData = [
                    'id' => $pregunta->id,
                    'texto' => $pregunta->texto,
                    'opciones' => [],
                ];

                foreach ($pregunta->opciones->sortBy('orden') as $opcion) {
                    $pregData['opciones'][] = [
                        'id' => $opcion->id,
                        'texto' => $opcion->texto,
                        'es_correcta' => $opcion->es_correcta,
                    ];
                }

                $this->examenFinal['preguntas'][] = $pregData;
            }
        }
    }

    public function render()
    {
        return view('livewire.edit-curso');
    }

    public function siguientePaso()
    {
        if ($this->paso === 1) {
            $this->validate();
        }
        $this->paso++;
    }

    public function pasoAnterior()
    {
        $this->paso--;
    }

    public function addModulo()
    {
        $this->modulos[] = [
            'id' => null,
            'titulo' => '',
            'descripcion' => '',
            'materiales' => [],
            'cuestionario' => [
                'id' => null,
                'titulo' => '',
                'preguntas' => [],
            ],
        ];
    }

    public function removeModulo($index)
    {
        unset($this->modulos[$index]);
        $this->modulos = array_values($this->modulos);
    }

    public function addMaterial($modIdx)
    {
        $this->modulos[$modIdx]['materiales'][] = [
            'id' => null,
            'titulo' => '',
            'tipo' => 'video',
            'url' => '',
            'archivo' => null,
            'archivo_nuevo' => null,
        ];
    }

    public function removeMaterial($modIdx, $matIdx)
    {
        unset($this->modulos[$modIdx]['materiales'][$matIdx]);
        $this->modulos[$modIdx]['materiales'] = array_values($this->modulos[$modIdx]['materiales']);
    }

    public function addPreguntaCuestionario($modIdx)
    {
        $this->modulos[$modIdx]['cuestionario']['preguntas'][] = [
            'id' => null,
            'texto' => '',
            'opciones' => [
                ['id' => null, 'texto' => '', 'es_correcta' => false],
                ['id' => null, 'texto' => '', 'es_correcta' => false],
            ],
        ];
    }

    public function removePreguntaCuestionario($modIdx, $pregIdx)
    {
        unset($this->modulos[$modIdx]['cuestionario']['preguntas'][$pregIdx]);
        $this->modulos[$modIdx]['cuestionario']['preguntas'] = array_values($this->modulos[$modIdx]['cuestionario']['preguntas']);
    }

    public function addOpcionCuestionario($modIdx, $pregIdx)
    {
        $this->modulos[$modIdx]['cuestionario']['preguntas'][$pregIdx]['opciones'][] = [
            'id' => null,
            'texto' => '',
            'es_correcta' => false,
        ];
    }

    public function removeOpcionCuestionario($modIdx, $pregIdx, $opcIdx)
    {
        if (count($this->modulos[$modIdx]['cuestionario']['preguntas'][$pregIdx]['opciones']) <= 2) {
            return;
        }
        unset($this->modulos[$modIdx]['cuestionario']['preguntas'][$pregIdx]['opciones'][$opcIdx]);
        $this->modulos[$modIdx]['cuestionario']['preguntas'][$pregIdx]['opciones'] = array_values(
            $this->modulos[$modIdx]['cuestionario']['preguntas'][$pregIdx]['opciones']
        );
    }

    public function setCorrectaCuestionario($modIdx, $pregIdx, $opcIdx)
    {
        foreach ($this->modulos[$modIdx]['cuestionario']['preguntas'][$pregIdx]['opciones'] as $i => &$opcion) {
            $opcion['es_correcta'] = ($i === $opcIdx);
        }
    }

    public function addPreguntaExamen()
    {
        $this->examenFinal['preguntas'][] = [
            'id' => null,
            'texto' => '',
            'opciones' => [
                ['id' => null, 'texto' => '', 'es_correcta' => false],
                ['id' => null, 'texto' => '', 'es_correcta' => false],
            ],
        ];
    }

    public function removePreguntaExamen($pregIdx)
    {
        unset($this->examenFinal['preguntas'][$pregIdx]);
        $this->examenFinal['preguntas'] = array_values($this->examenFinal['preguntas']);
    }

    public function addOpcionExamen($pregIdx)
    {
        $this->examenFinal['preguntas'][$pregIdx]['opciones'][] = [
            'id' => null,
            'texto' => '',
            'es_correcta' => false,
        ];
    }

    public function removeOpcionExamen($pregIdx, $opcIdx)
    {
        if (count($this->examenFinal['preguntas'][$pregIdx]['opciones']) <= 2) {
            return;
        }
        unset($this->examenFinal['preguntas'][$pregIdx]['opciones'][$opcIdx]);
        $this->examenFinal['preguntas'][$pregIdx]['opciones'] = array_values(
            $this->examenFinal['preguntas'][$pregIdx]['opciones']
        );
    }

    public function setCorrectaExamen($pregIdx, $opcIdx)
    {
        foreach ($this->examenFinal['preguntas'][$pregIdx]['opciones'] as $i => &$opcion) {
            $opcion['es_correcta'] = ($i === $opcIdx);
        }
    }

    public function validarTodo()
    {
        $errores = [];

        if (count($this->modulos) === 0) {
            $errores[] = 'Debes agregar al menos un modulo.';
        }

        foreach ($this->modulos as $modIdx => $modulo) {
            if (empty($modulo['titulo'])) {
                $errores[] = 'El modulo ' . ($modIdx + 1) . ' debe tener un titulo.';
            }

            if (count($modulo['materiales']) === 0) {
                $errores[] = 'El modulo ' . ($modIdx + 1) . ' debe tener al menos un material.';
            }

            foreach ($modulo['materiales'] as $matIdx => $material) {
                if (empty($material['titulo'])) {
                    $errores[] = 'El material ' . ($matIdx + 1) . ' del modulo ' . ($modIdx + 1) . ' debe tener un titulo.';
                }
                if ($material['tipo'] === 'video' && empty($material['url'])) {
                    $errores[] = 'El material ' . ($matIdx + 1) . ' del modulo ' . ($modIdx + 1) . ' debe tener una URL.';
                }
            }

            $preguntas = $modulo['cuestionario']['preguntas'];
            if (count($preguntas) === 0) {
                $errores[] = 'El modulo ' . ($modIdx + 1) . ' debe tener al menos una pregunta en su cuestionario.';
            }

            foreach ($preguntas as $pregIdx => $pregunta) {
                if (empty($pregunta['texto'])) {
                    $errores[] = 'La pregunta ' . ($pregIdx + 1) . ' del modulo ' . ($modIdx + 1) . ' debe tener texto.';
                }
                if (count($pregunta['opciones']) < 2) {
                    $errores[] = 'La pregunta ' . ($pregIdx + 1) . ' del modulo ' . ($modIdx + 1) . ' debe tener al menos 2 opciones.';
                }
                $correctas = collect($pregunta['opciones'])->where('es_correcta', true)->count();
                if ($correctas !== 1) {
                    $errores[] = 'La pregunta ' . ($pregIdx + 1) . ' del modulo ' . ($modIdx + 1) . ' debe tener exactamente 1 opcion correcta.';
                }
            }
        }

        if (count($this->examenFinal['preguntas']) === 0) {
            $errores[] = 'El examen final debe tener al menos una pregunta.';
        }

        foreach ($this->examenFinal['preguntas'] as $pregIdx => $pregunta) {
            if (empty($pregunta['texto'])) {
                $errores[] = 'La pregunta ' . ($pregIdx + 1) . ' del examen final debe tener texto.';
            }
            if (count($pregunta['opciones']) < 2) {
                $errores[] = 'La pregunta ' . ($pregIdx + 1) . ' del examen final debe tener al menos 2 opciones.';
            }
            $correctas = collect($pregunta['opciones'])->where('es_correcta', true)->count();
            if ($correctas !== 1) {
                $errores[] = 'La pregunta ' . ($pregIdx + 1) . ' del examen final debe tener exactamente 1 opcion correcta.';
            }
        }

        return $errores;
    }

    public function puedeActualizar()
    {
        $errores = $this->validarTodo();

        if (count($errores) > 0) {
            $this->dispatchBrowserEvent('mostrar-errores-validacion', ['errores' => $errores]);
            return false;
        }

        return true;
    }

    public function actualizarCurso()
    {
        if (!$this->puedeActualizar()) {
            return;
        }

        $curso = Curso::findOrFail($this->curso_id);

        $imagenPath = $this->imagenActual;
        if ($this->imagen && is_object($this->imagen) && method_exists($this->imagen, 'store')) {
            if ($curso->imagen) {
                \Storage::disk('public')->delete($curso->imagen);
            }
            $imageService = app(ImageService::class);
            $imagenPath = $imageService->uploadCurso($this->imagen);
        }

        $curso->update([
            'titulo' => $this->titulo,
            'descripcion' => $this->descripcion,
            'imagen' => $imagenPath,
            'audiencia' => $this->audiencia,
            'horas' => $this->horas,
        ]);

        // Track which IDs still exist to detect deletions
        $moduloIds = [];
        $materialIds = [];
        $cuestionarioPreguntaIds = [];
        $cuestionarioOpcionIds = [];
        $examenPreguntaIds = [];
        $examenOpcionIds = [];

        // Process modules
        $ordenModulo = 1;
        foreach ($this->modulos as $moduloData) {
            if ($moduloData['id']) {
                $modulo = Modulo::find($moduloData['id']);
                if ($modulo) {
                    $modulo->update([
                        'titulo' => $moduloData['titulo'],
                        'descripcion' => $moduloData['descripcion'] ?? '',
                        'orden' => $ordenModulo++,
                    ]);
                    $moduloIds[] = $modulo->id;
                } else {
                    $moduloIds[] = null; // shouldn't happen
                    continue;
                }
            } else {
                $modulo = Modulo::create([
                    'curso_id' => $curso->id,
                    'titulo' => $moduloData['titulo'],
                    'descripcion' => $moduloData['descripcion'] ?? '',
                    'orden' => $ordenModulo++,
                ]);
                $moduloIds[] = $modulo->id;
            }

            // Process materials
            $ordenMaterial = 1;
            foreach ($moduloData['materiales'] as $materialData) {
                $archivoPath = null;
                if (isset($materialData['archivo_nuevo']) && $materialData['archivo_nuevo']) {
                    if (is_object($materialData['archivo_nuevo']) && method_exists($materialData['archivo_nuevo'], 'store')) {
                        $this->validarArchivoMaterial($materialData['archivo_nuevo']);
                        $archivoPath = $materialData['archivo_nuevo']->store('materiales', 'public');
                    } elseif (is_string($materialData['archivo_nuevo'])) {
                        $archivoPath = $materialData['archivo_nuevo'];
                    }
                } elseif (isset($materialData['archivo']) && $materialData['archivo']) {
                    $archivoPath = $materialData['archivo'];
                }

                if ($materialData['id']) {
                    $material = Material::find($materialData['id']);
                    if ($material) {
                        // If new file uploaded, delete old one
                        if ($archivoPath && $archivoPath !== $material->archivo && $material->archivo) {
                            \Storage::disk('public')->delete($material->archivo);
                        }
                        $material->update([
                            'titulo' => $materialData['titulo'],
                            'tipo' => $materialData['tipo'],
                            'url' => $materialData['url'] ?? null,
                            'archivo' => $archivoPath,
                            'orden' => $ordenMaterial++,
                        ]);
                        $materialIds[] = $material->id;
                    }
                } else {
                    $material = Material::create([
                        'modulo_id' => $modulo->id,
                        'titulo' => $materialData['titulo'],
                        'tipo' => $materialData['tipo'],
                        'url' => $materialData['url'] ?? null,
                        'archivo' => $archivoPath,
                        'orden' => $ordenMaterial++,
                    ]);
                    $materialIds[] = $material->id;
                }
            }

            // Process cuestionario
            $cuestionarioData = $moduloData['cuestionario'];
            if ($cuestionarioData['id']) {
                $cuestionario = Cuestionario::find($cuestionarioData['id']);
                if ($cuestionario) {
                    $cuestionario->update([
                        'titulo' => $cuestionarioData['titulo'] ?: 'Cuestionario',
                    ]);
                }
            } else {
                $cuestionario = Cuestionario::create([
                    'modulo_id' => $modulo->id,
                    'titulo' => $cuestionarioData['titulo'] ?: 'Cuestionario',
                    'min_aprobacion' => 100,
                ]);
            }

            // Process cuestionario preguntas
            if ($cuestionario) {
                $ordenPregunta = 1;
                foreach ($cuestionarioData['preguntas'] as $preguntaData) {
                    if ($preguntaData['id']) {
                        $pregunta = PreguntaCuestionario::find($preguntaData['id']);
                        if ($pregunta) {
                            $pregunta->update([
                                'texto' => $preguntaData['texto'],
                                'orden' => $ordenPregunta++,
                            ]);
                            $cuestionarioPreguntaIds[] = $pregunta->id;
                        } else {
                            continue;
                        }
                    } else {
                        $pregunta = PreguntaCuestionario::create([
                            'cuestionario_id' => $cuestionario->id,
                            'texto' => $preguntaData['texto'],
                            'orden' => $ordenPregunta++,
                        ]);
                        $cuestionarioPreguntaIds[] = $pregunta->id;
                    }

                    // Process opciones
                    $ordenOpcion = 1;
                    foreach ($preguntaData['opciones'] as $opcionData) {
                        if ($opcionData['id']) {
                            $opcion = OpcionPregunta::find($opcionData['id']);
                            if ($opcion) {
                                $opcion->update([
                                    'texto' => $opcionData['texto'],
                                    'es_correcta' => $opcionData['es_correcta'],
                                    'orden' => $ordenOpcion++,
                                ]);
                                $cuestionarioOpcionIds[] = $opcion->id;
                            }
                        } else {
                            $opcion = OpcionPregunta::create([
                                'pregunta_id' => $pregunta->id,
                                'texto' => $opcionData['texto'],
                                'es_correcta' => $opcionData['es_correcta'],
                                'orden' => $ordenOpcion++,
                            ]);
                            $cuestionarioOpcionIds[] = $opcion->id;
                        }
                    }
                }
            }
        }

        // Process examen final
        $examenData = $this->examenFinal;
        if ($examenData['id']) {
            $examenFinal = ExamenFinal::find($examenData['id']);
            if ($examenFinal) {
                $examenFinal->update([
                    'titulo' => $examenData['titulo'] ?: 'Examen Final',
                ]);
            }
        } else {
            $examenFinal = ExamenFinal::create([
                'curso_id' => $curso->id,
                'titulo' => $examenData['titulo'] ?: 'Examen Final',
                'min_aprobacion' => 80,
            ]);
        }

        if ($examenFinal) {
            $ordenPregunta = 1;
            foreach ($examenData['preguntas'] as $preguntaData) {
                if ($preguntaData['id']) {
                    $pregunta = PreguntaExamenFinal::find($preguntaData['id']);
                    if ($pregunta) {
                        $pregunta->update([
                            'texto' => $preguntaData['texto'],
                            'orden' => $ordenPregunta++,
                        ]);
                        $examenPreguntaIds[] = $pregunta->id;
                    } else {
                        continue;
                    }
                } else {
                    $pregunta = PreguntaExamenFinal::create([
                        'examen_final_id' => $examenFinal->id,
                        'texto' => $preguntaData['texto'],
                        'orden' => $ordenPregunta++,
                    ]);
                    $examenPreguntaIds[] = $pregunta->id;
                }

                $ordenOpcion = 1;
                foreach ($preguntaData['opciones'] as $opcionData) {
                    if ($opcionData['id']) {
                        $opcion = OpcionExamenFinal::find($opcionData['id']);
                        if ($opcion) {
                            $opcion->update([
                                'texto' => $opcionData['texto'],
                                'es_correcta' => $opcionData['es_correcta'],
                                'orden' => $ordenOpcion++,
                            ]);
                            $examenOpcionIds[] = $opcion->id;
                        }
                    } else {
                        $opcion = OpcionExamenFinal::create([
                            'pregunta_id' => $pregunta->id,
                            'texto' => $opcionData['texto'],
                            'es_correcta' => $opcionData['es_correcta'],
                            'orden' => $ordenOpcion++,
                        ]);
                        $examenOpcionIds[] = $opcion->id;
                    }
                }
            }
        }

        // Delete removed items (those not in the current form data)
        $this->eliminarRemovidos($curso, $moduloIds, $materialIds, $cuestionarioPreguntaIds, $cuestionarioOpcionIds, $examenPreguntaIds, $examenOpcionIds);

        session()->flash('success', 'Curso actualizado exitosamente.');
        return redirect()->route('cursos.show', $curso);
    }

    private function validarArchivoMaterial($archivo)
    {
        $maxBytes = 30 * 1024 * 1024;

        if (method_exists($archivo, 'getSize') && $archivo->getSize() > $maxBytes) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'archivo' => 'El archivo supera el tamano maximo permitido (30MB). Revisa el tamano e intenta de nuevo.',
            ]);
        }

        $ext = strtolower(method_exists($archivo, 'getClientOriginalExtension') ? $archivo->getClientOriginalExtension() : $archivo->extension());
        if ($ext !== 'pdf') {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'archivo' => 'El archivo del material debe ser PDF.',
            ]);
        }
    }

    private function eliminarRemovidos($curso, $moduloIds, $materialIds, $cuestionarioPreguntaIds, $cuestionarioOpcionIds, $examenPreguntaIds, $examenOpcionIds)
    {
        // Delete opciones that were removed from cuestionario preguntas
        if ($cuestionarioPreguntaIds) {
            OpcionPregunta::whereIn('pregunta_id', function ($q) use ($curso) {
                $q->select('id')->from('preguntas_cuestionario')
                    ->whereIn('cuestionario_id', function ($q2) use ($curso) {
                        $q2->select('id')->from('cuestionarios')
                            ->whereIn('modulo_id', $curso->modulos()->pluck('id'));
                    });
            })->whereNotIn('id', $cuestionarioOpcionIds)->delete();
        }

        // Delete pregunta cuestionario that were removed
        if ($cuestionarioPreguntaIds) {
            PreguntaCuestionario::whereIn('cuestionario_id', function ($q) use ($curso) {
                $q->select('id')->from('cuestionarios')
                    ->whereIn('modulo_id', $curso->modulos()->pluck('id'));
            })->whereNotIn('id', $cuestionarioPreguntaIds)->delete();
        }

        // Delete opciones that were removed from examen final preguntas
        if ($examenPreguntaIds) {
            OpcionExamenFinal::whereIn('pregunta_id', function ($q) use ($examenPreguntaIds) {
                $q->select('id')->from('preguntas_examen_final')
                    ->whereIn('examen_final_id', function ($q2) {
                        $q2->select('id')->from('examenes_finales')
                            ->where('id', $this->examenFinal['id'] ?? 0);
                    });
            })->whereNotIn('id', $examenOpcionIds)->delete();
        }

        // Delete pregunta examen final that were removed
        if ($examenPreguntaIds) {
            PreguntaExamenFinal::where('examen_final_id', $this->examenFinal['id'] ?? 0)
                ->whereNotIn('id', $examenPreguntaIds)->delete();
        }

        // Delete materiales that were removed
        if ($materialIds) {
            $oldMateriales = Material::whereIn('modulo_id', $curso->modulos()->pluck('id'))
                ->whereNotIn('id', $materialIds)->get();
            foreach ($oldMateriales as $oldMat) {
                if ($oldMat->archivo) {
                    \Storage::disk('public')->delete($oldMat->archivo);
                }
                $oldMat->delete();
            }
        }

        // Delete modulos that were removed
        if ($moduloIds) {
            Modulo::where('curso_id', $curso->id)
                ->whereNotIn('id', $moduloIds)->delete();
        }
    }
}
