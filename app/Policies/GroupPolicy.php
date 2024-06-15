<?php

namespace App\Policies;

use App\Models\Group;
use App\Models\User;

class GroupPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
    }

    public function view(User $user): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Group $group): bool
    {
        if ($user->id == $group->owner_id) return true; else return false;
    }

    public function deleteUserFromGroup(User $user, Group $group): bool
    {
        if ($user->id == $group->owner_id) return true; else return false;
    }
}
