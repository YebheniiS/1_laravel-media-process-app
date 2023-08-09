<?php
namespace App\Lib;

class PublishChapters {
    protected $path;
    protected $node_Id;
    protected $chapters;
    protected $filename;
    protected $storage;
    protected $formattedChaptersArray = [];
    protected $videoEndTime = '59:55.000';

    public function __construct()
    {
        return $this;
    }

    public function toPath($path){
        $this->path = $path;
        return $this;
    }

    public function asFilename($filename){
        $this->filename = $filename;
        return $this;
    }

    public function usingChapters($chapters){
        $this->chapters = $chapters;
    }

    public function publish($storage){
        $this->storage = $storage;
        $this->createFormattedChaptersArray();
        $this->writeFile();
        $this->publishFile();
    }

    private function writeFile(){
        $file = $this->filename . 'vtt';
        $stream = fopen($file, 'a');
        fwrite($stream, 'WEBVTT');
        fwrite($stream, "\n" . " ");
        fwrite($stream, "\n" . " ");

        forEach($this->formattedChaptersArray as $chapter){
            fwrite($stream, "\n" . " ");
            fwrite($stream, $chapter['line1']);
            fwrite($stream, "\n" . $chapter['line2']);
            fwrite($stream, "\n" . $chapter['line3']);
            fwrite($stream, "\n" . " ");
            fwrite($stream, "\n" . " ");
        }

        fclose($stream);

    }

    private function createFormattedChaptersArray(){
        $prevFinTime = 0;

        forEach($this->chapters as $key => $chapter) {
            $this->formattedChaptersArray[] = [
                'line1' => $key + 1,
                'line2' => $chapter->time . ' --> ' . (isset($this->chapters[$key+1])) ? $this->chapters[$key+1]->time : $this->videoEndTime,
                'line3' => $chapter->name
            ];
        }
    }
}
