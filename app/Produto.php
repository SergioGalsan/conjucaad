<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Produto extends Model{
    
	protected $table = 'TB_PRODUTO';
    protected $primaryKey = 'CD_PRODUTO';
	
	//protected $with = array('usuario');
		
	public $timestamps = false;
	
	protected $fillable = array('NO_PRODUTO', 
								'DS_PRODUTO', 
								'VL_PRODUTO' 
								);
	
	public function inscricaoPedido(){
        return $this->belongsTo('App\InscricaoPedido','CD_PRODUTO');
	}
	
}
