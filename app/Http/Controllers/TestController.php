<?php
namespace App\Http\Controllers;
use App\Http\Requests\TestFormRequest;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Validator;
use App\Models\Photo;
use Illuminate\Http\UploadedFile;


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
        $request->validate([
            'images' => 'array',
            'images.*' => 'file|image|mimes:jpg,jpeg,png|max:2000',
        ]);

        //
        $links = [];
        if($request->images){
            foreach ($request->images as $img) {
               $hash = hash_file('sha1', $img->path());
//               dd($hash);
               // проверка по хеш суммам нет ли уже такого файла
               if(!Photo::where('hash_sum', $hash)->count()){
                   $path = $img->store('public/photos');
                   $link = str_replace('public', 'storage', $path);
                   $photo = new Photo();
                   $photo->hash_sum = $hash;
                   $photo->path = $link;
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
        $path = str_replace('storage', 'public', $path);
        $deleteFile = Storage::delete($path);
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


        $path = $request->file('cropped_img')->store('public/photos');
        $hash = hash_file('sha1', $request->file('cropped_img'));
        $link = str_replace('public', 'storage', $path);
        $photo = new Photo();
        $photo->hash_sum = $hash;
        $photo->path = $link;
        $photo->save();
        return response()->json([
            'success' => true,
            'photo' => $link,
        ]);
    }

}
