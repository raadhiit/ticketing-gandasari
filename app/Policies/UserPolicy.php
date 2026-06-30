<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('settings.manage');
    }

    public function view(User $user, User $model): bool
    {
        return $user->can('settings.manage');
    }

    public function create(User $user): bool
    {
        return $user->can('settings.manage');
    }

    public function update(User $user, User $model): bool
    {
        return $user->can('settings.manage');
    }

    public function delete(User $user, User $model): bool
    {
        return $user->can('settings.manage') && $user->id !== $model->id;
    }
}
