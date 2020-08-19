<?php

namespace App\Http\Requests\Financeiro;

use Illuminate\Foundation\Http\FormRequest;

class ContaFormRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }
    public function rules()
    {
        return [
            'descricao' => 'min:4',
        ];
    }
    public function messages()
    {
        return [
            'nome.min'=> 'O campo nome tem q ter no minimo 5 caracteres!',
        ];
    }
}
