<?php

namespace App\Http\Controllers;

use App\Models\Deliver;
use App\Models\Fail;
use App\Models\Raw;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class pay extends Controller
{
    //
    public function add_raw(){
        $RAW=Raw::create(['user_id'=>24,'cart'=>'its just a test lol ,dont be sad']);
        if ($RAW){
            $PAY=new \App\Models\Pay();
            $PAY->user_id=24;
            $PAY->f_id=rand(1,999999);
            $PAY->amount=150000;
            if ($RAW->pay()->save($PAY)){
                return response()->json('درخواست پرداخت ایجاد شد');
            }
            else{
                return response()->json('خطا در ایجاد درخواست پرداخت');
            }

        }else{
            return response()->json('مشکل در انتقال سبد');
        }


    }
    public function check($status){
        $pay=\App\Models\Pay::with('payable')->first();
        $raw_id=$pay->payable_id;
        if ($pay->status==null){
            if ($status=='ok'){
                $deliver=Deliver::create(['user_id'=>$pay->payable->user_id,'cart'=>$pay->payable->cart]);
                if ($deliver){
                    if ($deliver->pay()->save($pay)){
                        Raw::destroy($raw_id);
                        $pay->status='ok';
                        if ($pay->save()){
                            return response()->json(' و انتقال به صف پرداخت با موفقیت پرداخت و به لیست تسویه منتقل شد');
                        }
                        else{
                            return response()->json(' خطا و انتقال به صف تعیین شدن به عنوان موفق');
                        }

                    }
                    else{
                        return response()->json('خطا در انتقال ارسال به صف بررسی مجدد پرداخت ها');
                    }
                }
                else{
                    return response()->json('خطا در انتقال سبد');
                }
            }
            else{
                $fail=Fail::create(['user_id'=>$pay->payable->user_id,'cart'=>$pay->payable->cart]);
                if ($fail){
                    if ($fail->pay()->save($pay)){
                        Raw::destroy($raw_id);
                        $pay->status='false';
                        if ($pay->save()){
                            return response()->json(' و انتقال به صف پرداخت با موفقیت پرداخت و به لیست تسویه منتقل شد');
                        }
                        else{
                            return response()->json(' خطا و انتقال به صف تعیین شدن به عنوان موفق');
                        }
                    }
                    else{
                        return response()->json('خطا در انتقال ارسال به صف بررسی مجدد پرداخت ها');
                    }
                }
                else{
                    return response()->json('خطا در انتقال سبد');
                }
            }
        }
        else{
            return response()->json('این محصول قبلا تعیین وضعیت شده است');
        }


    }
    public function show(){
        $pay=\App\Models\Pay::with('payable')->where('payable_type','<>','raw')->get();
        return response()->json($pay);
    }
}
