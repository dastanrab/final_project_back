<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class CountryRule extends FormRequest
{
    public function rules()
    {
        return [
            'name' => 'required|alpha-num|unique:countries,name',
            'image' => 'required|image|mimes:jpg,png,jpeg,gif,svg|max:512'
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
            'name.required' => 'وجود این فیلد ضروری می باشد',
            'image.required' => 'وجود این فیلد ضروری می باشد',
            'name.string' => 'فقط حرف مجاز است',
            'image.image' => 'فایل انتخابی باید عکس باشد',
            'image.mimes' => 'فرمت نادرست است',
            'image.max' => 'اندازه بیش از حد مجاز'
        ];
    }
}
