<?php


namespace App\Scopes;


use App\Project;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class ModalScope implements Scope
{
    /**
     * Scope the model to authenticated user OR it's parent
     *
     * @param Builder $builder
     * @param Model $model
     */
    public function apply(Builder $builder, Model $model)
    {
        if(auth()->check()) {
            /**
             * Project::all() gets only auth user projects as it uses UserScope:class inside Project model
             */
            $userProjectIds = Project::all()->pluck('id');

            /**
             * Get only auth user related modals
             */
            $builder->whereIn('project_id', $userProjectIds);
        }
    }

}