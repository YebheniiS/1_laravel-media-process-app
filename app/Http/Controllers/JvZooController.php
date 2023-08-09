<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class JvZooController extends Controller
{
    //
    protected $UserController;

    public function __construct(UserController $userController)
    {
        $this->UserController = $userController;
    }

    public function checkEmail(Request $request){
        if ($request->has('email')){
            $user = User::where('email', $request->input('email'))->first();
            if(! $user){
                return view('register.checkTransactionId')->with('email', $request->input('email'));;
            }

            return view('register.userExists');
        }

        dd('error');
    }

    public function checkTransactionId(Request $request){
        $data = $this->checkPaymentId($request->get('transaction_id'));
        return view('register.password')
                    ->with('productId', $data['product'])
                    ->with('email', $data['email']);
    }

    private function checkPaymentId($paymentId)
    {
        $api_key = '0aa1299ce4e7e19896003aca40d29c69d73d956c44295f014ea909a09d6ffe48';
        $ch = curl_init("https://api.jvzoo.com/v2.0/transactions/summaries/" . $paymentId);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($ch, CURLOPT_HEADER, TRUE);
        curl_setopt($ch, CURLOPT_USERPWD, $api_key . ":" . 'x');
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_POST, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        $response = curl_exec($ch);
        $info = curl_getinfo($ch);
//        $headers = substr($response, 0, $info['header_size']);
        $body = substr($response, -$info['download_content_length']);
        $response = json_decode($body);

        // Check payment was completed
        try  {
            if ($response->results[0]->status === 'COMPLETED'){
                $productIds = [
                    '289489','291514'
                ];

                if (in_array($response->results[0]->product_id, $productIds)) {
                    return [
                        'product' => $response->results[0]->product_id,
                        'email'=> $response->results[0]->paypal_email
                    ];
                }
            } ;
        }catch(\Exception $e) {
            dd('Payment ID Not Found');
        }

        dd('Payment ID Not Completed For This Product');
    }

    public function createUser(Request $request){
      
        try {
            $data['name'] = $request->get('email');
            $data['email'] = $request->get('email');
            $data['password'] = $request->get('password');
            $data['role'] = 'user';

            if($request->get('productId') === 291514){
                $data['advanced_analytics'] = 0;
                $data['max_projects'] = 3;
            } else {
                $data['advanced_analytics'] = 1;
            }

            $user = new User();
            foreach($data as $key => $value) {
                if (Schema::hasColumn($user->getTable(), $key)) {
                    if ($key === 'password') {
                        $value = Hash::make($value);
                    }
                    $user->{$key} = $value;
                };
            }
            $user->save();

            try {
                $url = 'http://api.videosuite.io/list/add/19/' . strtolower($data['email']);
                file_get_contents($url);
            }catch(\Exception $e){}
        }catch(\Exception $e){
            dd('Error');
        }
//chrisdjbell@sfgdsgdfgdg.com
        return View('register.success')->with('email',  $data['email'])->with('password',  $data['password']);
    }
}
