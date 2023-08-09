<?php

namespace Tests\Utils;

use App\Models\User;
use App\PksProduct;
use Illuminate\Support\Facades\Config;
use Tests\TestCase;

class BaseIntegrationTest extends TestCase {

    const TEST_BUYER_EMAIL = 'testuser@unittest.com';
    const TEST_BUYER_FIRST_NAME = 'PHP Unit';
    const TEST_BUYER_LAST_NAME = 'test';

    protected function sale(array $args)
    {
        [$vendor, $event, $productId] = $args;

        $response = $this->post(
            $this->getWebhookUrl($vendor),
            $this->getWebhookParams($vendor, $event, $productId )
        );

        // Webhook returned no errors
        $response->assertStatus(200);

        // Check user was created in DB
        $this->assertDatabaseHas('users', [
            'email' => self::TEST_BUYER_EMAIL,
            'name' => self::TEST_BUYER_FIRST_NAME . " " . self::TEST_BUYER_LAST_NAME
        ]);

        $product = PksProduct::where('product_id', $productId)->first();
        $user = $this->getUser();

        if($product->is_agency) {
            $this->assertTrue($user->usage_plan_id != 0 && $user->is_agency == $product->is_agency);
        } else {
            $this->assertTrue($user->usage_plan_id == $product->usage_plan_id && $user->is_pro == $product->is_pro);
        }
    }

    protected function cancel(array $args)
    {
        [$vendor, $event, $productId] = $args;

        $response = $this->post(
            $this->getWebhookUrl($vendor),
            $this->getWebhookParams($vendor, $event, $productId )
        );

        // Webhook returned no errors
        $response->assertStatus(200);

        $product = PksProduct::where('product_id', $productId)->first();

        if(! $product->downgrade_id) {
            if(! $product->is_agency) {
                $this->assertEquals(0, $this->getUser()->usage_plan_id);
            } else {
                $this->assertEquals(0, $this->getUser()->is_agency);
                $this->assertFalse($this->getUser()->usage_plan_id == 0);
            }
        }
        else {
            $downgradedProduct = PksProduct::where('product_id', $product->downgrade_id)->first();
            $this->assertEquals($downgradedProduct->usage_plan_id, $this->getUser()->usage_plan_id);
        }
    }

    private function getWebhookUrl($vendor)
    {
        return 'https://' . config('domains.webhook') . '/' . config('webhooks.prefix')  . '/' . $vendor;
    }

    private function getWebhookParams($vendor, $event, $productId)
    {
        return  ($vendor==='pks') ? [
            'event' => $event,
            'buyer_email' => self::TEST_BUYER_EMAIL,
            'product_id' => $productId,
            'amount' => 1,
            'transaction_id' => 123,
            'buyer_first_name' => 'PHP Unit',
            'buyer_last_name' => 'Test'
        ] : [
            'ctransaction' => $event,
            'ccustemail' => self::TEST_BUYER_EMAIL,
            'cproditem' => $productId,
            'ctransamount' => 1,
            'ctransreceipt' => 123,
            'ccustname' => 'PHP Unit Test',
        ];
    }

    private function getUser() : User
    {
        return User::where('email', self::TEST_BUYER_EMAIL)->first();
    }

    /**
     * Tidy up the test by deleting the user
     */
    protected function deleteUser()
    {
        $user = User::where('email', self::TEST_BUYER_EMAIL)->first();

        User::destroy($user->id);

        return User::where('email', self::TEST_BUYER_EMAIL)->first();
    }
}