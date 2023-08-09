<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UsagePlans extends Model
{
    protected $table = 'usage_plans';
    protected $guarded = ['id'];

    public $timestamps = false;
}
