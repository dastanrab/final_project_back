<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePostRequest;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class ReviewController extends Controller
{
    //
    public function index(){
       return response()->json(['status'=>true,'data'=>Review::all()]);
    }
    public function add(StorePostRequest $request){
        $user_id=auth()->id();
        Artisan::call('optimize:clear');
        if (Review::where('user_id','=',$user_id)->where('product_id','=',$request->input('p_id'))->first()){
            return  response()->json("شما قبلا برای این محصول نظز داده اید");
        }
        else{
            $flag=0;
            $arr=['fuck','shit','shiit','ass','dick'];
            foreach ($arr as $string){
                if (preg_match("/.$string./i", $request->input('body')))
                {
                    $flag=1;
                }
            }
            if ($flag==0){
                $save=Review::create(['body'=>$request->input('body'),'vote'=>$request->input('vote'),'user_id'=>$user_id,'product_id'=>$request->input('p_id')]);
                if ($save){


                    return response()->json('نظر شما ثبت شد');
                }
                else{
                    return response()->json('اشکال در ثبت نظر');
                }
            }
            else{
                return response()->json('از کلمات نا مناسب استفاده کردید');
            }

        }

    }
    public function delete($id){
       if (Review::find($id)){
           Review::destroy($id);
           return response()->json("حذف شد");
       }
       else{
           return response()->json("نظر یافت شد");
       }
    }
    public function visible($id){
        $review=Review::find($id);
        if ($review){
            $review->status=1;
            if ($review->save()){
                return response()->json("تغییر کرد");
            }
            else{
                return response()->json("خطا در بروز رسانی");
            }

        }
        else{
            return response()->json("نظر یافت شد");
        }
    }
    public function hidden($id){
        $review=Review::find($id);
        if ($review){
            $review->status=0;
            if ($review->save()){
                return response()->json("تغییر کرد");
            }
            else{
                return response()->json("خطا در بروز رسانی");
            }

        }
        else{
            return response()->json("نظر یافت شد");
        }
    }
}
