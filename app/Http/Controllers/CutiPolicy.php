<?php

namespace App\Policies;

use App\Models\Cuti;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CutiPolicy
{
    /**
     * Allow admin to do anything.
     */
    public function before(User $user, string $ability): bool|null
    {
        return $user->hasRole('admin') ? true : null;
    }

    public function view(User $user, Cuti $cuti): bool
    {
        return $user->id === $cuti->user_id;
    }
}