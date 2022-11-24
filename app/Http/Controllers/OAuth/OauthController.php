<?php

namespace App\Http\Controllers\OAuth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Laravel\Socialite\Facades\Socialite;
use Exception;
use App\Models\User;
use App\Models\Oauth;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OauthController
{

    public function redirectToService($service, Request $request)
    {
        session(['ip' => $request->ip()]);
        return Socialite::driver($service)->redirect();
    }

    public function handleCallback($service)
    {
        $user = Socialite::driver($service)->user();
//        dd($user);
//        dd($user->user);
        $_user = Oauth::where('source_id', $user->id)->first();
        if ($_user) { //уже заходил с этим сервисом
            // реализована связь один к одному hasOne в модели User и соответственно belongsTo в модели Oauth
            // это аналогично этому $finduser = User::find($_user->user_id)
            $finduser = $_user->user;
            Auth::login($finduser);
//            return redirect()->intended('dashboard');
            return redirect()->intended('/');
        } else { // впервые
            // поля могут быть разные в зависимомти от сервиса
            switch ($service) {
                case 'yandex' :
                    $name = $user->nickname;
                    $email = $user->nickname . '@yandex.ru';
                    $avatar = null; // вот такая дичь
                    break;
                case 'google' :
                    $name = $user->name;
                    $email = $user->email;
                    $avatar = $user->user['picture'] ?? null;;
                    break;
                case 'vkontakte' :
                    $name = $user->name;
                    $email = $user->email;
                    $avatar = $user->user['photo_200'] ?? null;
                    break;
                case 'odnoklassniki' :
                    $name = $user->name;
                    $email = $user->email;
                    $avatar = $user->user['pic_1'];
                    break;
                case 'gitgub' :
                    $name = $user->name;
                    $email = $user->email;
                    $avatar = $user->user['avatar_url'];
                    break;
                default:
                    $name = $user->name;
                    $email = $user->email;
                    $avatar = null;
            }
            try {
                DB::beginTransaction();
                $newUser = User::create([
                    'name' => $name,
                    'email' => $email,
                    'avatar' => $avatar,
                    'password' => Str::random(8),
                    'email_verified_at' => date("Y-m-d H:i:s"),
                    'ip' => session('ip'),
                ]);
                Oauth::create([
                    'user_id' => $newUser->id,
                    'source' => $service,
                    'source_id' => $user->id,
                ]);
                DB::commit();
                Auth::login($newUser);
//                return redirect()->intended('dashboard');
                return redirect()->intended('/');
            } catch (Exception $e) {
                DB::rollBack();
                // поскольку поле email у нас unique возможны ошибки MYSQL
                // при попытке дублировать email Duplicate entry....  код ошибки 23000
                $oldUser = User::where('email', $email)->first();
//                dump($oldUser);
//                dd($email);
                $oauth = Oauth::where('user_id', $oldUser->id)->first();
                // if (($e->getCode() == 23000) && ($oldUser && $oauth)) {
                if ($oldUser && $oauth) { // юзер уже логинился через какой то сервис с таким же email
                    try {
                        DB::beginTransaction();
                        // оставляем email прежним и перезапишем имя и др. от ноаого сервиса
                        $oldUser->name = $name;
                        $oldUser->avatar = $avatar;
                        $oldUser->ip = session('ip');
                        $oldUser->save();

                        $oauth->source = $service;
                        $oauth->source_id = $user->id;
                        $oauth->save();
                        DB::commit();
                        Auth::login($oldUser);
                        return redirect()->intended('/');
                    } catch (Exception $e) {
                        DB::rollBack();
                        dd($e->getMessage());
                    }
                }
                // другая ошибка
                $errCode = $e->getCode();
                if($errCode == 23000){ // уже регались обычным способом(не OAuth !) с таким email
                    return view('auth.oauthErr', compact('email'));
                }
                dd($e->getMessage());
            }
        }
    }
}
