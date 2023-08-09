<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ImageElement extends Model
{
    protected $guarded = ['id'];
    protected $casts = [
        'animation' => 'json'
    ];
}
