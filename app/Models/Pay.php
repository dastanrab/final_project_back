<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pay extends Model
{
    use HasFactory;
    protected $fillable=['amount','user_id','f_id','payable_id','payable_type','status'];

    public function payable()
    {
        return $this->morphTo();
    }
}
