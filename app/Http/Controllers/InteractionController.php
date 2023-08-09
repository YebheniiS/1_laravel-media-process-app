<?php

namespace App\Http\Controllers;

use App\ButtonElement;
use App\Helper\PermissionHelper;
use App\Interaction;
use App\Node;
use App\Repositories\ElementRepository;
use App\Repositories\InteractionRepository;
use App\Repositories\ModalRepository;
use App\TriggerElement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class InteractionController extends Controller
{
    protected $interaction;
    protected $interactionRepo;
    protected $elementRepo;
    protected $modalRepo;
    protected $permissionHelper;



    public function __construct(
        Interaction $interaction,
        InteractionRepository $interactionRepo,
        ElementRepository $elementRepo,
        ModalRepository $modalRepo,
        PermissionHelper $permissionHelper
    ) {
        $this->interaction = $interaction;
        $this->interactionRepo = $interactionRepo;
        $this->elementRepo = $elementRepo;
        $this->modalRepo = $modalRepo;
        $this->permissionHelper = $permissionHelper;
    }

    public function save(Request $request, $interaction = false)
    {
        if ($interaction) {
            $data = $interaction;
        } else {
            $data = $request->json()->all();
        }

        $id = $data['id'];
        $nodeIdToCheck = $data['node_id'] ?? '';

        // TODO: When this is moved to a repository, we need to put this in a cleaner place too!
        if (empty($data['node_id'])) {
            $nodeIdToCheck = $this->interaction->find($id)->getAttribute('node_id');
        }

        if (!empty($nodeIdToCheck)) {
            $this->permissionHelper->checkNodePermissions($nodeIdToCheck);
        }

        return response()->json(
            $this->interactionRepo->updateOrCreate($data),
            201
        );
    }

    public function addToElementGroup(Interaction $interaction, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'elementGroupId' => 'required',
            'timeOut' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->messages(), 422);
        }

        return $this->interactionRepo->addToElementGroup($interaction, $request->all());
    }
}
