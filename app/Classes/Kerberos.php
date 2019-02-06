<?php
// KERBEROS AUTENTICAÇÃO //	

namespace App\Classes;

class Kerberos {
	
	private static $matricula = null; //$_SERVER["REMOTE_USER"];
	
	public static function getAdMatric(){
		$quebrarMatricula = explode("@", self::$matricula);
		$adMatric = preg_replace("/[^0-9]/", "", $quebrarMatricula[0]); 	
		$adMatric = 840413;
		return $adMatric;
	}
		
}
?>