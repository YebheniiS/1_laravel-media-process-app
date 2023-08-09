<?php

namespace App\Http\Controllers\Integrations;

/**
 * Should be implemented by any third party Integration Controllers that
 * extend the Integration Controller
 */
interface IntegrationInterface
{
    /**
     * Can process a refund
     * @return mixed
     */
    public function refund(array $data, $product);

    /**
     * Can process a sale
     * @return mixed
     */
    public function sale(array $data, $product);

    /**
     * Entry point for the API call
     * @return mixed
     */
    public function index();

    public function cancel(array $data, $product);
}