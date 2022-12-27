<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Chat;



class ChatController extends Controller
{

    public function all()
    {
        $lastId = Chat::all()->max('id');
//        $chat = Chat::all()->take(100);
        $chat = Chat::orderByDesc('id')->take(100)->get();
        return response()->json([
            'success' => true,
            'lastId' => $lastId,
            'msgs' => $chat
        ]);
    }

    public function update(Request $request)
    {
        $id = (int)$request->id;
        $lastId = Chat::all()->max('id');
        $chat = Chat::where('id', '>', $id )->orderByDesc('id')->take(100)->get();
        return response()->json([
            'success' => true,
            'lastId' => $lastId,
            'msgs' => $chat
        ]);
    }

    public function store(Request $request)
    {
        $ip = $request->ip();
        $data = $request->validate([
            'name' => 'required|max:100',
            'msg' => 'required:max:500',
            'color' => 'required:max:10',
        ]);
        $data['ip'] = $ip;
        $msg = Chat::create($data);
        if($msg){
            return response()->json([
                'success' => true,
                'id' => $msg->id
            ]);
        }else{
            return response()->json([
                'success' => false,
            ]);
        }

    }

}
