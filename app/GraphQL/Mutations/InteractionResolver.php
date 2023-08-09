<?php

namespace App\GraphQL\Mutations;

use App\Repositories\InteractionRepository;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class InteractionResolver
{
    /**
     * Create an interaction
     * @param $rootValue
     * @param array $args
     */
    public function create($rootValue, array $args)
    {
        $repo = app()->make(InteractionRepository::class);

        return $repo->create($args);
    }

    /**
     * Copy an interaction
     * @param $rootValue
     * @param array $args
     * @return mixed
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function copy($rootValue, array $args)
    {
        $repo = app()->make(InteractionRepository::class);
        $interaction = $repo->duplicate( $args['id'] );

        return $interaction;
    }
}
