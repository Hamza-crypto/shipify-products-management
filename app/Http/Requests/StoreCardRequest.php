<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCardRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'card_number' => 'required',
            'month' => 'required',
            'year' => 'required',
            'cvc' => 'required',
            'pin' => 'required',
            'amount' => 'required|numeric|gt:0|lte:500',
        ];
    }
}
