<?php
namespace App\Lib;

class Api {
    protected $success = [
        'status' => 'ok'
    ];

    public static function success($return = [])
    {
        $self = new static();
        return json_encode(array_merge(
            $self->success,
            $return
        ));
    }
    public static function error($e)
    {
        return json_encode([
            'status' => 'error',
            'message' => $e->getMessage()
        ]);
    }
}