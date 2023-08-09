<?php

namespace App;

use App\Repositories\QencodeTaskRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

class QencodeTask extends Model
{
    //
    protected $fillable = ['token', 'status', 'media_id', 'failed', 'error_description', 'stock_video_id', 'api_key', 'profile_key', 'percent'];

    protected $casts = ['error' => 'json'];

    public static function booted()
    {
        static::created(function(QencodeTask $task){
            $repo = app()->make(QencodeTaskRepository::class);
            $repo->encodeTask($task);
        });
    }


    public static function findByToken($token)
    {
        return self::where('token', $token)->first();
    }

    public static function findByMediaID($mediaId)
    {
        return self::where('media_id', $mediaId)->first();
    }

    public static function findByStockID($stockVideoID)
    {
        return self::where('stock_video_id', $stockVideoID)->first();
    }

    public static function deleteOldTask($mediaId)
    {
        return self::where('media_id', $mediaId)->delete();
    }
}
