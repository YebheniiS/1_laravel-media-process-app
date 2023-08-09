<?php

namespace App\GraphQL\Mutations;

use App\QencodeTask;
use App\Repositories\QencodeTaskRepository;
use Illuminate\Support\Arr;

class QencodeTaskResolver
{
    /**
     * Check encoding task status/behaviour
     *
     * @param $rootValue
     * @param array $args
     * @param $context
     * @param $resolveInfo
     * @return QencodeTask
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function checkTask($rootValue, array $args, $context, $resolveInfo) : QencodeTask
    {
        $repo = app()->make( QencodeTaskRepository::class);
        $token = $args['token'] ?? false;
        $mediaId = $args['mediaId'] ?? null;

        return $repo->checkTask($token, $mediaId);
    }

    /**
     * Re run a task, we do this by removing all the job data
     *
     * @param $rootValue
     * @param array $args
     * @param $context
     * @param $resolveInfo
     * @return QencodeTask
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function reEncodeTask($rootValue, array $args, $context, $resolveInfo) : QencodeTask
    {
        $repo = app()->make( QencodeTaskRepository::class);
        $taskId = $args['taskId'] ?? null;

        return $repo->reEncodeTask($taskId);
    }
}
