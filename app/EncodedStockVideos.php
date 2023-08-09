<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EncodedStockVideos extends Model
{
    protected $guarded = [];

    public static function findByStockVideoID($stockVideoID)
    {
        return self::where('stock_video_id', $stockVideoID)->first();
    }
}
