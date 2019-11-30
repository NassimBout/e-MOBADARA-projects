<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class JuryRequest extends FormRequest
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
            'name' => 'required|string|max:50|min:3',
            'email' => 'required|string|email|max:255|unique:juries',
            'numero' => 'required|digits:10',
            'type' => 'required'
            //
            //
        ];
    }
}
