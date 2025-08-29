<?php

namespace App\Http\Requests\Backend;

use Illuminate\Foundation\Http\FormRequest;

class ExamSaveRequest extends FormRequest
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
            'subtitle' => 'array',
            'duration' => 'required|integer|min:1',
        ];
    }

    public function messages()
    {
        return [
            'title.required' => 'Başlıq tələb olunur',
            'title.az.required' => 'Başlıq bütün dillərdə tələb olunur',
            'duration.required' => 'Müddəti tələb olunur',
            'duration.integer' => 'Müddəti rəqəm olmalıdır',
            'duration.min' => 'Müddəti ən azı 1 dəqiqə olmalıdır',
        ];
    }
}