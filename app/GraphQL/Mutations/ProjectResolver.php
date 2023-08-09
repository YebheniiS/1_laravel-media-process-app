<?php

namespace App\GraphQL\Mutations;

use App\Project;
use App\TemplatesUsed;
use App\Repositories\ProjectRepository;
use App\Services\ProjectPublisher;
use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Contracts\Container\BindingResolutionException;


class ProjectResolver
{
    /**
     * Publish a project
     *
     * @param $rootValue
     * @param array $args
     * @param $context
     * @param $resolveInfo
     * @return Project
     * @throws BindingResolutionException
     */
    public function publish($rootValue, array $args, $context, $resolveInfo)
    {
        $publisher = app()->make(ProjectPublisher::class);
        $project = $publisher->publish( $args["id"] );

        return $project;
    }

    /**
     * Un-publish the project
     *
     * @param $rootValue
     * @param array $args
     * @param $context
     * @param $resolveInfo
     * @return Project
     * @throws BindingResolutionException
     */
    public function unPublish($rootValue, array $args, $context, $resolveInfo)
    {
        $publisher = app()->make(ProjectPublisher::class);
        $project = $publisher->unPublish( $args["id"] );

        return $project;
    }

    /**
     * Copy a new project from existing
     *
     * @param $rootValue
     * @param array $args
     * @param $context
     * @param $resolveInfo
     * @return mixed
     * @throws BindingResolutionException
     */
    public function copyProject( $rootValue, array $args, $context, $resolveInfo )
    {
        $repo = app()->make(ProjectRepository::class);
        $title = $args["title"] ?? "";
        $description = $args["description"] ?? "";
        $folderId = $args['folderId'] ?? null;
        $projectId = $args["projectId"];
        $copyFromTemplate = $args["copyFromTemplate"] ?? false;


        $newProject = $repo->copyProject( $projectId, $folderId, $title, $description, $copyFromTemplate );

        return $newProject;
    }

    /**
     * Create a new project from template
     * @param $rootValue
     * @param array $args
     * @param $context
     * @param $resolveInfo
     * @return mixed
     * @throws BindingResolutionException
     */
    public function createTemplateProject( $rootValue, array $args, $context, $resolveInfo )
    {
        $repo = app()->make(ProjectRepository::class);
        $title = $args["title"] ?? "";
        $description = $args["description"] ?? "";
        $folderId = $args["project_group_id"] ?? null;

        $templateId = $args["templateId"];
        $copyFromTemplate = true;

        $newProject = $repo->copyProject( $templateId, $folderId, $title, $description, $copyFromTemplate);

        TemplatesUsed::create([
            'user_id' => auth()->user()->id,
            'project_id' => $templateId
        ]);

        return $newProject;
    }

    /**
     * Regenerate project social thumbnails
     * @param $rootValue
     * @param array $args
     * @param $context
     * @param $resolveInfo
     * @return mixed
     * @throws BindingResolutionException
     */
    public function regenerateSocialThumbnails( $rootValue, array $args, $context, $resolveInfo )
    {
        $repo = app()->make(ProjectRepository::class);
        $projectId = $args['id'];

        $regenerateSocialThumbnails = $repo->regenerateSocialThumbnails($projectId);

        return $regenerateSocialThumbnails;
    }

    /**
     * Sort projects and search by description, title
     * @param $rootValue
     * @param array $args
     * @param $context
     * @param $resolveInfo
     * @return mixed
     * @throws BindingResolutionException
     */
    public function getProjects( $rootValue, array $args, $context, $resolveInfo )
    {
        $repo = app()->make(ProjectRepository::class);
        $orderBy = '';
        foreach ($args['orderBy'] as $arg) {
            $orderBy .= " " . $arg['column'] . " " . $arg['order'] . ",";
        }
        $orderBy = trim($orderBy, ',');
        
        
        
        if(auth()->user()->parent_user_id > 0) {
            $projectIds = auth()->user()->projects;
            return $repo->sortProjectsByProjectIds($orderBy, $args['search'], $projectIds, $args['app']);
        }
        
        $sortedProjects = $repo->sortProjects($orderBy, $args['search'], $args['project_group_id'], $args['app']);

        return $sortedProjects;
    }

    public function getTemplates( $rootValue, array $args, $context, $resolveInfo )
    {
        $repo = app()->make(ProjectRepository::class);

        return $repo->getTemplates($args);
    }

    public function likeTemplate( $rootValue, array $args, $context, $resolveInfo )
    {
        $repo = app()->make(ProjectRepository::class);

        return $repo->likeTemplate($args['id'], auth()->user()->id);
    }
}
