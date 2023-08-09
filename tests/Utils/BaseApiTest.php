<?php
namespace Tests\Utils;

use Tests\TestCase;

/**
 * All API routes need testing on the api domains so we create a base test here that runs a given
 * test against all domains
 */
class BaseApiTest extends TestCase {
    protected function getApiDomains()
    {
        return  array_values( config('domains.api') );
    }

    protected function apiTest($path, $test)
    {
        foreach ($this->getApiDomains() as $domain){
            $test('https://' . $domain . $path);
        }
    }
}