<?php

namespace App\Http\Controllers\Integrations;

use App\JvzooPayment;
use App\JvzooProduct;
use Illuminate\Http\Request;

class JvzooIntegrationController extends IntegrationController implements IntegrationInterface
{
    public function index()
    {
        // Check we have all the data we need from the webhook
        $data = request()->validate([
            'ctransaction' => 'required',
            'cproditem' => 'required',
            'ccustemail' => 'required',
            'ctransamount' => '',
            'ctransreceipt' => '',
            'ccustname' => '',
        ]);

        $product = $this->getProduct($data['cproditem']);

        // Fire the correct event
        switch( $data['ctransaction'] ) {
            case('SALE') :
                $this->sale( $data, $product );
                break;
            case('RFND') :
            case('CGBK') :
            case('CANCEL-REBILL') :
                $this->refund( $data, $product );
                break;
            default :
                throw new \Exception('Unknown event type');
        }

        return 'success';
    }



    public function sale(array $data, $product)
    {
        $this->handleSale([
            $data['ccustemail'],
            $product,
            $data['ccustname']
        ]);

        return true;
    }

    public function refund(array $data, $product)
    {
        $product = $this->getProduct($data['cproditem']);

        $this->handleRefund([
            $data['ccustemail'],
            $product
        ]);

        return true;
    }

    private function getProduct(int $productId) : JvzooProduct
    {
        $product = JvzooProduct::where('product_id', $productId)->with('access')->first();

        if(! $product) throw new \Exception('Unknown product id: ' . $productId);

        if(! $product->access) throw new \Exception('No access level for this product: ' . $productId);

        return $product;
    }
}
