<?php


namespace App\Scopes;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class MediaScope implements Scope
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
            $parentUser = auth()->user()->parent_user_id;

            $builder
                ->where('user_id', auth()->user()->id)
                ->orWhere('user_id', $parentUser);

            /**
             * If sub user, use project ID's can access to
             */
            if ($parentUser) {
                $builder->whereIn('project_id', auth()->user()->projects);
            }
        }
    }

}