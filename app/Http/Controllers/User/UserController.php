<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Services\UserService;
use Illuminate\Http\Request;


class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function register(Request $request)
    {
        $new_user = $this->userService->register($request);

        return response()->json([
            'message' => 'User registered successfully! ,check your email for verification code',
            'user' => $new_user
        ], 201);
    }

    public function emailverify(Request $request)
    {
        $emailVerify = $this->userService->emailVerify($request);
        if(!$emailVerify){
            return response()->json([
                'message' => 'Email not verified'
            ],400);
        }
        return response()->json([
            'message' => 'Email Verified successfully!'
        ],200);
    }

    public function resendOtp()
    {
        $this->userService->resendOtp();
        return response()->json([
            'message' => 'resent otp successfully! ,check your email'
        ], 200);
    }

    public function login(Request $request)
    {
        $user = $this->userService->login($request);
        if(!$user){
            return response()->json([
                'message' => 'User not found'
            ]);
        }
        return response()->json([
            'message' => 'Logged in successfully!',
            'user' => $user
        ], 200);
    }

    public function logout(Request $request)
    {
        $this->userService->logout($request);

        return response()->json([
        'message' => 'Logged out successfully!'
        ], 200);
    }

    public function getProfile()
    {
        $profile= $this->userService->getProfile();
        if(!$profile){
            return response()->json([
                'message' => 'User not found'
            ], 404);
        }
        return response()->json([
            'message' => 'Get profile successfully!',
            'profile' => $profile
        ], 200);
    }
}
