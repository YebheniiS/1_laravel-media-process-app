<?php

namespace App\Policies;

use App\Helper\PermissionHelper;
use App\Interaction;
use App\Node;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class InteractionPolicy
{
    use HandlesAuthorization;

    /**
     * Create an interaction
     *
     * @param User $user
     * @param $args
     * @return bool
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function create(User $user, $args)
    {
        $permissionHelper = app()->make(PermissionHelper::class);

        $permissionHelper->checkNodePermissions($args['node_id']);

        $element_type = $args['element_type'];

        if (!in_array($element_type, (new Interaction())->allowedElementTypes)) {
            throw new \Error('element_type disallowed by interaction');
        }

        return true;
    }

    /**
     * Update an interaction
     *
     * @param User $user
     * @param $interaction
     * @return bool
     */
    public function update(User $user, Interaction $interaction) : Bool
    {
        return ( $user->id === $interaction->user_id );
    }

    /**
     * Delete an interaction
     *
     * @param User $user
     * @param Interaction $interaction
     * @return mixed
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function destroy(User $user, Interaction $interaction) : bool
    {
        $permissionHelper = app()->make(PermissionHelper::class);

        return $permissionHelper->checkInteractionPermissions($interaction->id);
    }
}
