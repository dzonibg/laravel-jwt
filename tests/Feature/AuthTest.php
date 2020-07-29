<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\User;

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

    public function testUserCreation() {
        $user = factory(User::class)->make();
        $user->save();
        $this->assertDatabaseHas('users', [
            'name' => $user->name,
            'email' => $user->email
        ]);
    }

    public function testUserLogin() {
        $user = factory(User::class)->make();
        $user->save();
        $response = $this->json('POST', '/api/login', [
            'email' => $user->email,
            'password' => 'password'
        ]);
        $response->assertStatus(200);
        $response->assertJson([
            'token_type' => 'bearer',
            'access_token' => $response->json('access_token'),
        ]);
    }

    public function testLoggedInUserInfo() {
        $user = factory(User::class)->make();
        $user->save();
        $response = $this->json('POST', '/api/login', [
            'email' => $user->email,
            'password' => 'password'
        ]);
        $this->actingAs($user)->json('POST', '/api/user')->assertStatus(200);
    }
}
