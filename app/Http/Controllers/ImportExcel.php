<?php

namespace App\Http\Controllers;

use App\Imports\ExelImport;
use App\Models\blog;
use App\Models\Code;
use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Tag;
use Symfony\Component\Console\Input\Input;

class ImportExcel extends Controller
{
    //
    /**
     * @throws \Illuminate\Validation\ValidationException
     */
    public function import(Request $request)
    {
        if($request->has('p_id'))
        {
            if ($request->hasFile('select_file')) {
                $data=[];
                $errors=[];
                //todo add try catch
                $rows=Excel::toCollection(new ExelImport(), $request->file('select_file'))->toArray();
                $validator=Validator::make(['data'=>$rows[0]], [
                    "data"    => "required|array",
                    "data.*.code2"  => "required|unique:codes,code"
                ]);

                $errors=$validator->errors()->getMessages();
                $validated=[];
                if (count($errors)>0)
                {
                return response()->json(['status'=>false,'data'=>[],'error'=>$errors]);

                }
                else{
                    $validated=$validator->validated();
                }

                foreach ($validated['data'] as $value)
                {
                   $data[$value['code2']]=['product_id'=>$request->input('p_id'),'code'=>strval(@$value['code2'])];
                }
                try {
                    Code::query()->insert($data);
                    return response()->json(['status'=>true,'data'=>[],'error'=>$errors]);
                }catch (\Exception $exception){
                    return response()->json(['status'=>false,'data'=>[],'error'=>['خطا در ئخیره سازی']]);
                }

            }

            else{

                return response()->json(['status'=>false,"error" => "فایل مورد نظز یافت نشد"]);
            }
        }
        else{
            return response()->json(['status'=>false,"error" => "شناسه محصول الزامی است"]);
        }


        }
    public function GetImage($id=null){
       if ($id!=null) {
           if (is_numeric($id)){
               $tag=Tag::find($id);
               if ($tag){
                   if(Storage::disk('local')->exists($tag->image))
                   {

                       $content = Storage::disk('local')->get($tag->image);

                       return response($content)->header('Content-Type','image/jpeg');
                   }
                   else{
                       return response()->json("nothing find");
                   }
               }
               else{
                   return response()->json("موردی یافت نشد");
               }
           }
           else{
               return response()->json("only number");
           }
       }
       else{
           return response()->json("its important");
       }


    }
    public function GetCImage($id=null){
        if ($id!=null) {
            if (is_numeric($id) and $id!=0){
                $country=Country::find($id);
                if ($country){
                    if(Storage::disk('local')->exists($country->image))
                    {
                        $content = Storage::disk('local')->get($country->image);

                        return response($content)->header('Content-Type','image/jpeg');
                    }
                    else{
                        return response()->json("nothing find");
                    }
                }
                else{
                    return response()->json("موردی یافت نشد");

                }
            }
            else{
                return response()->json("فقط عدد");
            }
        }
        else{
            return response()->json("its important");
        }


    }
    public function GetBImage($id=null){
        if ($id!=null) {
            if (is_numeric($id) and $id!=0){
                $blog=blog::find($id);
                if ($blog){
                    if(Storage::disk('local')->exists($blog->image))
                    {
                        $content = Storage::disk('local')->get($blog->image);

                        return response($content)->header('Content-Type','image/jpeg');
                    }
                    else{
                        return response()->json("nothing find");
                    }
                }
                else{
                    return response()->json("موردی یافت نشد");

                }
            }
            else{
                return response()->json("فقط عدد");
            }
        }
        else{
            return response()->json("its important");
        }


    }


}
