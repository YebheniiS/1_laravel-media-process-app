<?php

namespace Tests\Feature;

use App\Models\User;
use App\UserLogin;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Nuwave\Lighthouse\Testing\MakesGraphQLRequests;
use Tests\Utils\BaseApiTest;

class InteractrAuthenticateUserTest extends BaseApiTest
{
    protected $token;

    public static $TEST_USER_EMAIL = 'testuser@logintests.com';
    public static $TEST_USER_PASSWORD = 'argwragewgwave';

    public function test_incorrect_password_login_attempt()
    {
        $path = '/auth/interactr/authenticate';

        $this->apiTest($path, function($url){
            $response = $this->post( $url, [
                'email' => self::$TEST_USER_EMAIL,
                'password' => '123456'
            ]);

            $response->assertStatus(401)->assertJson([
                'message' => 'Invalid email or password',
            ]);
        });
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_authenticate_user()
    {
        $path = '/auth/interactr/authenticate';

        $this->apiTest($path, function($url){
            $response = $this->post($url, [
                'email' => self::$TEST_USER_EMAIL,
                'password' => self::$TEST_USER_PASSWORD
            ]);

            $response->assertStatus(200)->assertJson([
                'token' => true,
                'user' => true
            ]);
        });
    }

    public function test_last_login_updated()
    {
        $lastLogin = UserLogin::latest()->first();
        $testUser = User::where('email', self::$TEST_USER_EMAIL)->first();

        $this->assertTrue(
            $lastLogin->user_id === $testUser->id
        );
    }
}
