<?php

namespace App\Http\Controllers\Integrations;

use App\PksProduct;
use Illuminate\Http\Request;
use App\Models\User;

class ThriveCartIntegrationController extends IntegrationController implements IntegrationInterface
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
            'thrivecart_secret' => 'required',
            'base_product' => 'required',
            'customer' => 'required',
        ]);

        if($data['thrivecart_secret'] != env('THRIVECART_SECRET')) {
            throw new \Exception('Invalid thrivecart secret');
        }

        $product = $this->getProduct($data['base_product']);

        // Fire the correct event
        switch( $data['event'] ) {
            case('order.subscription_payment') :
                $this->sale( $data, $product );
                break;
            case('order.subscription_cancelled') :
                $this->cancel( $data, $product );
                break;
            case('order.refund') :
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
        $this->handleRefund([
            $data['customer']['email'],
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
            $data['customer']['email'],
            $product,
            $data['customer']['first_name'] . ' ' . $data['customer']['last_name']
        ]);

        return true;
    }

    public function cancel(array $data, $product) : bool
    {
        $this->handleCancel([
            $data['customer']['email'],
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

        // if(! $product->access) throw new \Exception('No access level for this product: ' . $productId);

        return $product;
    }
}
