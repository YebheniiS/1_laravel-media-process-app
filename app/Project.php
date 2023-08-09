<?php

namespace App;

use App\Scopes\UserScope;
use App\Scopes\ModalScope;
use App\Services\ProjectPublisher;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class Project extends Model
{

    protected $guarded = ['id'];

    protected $casts = [
        'player_skin' => 'json',
        'share_data' => 'json',
        'chapter_items' => 'json'
    ];

    public const DEFAULT_SHARE_DATA = [
      "email" => [
            "use" => true,
            "text" => 'Email',
            "subject" => 'Interactive Video',
            "body" => '<a href="{share_page_url}">Click here</a> to checkout this cool Interactive Video'
        ],
        "facebook" => [
            "use" => true,
            "text" => 'Share'
        ],
        "linkedin" => [
            "use" => true,
            "text" => "Share",
            "title" => 'Checkout this cool Interactive Video',
        ],
        "twitter" => [
            "use" => true,
            "text" => "Tweet"
        ],
        "pintrest" => [
            "use" => true,
            "text" => 'Pin'
        ]
    ];

    public const DEFAULT_PLAYER_SKIN = [
        "bigPlay" => [
            "color" => "#fff",
            "size" => 7
        ],
        "controls" => [
            "background" => "rgba(0, 0, 0, 0.3)",
            "color" => "#ffffff",
        ],
        "unmute_text" => "Click to unmute",
    ];


    protected static function booted()
    {
        // This adds the ->where('user_id', userId) to
        // all queries
        static::addGlobalScope(new UserScope());

        // This adds the user_id = authenticated user to
        // the model on creation
        static::creating(function ($project) {
            $project->font = 'Quicksand';
            $project->user_id = auth()->user()->id;
            $project->share_data = self::DEFAULT_SHARE_DATA;
            $project->player_skin = self::DEFAULT_PLAYER_SKIN;
            $project->migration_status = 2;
        });

        // This gets all media items and gets items deleted
        static::deleting(function ($project) {
            // Unpublish project from cdn before deleting
            if($project->published_path)
                app()->make(ProjectPublisher::class)->unpublish($project->id);

            // Delete project all related comment items
            $project->comments()->delete();

            // Delete project all related media items
            $mediaDeleteIds = $project->media->pluck('id');

            if ($mediaDeleteIds->isNotEmpty()) {
                Media::destroy( $mediaDeleteIds );
            }
        });
    }

    /**
     * Query templates by removing the UserScope and only getting
     * models where template  = 1
     * @param $query
     * @param int $state
     * @return mixed
     */
    public function scopeTemplates($query, $state = 1)
    {
        // The is_template is hardcoded to 1 here or all projects could be listed by passing in 0
        return $query->where('is_template', 1)
                // Remove the global user_id scope so we can get templates from the template admin
                ->withoutGlobalScope(UserScope::class)
                // This is our template user, gives more protection from other things getting in here
                ->where('user_id', 2);
    }

    /**
     * return the query without the global user scope as the share page will
     *  be accessed by unauthenticated users
     * @param $query
     * @return mixed
     */
    public function scopeSharepage($query)
    {
        return $query->withoutGlobalScope(UserScope::class);
    }

    /**
     * Query otherVideos for share page by removing the UserScope and only getting
     * items where is_public = 1 and storage_path is not NULL
     * @param $query
     * @param int $state
     * @return mixed
     */
    public function scopeOtherVideos($query, $state = 1)
    {
        return $query
                ->withoutGlobalScope(UserScope::class)
                ->where('is_public', $state)
                ->whereNotNull('storage_path');
    }

    /**
     * Nodes model relation
     * @return HasMany
     */
    public function nodes() : HasMany
    {
      return $this->hasMany(Node::class);
    }

    /**
     * Nodes model relation without userScope
     * @return HasMany
     */
    public function nodesWithoutScope() : HasMany
    {
        return $this->hasMany(Node::class)->withoutGlobalScope(UserScope::class);
    }

    /**
     * Relation to the project group model
     * @return BelongsTo
     */
    public function group() : BelongsTo
    {
        return $this->belongsTo(ProjectGroup::class, 'project_group_id', 'id');
    }

    /**
     * Relation to User model
     * @return BelongsTo
     */
    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Project to comments relationship
     * @return HasMany
     */
    public function comments() : HasMany
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Project to modals relationship
     * @return HasMany
     */
    public function modals() : HasMany
    {
        return $this->hasMany(Modal::class);
    }

    public function modalsWithoutScope() : HasMany
    {
        return $this->hasMany(Modal::class)->withoutGlobalScope(ModalScope::class);
    }

    /**
     * Project to medias relationship
     * @return HasMany
     */
    public function media() : HasMany
    {
        return $this->hasMany(Media::class);
    }

    /**
     * Get a single project by storage path
     *
     * @param string $storagePath
     * @return \Illuminate\Database\Eloquent\Builder|Model|object|null
     */
    public static function findByStorageHash(string $storagePath)
    {
        return self::query()
                    ->with('comments')
                    ->where('storage_path', $storagePath)
                    ->first();
    }

    /**
     * Get project thumbnails based on relative media items
     * @return array
     */
    public function getProjectThumbnails()
    {
        $projectThumbs = [];

        // If project default thumbnail is valid, make first in thumbnails list
        if ($this->image_url) {
            $projectThumbs[] = $this->image_url;
        }

        $medias = $this->media()->get();

        foreach ($medias as $media) {
            // FE needs no more than 3 thumbnails for each project
            if (count($projectThumbs) < 4 && $media->thumbnail_url) {
                $projectThumbs[] = $media->thumbnail_url;
                continue;
            }

            break;
        }

        return $projectThumbs;
    }

    /** Changes the start node of the project
     * @param Node $oldNode
     * @return Node $newStartNode
     * @todo add a potential newNode id but for now, We abide by don't add code unless you definitely need it
     */
    public function changeStartNode($oldNode)
    {
        $nodes = $this->nodes;
        if (count($nodes) > 0) {
            $newStartNode = $nodes->first();
            
            $this->start_node_id = $newStartNode->id;
            // Switch the thumbnail as well if project used the old node's thumbnail
            if ($this->image_url == $oldNode->media->thumbnail_url) {
                $this->image_url = $newStartNode->media->thumbnail_url;
            }
        } else {
            $this->start_node_id = 0;
            $this->image_url = "";
        }
        $this->save();
        return $this->start_node_id;
    }

    /**
     * Projects as templates relationship with users who liked
     * @return BelongsToMany
     */
    public function templateLikes() : BelongsToMany
    {
        return $this
            ->belongsToMany(User::class, 'template_likes', 'template_id', 'user_id')
            ->withoutGlobalScope(UserScope::class)
            ->withTimestamps();
    }

    public function getTemplateLikesCount()
    {
        return $this->templateLikes()->count();
    }

    public function isAuthUserLike()
    {
        $authUserId = auth()->user()->id;
        return $this->templateLikes()->where('user_id', $authUserId)->exists();
    }

    public function getTemplateNodesCount()
    {
        return $this->nodesWithoutScope()->count();
    }

    public function getOtherVideos()
    {
        if(! $this->show_more_videos_on_share_page) return [];

        return Project::where('project_group_id', $this->project_group_id)->where('is_public', 1)->where('id', "!=", $this->id)->whereNotNull('published_at')->get();
    }

    public static function getShareUrl(Project $project)
    {
        $user = User::findOrFail($project->user_id);

        $hasAgency = $user->is_agency || $user->is_agency_club;

        $hash = str_replace("projects/", "", $project->storage_path);

        if(! $hasAgency) {
            return 'https://interactrapp.com/share/' . $hash;
        }

        $agency = Agency::where('user_id', $user->id)->first();

        if(! $agency || ! $agency->domain_verified) {
            return 'https://interactrapp.com/share/' . $hash;
        }

        return 'https://' . $agency->domain . '/share/' . $hash;
    }
    
    public function templatesUsed()
    {
      return $this->hasMany(TemplatesUsed::class);
    }

    public function projectNotes()
    {
        return $this->hasOne(ProjectNote::class);
    }
}
