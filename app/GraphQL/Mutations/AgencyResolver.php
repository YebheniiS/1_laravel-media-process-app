<?php

namespace App\GraphQL\Mutations;

use App\Agency;
use App\Repositories\ProjectRepository;
use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Support\Facades\Auth;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class AgencyResolver
{
    /**
     * Check if the current user already has an agency set up if not create one
     *
     * @param $rootValue
     * @param array $args
     * @param GraphQLContext $context
     * @param ResolveInfo $resolveInfo
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model
     */
    public function firstOrCreate($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $auth = auth()->user();

        return Agency::query()->firstOrCreate([
                'user_id' => $auth->id
            ]);
    }

    public function getWhitelabel($rootValue, array $args, $context, $resolveInfo ){
        return \App\Agency::whitelabel($args['domain']);
    }
}
