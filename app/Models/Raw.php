<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Raw extends Model
{
    use HasFactory;
    protected $morphClass='Raw';
    protected $fillable=['user_id','cart'];
    public function pay()
    {
        return $this->morphOne(Pay::class, 'payable');
    }
}
