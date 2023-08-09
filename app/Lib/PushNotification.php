<?php
/**
 * Created by PhpStorm.
 * User: chrisbell
 * Date: 27/11/2018
 * Time: 13:26
 */

namespace App\Lib;


use Pusher\Pusher;

class PushNotification
{
    protected $_event;
    protected $_channel;
    protected $pusher;

    public function __construct()
    {
        $this->pusher = new Pusher(
            env('PUSHER_APP_KEY'),
            env('PUSHER_APP_SECRET'),
            env('PUSHER_APP_ID'),
            [
                'cluster' => 'eu'
            ]
        );
    }

    public function event($event){
        $this->_event = $event;
        return $this;
    }

    public function channel($channel){
        $this->_channel  = $channel . $this->authenticationSuffix();
        return $this;
    }

    public function channelWithId($channel, $id){
        $this->_channel  = $channel . "_" . $id;
        return $this;
    }

    public function push($data){
      $this->pusher->trigger($this->_channel, $this->_event, $data);
    }


    /**
     * Add the users id to the end of the channel name
     * so each FE only subscribes to it's own channels
     *
     * @return string
     */
    private function authenticationSuffix(): string
    {
        return '_' . auth()->user()->id;
    }
}
