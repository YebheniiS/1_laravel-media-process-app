<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StreamConversionLog extends Model
{
    //
    protected $casts = [
        'message' => 'json'
    ];
}
