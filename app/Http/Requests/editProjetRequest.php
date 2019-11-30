<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class editProjetRequest extends FormRequest
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
            'nom' => 'required | min:3 |max:50|unique:projets,title',
            'url' => 'required | min:3 |URL|unique:projets,url',
            'upload' => ' image | max:2000',
            'description' => 'required | min:10 |max:225 ',
            'fichierB' => 'max:2000 | mimes:pdf,PDF,doc,docx',
            'fichierR' => 'max:2000 | mimes:pdf,PDF,doc,docx'
            //
        ];
    }
}
