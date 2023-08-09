<?php

namespace App\Repositories;

use App\Helper\PermissionHelper;
use App\Interaction;
use App\Scopes\UserScope;
use App\Modal;
use App\Node;
use App\TriggerElement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Session;

class InteractionRepository
{
    protected $interaction;
    protected $elementRepo;
    protected $modalRepo;
    protected $permissionHelper;

    public function __construct(
        Interaction $interaction,
        ElementRepository $elementRepo,
        ModalRepository $modalRepo,
        PermissionHelper $permissionHelper
    ) {
        $this->interaction = $interaction;
        $this->elementRepo = $elementRepo;
        $this->modalRepo = $modalRepo;
        $this->permissionHelper = $permissionHelper;
    }

    public function duplicate($id)
    {
        $this->permissionHelper->checkInteractionPermissions($id);

        $interaction = Interaction::query()->findOrFail($id);
        $newElement = $this->elementRepo->copy($interaction->element_type, $interaction->element_id);

        // shift new element
        $newElement->posX += 10;
        $newElement->posY += 10;
        $newElement->save();

        $newInteraction = $interaction->replicate();
        $newInteraction->element_id = $newElement->id;
        $newInteraction->push();

        return $newInteraction;
    }

    public function getSingleInteraction($interactionId)
    {
        $data = array();
        $interaction =  $this->interaction->findOrFail($interactionId)->load('element');

        $data['interaction'] = $interaction;

        if($interaction->element instanceof TriggerElement && $interaction->element->actionArg) {
            $data['modal'] = $this->modalRepo->getModal($interaction->element->actionArg);
        }

        return $data;
    }

    public function create($data)
    {
        $nodeId = $data['node_id'];
        $element_type = $data['element_type'];
        $isInteractionLayer = $data['interaction_layer'] ?? null;

        $totalInteractionsForNode = Interaction::getTotalInteractionsForNode($nodeId, $element_type);

        $element = $this->elementRepo->add($element_type, $data, $totalInteractionsForNode);

        $interaction = Interaction::query()->create([
            'node_id' => $nodeId,
            'element_type' => $element_type,
            'element_id' => $element->id,
            'timeIn' => $data['timeIn'],
            'timeOut' => $data['timeOut'],
            'draft' => $data['draft']
        ]);

        $interaction->load('element');

        // return the modal as well when the interaction created is for a modal
        // modal has already been created by the element creating logic .

        if ($element_type === TriggerElement::class && $element->actionArg) {
            $modal = $this->modalRepo->getModal($element->actionArg);
            $interaction->modal = $modal;
        }

        // update the node if it's an interaction_layer
        // and add the node to response object
        if ($isInteractionLayer) {
            $node = Node::query()->findOrFail($nodeId);
            $node->interaction_layer_id = $interaction->id;
            $node->save();
            $interaction->node = $node;
        }

        return $interaction;
    }

    public function updateOrCreate($data)
    {
        $response = array();
        $interaction = $this->interaction->find($data['id']);
        $timeIn = $data['timeIn'] ?? null;
        $timeOut = $data['timeOut'] ?? null;
        // Check if interaction in a elementGroup, update elementGroup timeIn/timeOut.
        $elementGroup = $interaction->elementGroup;

        if ($elementGroup && ($timeIn || $timeOut)) {
            if ($elementGroup->timeIn !== $timeIn || $elementGroup->timeOut !== $timeOut) {
                $this->elementRepo->updateElementGroup($elementGroup, $timeIn, $timeOut);
                $response['elementGroupUpdate'] = true;
            }
        }

        // save element data if appended 
        if (isset($data['element']) && gettype($data['element']) === 'array') {
            $element = $data['element'];
            // add element_type to element, save method from repo needs it
            $element['element_type'] = $interaction->element_type;

            $this->elementRepo->save($element);
        }

        $interactionData = [];
        foreach ($data as $key => $value) {
            if (Schema::hasColumn($this->interaction->getTable(), $key)) {
                $interactionData[$key] = $value;
            }
        }
        $response['interaction'] = $this->interaction->updateOrCreate(['id' => $data['id'] ?? null], $interactionData)
            ->load('element', 'elementGroup');

        // append modal if relevant 
        if($interaction->element_type === TriggerElement::class && $interaction->element->actionArg){
            $response['modal'] = Modal::where('id', $interaction->element->actionArg)->with('elements.element')->first();
        }
        return $response;
    }

    public function copyAllFromNode($oldNodeId, $newNodeId, $userId = null)
    {
        $interactionsToCopy = Interaction::query()->withoutGlobalScope(UserScope::class)->where('node_id', $oldNodeId)->with('element')->get();
        
        foreach ($interactionsToCopy as $interactionToCopy) {
            $newInteraction = $interactionToCopy->replicate();
            $newInteraction->node_id = $newNodeId;

            // Copy the element
            $newElement = $interactionToCopy->element->replicate();
            $newElement->save();

            // Clear any values we need to
            if ($newInteraction->element_type == 'App\\FormElement') {
                $newElement->integration = '';
                $newElement->integration_list = '';
                $newElement->is_template = 0;
                $newElement->template_image_url = '';
                $newElement->template_name = '';
                $newElement->button_html = $interactionToCopy->element->button_html;
                $newElement->on_one_line  = $interactionToCopy->element->on_one_line;
                $newElement->height = $interactionToCopy->element->height;
                $newElement->width = $interactionToCopy->element->width;
                $newElement->button_paddingSides = $interactionToCopy->element->button_paddingSides;
                $newElement->padding = $interactionToCopy->element->padding;
                $newElement->button_background = $interactionToCopy->element->button_background;
            }

            if ($newInteraction->element_type == 'App\\TextElement') {
                $newElement->html = $interactionToCopy->element->html;
                $newElement->width = $interactionToCopy->element->width;
            }

            if ($newInteraction->element_type == 'App\\ButtonElement') {
                $newElement->background =  $interactionToCopy->element->background;
                $newElement->width =  $interactionToCopy->element->width;
                $newElement->html  =  $interactionToCopy->element->html;
            }

            $newElement->save();
            $newInteraction->element_id = $newElement->id;

            if(isset($userId)) {
                $newInteraction->save();
                $newInteraction->user_id = $userId;
            }

            $newInteraction->save();
            
            // Post the record to the copy history
            Session::push('copyHistory.interactions', [
                'old' => $interactionToCopy->id,
                'new' => $newInteraction->id
            ]);
            
        }

        return;
    }

    public function addToElementGroup(Interaction $interaction, $data)
    {
        $initialData = array(
            'element_group_id' => null
        );

        $elementGroupId = $data['elementGroupId'];

        if ($elementGroupId) {
            $elementGroup = $this->elementRepo->getElementGroupById($elementGroupId);
            $updateElementGroup = $elementGroup->update([
                'timeIn' => $interaction->timeIn,
                'timeOut' => $interaction->timeOut
            ]);

            if (!$updateElementGroup) {
                throw new QueryException();
            }

            $initialData['element_group_id'] = $elementGroup->id;
        } else {
            $initialData['timeIn'] = 0;
            $initialData['timeOut'] = $data['timeOut'];
        }

        $update = $interaction->update($initialData);

        if (!$update) {
            throw new QueryException();
        }

        return response()->json([
            'success' => true,
            'interaction' => $interaction->load('element', 'elementGroup')
        ], 200);
    }

    /**
     * Set given element name based on node interactions count
     *
     * @param $nodeId
     * @param $elementType
     * @return string
     */
    public static function getElementName($nodeId, $elementType)
    {
        if ($elementType === TriggerElement::class) $strippedName = 'Popup';
        else $strippedName = str_replace(['App\\', 'Element'], '', $elementType);

        $totalInteractionsForNode = Interaction::getTotalInteractionsForNode($nodeId, $elementType);
        $elementName = $strippedName . ' ' . ($totalInteractionsForNode + 1);

        return $elementName;
    }
}
