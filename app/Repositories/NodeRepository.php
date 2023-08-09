<?php

namespace App\Repositories;

use App\Node;
use App\Scopes\UserScope;
use Illuminate\Support\Facades\Session;

class NodeRepository
{
    /**
     * @param $nodeIdToCopy
     * @param $projectId
     * @param $newMediaId
     * @param null $name
     * @param null $posX
     * @param null $posY
     * @return Node
     */

    public function copyNode($nodeIdToCopy, $projectId, $copyFromProjectTemplate = false, $newMedia = null, $name = null, $posX = null, $posY = null, $userId = null): Node
    {
        // If project is being copied by "createTemplateProject" method, remove global UserScope from Node queries
        if ($copyFromProjectTemplate) {
            $query = Node::query()->withoutGlobalScope(UserScope::class);
        } else {
            $query = Node::query();
        }
        $nodeToCopy =  $query->findOrFail($nodeIdToCopy);
        $newNode = $nodeToCopy->replicate();
        $newNode->project_id = $projectId;

        if(isset($userId)) {
            $newNode->save();
            $newNode->user_id = $userId;
        }

        if (isset($newMedia)) {
            $newNode->media_id = $newMedia->id;
        }

        // Set the name of the node, if no name passed in we just append
        // "copy" to the name of the source node
        if (isset($name)) {
            $newNode->name = $name;
        } else {
            $newNode->name =  $newNode->name . " (copy)";
        }

        // If any new positioning values are passed in we need to set them too
        if (isset($posX)) {
            $newNode->posX = $posX;
        }
        if (isset($posY)) {
            $newNode->posY = $posY;
        }

        $newNode->save();


        return $newNode;
    }

    public function copyAllFromProject($oldProjectId, $newProjectId, $mediaRepository, $interactionRepository, $copyFromProjectTemplate, $userId)
    {
        $query = null;

        // If project is being copied by "createTemplateProject" method, remove global UserScope from Node queries
        if ($copyFromProjectTemplate) {
            $query = Node::query()->withoutGlobalScope(UserScope::class);
        } else {
            $query = Node::query();
        }

        $nodesToCopy = $query->where('project_id', $oldProjectId)->get();

        foreach ($nodesToCopy as $nodeToCopy) {
            $newMedia = $mediaRepository->copyToProject($nodeToCopy->media_id, $newProjectId, $userId);
            
            // Create a new node and assign the new project ID
            $newNode = $this->copyNode($nodeToCopy->id, $newProjectId, copyFromProjectTemplate: $copyFromProjectTemplate, newMedia: $newMedia, userId: $userId );
            
            $interactionRepository->copyAllFromNode($nodeToCopy->id, $newNode->id, $userId);

            // Post the record to the copy history
            Session::push('copyHistory.nodes', [
                'old' => $nodeToCopy->id,
                'new' => $newNode->id
            ]);
        }
    }

    public function mediaInUse(int $mediaId): bool
    {
        return Node::query()->where("media_id", "=", $mediaId)->count() > 0;
    }

    /**
     * Delete a node
     * @param $nodeIdToCopy
     * @param array $args
     */
    // function deleteNode($nodeId)
    // {
    //     $node = Node::findOrFail($nodeId);
    //     $project = $node->project;
    //     $newStartNodeId  =  0;
    //     if ($project->start_node_id == $node->id) {
    //         $newStartNodeId = $project->changeStartNode($node);
    //     }
    //     $deleted = $node->delete();
    //     return ["success" => $deleted,  "newStartNodeId" => $newStartNodeId];
    // }

    /**
     * Sort nodes
     * @param $nodes
     * @return bool
     */
    public static function sortNodes($nodes)
    {
        $response = "Nodes sorting updated.";

        foreach ($nodes as $node) {
            try {
                $nodeItem = Node::query()->find($node['id']);

                $nodeItem->update(['sort_order' => $node['sort_order']]);
            } catch (\Exception $e) {
                $response = $e->getMessage();
            }
        }

        return $response;
    }

}
