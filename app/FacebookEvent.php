<?php

namespace App;

use App\Scopes\UserScope;
use Illuminate\Database\Eloquent\Model;

class FacebookEvent extends Model
{
    //
    protected $guarded = [
        'id'
    ];

    public static function booted()
    {
        static::addGlobalScope(new UserScope());

        // This adds the user_id = authenticated user to
        // the model on creation
        static::creating(function ($model) {
            $model->user_id = ( auth()->user()->parent_user_id ) ? auth()->user()->parent_user_id : auth()->user()->id;
        });
    }
}
