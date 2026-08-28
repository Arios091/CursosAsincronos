<?php

namespace App\Http\Controllers;

use App\Models\Material;
use App\Models\ProgresoCurso;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function upload(Request $request)
    {
        $request->validate([
            'file' => 'required|file|max:20480',
            'type' => 'required|in:material,curso',
        ]);

        $path = $request->file('file')->store($request->input('type'), 'public');

        return response()->json([
            'success' => true,
            'path' => $path,
            'url' => storage_url($path),
        ]);
    }

    public function delete(Request $request)
    {
        $request->validate([
            'path' => 'required|string',
        ]);

        if (Storage::disk('public')->exists($request->input('path'))) {
            Storage::disk('public')->delete($request->input('path'));
        }

        return response()->json(['success' => true]);
    }

    public function verPdf($filename)
    {
        $material = Material::where('archivo', 'materiales/' . $filename)->firstOrFail();
        
        $this->authorizeMaterialAccess($material);

        $path = 'materiales/' . $filename;
        
        if (!Storage::disk('public')->exists($path)) {
            abort(404, 'Archivo no encontrado');
        }

        $file = Storage::disk('public')->get($path);
        $type = Storage::disk('public')->mimeType($path);

        return response($file, 200, [
            'Content-Type' => $type,
            'Content-Disposition' => 'inline; filename="' . $filename . '"',
        ]);
    }

    public function descargarPdf($filename)
    {
        $material = Material::where('archivo', 'materiales/' . $filename)->firstOrFail();
        
        $this->authorizeMaterialAccess($material);

        $path = 'materiales/' . $filename;
        
        if (!Storage::disk('public')->exists($path)) {
            abort(404, 'Archivo no encontrado');
        }

        return Storage::disk('public')->download($path, $filename);
    }

    protected function authorizeMaterialAccess(Material $material)
    {
        $user = auth()->user();
        
        // Admin global y admin tienen acceso a todo
        if ($user->isAdmin() || $user->isAdminGlobal()) {
            return true;
        }

        $curso = $material->modulo->curso;
        
        // Verificar que el usuario está inscrito en el curso
        $progreso = ProgresoCurso::where('user_id', $user->id)
            ->where('curso_id', $curso->id)
            ->first();

        if (!$progreso) {
            abort(403, 'No tienes acceso a este material. Debes inscribirte en el curso primero.');
        }

        return true;
    }
}