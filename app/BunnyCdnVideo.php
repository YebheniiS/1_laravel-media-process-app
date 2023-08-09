<?php

namespace App;

use App\Lib\PushNotification;
use Illuminate\Database\Eloquent\Model;

class BunnyCdnVideo extends Model
{
    //
    protected $guarded = ['id'];

    public static function syncUpdateWithFe(BunnyCdnVideo $bunnyCdnVideo, $newData)
    {
        $bunnyCdnVideo->update($newData);

        $pusher = new PushNotification();
        $pusher->channel('bunny_cdn_video')->event('update')->push([
            'id' => $bunnyCdnVideo->id,
            'data' => $newData
        ]);
    }

    public function media()
    {
        return $this->hasOne(Media::class, "id", "media_id");
    }
}
