<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Fail extends Model
{
    use HasFactory;
    protected $morphClass='Fail';
    protected $fillable=['user_id','cart'];
    public function pay()
    {
        return $this->morphOne(Pay::class, 'payable');
    }
}
