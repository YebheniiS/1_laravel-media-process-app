<?php
namespace App\Services;

use App\Media;
use CloudConvert\Api;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Auth;

class VideoCompressionService
{
    const COMPRESSION_SERVICE_CLOUD = 'COMPRESSION_SERVICE_CLOUD';
    const COMPRESSION_SERVICE_STREAM = 'COMPRESSION_SERVICE_STREAM';

    const OUTPUT_LOCATION_CLOUD = 'temp/media/';
    const OUTPUT_LOCATION_STREAM = 's3://cdn6.swiftcdn.co/hls/';

    private $itemId;

    protected function getS3PathForItem()
    {
        $url =  (Media::findOrFail($this->itemId))->url;

        if (strpos($url, 'interactr-2-source-plppvsc270ey') !== false) {
            // Original Steam File
            return str_replace('https://s3.amazonaws.com/', 's3://', $url);
        }

        // Compressed mp4 to convert to stream
        return str_replace('https://swiftcdn6.global.ssl.fastly.net', 's3://cdn6.swiftcdn.co', $url);
    }

    protected function getItemInfoForItemId()
    {
        $media = Media::find($this->itemId);
        if (!$media instanceof Media) {
            throw new \Exception("Cannot find the original media to attempt to convert.");
        }
        return $media->url;
    }

    /**
     * @param $mediaId
     * @param bool $compressTo
     * @throws \Exception
     */
    public function compressVideo($mediaId, $compressTo = false){
        if($compressTo){
            switch($compressTo){
                case('mp4') :
                    $this->triggerVideoCompression($mediaId, self::COMPRESSION_SERVICE_CLOUD, 'mp4');
                    break;
                case('hls') :
                    $this->triggerVideoCompression($mediaId,  self::COMPRESSION_SERVICE_STREAM );
                    break;
                case('webm') :
                    $this->triggerVideoCompression($mediaId, self::COMPRESSION_SERVICE_CLOUD, 'webm');
                    break;
            }
        } else {
            if (auth()->user()->should_compress_videos){
                $this->triggerVideoCompression($mediaId, self::COMPRESSION_SERVICE_CLOUD);
            }
        }
    }

    private function compressViaCloud($type)
    {
        $videoUrlKey = last(explode("/", $this->getItemInfoForItemId()));
        $cloudConvertApi = $this->getCloudConvertApi();

        $finalPathMP4 = self::OUTPUT_LOCATION_CLOUD . $videoUrlKey;
        $finalPathWEBM = self::OUTPUT_LOCATION_CLOUD . str_replace(".mp4", ".webm", $videoUrlKey);

        $processForWEBM = $cloudConvertApi->createProcess(['inputformat' => 'mp4', 'outputformat' => 'webm']);
        $processForMP4 = $cloudConvertApi->createProcess(['inputformat' => 'mp4', 'outputformat' => 'mp4']);

        $configForWEBM = $configForMP4 = [
            "input" => [
                "s3" => [
                    "accesskeyid" => env('S3_PUBLIC_UPLOADS_KEY'),
                    "secretaccesskey" => env('S3_PUBLIC_UPLOADS_SECRET'),
                    "bucket" => env('S3_PUBLIC_UPLOADS_BUCKET'),
                    "region" => env('S3_PUBLIC_UPLOADS_REGION')
                ],
            ],
            'file' => self::OUTPUT_LOCATION_CLOUD . $videoUrlKey,
            "output" => [
                "s3" => [
                    "accesskeyid" => env('S3_PUBLIC_UPLOADS_KEY'),
                    "secretaccesskey" => env('S3_PUBLIC_UPLOADS_SECRET'),
                    "bucket" => env('S3_PUBLIC_UPLOADS_BUCKET'),
                    "region" => env('S3_PUBLIC_UPLOADS_REGION'),
                    'acl' => 'public-read-write',
                ],
            ],
            'callback' => (isset($_SERVER['HTTPS']) ? "https" : "http") ."://".$_SERVER['HTTP_HOST']."/api/media/dgshdthbdzsgrdhfbvc/convert-complete"
//            'callback' => "http://6f01f399.ngrok.io/api/media/dgshdthbdzsgrdhfbvc/convert-complete"
        ];

        $configForMP4['outputformat'] = 'mp4';
        $configForMP4['output']['s3']['path'] = $finalPathMP4;
        $configForMP4['tag'] = json_encode([
            'item_id' => $this->itemId,
            'final_path' => $finalPathMP4,
            'ext' => 'mp4'
        ]);

        $configForWEBM['outputformat'] = 'webm';
        $configForWEBM['output']['s3']['path'] = $finalPathWEBM;
        $configForWEBM['tag']= json_encode([
            'item_id' => $this->itemId,
            'final_path' => $finalPathWEBM,
            'ext' => 'webm'
        ]);

        if($type) {
            switch($type){
                case('mp4') :
                    $processForMP4->start($configForMP4);
                    break;
                case('webm') :
                    $processForWEBM->start($configForWEBM);
                    break;
            }
        } else {
            $processForMP4->start($configForMP4);
            $processForWEBM->start($configForWEBM);
        }
    }

    private function compressViaStream()
    {
        $guzzle = new Client();
        $guzzle->post(env('CONVERT_STREAM_URL'), [
            'form_params' => [
                'id' => $this->itemId,
                's3_source_path' => $this->getS3PathForItem(),
                'output_location' => self::OUTPUT_LOCATION_STREAM . $this->itemId .'/',
                'api_key' => env('CONVERT_STREAM_KEY')
        ]]);
    }

    public function triggerVideoCompression($itemId, $compressionService, $type = false)
    {
        $this->itemId = $itemId;
        switch ($compressionService) {
            case self::COMPRESSION_SERVICE_CLOUD:
                $this->compressViaCloud($type);
                break;
            case self::COMPRESSION_SERVICE_STREAM:
                $this->compressViaStream();
                break;
            default:
                throw new \Exception('Compression method could not be found');
        }
    }

    /**
     * @return Api
     */
    protected function getCloudConvertApi()
    {
        $apiKey = env('CLOUDCONVERT_API_KEY');
        return new Api($apiKey);
    }
}
