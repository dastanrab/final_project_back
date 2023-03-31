<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class updateTag extends FormRequest
{
    public function rules()
    {
        return [
            'id'=> 'required|numeric',
            'name' => 'string|nullable|max:15',
            'country_id' => 'nullable|numeric|exists:countries,id',
            'image' => 'image|mimes:jpg,png,jpeg,gif,svg|max:512'
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
            'id.required' => 'وجود این فیلد ضروری می باشد',
            'name.regex' => 'فقط حرف مجاز است',
            'name.alpha-num'=> 'فقط عدد و حروف مجاز هستند',
            'image.image' => 'فایل انتخابی باید عکس باشد',
            'image.mimes' => 'فرمت نادرست است',
            'image.max' => 'اندازه بیش از حد مجاز',

        ];
    }
}
