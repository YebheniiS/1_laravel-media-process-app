<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TemplatesUsed extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'templates_used';

    protected $guarded = [];

    public function project()
    {
      return $this->belongsTo(Project::class);
    }

    public function user()
    {
      return $this->belongsTo(User::class);
    }
}
