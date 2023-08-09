<?php

namespace App\Http\Controllers;

use App\Jobs\EncodeVideo;
use App\Jobs\ReencodeVideo;
use App\BunnyCdnVideo;
use App\Media;
use App\Repositories\BunnyCdnVideoRepository;
use App\Services\BunnyCDN\BunnyStreamAdapter;
use Illuminate\Http\Request;

class BunnyCdnVideoController extends Controller
{
    //
    protected $repo;

    public function __construct(BunnyCdnVideoRepository  $repo)
    {
        $this->repo = $repo;
    }

    public function webhook()
    {
        app('honeybadger')->notify("Webhook Request", app('request'));

        $bunnyCdnVideo = BunnyCdnVideo::where('bunny_cdn_video_id', request()->VideoGuid);

        if(! $bunnyCdnVideo){
            // TODO throw error to honeybadger here
            return;
        }

        // Check we don't move the status backwards. When testing the webhook sometimes
        // the complete status came in then a lower status after
        if(request()->Status > $bunnyCdnVideo->status) {
            BunnyCdnVideo::syncUpdateWithFe($bunnyCdnVideo, [
                'status' => request()->Status
            ]);
        }

        // Status 4 means the encoding is complete full list
        // of status ids can be found here: https://github.com/chrisbell08/interactr/wiki/Content-Delivery-Network
        if(request()->Status===4){
            $this->repo->encodingComplete($bunnyCdnVideo);
        }

        // Catch any errors
        if(
            request()->Status === 5 ||
            request()->Status === 6
        ){
            // TODO throw error to honey badger
            return;
        }
    }

    /**
     * URL used to poll for updates from the FE
     *
     * @param BunnyCdnVideo $bunnyCdnVideo
     */
    public function poll(BunnyCdnVideo $bunnyCdnVideo)
    {
        return $this->repo->checkEncodingStatus($bunnyCdnVideo);
    }

    public function encodeVideo(Media $media) {
        
        $bunnyCdnVideo = BunnyCdnVideo::query()->where('media_id', $media->id)->first();
        $bunnyVideo->status = 0;
        $bunnyCdnVideo->save();
        ReencodeVideo::dispatch($media, $bunnyCdnVideo);
        return $bunnyCdnVideo;
    }

    public function getVideo(Request $request) {
        $mediaId = $request->get('media_id');
        $bunnyCdnVideo = BunnyCdnVideo::query()->where('media_id', $mediaId)->first();       
        return $bunnyCdnVideo;
    }
}
