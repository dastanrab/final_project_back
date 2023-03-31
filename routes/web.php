<?php


use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use MyClass\Check_user as test ;
use App\Http\Controllers\ProjectsController;
use App\Services\Cart\CartFacade;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    $columns = Schema::getColumnListing('products'); // users table
    return $columns;
//
//    return view('welcome');
//    //$test = new test();
//    //return dd($test);

 $product=Product::query();
 return $product->select(['tag_id'])->get();
});
Route::get('/test3',function (){
    $user = new User();
    //return response()->json($product->tagname()->groupBy('tag_id')->get(),200);
    if ($user->is_admin(10)){
        return response()->json('ok',200);
    }
    else{
        return response()->json('wrong',200);
    }

});
Route::resource('projects', 'ProjectsController');
Route::get('test1',function (){
   // $data=Product::with('tag')->where('tag_id','=',21)->get();
    //\Illuminate\Support\Facades\Cache::put('xbox',$data->groupBy('country_id'),3600);
    //return \Illuminate\Support\Facades\Cache::get('xbox');
    return Cart::show();


});
