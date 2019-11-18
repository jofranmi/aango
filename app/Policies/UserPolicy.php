<?php

namespace App\Policies;

use App\Models\UserType;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Auth;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * @param User $user
     * @param UserType $userType
     * @return bool
     */
    public function admin(User $user, UserType $userType)
    {
        return $user->user_type_id == $userType->id;
    }
}
