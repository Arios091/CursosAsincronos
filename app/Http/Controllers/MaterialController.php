<?php

namespace App\Http\Controllers;

use App\Models\Material;
use App\Models\Modulo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MaterialController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show(Material $material)
    {
        $material->load('modulo.curso', 'opciones');
        return view('cursos.material', compact('material'));
    }

    public function update(Request $request, Material $material)
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'tipo' => 'required|in:video,pdf,cuestionario',
            'url' => 'nullable|string',
            'archivo' => 'nullable|file|max:20480',
        ]);

        $data = $request->only(['titulo', 'tipo', 'url']);

        if ($request->hasFile('archivo')) {
            if ($material->archivo) {
                Storage::disk('public')->delete($material->archivo);
            }
            $data['archivo'] = $request->file('archivo')->store('materiales', 'public');
        }

        $material->update($data);

        return back()->with('success', 'Material actualizado exitosamente.');
    }

    public function destroy(Material $material)
    {
        if ($material->archivo) {
            Storage::disk('public')->delete($material->archivo);
        }
        $material->delete();

        return back()->with('success', 'Material eliminado exitosamente.');
    }
}
