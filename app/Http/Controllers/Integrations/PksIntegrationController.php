<?php

namespace App\Http\Controllers\Integrations;

use App\PksProduct;
use Illuminate\Http\Request;
use App\Models\User;

class PksIntegrationController extends IntegrationController implements IntegrationInterface
{
    /**
     * Entry point for all PKS Webhooks
     *
     * @return string
     * @throws \Exception
     */    
    public function index()
    {
        // Check we have all the data we need from the webhook
        $data = request()->validate([
            'event' => 'required',
            'product_id' => 'required',
            'buyer_email' => 'required',
            'amount' => '',
            'transaction_id' => '',
            'buyer_first_name' => '',
            'buyer_last_name' => ''
        ]);

        $product = $this->getProduct($data['product_id']);

        // Fire the correct event
        switch( $data['event'] ) {
            case('sales') :
                $this->sale( $data, $product );
                break;
            case('subscription-cancelled') :
                $this->cancel( $data, $product );
                break;
            case('refund') :
                $this->refund( $data, $product );
                break;
            default :
                throw new \Exception('Unknown event type');
        }

        return 'success';
    }


    /**
     * Refund a sale
     *
     * @param array $data
     * @return bool
     * @throws \Exception
     */
    public function refund(array $data, $product) : bool
    {
        $this->handleCancel([
            $data['buyer_email'],
            $product
        ]);

        return true;
    }


    /**
     * Process a new sale
     *
     * @param array $data
     * @return bool
     * @throws \Exception
     */
    public function sale(array $data, $product) : bool
    {
        $this->handleSale([
            $data['buyer_email'],
            $product,
            $data['buyer_first_name'] . ' ' . $data['buyer_last_name']
        ]);

        return true;
    }

    public function cancel(array $data, $product) : bool
    {
        $this->handleCancel([
            $data['buyer_email'],
            $product
        ]);

        return true;
    }

    /**
     * Get the product from the product access library
     *
     * @param int $productId
     * @return PksProduct
     * @throws \Exception
     */
    private function getProduct(int $productId) : PksProduct
    {
        $product = PksProduct::where('product_id', $productId)->first();

        if(! $product) throw new \Exception('Unknown product id: ' . $productId);

        return $product;
    }
}
