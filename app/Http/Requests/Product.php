<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class Product extends FormRequest
{
    public function rules()
    {
        return [
            'tag_id'=> 'nullable|numeric|exists:tags,id',
            'credit_id' => 'numeric|nullable|exists:credits,id',
            'off_id' => 'numeric|nullable|exists:offers,id',
            'price_id' => 'numeric|nullable|exists:prices,id',
            'p_name' => 'nullable|string',
            'price'=>'numeric|nullable'
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


            'p_name.string'=> 'فقط  حروف مجاز هستند',
            'numeric'=> 'فقط عدد  مجاز میباشد',

        ];
    }
}
