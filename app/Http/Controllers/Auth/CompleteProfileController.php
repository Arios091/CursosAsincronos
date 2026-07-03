<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CompleteProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function showForm()
    {
        $user = auth()->user();

        $parts = explode(' ', trim($user->name));
        $suggestedFirstName = $parts[0] ?? '';
        $suggestedLastName = count($parts) > 1 ? end($parts) : '';

        return view('auth.completar-perfil', compact('user', 'suggestedFirstName', 'suggestedLastName'));
    }

    public function save(Request $request)
    {
        $request->validate([
            'primer_nombre' => 'required|string|max:100',
            'segundo_nombre' => 'nullable|string|max:100',
            'primer_apellido' => 'required|string|max:100',
            'segundo_apellido' => 'nullable|string|max:100',
        ], [
            'primer_nombre.required' => 'El primer nombre es obligatorio.',
            'primer_nombre.max' => 'El primer nombre no debe exceder los 100 caracteres.',
            'primer_apellido.required' => 'El primer apellido es obligatorio.',
            'primer_apellido.max' => 'El primer apellido no debe exceder los 100 caracteres.',
        ]);

        $user = auth()->user();

        $primerNombre = $this->normalizeName($request->primer_nombre);
        $segundoNombre = $this->normalizeName($request->segundo_nombre);
        $primerApellido = $this->normalizeName($request->primer_apellido);
        $segundoApellido = $this->normalizeName($request->segundo_apellido);

        $fullName = trim($primerNombre . ' ' . $segundoNombre . ' ' . $primerApellido . ' ' . $segundoApellido);
        $fullName = preg_replace('/\s+/', ' ', $fullName);

        $user->update([
            'name' => $fullName,
            'primer_nombre' => $primerNombre,
            'segundo_nombre' => $segundoNombre ?: null,
            'primer_apellido' => $primerApellido,
            'segundo_apellido' => $segundoApellido ?: null,
        ]);

        return redirect()->intended('/home')
            ->with('success', 'Perfil completado exitosamente. Bienvenido!');
    }

    private function normalizeName(?string $name): string
    {
        if (!$name || !trim($name)) {
            return '';
        }
        $name = mb_strtolower(trim($name));
        return ucwords($name);
    }
}
