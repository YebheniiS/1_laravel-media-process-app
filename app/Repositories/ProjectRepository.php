<?php

namespace App\Repositories;

use App\Lib\ThumbnailGenerator;
use App\Media;
use App\Modal;
use App\Node;
use App\Project;
use App\Scopes\UserScope;
use App\Services\ProjectCopier;
use App\Models\User;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Schema;

class ProjectRepository
{
    protected $model;
    protected $project;

    public function __construct()
    {
        $this->model = new Project();
    }

    /**
     * @param $id
     * @param bool $disableAuth
     * @return Project
     */
    public function getProject($id, $disableAuth = false)
    {
        $project = $this->model->where('id', $id)->with('comments');

        if ($disableAuth) $project = $project->withoutGlobalScope(UserScope::class);

        return $project->first();
    }

    public function saveProject($project)
    {
        if (isset($project['modals'])) {
            unset($project['modals']);
        }

        if (isset($project['comments'])) {
            unset($project['comments']);
        }


        // If no start node clear the image
        if (isset($project['start_node_id']) && $project['start_node_id'] === 0) {
            $project['image_url'] = '';
            $project['facebook_image_url'] = '';
            $project['google_image_url'] = '';
            $project['twitter_image_url'] = '';
        }

        $oldProject = Project::findOrFail($project['id']);

        if (!empty($project['image_url']) && $project['image_url'] !== $oldProject->image_url) {
            $project = array_merge($project, $this->generateSocialThumbnails($project['image_url']));
        }

        // If the start node has changed we need to update the thumbnail
        if (isset($project['start_node_id'])) {
            if ($oldProject->start_node_id !== $project['start_node_id']) {

                $node = Node::where('id', $project['start_node_id'])->with('media')->first();
                if ($node && $node->media && !empty($node->media->thumbnail_url)) {
                    $project['image_url'] = $node->media->thumbnail_url;
                    $project = array_merge($project, $this->generateSocialThumbnails($node->media->thumbnail_url));
                }
            }
        }

        if ($oldProject) {
            foreach ($project as $key => $value) {
                if (Schema::hasColumn($this->project->getTable(), $key)) {
                    // Be aware the ugliest thing you've ever seen ahead !
                    if (!is_null($value) || $this->filterNullableColumns($key)) {
                        $oldProject->{$key} = $value;
                    }
                };
            }

            $oldProject->save();

            return $oldProject;
        }

        return $this->project->create($project);
    }

    public function filterNullableColumns($column)
    {
        switch ($column) {
            case 'published_at':
            case 'description':
            case 'project_group_id':
            case 'downloadable_assets':
            case 'template_name':
                return true;
            default:
                return false;
        }
    }

    /**
     * Get the project and its relationships for the player
     *
     * @param $id
     * @param bool $disableAuth
     * @return Project|array
     */
    public function getPlayerData($id, $disableAuth = false)
    {
        if($disableAuth) {
            $project = Project::withoutGlobalScope(UserScope::class)->where('id', $id)->first();
        }else {
            $project = Project::findOrFail($id);
        }

        // Disable this as we have no legacy projects now
//        if ($project->legacy) {
//            $data = $this->getProject($id, $disableAuth)->with('nodes.interactions.element', 'nodes.media', 'modals.elements.element')->get()[0];
//            return $this->addProjectOwnerSettings($data);
//        }

        $nodes = Node::where('project_id', $project->id)->with('media', 'interactions.element', 'element_groups');
        if($disableAuth) {
            $nodes = $nodes->withoutGlobalScope(UserScope::class);
        }
        $nodes = $nodes->get();

        $modals = Modal::where('project_id', $project->id)->with('elements.element');
        if($disableAuth) {
            $modals = $modals->withoutGlobalScope(UserScope::class);
        }
        $modals = $modals->get();

        $videos = $nodes->pluck('media');

        $sharePageUrl = Project::getShareUrl($project);

        return [
            $nodes,
            $modals,
            $videos,
            $project,
            $sharePageUrl
        ];

    }

    /**
     * Receives a project id and returns if the project can be
     * published or not
     *
     * @param $id
     * @return bool
     */
    public function publishingCheck($id)
    {
        $project = Project::findOrFail($id);

        if (!$project->legacy) {
            $failedEncodedVideos = Media::where('project_id', $project->id)->whereIn('manifest_url', [null, ''])->get();

            return $failedEncodedVideos->isEmpty();
        }

        return true;
    }

    /**
     * Gets any user settings that are relevant during project playing and appends them to the project passed to it
     *
     * @param Project
     * @return Project
     */
    private function addProjectOwnerSettings($project)
    {
        $projectOwner = User::findOrFail($project->user_id);

        $settings = [];

        if ($projectOwner->show_gdpr) {
            $project->show_gdpr = $projectOwner->show_gdpr;
            $project->gdpr_text = $projectOwner->gdpr_text;
            $project->privacy_policy_url = $projectOwner->privacy_policy_url;
            // $project->privacy_policy_text = $projectOwner->privacy_policy_text
        }

        return $project;
    }

    /**
     * Get the raw  s3 path from a url that points to the CDN
     *
     * @param $url
     * @return bool|string|string[]
     */
    protected function getS3KeyForUrl($url)
    {
        if (strpos($url, "cdn6.swiftcdn.co") === false) {
            return false;
        }

        $urlParts = parse_url($url);
        $path = $urlParts['path'];
        return str_replace("/cdn6.swiftcdn.co/", "", $path);
    }

    public function generateSocialThumbnails($url)
    {
        
        $tg = new ThumbnailGenerator($url);
        
        return $tg->generateSocialThumbs($url);
    }

    /**
     * Copy a project and all it's relations
     *
     * @param $id
     * @param null $groupId
     * @param bool $title
     * @param bool $description
     * @param bool $copyFromTemplate
     * @return mixed
     * @throws BindingResolutionException
     */
    public function copyProject($id, $groupId = null, $title = false, $description = "", bool $copyFromTemplate = false, $userId=null)
    {
        $copier = app()->make(ProjectCopier::class);
        return $copier->copy($id, $groupId, $title, $description, $copyFromTemplate, $userId);
    }

    public function createExampleProject(User $user)
    {
        $project = $this->copyProject(4, null, "Example Project", "", true, $user->id);
        $project->user_id = $user->id;
        $project->save();
    }

    public function publish($id)
    {
        $publishingValidated = $this->publishingCheck($id);

        if ($publishingValidated) {
            return $this->publish($id);
        }

        throw new \Exception('Some videos are still encoding and the project can\'t be published until all videos are encoded.');
    }

    /**
     * remove template language from templates in case of removing languages
     * @param $templates
     * @throws \Exception
     */
    public static function makeTemplateLanguagesNullable($templates)
    {
        try {
            foreach ($templates as $template) {
                $template->update(['language_id' => null]);
            }
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * @param $projectId
     * @return array
     * @throws \Exception
     */
    public function regenerateSocialThumbnails($projectId)
    {
     
        $project = Project::query()->find($projectId);
        $thumbnail = $project->image_url;
     

        if (! $thumbnail) {
            throw new \Exception('No project thumbnail to regenerate.');
        }
        
        $socialThumbs = $this->generateSocialThumbnails($thumbnail);
        var_dump($socialThumbs);
        
        foreach ($socialThumbs as $key => $value) {
            $project->$key = $value;
        }
        $project->save();

        return [
            'projectId' => $project->id,
            'google_image_url' => $project->google_image_url,
            'facebook_image_url' => $project->facebook_image_url,
            'twitter_image_url' => $project->twitter_image_url
        ];
    }

    /**
     * @param $orderBy
     * @param $search
     * @param $groupId
     * @return mixed
     */
    public function sortProjects($orderBy, $search, $groupId, $app)
    {
        // var_dump($groupId);
        if($groupId < 0) {
            // return Project::whereIn('migration_status', [100, 111])
            return Project::whereIn('migration_status', [0, 1])
            ->where('app', $app)
            ->where(function($q) use ($search) {
                $q->where('title', 'LIKE', '%'. $search .'%')
                    ->orWhere('description', 'LIKE', '%' . $search . '%');
            })
            ->orderByRaw($orderBy);
        }
        return Project::where('project_group_id', $groupId)
            ->where('migration_status', 2) // 2 is "migrated"
            ->where('app', $app)
            ->where(function($q) use ($search) {
                $q->where('title', 'LIKE', '%'. $search .'%')
                    ->orWhere('description', 'LIKE', '%' . $search . '%');
            })
            ->orderByRaw($orderBy);
    }

    public function sortProjectsByProjectIds($orderBy, $search, $projectIds, $app)
    {
        
        return Project::whereIn('id', $projectIds)
            ->where('app', $app)
            ->where('migration_status', 2)
            ->where(function($q) use ($search) {
                $q->where('title', 'LIKE', '%'. $search .'%')
                    ->orWhere('description', 'LIKE', '%' . $search . '%');
            })
            ->orderByRaw($orderBy);
    }

    /**
     * @param $search
     * @return mixed
     */
    public function getTemplates($args)
    {
        $query =  Project::where('is_template', 1)
            ->withoutGlobalScope(UserScope::class)
            ->where('user_id', 2);

        $search = $args['search'];

        if($search){
            $query->where(function ($q) use ($search) {
                $q->where('title', 'LIKE', '%' . $search . '%')
                    ->orWhere('description', 'LIKE', '%' . $search . '%')
                    ->orWhere('template_name', 'LIKE', '%' . $search . '%');
            });
        }

        if(isset($args['template_is_example'])) {
            $query->where("template_is_example",  $args['template_is_example']);
        }

        if(isset($args['template_is_dfy'])) {
            $query->where("template_is_dfy", $args['template_is_dfy']);
        }

        return $query;
    }

    /**
     * @param $projectId
     * @param $userId
     * @return Project
     */
    public function likeTemplate($projectId, $userId) : Project
    {
        $project = Project::templates()->find($projectId);
        $project->templateLikes()->toggle($userId);

        return $project->fresh();
    }
}
