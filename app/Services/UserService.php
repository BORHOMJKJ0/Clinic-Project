<?php

namespace App\Services;

use App\Helpers\ResponseHelper;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Notifications\EmailVerificationNotification;
use App\Notifications\ResetPasswordNotification;
use App\Traits\ImageProcessing;
use Illuminate\Http\Request;
use Ichtrojan\Otp\Otp;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;

class UserService{
    use ImageProcessing;
    private $otp;

    public function __construct()
    {
        $this->otp= new Otp();
    }
    public function register(Request $request)
    {
        $inputs = $request->all();
        if($request->hasFile('image')){
            $inputs['image'] = $this->saveImage($request->file('image'));
        }
        $inputs['password'] = Hash::make($inputs['password']);
        $new_user= User::create($inputs);

        $token= Auth::guard('user-api')->login($new_user);
        $new_user->token = $token;

        $new_user->notify(new EmailVerificationNotification());

        $data =[
            'new_user' => UserResource::make($new_user),
            'token' => $token,
        ];
        return ResponseHelper::jsonResponse(
            $data,
            'User registered successfully! ,check your email for verification code',
            201);
    }

    public function emailVerify(Request $request)
    {
        $otp2= $this->otp->validate($request->email, $request->otp);
        if(!$otp2->status){
            return ResponseHelper::jsonResponse([],'Email not verified',400,false);
        }
        User::where('email', $request['email'])
            ->update(['email_verified_at' => now()]);
        return ResponseHelper::jsonResponse([], 'Email Verified successfully!');
    }

    public function resendVerificationCode()
    {
        $user= Auth::guard('user-api')->user();
        $user->notify(new EmailVerificationNotification());

        return ResponseHelper::jsonResponse([], 'Resent otp successfully! ,check your email');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        $token= Auth::guard('user-api')->attempt($credentials);
        if(!$token){
            return ResponseHelper::jsonResponse([],'User not found',400,false);
        }
        $user= Auth::guard('user-api')->user();

        $data=[
            'user' => UserResource::make($user),
            'token' => $token
        ];
        return ResponseHelper::jsonResponse($data, 'Logged in successfully!');
    }

    public function forgetPassword(Request $request)
    {
        $email= $request->email;
        $user= User::where('email', $email)->first();
        if(!$user){
            return ResponseHelper::jsonResponse([],'User not found',400,false);
        }
        $user->notify(new ResetPasswordNotification());
        return ResponseHelper::jsonResponse([], 'Check your email for reset password!');
    }

    public function resetPassword(Request $request)
    {
        $data =$request->all();

        $otp2 = $this->otp->validate($data['email'], $data['otp']);
        if(!$otp2->status){
            return ResponseHelper::jsonResponse([], 'Invalid otp', 400,false);
        }

        $data['password'] = Hash::make($data['password']);
        User::where('email',$data['email'])
            ->update(['password' => $data['password']]);

        return ResponseHelper::jsonResponse([], 'Password reset successfully!');
    }

    public function logout(Request $request)
    {
        $token= $request->header('Authorization');
        Auth::guard('user-api')->invalidate($token);

        return ResponseHelper::jsonResponse([], 'Logged out successfully!');
    }

    public function getProfile()
    {
        $user= Auth::guard('user-api')->user();
        if(!$user){
            return ResponseHelper::jsonResponse([], 'User not found',400,false);
        }
        $data=[
            'profile'=>UserResource::make($user)
        ];
        return ResponseHelper::jsonResponse($data,'Get profile successfully!');
    }

    public function updateProfile(Request $request)
    {
        $inputs= $request->all();
        $user= Auth::guard('user-api')->user();

        if($request->hasFile('image')){
            if($user->image)
                $this->delete_image($user->image);
            $inputs['image'] = $this->saveImage($request->file('image'));
        }

        $updated_user= $user->update($inputs);

        $data=[
            'updated_user'=>UserResource::make($updated_user)
        ];

        return ResponseHelper::jsonResponse($data, 'Update successfully!');
    }

    public function deleteAccount()
    {
        $user= Auth::guard('user-api')->user();
        User::destroy($user);

        return ResponseHelper::jsonResponse([], 'Account deleted successfully!');
    }
}
