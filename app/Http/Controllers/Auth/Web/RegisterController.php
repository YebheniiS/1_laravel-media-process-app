<?php

namespace App\Http\Controllers\Auth\Web;

use App\JvzooPayment;
use App\Http\Controllers\Controller;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use App\Models\User;


class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
//    protected function validator(array $data)
//    {
//        return Validator::make($data, [
//            'name' => 'required|string|max:255',
//            'email' => 'required|string|email|max:255|unique:users',
//            'password' => 'required|string|min:6|confirmed',
//        ]);
//    }

    protected function create(Request $request)
    {
        // TODO: validate data
        $data = $request->all();

        $paymentModel = new JvzooPayment();

        $emailCheck =  User::where('email', $data['email'])->first();
        if($emailCheck) {
            return $this->error('Email already in use');
        }

        $payment = JvzooPayment::where('transaction_id', $data['transaction_id'])->first();

        if($payment){
            if($payment->refunded) {
                return $this->error('This transaction ID has been refunded');
            }

            if($payment->user_id){
                $user = User::find($payment->user_id);
                $error = ($user) ? 'Transaction ID used by user: ' . $user->email . ' please go back to the login screen to reset password' : 'Transaction ID already used';
                return $this->error($error);
            };

        } else {
            $getProductId = $this->checkPaymentId($data['transaction_id'], $paymentModel);

            if(! $getProductId['success']){
                return $this->error($getProductId['message']);
            }

            $payment = JvzooPayment::create([
                'transaction_id' => $data['transaction_id'],
                'product_id' => $getProductId['product_id'],
                'amount' => $getProductId['price'],
                'affiliate' => 0,
                'email' => strtolower( $data['email'] )
            ]);
        }

        if(! in_array($payment->product_id, array_keys($paymentModel->feProductIds))){
            return $this->error('Incorrect Product ID on transaction');
        }

        $newUserData = [
            'name' => $data['name'],
            'email' => strtolower( $data['email'] ),
            'password' => bcrypt($data['password']),
        ];

        $productName = $paymentModel->feProductIds[$payment->product_id];
        $productDefaults = $paymentModel->productActions[$productName];

        $newUserData = array_merge($newUserData, $productDefaults['upgrade']);

        try {
            $user = User::create($newUserData);

            $hasUpgrades = JvzooPayment::whereIn('product_id', array_keys($paymentModel->upgradeProductIds) )
                                        ->where('email', $user->email)
                                        ->whereNull('user_id')
                                        ->whereNull('refunded')
                                        ->get();

            if(count($hasUpgrades)){
                foreach($hasUpgrades as $upgrade){
                    $paymentModel->upgradeUser([
                        'ccustemail' => $user->email,
                        'cproditem' => $upgrade->product_id
                    ]);
                    $upgrade->user_id = $user->id;
                    $upgrade->save();
                }
            }

            $payment->user_id = $user->id;
            $payment->save();

            $user->fresh();

            $token = $this->JWTAuth->fromUser($user);

            return response()->json([
                'token' => $token,
                'user' => $user,
                'success'  => true
            ]);
        } catch (QueryException $e) {
            return response()->json([
                'success' => false,
                'message' => (env('APP_ENV')==='local')  ? $e->getMessage() : 'Error, Please contact support @ support@vidoesuite.io'
            ],500);
        }

    }


    private function checkPaymentId($paymentId, $model)
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
            if ($response->results[0]->status === 'COMPLETED' || $response->results[0]->status === 'INCOMPLETE') {
                $productIds = array_keys($model->feProductIds);

                if(in_array($response->results[0]->product_id, $productIds)) {
                    return [
                        'success' => true,
                        'product_id' => $response->results[0]->product_id,
                        'price' => $response->results[0]->price,
                    ];
                }

                return [
                    'success' => false,
                    'message' => 'Transaction ID is from a different product',
                ];

            }

            return [
              'success' => false,
              'message' => 'Payment not completed for this transaction'
            ];

        }catch(\Exception $e) {
            return [
                'success' => false,
                'message' => 'Transaction ID not found'
            ];
        }
    }

    protected function error($error){
        return response()->json([
            'success' => false,
            'message' =>  $error
        ], 500);
    }
}
