<?php

namespace App\Http\Controllers;

use App\Lib\ScreenshotAPI;
use App\Project;
use App\Models\User;
use Illuminate\Http\Request;

class ShareController extends Controller
{
    public function like(Request $request, $hash){
        $project = Project::where('storage_path', 'projects/'.$hash)->with('comments')->first();
        $project->likes++;
        $project->save();

        return [
            'likes' => $project->likes
        ];
    }

    public function unlike(Request $request, $hash){
        $project = Project::where('storage_path', 'projects/'.$hash)->with('comments')->first();
        $project->likes--;
        $project->save();

        return [
            'likes' => $project->likes
        ];
    }

    public function screenshot()
    {
        $url = request()->get('share_page_url');
        $projectId = request()->get('project_id');
        $project = Project::query()->find( $projectId );

        try {
            $screenshotAPI = new ScreenshotAPI();
            $src = $screenshotAPI->make($url, 'png')->uploadToBunnyCDN();

            $project->update([ 'share_page_screenshot' => $src ]);

            return response()->json(compact('project'));
        } catch (\Exception $exception) {
            return response()->json(['error' => $exception->getMessage()], 400);
        }
    }
}
