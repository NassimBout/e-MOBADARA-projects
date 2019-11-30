<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class soumissionRequest extends FormRequest
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
           // 'name' => 'required|string|max:50|min:3',
           
          // 'name' => 'bail|required|max:50|min:3|alpha',
           
          //mounia
            'name' => 'bail|required|max:50|min:6|string',
           
           // 'email' => 'required|string|email|max:255|unique:users',
           
           'email' =>'bail|required|email',
           'numero' => 'bail|required|digits:10',
           'nom' => 'bail|required | min:3 |max:50|unique:projets,title',
           'description' => 'bail|required | min:60 |max:225 ',
           'fichierR' => 'bail|required | max:20000 | mimes:pdf,PDF',
           'soustype' => 'bail|required',
           'type' => 'bail|required'
            //
        ];
    }
}
