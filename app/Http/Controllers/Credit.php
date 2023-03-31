<?php

namespace App\Http\Controllers;

use App\Models\Credit as C;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class Credit extends Controller
{
    //
    public function add(Request $request){
        $customMessages = [
            'required' => 'وجود این فیلد ضروری می باشد',
            'numeric' => 'عدد را نا مناسب وارد کردید'
        ];
        $rules = [
            'type' => 'String|Nullable',
            'date' => 'Nullable|Integer',

        ];
        $validator = Validator::make($request->only(['type','date']), $rules,$customMessages);
        if ($validator->passes()) {
          if ($request->input('type')==null and $request->input('date')==null)
          {
              $C=new C();
              if ($C->where('type',$request->input('type'))->where('time',$request->input('date'))->first()){

                  return response()->json("موجود است.");
              }
              else{
                  $C->type=$request->input('type');
                  $C->time=$request->input('date');
                  if ($C->save()){

                      return response()->json("اضافه شد");
                  }
                  else{

                      return response()->json("خطا در ذخیره سازی");
                  }
              }
          }
          elseif ($request->input('type')==null or $request->input('date')==null)
          {
             return response()->json('این حالت مجاز نیست');
          }
          else{
              if (C::where('type',$request->input('type'))->where('time',$request->input('date'))->first()){

                  return response()->json("این حالت موجود است");
              }
              else{
                  $c=new C();
                  $c->type=$request->input('type');
                  $c->time=$request->input('date');
                  if ($c->save()){

                      return response()->json("اضافه شد");
                  }
                  else{

                      return response()->json("خطا در ذخیره سازی");
                  }
              }

          }

        } else {
            return response()->json($validator->errors()->all());
        }

    }
    public function delete($id=null){
       if ($id==null){
           return response()->json("وجود این پارامتر ضروری است");
       }
       elseif (!is_numeric($id))
       {
           return response()->json("فقط عدد مجاز است");
       }
       else{
           $c=C::find($id);
           if ($c){
               if (Product::where('credit_id',$id)->first()){
                   return response()->json("این مورد در چند کالا در حال استفاده است");
               }
               else{

                   if ($c->delete()){
                       return response()->json("حذف شد");
                   }
                   else{
                       return response()->json("خطا در حذف");
                   }
               }
           }
           else{
               return response()->json("این شماره یافت نشد");
           }
       }

    }
}
