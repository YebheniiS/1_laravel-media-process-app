<?php


namespace App\Services;


use App\Project;
use App\Repositories\MediaRepository;
use App\Repositories\ModalRepository;
use App\Repositories\NodeRepository;
use App\Repositories\InteractionRepository;
use App\Scopes\UserScope;
use App\Models\User;
use Illuminate\Support\Facades\Session;

class ProjectCopier
{
    protected $project;
    protected $modalRepository;
    protected $nodeRepository;
    protected $mediaRepository;
    protected $interactionRepository;

    public function __construct(
        ModalRepository $modalRepository,
        NodeRepository $nodeRepository,
        MediaRepository $mediaRepository,
        InteractionRepository $interactionRepository
    )
    {
        $this->project = new Project();
        $this->modalRepository = $modalRepository;
        $this->mediaRepository = $mediaRepository;        
        $this->nodeRepository = $nodeRepository;
        $this->interactionRepository = $interactionRepository;
    }

    /**
     * Warning this method is a BEAST
     * Is does need reworking but its going to take some time
     *
     * @param Int $id
     * @param $groupId
     * @param String $title
     * @param String $description
     * @param bool $copyFromTemplate
     * @return Project
     */
    public function copy(Int $id, $groupId, String $title, String $description, bool $copyFromTemplate = false, $userId=null) : Project
    {
        // If project coping from template, get template without UserScope
        if ($copyFromTemplate) {
            $projectToCopy = $this->project->query()->withoutGlobalScope(UserScope::class)->findOrFail($id);
        } else {
            $projectToCopy = $this->project->query()->findOrFail($id);
        }

        $newProject  = $projectToCopy->replicate();

        // Update the name and description with the new values. The reason we
        // allow these to be passed in is so we can usse this copy method to both
        // copy and create from template as is both basically the same thing
        $newProject->title = ($title) ?? $newProject->title . ' (copy)';
        $newProject->description = ($description) ?? '';

        // new project shouldn't automatically belong to same group unless specified
        $newProject->project_group_id = $groupId;

        // Remove the template stuff
        $newProject->is_template = 0;
        $newProject->template_image_url = '';
        $newProject->template_name = '';
        $newProject->downloadable_assets = '';
        $newProject->is_favourite = false;
        $newProject->preview_url = '';

        // Clear the old project data
        $newProject->storage_path = null;
        $newProject->published_path = null;
        $newProject->published_at = null;

        $newProject->save();

        // Init the Session data we post all copied data in here so we can go back and update the actionArg's at end
        Session([
            'copyHistory' => [
                'nodes' => [],
                'modals' => [],
                'interactions' => []
            ]
        ]);


        // Copy Modals
        $this->modalRepository->copyAllFromProject($projectToCopy->id, $newProject->id);

        // Copy ( Nodes + Medias + interactions)
        $this->nodeRepository->copyAllFromProject(
            $projectToCopy->id,
            $newProject->id,
            $this->mediaRepository,
            $this->interactionRepository,
            $copyFromTemplate,
            $userId
        );


        // Finally we need to grab the project and go through all the action args and update them to the new nodes (this will be fun! :( )
        $project = $this->project->with('modals.elements.element', 'nodes.interactions.element')
                                    ->where('id', $newProject->id)->first();
        $history = Session::get('copyHistory');

        // Update Modals
        $modals = $copyFromTemplate ? $project->modalsWithoutScope : $project->modals;

        foreach ($modals as $modal) {
            foreach ($modal->elements as $modalElement) {
                if ($modalElement->element->action === 'playNode' && $modalElement->element->actionArg) {
                    // it has an action arg so we need to update it with the new ID
                    foreach ($history['nodes'] as $historyNode) {
                        if ($historyNode['old'] == $modalElement->element->actionArg) {
                            $modalElement->element->actionArg = $historyNode['new'];
                            $modalElement->element->save();
                        }
                    }
                }
            }
        }

        // Update Node Complete Actions + Elements
        $nodes = $copyFromTemplate ? $project->nodesWithoutScope : $project->nodes;

        foreach ($nodes as $node) {
            foreach ($history['nodes'] as $historyItem) {
                if ($historyItem['old'] == $project->start_node_id) {
                    $project->start_node_id = $historyItem['new'];
                    $project->save();
                }
            }

            // point interaction layer id to the new copy's interaction
            if ($node->interaction_layer_id) {
                foreach ($history['interactions'] as $interactionHistory) {
                    if ($node->interaction_layer_id == $interactionHistory['old']) {
                        $node->interaction_layer_id = $interactionHistory['new'];
                        $node->save();
                    }
                }
            }

            if ($node->completeActionArg) {
                if ($node->completeAction === 'playNode') {
                    $historyItems = $history['nodes'];
                }
                if ($node->completeAction === 'openModal') {
                    $historyItems = $history['modals'];
                }

                if (isset($historyItems)) {
                    foreach ($historyItems as $historyItem) {
                        if ($historyItem['old'] == $node->completeActionArg) {
                            $node->completeActionArg = $historyItem['new'];
                            $node->save();
                        }
                    }
                }
            }

            $interactions = $copyFromTemplate ? $node->interactionsWithoutScope : $node->interactions;
            foreach ($interactions as $interaction) {
                if ($interaction->element->actionArg) {
                    if ($interaction->element->action === 'playNode') {
                        $historyItems = $history['nodes'];
                    }
                    if ($interaction->element->action === 'openModal') {
                        $historyItems = $history['modals'];
                    }

                    if (isset($historyItems)) {
                        foreach ($historyItems as $historyItem) {
                            if ($historyItem['old'] == $interaction->element->actionArg) {
                                $interaction->element->actionArg = $historyItem['new'];
                                $interaction->element->save();
                            }
                        }
                    }
                }
            }
        }

        return $newProject;
    }

}