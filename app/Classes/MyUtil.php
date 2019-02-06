<?php
// KERBEROS AUTENTICAÇÃO //	

namespace App\Classes;

//use App\Campo; // Para a função de ajuste do array de requisicao (post) para o modelo
//use App\Preenchimento; // Para a função de ajuste do array de requisicao (post) para o modelo
use App\InscricaoPedido;


class MyUtil {
	
	public static function parseInscricaoPedido($arrReq){
		if(empty($arrReq['CD_PRODUTO']))
			return NULL;
		$saida = array();
		$insPed = new InscricaoPedido;
		$insPedFill = $insPed->getVarFillable(); //dd($insPedFill);
		for($i=0;$i<count($arrReq['CD_PRODUTO']);$i++){
			for($j=0;$j<count($insPedFill);$j++){
				if(!empty($arrReq[$insPedFill[$j]][$i]))
					$aux[$insPedFill[$j]] = $arrReq[$insPedFill[$j]][$i];				
			}
			array_push($saida,$aux);
			unset($aux);
		}
		//dd($saida);	
		return $saida;
	}	









	public static function b64UrlEncode($var) { 
	  	return rtrim(strtr(base64_encode($var), '+/', '-_'), '='); 
	} 
	
	public static function b64UrlDecode($var) { 
	  	return base64_decode(str_pad(strtr($var, '-_', '+/'), strlen($var) % 4, '=', STR_PAD_RIGHT)); 
	}
	
	public static function convDt($data){ // Recebe uma data, identifica o formato  e converte
		if($data == "0000-00-00 00:00:00" || empty($data)) 
			return NULL;
		elseif(count(explode("/",$data)) > 1)
			if(count(explode(":",$data)) > 1) 
				return implode("-",array_reverse(explode("/",substr($data,0,10)))).substr($data,10);
			else 
				return implode("-",array_reverse(explode("/",$data))); 
		elseif(count(explode("-",$data)) > 1)
			if(count(explode(":",$data)) > 1)				
				return implode("/",array_reverse(explode("-",substr($data,0,10)))).substr($data,10,6);
			else	
				return implode("/",array_reverse(explode("-",$data)));
		else
			return NULL;
	}
	
	// Recebe um array, identifica datas, identifica o formato, converte e retorna o mesmo array com as datas convertidas
	public static function convArrDt($arrDatas){ 
		foreach($arrDatas as $key => $val){
			if(is_string($val) and (count(explode('/',$val))==3 or count(explode('-',$val))==3) and strlen($val)<20)
				$arrDatas[$key] = MyUtil::convDt($val);
		} //dd($arrDatas);
		return $arrDatas;
	}	
	
	/* 	Recebe um array da requisicao (post do form front gerado), identifica o que tem haver com o model Preenchimento 
	 *	e devolve um array pronto para insercao com o createMany */
	public static function parseCampoForm($arrReq){
		$saida = array();
		$campos = $GLOBALS['FormRequest.campos']; //dd($campos);
		$preenchimento = new Preenchimento;
		$preenchimentoFill = $preenchimento->getVarFillable(); //dd($preenchimentoFill);
		//'CD_CAMPO','TX_PREENCHIMENTO','NR_PREENCHIMENTO','DT_PREENCHIMENTO','DS_COMPLEMENTO'
		for($i=0;$i<count($campos);$i++){
			$aux['CD_CAMPO'] = $arrReq['cdCampo'.$i];
			if(!empty($arrReq['campo'.$i])){
				// Identificar tipo de dado do campo para incluir no campo certo do banco (texto, número, data etc)
				if(in_array($campos[$i]->CD_MASCARA,array(1,2,3,4,5,9,10,12))) 
					$aux['NR_PREENCHIMENTO'] = MyUtil::clearMask($arrReq['campo'.$i]);
				elseif($campos[$i]->CD_MASCARA == 11)
					$aux['NR_PREENCHIMENTO'] = MyUtil::convMoeda($arrReq['campo'.$i]);
				elseif(in_array($campos[$i]->CD_MASCARA,array(6,8)))
					$aux['DT_PREENCHIMENTO'] = MyUtil::convDt($arrReq['campo'.$i]);
				elseif($campos[$i]->CD_MASCARA == 7)
					$aux['DT_PREENCHIMENTO'] = "0000-01-01 ".$arrReq['campo'.$i].":00";
				else 
					$aux['TX_PREENCHIMENTO'] = $arrReq['campo'.$i];
				
				if($campos[$i]->CD_TIPO_CAMPO == 5) // Sobrescreve o de cima
					$aux['TX_PREENCHIMENTO'] = implode(';',$arrReq['campo'.$i]);
			}else{
				$aux['TX_PREENCHIMENTO'] = NULL;
			}
			array_push($saida,$aux);
			unset($aux);
		}
		//dd($saida);	
		return $saida;
	}

	/* 	Recebe um array da requisicao (post do criar pesquisa), identifica o que tem haver com o model Campo 
	 *	e devolve um array pronto para insercao com o createMany */
	public static function parseCampo($arrReq){
		$saida = array();
		$campo = new Campo;
		$campoFill = $campo->getVarFillable(); //dd($campoFill);
		for($i=0;$i<count($arrReq['NO_CAMPO']);$i++){
			for($j=0;$j<count($campoFill);$j++){
				if(!empty($arrReq[$campoFill[$j]][$i]))
					$aux[$campoFill[$j]] = $arrReq[$campoFill[$j]][$i];
				// Varredura para campos com a flag -array- (Problema com checkboxes)
				elseif(array_key_exists($campoFill[$j].'-array-1',$arrReq) and strpos($campoFill[$j],@$noMoreThis[$i])===false){
					$noMoreThis[$i] = $campoFill[$j];
					$a = 1;
					while(array_key_exists($campoFill[$j].'-array-'.$a,$arrReq)){ 
						if(!empty($arrReq[$campoFill[$j].'-array-'.$a][$i])){
							@$aux[$campoFill[$j]] .= $arrReq[$campoFill[$j].'-array-'.$a][$i].";";
						}	
						$a++;	
					}
					if(!empty($aux[$campoFill[$j]])) 
						$aux[$campoFill[$j]] = substr($aux[$campoFill[$j]],0,strlen($aux[$campoFill[$j]])-1);	
				}
			}
			array_push($saida,$aux);
			unset($aux);
		}
		//dd($saida);	
		return $saida;
	}	
	
	public static function campoValue($campo,$preenchimento){
		
		if(empty($preenchimento))
			return "";
		elseif(empty($preenchimento->TX_PREENCHIMENTO
				.$preenchimento->NR_PREENCHIMENTO
				.$preenchimento->DT_PREENCHIMENTO
				.$preenchimento->DS_COMPLEMENTO))
			return "";
		
		else{ //Identificar o tipo e apresentar o preenchimento formatado
			if(in_array($campo->CD_MASCARA,array(1,2,3,4,5,9,10,12))){
				$value = $preenchimento->NR_PREENCHIMENTO;
				if(in_array($campo->CD_MASCARA,array("1","2","3","4","5","9","12")))
					$value = MyUtil::addZeros(str_replace('.00','',$value),$campo->CD_MASCARA,@$preenchimento->DS_COMPLEMENTO);
				return MyUtil::mask($value,$campo->mascara[0]->NO_CLASSE_MASCARA);
			}elseif($campo->CD_MASCARA == 11)
				return MyUtil::convMoeda($preenchimento->NR_PREENCHIMENTO);
			elseif(in_array($campo->CD_MASCARA,array(6,8)))
				return MyUtil::convDt($preenchimento->DT_PREENCHIMENTO);
			elseif($campo->CD_MASCARA == 7)
				return substr($preenchimento->DT_PREENCHIMENTO,10,6);
			else 
				return $preenchimento->TX_PREENCHIMENTO;
		}
	}
	
	public static function percent($num,$total){
	  if ( $total > 0 ) return round($num / ($total / 100),2);
	  else return 0;	  
	}
	
	public static function addZeros($v,$mask,$etc){ 
		if(empty($v)) return NULL;
		$mask_len = array("1"=>10,"2"=>11,"3"=>14,"9"=>8,"12"=>16); //Mascara CPF e CNPJ tem tratamento a parte
		if($mask==4){ 
			if($etc=='CPF') $mask_len[4] = 11;
			elseif($etc=='CNPJ') $mask_len[4] = 14;
		}
		for($i=strlen($v);$i<$mask_len[$mask];$i++){
			$v = "0".$v;	
		}
		
		return $v;
	}
	
	public static function mask($val, $mask){
		if(empty($val)) return null;
		$val = (string)$val;
		if($mask == "mCnpj") $mask = '##.###.###/####-##';
		elseif($mask == "mCpf") $mask = '###.###.###-##';
		elseif($mask == "mCep") $mask = '#####-###';
		elseif($mask == "mConta") $mask = '###.#######-#';
		elseif($mask == "mCard") $mask = '#### #### #### ####';
		elseif($mask == "mFone") $mask = '(##)####-#####';
		elseif(strpos($mask,"#")===false)
			return $val;

	  	$maskared = '';
	  	$k = 0;
	  	for($i = 0; $i<=strlen($mask)-1; $i++){
	    	if($mask[$i] == '#'){ 
	   	  		if(isset($val[$k]))
	   				$maskared .= $val[$k++];
	    	}else{
	   	  		if(isset($mask[$i]))
	   				$maskared .= $mask[$i];
	   		}
			
	  	}
	  	return $maskared;
	}
	
	public static function clearMask($val){
		if(empty($val))
			return NULL;
		$val = str_replace('/','',$val);
		$val = str_replace('_','',$val);
		$val = str_replace('-','',$val);
		$val = str_replace('.','',$val);
		$val = str_replace(':','',$val);
		$val = str_replace('|','',$val);
		$val = str_replace('(','',$val);
		$val = str_replace(')','',$val);
		$val = str_replace(' ','',$val);
		
		return $val;		
	}
	
	public static function convMoeda($get_valor) {
        $source = array('.', ',');
        $replace = array('', '.');
        $valor = str_replace($source, $replace, $get_valor); //remove os pontos e substitui a virgula pelo ponto
        return $valor; //retorna o valor formatado para gravar no banco
    }
	
	public static function curlGetContents($url){
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		$data = curl_exec($ch);
		curl_close($ch);
		return $data;
	}
	
	public static function convSexo($sexo) {
        if($sexo == 1)
			return "Masculino";
		elseif($sexo == 2)
			return "Feminino";        
    }
}
?>