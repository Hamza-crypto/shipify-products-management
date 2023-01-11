<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }


    public function rules()
    {
        return [
                'card_number' => 'required',
                'month' => 'required|max:2',
                'year' => 'required|max:2',
                'cvc' => 'required|max:4',
                'amount' => 'required|numeric|gt:0|lte:500',
        ];
    }
}
