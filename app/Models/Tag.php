<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;


class Tag extends Model
{
    use HasFactory ,Cachable;
    protected $fillable=['name','image','country_id'];
    protected $visible=['id','name','country_id','product','country','product_count'];
    protected $table='tags';
    public function product()
    {
        return $this->hasMany(Product::class,'tag_id','id');
    }
    public function country(){
        return $this->belongsTo(Country::class);
    }

}
