<?php namespace App\Http\Requests;

use App\Http\Requests\Request;
use App\Classes\MyUtil;
use App\Campo;

class FormRequest extends Request {

    /**
     * Determine if the user is authorized
     * to make this request.
     *
     * @return bool
     */
    public function authorize(){
        return true;
    }

    /**
     * Get the validation rules that apply
     * to the request.
     *
     * @return array
     */
    
    public function rules(){
		$saida = array();
        $cdPesquisa = MyUtil::b64UrlDecode($this->route('cdPesquisaHash')) - 10000;
		$GLOBALS['FormRequest.campos'] = Campo::where('CD_PESQUISA','=',$cdPesquisa)->orderBy('CD_CAMPO')->get();
		$i=0;
		foreach($GLOBALS['FormRequest.campos'] as $campo){
			if($campo->ST_OBRIGATORIO == 'Sim')
				$saida['campo'.$i] = "required";
			$i++;	
		}
        return $saida;
    }

    public function messages(){
		$saida = array();
        $i=0;
		foreach($GLOBALS['FormRequest.campos'] as $campo){
			if($campo->ST_OBRIGATORIO == 'Sim')
				$saida['campo'.$i.'.required'] = "O campo <strong>{$campo->NO_CAMPO}</strong> é obrigatório.";
			$i++;
		}
		return $saida;
    }
    
}