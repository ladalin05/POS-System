<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Firebase\JWT\JWT;
use App\Traits\ApiResponses;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\Api\Auth\LoginUserRequest;
use App\Http\Requests\Api\Auth\RegisterUserRequest;
use App\Http\Requests\Api\Auth\ChangePasswordUserRequest;

class AuthController extends Controller
{
    use ApiResponses;

    public function login(LoginUserRequest $request)
    {
        $request->validated($request->all());
        if (!Auth::attempt($request->only('username', 'password'))) {
            return $this->error('Invalid credentials', 401);
        }
        $user = User::where('username', $request->username)->first();
        if($user->secret != $request->secret) {
            return $this->error('Invalid secret', 401);
        }
        $payload = [
            'token' => $user->createToken('auth_token')->plainTextToken,
            'iat' => time(),
            'nbf' => config('sanctum.expiration'),
        ];
        $token = JWT::encode($payload, env('SANCTUM_STATEFUL_SECRET'), 'HS256');
        return $this->ok('Authenticated', [
            'token' => $token,
            'expires_in' => (int)config('sanctum.expiration')
        ]);
    }
    public function refreshToken(Request $request)
    {
        try {
            $request->user()->tokens()->delete();
            return $this->ok('Token refreshed', [
                'token' => $request->user()->createToken('auth_token')->plainTextToken
            ]);
        } catch (\Throwable $th) {
            return $this->error('Error refreshing token', 500);
        }
    }
    public function register(RegisterUserRequest $request)
    {
        try {
            $request->validated($request->all());
            $user = User::create([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'phone' => $request->phone,
                'gender' => $request->gender,
                'dob' => $request->dob,
                'email' => $request->email,
                'password' => bcrypt($request->password)
            ]);
            $payload = [
                'token' => $user->createToken('auth_token')->plainTextToken,
                'iat' => time(),
                'nbf' => config('sanctum.expiration'),
            ];
            $token = JWT::encode($payload, env('SANCTUM_STATEFUL_SECRET'), 'HS256');
            return $this->ok('User registered', [
                'token' => $token
            ]);
        } catch (\Throwable $th) {
            return $this->error($th->getMessage(), 500);
        }
    }
    public function logout(Request $request)
    {
        try {
            $request->user()->currentAccessToken()->delete();
            return $this->ok('Logged out');
        } catch (\Throwable $th) {
            return $this->error('Error logging out', 500);
        }
    }
    public function logoutOtherDevices(Request $request)
    {
        try {
            $request->user()->tokens()->where('id', '!=', $request->user()->currentAccessToken()->id)->delete();
            return $this->ok('Logged out on other devices');
        } catch (\Throwable $th) {
            return $this->error('Error logging out on other devices', 500);
        }
    }
    public function loggedDevices(Request $request)
    {
        return $this->ok('Logged devices', [
            'tokens' => $request->user()->tokens
        ]);
    }
    public function changePassword(ChangePasswordUserRequest $request)
    {
        try {
            $request->validated($request->all());
            $old_password = $request->old_password;
            $new_password = $request->new_password;
            $logout_other_devices = filter_var($request->logout_other_devices, FILTER_VALIDATE_BOOLEAN);
            if($old_password === $new_password && config('app.env') === 'production') {
                return $this->error('Your new password cannot be the same as your current password. Please choose a different password', 401);
            }
            $user = $request->user();
            if(!Hash::check($old_password, $user->password)) {
                return $this->error('Invalid old password', 401);
            }
            $user->password = bcrypt($new_password);
            $user->save();
            if($logout_other_devices === true) {
                $user->tokens()->where('id', '!=', $user->currentAccessToken()->id)->delete();
                return $this->ok('Password changed and logged out on other devices');
            }
            return $this->ok('Password changed');
        } catch (\Throwable $th) {
            return $this->error('Error changing password', 500);
        }
    }
}
