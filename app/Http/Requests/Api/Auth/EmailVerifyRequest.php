<?php

namespace App\Http\Requests\Api\Auth;

use App\Models\User;
use App\Rules\UserEmailVerifyRule;
use Illuminate\Foundation\Http\FormRequest;

class EmailVerifyRequest extends FormRequest
{


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => [new UserEmailVerifyRule,'required','email','exists:user_temps,email'],
            'otp_code' => 'required|digits:6',
        ];
    }



}
