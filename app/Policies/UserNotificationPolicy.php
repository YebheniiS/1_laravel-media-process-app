<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserNotificationPolicy
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
     * Define policy for creating a new user notiification
     *
     * @return bool
     */
    public function create() {
      return auth()->user()->superuser;
    }

    public function update() {
      return auth()->user()->superuser;
    }

    public function delete() {
      return auth()->user()->superuser;
    }
}
