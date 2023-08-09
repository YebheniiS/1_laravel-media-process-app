<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CustomHtmlElement extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'default_values' => 'array'
    ];

    public function toArray()
    {
        $parent = parent::toArray();

        if (!empty($parent['posX'])) {
            $parent['posX'] = (float)$parent['posX'];
        }

        if (!empty($parent['posY'])) {
            $parent['posY'] = (float)$parent['posY'];
        }

        return $parent;
    }
}
