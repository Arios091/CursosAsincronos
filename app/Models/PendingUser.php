<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class PendingUser extends Model
{
    use Notifiable;

    protected $table = 'pending_users';

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'token',
        'expires_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
    ];

    public function hasExpired(): bool
    {
        return $this->expires_at->isPast();
    }
}
