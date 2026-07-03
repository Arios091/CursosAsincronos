<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\PendingUser;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class PendingVerificationController extends Controller
{
    public function showPending()
    {
        return view('auth.pending-verification');
    }

    public function verify($token)
    {
        $pendingUser = PendingUser::where('token', $token)->first();

        if (!$pendingUser) {
            return redirect()->route('login')
                ->with('error', 'Enlace invalido.');
        }

        if ($pendingUser->hasExpired()) {
            $pendingUser->delete();
            return redirect()->route('login')
                ->with('error', 'Enlace expirado. Vuelve a registrarte.');
        }

        $existingUser = User::where('email', $pendingUser->email)->first();
        if ($existingUser) {
            $pendingUser->delete();
            return redirect()->route('login')
                ->with('error', 'Ya registrado. Inicia sesion.');
        }

        $user = User::create([
            'name' => $pendingUser->name,
            'email' => $pendingUser->email,
            'password' => $pendingUser->password,
            'role' => $pendingUser->role,
            'email_verified_at' => now(),
        ]);

        $pendingUser->delete();

        Auth::login($user);

        return redirect()->route('completar.perfil');
    }
}
