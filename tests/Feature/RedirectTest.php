<?php

namespace Tests\Feature;

use App\Models\Redirect;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RedirectTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_redirects_return_302()
    {
        $redirects = Redirect::all();

        foreach($redirects as $redirect ){
            $response = $this->get($redirect->from);

            $response->assertStatus(302);
        }
    }

    public function test_redirects_to_destination_with_params()
    {
        $redirects = Redirect::all();

        foreach($redirects as $redirect ){
            $response = $this->get($redirect->from . '?campaign_name=test&');

            $response->assertRedirect($redirect->to . '?campaign_name=test&');
        }
    }
}
