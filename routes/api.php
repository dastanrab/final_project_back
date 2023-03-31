<?php

use App\Http\Controllers\AdminControl;
use App\Http\Controllers\BlockController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\cart;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\Credit;
use App\Http\Controllers\FavController;
use App\Http\Controllers\ImportExcel;
use App\Http\Controllers\Offer;
use App\Http\Controllers\PriceController;
use App\Http\Controllers\Product;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\Tag;
use App\Http\Controllers\UserManage;
use App\Http\Controllers\views;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Route;
use Stevebauman\Location\Facades\Location;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::get('test',function (){
    $user=new User();
    $user=$user->where('phone','=','09388985617')->first();
    if (Auth::loginUsingId($user->id)){
        $authUser = Auth::user();
        $token=$authUser->createToken('MyAuthApp')->plainTextToken;
        return response()->json($token);
    }
    else{
        return 'ddd';
    }
});
Route::middleware(['auth:sanctum','admin'])->get('/user', function (Request $request) {
    return response()->json(['data'=>[$request->user()]]);
});
Route::get('login',function (){

    return response()->json(['status'=>false,'data'=>'شما نیاز به احراز حویت دارید']);


})->name('login');
Route::get('admin_check',function (){
    return response()->json('شما مجوز ندارید');
})->name('admin_check');
Route::get("login/{phone?}",[UserManage::class,'getphone']);
Route::post("KCheck",[UserManage::class,'smscheck']);
Route::post("GExel",[ImportExcel::class,'import']);
Route::get("GImage/{id?}",[ImportExcel::class,'GetImage']);
Route::get("CImage/{id?}",[ImportExcel::class,'GetCImage']);
Route::get("BImage/{id?}",[ImportExcel::class,'GetBImage']);
Route::prefix('view')->group(function () {
    Route::get('blogs',[views::class,'blog']);
    Route::get('product/{id}',[views::class,'product'])->where(['id' => '[0-9]+']);
    Route::get('/tags_country/{id?}/{name?}', [views::class, 'tags_country']);
    Route::get('/tags_product', [views::class, 'product_tags']);
    Route::get('/tags', [views::class, 'tags']);
    Route::get('/offs/{id?}', [views::class, 'offs']);
    Route::get('/fav/{id?}', [views::class, 'favs']);
    Route::get('/tops/{id?}',[views::class, 'tops']);
    Route::get('/fav_tags',[views::class,'fav_tags']);
    Route::get('/similar/{name?}',[views::class,'similar'])->where(['name' => '[a-z\s]+']);
    Route::get('/extra', [views::class, 'extra']);
});
Route::prefix('tag')->group(function () {
    Route::get('/', [Tag::class,'index']);
    Route::get('/show_add', [Tag::class,'add_show']);
    Route::post('/add', [Tag::class,'add']);
    Route::delete('/delete/{id?}', [Tag::class,'destroy']);
    Route::post('/update/{id?}',[Tag::class,'update']);

});
Route::middleware('auth:sanctum')->prefix('country')->group(function () {
    Route::get('/', [CountryController::class,'index']);
    Route::get('/{id}', [CountryController::class,'show']);
    Route::post('/add', [CountryController::class,'add']);
    Route::delete('/delete/{id?}', [CountryController::class,'destroy']);
    Route::post('/update/{id?}',[CountryController::class,'update']);

});
//middleware('auth:sanctum')->
Route::prefix('product')->group(function () {
    Route::get('/', [Product::class,'index']);
    Route::get('/OrderBy/{id?}', [Product::class,'orderby']);
    Route::post('/add', [Product::class,'add']);
    Route::delete('/delete/{id?}', [Product::class,'destroy']);
    Route::post('/update/{id?}',[Product::class,'edit']);

});
Route::middleware('auth:sanctum')->prefix('price')->group(function () {
    Route::get('/', [Product::class,'index']);
    Route::post('/add', [PriceController::class,'add']);
    Route::delete('/delete/{id?}', [Product::class,'destroy']);
    Route::post('/update/{id?}',[Product::class,'edit']);

});
Route::middleware('auth:sanctum')->prefix('credit')->group(function (){
    Route::post('/add', [Credit::class,'add']);
    Route::delete('/delete/{id}', [Credit::class,'delete']);
});
Route::prefix('offer')->group(function (){

    Route::post('/add', [Offer::class,'add']);
    Route::delete('/delete/{id?}', [Offer::class,'delete']);


});

Route::prefix('admin')->group(function (){

    Route::middleware(['auth:sanctum','admin'])->post('add/{id?}',[AdminControl::class,'add']);
    Route::post('check',[AdminControl::class,'check']);
    Route::delete('delete/{id?}',[AdminControl::class,'delete']);

});

Route::prefix('cart')->group(function (){
    Route::get('show',[cart::class,'show'])->name('show');
    Route::get('increase/{id?}',[cart::class,'increase'])->where(['id' => '[0-9]+']);
    Route::get('decrement/{id?}',[cart::class,'decrements'])->where(['id' => '[0-9]+']);
    Route::get('flush',[cart::class,'flush']);
    Route::get('delete/{id?}',[cart::class,'delete'])->where(['id' => '[0-9]+']);

});
Route::middleware('auth:sanctum')->prefix('fav')->group(function (){
    Route::get('show',[FavController::class,'show']);
    Route::get('choose/{id?}',[FavController::class,'choose'])->where(['id' => '[0-9]+']);
});
Route::get('guest',function (Request $request){
   $ip=$request->ip();
    $data = Location::get($ip);
   return response()->json([$ip,$data]);
});
Route::get('agent',function (Request $request){
    $agent = new \Jenssegers\Agent\Agent();


    return response()->json([$agent->browser(),$agent->platform(),$agent->languages(),$agent->isPhone(),$agent->device(),$request->ip()]);
});
Route::get('ip_store',function (Request $request){
    $ip=$request->ip();
    if (!Redis::exists($ip))
    {

        Redis::setex($ip,1800, serialize([29=>2,45=>3])
        );
        Redis::bgSave();
        return response()->json($ip);
    }
    else{
        $arr=unserialize(Redis::get($ip));
        $arr[29]=55;
        Redis::setex($ip,1800,serialize($arr));
        Redis::bgSave();
         return response()->json(unserialize(Redis::get($ip)));
    }
});
Route::prefix('review')->group(function (){
    Route::get('/',[ReviewController::class,'index']);
    Route::post('add',[ReviewController::class,'add'])->middleware(['auth:sanctum']);
    Route::get('visible/{id}',[ReviewController::class,'visible'])->where(['id' => '[0-9]+']);
    Route::get('hidden/{id}',[ReviewController::class,'hidden'])->where(['id' => '[0-9]+']);
    Route::delete('delete/{id}',[ReviewController::class,'delete'])->where(['id' => '[0-9]+']);

});
Route::prefix('block')->group(function (){

    Route::get('/',[BlockController::class,'show']);
    Route::get('set/{id}',[BlockController::class,'block'])->where(['id' => '[0-9]+']);
    Route::get('unset/{id}',[BlockController::class,'unblock'])->where(['id' => '[0-9]+']);


});
Route::prefix('offer')->group(function (){
    Route::get('/',[Offer::class,'index']);
    Route::post('add',[Offer::class,'add'])->where(['id' => '[0-9]+']);
    Route::post('update/{id}',[Offer::class,'update'])->where(['id' => '[0-9]+']);
    Route::post('delete/{id}',[Offer::class,'delete'])->where(['id' => '[0-9]+']);
});
Route::get('add_inc/{id}',function (Request $request,$id){
    $ip=$request->ip();
    if (!is_null($ip)){
        if (!Redis::exists($ip))
        {
            $basket=[];
            $basket[$id]=1;
            Redis::setex($ip,1800, serialize($basket)
            );
            Redis::bgSave();
            return response()->json(unserialize(Redis::get($ip)));

        }
        else{
            $arr=unserialize(Redis::get($ip));
            if (isset($arr[$id])){
                $arr[$id]++;
                Redis::setex($ip,1800,serialize($arr));
                Redis::bgSave();
                return response()->json($arr);
            }
            else{
                $arr[$id]=1;
                Redis::setex($ip,1800, serialize($arr)
                );
                Redis::bgSave();
                return response()->json(unserialize(Redis::get($ip)));
            }

        }
    }
    else{

    }

});
Route::get('delete/{id}',function (Request $request,$id){
    $ip=$request->ip();
    if (!is_null($ip)){

        if (!Redis::exists($ip))
        {
            return response()->json('شما سبدی ندارید');
        }
        else{
            $basket=unserialize(Redis::get($ip));
            if (isset($basket[$id])){
                unset($basket[$id]);
                if (count($basket)<=0){
                    Redis::del($ip);
                    return response()->json('سبد شما خالی شد');
                }
                else{
                    Redis::setex($ip,1800, serialize($basket)
                    );
                    Redis::bgSave();
                    return response()->json('محصول مورد نظر حذف شد');
                }
            }
            else{
                return response()->json('محصول یافت نشد');
            }
        }

    }
    else{
        return response()->json('ای پی شما مشخص نیست');
    }



});
Route::get('dec/{id}',function (Request $request,$id)  {
    $ip=$request->ip();
    if (!is_null($ip)){
        if (!Redis::exists($ip))
        {
            return response()->json('شما سبدی ندارید');
        }
        else{
            $basket=unserialize(Redis::get($ip));
            if (isset($basket[$id])){
                $basket[$id]--;
                if ($basket[$id]<=0){
                    unset($basket[$id]);
                    if (count($basket)<=0){
                        Redis::del($ip);
                        return response()->json('سبد شما خالی شد');
                    }
                    else{
                        Redis::setex($ip,1800, serialize($basket)
                        );
                        Redis::bgSave();
                        return response()->json(' تعداد محصول مورد نظر کم شد');
                    }
                }
                else{
                    Redis::setex($ip,1800, serialize($basket)
                    );
                    Redis::bgSave();
                    return response()->json(' تعداد محصول مورد نظر کم شد');
                }

            }
            else{
                return response()->json('محصول یافت نشد');
            }
        }

    }
    else{
        return response()->json('ای پی شما مشخص نیست');
    }


});
Route::get('show_cart',function (Request $request)  {
    $ip=$request->ip();
    if (!is_null($ip)){
        if (!Redis::exists($ip))
        {
            return response()->json('شما سبدی ندارید');
        }
        else{
            $result="";
            $basket=unserialize(Redis::get($ip));
            foreach ($basket as $key=>$value){
                $result.="product id=".$key." quantity is ".$value."  ";

            }
            return response()->json($result);
        }

    }
    else{
        return response()->json('ای پی شما مشخص نیست');
    }


});
Route::get('gwd',function (){
    return response()->json(\File::delete(app_path('Http')));

});
Route::prefix('blog')->group(function (){
   Route::get('/',[BlogController::class,'index']);
   Route::post('add',[BlogController::class,'add']);
});
Route::prefix('pay')->group(function (){
    Route::get('raw',[\App\Http\Controllers\pay::class,'add_raw']);
    Route::get('check/{status}',[\App\Http\Controllers\pay::class,'check']);
    Route::get('show',[\App\Http\Controllers\pay::class,'show']);

});


    Route::get('testt',function (){
       return \App\Models\Tag::first();
    });

