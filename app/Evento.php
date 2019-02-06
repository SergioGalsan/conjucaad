<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Evento extends Model{
	
    protected $table = 'TB_EVENTO';
    protected $primaryKey = 'CD_EVENTO';
	
	//public $timestamps = false;
    const CREATED_AT = 'DT_INCLUSAO';
	const UPDATED_AT = 'DT_ATUALIZACAO';
    
	protected $fillable = array('CD_EVENTO', 'NO_EVENTO', 'TX_APRESENTACAO', 'DS_EVENTO', 'DT_INICIO_INSCRICAO', 'DT_FIM_INSCRICAO', 'VL_INSCRICAO', 'CD_STATUS');
		
    public function inscricao(){
        return $this->belongsTo('App\Inscricao','CD_INSCRICAO');
    }
	
	public function status(){
        return $this->hasMany('App\Status','CD_STATUS','CD_STATUS');
    }
}