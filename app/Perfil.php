<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Perfil extends Model{
	
    protected $table = 'TB_PERFIL';
    protected $primaryKey = 'CD_PERFIL';
	
	public $timestamps = false;
	
	protected $fillable = array('NO_PERFIL', 'DS_PERFIL');
	
    public function usuarioPerfil(){
        return $this->belongsTo('App\UsuarioPerfil','CD_PERFIL');		
    }
}
