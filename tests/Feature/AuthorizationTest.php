<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Tymon\JWTAuth\JWTAuth;

class AuthorizationTest extends TestCase
{


    use RefreshDatabase;

    public function testUserRegistration() {
        $user = factory(User::class)->create();
        $this->assertDatabaseHas('users', [
            'name' => $user->name,
            'email' => $user->email
        ]);

        return $user;
    }


    public function testUserLogin() {
        $user = factory(User::class)->create();
        $response = $this->json('POST', '/api/login',[
            'email' => $user->email,
            'password' => 'password'
        ]);
        $this->assertDatabaseHas('users', [
            'name' => $user->name,
            'email' => $user->email
        ]);
//        var_dump(\JWTAuth::fromUser($user));
    }

    public function testUserInfo() {
        $user = factory(User::class)->create();
        $token = \JWTAuth::fromUser($user);
        $response = $this->postJson('/api/user', [
            'token' => $token
        ])->assertStatus(200);
        $response->assertJson([
            'email' => $user->email,
            'name' => $user->name,
        ]);
    }

    public function testUserLogout() {
        $user = factory(User::class)->create();
        $token = \JWTAuth::fromUser($user);
        $response = $this->postJson('/api/logout', [
            'token' => $token
        ]);
        $response->assertStatus(200);
        //TODO add message
    }
}
