<?php

namespace Tests\Feature;

use App\Models\PendingUser;
use App\Models\User;
use App\Notifications\VerifyPendingEmail;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use DatabaseTransactions;

    public function test_usuario_puede_ver_formulario_registro()
    {
        $response = $this->get('/register');
        $response->assertStatus(200);
    }

    public function test_usuario_puede_registrarse_con_email_institucional()
    {
        $response = $this->post('/register', [
            'email' => 'juan.perez@unas.edu.pe',
            'password' => 'Password123',
            'password_confirmation' => 'Password123',
        ]);

        $response->assertRedirect('/registro/verificar');
        $this->assertDatabaseHas('pending_users', [
            'email' => 'juan.perez@unas.edu.pe',
            'role' => 'docente',
        ]);
    }

    public function test_registro_falla_con_email_no_institucional()
    {
        $response = $this->post('/register', [
            'email' => 'juan.perez@gmail.com',
            'password' => 'Password123',
            'password_confirmation' => 'Password123',
        ]);

        $response->assertSessionHasErrors('email');
    }

    public function test_registro_falla_con_password_corta()
    {
        $response = $this->post('/register', [
            'email' => 'juan.perez@unas.edu.pe',
            'password' => 'Ab1',
            'password_confirmation' => 'Ab1',
        ]);

        $response->assertSessionHasErrors('password');
    }

    public function test_pending_user_tiene_token_unico()
    {
        $pendingUser = PendingUser::create([
            'name' => 'Test',
            'email' => 'test@unas.edu.pe',
            'password' => Hash::make('password123'),
            'role' => 'docente',
            'token' => Str::uuid(),
            'expires_at' => now()->addHours(24),
        ]);

        $this->assertNotNull($pendingUser->token);
        $this->assertFalse($pendingUser->hasExpired());
    }

    public function test_pending_user_puede_expiar()
    {
        $pendingUser = PendingUser::create([
            'name' => 'Test',
            'email' => 'test2@unas.edu.pe',
            'password' => Hash::make('password123'),
            'role' => 'docente',
            'token' => Str::uuid(),
            'expires_at' => now()->subHours(1),
        ]);

        $this->assertTrue($pendingUser->hasExpired());
    }

    public function test_verificacion_con_token_valido_crea_usuario()
    {
        $pendingUser = PendingUser::create([
            'name' => 'Juan Perez',
            'email' => 'juan.verificado@unas.edu.pe',
            'password' => Hash::make('password123'),
            'role' => 'docente',
            'token' => $token = Str::uuid(),
            'expires_at' => now()->addHours(24),
        ]);

        $response = $this->get('/registro/verificar/' . $token);

        $response->assertRedirect('/completar-perfil');
        $this->assertDatabaseHas('users', [
            'email' => 'juan.verificado@unas.edu.pe',
        ]);
        $this->assertDatabaseMissing('pending_users', [
            'email' => 'juan.verificado@unas.edu.pe',
        ]);
    }

    public function test_verificacion_con_token_invalido_muestra_error()
    {
        $response = $this->get('/registro/verificar/token-invalido');
        $response->assertRedirect('/login');
        $response->assertSessionHas('error');
    }

    public function test_usuario_puede_iniciar_sesion()
    {
        $user = User::factory()->create([
            'email' => 'login@unas.edu.pe',
            'password' => Hash::make('password123'),
            'email_verified_at' => now(),
        ]);

        $response = $this->post('/login', [
            'email' => 'login@unas.edu.pe',
            'password' => 'password123',
        ]);

        $response->assertRedirect('/home');
        $this->assertAuthenticated();
    }

    public function test_usuario_no_verificado_no_puede_acceder_a_home()
    {
        $user = User::factory()->create([
            'email' => 'no-verificado@unas.edu.pe',
            'password' => Hash::make('password123'),
            'email_verified_at' => null,
        ]);

        $response = $this->post('/login', [
            'email' => 'no-verificado@unas.edu.pe',
            'password' => 'password123',
        ]);

        $this->assertAuthenticated();
        $response = $this->get('/home');
        $response->assertRedirect('/email/verify');
    }
}
