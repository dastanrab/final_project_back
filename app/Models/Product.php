<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;




class Product extends Model
{
    use HasFactory, Cachable
;
    protected $fillable=['name','price','total_vote','total_avg','tag_id','credit_id','offer_id','price_id','country_id'];
    protected $visible=['id','name','price','total_avg','total_vote','tag_id','credit_id','tag','credit','tagname','offer','codes_count','price_id','doller','review','reviews','prices','country','order_by','country_id'];
    protected $table='products';
    public function tag(){
        return $this->belongsTo(Tag::class);
    }
    public function tagname(){
        return $this->with(['tag' => function ($query){
            $query->select('id','name','country_id');
        },'tag.country']);
    }
    public function credit(){
        return $this->belongsTo(Credit::class);
    }
    public function codes(){
        return $this->hasMany(Code::class);
    }
    public function reviews(){
        return $this->hasMany(Review::class);
    }
    public function doller(){
        return $this->belongsTo(Price::class,'price_id','id');
    }
    public function review(){
        return $this->belongsToMany(User::class,'reviews','product_id','user_id')->withPivot();
    }
    public function offer()
    {
        return $this->hasOne(Offer::class);
    }
    public function prices($name){
        $prices=$this;
        if (!is_null($name)){
            $tags=explode(',',$name);
            if (count($tags)>=1){

                $prices=$prices->whereHas('tag',function (Builder $query) use($tags){
                    $i=0;
                    foreach ($tags as $tag){
                        if ($tag!=null){
                            if ($i==0){
                                $query->where('name', '=',  $tag );
                            }
                            else{
                                $query->orWhere('name', '=',  $tag );
                            }
                        }
                        $i++;

                    }

                });
            }


        }
        $prices=$prices->get();
        return $prices->unique('price')->pluck('price');
    }public function prices2($id){
    $prices=$this->where('id','=',$id)->get();
    return $prices->unique('price')->pluck('price');
}
    public function country($name){
        $country=new Country();
        if (!is_null($name)){
            $tags=explode(',',$name);
            if (count($tags)>=1){

                $country=$country->whereHas('tags',function (Builder $query) use($tags){
                    $i=0;
                    foreach ($tags as $tag){
                    if ($tag!=null){
                        if ($i==0){
                            $query->where('name', '=',  $tag );
                        }
                        else{
                            $query->orWhere('name', '=',  $tag );
                        }
                    }
                    $i++;

                }
            });


        }

}
        $country=$country->get();
        return $country;
}
public function order_by($id){
        switch ($id){
            case 1:
                return $this->orderByDesc('created_at');
            case 2:
                return $this->orderByDesc('total_avg');
            case 3:
                return $this->orderByDesc('total_sold');
            case 4:
                return $this->orderByDesc('price');
            case 5:
                return $this->orderBy('price');
            default:
                return $this;
        }
}
}

