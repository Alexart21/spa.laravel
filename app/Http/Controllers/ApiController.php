<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Country;

class ApiController extends Controller
{

    public function inf(Request $request)
    {
        $offset = $request->offset;
        $step = $request->step;
        $total = Country::count();
        if($offset > $total){
           return response()->json(['success' => false]);
        }
        $countrys = Country::orderBy('name')
            ->offset($offset)
            ->limit($step)->get();
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

}
