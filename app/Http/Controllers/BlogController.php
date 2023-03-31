<?php

namespace App\Http\Controllers;

use App\Http\Requests\blog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class BlogController extends Controller
{
    //
    public function index(){
        return response()->json(['status'=>true,'data'=>\App\Models\blog::all()]);
    }
    public function add(blog $request){
        $user=Auth::loginUsingId(24);
        if ($user->admin==1){
            if($request->hasFile('image')){
                $file=$request->file('image');
                $name=time().".".$file->extension();
                $img=Image::make($file->getRealPath());
                $img->resize(500, 500, function ($constraint) {
                    $constraint->aspectRatio();
                });
                if(Storage::disk('local')->put('/blog/'.$name,  (string) $img->encode(), 'public')){
                    $blog = \App\Models\blog::create([
                        'name' => $request->input('name'),
                        'body'=>$request->input('body'),
                        'image'=> '/blog/'.$name,
                        'user_id'=>$user->id
                    ]);
                    if ($blog){
                        return response()->json(['status'=>'ذخیره شد']);
                    }
                    else{
                        Storage::disk('local')->delete('/blog/'.$name);
                        return response()->json(['status'=>'خطا در ذخیره سازی']);
                    }

                }
                else{
                    return response()->json('خطا در ذخیره سازی');
                }
            }
        }
        else{
            return response()->json("شما ادمین نیستید");
        }
    }
}
