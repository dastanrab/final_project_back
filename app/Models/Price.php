<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;


class Price extends Model
{
    use HasFactory ,Cachable;
    protected $table='prices';
    protected $fillable=['doller'];
    protected $visible=['doller','id'];
    public function products(){
        return $this->hasMany(Product::class);
    }
}
