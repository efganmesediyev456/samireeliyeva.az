<?php

namespace App\Http\Requests\Backend;

use Illuminate\Foundation\Http\FormRequest;

class ExamQuestionSaveRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'question_text' => 'required|array',
            'question_text.az' => 'required|string',
            // 'points' => 'required|integer|min:1',
            // 'position' => 'required|integer|min:0',
            // 'type' => 'required|integer',
            'options' => 'required_if:type,1|array|min:2',
            // 'options.az.texts' => 'required|array',
         
        ];
    }

    public function messages()
    {
        return [
            'question_text.required' => 'Sual mətni az dili üçün daxil etmək lazımdır',
            'question_text.*.required' => 'Sual mətni az dili üçün daxil etmək lazımdır',
            'points.required' => 'Xal tələb olunur',
            'points.integer' => 'Xal rəqəm olmalıdır',
            'points.min' => 'Xal ən azı 1 olmalıdır',
            'position.required' => 'Sıra tələb olunur',
            'position.integer' => 'Sıra rəqəm olmalıdır',
            'position.min' => 'Sıra ən azı 0 olmalıdır',
            'type.required' => 'Tip tələb olunur',
            'options.required' => 'Cavab variantları tələb olunur',
            'options.min' => 'Ən azı 2 cavab variantı olmalıdır',
        ];
    }


    
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {

                 


            $options = $this->input('options', []);

            foreach ($options as $index => $option) {
                if (!isset($option['texts']['az']) || empty($option['texts']['az'])) {
                    $letterIndex = chr(65 + $index); 
                    $validator->errors()->add("options.$index.texts.az", "{$letterIndex} variantı cavab az dili üçün daxil edilməlidir.");
                }
            }


            $correctCount = 0;
            foreach ($this->input('options', []) as $option) {
                if (isset($option['is_correct'])) {
                    $correctCount++;
                }
            }

            if ($correctCount !== 1 and request()->type==1) {
                $validator->errors()->add('is_correct', 'Bir doğru cavab mütləq olmalıdır və birdən çox doğru cavab ola bilməz');
            }
        });
    }


}