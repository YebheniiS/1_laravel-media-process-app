<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgencyClubDfyContent extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'landing_pages' => 'json',
        'projects' => 'json',
    ];
}
