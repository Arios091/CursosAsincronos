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

class CreateCurso extends Component
{
    use WithFileUploads;

    public $paso = 1;
    public $titulo;
    public $descripcion;
    public $imagen;
    public $audiencia = 'docente';
    public $horas;

    public $modulos = [];

    public $examenFinal = [
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

    public function render()
    {
        return view('livewire.create-curso');
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
            'titulo' => '',
            'descripcion' => '',
            'materiales' => [],
            'cuestionario' => [
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
            'titulo' => '',
            'tipo' => 'video',
            'url' => '',
            'archivo' => null,
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
            'texto' => '',
            'opciones' => [
                ['texto' => '', 'es_correcta' => false],
                ['texto' => '', 'es_correcta' => false],
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
            'texto' => '',
            'opciones' => [
                ['texto' => '', 'es_correcta' => false],
                ['texto' => '', 'es_correcta' => false],
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

    public function puedeCrear()
    {
        $errores = $this->validarTodo();

        if (count($errores) > 0) {
            $this->dispatchBrowserEvent('mostrar-errores-validacion', ['errores' => $errores]);
            return false;
        }

        return true;
    }

    public function crearCurso()
    {
        if (!$this->puedeCrear()) {
            return;
        }

        $imagenPath = null;
        if ($this->imagen && is_object($this->imagen) && method_exists($this->imagen, 'store')) {
            $imageService = app(ImageService::class);
            $imagenPath = $imageService->uploadCurso($this->imagen);
        } elseif ($this->imagen && is_string($this->imagen)) {
            $imagenPath = $this->imagen;
        }

        $curso = Curso::create([
            'titulo' => $this->titulo,
            'descripcion' => $this->descripcion,
            'imagen' => $imagenPath,
            'estado' => 'publicado',
            'user_id' => auth()->id(),
            'audiencia' => $this->audiencia,
            'horas' => $this->horas,
        ]);

        $ordenModulo = 1;
        foreach ($this->modulos as $moduloData) {
            $modulo = Modulo::create([
                'curso_id' => $curso->id,
                'titulo' => $moduloData['titulo'],
                'descripcion' => $moduloData['descripcion'] ?? '',
                'orden' => $ordenModulo++,
            ]);

            $ordenMaterial = 1;
            foreach ($moduloData['materiales'] as $materialData) {
                $archivoPath = null;
                if (isset($materialData['archivo']) && $materialData['archivo']) {
                    if (is_object($materialData['archivo']) && method_exists($materialData['archivo'], 'store')) {
                        $archivoPath = $materialData['archivo']->store('materiales', 'public');
                    } elseif (is_string($materialData['archivo'])) {
                        $archivoPath = $materialData['archivo'];
                    }
                }

                Material::create([
                    'modulo_id' => $modulo->id,
                    'titulo' => $materialData['titulo'],
                    'tipo' => $materialData['tipo'],
                    'url' => $materialData['url'] ?? null,
                    'archivo' => $archivoPath,
                    'orden' => $ordenMaterial++,
                ]);
            }

            $cuestionario = Cuestionario::create([
                'modulo_id' => $modulo->id,
                'titulo' => $moduloData['cuestionario']['titulo'] ?: 'Cuestionario',
                'min_aprobacion' => 100,
            ]);

            $ordenPregunta = 1;
            foreach ($moduloData['cuestionario']['preguntas'] as $preguntaData) {
                $pregunta = PreguntaCuestionario::create([
                    'cuestionario_id' => $cuestionario->id,
                    'texto' => $preguntaData['texto'],
                    'orden' => $ordenPregunta++,
                ]);

                $ordenOpcion = 1;
                foreach ($preguntaData['opciones'] as $opcionData) {
                    OpcionPregunta::create([
                        'pregunta_id' => $pregunta->id,
                        'texto' => $opcionData['texto'],
                        'es_correcta' => $opcionData['es_correcta'],
                        'orden' => $ordenOpcion++,
                    ]);
                }
            }
        }

        $examenFinal = ExamenFinal::create([
            'curso_id' => $curso->id,
            'titulo' => $this->examenFinal['titulo'] ?: 'Examen Final',
            'min_aprobacion' => 80,
        ]);

        $ordenPregunta = 1;
        foreach ($this->examenFinal['preguntas'] as $preguntaData) {
            $pregunta = PreguntaExamenFinal::create([
                'examen_final_id' => $examenFinal->id,
                'texto' => $preguntaData['texto'],
                'orden' => $ordenPregunta++,
            ]);

            $ordenOpcion = 1;
            foreach ($preguntaData['opciones'] as $opcionData) {
                OpcionExamenFinal::create([
                    'pregunta_id' => $pregunta->id,
                    'texto' => $opcionData['texto'],
                    'es_correcta' => $opcionData['es_correcta'],
                    'orden' => $ordenOpcion++,
                ]);
            }
        }

        session()->flash('success', 'Curso creado exitosamente.');
        return redirect()->route('cursos.show', $curso);
    }
}
