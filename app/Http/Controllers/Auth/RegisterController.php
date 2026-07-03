<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\PendingUser;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class RegisterController extends Controller
{
    use RegistersUsers;

    protected $redirectTo = '/registro/verificar';

    public function __construct()
    {
        $this->middleware('guest');
    }

    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        $user = $this->create($request->all());

        return redirect($this->redirectPath());
    }

    protected function validator(array $data)
    {
        $messages = [
            'email.required' => 'El correo electronico es obligatorio.',
            'email.email' => 'Ingrese un correo electronico valido.',
            'email.unique' => 'Este correo ya esta registrado.',
            'email.regex' => 'Solo se permiten correos institucionales @unas.edu.pe.',
            'password.required' => 'La contrasena es obligatoria.',
            'password.min' => 'La contrasena debe tener al menos 8 caracteres.',
            'password.confirmed' => 'Las contrasenas no coinciden.',
            'password.regex' => 'La contrasena debe tener al menos una mayuscula y un numero.',
        ];

        return Validator::make($data, [
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                'unique:users',
                'unique:pending_users',
                'regex:/^[a-zA-Z0-9._%+-]+@unas.edu.pe$/i',
            ],
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
                'regex:/[A-Z]/',
                'regex:/[0-9]/',
            ],
        ], $messages);
    }

    protected function create(array $data)
    {
        $partialName = $this->extractNameFromEmail($data['email']);

        $pendingUser = PendingUser::create([
            'name' => $partialName,
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => 'docente',
            'token' => Str::uuid(),
            'expires_at' => now()->addHours(24),
        ]);

        $pendingUser->notify(new \App\Notifications\VerifyPendingEmail($pendingUser));

        return $pendingUser;
    }

    private function extractNameFromEmail(string $email): string
    {
        $localPart = explode('@', $email)[0];
        $parts = explode('.', $localPart);

        $firstName = isset($parts[0]) ? ucwords(mb_strtolower($parts[0])) : '';
        $lastName = isset($parts[1]) ? ucwords(mb_strtolower($parts[1])) : '';

        return trim($firstName . ' ' . $lastName);
    }
}
