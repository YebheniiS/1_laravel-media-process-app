<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PlayerVersion extends Model
{
    //
    public static function getLatest(){
        return PlayerVersion::orderBy('created_at', 'desc')->first();
    }
}
