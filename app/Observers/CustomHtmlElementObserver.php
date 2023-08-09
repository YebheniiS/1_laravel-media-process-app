<?php

namespace App\Observers;

use App\CustomHtmlElement;
use App\Repositories\InteractionRepository;

class CustomHtmlElementObserver
{
    const ELEMENT_TYPE = 'App\\CustomHtmlElement';

    public function creating(CustomHtmlElement $customHtmlElement)
    {
        $nodeId = $customHtmlElement->nodeId;

        if (!isset($customHtmlElement->name) && isset($nodeId)) {
            $customHtmlElement->name = InteractionRepository::getElementName($nodeId, self::ELEMENT_TYPE);
            // Remove custom attribute from model
            unset($customHtmlElement->nodeId);
        }

        return $customHtmlElement;
    }
}
