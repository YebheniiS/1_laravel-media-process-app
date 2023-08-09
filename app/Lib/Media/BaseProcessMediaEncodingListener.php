<?php


namespace App\Lib\Media;


use App\EncodedStockVideos;
use App\Lib\PreloadConverter;
use App\Media;
use App\QencodeTask;
use Nuwave\Lighthouse\Execution\Utils\Subscription;

abstract class BaseProcessMediaEncodingListener
{
    public $preloadConverter;

    public function __construct()
    {
        $this->preloadConverter = new PreloadConverter();
    }

    /**
     * The MAIN method we run in ProcessMediaEncodingListener.php
     *
     * @param QencodeTask $task
     * @return bool
     */
    protected function processQEncodeTask(QencodeTask $task)
    {
        if (is_null($this->responseJSON)) {
            return $this->processEncoding($task);
        }

        $event = $this->responseJSON['event'] ?? null;
        $status = $this->responseJSON['status'] ?? null;
        $error = $this->responseJSON['error'] ?? null;

        if (isset($status) && $status === 'completed') {
            return $this->processCompleted($task);
        }

        if (isset($error)) {
            return $this->processFailed($task);
        }

        if (isset($event)) {
            switch($event) {
                case('queued') :
                    return $this->processQueued($task);
                case('encoding') :
                    return $this->processEncoding($task);
                case('completed') :
                    return $this->processCompleted($task);
            }
        }
    }

    /**
     * @param QencodeTask $task
     */
    protected function processQueued(QencodeTask $task)
    {
        $task->status = 'queued';
        $task->percent = 1;
        $task->save();

        $this->pushTaskUpdate($task);

        return true;
    }

    /**
     * @param QencodeTask $task
     */
    protected function processEncoding(QencodeTask $task)
    {
        $task->status = 'encoding';
        $task->percent = 10;
        $task->save();

//        $this->pushTaskUpdate($task);

        return true;
    }

    /**
     * @param QencodeTask $task
     * @return bool
     */
    protected function processFailed(QencodeTask $task) {
        $task->status = 'failed';
        $task->failed = $this->responseJSON['error'];
        $task->error_description = $this->responseJSON['error_description'];
        $task->save();

//        $this->pushTaskUpdate($task);

        return true;
    }

    /**
     * @param QencodeTask $task
     */
    protected function processCompleted(QencodeTask $task)
    {
        $task->status = 'completed';
        $task->percent = 100;
        $task->save();

        $manifest_url = $this->responseJSON['videos'][0]->url;
        $preloadPlaylistUrl = $this->preloadConverter->getPreloadPlaylist($manifest_url);

        $media = Media::query()->findOrFail($task->media_id);
        $media->manifest_url = $this->addCdn($manifest_url);
        $media->preload_manifest_url = $this->addCdn($preloadPlaylistUrl);
        $media->url = '';
        $media->thumbnail_url = $this->addCdn($this->responseJSON['images'][0]->url);
        $media->save();

        /**
         * If task has been created by stock video item -> -> ->
         * update the stock video record with encoded data to not duplicate conversion next time
         */
        if ((int) $task->stock_video_id) {
            $stockVideoItem = EncodedStockVideos::findByStockVideoID($task->stock_video_id);
            $stockVideoItem->encoded_url = $media->manifest_url;
            $stockVideoItem->thumbnail_url = $media->thumbnail_url;
            $stockVideoItem->status = $task->status;
            $stockVideoItem->save();
        }

//        $this->pushTaskUpdate($task);
        $this->pushMediaUpdate($media);

        return true;
    }

    /**
     * @param QencodeTask $task
     */
    protected function pushTaskUpdate(QencodeTask $task){
        $this->pusher->channel('encode_tasks')->event('update');
        $this->pusher->push([
            'token' => $task->token,
            'status' => $task->status,
            'percent' => $task->percent
        ]);
    }

    /**
     * @param $media
     */
    protected function pushMediaUpdate($media)
    {
        Subscription::broadcast('mediaUpdated', $media);
    }

    /**
     * @param $path
     * @return string|string[]
     */
    private function addCdn($path){
        // https://cdn6.swiftcdn.co.s3.amazonaws.com/thumbnails/32c2afb846d411eaa43c86792b26878c/thumbnail.jpg
        $fastly = 'https://swiftcdn6.global.ssl.fastly.net';
        $aws = 'https://cdn6.swiftcdn.co.s3.amazonaws.com';

        return str_replace($aws, $fastly, $path);
    }

}