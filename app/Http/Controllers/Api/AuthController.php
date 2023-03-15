<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        auth()->setDefaultDriver('api'); // ВОТ без этой строчки не работала api&&web аутентификация !!!!
        $this->middleware('auth:api', ['except' => ['login', 'registration']]);
    }

    public function test()
    {
        return response()->json(['here' => 'here']);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login()
    {
        $credentials = request(['email', 'password']);
        if (! $token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        return $this->respondWithToken($token);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    /*public function me()
    {
        return response()->json(auth()->user());
    }*/

    public function user()
    {
        die('here');
        $user = Auth::user();
        if($user){
            $token = auth()->tokenById($user->id);
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

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }
}
