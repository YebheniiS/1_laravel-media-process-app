<?php

namespace Tests\Feature;

use App\Models\Page;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SalesPagesTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_pages_ok()
    {
        $pages = Page::with('domain', 'funnel')->get();

        foreach ($pages as $page) {
            $url = 'https://' . $page->domain->domain_name . '/' . $page->funnel->url . '/' . $page->url;
            $response = $this->get($url);
            $response->assertStatus(200);
        };
    }
}
