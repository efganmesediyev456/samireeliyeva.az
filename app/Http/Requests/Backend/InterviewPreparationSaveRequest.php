<?php

namespace App\Http\Requests\Backend;

use Illuminate\Foundation\Http\FormRequest;

class InterviewPreparationSaveRequest extends FormRequest
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
            // 'video_url' => 'nullable|file|mimes:mp4,mov,ogg,qt|max:100000',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'date' => 'nullable|date',
            // 'type' => 'required|integer',
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
            'title.az.required' => 'Az dili üçün başlıq daxil edilməlidir',
            'video_url.file' => 'Video fayl formatında olmalıdır',
            'video_url.mimes' => 'Video formatı mp4, mov, ogg və ya qt olmalıdır',
            'video_url.max' => 'Video maksimum 100MB olmalıdır',
            'thumbnail.image' => 'Fayl şəkil formatında olmalıdır',
            'thumbnail.mimes' => 'Şəkil formatı jpeg, png, jpg və ya gif olmalıdır',
            'thumbnail.max' => 'Şəkil maksimum 2MB olmalıdır',
            'date.date' => 'Düzgün tarix formatı daxil edin',
            'type.required' => 'Status mütləqdir',
            'type.integer' => 'Status tam ədəd olmalıdır',
        ];
    }
}