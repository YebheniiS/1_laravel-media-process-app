<?php

namespace App\Http\Controllers;


use App\Project;
use App\Repositories\ProjectRepository;
use App\Services\ProjectPublisher;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\UnauthorizedException;

class ProjectController extends Controller
{
    /**
     * Get projects paginated list
     */
    public function projects()
    {
        $projectIds = auth()->user()->projects;
        if($projectIds !== NULL) {
            $projects = Project::query()->whereIn('id', $projectIds)->select('id', 'title')->paginate(10);
            return response()->json($projects);
        }

        $projects = Project::query()->select('id', 'title')->paginate(10);
        return response()->json($projects);
    }

    /**
     * Returns the projects index.html. Used to debug projects using a
     * local version of the player code
     *
     * @param Project $project
     * @return mixed
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function preview($id)
    {
        //if(! auth()->user()->superuser) throw new UnauthorizedException();
        $playerEnv = ( request()->env ) ?? false;
        
        $repo = app()->make(ProjectRepository::class);
        $data = $repo->getPlayerData($id, true);
        
        // Format of new projects is data [ project => [] ] old data has the project
        // as the root of the data so we can just check for data->legacy and know if it's an old
        // or new project. New projects would be data->project->legacy for example.
        $legacy = (isset($data['legacy']));

        // Should fire the analytics or not
        $data[] = true;
        
        $publisher = app()->make(ProjectPublisher::class);
        return $publisher->view($data, false, $playerEnv);
    }


    /**
     * Public facing API allows users to  lookup there own projects via the api
     * key.
     *
     * @param Request $request
     * @return false|string
     */
    public function api(Request $request)
    {
        if (!$request->has('api_key')) abort(401, 'Missing api key');

        $user = User::where('api_key', $request->api_key)->first();
        if (!$user) abort(401, 'No user with that key');

        $projects = Project::where('user_id', $user->id)->get();

        $result = [];
        foreach ($projects as $project) {
            $result[] = [
                'name' => $project->title,
                'id' => $project->id,
                'path' => $project->storage_path,
                'height' => $project->embed_height,
                'width' => $project->embed_width
            ];
        }

        return json_encode($result);
    }

    public function player($id)
    {
        $projectRepository = app()->make(ProjectRepository::class);
        $data =  $projectRepository->getPlayerData($id, true);

        return [
            'nodes' => $data[0],
            'modals' => $data[1],
            'videos' => $data[2],
            'project' => $data[3],
            'sharePageUrl' => $data[4]
        ];
    }
}
