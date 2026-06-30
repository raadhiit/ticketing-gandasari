<?php

namespace App\Policies;

use App\Models\TicketCategory;
use App\Models\User;

class TicketCategoryPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('settings.manage');
    }

    public function view(User $user, TicketCategory $ticketCategory): bool
    {
        return $user->can('settings.manage');
    }

    public function create(User $user): bool
    {
        return $user->can('settings.manage');
    }

    public function update(User $user, TicketCategory $ticketCategory): bool
    {
        return $user->can('settings.manage');
    }

    public function delete(User $user, TicketCategory $ticketCategory): bool
    {
        return $user->can('settings.manage');
    }
}
