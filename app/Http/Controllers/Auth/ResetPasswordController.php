<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\PasswordReset;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ResetPasswordController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function showResetForm($token)
    {
        $email = request('email');

        $reset = PasswordReset::where('email', $email)->latest()->first();

        if (!$reset || !$reset->isValid()) {
            return redirect()->route('password.request')
                ->with('error', 'El enlace de restablecimiento ha expirado o ya fue usado.');
        }

        if (!$reset || !Hash::check($token, $reset->token)) {
            return redirect()->route('password.request')
                ->with('error', 'Enlace invalido.');
        }

        return view('auth.passwords.reset', [
            'token' => $token,
            'email' => $email,
        ]);
    }

    public function reset(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email|exists:users,email',
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
                'regex:/[A-Z]/',
                'regex:/[0-9]/',
            ],
        ], [
            'token.required' => 'El token es obligatorio.',
            'email.required' => 'El correo es obligatorio.',
            'email.email' => 'Ingrese un correo valido.',
            'email.exists' => 'No encontramos una cuenta con ese correo.',
            'password.required' => 'La contrasena es obligatoria.',
            'password.min' => 'La contrasena debe tener al menos 8 caracteres.',
            'password.confirmed' => 'Las contrasenas no coinciden.',
            'password.regex' => 'La contrasena debe tener al menos una mayuscula y un numero.',
        ]);

        $reset = PasswordReset::where('email', $request->email)->latest()->first();

        if (!$reset || !$reset->isValid()) {
            return redirect()->route('password.request')
                ->with('error', 'El enlace de restablecimiento ha expirado o ya fue usado.');
        }

        if (!Hash::check($request->token, $reset->token)) {
            return redirect()->route('password.request')
                ->with('error', 'Enlace invalido.');
        }

        $user = User::where('email', $request->email)->first();
        $user->password = Hash::make($request->password);
        $user->save();

        $reset->used_at = Carbon::now();
        $reset->save();

        return redirect()->route('login')
            ->with('success', 'Contrasena restablecida exitosamente. Ahora puedes iniciar sesion.');
    }
}
