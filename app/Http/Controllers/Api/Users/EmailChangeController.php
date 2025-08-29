<?php
namespace App\Http\Controllers\Api\Users;

use App\Http\Controllers\Controller;
use App\Services\Api\EmailChangeService;
use Illuminate\Http\Request;

class EmailChangeController extends Controller
{
    protected $emailChangeService;

    public function __construct(EmailChangeService $emailChangeService)
    {
        $this->emailChangeService = $emailChangeService;
    }

    public function sendOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:users,email',
        ]);

        $this->emailChangeService->sendOtp($request->email);

        return $this->responseMessage('success', 'OTP göndərildi.', null, 200, null);
    }

    public function changeEmail(Request $request)
    {
        $request->validate([
            // 'new_email' => 'required|email|unique:users,email',
            'otp' => 'required|digits:4',
        ]);

        $user = auth()->user();

        try {
            $this->emailChangeService->changeEmail($user, $request->new_email, $request->otp);
            return $this->responseMessage('success', 'E-poçt ünvanı uğurla dəyişdirildi.', null, 200, null);
        } catch (\Exception $e) {
            return $this->responseMessage('error', $e->getMessage(), null, 400, null);
        }
    }
}
