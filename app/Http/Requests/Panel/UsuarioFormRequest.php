<?php

namespace App\Http\Requests\Panel;

use Illuminate\Foundation\Http\FormRequest;

class UsuarioFormRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }
    public function rules()
    {
        return [
            'empresa_id' => 'required',
            'name' => 'required|min:5|max:255',
            'email_principal' => 'required|email|max:255',
        ];
    }
    public function messages()
    {
        return [
            'name.required'  => 'O campo nome é obrigatório.',
            'name.min'       => 'O campo nome tem que ter no é obrigatório.',
            'email.required' => 'O campo preço é obrigatório.',
        ];
    }
}
