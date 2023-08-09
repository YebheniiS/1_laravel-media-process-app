<?php

namespace App\Observers;

use App\Repositories\InteractionRepository;
use App\TextElement;

class TextElementObserver
{
    const ELEMENT_TYPE = 'App\\TextElement';

    public function creating(TextElement $textElement)
    {
        $nodeId = $textElement->nodeId;

        if (!isset($textElement->name) && isset($nodeId)) {
            $textElement->name = InteractionRepository::getElementName($nodeId, self::ELEMENT_TYPE);
            // Remove custom attribute from model
            unset($textElement->nodeId);
        }

        // IMPORTANT any changes here need to be reflected in the interaction repository where it copies and element as
        // we need to write back over these defaults with the copied element
        if(! isset($textElement->width)) {
            $textElement->html = '<p><span style="color: rgb(255,255,255); font-size: 16px;">Your text here</span></p>';
            $textElement->width = 130;
        }
        

        $textElement->borderWidth = 0 ;

        return $textElement;
    }

}