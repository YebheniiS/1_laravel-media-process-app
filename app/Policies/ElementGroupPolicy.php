<?php

namespace App\Policies;

use App\ElementGroup;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Auth;

class ElementGroupPolicy
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

    public function destroy(User $user, ElementGroup $elementGroup)
    {
        $elementGroup->load('node');
        return ($elementGroup->node->user_id===Auth::user()->id);
    }
}
