<?php

namespace App\Http\Requests\Backend;

use Illuminate\Foundation\Http\FormRequest;

class PartnerSaveRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'title.az' => 'required|string|max:255',
            'url' => 'nullable|url',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ];
    }

    public function messages()
    {
        return [
            'title.az.required' => 'Başlıq sahəsi tələb olunur',
            'url.url' => 'Düzgün URL daxil edin',
            'image.image' => 'Şəkil bir şəkil olmalıdır',
        ];
    }
}