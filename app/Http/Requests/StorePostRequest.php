<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class StorePostRequest extends FormRequest
{
    public function rules()
    {
        return [
            'body' => 'required|string|min:3|max:1000',
            'vote' => 'required|numeric|between:0,5',
//            'user_id' => 'required|exists:users,id|numeric|unique:blocks,user_id',
            'p_id'=>'required|exists:products,id|numeric'
        ];
    }
    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success'   => false,
            'message'   => 'Validation errors',
            'data'      => $validator->errors()
        ]));
    }


    public function messages() //OPTIONAL
    {
        return [
            'body.required' => 'وجود این فیلد ضروری می باشد',
            'user_id.required' => 'وجود کشور ضروری می باشد',
            'user_id.exists' => 'این کاربر موجود نیست',
            'user_id.unique' => 'شما مسدود شده اید',
            'p_id.required' => 'وجود این فیلد ضروری می باشد',
            'p_id.exists' => 'این کالا موجود نیست',
            'vote.required' => 'این فیلد ضروری است',
            'vote.between' => 'امتیاز باید بین 0 تا 5 باشد',
            'vote.numeric' => 'باید امتیاز عددی باشد',
        ];
    }
}
