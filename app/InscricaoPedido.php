<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InscricaoPedido extends Model{
    
	protected $table = 'TB_INSCRICAO_PEDIDO';
    protected $primaryKey = 'CD_INSCRICAO_PEDIDO';
	
	protected $with = array('produto');
		
	public $timestamps = false;
	
	protected $fillable = array('CD_INSCRICAO', 
								'CD_PRODUTO', 
								'NO_GENERO',
								'NO_COR',
								'NO_TAMANHO',
								'NR_QUANTIDADE' 
								);
	
	public function inscricao(){
        return $this->belongsTo('App\Inscricao','CD_INSCRICAO');
	}
	
	public function produto(){
        return $this->hasMany('App\Produto','CD_PRODUTO','CD_PRODUTO');
    }
	
	public function getVarFillable(){
        $saida = $this->fillable; 
		unset($saida[0]);
		return array_values($saida);
    }
}
