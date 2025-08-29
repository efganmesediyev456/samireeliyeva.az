<?php

namespace App\Http\Requests\Backend;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class TopicCategorySaveRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'title' => 'required|array',
            'title.az' => 'nullable|string|max:255',
        ];
    }

    public function messages()
    {
        return [
            'title.required' => 'Kateqoriya başlığı tələb olunur',
            'title.array' => 'Kateqoriya başlığı düzgün formatda deyil',
            'title.*.string' => 'Kateqoriya başlığı mətn formatında olmalıdır',
            'title.*.max' => 'Kateqoriya başlığı maksimum 255 simvol ola bilər',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function (Validator $validator) {
            $titles = $this->input('title', []);
            $requiredLocales = ['az'];
            $missing = [];

            foreach ($requiredLocales as $locale) {
                if (empty($titles[$locale])) {
                    $missing[] = strtoupper($locale);
                }
            }

            if (!empty($missing)) {
                foreach ($requiredLocales as $locale) {
                    $validator->errors()->forget("title.$locale");
                }

                // Ümumi xəta əlavə et
                $validator->errors()->add('title', 'Aşağıdakı dillər üçün kateqoriya başlığı daxil edilməlidir: ' . implode(', ', $missing));
            }
        });
    }
}
