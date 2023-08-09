<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JvzooProduct extends Model
{
    use HasFactory;

    public function access() : belongsTo
    {
        return $this->belongsTo('App\Models\AccessLevel', 'access_level_id');
    }
}
