<?php

namespace App\Http\Controllers;

use App\Models\Price;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\Tag AS T;
use App\Models\Product as kala ;
use App\Models\Code AS C;
use App\Models\Credit;
use App\Models\Offer;

class Product extends Controller
{
    //
    private function responseMassage(bool $status, array $data)
    {
        return response()->json(['status' => $status, 'data' => $data]);
    }
    public function index(){


        $tag=new T();
        $min=0;
        $max=57000;
        //$data1=$tag->where('id',14)->with(['product'=> function($query){
           // $query->where('name','=','wewewe');
        //}])->get();

        //$data1=$products->with('tag')->get();
        //return response()->json($products->leftjoin('tags','products.tag_id','tags.id')->select('tags.*')->get());
         //$data=DB::table('products');

        //$products=DB::table('products')->join('tags','tags.id',"=",'products.tag_id')->select(['products.id','products.name','products.price','products.total_vote','products.total_avg','products.tag_id','tags.name as tag_name',])->groupBy(['products.tag_id','products.price'])->get();
        //$tags=DB::table('tags')->join('products','products.tag_id',"=",'tags.id')->groupBy(['products.tag_id','tags.name','tags.country'])->select(['tags.name','tags.country',DB::raw('count(products.tag_id) as total_products')])->get();
        //if (isset($max) and isset($min)) // ->select('tags.name',DB::raw('count(tags.name) as total'))->groupBy('tags.name')->get();
        // $data->where('products.name' ,'=',"wewewe");
        // $data->whereBetween('products.price',[$min,$max])->get();
       // $data=$data->select('*');
       // $data=$data->where('name','=','wewewe');
       // $data=$data->get();
       //
        //$data=Credit::with(['product'])->get();
//        return   \App\Models\Product::with(['offer'])->get()->count();

        $product=\App\Models\Product::with(['tag','tag.country','credit','offer','doller'])->get();

        return response()->json(['status'=>true,'data'=>$product]);


    }
    public function orderby($id=null){
  if ($id!=null)
  {
      $data=DB::table('products')->join('tags','tags.id',"=",'products.tag_id')->select(['products.id','products.name','products.price','products.total_vote','products.total_avg','products.tag_id','tags.name as tag_name','tags.country']);
      switch ($id){
          case 1:
              $data=$data->orderByDesc('products.created_at')->paginate(2);
              return response()->json($data);
          case 2:
              $data=$data->orderByDesc('products.total_vote')->get();
              return response()->json($data);
          case 3:
              $data=$data->orderBy('tags.country')->get();
              return response()->json($data);
          default:
              return response()->json("مرتب سازی مورد نظر یافت نشد");
      }
  }
  else{
      return response()->json("نوع مرتب سازی باید تعریف شود");
  }

    }

    public function add(Request $request){


        $customMessages = [
            'required' => 'وجود این فیلد ضروری می باشد',
            'numeric' => 'عدد را نا مناسب وارد کردید'
        ];
        $rules = [
            'tag_id' => 'required|numeric|exists:tags,id',
            'credit_id' => 'required|numeric|exists:credits,id',
            'price_id' => 'required|numeric|exists:prices,id',
            'p_name' => 'required|string',
            'code' => 'required|unique:codes,code',
            'price'=> 'required|numeric'
        ];
        $validator = Validator::make($request->only(['tag_id','p_name','code','price','credit_id','off_id','price_id']), $rules,$customMessages);
        if ($validator->passes()) {

           // where('name' ,'=',$request->input('p_name'))->
                $p=DB::table('products')->where('price_id','=',$request->input('price_id'))->where('credit_id','=',$request->input('credit_id'))->where('tag_id','=',$request->input('tag_id'))->first();
                if($p){
                  $code=new C();
                   $code->product_id=$p->id;
                   $code->code=$request->input('code');
                   if ($code->save()){
                       return response()->json("add");
                   }
                   else{

                       return response()->json('error in add code');
                   }

                }
                else{


                        $product=kala::create(['name'=> $request->input('p_name'),'tag_id'=>$request->input('tag_id'),'price'=>$request->input('price'),'credit_id'=>$request->input('credit_id'),'price_id'=>$request->input('price_id')]);
                        if ($product)
                        {
                            $code=new C();
                            $code->product_id=$product->id;
                            $code->code=$request->input('code');
                            if ($code->save()) {
                                return response()->json("add");
                            }
                            else{
                                return response()->json('error in add code');
                            }

                        }


                }



        } else {
            return response()->json($validator->errors()->all());
        }

    }
    public function edit($id,\App\Http\Requests\Product $request){
        if ($id==null){
            return response()->json('وجود این فیلد ضروری است');
        }
        else{
             $product=\App\Models\Product::find($id);
             if ($product)
             {
                 if ($request->input('name')!=null){
                     $product->name=$request->input('name');
                 }
                 if ($request->input('price')!=null){
                     $product->name=$request->input('price');
                 }
                 if ($request->input('tag_id')!=null){
                     $product->tag_id=$request->input('tag_id');
                 }
                 if ($request->input('credit_id')!=null){
                     $product->credit_id=$request->input('credit_id');
                 }
                 if ($request->input('off_id')!=null){
                     $product->offer_id=$request->input('off_id');
                 }
                 if ($request->input('price_id')!=null){
                     $product->price_id=$request->input('price_id');
                 }
                 if ($product->save()){
                     return response()->json('بروززسانی شد');
                 }
                 else{
                     return response()->json('مشکل در بروز رسانی');
                 }

             }
             else{
                 return response()->json('محصول یافت نشد');
             }
        }
    }
}
