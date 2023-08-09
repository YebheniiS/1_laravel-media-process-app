<?php

namespace App\Repositories;

use App\Chapter;
use Illuminate\Support\Facades\Auth;

class ChapterRepository {
    protected $interaction;

    public function __construct(Chapter $chapter)
    {
        $this->chapter = $chapter;
    }

    public function addChapter($nodeId, $data)
    {
        $data['node_id'] = $nodeId;
        $chapter = $this->chapter->create($data);
        $chapter->save();
    }

    public function getChapters($nodeId)
    {
        $chapters = $this->chapter->where('node_id', '=', $nodeId)->orderBy('time');
        return $chapters;
    }

    public function copyAllFromNode($oldNodeId, $newNodeId){
        $chaptersToCopy = $this->chapter->where('node_id', $oldNodeId)->get();

        forEach($chaptersToCopy as $chapterToCopy) {
            $newChapter = $chapterToCopy->replicate();
            $newChapter->node_id = $newNodeId;
            $newChapter->save();
        }

        return;
    }
}