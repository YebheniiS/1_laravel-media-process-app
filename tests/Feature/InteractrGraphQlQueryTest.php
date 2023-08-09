<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Nuwave\Lighthouse\Testing\ClearsSchemaCache;
use Nuwave\Lighthouse\Testing\MakesGraphQLRequests;
use Tests\TestCase;

class InteractrGraphQlQueryTest extends TestCase
{
    use MakesGraphQLRequests;
    use ClearsSchemaCache;

    protected function setUp(): void
    {
        parent::setUp();
        //$this->bootClearsSchemaCache();
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_projects_query()
    {
        // First we need to authencticate the user
        $login = $this->post('https://' . config('domains.api.interactr') . '/auth/interactr/authenticate', [
            'email' => InteractrAuthenticateUserTest::$TEST_USER_EMAIL,
            'password' => InteractrAuthenticateUserTest::$TEST_USER_PASSWORD
        ]);

        $token = $login['token'];

        $response = $this->withHeaders([
            'authorization' => 'Bearer ' . $token
        ])->graphQL(/** @lang GraphQL */ '{
            allProjects(app: "interactr") {
                id
                title
            }
        }');

        $response->assertStatus(200)->assertJson([
            'data' => true,
        ]);
    }
}
