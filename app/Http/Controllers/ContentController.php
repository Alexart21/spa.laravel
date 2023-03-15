<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ContentController extends Controller
{
    public function index()
    {
        return view('content.index');
    }

    public function csrf()
    {
        return response()->json([
           'csrf' =>  csrf_token()
        ]);
    }

    public function user()
    {
        $user = Auth::user();
        if($user){
            //
            auth()->setDefaultDriver('api'); // ВОТ без этой строчки не работала api&&web аутентификация !!!!
            $token = auth()->login($user);
            //
            if($user->avatar){
                $avatar = $user->avatar;
            }elseif ($user->profile_photo_path){
                $avatar = 'storage/' . $user->profile_photo_path;
            }else{
                $avatar = 'img/no-image.png';
            }
            return response()->json([
                'isGuest' => false,
                'username' => $user->name,
                'email' => $user->email,
                'status' => 10,
                'avatarPath' => $avatar,
                'token' => $token
            ]);
        }else{
            return response()->json(['isGuest' => true]);
        }

    }
}
