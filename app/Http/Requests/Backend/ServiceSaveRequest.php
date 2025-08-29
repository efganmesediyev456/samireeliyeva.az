<?php

namespace App\Http\Requests\Backend;

use Illuminate\Foundation\Http\FormRequest;

class ServiceSaveRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'title.az' => 'required|string',
            'image' => 'sometimes|nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'icon' => 'sometimes|nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'slug.*' => 'nullable|string',
            'description.*' => 'nullable|string',
            'seo_description.*' => 'nullable|string',
            'seo_keywords.*' => 'nullable|string'
        ];
    }

    public function messages()
    {
        return [
            'title.az.required' => 'Başlıq mütləqdir',
            'image.image' => 'Yalnız şəkil faylı yükləmək olar',
            'image.mimes' => 'Şəkil formatı: jpeg, png, jpg, gif, webp olmalıdır',
            'image.max' => 'Şəkil maksimum 2MB ola bilər',
            'icon.image' => 'Yalnız şəkil faylı yükləmək olar',
            'icon.mimes' => 'İkon formatı: jpeg, png, jpg, gif, webp olmalıdır',
            'icon.max' => 'İkon maksimum 2MB ola bilər'
        ];
    }
}