<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TextElement extends Model
{
    //
    protected $guarded = ['id'];
    protected $casts = [
        'default_values' => 'array',
        'animation' => 'json'
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
