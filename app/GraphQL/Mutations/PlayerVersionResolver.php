<?php

namespace App\GraphQL\Mutations;

use App\PlayerVersion;
use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Support\Facades\Auth;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class PlayerVersionResolver
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
    public function getLatestPlayerVersion($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        return PlayerVersion::getLatest();
    }
}
