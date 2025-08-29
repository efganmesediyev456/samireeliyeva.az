<?php

namespace App\Http\Requests\Backend;

use Illuminate\Foundation\Http\FormRequest;

class GalleryPhotoSaveRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'media_files.*' => 'nullable|file|mimes:jpeg,png,jpg,webp,pdf|max:5120',
            'delete_media' => 'array',
            'delete_media.*' => 'exists:gallery_photo_media,id',
            'media_order' => 'array',
            'media_order.*' => 'integer|min:0',
        ];

        $rules['title.az'] = 'required|string|max:255';
        

        return $rules;
    }

    public function messages()
    {
        return [
            'title.az.required' => 'Başlıq tələb olunur',
            'slug.*.required' => 'Slug tələb olunur',
            'image.image' => 'Fayl şəkil olmalıdır',
            'media_files.*.file' => 'Fayllar düzgün olmalıdır',
        ];
    }
}