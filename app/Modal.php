<?php

namespace App;

use App\Scopes\ModalScope;
use App\Scopes\UserScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

use function PHPUnit\Framework\isNull;

class Modal extends Model
{
    protected $guarded = ['id'];
    protected $casts = [
        'background_animation' => 'json'
    ];

    protected static function booted()
    {
        /**
         * This adds the ->whereIn('project_id', $projectIds) to
         * all queries where $projectIds are belonging to the auth user
         */
        static::addGlobalScope(new ModalScope());

        static::deleting(function ($modal) {
          // Delete all modal related element items
          $interactions = $modal->project->nodes->map(function ($node)
          {
            return $node->interactions;
          });

          $interactions->each(function ($interaction) use ($modal)
          {
            if($interaction->count()) {
              $interaction->each(function ($item) use ($modal)
              {
                if ($item->element->actionArg == $modal->id) {
                  $item->element->clickAction = null;
                  $item->element->clickActionArg = null;
                }
              });
            }
          });
      });
    }

    public function elements() : HasMany
    {
        return $this->hasMany(ModalElement::class);
    }

    public function project() : BelongsTo
    {
        return $this->belongsTo(Project::class)->withoutGlobalScope(UserScope::class);
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
            ->withoutGlobalScope(ModalScope::class);
            // This is our template user, gives more protection from other things getting in here
            //->where('user_id', 2);
    }
}
