<?php

namespace App\Http\Controllers;

use App\Media;
use App\QencodeTask;
use App\Repositories\QencodeTaskRepository;
use Mockery\Exception;
use Qencode\Exceptions\QencodeException;
use Qencode\QencodeApiClient;

class QencodeTaskController extends Controller
{
    protected $repo;

    public function __construct(QencodeTaskRepository $qencodeTaskRepository)
    {
        $this->repo = $qencodeTaskRepository;
    }

    /**
     * Process a callback from qencode's API
     */
    public function callback()
    {
        $token = request()->task_token;

        if(! $token) return null;

        $task = QencodeTask::findByToken($token);

        if(! $task) return null;

        $this->repo->processCallback( request()->all() , $task, true );
    }

    /**
     * Check a token in the qencode api
     *
     * @param $task
     * @return QencodeTask
     * @throws \Exception
     */
    public function checkTask($task) : QencodeTask
    {
        return $this->repo->checkTask($task);
    }
}