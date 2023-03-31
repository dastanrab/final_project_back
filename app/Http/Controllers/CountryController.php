<?php

namespace App\Http\Controllers;
use App\Http\Requests\CountryRule;
use App\Models\Country;
use App\Models\Tag as T;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CountryController extends Controller
{
    //
    public function index(){
        $country=Country::all();
        return response()->json($country);
    }
    public function show($id){
        $country=Country::find($id);
        if ($country){
            return response()->json($country);
        }
        else{
            return response()->json('موردی یافت نشد');
        }
    }
    public function add(CountryRule $request){
        $file1=$request->file('image');
        $name=time().".".$file1->extension();
        if($file1->storeAs('/country/',$name)){
            $country = Country::create([
                'name' => $request->input('name'),
                'image'=> '/country/'.$name
            ]);
            return response()->json($country);
        }
        else{
            return response()->json("مشکل در ذخیره سازی");
        }
    }
    public function destroy($id=null){
      if ($id==null){
          return response()->json("وجود id ضروری است");
      }
      else{
          $country=Country::find($id);
          if ($country){
              $tag=T::where('country_id','=',$id)->first();
              if ($tag){
                  return response()->json("این کشور در چند دسته بندی استفاده شده و قابل حذف نیست");
              }
              else {
                  if (Storage::disk('local')->exists($country->image)) {
                      if (Storage::disk('local')->delete($country->image)) {
                          if ($country->delete()) {

                              return response()->json("حذف شد");
                          } else {

                              return response()->json("اشکال در حذف از جدول");
                          }

                      } else {

                          return response()->json("اشکال در حذف فایل");
                      }
                  }

                  else{
                      return response()->json("فایل موجود نیست");
                  }
              }
          }else{
              return response()->json("کشور مورد نظز یافت نشد");
          }

      }
    }
    public function update(){}
}
