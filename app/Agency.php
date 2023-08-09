<?php

namespace App;

use App\Scopes\UserScope;
use GraphQL\Error\Error;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Agency extends Model
{
    //
    protected $table = 'agency';

    protected $guarded = [];

    protected static function booted()
    {
        static::addGlobalScope(new UserScope());
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }

    /**
     * Get the whitelabel theme for the current domain name
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public static function whitelabel($domain){
        return Agency::where('domain', $domain)->withoutGlobalScope(new UserScope())->first();
    }
}
