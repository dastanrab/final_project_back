<?php
namespace App\Traits;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use Nette\Utils\Random;
use App\Models\User;
use SoapClient;

trait phone_validate
{
    public function send_sms($phone,$code){
        $url = "https://portal.amootsms.com/webservice2.asmx/SendQuickOTP_REST";
        $url = $url."?"."UserName=09300693786";
        $url = $url."&"."Password=sina4244";
        $url = $url."&"."Mobile=".$phone;
        $url = $url."&"."CodeLength=5";
        $url = $url."&"."OptionalCode=".$code;

        $json = file_get_contents($url);
          $result = json_decode($json);
          return $result;




    }



    public function check($phone){

        $pattern ="/^09(0[1-3]|1[0-9]|3[0-9]|2[0-1])-?[0-9]{3}-?[0-9]{4}/";
        if ($phone==null)
        {
            return response()->json('شماره را وارد کنید');
        }
        elseif (!is_numeric($phone))
        {
            return response()->json('فقط عدد مجاز است');
        }
        elseif (!preg_match($pattern,$phone))
        {
            return   response()->json('قالب شماره شما نادرست است');
        }
        elseif (strlen(strval($phone)) >11)
        {
            return   response()->json('حداکثر طول 11 عدد میباشد');
        }
        else{

            $user = User::firstOrCreate([
                'phone' => strval($phone)
            ]);
            //Auth::loginUsingId($user->id);
            $token=random_int(10000,99999);
            if (!Redis::exists($token))
            {

                Redis::setex($token,120, json_encode([
                    'token' => $token,
                    'phone' => $user->phone
                ]));
                Redis::bgSave();
                $value=json_decode(Redis::get($token));
                //$result=$this->send_sms(substr(strval($phone),1),strval($token));
                return response()->json($token);
            }
            else{

                return response()->json("دوباره سعی کنید",200);
            }



        }


    }




}
