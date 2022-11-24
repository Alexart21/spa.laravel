<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\ImplicitRule;

class ReCaptchaV3 implements ImplicitRule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
//        return false;
        $recaptcha_url = 'https://www.google.com/recaptcha/api/siteverify';
        $recaptcha_secret = env('RECAPTCHA_V3_SECRET_KEY');
//        $recaptcha_response = $_POST['reCaptcha'];
        if (!$value) {
            return false;
        }
        // Отправляем POST запрос и декодируем результаты ответа
        $recaptcha = file_get_contents($recaptcha_url . '?secret=' . $recaptcha_secret . '&response=' . $value);
        $recaptcha = json_decode($recaptcha);
        $score = $recaptcha->score;
//        dd($value);
//        dd($score);
//        return false;
        return $score > 0.5 ?? false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Не пройдена ReCaptcha!';
    }
}
