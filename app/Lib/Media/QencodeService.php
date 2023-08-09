<?php
namespace App\Lib\Media;

use App\Media;
use App\QencodeTask;
use Qencode\Exceptions\QencodeException;
use Qencode\QencodeApiClient;

/**
 * Responsible for interacting with the Qencode API
 *
 * Class QencodeTaskService
 * @package App\Lib\Media
 */
class QencodeService {
    protected $qencode;

    protected $destination;

    protected $aws_key;

    protected $aws_secret;

    protected $callback_url;

    protected $bitrates = [
        2160 => 6500,
        1080 => 4800,
        720 => 3800,
        540 => 3000
    ];

    public function __construct()
    {
        $this->qencode = new QencodeApiClient(env('QENCODE_KEY'));
        $this->aws_key = env('AWS_KEY');
        $this->aws_secret = env('AWS_SECRET');
        $this->aws_region = env('AWS_REGION');
        $this->aws_bucket = env('AWS_BUCKET');

        $this->destination =

        $this->callback_url = 'https://webhook.site/a90c7cf0-e174-4c7f-8b26-81175e1aafa3';
        //$this->callback_url = 'https://e0036d502133.ngrok.io/qencode/callback';
    }

    /**
     * Create  a new task in the qencode api
     *
     * @param QencodeTask $task
     * @return array
     */
    public function createTask(QencodeTask $task)
    {
        $token = null;

        try {
            $qencodeTask = $this->qencode->createTask();

            $token = $qencodeTask->getTaskToken();

            $qencodeTask->startCustom($this->getParams($task));

            return [
                'status' => 'ok',
                'token' => $token
            ];

        } catch (QencodeException $e) {
            return [
                'status' => 'error',
                'token' => $token,
                'error' =>  $e->getMessage()
            ];
        }
    }

    public function getTask()
    {
        $qEncode = new QencodeApiClient($task->api_key);

        $path = '/status';
        $params = [
            'task_tokens' => $token
        ];

        $response = $qEncode->post($path, $params);

        $encode_task = $response['statuses'][$token];

        if ($task->percent < $encode_task['percent']) {
            $task->percent = $encode_task['percent'];
            $task->save();
        }

        return QencodeTask::findByToken($token);
    }


    private function getParams($task)
    {
        $media = Media::findOrFail($task->media_id);

        return '{
                "query": {
                    "format": [
                        {
                            "output": "advanced_hls",
                            "separate_audio": 0,
                            "segment_duration": 6,
                            "destination": {
                                "url": "'.$this->destination.'",
                                "key": "'.$this->aws_key.'",
                                "secret": "'.$this->aws_secret.'",
                                "permissions": "public-read"
                            },
                            "stream": [
                                {
                                    "video_codec": "libx264",
                                    "height": "'.$media->encoded_size.'",
                                    "width": "1280",
                                    "audio_bitrate": 128,
                                    "optimize_bitrate": 1
                                }
                            ],
                            "playlist_name": "playlist.m3u8"
                        }    
                    ],
                    "use_subtask_callback": "1",
                    "encoder_version": "2",
                    "callback_url": "'.$this->callback_url.'",
                    "source": "'.$media->url.'"  
                }
            }';
    }
}