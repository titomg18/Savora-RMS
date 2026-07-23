<?php

namespace Tests\Feature;

use Tests\TestCase;

class ExampleTest extends TestCase
{
    public function test_root_route_shows_login_page(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Welcome back');
        $response->assertSee('Sign in');
    }
}
