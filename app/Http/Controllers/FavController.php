<?php

namespace App\Http\Controllers;

use App\Models\Fav;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavController extends Controller
{
    //
    public function show(){
        $user_id=Auth::id();
        $favs=Fav::with(['product'=>function($q){
            $q->select(['id','name','price','tag_id','credit_id','price_id']);
        },'product.doller','product.credit','product.tag'])->where('user_id','=',$user_id)->get();
        return response()->json(['favs'=>$favs]);
    }
    public function choose($id=null){

        if ($id!=null or $id!=0 ){
            $user_id=Auth::id();
            if (\App\Models\Product::find($id)){
                $fav=Fav::where('product_id','=',$id)->where('user_id','=',$user_id)->first();
                if ($fav){
                    $fav->delete();
                    return response()->json('کالا از محبوب ها حذف شد');
                }
                else{

                    if (Fav::create(['user_id'=>$user_id,'product_id'=>$id])){
                        return response()->json('کالا به محبوب ها اضافه شد');
                    }else{

                        return response()->json('خطا در اضافه کردن');
                    }
                }
            }
           else{
               return response()->json('محصول مورد نظز یافت نشد');
           }
        }
        else{
            return response()->json('محصول مورد نظز یافت نشد');
        }
    }
}
