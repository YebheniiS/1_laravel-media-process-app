<?php

namespace App\Repositories;

use App\Events\ProcessMediaEncoding;
use App\Lib\Media\QencodeService;
use App\Lib\PreloadConverter;
use App\Lib\PushNotification;
use App\Media;
use App\QencodeTask;
use Illuminate\Support\Facades\Log;
use Qencode\Exceptions\QencodeException;
use Qencode\QencodeApiClient;

class QencodeTaskRepository
{
    protected $service;
    protected $pusher;

    /**
     * QencodeTaskRepository constructor.
     * @throws QencodeException
     */
    public function __construct()
    {
        $this->service = new QencodeService();
        $this->pusher = new PushNotification();
    }

    /**
     * Triggered when a  new task is created in the DB
     * and creates new task in qencode
     * @param QencodeTask $task
     */
    public function encodeTask(QencodeTask $task)
    {
        $req = $this->service->createTask($task);

        if($req['status']==='error') {
            $this->update($task, [
                'status' => 'failed',
                'token' => $req['token'],
                'failed' => 1,
                'error_description' => $req['error'],
                'percent' => 0
            ]);

            return;
        }

        if($req['status']==='ok') {
            $this->update($task, [
                'status' => 'started',
                'token' => $req['token'],
                'percent' => 10
            ]);
        }
    }


    /**
     * Updates the task in the DB with the given data
     * then sends the new task to the FE
     * @param $task
     * @param $data
     */
    public function update($task, $data)
    {
        $task->update($data);
        $this->pusher->channel('qencode_task')->event('update');
        $this->pusher->push([
            'token' => $task->token,
            'status' => $task->status,
            'percent' => $task->percent
        ]);
    }

    /**
     * Query a task on qencode api's
     *
     * @param $task
     * @return QencodeTask
     * @throws \Exception
     */
    public function checkTask($task): QencodeTask
    {
        if (!$task) throw new \Exception('Invalid Task');

        // Get the task update from qencode
        $data = $this->service->checkTask($task->token);

        // Process the task update and return the new task
        $task = $this->processCallback($data, $task);

        // Return the new task
        return $task;
    }


    /**
     * Process response from the QEncode API
     *
     * @param $data
     * @param $task
     * @return mixed
     * @throws \Exception
     */
    public function processCallback($data, $task)
    {
        // IF the event is ! saved then we just save the update
        if( $data['event'] !== 'saved' ) {
            $this->update($task, [
                'status' => $data['event'],
                'percent' => $this->getPercent($data['event'])
            ]);
        }

        // Job is completed
        $response = (array) json_decode($data['status']);

        // This saves the update to the task and pushes
        // to the front end
        $this->update($task, [
            'status' => 'completed',
            'percent' => 100
        ]);

        $url = $this->getFirstManifestUrl($response['videos'][0]['url']);

        $preloadConverter = new PreloadConverter( $url );

        $mediaRepo = app()->make(MediaRepository::class);

        // This saves the update to the media item and pushes the
        // update to the front end
        $mediaRepo->updateMedia(
            $task->media_id, [
                'manifest_url' => cdn( $url ),
                'preload_manifest_url' => cdn( $preloadConverter->getResult() ),
                'url' => ''
            ]
        );
    }


    protected function getPercent($event)
    {
        switch($event){
            case('queued') :
                return 15;
            case('converting') :
                return 20;
            case('converted') :
                return 70;
            case('saving') :
                return 85;
        }
    }

    /**
     * As we're only creating one video now the first manifest isn't needed and we can
     * cut out a http request on the player by taking it out
     *
     * @param $url
     * @return string|string[]
     */
    protected function getFirstManifestUrl($url)
    {
        return str_replace('playlist.m3u8', 'video_1.m3u8', $url);
    }
}
