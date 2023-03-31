<?php

namespace App\Http\Controllers;

use App\Models\Block;
use App\Models\User;
use Illuminate\Http\Request;


class BlockController extends Controller
{
    //
    public function block($id){
        $block=Block::where('user_id','=',$id)->first();
        if (User::find($id) and !$block){
            if (Block::create(['user_id'=>$id])){
                return response()->json('کاربر مسدود شد');
            }
            else{
                return response()->json('خطا در مسدود سازی');
            }
        }
        else{

            return  response()->json("این کاربر قبلا مسدود شده");
        }
    }

    public function unblock($id){
        $block=Block::where('user_id','=',$id)->first();
        if (User::find($id) and $block){
           if ($block->delete()){
               return  response()->json('رفع مسدودی انجام شد');
           }
           else{
               return  response()->json('خطا در رفع مسدودی');
           }
        }
        else{

            return  response()->json("این کاربر قبلا مسدود شده");
        }
    }
    public function show()
    {
        return response()->json(['status'=>true,'data'=>Block::all()]);
    }

}
