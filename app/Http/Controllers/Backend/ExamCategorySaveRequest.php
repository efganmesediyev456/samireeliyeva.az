<?php

namespace App\Http\Requests\Backend;

use Illuminate\Foundation\Http\FormRequest;

class ExamCategorySaveRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|array',
            'title.az' => 'required|string|max:255',
            'status' => 'nullable|boolean',


        ];
        //salam
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Başlıq sahəsi tələb olunur',
            'title.az.required' => 'Başlıq sahəsi tələb olunur',
            'title.az.string' => 'Başlıq sahəsi mətn olmalıdır',
            'title.az.max' => 'Başlıq sahəsi maksimum 255 simvol ola bilər',
        ];
    }
}