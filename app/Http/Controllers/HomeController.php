<?php

namespace App\Http\Controllers;

use App\Models\Curso;
use App\Models\ProgresoCurso;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = auth()->user();

        if (empty($user->primer_nombre) || empty($user->primer_apellido)) {
            return redirect()->route('completar.perfil');
        }

        $cursoEnProgreso = null;
        if ($user->curso_en_progreso_id) {
            $cursoEnProgreso = Curso::find($user->curso_en_progreso_id);
        }

        $progresos = ProgresoCurso::where('user_id', $user->id)
            ->with('curso')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('home', compact('cursoEnProgreso', 'progresos'));
    }
}
