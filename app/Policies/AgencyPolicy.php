<?php

namespace App\Policies;

use App\Agency;
use App\Media;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AgencyPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Define policy for creating a new agency
     *
     * @param User $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->is_agency || $user->is_agency_club;
    }

    /**
     * Define policy for updating an agency
     *
     * @param User $user
     * @param Agency $agency
     * @return bool
     */
    public function update(User $user, Agency $agency)
    {
        return $user->id === $agency->user_id;
    }
}
