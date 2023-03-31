<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;

class Review extends Model
{
    use HasFactory,Cachable;
    protected $table='reviews';
    protected $fillable=['body','vote','user_id','product_id','status'];
    public function user(){
       return $this->belongsTo(User::class);
    }
    public function product(){
       return $this->belongsTo(Product::class);
    }
}
