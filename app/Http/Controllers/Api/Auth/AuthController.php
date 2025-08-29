<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\EmailVerifyRequest;
use App\Http\Requests\Api\Auth\LoginUserRequest;
use App\Http\Requests\Api\Auth\RegisterUserRequest;
use App\Models\PasswordReset;
use App\Models\User;
use App\Models\UserTemp;
use App\Models\UserVerify;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Services\Api\UserAuthService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;

class AuthController extends Controller
{

    protected UserRepositoryInterface $userRepository;
    protected UserAuthService $userAuthService;

    public function __construct(UserRepositoryInterface $userRepository, UserAuthService $userAuthService)
    {
        $this->userRepository = $userRepository;
        $this->userAuthService = $userAuthService;
    }

    public function register(RegisterUserRequest $request)
    {
        try {
            DB::beginTransaction();
            $user = $this->userRepository->createTemp($request->validated());
            $this->userAuthService->sendOtp($user, $request->all());
            DB::commit();
            return $this->responseMessage('success', 'Otp code successfully send', [], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->responseMessage('error', 'Server error' . $e->getMessage(), [], 500);
        }
    }


    public function resendOtp(Request $request)
    {
        $this->validate($request, [
            "email" => "email|required|exists:user_temps,email"
        ]);
        try {
            DB::beginTransaction();
            $user = $this->userRepository->getTempUser($request->email);
            $this->userAuthService->sendOtp($user, $request->all());
            DB::commit();
            return $this->responseMessage('success', 'Otp code successfully send', [], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->responseMessage('error', 'Server error' . $e->getMessage(), [], 500);
        }
    }


    public function verify(EmailVerifyRequest $request)
    {
        try {
            DB::beginTransaction();
            $userTemp = $this->userRepository->getUserVerify($request->all());
            if (!$userTemp) {
                return $this->responseMessage('error', 'OTP code is wrong.', [], 400);
            }
            if (Carbon::parse($userTemp->expires_at)->isPast()) {
                return $this->responseMessage('error', 'OTP code is expired.', [], 400);
            }
            $this->userAuthService->moveTempToUsers($userTemp);
            DB::commit();
            return $this->responseMessage('success', 'Email successfully verified', [], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->responseMessage('error', 'Server error' . $e->getMessage(), [], 500);
        }
    }

    public function login(LoginUserRequest $request)
    {
        try {
            $user = User::where('email', $request->email)->first();
            if (!$user || !Hash::check($request->password, $user->password) || !$user->email_verified_at) {
                return $this->responseMessage('error', 'Password incorrect', [], 401);
            }

            $response = Http::asForm()->post(url('oauth/token'), [
                // $response = Http::asForm()->post('http://localhost:8001/oauth/token', [
                'grant_type' => 'password',
                'client_id' => (int)env('CLIENT_ID'),
                'client_secret' => env('CLIENT_SECRET'),
                'username' => $request->email,
                'password' => $request->password,
                'scope' => ''
            ]);
            $response = $response->json();


            if ($response and array_key_exists('error', $response)) {
                return $this->responseMessage('error', $response['error_description'], [], 400);
            }

            $data['user'] = $user;
            $data['token'] = $response['access_token'];
            $data['refresh_token'] = $response['refresh_token'];

            return $this->responseMessage('success', 'Login is successfully', $data, 200);
        } catch (\Exception $e) {
            return $this->responseMessage('error', 'System Error: ' . $e->getMessage(), [], 500);
        }
    }


    public function logout(Request $request)
    {
        try {
            $token =auth()->guard('api')->user()->token();

            $token->revoke();

            return $this->responseMessage('success', 'Logout successful', [], 200);
        } catch (\Exception $e) {
            return $this->responseMessage('error', 'System Error: ' . $e->getMessage(), [], 500);
        }
    }



    public function user(Request $request)
    {
        return $request->user();
    }


    public function refreshToken(Request $request)
    {
        $request->validate([
            'token' => 'required',
        ]);
        try {
            // $response = Http::asForm()->post('http://localhost:8001/oauth/token', [
           $response = Http::asForm()->post(url('oauth/token'), [
                'grant_type' => 'refresh_token',
                'client_id' => env('CLIENT_ID'),
                'client_secret' => env('CLIENT_SECRET'),
                'refresh_token' => $request->token,
                'scope' => ''
            ]);
            $response = $response->json();

            if ($response and array_key_exists('error', $response)) {
                return $this->responseMessage('error', __('api.refresh_token_invalid') , [], 400);
            }
            if ($response and array_key_exists('access_token', $response) and array_key_exists('refresh_token', $response)) {
                $data['token'] =  $response['access_token'];
                $data['refresh_token'] = $response['refresh_token'];
                return $this->responseMessage('success', 'Uğurla token dəyişdirildi', $data, 200);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'System Error: ' . $e->getMessage()], 400);
        }
    }
}
