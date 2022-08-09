<?php

namespace App\Http\Controllers\APIv1;

use Symfony\Component\HttpFoundation\Response;
use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Models\User;

class AuthController extends ApiController
{

    public function register(Request $request)
    {
        return User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password'))
        ]);
    }

    public function login(Request $request)
    {
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response([
                'message' => 'Invalid credentials!'
            ], Response::HTTP_UNAUTHORIZED);
        }

        /** @var \App\Models\User $user **/
        $user = Auth::user();

        $token = $user->createToken('token')->plainTextToken;

        $cookie = cookie('jwt', $token, 60 * 24); // 1 day

        return response()->json([
            "status" => 200, "success" => true,
            'Welcome!' . ' ' . $user->name => $token
        ])
            ->withCookie($cookie);
    }

    public function user()
    {
        return Auth::user();
    }

    public function logout()
    {
        $cookie = Cookie::forget('jwt');

        return response()->json(['Logout successful'])
            ->withCookie($cookie);
    }
}
