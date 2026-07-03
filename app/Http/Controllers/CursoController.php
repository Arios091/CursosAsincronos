<?php

namespace App\Http\Controllers;

use App\Models\Curso;
use App\Models\Cuestionario;
use App\Models\ExamenFinal;
use App\Models\Material;
use App\Models\Modulo;
use App\Models\PreguntaCuestionario;
use App\Models\OpcionPregunta;
use App\Models\ProgresoCurso;
use App\Models\ProgresoMaterial;
use App\Models\ResultadoCuestionario;
use App\Models\ResultadoExamenFinal;
use App\Models\OpcionesCuestionario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade as PDF;

class CursoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['verificarCertificado', 'verificarCertificadoPublico']);
    }

    public function index()
    {
        $user = auth()->user();
        $query = Curso::with('user');

        if ($user->isDocente()) {
            $query->where('audiencia', 'docente');
        }

        $cursos = $query->get();

        return view('cursos.index', compact('cursos'));
    }

    public function show(Curso $curso)
    {
        $curso->load('user', 'modulos.materiales');
        return view('cursos.show', compact('curso'));
    }

    public function comenzar(Curso $curso)
    {
        $user = auth()->user();

        $progreso = ProgresoCurso::where('user_id', $user->id)
            ->where('curso_id', $curso->id)
            ->first();

        if ($progreso) {
            if ($progreso->completado) {
                return redirect()->route('cursos.completado', $curso->id);
            }

            $user->curso_en_progreso_id = $curso->id;
            $user->save();

            return redirect()->route('mis-cursos', $curso->id);
        }

        if ($user->curso_en_progreso_id !== null) {
            return redirect()->route('cursos.show', $curso)
                ->with('error', 'Ya tienes un curso en progreso. Debes completarlo primero.');
        }

        ProgresoCurso::create([
            'user_id' => $user->id,
            'curso_id' => $curso->id,
            'completado' => false,
            'progreso' => 0,
        ]);

        $user->curso_en_progreso_id = $curso->id;
        $user->save();

        return redirect()->route('mis-cursos', $curso->id);
    }

    public function verCurso(Curso $curso)
    {
        $user = auth()->user();

        $progresoCurso = ProgresoCurso::where('user_id', $user->id)
            ->where('curso_id', $curso->id)
            ->firstOrFail();

        if ($progresoCurso->completado) {
            return redirect()->route('cursos.completado', $curso->id);
        }

        $curso->load('modulos.materiales', 'modulos.cuestionario.preguntas.opciones', 'examenFinal.preguntas.opciones');

        $modulos = $curso->modulos->sortBy('orden');
        $totalModulos = $modulos->count();

        $moduloActual = null;
        $materialActual = null;
        $modulosCompletados = 0;
        $siguienteModulo = null;

        $todosMaterialesIds = $modulos->pluck('materiales')->flatten()->pluck('id');
        $materialesCompletados = ProgresoMaterial::where('user_id', $user->id)
            ->whereIn('material_id', $todosMaterialesIds)
            ->where('completado', true)
            ->pluck('material_id')
            ->toArray();

        $cuestionarioIds = $modulos->pluck('cuestionario.id')->filter();
        $resultadosCuestionarios = ResultadoCuestionario::where('user_id', $user->id)
            ->whereIn('cuestionario_id', $cuestionarioIds)
            ->where('aprobado', true)
            ->get()
            ->keyBy('cuestionario_id');

        foreach ($modulos as $modulo) {
            $materialesModulo = $modulo->materiales->sortBy('orden');
            $totalMod = $materialesModulo->count();
            $completadosMod = $materialesModulo->filter(function ($m) use ($materialesCompletados) {
                return in_array($m->id, $materialesCompletados);
            })->count();

            $quizAprobado = true;
            if ($modulo->cuestionario) {
                $quizAprobado = isset($resultadosCuestionarios[$modulo->cuestionario->id]);
            }

            $moduloCompleto = $completadosMod === $totalMod && $quizAprobado;

            if ($moduloCompleto) {
                $modulosCompletados++;
                continue;
            }

            if (!$moduloActual) {
                $moduloActual = $modulo;

                foreach ($materialesModulo as $mat) {
                    if (!in_array($mat->id, $materialesCompletados)) {
                        $materialActual = $mat;
                        break;
                    }
                }
            }

            if ($moduloActual && !$siguienteModulo) {
                $siguienteModulo = $modulo;
            }
        }

        if ($moduloActual && !$materialActual && $moduloActual->cuestionario && !isset($resultadosCuestionarios[$moduloActual->cuestionario->id])) {
            $materialActual = null;
        }

        $progreso = $totalModulos > 0 ? round(($modulosCompletados / $totalModulos) * 100) : 0;

        $mostrarExamenFinal = false;
        $examenFinalAprobado = false;
        $resultadoExamenFinal = null;

        // All modules done — show final exam if it exists
        if ($modulosCompletados === $totalModulos && $totalModulos > 0 && $curso->examenFinal) {
            $mostrarExamenFinal = true;
            $resultadoExamenFinal = ResultadoExamenFinal::where('user_id', $user->id)
                ->where('examen_final_id', $curso->examenFinal->id)
                ->first();
            $examenFinalAprobado = $resultadoExamenFinal && $resultadoExamenFinal->aprobado;
        }

        return view('cursos.ver', compact(
            'curso', 'progresoCurso', 'modulos', 'moduloActual', 'materialActual',
            'modulosCompletados', 'totalModulos', 'progreso', 'materialesCompletados',
            'resultadosCuestionarios', 'mostrarExamenFinal', 'examenFinalAprobado',
            'resultadoExamenFinal'
        ));
    }

    public function completarMaterial(Request $request, Material $material)
    {
        $user = auth()->user();

        ProgresoMaterial::updateOrCreate(
            ['user_id' => $user->id, 'material_id' => $material->id],
            ['completado' => true]
        );

        $curso = $material->modulo->curso;
        $modulos = $curso->modulos->sortBy('orden');
        $totalModulos = $modulos->count();

        $todosMaterialesIds = $modulos->pluck('materiales')->flatten()->pluck('id');
        $materialesCompletados = ProgresoMaterial::where('user_id', $user->id)
            ->whereIn('material_id', $todosMaterialesIds)
            ->where('completado', true)
            ->pluck('material_id')
            ->toArray();

        $cuestionarioIds = $modulos->pluck('cuestionario.id')->filter();
        $resultadosCuestionarios = ResultadoCuestionario::where('user_id', $user->id)
            ->whereIn('cuestionario_id', $cuestionarioIds)
            ->where('aprobado', true)
            ->get()
            ->keyBy('cuestionario_id');

        $modulosCompletados = 0;
        $siguienteMaterial = null;
        $siguienteEsCuestionario = false;

        foreach ($modulos as $modulo) {
            $materialesModulo = $modulo->materiales->sortBy('orden');
            $completadosMod = $materialesModulo->filter(function ($m) use ($materialesCompletados) {
                return in_array($m->id, $materialesCompletados);
            })->count();

            $quizAprobado = true;
            if ($modulo->cuestionario) {
                $quizAprobado = isset($resultadosCuestionarios[$modulo->cuestionario->id]);
            }

            $moduloCompleto = $materialesModulo->count() === $completadosMod && $quizAprobado;

            if ($moduloCompleto) {
                $modulosCompletados++;
                continue;
            }

            // Find first incomplete material in this module
            if (!$siguienteMaterial && !$siguienteEsCuestionario) {
                foreach ($materialesModulo as $mat) {
                    if (!in_array($mat->id, $materialesCompletados)) {
                        $siguienteMaterial = $mat;
                        break;
                    }
                }
                if (!$siguienteMaterial && $modulo->cuestionario && !$quizAprobado) {
                    $siguienteEsCuestionario = true;
                }
            }
            break;
        }

        $progresoCurso = ProgresoCurso::where('user_id', $user->id)
            ->where('curso_id', $curso->id)
            ->first();

        $nuevoProgreso = $totalModulos > 0 ? round(($modulosCompletados / $totalModulos) * 100) : 0;
        $progresoCurso->progreso = $nuevoProgreso;

        if ($modulosCompletados === $totalModulos && $totalModulos > 0) {
            if ($curso->examenFinal) {
                $progresoCurso->save();
                return response()->json([
                    'completado' => false,
                    'mostrarExamenFinal' => true,
                    'progreso' => 100,
                    'mensaje' => 'Has completado todos los modulos. Ahora rinde el examen final.',
                ]);
            }

            // No final exam - mark course complete
            $progresoCurso->completado = true;
            $progresoCurso->progreso = 100;
            $progresoCurso->save();

            $user->curso_en_progreso_id = null;
            $user->save();

            return response()->json([
                'completado' => true,
                'mostrarExamenFinal' => false,
                'progreso' => 100,
                'redirect' => route('cursos.completado', $curso->id),
            ]);
        }

        $progresoCurso->save();

        return response()->json([
            'completado' => false,
            'mostrarExamenFinal' => false,
            'progreso' => $nuevoProgreso,
            'siguiente' => $siguienteMaterial ? [
                'id' => $siguienteMaterial->id,
                'titulo' => $siguienteMaterial->titulo,
                'tipo' => $siguienteMaterial->tipo,
            ] : null,
            'siguienteEsCuestionario' => $siguienteEsCuestionario,
            'mensaje' => 'Material completado.',
        ]);
    }

    public function enviarCuestionario(Request $request, Material $material)
    {
        $user = auth()->user();
        $respuestas = $request->input('respuestas', []);

        $opcionesCorrectas = OpcionesCuestionario::where('material_id', $material->id)
            ->where('es_correcta', true)
            ->pluck('id')
            ->toArray();

        $aciertos = 0;
        foreach ($respuestas as $respuesta) {
            if (in_array($respuesta, $opcionesCorrectas)) {
                $aciertos++;
            }
        }

        $totalPreguntas = OpcionesCuestionario::where('material_id', $material->id)->count();
        $porcentaje = $totalPreguntas > 0 ? round(($aciertos / $totalPreguntas) * 100) : 0;

        $minAprobacion = 80;

        $aprobado = $porcentaje >= $minAprobacion;

        $resultado = ResultadoCuestionario::where('user_id', $user->id)
            ->where('material_id', $material->id)
            ->first();

        if ($resultado) {
            $resultado->update([
                'intentos' => $resultado->intentos + 1,
                'puntaje' => $porcentaje,
                'aprobado' => $aprobado,
            ]);
        } else {
            ResultadoCuestionario::create([
                'user_id' => $user->id,
                'material_id' => $material->id,
                'intentos' => 1,
                'puntaje' => $porcentaje,
                'aprobado' => $aprobado,
            ]);
        }

        if ($aprobado) {
            ProgresoMaterial::updateOrCreate(
                ['user_id' => $user->id, 'material_id' => $material->id],
                ['completado' => true]
            );
        }

        return response()->json([
            'aprobado' => $aprobado,
            'puntaje' => $porcentaje,
            'aciertos' => $aciertos,
            'total' => $totalPreguntas,
        ]);
    }

    public function enviarCuestionarioModulo(Request $request, Modulo $modulo)
    {
        $user = auth()->user();
        $cuestionario = $modulo->cuestionario;

        if (!$cuestionario) {
            return response()->json(['error' => 'Este modulo no tiene cuestionario.'], 404);
        }

        $cuestionario->load('preguntas.opciones');
        $respuestas = $request->input('respuestas', []);

        $totalPreguntas = $cuestionario->preguntas->count();
        $aciertos = 0;

        foreach ($cuestionario->preguntas as $pregunta) {
            $respuestaId = $respuestas[$pregunta->id] ?? null;
            $correcta = $pregunta->opciones->firstWhere('es_correcta', true);
            if ($correcta && $respuestaId == $correcta->id) {
                $aciertos++;
            }
        }

        $porcentaje = $totalPreguntas > 0 ? round(($aciertos / $totalPreguntas) * 100) : 0;
        $aprobado = $porcentaje >= $cuestionario->min_aprobacion;

        $resultado = ResultadoCuestionario::where('user_id', $user->id)
            ->where('cuestionario_id', $cuestionario->id)
            ->first();

        if ($resultado) {
            $resultado->update([
                'intentos' => $resultado->intentos + 1,
                'puntaje' => $porcentaje,
                'aprobado' => $aprobado,
            ]);
        } else {
            ResultadoCuestionario::create([
                'user_id' => $user->id,
                'cuestionario_id' => $cuestionario->id,
                'intentos' => 1,
                'puntaje' => $porcentaje,
                'aprobado' => $aprobado,
            ]);
        }

        return response()->json([
            'aprobado' => $aprobado,
            'puntaje' => $porcentaje,
            'aciertos' => $aciertos,
            'total' => $totalPreguntas,
        ]);
    }

    public function enviarExamenFinal(Request $request, Curso $curso)
    {
        $user = auth()->user();
        $examenFinal = $curso->examenFinal;

        if (!$examenFinal) {
            return response()->json(['error' => 'Este curso no tiene examen final.'], 404);
        }

        $examenFinal->load('preguntas.opciones');
        $respuestas = $request->input('respuestas', []);

        $totalPreguntas = $examenFinal->preguntas->count();
        $aciertos = 0;

        foreach ($examenFinal->preguntas as $pregunta) {
            $respuestaId = $respuestas[$pregunta->id] ?? null;
            $correcta = $pregunta->opciones->firstWhere('es_correcta', true);
            if ($correcta && $respuestaId == $correcta->id) {
                $aciertos++;
            }
        }

        $porcentaje = $totalPreguntas > 0 ? round(($aciertos / $totalPreguntas) * 100) : 0;
        $aprobado = $porcentaje >= ($examenFinal->min_aprobacion ?? 80);

        $resultado = ResultadoExamenFinal::where('user_id', $user->id)
            ->where('examen_final_id', $examenFinal->id)
            ->first();

        if ($resultado) {
            $resultado->update([
                'intentos' => $resultado->intentos + 1,
                'puntaje' => $porcentaje,
                'aprobado' => $aprobado,
            ]);
        } else {
            ResultadoExamenFinal::create([
                'user_id' => $user->id,
                'examen_final_id' => $examenFinal->id,
                'intentos' => 1,
                'puntaje' => $porcentaje,
                'aprobado' => $aprobado,
            ]);
        }

        if ($aprobado) {
            $progresoCurso = ProgresoCurso::where('user_id', $user->id)
                ->where('curso_id', $curso->id)
                ->first();

            $progresoCurso->completado = true;
            $progresoCurso->progreso = 100;
            $progresoCurso->save();

            $user->curso_en_progreso_id = null;
            $user->save();

            return response()->json([
                'aprobado' => true,
                'puntaje' => $porcentaje,
                'redirect' => route('cursos.completado', $curso->id),
            ]);
        }

        return response()->json([
            'aprobado' => false,
            'puntaje' => $porcentaje,
            'aciertos' => $aciertos,
            'total' => $totalPreguntas,
            'minimo' => $examenFinal->min_aprobacion ?? 80,
        ]);
    }

    public function completado(Curso $curso)
    {
        $user = auth()->user();
        $progresoCurso = ProgresoCurso::where('user_id', $user->id)
            ->where('curso_id', $curso->id)
            ->where('completado', true)
            ->firstOrFail();

        $codigo = str_pad($progresoCurso->id, 6, '0', STR_PAD_LEFT);

        return view('cursos.completado', compact('curso', 'progresoCurso', 'codigo'));
    }

    public function destroy(Curso $curso)
    {
        if ($curso->imagen) {
            Storage::disk('public')->delete($curso->imagen);
        }
        $curso->delete();

        return redirect()->route('cursos.index')
            ->with('success', 'Curso eliminado exitosamente.');
    }

    public function verificarCertificadoPublico($codigo)
    {
        $progreso = ProgresoCurso::whereRaw(
            "LPAD(CAST(id AS TEXT), 6, '0') = ?", [$codigo]
        )->with(['user', 'curso'])->first();

        return view('verificar.certificado', compact('progreso', 'codigo'));
    }

    public function verificarCertificado($codigo)
    {
        $progreso = ProgresoCurso::whereRaw(
            "LPAD(CAST(id AS TEXT), 6, '0') = ?", [$codigo]
        )->with(['user', 'curso'])->first();

        if (!$progreso || !$progreso->completado) {
            return response()->json([
                'valido' => false,
                'message' => 'Certificado no encontrado o invalido',
            ], 404);
        }

        return response()->json([
            'valido' => true,
            'certificado' => $codigo,
            'estudiante' => $progreso->user->name,
            'curso' => $progreso->curso->titulo,
            'fecha' => $progreso->updated_at->format('d/m/Y'),
        ]);
    }
}
