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
        $response = $this->post('/api/user');

        $response->assertStatus(302);
    }

    public function testUserLogoutWithoutToken() {
        $response = $this->post('/api/logout');

        $response->assertStatus(302);
    }

    public function testTest() {
        $response = $this->json('POST', '/api/user', ['']);
        $response->assertStatus(401);
    }
}
// 302 was return cuz of HTTP instead of AJAX
