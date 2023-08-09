<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ElementGroup extends Model
{
    protected $table = 'element_groups';
    protected $guarded = [];

    public function interactions()
    {
        return $this->hasMany('App\Interaction');
    }

    public function node()
    {
        return $this->belongsTo('App\Node');
    }
}
