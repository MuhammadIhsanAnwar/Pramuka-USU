<?php

namespace Tests\Feature;

use Tests\TestCase;

class AdminRouteTest extends TestCase
{
    public function test_admin_route_redirects_to_login_page_for_guests(): void
    {
        $response = $this->get('/admin');

        $response->assertStatus(302);
        $response->assertRedirectContains('/admin/login');
    }
}
