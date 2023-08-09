<?php

namespace App;

use App\Scopes\TemplateScope;
use Illuminate\Database\Eloquent\Model;

class Template extends Model
{

    protected $table = 'projects';
    protected $fillable = ['title', 'description'];

    protected static function booted()
    {
        // This adds the ->where('is_template', 1) to
        // all queries
        static::addGlobalScope(new TemplateScope());
    }
}
