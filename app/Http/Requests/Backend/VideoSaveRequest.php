<?php

namespace App\Http\Requests\Backend;

use Illuminate\Foundation\Http\FormRequest;

class VideoSaveRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|array',
            'title.az' => 'required|string|max:255',
            'description' => 'nullable|array',
            'description.*' => 'nullable|string',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            // "video_url"=>"required"
        ];
    }
    
    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'title.required' => 'Başlıq mütləqdir',
            'title.az.required' => 'Bütün dillər üçün başlıq daxil edilməlidir',
            'video_url.required' => 'Video  mütləqdir',
            'video_url.url' => 'Düzgün URL formatı daxil edin',
            'thumbnail.image' => 'Fayl şəkil formatında olmalıdır',
            'thumbnail.mimes' => 'Şəkil formatı jpeg, png, jpg və ya gif olmalıdır',
            'thumbnail.max' => 'Şəkil maksimum 2MB olmalıdır',
        ];
    }
}