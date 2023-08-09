<?php

namespace App\Policies;

use App\Media;
use App\Project;
use App\Repositories\NodeRepository;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class MediaPolicy
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
     * Does the user have permission to create a media
     *
     * @param User $user
     * @return bool
     */
    public function create(User $user) : Bool
    {
        // Is the user a sub user?
        if($user->parent_user_id) return false;

        return true;
    }

    /**
     * Can authenticated user update the media. The global scope should only return
     * projects that can be updated but this is an added layer of auth
     * @param User $user
     * @param Media $media
     * @return bool
     */
    public function update(User $user, Media $media) : Bool
    {
        // Does the user own the media
        if( $user->id === $media->user_id ) return true;

        // Does the users parent own the media
        if( $user->parent_user_id === $media->user_id ) return true;

        // If we get here user does't have permission
        return false;
    }

    /**
     * Can authenticated user delete the media
     * @param User $user
     * @param Media $media
     * @return bool
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function destroy(User $user, Media $media) : Bool
    {
        $nodeRepository = app()->make(NodeRepository::class);
        $isInUse = $nodeRepository->mediaInUse($media->id);

        if ($isInUse) {
            throw new \Exception('Can\'t delete media item. It is in use for other nodes.');
        }

        return ( $user->id === $media->user_id );
    }
}
