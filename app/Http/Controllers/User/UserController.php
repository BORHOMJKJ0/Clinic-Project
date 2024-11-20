<?php

namespace App\Http\Controllers\User;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
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

    public function resendOtp()
    {
        return $this->userService->resendOtp();
    }

    public function login(Request $request)
    {
       return $this->userService->login($request);
    }

    public function logout(Request $request)
    {
        return $this->userService->logout($request);
    }

    public function getProfile()
    {
        return $this->userService->getProfile();
    }
}
