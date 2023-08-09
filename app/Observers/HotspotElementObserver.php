<?php

namespace App\Observers;

use App\FormElement;
use App\HotspotElement;
use App\Repositories\InteractionRepository;

class HotspotElementObserver
{
    const ELEMENT_TYPE = 'App\\HotspotElement';

    public function creating(HotspotElement $hotspotElement)
    {
        $nodeId = $hotspotElement->nodeId;

        if (!isset($hotspotElement->name) && isset($nodeId)) {
            $hotspotElement->name = InteractionRepository::getElementName($nodeId, self::ELEMENT_TYPE);
            // Remove custom attribute from model
            unset($hotspotElement->nodeId);
        }

        return $hotspotElement;
    }
}
