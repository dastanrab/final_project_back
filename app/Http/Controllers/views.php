<?php

namespace App\Http\Controllers;
use App\Models\blog;
use App\Models\Country;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use App\Models\Product as kala ;
use  App\Models\Tag;
use Illuminate\Routing\UrlGenerator;


class views extends Controller
{
    //

    public function tags_country(Request $request,$id=null,$name=null){

        if (is_numeric($id) and $id!=null and $id!=0 and \App\Models\Product::where('tag_id','=',$id)->first()){
            $tag=Tag::with('country')->where('id','=',$id)->first();
            $tags_country=new \App\Models\Product();
            $result=$tags_country->with('tag','tag.country','credit','doller')->where('tag_id','=',$id);
            if ($request->has('min') and $request->has('max') and is_numeric($request->input('min') ) and $request->input('min') !=null and is_numeric($request->input('max') ) and $request->input('max') !=null){
                $result=$result->whereBetween('price',[$request->input('min') ,$request->input('max')]);
            }
            if ($request->has('rate') and is_numeric($request->input('rate') ) and $request->input('rate') !=null){
                $result=$result->where('total_avg','<=',$request->input('rate'));
            }
            $prices=$result;
           // $prices=$prices->orderByDesc('price')->groupBy('price')->pluck('price')->get();
            $result=$result->get();
            //
            $prices=new \App\Models\Product();
            $prices=$prices->prices2($id);
            return response()->json([$result,$tag,$prices,$name]);
        }
        else{
            return response()->json("ناموجود");
        }
    }
    public function product_tags(Request $request){
           $tag=\App\Models\Tag::has('product')->groupBy('name')->select('name')->get();
           $product_tags=new \App\Models\Product();
           $result=$product_tags;
        if ($request->has('sort') and is_numeric($request->input('sort') ) and $request->input('sort') !=null){
            $result=$result->order_by($request->input('sort'));
        }
           if ($request->has('name') and $request->input('name')!=null and strlen($request->input('name'))<=25){
               $tags=explode(',',$request->input('name'));

               $result=$result->whereHas('tag',function (Builder $query) use($tags){
                   if (count($tags)>=1){
                       $i=0;
                       foreach ($tags as $tag){
                           if ($tag!=null){
                               if ($i==0){
                                   $query=$query->where('name', '=',  $tag );
                               }
                               else{
                                   $query=$query->orWhere('name', '=',  $tag );
                               }

                           }
                           $i++;
                       }
                   }
               });
           }
           if ($request->has('country') and $request->input('country')!=null and strlen($request->input('country'))<=10 and Country::where('name','=',$request->input('country'))->exists()){
               $name=$request->input('country');
               $result=$result->whereHas('tag',function (Builder $query) use($name){
                   $query->whereHas('country',function (Builder $query) use($name){
                       $query->where('name','=',$name);
                   });

               });
           }
           if ($request->has('min') and $request->has('max') and is_numeric($request->input('min') ) and $request->input('min') !=null and is_numeric($request->input('max') ) and $request->input('max') !=null){
               $result=$result->whereBetween('price',[$request->input('min') ,$request->input('max')]);
           }
           if ($request->has('rate') and is_numeric($request->input('rate') ) and $request->input('rate') !=null){
               $result=$result->where('total_avg','<=',$request->input('rate'));
           }

           $result=$result->with('tag','tag.country','credit','doller')->get();
           $prices=new \App\Models\Product();
           $prices=$prices->prices($request->input('name'));
           $rates=[1,2,3,4,5];
           //$parsedUrl = parse_url(url()->full());
            $country=new  \App\Models\Product();
            $country=$country->country($request->input('name'));
           return response()->json(['product'=>$result,'prices'=>$prices,'rates'=>$rates,'tags_name'=>$tag,'countries'=>$country,'url_data'=>$request->all()]);

    }
    public function tags(){
        $tags=Tag::has('product')->with('country')->get();
        return response()->json(['status'=>true,'data'=>$tags]);
    }
    public function offs($id=null){

        $offs=\App\Models\Offer::has('product')->with('product','product.tag','product.doller','product.credit','product.tag.country')->orderBy('off', 'desc');
        if ($id==null or $id==0){
            $offs=$offs->take(30)->get();
            return response()->json(['tops'=>$offs]);
        }
        elseif(!is_numeric($id)){
            $offs=$offs->take(30)->get();
            return response()->json(['tops'=>$offs]);
        }
        else{
            $offs=$offs->take($id)->get();
            return response()->json(['tops'=>$offs]);
        }
    }
    public function favs($id=null){
        $fav=kala::with(['credit','doller','tag','tag.country'])->orderByDesc('total_avg');
        if ($id==null or $id==0){
            $fav=$fav->get();
            return response()->json($fav);
        }
        elseif(!is_numeric($id)){
            $fav=$fav->get();
            return response()->json($fav);
        }
        else{
            $fav=$fav->take($id)->get();
            return response()->json($fav);
        }

    }
    public function tops($id=null){
        $product=\App\Models\Product::with(['tag','tag.country','credit','doller'])->orderByDesc('total_sold');
        if ($id==null or $id==0){
            $product=$product->get();
            return response()->json($product);
        }
        elseif(!is_numeric($id)){
            $product=$product->get();
            return response()->json($product);
        }
        else{
            $product=$product->take($id)->get();
            return response()->json($product);
        }
    }
    public function fav_tags(){
     $result=\App\Models\Tag::has('product')->groupBy('name')->withCount('product')->get();

        return response()->json($result);
    }
    public function similar($name =null){
    if ($name!=null and Tag::has('product')->where('name', '=', $name)->exists() and strlen($name)<=15){
        $data=\App\Models\Product::whereHas('tag',function (Builder $query) use($name){
            $query->where('name', '=', $name );
        })->with('tag','tag.country','credit','doller')->orderByDesc('total_sold')->take(5)->get();
        return response()->json(['simlars'=>$data]);
    }
    else{
        return response()->json('محصول مشابهی یافت نشد');
    }
}
    public function product($id){
        $product=kala::query()->where('id',$id)->with(['credit','doller','tag','tag.country','offer'])->first();
        if ($product){
            return response()->json(['status'=>true,'data'=>$product]);
        }
        else{
            return response()->json(['ststus'=>false,'data'=>'محصول موجود نیست']);
        }
    }
    public function blog($id=null){

        if ($id==null or $id==0){
            $blog=blog::with(['user'=>function($q){
               $q->select('name');
            }])->get();
            return response()->json(['blogs'=>$blog]);
        }
        elseif(!is_numeric($id)){
            $blog=blog::with(['user'=>function($q){
                $q->select('name');
            }])->get();
            return response()->json(['blogs'=>$blog]);
        }
        else{
            $blog=blog::with(['user'=>function($q){
                $q->select('name');
            }])->take($id)->get();
            return response()->json(['blogs'=>$blog]);
        }
    }
}

