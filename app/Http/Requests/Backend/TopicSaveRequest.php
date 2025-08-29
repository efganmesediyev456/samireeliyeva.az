<?php

namespace App\Http\Requests\Backend;

use Illuminate\Foundation\Http\FormRequest;

class TopicSaveRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'title' => 'required|array',
            'title.az' => 'required|string|max:255',
            'subtitle' => 'nullable|array',
            'subtitle.az' => 'nullable|string|max:255',
            'subcontent' => 'nullable|array',
            'subcontent.az' => 'nullable|string',
            'icon' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
        ];
    }
}