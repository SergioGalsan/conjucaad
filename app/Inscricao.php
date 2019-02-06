<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Inscricao extends Model{
	
    protected $table = 'TB_INSCRICAO';
    protected $primaryKey = 'CD_INSCRICAO';
	
	//public $timestamps = false;
    
    const CREATED_AT = 'DT_INCLUSAO';
	const UPDATED_AT = 'DT_ATUALIZACAO';

	protected $fillable = array('CD_USUARIO_PERFIL', 'CD_EVENTO', 'CD_STATUS', 'ST_ALIMENTACAO');
		
    public function usuarioPerfil(){
        return $this->hasMany('App\UsuarioPerfil','CD_USUARIO_PERFIL','CD_USUARIO_PERFIL');
    }
	
	public function evento(){
        return $this->hasMany('App\Evento','CD_EVENTO','CD_EVENTO');
    }
	
	public function status(){
        return $this->hasMany('App\Status','CD_STATUS','CD_STATUS');
    }

    public function inscricaoPedido(){
        return $this->hasMany('App\InscricaoPedido','CD_INSCRICAO','CD_INSCRICAO');
    }


    // Metodo para agilizar a codificação de querys de buscas, advindas de forms
    public function scopeRequest($query, $request){         
        if (!empty($request['NO_USUARIO']))             
            $query->whereHas('usuarioPerfil', function ($query) use ($request) {
                $query->whereHas('usuario', function ($query) use ($request) {
                    $query->where('NO_USUARIO', 'like', "%{$request['NO_USUARIO']}%");
                });    
            });
        if (!empty($request['NO_MAIL']))             
            $query->whereHas('usuarioPerfil', function ($query) use ($request) {
                $query->whereHas('usuario', function ($query) use ($request) {
                    $query->where('NO_MAIL', $request['NO_MAIL']);
                });    
			});     
        if (!empty($request['CD_CONGREGACAO']))             
            $query->whereHas('usuarioPerfil', function ($query) use ($request) {
				$query->where('CD_CONGREGACAO', $request['CD_CONGREGACAO']);
			});		   
        if (!empty($request['CD_STATUS'])) 
            $query->where('CD_STATUS', $request['CD_STATUS']);
        
        return $query;       
    }
}
