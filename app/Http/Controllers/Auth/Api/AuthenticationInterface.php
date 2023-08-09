<?php

namespace App\Http\Controllers\Auth\Api;

/**
 * This interface must be implemented for any authentication controllers using the base AuthenticationController
 */
interface AuthenticationInterface
{
    /**
     *
     * @return mixed
     */
    public function authenticate();

    /**
     *
     * @return mixed
     */
    public function logout();
}