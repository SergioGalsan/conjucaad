<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Usuario extends Model{
    
	protected $table = 'TB_USUARIO';
    protected $primaryKey = 'CD_USUARIO';
	
	//protected $with = array('usuarioLider');
		
	//public $timestamps = false;	
	const CREATED_AT = 'DT_INCLUSAO';
	const UPDATED_AT = 'DT_ATUALIZACAO';
	
	protected $fillable = array('CD_USUARIO', 
								'NO_USUARIO', 
								'NR_SEXO', 
								'DT_NASCIMENTO', 
								'DS_ENDERECO', 
								'NO_BAIRRO',
								'NO_CIDADE', 
								'NR_CELULAR', 
								'NR_TELEFONE',																 
								'CD_USUARIO_LIDER',
								'NO_MAIL', 
								'TX_SENHA');
	/*
	public function pesquisa(){
        return $this->belongsTo('App\Pesquisa','CD_PESQUISA');
    }
	*/
	public function usuarioPerfil(){
        return $this->belongsTo('App\UsuarioPerfil','CD_USUARIO');		
    }
		
	public function usuarioLider(){
        return $this->hasMany('App\Usuario','CD_USUARIO','CD_USUARIO_LIDER');
    }
	
}
