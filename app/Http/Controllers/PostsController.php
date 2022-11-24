<?php

namespace App\Http\Controllers;

//use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\Feedback;
use App\Http\Requests\IndexFormRequest;
use MoveMoveIo\DaData\Facades\DaDataName;
use MoveMoveIo\DaData\Enums\Gender;
use MoveMoveIo\DaData\Enums\Parts;

class PostsController extends Controller
{
    public function index()
    {
        return 'Only POST method';
    }

    public function store(IndexFormRequest $request)
    {
        $data = $request->validated();
        // если не провалидировано то уходит {success:false, errors:....}
        // это в методе failedValidation в файле app/Http/Requests/IndexFormRequest.php

        /*$score = $this->checkReCaptcha(); // метод из родительского контроллера AppFormsController.php
        if (!$score || $score < 0.5) // не прошла recaptcha
        {
            return response()->json([
                'success' => false,
                'recaptcha' => false,
                'score' => $score,
            ]);
        }*/
        $db = Post::create($data) ? true : false;
        $mail = $this->sendEmail($data);
        if ($db && $mail) {
            return response()->json([
                'success' => true,
            ]);
        } else { // почемуто не записалось в базу или не отправилась почта
            return response()->json([
                'success' => false,
                'db' => $db,
                'mail' => $mail,
//                'score' => $score,
            ]);
        }
    }

    private function sendEmail($data)
    {
        try {
            $params = [// Эти параметры так же надо указать в файле app/Mail/Feedback.php.
                'email' => $data['email'],
                'tel' => $data['tel'],
                'body' => nl2br(htmlspecialchars($data['body'])),
                'name' => $data['name'],
                'subject' => 'Письмо с сайта',
            ];
            Mail::to(env('ADMIN_EMAIL'))
                ->send(new Feedback($params));
            return true;
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }

    public function info(Request $request)
    {
        /*return response()->json([
            'success' => true,
            'count' => 2,
            'names' => ['вася', 'василий', 'василиса', 'васискис'],
        ]);*/
        $dadata = DaDataName::prompt($request->name, $count=5, Gender::UNKNOWN, [Parts::NAME]);
        $res = [];
        foreach ($dadata['suggestions'] as $v){
            array_push($res, $v['value']);
        }
        return response()->json([
            'success' => true,
            'count' => $count,
            'names' => $res
        ]);

    }

}

