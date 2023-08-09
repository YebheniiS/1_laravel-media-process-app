<?php

namespace App\GraphQL\Mutations;

use App\Repositories\AuthRepository;
use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Support\Arr;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class AuthResolver
{
    /**
     * Authenticate a user
     *
     * @param $rootValue
     * @param array $args
     * @param GraphQLContext $context
     * @param ResolveInfo $resolveInfo
     * @return mixed
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function login($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $repo = app()->make(AuthRepository::class);
        $credentials = Arr::only($args, ['email', 'password']);

        return $repo->login($credentials);
    }

    /**
     * Re-authenticate logged in user
     *
     * @return mixed
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function reauthenticate()
    {
        $repo = app()->make(AuthRepository::class);

        return $repo->reauthenticate();
    }

    /**
     * Logout logged in user
     *
     * @return mixed
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function logout()
    {
        $repo = app()->make(AuthRepository::class);

        return $repo->logout();
    }

    /**
     *
     * @param $rootValue
     * @param array $args
     * @param GraphQLContext $context
     * @param ResolveInfo $resolveInfo
     * @return mixed
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function updateForgottenPassword($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $repo = app()->make(AuthRepository::class);
        $email = $args['email'] ?? null;

        return $repo->sendResetLinkEmail($email);
    }

    public function resetPassword($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo) : bool
    {
        $repo = app()->make(AuthRepository::class);

        return $repo->resetPassword($args);
    }
}
