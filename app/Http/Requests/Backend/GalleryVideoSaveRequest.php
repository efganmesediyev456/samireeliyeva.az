<?php

namespace App\Http\Requests\Backend;

use Illuminate\Foundation\Http\FormRequest;

class GalleryVideoSaveRequest extends FormRequest
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
            'subtitle.*' => 'nullable|string',
            'slug.*' => 'nullable|string',
            'description.*' => 'nullable|string',
            'seo_description.*' => 'nullable|string',
            'seo_keywords.*' => 'nullable|string',
            'media_files' => 'nullable|array',
            'media_files.*' => 'mimes:mp4,avi,mov,wmv,webm', 
            'delete_media' => 'nullable|array',
            'delete_media.*' => 'integer',
            'media_order' => 'nullable|array',
            'media_order.*' => 'integer'
        ];
    }

    public function messages()
    {
        return [
            'title.az.required' => 'Başlıq mütləqdir',
            'image.image' => 'Yalnız şəkil faylı yükləmək olar',
            'image.mimes' => 'Şəkil formatı: jpeg, png, jpg, gif, webp olmalıdır',
            'image.max' => 'Şəkil maksimum 2MB ola bilər',
            'media_files.*.mimes' => 'Video formatı: mp4, avi, mov, wmv, webm olmalıdır',
            'media_files.*.max' => 'Hər bir video maksimum 100MB ola bilər'
        ];
    }
}