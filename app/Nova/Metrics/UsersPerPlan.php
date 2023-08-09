<?php

namespace App\Nova\Metrics;

/*use App\Models\AccessLevel;
use App\Models\AccessLevelUser;*/
use App\Models\User;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Metrics\Partition;

class UsersPerPlan extends Partition
{
    /**
     * Calculate the value of the metric.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return mixed
     */
    public function calculate(NovaRequest $request)
    {

        /*---------------------Updated by Yuri on 15-6-2023-------------------------------*/
        return $this->result([
            'Interactr' => User::where('is_pro', 0)->where('is_agency_club', 0)->distinct('user_id')->get()->count(),
            'Interactr Pro' => User::where('is_pro', 1)->where('is_agency_club', 0)->distinct('user_id')->get()->count(),
            'Interactr Agency' => User::where('is_pro', 0)->where('is_agency_club', 1)->distinct('user_id')->get()->count(),
            'Interactr Pro & Agency' => User::where('is_pro', 1)->where('is_agency_club', 1)->distinct('user_id')->get()->count()
        ]);
    }

    /**
     * Determine for how many minutes the metric should be cached.
     *
     * @return  \DateTimeInterface|\DateInterval|float|int
     */
    public function cacheFor()
    {
        // return now()->addMinutes(5);
    }

    /**
     * Get the URI key for the metric.
     *
     * @return string
     */
    public function uriKey()
    {
        return 'users-per-plan';
    }
}
