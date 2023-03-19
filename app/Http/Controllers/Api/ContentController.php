<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Country;
use Illuminate\Http\Request;

class ContentController extends Controller
{
    public function __construct()
    {
        auth()->setDefaultDriver('api'); // ВОТ без этой строчки не работала api&&web аутентификация !!!!
//        $this->middleware('auth:api', ['except' => ['login', 'registration']]);
    }


    public function countrys(Request $request)
    {
//        return response()->json(['bla' => 'bla']);
        $offset = $request->input('offset');
        $limit = $request->input('limit');
        $total = Country::count();
//        return response()->json(['bla' => $limit]);
//        die($total);
        if($offset > $total){
            return response()->json(['success' => false]);
        }
        $countrys = Country::orderBy('name')
            ->offset($offset)
            ->limit($limit)->get();
        if($countrys){
            return response()->json(
                [
                    'success' => true,
                    'data' => $countrys,
                    'total' => $total,
                ]
            );
        }else{
            return response()->json(['success' => false]);
        }
    }

    public function test()
    {
        return response()->json(
            [
                'success' => true,
                'data' => 'bla',
            ]
        );
    }
}
