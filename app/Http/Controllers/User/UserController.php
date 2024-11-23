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
        return $this->userService->register($request);
    }

    public function emailverify(Request $request)
    {
        return $this->userService->emailVerify($request);
    }

    public function resendVerificationCode()
    {
        return $this->userService->resendVerificationCode();
    }

    public function login(Request $request)
    {
        return $this->userService->login($request);
    }

    public function forgetPassword(Request $request)
    {
        return $this->userService->forgetPassword($request);
    }

    public function resetPassword(Request $request)
    {
        return $this->userService->resetPassword($request);
    }

    public function logout(Request $request)
    {
        return $this->userService->logout($request);
    }

    public function getProfile()
    {
        return $this->userService->getProfile();
    }

    public function updateProfile(Request $request)
    {
        return $this->userService->updateProfile($request);
    }

    public function deleteAccount()
    {
        return $this->userService->deleteAccount();
    }
}
