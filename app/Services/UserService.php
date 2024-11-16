<?php

namespace App\Services;

use App\Models\User;
use App\Notifications\EmailVerificationNotification;
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

        return $new_user;
    }

    public function emailVerify(Request $request)
    {
        $otp2= $this->otp->validate($request->email, $request->otp);
        if(!$otp2->status){
            return false;
        }
        User::where('email', $request['email'])
            ->update(['email_verified_at' => now()]);
        return true;
    }

    public function resendOtp()
    {
        $user= Auth::guard('user-api')->user();
        $user->notify(new EmailVerificationNotification());
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        $token= Auth::guard('user-api')->attempt($credentials);
        if(!$token){
            return null;
        }
        $user= Auth::guard('user-api')->user();
        $user->token = $token;

        return $user;
    }

    public function logout(Request $request)
    {
        $token= $request->header('Authorization');
        Auth::guard('user-api')->invalidate($token);
    }

    public function getProfile()
    {
        $user= Auth::guard('user-api')->user();
        return $user;
    }
}
