<?php

namespace App\GraphQL\Mutations;

use App\Media;
use App\Repositories\MediaRepository;
use App\Project;

class MediaResolver
{
    public function create($rootValue, array $args, $context, $resolveInfo)
    {
        $repo = app()->make(MediaRepository::class);
        return $repo->create($args);
    }

    /**
     * Copy a media for new project
     *
     * @param $rootValue
     * @param array $args
     * @param $context
     * @param $resolveInfo
     * @return Media
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function copyToProject($rootValue, array $args, $context, $resolveInfo) : Media
    {
        $repo = app()->make(MediaRepository::class);

        $mediaId = $args["mediaId"];
        $projectId = $args["projectId"];

        $newMedia = $repo->copyToProject($mediaId, $projectId);

        return $newMedia;
    }


    /**
     * @param $rootValue
     * @param array $args
     * @param $context
     * @param $resolveInfo
     * @return mixed
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function getMedias($rootValue, array $args, $context, $resolveInfo)
    {
        $repo = app()->make(MediaRepository::class);
        $orderBy = '';
        foreach ($args['orderBy'] as $arg) {
            $orderBy .= " " . $arg['column'] . " " . $arg['order'] . ",";
        }
        $orderBy = trim($orderBy, ',');

        return $repo->sortMedias($orderBy, $args);
    }
}
