<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
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
     * Define policy for listing all users
     *
     * @param User $user
     * @return mixed
     */
    public function viewAllUsers(User $user){
        return ( auth()->user()->superuser );
    }


    /**
     * Define policy for creating a new user
     *
     * @param User $user
     * @return bool
     */
    public function create(User $user){
        return ( auth()->user()->superuser || auth()->user()->is_agency );
    }


    /**
     * Define policy for updating a user
     *
     * @param User $user
     * @return bool
     */
    public function update(User $user){
        return auth()->user()->id === $user->id ;
    }

    /**
     * Define policy for updating a subUser
     * @param User $user
     * @return bool
     */
    public function updateSubUser(User $user, $args){
        $subUser = User::query()->findOrFail($args['id']);
        return auth()->user()->id === $subUser->parent_user_id;
    }

    /**
     * Define policy for deleting a user
     *
     * @param User $user
     * @param $args
     * @return bool
     */
    public function delete(User $user, $args)
    {
        /**
         * Allow delete user if auth is superuser.
         * Either allow to delete if given user is sub user of current auth
         */
        $isSuperUser = auth()->user()->superuser;
        $subUser = User::query()->findOrFail($args['id']);
        $isSubUser = auth()->id() === $subUser->parent_user_id;

        return $isSuperUser || $isSubUser;
    }

    /**
     * Does user exist with given @param $args['email']
     * @param User $user
     * @param $args
     * @return bool|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object|null
     */
    public function passwordForgot(User $user, $args)
    {
        if (isset($args['email'])) {
            $doesUserExist =  User::query()->where('email', $args['email'])->first();

            return $doesUserExist;
        }

        return false;
    }

    /**
     * Check user is agency or not
     *
     * @param User $user
     * @return bool
     */
    public function authUserCanUpdateSubUser(User $user, $args)
    {
        if(! auth()->user()->is_agency && ! auth()->user()->is_agency_club) {
            return false;
        }

        $subUser = User::findOrFail($args['id']);

        return (auth()->user()->id == $subUser->parent_user_id);
    }
}
