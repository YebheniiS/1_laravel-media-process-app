<?php

namespace App\Observers;

use App\ButtonElement;
use App\Repositories\InteractionRepository;

class ButtonElementObserver
{
    const ELEMENT_TYPE = 'App\\ButtonElement';

    public function creating(ButtonElement $buttonElement)
    {
        $nodeId = $buttonElement->nodeId;

        if (!isset($buttonElement->name) && isset($nodeId)) {
            $buttonElement->name = InteractionRepository::getElementName($nodeId, self::ELEMENT_TYPE);
            // Remove custom attribute from model
            unset($buttonElement->nodeId);
        }

        // IMPORTANT any changes here need to be reflected in the interaction repository where it copies and element as
        // we need to write back over these defaults with the copied element
        if(! isset($buttonElement->width)) {
            $buttonElement->background = 'rgba(0, 179, 130, 0.92)';
            $buttonElement->width = 130;
            $buttonElement->html  = '<p style="text-align:center;"><span style="color: rgb(255,255,255); font-size: 16px;">Your text here</span></p>';
            $buttonElement->borderWidth = 0;
        }

        return $buttonElement;
    }

}