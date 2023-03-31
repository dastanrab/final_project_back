<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;


class Country extends Model
{
    use HasFactory ,Cachable;
    protected $table='countries';
    public $visible=['id','name','tags'];
    protected $fillable=['name','image'];
    public function tags(){
        return $this->hasMany(Tag::class);
    }
}
