<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\PasswordReset;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ForgotPasswordController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function showLinkRequestForm()
    {
        return view('auth.passwords.email');
    }

    public function sendResetLinkEmail(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email', 'exists:users,email'],
        ], [
            'email.required' => 'El correo es obligatorio.',
            'email.email' => 'Ingrese un correo valido.',
            'email.exists' => 'No encontramos una cuenta con ese correo.',
        ]);

        $user = User::where('email', $request->email)->first();

        PasswordReset::where('email', $user->email)
            ->whereNull('used_at')
            ->where('expires_at', '>', Carbon::now())
            ->update(['used_at' => Carbon::now()]);

        $token = Str::random(64);
        $hashedToken = Hash::make($token);

        PasswordReset::create([
            'email' => $user->email,
            'token' => $hashedToken,
            'expires_at' => Carbon::now()->addHours(3),
        ]);

        try {
            $user->notify(new \App\Notifications\ResetPasswordNotification($token, $user->email));
        } catch (\Throwable $e) {
            report($e);

            return back()->with('error', 'No pudimos enviar el correo de restablecimiento. Intenta nuevamente mas tarde o contacta al administrador.');
        }

        return back()->with('status', 'Te enviamos un enlace de restablecimiento a tu correo institucional.');
    }
}
