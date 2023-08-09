<?php

namespace App\Models;

use App\Repositories\ProjectRepository;
use App\Scopes\UserScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use mysql_xdevapi\Exception;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Laravel\Sanctum\HasApiTokens;
use App\UserLogin;
use App\Agency;
use App\UsagePlans;
use App\Services\AnalyticsApi;
class User extends Authenticatable 
{
    use Notifiable;
    use HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'integration_aweber' => 'array',
        'integration_zapier' => 'array',
        'integration_sendlane' => 'array',
        'integration_activecampaign' => 'array',
        'integration_mailchimp' => 'array',
        'integration_getresponse' => 'array',
        'integration_youzign' => 'array',
        'projects' => 'json'
    ];

    protected static function booted()
    {
        // Delete all related models when delete user
        static::deleting(function ($user) {
            // Delete user related agency
            $user->agency()->delete();
        });

        static::updating(function ($user) {
            if ($user->gravatar) unset($user->gravatar);
        });

        // If user is creating in AGENCY page update parent_user_id for both admins and agency users
        static::creating(function ($user) {
            if ($user->agency_page) {
                $user->parent_user_id = auth()->id();
            } else {
                $user->upgraded = 1;
            }

            unset($user->agency_page);
        });

        // When a new user is created, create a template project automatically
        static::created(function ($user) {
            try {
                if($user->parent_user_id == 0) {
                    $repo = app()->make(ProjectRepository::class);
                    $repo->createExampleProject($user);
                }

                // $interactrId = AccessLevel::where('name', 'interactr')->pluck('id')->first();
                // $accessLevelUser = new AccessLevelUser();
                // $accessLevelUser->user_id = $user->id;
                // $accessLevelUser->access_level_id = $interactrId;
                // $accessLevelUser->save();

            }catch(\Exception $e){
                // Ensure if this breaks the create user still continues
                app('honeybadger')->notify($e, app('request'));
            }

        });
    }

    /**
     * If FE queries to get user data, we send empty string as 'password' field for security reasons
     * @return string
     */
    public function getPassword()
    {
        return '';
    }

    /**
     * adds a subusers prop to the graphql response with all the
     * users subusers
     *
     * @return mixed
     */
    public function getSubUsers()
    {
        return $this->where('parent_user_id', auth()->id())->get();
    }

    /**
     * returns the most recent login for this user
     *
     * @return mixed
     */
    public function lastLogin()
    {
        $lastLogin =  UserLogin::where('user_id', auth()->id())->latest()->get()->first();

        return ($lastLogin) ? $lastLogin->created_at : null;
    }


    /**
     * JWT required conf methods
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    /**
     * User to agency relationship
     * @return HasOne
     */
    public function agency(): HasOne
    {
        return $this->hasOne(Agency::class);
    }

    /**
     * get parent user instance
     * @return |null
     */
    public function getParentUser()
    {
        if($this->parent_user_id){
            return User::findOrFail($this->parent_user_id);
        }
        return null;
    }
    public function getUsagePlan()
    {  
        if($this->usage_plan_id){
            return UsagePlans::findOrFail($this->usage_plan_id);
        }
        return null;
    }
    public function getUserPlanUsed(){
        $analyticsApi = new AnalyticsApi();
        $isStorageLeft = $analyticsApi->getStorageUsed(auth()->id());
        return ["used_storage" => $isStorageLeft, "used_mins" => 0];
    }
    public function searchableAs()
    {
        return ['name', 'email'];
    }

    /**
     * User to liked templates relationship
     * @return BelongsToMany
     */
    public function likedTemplates() : BelongsToMany
    {
        return $this->belongsToMany(Project::class, 'template_likes', 'user_id','template_id')->withoutGlobalScope(UserScope::class);
    }

    public function userLogins()
    {
        return $this->hasMany(UserLogin::class);
    }
    
    public function templatesUsed()
    {
        return $this->hasMany(TemplatesUsed::class);
    }

    public function access()
    {
        return $this->belongsToMany(AccessLevel::class);
    }

    public function brandings()
    {
        return $this->hasMany(Branding::class);
    }

    public function projectNotes()
    {
        return $this->hasMany(ProjectNote::class);
    }

    public function plan() : BelongsTo
    {
        return $this->belongsTo('App\UsagePlans', 'usage_plan_id');
    }
    
}
