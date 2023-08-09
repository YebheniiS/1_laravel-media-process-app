<?php

namespace App\Repositories;

use App\ButtonElement;
use App\CustomHtmlElement;
use App\ElementGroup;
use App\FormElement;
use App\HotspotElement;
use App\ImageElement;

use App\Modal;
use App\TextElement;
use App\TriggerElement;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class ElementRepository
{
    public $allowedElementTypes = [
        ButtonElement::class,
        HotspotElement::class,
        ImageElement::class,
        TextElement::class,
        CustomHtmlElement::class,
        TriggerElement::class,
        FormElement::class
    ];

    CONST ANIMATION_LAYER_DEFAULTS = [
        "name"=>"SlideInTopRight",
        "delay"=> 1,
        "easing"=> "easeOutSine",
        "duration"=> 1.8,
        "playSound"=> true,
        "use_timer"=> true,
        "timer_duration"=> 20
    ];

    public static $actions = [
        'openUrl', 'openModal', 'playNode', null
    ];

    protected function guardElementType($element_type)
    {
        if (!in_array($element_type, $this->allowedElementTypes)) {
            throw new \TypeError('invalid element_type');
        }
    }


    public function copy($element_type, $id)
    {
        $this->guardElementType($element_type);
        $model = new $element_type;
        $element = $model->find($id);
        $newElement = $element->replicate();
        $newElement->push(); //saves
        return $newElement;
    }

    /** Applies template element , creates a new element if `id` of the target element isn't provided
     * expects an object with **required** properties `element_type` and `templateId` and optional `id` property
     */
    public function applyTemplate($data)
    {
        $element_type = $data['element_type'];
        $templateId = $data['templateId'];


        $this->guardElementType($element_type);
        $model = new $element_type;
        $elementToCopy = $model->where([
            ['id', '=', $templateId],
            ['is_template', '=', 1]
        ])->first();

        if (is_null($elementToCopy)) return false;

        $element = $elementToCopy->replicate();

        if (isset($data['id'])) {
            $id = $data['id'];
            // delete old and replace with clone
            $model->find($id)->delete();
            $element->id = $id;
        }
        $element->save();



        // Save before we reset the is_template this prevent the
        // defaults overriding the template in the Element Observer
        // classes
        $element->is_template = 0;
        $element->template_name = '';
        $element->template_image_url = '';
        $element->save();

        return $element;
    }

    public function getElementGroupById($id)
    {
        return ElementGroup::find($id);
    }

    public function updateElementGroup($elementGroup, $timeIn, $timeOut)
    {
        $newCollection = array();

        if ($timeIn) {
            $newCollection['timeIn'] = $timeIn;
        }

        if ($timeOut) {
            $newCollection['timeOut'] = $timeOut;
        }

        return $elementGroup->update($newCollection);
    }

    /**
     * Delete method used inside the deleting event for interactions
     * to remove the element
     * @param $element_type
     * @param $element_id
     */
    public function delete($element_type, $element_id)
    {
        $this->guardElementType($element_type);
        $element = $element_type::find($element_id);
        if(!$element) return ;
        // Not sure we should delete modal here anymore
//        if ($element_type === TriggerElement::class && $element->actionArg) {
//            $modal = Modal::find($element->actionArg);
//            if($modal) $modal->delete();
//        }
        return $element->delete();
    }
}
