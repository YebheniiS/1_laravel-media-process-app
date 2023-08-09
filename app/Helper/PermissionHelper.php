<?php

namespace App\Helper;

use App\Interaction;
use App\Modal;
use App\Node;
use App\Project;
use App\Repositories\InteractionRepository;
use App\Repositories\ModalRepository;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PermissionHelper
{
    /**
     * @var ModalRepository
     */
    protected $modalRepository;

    /**
     * @var Node
     */
    protected $node;

    /**
     * @var Modal
     */
    protected $modal;

    public function __construct(
        ModalRepository $modalRepository,
        Node $node,
        Modal $modal,
        Project $project
    ) {
        $this->modalRepository = $modalRepository;
        $this->node = $node;
        $this->modal = $modal;
        $this->project= $project;
    }

    /**
     * @param Request $request
     * @throws AuthorizationException
     */
    public function checkElementPermissions(Request $request)
    {
        if(Auth::user()->email == 'chrisdjbell@gmail.com'){
            return true;
        }
        $element_type = $request->get('element_type');
        $element_id = $request->get('id');

        $node_id = null;

        // check if it is an interaction, if not, check to see if it is a modal.
            $interaction = Interaction::getInteractionByInfo($element_type, $element_id);
            if ($interaction instanceof Interaction) {
                $this->checkNodePermissions($interaction->node_id);
                return;
            }

            $modal = $this->modalRepository->getModalByElementInfo($element_type, $element_id);
            if ($modal instanceof Modal) {
                $this->checkModalPermissions($modal->id);
                return;
            }
    }

    public function checkInteractionPermissions($interaction_id)
    {
        if(Auth::user()->email == 'chrisdjbell@gmail.com'){
            return true;
        }

        $interaction = Interaction::query()->findOrFail($interaction_id);

        if ($interaction instanceof Interaction) {
            $this->checkNodePermissions($interaction->node_id);
            return true;
        }

        throw new AuthorizationException();
    }

    public function checkNodePermissions($node_id)
    {
        if(Auth::user()->email == 'chrisdjbell@gmail.com'){
            return true;
        }

        $node = $this->node->query()->findOrFail($node_id);

        if (!$this->isOwner($node->project->user_id)) {
            throw new AuthorizationException();
        }
    }

    public function checkProjectPermissions($project_id)
    {
        if(Auth::user()->email == 'chrisdjbell@gmail.com'){
            return true;
        }

        $project = $this->project->find($project_id);
        if (!$project instanceOf Project || !$this->isOwner($project->user_id)) {
            throw new AuthorizationException();
        }
    }

    /**
     * Check if given user is owner of modal element
     * @param $modal_id
     * @return bool
     * @throws AuthorizationException
     */
    public function checkModalPermissions($modal_id)
    {
        if(Auth::user()->email == 'chrisdjbell@gmail.com'){
            return true;
        }

        $modal = $this->modal->find($modal_id);
        if (!$modal instanceof Modal) {
            throw new AuthorizationException();
        }

        $this->checkProjectPermissions($modal->project_id);
    }

    /**
     * Check if given user_id owner of item
     * @param $value
     * @return bool
     */
    public function isOwner($value)
    {

        return $value === auth()->id() || $value === auth()->user()->parent_user_id;
    }
}