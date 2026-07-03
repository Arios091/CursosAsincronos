<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Curso;
use Illuminate\Auth\Access\HandlesAuthorization;

class CursoPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Curso $curso): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return in_array($user->role, ['admin', 'admin_global']);
    }

    public function update(User $user, Curso $curso): bool
    {
        return in_array($user->role, ['admin', 'admin_global']);
    }

    public function delete(User $user, Curso $curso): bool
    {
        return in_array($user->role, ['admin', 'admin_global']);
    }
}
