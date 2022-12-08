<?php
namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class TestFormRequest  extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
//            'name' => 'required|min:2|max:8',
            'name' => [
                'required',
                'min:2',
                'max:30',
                'regex:/^[А-ЯЁа-яё]+\s*/',
            ],
            'age' => 'required|integer|min:18|max:80',
        ];
    }

    public function messages()
    {
        return [
            'name.min' => '2 буквы хотя бы...',
            'name.max' => 'не более 8 букв...',
            'name.required' => 'Имя обязательно',
            'name.regex' => 'пишите кирилицей!',
            'age.min' => 'Вам нет 18',
            'age.max' => 'Старперам тут не место',
            'age.required' => 'Укажите возраст',
            'age.integer' => 'Пишите цифирьками...',
        ];
    }

    // возвращает JSON !
    protected function failedValidation(Validator $validator) {
        $response = response()
            ->json([ 'success' => false, 'errors' => $validator->errors()], 422);

        throw (new ValidationException($validator, $response))
            ->errorBag($this->errorBag);
//            ->redirectTo($this->getRedirectUrl());
    }

}
