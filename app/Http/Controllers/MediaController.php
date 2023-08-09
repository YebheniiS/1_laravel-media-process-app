<?php

namespace App\Http\Controllers;

use App\EncodedStockVideos;
use App\Helper\VideoUrlHelper;
use App\Lib\PushNotification;
use App\Lib\ThumbnailGenerator;
use App\Media;
use App\Node;
use App\Project;
use App\Repositories\MediaRepository;
use App\Repositories\ProjectRepository;
use App\Services\FineUploader;
use App\Services\VideoCompressionService;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MediaController extends Controller
{
    //
    private $media;
    private $encodedStockVideos;
    private $mediaRepository;
    private $disk = 's3';
    /* @var FilesystemAdapter Storage */
    private $storage;
    private $videoUrlHelper;
    private $videoCompressionService;
    private $fineUploader;
    private $node;
    private $project;
    private $projectRepository;
    private $request;

    public function __construct(
        Media $media,
        EncodedStockVideos $encodedStockVideos,
        MediaRepository $mediaRepository,
        VideoUrlHelper $videoUrlHelper,
        VideoCompressionService $videoCompressionService,
        FineUploader $fineUploader,
        Node $node,
        Request $request,
        Project $project,
        ProjectRepository $projectRepository
    ) {

        $this->mediaRepository = $mediaRepository;
        $this->media = $media;
        $this->encodedStockVideos = $encodedStockVideos;
        $this->storage = Storage::disk($this->disk);
        $this->videoUrlHelper = $videoUrlHelper;
        $this->videoCompressionService = $videoCompressionService;
        $this->fineUploader = $fineUploader;
        $this->node = $node;
        $this->request = $request;
        $this->project = $project;
        $this->projectRepository = $projectRepository;
    }

    public function generateThumbnailComplete($videoId = false, $thumbnail = false)
    {
        if(! $videoId) {
            $videoId = request()->get('videoId');
        }
        if(! $thumbnail){
            $thumbnail = request()->get('thumbnail');
        }

        $tg = new ThumbnailGenerator($thumbnail);
        $tg->updateThumbnail($videoId, $thumbnail);
    }

    public function convertComplete(Request $request)
    {
        $processUrl = $request->get('url');
        $step = $request->get('step'); // finished or error

        if (empty($processUrl) || empty($step)) {
            throw new \Exception("Missing crucial fields for conversion complete request.");
        }

        if ($step === "error") {
            return false;
        }

        $outputJson = file_get_contents("http:".$processUrl);
        $output = json_decode($outputJson, true);

        if (empty($output['tag'])) {
            throw new \Exception("Missing crucial tag field for conversion");
        }

        $tag = json_decode($output['tag'], true);

        $item = $this->media->find($tag['item_id']);

        if (!$item instanceof Media) {
            throw new \Exception("The item being converted does not exist. [Convert completed]");
        }

        $currentS3Path = 'public-uploads.interactr.io/'.$tag['final_path'];

        $response = $this->storage->getDriver()->getAdapter()->getClient()->copyObject([
            'Bucket' => 'cdn6.swiftcdn.co',
            'Key' => str_replace("temp/", '', $tag['final_path']),
            'CopySource' => $currentS3Path,
            'ACL' => 'public-read'
        ]);

        $videoUrl = $response->get('ObjectURL');

        $pusher = new PushNotification();
        $pusher->channel('media')->event('update');

        switch ($tag['ext']) {
            case 'mp4':
                $item->update(['compressed_mp4' => $videoUrl]);

                $pusher->push([
                    'id' => $item->id,
                    'data' => [
                        'compressed_mp4' => $videoUrl
                    ]
                ]);
                break;
            case 'webm':
                $item->update(['compressed_url' => $videoUrl]);

                $pusher->push([
                    'id' => $item->id,
                    'data' => [
                        'compressed_url' => $videoUrl
                    ]
                ]);
                break;
        }
    }

    public function convertStreamStatusUpdate(Request $request)
    {
        try {
            if (!$request->json('status', null) || !$request->json('id', null)) {
                return response('Critical key missing',400);
            }

            if ($request->json('status', null) != 'COMPLETE') {
                return response(null, 200);
            }

            $video = explode('/', $request->json('output_location', '') );
            $video[0]  = 'https:';
            $video[2] = 'swiftcdn6.global.ssl.fastly.net';

            $thumbnail = explode('/', $request->json('output_thumbnail', '') );
            $thumbnail[0]  = 'https:';
            $thumbnail[2] = 'swiftcdn6.global.ssl.fastly.net';

            /** @var Media $model */
            $model = Media::findOrFail($request->json('id'));
            $model->hls_stream = implode('/', $video);
            $model->thumbnail_url = implode('/', $thumbnail);
            
            $model->save();

            $pusher = new PushNotification();
            $pusher->channel('media')->event('update')->push([
                'id' => $model->id,
                'data' => [
                    'hls_stream' => $model->hls_stream
                ]
            ]);

            $this->generateThumbnailComplete($model->id, $model->thumbnail_url);

            return response('success', 200);
        }catch(\Exception $e){
            return response($e->getMessage(), 500);
        }
    }
}
