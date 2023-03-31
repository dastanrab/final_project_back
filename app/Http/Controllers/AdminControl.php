<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Model as O;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use DateTime;


class AdminControl extends Controller
{
    //
    public function add(Request $request,$id=null){
        $customMessages = [
            'required' => 'وجود این فیلد ضروری می باشد',
            'numeric' => 'عدد را نا مناسب وارد کردید',
            'password.regex'=>'رمز عبور باید بالای 6 کاکتر و دارای عدد,حروف و نشانه ها باشد'
        ];
        $rules = [
            'username'=>'required|string|alpha_dash|max:10|unique:users,name',
            'password' => [
                'required',
                'string',
                'min:6',             // must be at least 10 characters in length
                'regex:/[a-z]/',      // must contain at least one lowercase letter
                'regex:/[A-Z]/',      // must contain at least one uppercase letter
                'regex:/[0-9]/',      // must contain at least one digit
                'regex:/[@$!%*#?&]/', // must contain a special character
            ]

        ];
        $validator = Validator::make($request->only(['password','username']), $rules,$customMessages);
        if ($validator->passes()) {
            if ($id==null){
                $phone=$request->input('phone');
                $pattern ="/^09(0[1-2]|1[0-9]|3[0-9]|2[0-1])-?[0-9]{3}-?[0-9]{4}/";
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
                elseif (User::where('phone','=',$phone)->first()){
                    return response()->json("این شماره قبلا ثبت شده");
                }
                else{

                    $user=new User();
                    $user->name = $request->input('username');
                    $user->password =Hash::make($request->input('password'));
                    $user->admin = 1;
                    $user->phone=$phone;
                    if ($user->save()) {
                        return response()->json('ذخیزه شد');
                    } else {
                        return response()->json('مشکل در ذخیره سازی');
                    }
                }
            }
            elseif(is_numeric($id)){
                $user=User::find($id);
                if ($user) {
                    $user->name = $request->input('username');
                    $user->password = Hash::make($request->input('password'));
                    $user->admin = 1;
                    if ($user->save()) {
                        return response()->json('ذخیزه شد');
                    } else {
                        return response()->json('مشکل در ذخیره سازی');
                    }
                }
                else{
                    return response()->json('کاربر یافت نشد');
                }
            }
            else{
                return response()->json('این ایدی دزست نیست');
            }
        } else {
            return response()->json($validator->errors()->all());
        }




    }
    public function delete($id=null){
        if ($id==null){
            return response()->json('وجود ایدی ضروری است');
        }
        elseif (is_numeric($id)){
            $user=User::find($id);
            if ($user){
                $user->admin=0;
                if($user->save()){
                    return response()->json('انجام شد');
                }
                else{
                    return response()->json('خطا در انجام عملیات');
                }
            }
            else{
                return response()->json('کابر یافت نشد');
            }
        }
        else{
            return response()->json('فرمت نامناسبی را برای ایدی انتخاب کردید');
        }
    }
    public function check(Request $request){
        $customMessages = [
        'required' => 'وجود این فیلد ضروری می باشد',
        'numeric' => 'عدد را نا مناسب وارد کردید'
    ];
        $rules = [
            'username' => 'required|string',
            'password' => 'required|string',

        ];
        $validator = Validator::make($request->only(['username','password']), $rules,$customMessages);
        if ($validator->passes()) {
            $user=User::where('name','=',$request->input('username'))->first();
            if ($user)
            {
                if (Hash::check( $request->input('password'),$user->password)) {

                    if(Auth::loginUsingId($user->id)){
                           $authUser = Auth::user();
                           $authUser->tokens->each(function($token, $key) {
                               $token->delete();
                           });
                          // Auth::user()->tokens()->where('id', $user->id)->delete();
                           $token=$authUser->createToken('MyAuthApp')->plainTextToken;
                           //  $dt = Carbon::now();
                             //$date=new DateTime('2020-11-03');
                        return response()->json($token);

                   }
                   else{
                       return response()->json('خطا در ثبت نام');
                   }


                }
                else{
                      return response()->json('پسورد یا نام کازبری نادرست است');
                }
            }
            else{
                return response()->json('پسورد یا نام کازبری نادرست است');
            }

        } else {
            return response()->json($validator->errors()->all());
        }


    }
}
