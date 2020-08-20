<?php

namespace App\Http\Requests\Financeiro;

use Illuminate\Foundation\Http\FormRequest;

class MovimentoFormRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }
    public function rules()
    {
        return [
            'categoria_id'   => 'required',
            'conta_id'       => 'required',
            'descricao'      => 'required|min:5|max:255',
            'valor'          => 'required'
        ];
    }
    public function messages()
    {
        return [
            'descricao.min'       => 'O campo nome tem q ter no minimo 5 caracteres!',
        ];
    }
}
