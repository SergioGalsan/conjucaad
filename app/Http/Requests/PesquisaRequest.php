<?php namespace App\Http\Requests;

use App\Http\Requests\Request;

class PesquisaRequest extends Request {

    /**
     * Determine if the user is authorized
     * to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply
     * to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "NO_TITULO" => "required|max:100",
            "ST_MULTIPLA_RESPOSTA" => "required|max:3",
			"ST_ACESSO_FORM" => "required|max:50",
			"ST_ACESSO_PREENCHIMENTO" => "required|max:50",
			"ST_EDITAR_RESPOSTA" => "required|max:50",
        ];
    }

    public function messages()
    {	
		return [
            "required" => "O campo :attribute é obrigatório", 
			"max" => "Número de caracteres superior ao permitido (:max) para o campo :attribute.",
        ];
    }
}