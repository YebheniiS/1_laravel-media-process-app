<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JvzooPayment extends Model
{
    //
    protected $fillable = [
        'transaction_id', 'product_id', 'amount', 'email', 'affiliate'
    ];

    public $upgradeProductIds = [
        '348083' => 'evolution_agency', // Agency Monthly
        '348081' => 'evolution_agency', // Agency Yearly
        '348075' => 'pro', // Pro Edition
    ];

    public $feProductIds = [
        '347951' => 'evolution',
        '349251' => 'lite'
    ];

    public $productActions  = [
        'evolution_agency' => [
          'upgrade' => [
              'evolution_club' => 1,
              'is_agency' => 1
          ],
          'downgrade' => [
              'evolution_club' => 0,
              'is_agency' => 0
          ]
        ],
        'pro' => [
            'upgrade' => [
                'evolution_pro' => 1,
            ],
            'downgrade' => [
                'evolution_pro' => 0,
            ]
        ],
        'evolution' => [
            'upgrade' => [
                'evolution' => 1,
            ],
        ],
        'lite' =>  [
            'upgrade' => [
                'evolution' => 1,
                'max_projects' => 3
            ],
        ]
    ];

    public function sale($data){
        $payment = $this->savePayment($data);

        if( $this->isUpgrade($data['cproditem']) ){
            $user = $this->upgradeUser($data);
            $payment->user_id = $user->id;
            $payment->save();
        }
    }

    public function refund($data){
        $payment = $this->where('transaction_id', $data['ctransreceipt'])->first();
        $payment->refunded = 1;
        $payment->save();

        if($payment->user_id) {
            if( $this->isUpgrade($data['cproditem']) ){
                $this->downgradeUser($data, $payment);
            }else {
                $this->deleteUser($data, $payment);
            }
        }
    }

    protected function downgradeUser($data, $payment){
        $user = User::findOrFail($payment->user_id);

        if($user) {
            $action = $this->getActions($data['cproditem']);

            foreach($action['downgrade'] as $key => $value) {
                $user->{$key} = $value;
            }

            $user->save();
        }
    }

    protected function deleteUser($data, $payment){
        User::destroy($payment->user_id);
        // TODO delete all projects media nodes etc, Also delete all files from s3
    }

    protected function savePayment($data){
        return $this->firstOrCreate([
            'transaction_id' => $data['ctransreceipt']
        ],[
            'product_id' => $data['cproditem'],
            'amount' => $data['ctransamount'],
            'email' =>  strtolower( $data['ccustemail'] ),
            'affiliate' => isset($data['ctransaffiliate']) ? $data['ctransaffiliate'] : 0
        ]);
    }

    public function upgradeUser($data){
        $user = User::where('email', strtolower( $data['ccustemail'] ))->first();

        if($user) {
            $action = $this->getActions($data['cproditem']);

            foreach($action['upgrade'] as $key => $value) {
                $user->{$key} = $value;
            }

            $user->save();
        }

        return $user;
    }

    protected function isUpgrade($productId){
        return (in_array($productId, array_keys( $this->upgradeProductIds )));
    }

    protected function getActions($productId){
        $product = $this->upgradeProductIds[$productId];
        return $this->productActions[$product];
    }
}
