<?php

namespace Tests\Http\Controllers;

use App\Http\Controllers\BlockController;
use App\Models\Block;
use App\Models\User;

class BlockControllerTest extends \PHPUnit\Framework\TestCase
{

    public function testAuthorize()
    {

    }

    public function testAuthorizeForUser()
    {

    }

    public function test__call()
    {

    }

    public function testGetMiddleware()
    {

    }

    public function testValidate()
    {

    }

    public function testValidateWithBag()
    {

    }

    public function testUnblock()
    {

    }

    public function testValidateWith()
    {

    }

    public function testMiddleware()
    {

    }

    public function testCallAction()
    {

    }

    public function testBlock($id)
    {

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

    public function testAuthorizeResource()
    {

    }

    public function testDispatchNow()
    {

    }

    public function testDispatchSync()
    {

    }
}
