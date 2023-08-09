<?php

namespace App;

use App\Mail\NewCommentMail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Mail;

class Comment extends Model
{
    protected $guarded = ['id'];


    protected static function booted()
    {
        static::created(function ($comment) {
            $project = $comment->project;
            $project_owner = $project->user;

            // Send notification to the project owner, about new comment
            Mail::to($project_owner)->send(new NewCommentMail($project, $comment));
        });
        // Add the image prop to the comment
        static::retrieved(function ($comment) {
            $comment->image = "https://www.gravatar.com/avatar/" . urlencode(md5(strtolower(trim($comment->email)))) . "?d=mm&s=" . 50;
        });
    }

    /**
     * Get commentator image from https://www.gravatar.com/
     * @return string
     */
    public function gravatar()
    {
        return "https://www.gravatar.com/avatar/" . urlencode(md5(strtolower(trim($this->email)))) . "?d=mm&s=" . 50;
    }

    /**
     * Get comment project
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
