<?php

namespace App\Policies;

use App\Node;
use App\Project;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class NodePolicy
{
    use HandlesAuthorization;

    /**
     * Determine if the given post can be updated by the user.
     * @param User $user
     * @param Node $node
     * @return bool
     */
    public function update(User $user, Node $node) : Bool
    {
        return $this->check($user, $node);
    }

    /**
     * Abstract the basic check into it's own method we will need to reuse this.
     * @param $user
     * @param $node
     * @return bool
     */
    protected function check($user, $node) : Bool
    {
        return (
            $node->user_id === $user->id ||
            $node->user_id === $user->parent_user_id
        );
    }

    /**
     * Determine if the given post can be viewed by the user.
     * @param User $user
     * @param Node $node
     * @return bool
     */
    public function view(User $user, Node $node) : Bool
    {
        return $this->check($user, $node);
    }

    /**
     * Can user delete the node
     * @param User $user
     * @param Node $node
     * @return bool
     */
    public function destroy(User $user, Node $node) : Bool
    {
        return ( $user->id === $node->user_id );
    }
}
