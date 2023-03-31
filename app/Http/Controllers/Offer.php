<?php

namespace App\Http\Controllers;
use App\Models\Offer as O;
use Carbon\Carbon;
use Carbon\Traits\Creator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Validator;

class Offer extends Controller
{
    //
    public function add(Request $request){
        Artisan::call('optimize:clear');
        $customMessages = [

            'required' => 'وجود این فیلد ضروری می باشد',
            'numeric' => 'عدد را نا مناسب وارد کردید'
        ];
        $rules = [
            'p_id' => 'required|numeric|unique:offers,product_id|exists:products,id',
            'off' => 'required|integer|between:1,100',
            'day' => 'required|integer|between:0,30',
            'hour'=> 'required|integer|between:0,24',
        ];
        $validator = Validator::make($request->only(['off','day','p_id','hour']), $rules,$customMessages);
        if ($validator->passes()) {
                    $now=Carbon::now();
                    $now=$now->addDay($request->input('day'));
                    $now=$now->addHour($request->input('hour'));
                    $C=new O();
                    $C->off=$request->input('off');
                    $C->product_id=$request->input('p_id');
                    $C->finish=$now;
                    if ($C->save()){

                        return response()->json("اضافه شد");
                    }
                    else{

                        return response()->json("خطا در ذخیره سازی");
                    }


        } else {
            return response()->json($validator->errors()->all());
        }
    }
    public function update(Request $request,$id){
        Artisan::call('optimize:clear');
        if ($id!=null and $id!=0){
            $customMessages = [

                'required' => 'وجود این فیلد ضروری می باشد',
                'numeric' => 'عدد را نا مناسب وارد کردید'
            ];
            $rules = [
                'off' => 'required|integer|between:1,100',
                'day' => 'required|integer|between:0,30',
                'hour'=> 'required|integer|between:0,24',
            ];
            $validator = Validator::make($request->only(['off','day','hour']), $rules,$customMessages);
            if ($validator->passes()) {
                $C=O::find($id);
                if ($C){
                    $now=new Carbon($C->finish);
                    if ($now->isValid())
                    {
                        $now=$now->addDay($request->input('day'));
                        $now=$now->addHour($request->input('hour'));
                        $C->off=$request->input('off');
                        $C->finish=$now;
                        if ($C->save()){
                            return response()->json("اضافه شد");
                        }
                        else{
                            return response()->json("خطا در ذخیره سازی");
                        }
                    }
                    else
                    {
                        return response()->json("تاریخ معتبر نیست");
                    }

                }
                else{
                    return response()->json("موردی یافت نشد");
                }



            } else {
                return response()->json($validator->errors()->all());
            }
        }
        else{
           return response()->json('ایدی اشتباه است');
        }

    }
    public function delete($id){
        Artisan::call('optimize:clear');
        if ($id!=null and $id!=0){
            $C=O::find($id);
            if ($C){
                if ($C->delete()){

                    return response()->json('حذف شد');
                }
                else{
                    return response()->json(' خطا در حذف شد');
                }
            }else{
                return response()->json("موردی یافت نشد");
            }
        }
        else{
            return response()->json("ایدی اشتباه است");
        }



    }
}
