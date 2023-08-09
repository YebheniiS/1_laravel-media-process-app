<?php

namespace App\Policies;

use App\ProjectGroup;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProjectGroupPolicy
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
     * Handle when the project group can be created
     *
     * @param User $user
     * @return bool
     */
    public function create(User $user)
    {
        // Sub users can't create folders
        if($user->parent_user_id) return false;

        return  true;
    }

    /**
     * Handle when the project group can be updated
     *
     * @param User $user
     * @param ProjectGroup $projectGroup
     * @return bool
     */
    public function update(User $user, ProjectGroup $projectGroup)
    {
        return $user->id === $projectGroup->user_id;
    }

    /**
     * Define when a user can be destroyed
     *
     * @param User $user
     * @param ProjectGroup $projectGroup
     * @return bool
     */
    public function destroy(User $user, ProjectGroup $projectGroup)
    {
        return $user->id === $projectGroup->user_id;
    }
}
