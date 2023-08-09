<?php

namespace App;

use App\Scopes\UserScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CustomLists extends Model
{
    protected $table = 'custom_lists';
    protected $fillable = ['custom_list_name', 'user_id'];

    protected static function booted()
    {
        static::addGlobalScope(new UserScope());
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }

    public function emails() : HasMany
    {
        return $this->hasMany('App\CustomListsEmails', 'custom_lists_id')
                    ->select('id', 'custom_lists_id', 'email', 'created_at');
    }
}
