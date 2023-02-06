<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;
use App\Rules\ReCaptchaV3;

class IndexFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'reCaptcha' => ['required', new ReCaptchaV3],
            // регулярка только кириллица пробел и дефис
            'name' => 'required|min:2|max:128|regex:/^[А-Яа-яё -]*$/ui',
            'email' => 'required|email',
            'tel' => 'min:6|max:20',
            'body' => 'required|min:2|max:10000',
        ];
    }

    public function attributes()
    {
        return [
            'name' => 'Имя',
            'email' => 'email',
            'tel' => 'Номер телефона',
            'body' => 'Сообщение',
        ];
    }

    public function messages()
    {
        return [
            'reCaptcha.required' => 'Отсутствует параметр reCaptcha',
            'name.required' => 'Укажите имя',
            'name.min' => '2 буквы хотя бы...',
            'name.regex' => 'Только кириллица без цифр и спецсимволов!',
            'tel.min' => '6 цифр хотя бы...',
            'tel.max' => 'много цифр...',
            'body.required' => 'Напишите что нибудь :)',
            'body.min' => '2 буквы хотя бы...',
            'body.max' => 'не более 10000 символов',
        ];
    }

    // возвращает JSON !
    protected function failedValidation(Validator $validator) {
        $response = response()
            ->json([ 'success' => false, 'errors' => $validator->errors()], 422);

        throw (new ValidationException($validator, $response))
            ->errorBag($this->errorBag);
    }
}
