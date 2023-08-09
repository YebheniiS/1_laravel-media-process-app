<?php

namespace App;

use App\Scopes\UserScope;
use Illuminate\Database\Eloquent\Model;

class ProjectGroup extends Model
{
    protected $fillable = [
        'title', 'sort_order'
    ];

    protected static function booted()
    {
        // This adds the ->where('user_id', userId) to
        // all queries
        static::addGlobalScope(new UserScope());

        // This adds the user_id = authenticated user to
        // the model on creation
        static::creating(function ($ProjectGroup) {
            $ProjectGroup->user_id = auth()->user()->id;
            $ProjectGroup->sort_order = self::setSortOrderNumber();
        });

        static::deleting(function ($projectGroup) {
            // $projectGroup->projects()->update(['project_group_id' => null]);
            $projectGroup->allprojects()->update(['project_group_id' => null]);
        });
    }

    /**
     * Get project group projects count
     * @return int
     */
    public function getProjectsCount()
    {
        return $this->projects()->count();
    }

    /**
     * Get project group project ID's
     * @return \Illuminate\Support\Collection
     */
    public function getProjectIds()
    {
        return $this->projects()->select('id')->pluck('id');

    }

    /**
     * Relation to the project group model
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function projects()
    {
        return $this->hasMany('App\Project', 'project_group_id')->where('migration_status', 2); // 2 means migration is done 
    }

    public function allprojects()
    {
        return $this->hasMany('App\Project', 'project_group_id');
    }

    /**
     * Get auth user project groups count
     * Used for setting the last sort number on create new project group action
     * @return int
     */
    public static function setSortOrderNumber()
    {
        $count = self::query()->count();
        return ++$count;
    }

}