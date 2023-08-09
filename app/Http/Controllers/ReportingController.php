<?php

namespace App\Http\Controllers;

use App\Project;
use App\Scopes\UserScope;
use App\TemplatesUsed;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class ReportingController extends Controller
{
    //
    public function templatesUsedReport()
    {
        if(! auth()->user()->superuser) return null;

        $query = "SELECT COUNT(project_id), project_id FROM templates_used  GROUP BY project_id";

        $result = DB::select(DB::raw($query));

        $return = [];

        foreach($result as $r){
            $project = Project::withoutGlobalScope(UserScope::class)->where('id', $r->project_id)->first();

            $return[] = [
                'name' => $project->template_name,
                'data'=>  $r->{'COUNT(project_id)'}
            ];
        }

        return $return;
    }

    public function getUserLastLogins()
    {
//        if(! auth()->user()->superuser) return null;

        $to = Carbon::now()->addDays(1);
        $from = Carbon::now()->subYears(1);

        $query = "SELECT  COUNT(id), DATE_FORMAT(created_at, '%m-%y') FROM user_logins WHERE created_at BETWEEN '" . $from->format('Y-m-d') . "' AND '". $to->format('Y-m-d') ."' GROUP BY DATE_FORMAT(created_at, '%m-%y')";

        $result = DB::select(DB::raw($query));

        $data = [];

        $dateKey = "DATE_FORMAT(created_at, '%m-%y')";

        $countKey = 'COUNT(id)';

        foreach ($result as $key => $value) {
            $data[$value->{$dateKey}] = $value->{$countKey};
        }

        do {
            $group = [
                // If this group isn't set we just return it with a 0
                'count' => (isset($data[$from->format('m-y')])) ? $data[$from->format('m-y')] : 0,
                'month' => $from->format('m-y'),
            ];

            $from = $from->addMonth(1);
            $group['end_date'] = $from->format('m-y');

            $return[] = $group;

        } while ($from <= $to);


        return $return;
    }
}
