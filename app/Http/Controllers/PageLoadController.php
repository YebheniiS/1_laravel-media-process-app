<?php

namespace App\Http\Controllers;

//use App\Repositories\ProjectRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PageLoadController extends Controller
{
    protected $nodeController;
    protected $mediaController;
    protected $projectRepository;
    protected $userController;
//    protected $projectGroupController;
//    protected $agencyController;
    protected $customListsController;
    protected $templateLanguagesController;

    //
    public function __construct(
//        ProjectRepository $projectRepository,
        NodeController $nodeController,
        MediaController $mediaController,
        UserController $userController,
//        ProjectGroupController $projectGroupController,
//        AgencyController $agencyController,
//        CustomListsController $customListsController,
        TemplateLanguagesController $templateLanguagesController
    )
    {
//        $this->projectRepository = $projectRepository;
        $this->nodeController = $nodeController;
        $this->mediaController = $mediaController;
        $this->userController = $userController;
//        $this->projectGroupController = $projectGroupController;
//        $this->agencyController = $agencyController;
//        $this->customListsController = $customListsController;
        $this->templateLanguagesController = $templateLanguagesController;
    }

    /**
     * Provides all the data the FE needs on pageload. This is simply a wrapper around
     * the original get data routes to prevent multiple http requests
     */
    public function index(){
        return true;
        try {
//            $projectsData = $this->projectRepository->getProjectsForUser(false);
//            $projects = $projectsData['projects'];
//            $projectGroups = $projectsData['projectGroups'];
            $media = $this->mediaController->index();
            $users = $this->userController->index();
//            $customLists = $this->customListsController->get(true);
            $templateLanguages = $this->templateLanguagesController->index()->collection;

            $nodes = [];
            if($projects && count($projects)){
                foreach($projects as $key => $project){
                    $projectNodes = $this->nodeController->forProject($project->id)->toArray();
                    $nodes = array_merge($nodes , $projectNodes);
                }
            }

//            $agency = (Auth::user()->is_agency) ? $this->agencyController->create() : [];

            return response(compact(
//                'projectGroups' ,
//                'projects',
                'nodes',
                'users',
                'media',
//                'agency',
//                'customLists',
                'templateLanguages'
            ), 200);
        }catch(\Exception $exception){
            return response([
                'status' => 'error',
                'message' => $exception->getMessage()
            ], 500);
        }

    }
}
