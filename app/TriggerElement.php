<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TriggerElement extends Model
{
    protected $guarded = ['id'];

    protected static function booted()
    {
        // Delete trigger element related modal items
        static::deleting(function ($element) {
            // Not sure this should be the desired outcome when deleting a trigger
//            $modal = Modal::query()->find($element->actionArg);
//            if($modal) $modal->delete();
        });
    }

}
