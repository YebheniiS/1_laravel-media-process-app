<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class NewCommentMail extends Mailable
{
    use Queueable, SerializesModels;
    public $comment;
    public $project;
    public $hash;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($project, $comment)
    {
        $this->comment = $comment;
        $this->project = $project;

        $this->hash = str_replace("projects/", "", $project->storage_path);
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Interactr Comment Notification')
                    ->markdown('emails.newComment');
    }
}
