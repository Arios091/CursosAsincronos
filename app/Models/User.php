<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable, \Illuminate\Auth\MustVerifyEmail;

    protected $fillable = [
        'name',
        'primer_nombre',
        'segundo_nombre',
        'primer_apellido',
        'segundo_apellido',
        'email',
        'password',
        'role',
        'curso_en_progreso_id',
        'email_verified_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function isAdmin(): bool
    {
        return in_array($this->role, ['admin', 'admin_global']);
    }

    public function isAdminGlobal(): bool
    {
        return $this->role === 'admin_global';
    }

    public function isDocente(): bool
    {
        return $this->role === 'docente';
    }

    public function isEstudiante(): bool
    {
        return $this->role === 'estudiante';
    }

    public function puedeGestionarUsuarios(): bool
    {
        return $this->role === 'admin_global';
    }

    public function puedeGestionarCursos(): bool
    {
        return in_array($this->role, ['admin', 'admin_global']);
    }

    public function cursoEnProgreso()
    {
        return $this->belongsTo(Curso::class, 'curso_en_progreso_id');
    }

    public function progresos()
    {
        return $this->hasMany(ProgresoCurso::class);
    }
}
