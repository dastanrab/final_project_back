<?php

namespace App\Http\Controllers;

use App\Models\Price;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PriceController extends Controller
{
    //
    public function add(Request $request){
        $customMessages = [
            'required' => 'وجود این فیلد ضروری می باشد',
            'numeric' => 'عدد را نا مناسب وارد کردید'
        ];
        $rules = [
            'doller' => 'Integer|Nullable|unique:prices,doller',


        ];
        $validator = Validator::make($request->only(['doller']), $rules,$customMessages);
        if ($validator->passes()) {
            $prices=new \App\Models\Price();
            $prices=$prices->where('doller','=',null)->first();
            if ($request->input('doller')==null and $prices )
            {
                return response()->json("این حالت موجود است");
            }
            else{
                $price=new Price();
                $price->doller=$request->input('doller');
                if ($price->save())
                {
                    return response()->json("ذخیره شد");
                }
                else{
                    return response()->json("مشکل در ذخیره سازی");
                }
            }


        } else {
            return response()->json($validator->errors()->all());
        }
    }
}
