<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;

class FileUploads extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'images' => 'array|max:10',
            'images.*' => 'file|image|mimes:jpg,jpeg,png|max:200',
        ];
    }

    public function messages()
    {
        return [
            'images.max' => 'Не более 10 фотографий max 200 кБ каждая',
            'images.0' => 'Только файлы изображений',
            'images.0' => 'Только файлы jpg,jpeg,png',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $response = response()
            ->json(['success' => false, 'errors' => $validator->errors()], 422);

        throw (new ValidationException($validator, $response))
            ->errorBag($this->errorBag);
    }
}
