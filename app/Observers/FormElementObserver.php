<?php

namespace App\Observers;

use App\FormElement;
use App\Repositories\InteractionRepository;

class FormElementObserver
{
    const ELEMENT_TYPE = 'App\\FormElement';

    public function creating(FormElement $formElement)
    {
        $nodeId = $formElement->nodeId;

        if (!isset($formElement->name) && isset($nodeId)) {
            $formElement->name = InteractionRepository::getElementName($nodeId, self::ELEMENT_TYPE);
            // Remove custom attribute from model
            unset($formElement->nodeId);
        }

        // IMPORTANT any changes here need to be reflected in the interaction repository where it copies and element as
        // we need to write back over these defaults with the copied element
        if(! isset($formElement->width)) {
            if (! $formElement->is_template) {
                $formElement->button_html = '<p><span style="color: rgb(255,255,255); font-size: 16px;">Submit</span></p>';
                $formElement->on_one_line  = 1;
                $formElement->height = 50;
                $formElement->width = 340;
                $formElement->button_paddingSides = 20;
                $formElement->padding = 10;
                $formElement->button_background = 'rgba(0, 179, 130, 0.92)';
            }
        }

        $formElement->button_borderWidth = 0 ;
        $formElement->input_borderWidth = 0 ;

        return $formElement;
    }

}