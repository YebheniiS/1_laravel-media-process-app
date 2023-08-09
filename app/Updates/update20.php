<?php


namespace App\Updates;


use App\Interaction;
use App\Modal;
use App\Node;
use App\Project;
use App\Scopes\UserScope;
use Illuminate\Support\Facades\Auth;

class update20
{
    public function apply()
    {
        $this->addUserIdToAllModels();

        $this->updateProjects();

        // Update node level settings
        $this->updateNodes();

        $this->updateModals();

        $this->upgradeComplete();
    }

    private function updateProjects()
    {
        $projects = Project::all();

        foreach($projects as $project) {

            $project->published_path = 'https://swiftcdn6.global.ssl.fastly.net/'.$project->storage_path.'/index.html';

            $project->save();
        }

    }

    private function user()
    {
        return Auth::user()->id;
    }

    private function addUserIdToAllModels()
    {
        $projects = Project::withoutGlobalScope(UserScope::class)->where('user_id', $this->user())->with('nodes')->get();
        foreach($projects as $project) {
            // Add the user id to all nodes
            foreach($project->nodes as $node) {
                $node->user_id = $this->user();
                $node->save();

                $node->load('interactions');
                foreach($node->interactions as $interaction){
                    $interaction->user_id = $this->user();
                    $interaction->save();
                }
            }
        }
    }

    private function updateNodes()
    {
        $nodes = Node::withoutGlobalScope(UserScope::class)->where('user_id', $this->user())->get();
        foreach($nodes as $node){
            $this->updateNode($node);
        }
    }

    private function updateNode($node)
    {
        // First step is to check for loop=true, if so we add loop as the complete action
        if($node->loop) {
            $node->loop = 0;
            $node->completeAction = 'loop';
            $node->save();
        }

        // Next we handle the interaction layer stuff. This is now a complete action of openModal. the will override
        // the previous loop setting. this is by design as interaction layer takes priority
        if($node->interaction_layer_id) {

            $interaction = Interaction::find($node->interaction_layer_id);
            if($interaction){
                $interaction->load('element');
                //$interaction->element->load('modal');
    
                // {"name": "FadeIn", "delay": 1, "easing": "easeOutSine", "duration": 1.2, "playSound": true, "use_timer": true, "timer_duration": 20}
                //$modal = $interaction->element->modal;
    
                $modal = Modal::find($interaction->element->actionArg);
    
                if($modal) {
                    $node->completeActionArg = $modal->id;
                    $node->completeAction = 'openModal';
                    $node->completeActionDelay = $modal->background_animation['delay'];
                    $node->completeAnimation = $modal->background_animation['name'];
                    $node->completeActionSound = $modal->background_animation['playSound'];
                    $node->completeActionTimer = ($modal->background_animation['use_timer']) ? $modal->background_animation['timer_duration'] :  0;
                }
                $interaction->delete();
    
            }       



            $node->interaction_layer_id = 0;
            $node->enable_interaction_layer = 0;
            $node->save();

            // The interaction and the trigger element can now be removed as the modal is linked directly
            // to the node via the completeActionArg (interaction has deleting model observer that also
            // deletes the element)
        }

        // Just some cleanup as this defaults to 1
        if($node->enable_interaction_layer) {
            $node->enable_interaction_layer = 0;
            $node->save();
        }
    }

    public function updateModals(){
        $projects = Project::with('modals')->get();

        foreach($projects as $project) {
            foreach($project->modals as $modal) {
                if($modal->interaction_layer) {
                    $modal->size=100;
                    $modal->show_close_icon=0;
                    $modal->save();
                }
            }
        }
    }

    private function upgradeComplete(){
        $user = Auth()->user();
        $user->upgraded = 1;
        $user->save();
    }
}