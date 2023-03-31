<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class blog extends Model
{
    use HasFactory;
    protected $table='blogs';
    protected $fillable=['name','body','image','user_id'];
    protected $visible=['id','name','body','created_at'];
    public function user(){
        return $this->belongsTo(User::class);
    }
}
