<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use JWTAuth;

class AuthController extends Controller
{
    public function index() {
        return response()->json([
            'status' => 'ok',
            'message' => 'welcome'
        ]);
    }
    public function register(Request $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        $token = auth()->login($user);

        return $this->respondWithToken($token);
    }

    public function login(Request $request)
    {
        $credentials = $request->only(['email', 'password']);

        if (!$token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            'name' => auth()->user()->name,
            'email' => auth()->user()->email,
            'message' => "Logged in."
        ]);
    }

    public function user(Request $request) {
        $user = $request->user();
        return response()->json([
           'name' => $user->name,
            'email' => $user->email
        ]);
    }

    public function logout(Request $request) {
        $user = $request->user();
        auth()->invalidate();
        return response()->json([
            'name' => $user->name,
            'message' => 'Logged out.'
            ]);
    }
}
