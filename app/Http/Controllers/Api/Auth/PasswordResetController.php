<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class PasswordResetController extends Controller
{
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $status = Password::sendResetLink($request->only('email'), function ($user, $token) use($request) {
            $resetUrl = "https://tehsil.netlify.app/password-reset?token={$token}&email={$user->email}";
            Mail::html("Zəhmət olmasa, aşağıdakı linkə daxil olub yeni şifrə təyin edin: <a href='$resetUrl'>Daxil ol</a>", function ($message) use ($request) {
                $message->to($request->email)->subject("Şifrə Sıfırlama");
            });
        });

        if ($status === Password::RESET_LINK_SENT) {
            return $this->responseMessage('success', 'Şifrə sıfırlama linki e-poçt ünvanınıza göndərildi.', [], 200);
         }

        throw ValidationException::withMessages([
            'email' => [__($status)],
        ]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'token' => 'required',
            'password' => 'required|min:8|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                ])->save();
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return response()->json(['message' => 'Şifrə uğurla yeniləndi.'], 200);
        }

        return response()->json(['message' => 'Token yanlışdır və ya müddəti bitmişdir.'], 400);
    }
}
