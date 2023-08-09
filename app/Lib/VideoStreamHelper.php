<?php

namespace App\Lib;

use GuzzleHttp\Client;

class VideoStreamHelper
{
    protected $url;
    protected $type;

    public function __construct($url)
    {
        $this->url = $url;
        $this->type = $this->setType();
    }

    public function getName(){
        if ($this->type) {
            switch($this->type) {
                case('YouTube') :
                    return $this->YouTubeVideoName();
                case('Vimeo') :
                    return $this->VimeoVideoName();
            }
        }

        return false;
    }

    private function setType(){
        // Is Youtube
        if (strpos($this->url, 'youtube.com/watch?') !== false){
            return 'YouTube';
        }

        // Is Vimeo
        if (strpos($this->url, 'vimeo.com/') !== false){
            return 'Vimeo';
        }

        return false;
    }

    private function YouTubeVideoName(){
        $id = $this->getYoutubeId();
        if ($id) {
            $content = file_get_contents("http://youtube.com/get_video_info?video_id=".$id);
            parse_str($content, $ytarr);
            return empty($ytarr['title']) ? false : $ytarr['title'];
        }

        return false;
    }

    private function VimeoVideoName(){
        $id = $this->getVimeoVideoId();
        if ($id) {
            try {
                $hash = json_decode(file_get_contents("http://vimeo.com/api/v2/video/{$id}.json"));
                return $hash[0]->title;
            } catch (\Exception $e) {
                return false;
            }
        }

        return false;
    }


    private function getYoutubeId()
    {
        return preg_replace("/(https?)?+(:\/\/?)?+(www.?)?+[a-zA-Z]+(.com|.be)+(\/)+(watch?)?+[(?)]?+(v=?|V=?)?|(&)+(.*)/", "", $this->url);
    }


    private function getVimeoVideoId()
    {
        $slashExplode = explode('/', $this->url);
        $dotExplode = explode('.', last($slashExplode));
        return $dotExplode[0];
    }
}