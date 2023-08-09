<?php

namespace App\Observers;

use App\ImageElement;
use App\Repositories\InteractionRepository;

class ImageElementObserver
{
    const ELEMENT_TYPE = 'App\\ImageElement';

    public function creating(ImageElement $imageElement)
    {
        $nodeId = $imageElement->nodeId;

        if (!isset($imageElement->name) && isset($nodeId)) {
            $imageElement->name = InteractionRepository::getElementName($nodeId, self::ELEMENT_TYPE);
            // Remove custom attribute from model
            unset($imageElement->nodeId);
        }

        return $imageElement;
    }
}
