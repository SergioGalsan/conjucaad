<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Status extends Model{
	
    protected $table = 'TB_STATUS';
    protected $primaryKey = 'CD_STATUS';
	
	public $timestamps = false;
	
	protected $fillable = array('CD_STATUS', 'NO_STATUS', 'DS_STATUS');
		
    public function usuarioPerfil(){
        return $this->belongsTo('App\UsuarioPerfil','CD_STATUS');		
    }
	
	public function evento(){
        return $this->belongsTo('App\Evento','CD_STATUS');
    }
	
	public function inscricao(){
        return $this->belongsTo('App\Inscricao','CD_STATUS');
    }
}
