<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CustomListsEmails extends Model
{
    protected $table = 'custom_lists_emails';
    protected $fillable = ['custom_lists_id', 'email', 'name'];

    public function customList() : BelongsTo
    {
        return $this->belongsTo('App\CustomLists', 'custom_lists_id');
    }
}
