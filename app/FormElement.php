<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FormElement extends Model
{
    protected $guarded = ['id'];

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
