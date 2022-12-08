<?php
namespace App\Http\Controllers;
use App\Http\Requests\TestFormRequest;


class TestController extends Controller
{

    public function test(TestFormRequest $request)
    {
        $data = $request->validated();
//        dd($data);
        return response()->json([
            'success' => true,
            'submitted' => $data['name'],
        ]);

    }

}
