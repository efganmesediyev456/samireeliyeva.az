<?php

namespace App\Http\Requests\Backend;

use Illuminate\Foundation\Http\FormRequest;

class FreeOnlineLessonSaveRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'title.az' => 'required|string|max:255',
            'subtitle.*' => 'nullable|string|max:255',
            'url' => 'required|url',
            'icon' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ];
    }

    public function messages()
    {
        return [
            'title.az.required' => 'Başlıq sahəsi tələb olunur',
            'url.required' => 'URL sahəsi tələb olunur',
            'url.url' => 'Düzgün URL daxil edin',
            'icon.image' => 'İkon bir şəkil olmalıdır',
        ];
    }
}