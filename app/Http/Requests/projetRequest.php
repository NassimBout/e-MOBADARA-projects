<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class projetRequest extends FormRequest
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
            /*
            'nom' => 'required | min:3 |max:50|unique:projets,title',
            'url' => 'nullable | URL|unique:projets,url',
            'apk' => 'nullable | mimes:apk| max:4000',
            'description' => 'required | min:10 |max:225 ',
            'upload' => ' image | max:2000',
            'fichierB' => 'max:2000 | mimes:pdf,PDF,doc,docx',
            'fichierR' => 'required | max:2000 | mimes:pdf,PDF,doc,docx',
            'soustype' => 'required',
            'type' => 'required',
            //*/
        ];
    }
}
