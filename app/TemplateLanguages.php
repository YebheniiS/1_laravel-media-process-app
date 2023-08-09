<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TemplateLanguages extends Model
{
    protected $table = 'languages';
    protected $fillable = ['english_name', 'native_name'];

    public function templates()
    {
        return $this->hasMany('App\Project', 'language_id');
    }
}
