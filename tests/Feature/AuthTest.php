<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testIndex()
    {
        $response = $this->get('/api');

        $response->assertStatus(200);
    }

    public function testUserInfoWithoutLogin() {
        $response = $this->json('POST', '/api/user');

        $response->assertStatus(401);
    }

    public function testUserLogoutWithoutToken() {
        $response = $this->json('POST', '/api/logout');

        $response->assertStatus(401);
    }

    public function testTest() {
        $response = $this->json('POST', '/api/user', ['']);
        $response->assertStatus(401);
    }
}
