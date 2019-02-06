<?php namespace App\Http\Requests;

use App\Http\Requests\Request;

class UsuarioRequest extends Request {

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
            "NO_USUARIO" => "required|max:80",
            "NR_SEXO" => "required",
			"DT_NASCIMENTO" => "required|max:10",
			"DS_ENDERECO" => "required|max:100",
			"NO_BAIRRO" => "required|max:100",
			"NO_CIDADE" => "required|max:100",
			"NR_CELULAR" => "required|max:15",
			//"NR_TELEFONE" => "required|max:15",
			"CD_CONGREGACAO" => "required",
			"CD_USUARIO_LIDER" => "required",
			"NO_MAIL" => "required|max:300",
			//"TX_SENHA" => "required|confirmed|max:300",
        ];
    }

    public function messages()
    {	
		return [
            "required" => "O campo <strong>:attribute</strong> é obrigatório.", 
			"max" => "Número de caracteres superior ao permitido (:max) para o campo :attribute.",
			"confirmed" => "As senhas informadas estão diferentes."
        ];
    }
}