<?php

namespace App\Http\Controllers\Integrations;

use App\UsagePlans;
use App\PksProduct;
/*use App\Models\AccessLevel;*/
use App\Http\Controllers\Controller;
use App\JvzooPayment;
use App\Mail\NewUserWelcome;
use App\Payment;
use App\Models\User;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Services\AnalyticsApi;

class IntegrationController extends Controller
{
    // These products are the base, so they can't be
    // downgrades or upgrades and users will receive a welcome
    // email when attached one of these
    protected const BASE_PRODUCTS = [
        'interactr', 'videobubble'
    ];

    /**
     * Process a new Sale
     * @return array
     */
    public function handleSale(array $args): array
    {
        [$email, $product, $name] = $args;

        [$user, $password, $created] = $this->getOrCreateUser($email, $name);

        if ($created){
            $this->sendNewUserEmail($user, $password);
        }

        // If the product is agency
        if($product->is_agency) {
            $user->is_agency = 1;
        } else {
            // Check if a user has purchased lower level product first when purchased from facebook
            $user->usage_plan_id = $product->usage_plan_id;
            $user->is_pro = $product->is_pro;
        }
        
        $user->save();
        // $user = $this->attachProductToUser($user, $product);

        // if($product->ac_list_id) {
        //     $this->addUserToAutoResponder($product->ac_list_id, $email);
        // }

        return [$user, $password];
    }

    public function handleCancel(array $args)
    {
        [$email, $product] = $args;

        $user = User::where('email', $email)->first();
        if($product->is_agency) {
            $user->is_agency = 0;
        } else {
            if($product->downgrade_id) {
                $pksProduct = PksProduct::where('product_id', $product->downgrade_id)->first();
                $user->usage_plan_id = $pksProduct->usage_plan_id;
                $user->is_pro = $pksProduct->is_pro;
            } else {
                $user->usage_plan_id = 0;
            }
        }
        
        $user->save();        
    }

    /**
     * Emits a new user welcome email event
     *
     * @param User $user
     * @param string $password
     */
    private function sendNewUserEmail(User $user, string $password)
    {
        Mail::to($user)->send(new NewUserWelcome($user, $password));
    }


    /**
     * Add The New User To Our List
     *
     */
    private function addUserToAutoResponder(int $listId, string $email)
    {
        try {
            $url = 'http://api.videosuite.io/list/add/'.$listId.'/' . strtolower($email);
            file_get_contents($url);
        }catch(\Exception $e){}
    }


    /**
     * If the request is to add a feature to an account
     * do that here
     */
    private function attachProductToUser( User $user, $product) : User
    {
        if(!  $user->access->contains($product->access_level_id)) {
            $user->access()->attach($product->access_level_id);
        }

        return $user;
    }


    /**
     * Process a new Refund
     */
    public function handleRefund(array $args) : bool
    {
        [$email, $product] = $args;

        $user = User::where('email', $email)->first();
        if($product->is_agency) {
            $user->is_agency = 0;
        } else {
            if($product->downgrade_id) {
                $pksProduct = PksProduct::where('product_id', $product->downgrade_id)->first();
                $user->usage_plan_id = $pksProduct->usage_plan_id;
                $user->is_pro = $pksProduct->is_pro;
            } else {
                $user->usage_plan_id = 0;
            }
        }
        
        $user->save();

        return true;
    }


    /**
     * Create a new user
     */
    private function getOrCreateUser( string $email, string $name ): array
    {
        $user = User::where('email', $email)->first();
        $password = false;
        $created = false;
        if(! $user ){
            $password = $this->generatePassword();

            $user = User::create([
                'name' => $name,
                'email' => $email,
                'password' => Hash::make($password),
            ]);

            $created = true;
        }

        return [$user, $password, $created];
    }


    /**
     * Generate a random
     * password for the user
     *
     * @return mixed
     */
    private function generatePassword()
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        $length = 5;
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

}
