<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\ProgresoCurso;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin_global');
    }

    public function index(Request $request)
    {
        $query = User::query();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'ilike', "%{$search}%")
                  ->orWhere('email', 'ilike', "%{$search}%");
            });
        }

        if ($request->filled('role')) {
            $query->where('role', $request->input('role'));
        }

        $usuarios = $query->withCount('progresos')->orderBy('created_at', 'desc')->paginate(15);

        return view('admin.usuarios.index', compact('usuarios'));
    }

    public function show(User $user)
    {
        $progresos = ProgresoCurso::where('user_id', $user->id)
            ->with('curso')
            ->get();

        return view('admin.usuarios.show', compact('user', 'progresos'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'role' => 'required|in:admin_global,admin,docente,estudiante',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $data = [
            'name' => $request->input('name'),
            'role' => $request->input('role'),
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->input('password'));
        }

        $user->update($data);

        return redirect()->route('admin.usuarios.show', $user)
            ->with('success', 'Usuario actualizado exitosamente.');
    }

    public function destroy(User $user)
    {
        if ($user->isAdminGlobal() && User::where('role', 'admin_global')->count() <= 1) {
            return back()->with('error', 'No se puede eliminar el unico administrador global.');
        }

        $user->delete();

        return redirect()->route('admin.usuarios.index')
            ->with('success', 'Usuario eliminado exitosamente.');
    }

}
