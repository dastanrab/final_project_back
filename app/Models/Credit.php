<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;


class Credit extends Model
{
    use HasFactory ,Cachable;
    protected $table='credits';
    protected $fillable=['type','time'];
    protected $visible=['type','time','product'];
    public function product(){

        return $this->hasMany(Product::class);
    }
}
