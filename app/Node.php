<?php

namespace App;

use App\Lib\ThumbnailGenerator;
use App\Scopes\UserScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Node extends Model
{

    protected $guarded = ['id'];

    protected static function booted()
    {
        // This adds the ->where('user_id', userId) to
        // all queries
        static::addGlobalScope(new UserScope());

        // This adds the user_id = authenticated user to
        // the model on creation
        static::creating(function ($node) {
            $node->user_id = auth()->user()->id;
        });
    }

    /**
     * The nodes relation with interactions
     * @return HasMany
     */
    public function interactions() : HasMany
    {
        return $this->hasMany(Interaction::class);
    }

    public function interactionsWithoutScope() : HasMany
    {
        return $this->hasMany(Interaction::class)->withoutGlobalScope(UserScope::class);
    }

    /**
     * This nodes element groups
     * @return HasMany
     */
    public function element_groups() : HasMany
    {
        return $this->hasMany(ElementGroup::class);
    }

    /**
     * The nodes relation to the media
     * @return BelongsTo
     */
    public function media() : BelongsTo
    {
        return $this->belongsTo(Media::class);
    }

    /**
     * The nodes relation to a project
     * @return BelongsTo
     */
    public function project() : BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * This isn't ideal as it returns all interactions if 1 === true instead of only the ones that
     * == true. May take another look at this later but for now we can easily handle the rest
     * on FE
     * @param $query
     * @param $id
     * @return mixed
     */
    public function scopeSurveys($query, $id)
    {
        return $query->where('project_id', $id)->whereHas('interactions', function(Builder $q){
            $q->whereHasMorph(
                'element',
                ['App\ButtonElement', 'App\ImageElement', 'App\HotspotElement'],
                function (Builder $q2) {
                    $q2->where('send_survey_click_event', 1);
                }
            );
        });
    }
}
