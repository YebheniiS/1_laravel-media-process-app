<?php

namespace App\GraphQL\Mutations;

use App\Repositories\InteractionRepository;
use App\Repositories\NodeRepository;

class NodeResolver
{
    /**
     * Copy a node
     * @param $rootValue
     * @param array $args
     * @param $context
     * @param $resolveInfo
     * @return mixed
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function copyNode( $rootValue, array $args, $context, $resolveInfo )
    {
        $repo = app()->make(NodeRepository::class);
        $nodeId = $args["id"];
        $projectId = $args["project_id"];
        $name = $args["name"] ?? null;
        $posX = $args["posX"] ?? null;
        $posY = $args["posY"] ?? null;
        $node = $repo->copyNode( $nodeId, $projectId, name: $name, posX: $posX, posY: $posY );
        $interactionRepository = app()->make(InteractionRepository::class);
        $interactionRepository->copyAllFromNode($nodeId, $node->id);
        return $node;
    }

    /**
     * Sort nodes collection by sort_order column
     * @param $rootValue
     * @param array $args
     * @param $context
     * @param $resolverInfo
     * @return mixed
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function sortNodes( $rootValue, array $args, $context, $resolverInfo )
    {
        $repo = app()->make(NodeRepository::class);
        $response = $repo->sortNodes($args['nodes']);

        return $response;
    }
}
