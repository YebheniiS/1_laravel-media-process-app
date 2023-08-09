<?php

namespace App\GraphQL\Mutations;

use App\Repositories\ModalRepository;
use Illuminate\Contracts\Container\BindingResolutionException;

class ModalResolver
{
    /**
     * Copy a new modal from existing
     *
     * @param $rootValue
     * @param array $args
     * @param $context
     * @param $resolveInfo
     * @return mixed
     * @throws BindingResolutionException
     */
    public function copyModal( $rootValue, array $args, $context, $resolveInfo )
    {
        $repo = app()->make(ModalRepository::class);
        $modalId = $args["modalId"];
        $projectId = $args["project_id"];
        $name = $args["name"];

        $newModal = $repo->copyModal( $modalId, $projectId, $name );

        return $newModal;
    }

    public function copyModalElement( $rootValue, array $args, $context, $resolveInfo )
    {
        $repo = app()->make(ModalRepository::class);

        $modalElement = $repo->getModalElement($args["id"]);
        $newModalElement = $repo->copyModalElement($modalElement);

        return $newModalElement;
    }

    public function applyTemplate( $rootValue, array $args, $context, $resolveInfo )
    {
        $repo = app()->make(ModalRepository::class);
        $templateId = $args["templateId"];
        $id = $args["modalId"];


        $newModal = $repo->applyTemplate( $templateId, $id);

        return $newModal;
    }
}