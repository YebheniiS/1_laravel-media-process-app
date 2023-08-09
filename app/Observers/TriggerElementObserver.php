<?php

namespace App\Observers;

use App\Modal;
use App\Repositories\ElementRepository;
use App\Repositories\InteractionRepository;
use App\TriggerElement;

class TriggerElementObserver
{
    const ELEMENT_TYPE = 'App\\TriggerElement';

    public function creating(TriggerElement $triggerElement)
    {
//        $nodeId = $triggerElement->nodeId;
//
//        if (!isset($triggerElement->name) && isset($nodeId)) {
//            $triggerElement->name = InteractionRepository::getElementName($nodeId, self::ELEMENT_TYPE);
//            // Remove custom attribute from model
//            unset($triggerElement->nodeId);
//        }
//
//        // ⚠⚡ Fusing the trigger element creation with modals creation
//        // in interactr-3 iteration to loose the confusion between them.
//        // So now users only see modals as an element .
//        $projectId = $triggerElement->projectId;
//        $name = $triggerElement->name;
//        $newModalData = [
//            'project_id' => $projectId,
//            'name' => $name,
//        ];
//
//        // make it an interaction_layer
//        if (isset($data['interaction_layer']) && $data['interaction_layer']) {
//            $newModalData['interaction_layer']  = 1;
//            $newModalData['background_animation'] = ElementRepository::ANIMATION_LAYER_DEFAULTS;
//            $newModalData['backgroundColour'] = 'rgba(0, 0, 0, 0.92)';
//        }
//
//        // create a new modal for this trigger
//        $modal =  Modal::query()->create($newModalData);
//        // set the link between the modal and it's trigger
//        // trigger elments get the 'openModal' action by default and need
//        // actionArg to point to the right modal
//        $triggerElement->actionArg = $modal->id;
//
//        $triggerElement->action = 'openModal';
//
//        // Delete custom projectId attribute from model
//        unset($triggerElement->projectId);
//
//        return $triggerElement;
    }

}