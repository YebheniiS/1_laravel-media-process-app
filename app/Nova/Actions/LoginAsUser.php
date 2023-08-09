<?php

namespace App\Nova\Actions;

use App\Http\Controllers\Auth\Api\InteractrAuthenticationController;
use App\Http\Controllers\Auth\Api\VideobubbleAuthenticationController;
use App\Models\AdminLoginAsUserTokens;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\App;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Fields\Select;
use Illuminate\Support\Facades\Auth;

class LoginAsUser extends Action
{
    use InteractsWithQueue, Queueable;

    /**
     * The displayable name of the action.
     *
     * @var string
     */
    public $name = 'Login As User';

    protected $controller;

    /**
     * Perform the action on the given models.
     *
     * @param  \Laravel\Nova\Fields\ActionFields  $fields
     * @param  \Illuminate\Support\Collection  $models
     * @return mixed
     */
    public function handle(ActionFields $fields, Collection $models)
    {
        foreach($models as $user) {
            // $token = (new AdminLoginAsUserTokens())->create([
            //     'user_id' => $user->id
            // ]);

            // $url = $this->getLoginUrl($fields['select_app']);

            // return Action::openInNewTab($url . '?token=' . $token->token);

            $url = $this->getLoginUrl($fields['select_app']);
            Auth::guard('web')->loginUsingId($user->id);
            $token = $user->createToken("interactr");
            return Action::openInNewTab($url . '?token=' . $token->plainTextToken);
        }
    }

    /**
     * Get the fields available on the action.
     *
     * @return array
     */
    public function fields()
    {
        return [
            Select::make('Select App')->options([
                'interactr' => 'Interactr',
                'videobubble' => 'VideoBubble',
                'videosuite' => 'Videosuite'
            ]),
        ];
    }

    protected function getLoginUrl($app)
    {
        switch($app) {
            case('interactr'):
                return env('INTERACTR_APP_URL')."/login";
            case('videobubble') :
                return "https://google.com";
            default :
                throw new \Exception('Unknown app');
        }
    }
}
