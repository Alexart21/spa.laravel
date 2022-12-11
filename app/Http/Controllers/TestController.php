<?php
namespace App\Http\Controllers;
use App\Http\Requests\TestFormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Validator;


class TestController extends Controller
{

    public function test(TestFormRequest $request)
    {
        $data = $request->validated();
        if ($request->avatar_img){
            $path = $request->file('avatar_img')->store('public/avatars');
        }
        return response()->json([
            'success' => true,
            'submitted' => $data['name'],
            'avatar' => $path ?? 'no-avatar',
        ]);
    }

    public function upload(Request $request){
        $path = [];
        if($request->images){
            foreach ($request->images as $img) {
               $path[] = $img->store('public/avatars');
            }
        }
        return response()->json([
            'success' => true,
            'avatar' => $path,
        ]);
    }

}
