<?php
namespace App\Http\Controllers;
use App\Http\Requests\TestFormRequest;
use App\Http\Requests\PhotoRequest;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use App\Http\Requests\FileUploads;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Validator;
use App\Models\Photo;
use Illuminate\Http\UploadedFile;
use function PHPUnit\Framework\fileExists;
use Iman\Streamer\VideoStreamer;


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

    // здесь мультизагрузка файлов
    public function upload(FileUploads $request){

        $links = [];
        $user_id = Auth::user()->id;
        if($request->images){
            foreach ($request->images as $img) {
                $hash = hash_file('sha1', $img->path());
                // проверка по хеш суммам нет ли уже такого файла
                if(!Photo::where('hash_sum', $hash)->count()){
                    $path = $img->store('public/photos');
                    $link = str_replace('public', 'storage', $path);
                    $photo = new Photo();
                    $photo->hash_sum = $hash;
                    $photo->path = $path;
                    $photo->link = $link;
                    $photo->user_id = $user_id;
                    $photo->save();
                    $links[] = $link;
                }

            }
        }
        return response()->json([
            'success' => true,
            'avatar' => $links,
        ]);
    }

    public function images()
    {
        $data = Photo::orderByDesc('created_at')->paginate(2);
        if ($data){
            return response()->json([
                'success' => true,
                'all' => $data,
            ]);
        }
        return response()->json([
            'success' => true,
            'all' => null,
        ]);
    }

    public function remove(Request $request)
    {
        $id = $request->id;
        $img = Photo::findOrFail($id);
        $path = $img->path;
        // файл
        $deleteFile = Storage::delete($path);
        // запись из базы
        $deletedRows = $img->delete();
        if($deleteFile && $deletedRows){
            return response()->json([
                'success' => true,
                'deletedId' => $id,
            ]);
        }else{
            return response()->json([
                'success' => false,
            ]);
        }
    }

    public function crop(Request $request)
    {
//        dd($request->cropped_img);
        $request->validate([
            'cropped_img' => 'required|file|image|mimes:png|max:200',
        ]);
        $user_id = Auth::user()->id;
        $path = $request->file('cropped_img')->store('public/photos');
        $hash = hash_file('sha1', $request->file('cropped_img'));
        $link = str_replace('public', 'storage', $path);
        $photo = new Photo();
        $photo->hash_sum = $hash;
        $photo->path = $path;
        $photo->link = $link;
        $photo->user_id = $user_id;
        $photo->save();
        return response()->json([
            'success' => true,
        ]);
    }

    // stream
    public function video()
    {
        $path = public_path('storage/files/_video.mp4');
        VideoStreamer::streamFile($path);
    }

}
