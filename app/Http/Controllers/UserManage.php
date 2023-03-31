<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use App\Traits\phone_validate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Validator;


class UserManage extends Controller
{

    use phone_validate;
    /**
     * @OA\GET(
     * path="/login",
     * summary="Sign in",
     * description="Login by PHONE",
     * operationId="authLogin",
     * tags={"auth"},
     * @OA\RequestBody(
     *    required=true,
     *    description="Pass user credentials",
     *    @OA\JsonContent(
     *       required={"phone"},
     *       @OA\Property(property="phone", type="string", format="text", example="09388985617"),
     *
     *    ),
     * ),
     * @OA\Response(
     *    response=422,
     *    description="Wrong credentials response",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Sorry, wrong email address or password. Please try again")
     *        )
     *     )
     * )
     */
    public function getphone($phone=null){

       return  $this->check($phone);
    }
    public function smscheck(Request $request){
        $customMessages = [
            'required' => 'وجود این فیلد ضروری می باشد',
            'digits' => 'عدد را نا مناسب وارد کردید'
        ];
        $rules = [
        'token' => 'required|digits:5',
    ];
        $validator = Validator::make($request->only(['token']), $rules,$customMessages);
         if ($validator->passes()) {

             if (Redis::exists($request->input('token'))){
                 $data=json_decode(Redis::get($request->input('token')));
                 $user=new User();
                 $user=$user->where('phone','=',$data->phone)->first();
                 if (Auth::loginUsingId($user->id)){
                     $authUser = Auth::user();
                     $authUser->tokens->each(function($token, $key) {
                         $token->delete();
                     });
                     $token=$authUser->createToken('MyAuthApp')->plainTextToken;
                     return response()->json($token);
                 }
                 else{
                     return response()->json("خطا در وزود");
                 }

             }
             else{
                 return response()->json('رمز عبور نادرست است');
             }


         } else {
          return response()->json($validator->errors()->all());
          }


    }
}
