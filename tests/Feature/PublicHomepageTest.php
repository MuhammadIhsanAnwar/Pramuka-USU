<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicHomepageTest extends TestCase
{
    use RefreshDatabase;

    public function test_homepage_is_accessible(): void
    {
        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('Pramuka USU', false);
    }
}