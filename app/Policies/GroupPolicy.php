<?php

namespace App\Policies;

use App\Models\User;

class GroupPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
    }

//    public function view(User $user): bool
//    {
//        return true;
//    }

    public function create(User $user): bool
    {
        return true;
    }
}
