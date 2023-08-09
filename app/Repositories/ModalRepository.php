<?php

namespace App\Repositories;

use App\Modal;
use App\ModalElement;
use App\Scopes\ModalScope;
use Illuminate\Support\Facades\Session;

class ModalRepository
{
    protected $modal;
    protected $modalElement;
    protected $elementRepo;

    public function __construct(Modal $modal, ModalElement $modalElement, ElementRepository $elementRepo)
    {
        $this->modal = $modal;
        $this->modalElement = $modalElement;
        $this->elementRepo = $elementRepo;
    }

    public function getModalByElementInfo(string $type, int $id)
    {
        $modalElement = $this->modalElement->where([['element_type', '=', $type], ['element_id', '=', $id]])->first();

        if (!isset($modalElement))
            return null;

        return $modalElement->modal()->first();
    }

    public function getModalByModalElementId($id)
    {
        return $this->modalElement->find($id)->modal()->first();
    }

    public function getModal($modal, $removeModalScope = false)
    {
        if(is_integer($modal)){
            $modal = Modal::where('id', $modal);
        }

        $q = $modal->with('elements.element');

        if($removeModalScope) {
            $q->withoutGlobalScope(ModalScope::class);
        }
        
        return $q->first();
    }

    public function getModalElement($id)
    {
        return $this->modalElement->query()->find($id)->load('element');
    }

    //
    public function createElement($id, $data)
    {
        $element_type = $data['element_type'];

        $elData = [
            'pos' => $data['pos'],
        ];

        $modal = $this->getModal($id);
        if($modal->interaction_layer){
            $elData['animation'] =  [
                "name"=>"ScaleUpCenter",
                "delay"=> 1,
                "easing"=> "easeOutSine",
                "duration"=> 1,
            ];
        }

        $numberOfElementsModalHas = ModalElement::where('modal_id', $id)->get()->count();
        $element = $this->elementRepo->add(
            $element_type, $elData, $numberOfElementsModalHas
        );

        $modalElement = $this->modalElement->create([
            'modal_id' => $id,
            'element_type' => $element_type,
            'element_id' => $element->id
        ]);

        return $this->modalElement->where('id', '=', $modalElement->id)->with('element')->get()[0];
    }

    public function applyTemplate($templateId, $id)
    {
        $withoutGlobalScope = true;
        $template = $this->getModal($templateId, $withoutGlobalScope);

        if (is_null($template)) return false;

        // when we apply a template to a modal, we're basically deleting all existing modalElements and elements
        // then copying the ones from the template
        $modal = $this->getModal($id);

        // Fields to pull across from the template
        $modal->backgroundColour = $template->backgroundColour;
        $modal->show_close_icon = $template->show_close_icon;
        $modal->close_icon_color = $template->close_icon_color;
        $modal->border_radius = $template->border_radius;
        $modal->size  = $template->size;
        $modal->interaction_layer = $template->interaction_layer;
        $modal->background_animation = $template->background_animation;
        $modal->save();

        // remove existing elements
        // TODO: we may need to go through elements manually and clean them up too
        $modal->elements()->delete();

        foreach ($template->elements as $modalElement) {
            $element = $this->elementRepo->copy($modalElement->element_type, $modalElement->element_id);

            $newModalElement = $modalElement->replicate();
            $newModalElement->element_id = $element->id;
            $newModalElement->modal_id = $id;
            $newModalElement->push();
        }

        // refresh data
        // This could probably be done in the foreach using attach() or something but this is quicker for now.
        $modal =  $this->getModal($id);
        foreach ($modal->elements as $key => $value) {
            $modal->elements[$key]->name = $modal->elements[$key]->element->name;
        }
        return $modal;
    }

    public function copyAllFromProject($oldProjectId, $newProjectId)
    {
        // Lets grab all the modals we need
        $modals = Modal::query()->withoutGlobalScope(ModalScope::class)->where('project_id', $oldProjectId)->with('elements.element')->get();
        foreach ($modals as $modal) {
            // Firstly create a new modal;
            $newModal = $this->copyModal($modal->id, $newProjectId);

            // Post the record to the copy history
            Session::push('copyHistory.modals', [
                'old' => $modal->id,
                'new' => $newModal->id
            ]);
        }

        return;
    }

    public function copyModal(int $id, $newProjectId = 0, $name = false)
    {
        $modal = $this->getModal($id, true);
        $newModal = $modal->replicate();

        if ($newProjectId) {
            $newModal->project_id = $newProjectId;
        }

        if($name) {
            $newModal->name = $name;
        }else {
            $newModal->name = 'Copy of - ' . $newModal->name;
        }

        $newModal->is_template = 0;
        $newModal->template_name = '';
        $newModal->template_image_url = '';
        $newModal->save();

        // Now copy the elements
        foreach ($modal->elements as $modalElement) {
            $this->copyModalElement($modalElement, $newModal->id);
        }

        return $newModal;
    }

    public function copyModalElement($modalElement, $newModalId = 0)
    {
        $newModalElement = $modalElement->replicate();

        if ($newModalId) {
            $newModalElement->modal_id = $newModalId;
        }

        // Create a new element
        $newElement = $modalElement->element->replicate();
        $newElement->posX += 10;
        $newElement->posY += 10;
        $newElement->save();

        $newModalElement->element_id = $newElement->id;
        $newModalElement->save();

        return $newModalElement;
    }
}
