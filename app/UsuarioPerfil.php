<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Classes\MyUtil;

class UsuarioPerfil extends Model{
	
    protected $table = 'TB_USUARIO_PERFIL';
    protected $primaryKey = 'CD_USUARIO_PERFIL';
    
    protected $with = array('usuario','perfil','congregacao','status');

	public $timestamps = false;
	
	protected $fillable = array('CD_USUARIO', 'CD_PERFIL','CD_CONGREGACAO','CD_STATUS');
	
    public function usuario(){
        return $this->hasMany('App\Usuario','CD_USUARIO','CD_USUARIO');
    }
	
	public function perfil(){
        return $this->hasMany('App\Perfil','CD_PERFIL','CD_PERFIL');
    }
    
    public function congregacao(){
        return $this->hasMany('App\Congregacao','CD_CONGREGACAO','CD_CONGREGACAO');
    }

	public function status(){
        return $this->hasMany('App\Status','CD_STATUS','CD_STATUS');
    }

    
    // Metodo para agilizar a codificação de querys de buscas, advindas de forms
    public function scopeRequest($query, $request){         
        if (!empty($request['NO_USUARIO']))   
            $query->whereHas('usuario', function ($query) use ($request) {
                $query->where('NO_USUARIO', 'like', "%{$request['NO_USUARIO']}%");
            });
        if (!empty($request['NO_MAIL']))   
            $query->whereHas('usuario', function ($query) use ($request) {
                $query->where('NO_MAIL',$request['NO_MAIL']);
            });
        if (!empty($request['TX_SENHA']))   
            $query->whereHas('usuario', function ($query) use ($request) {
                $query->where('TX_SENHA',base64_encode($request["TX_SENHA"]));
            });
        if (!empty($request['DT_NASCIMENTO']))   
            $query->whereHas('usuario', function ($query) use ($request) {
                $query->where('DT_NASCIMENTO',$request["DT_NASCIMENTO"]);
            });               
        if (!empty($request['CD_CONGREGACAO']))   
			$query->where('CD_CONGREGACAO', $request['CD_CONGREGACAO']);
        if (!empty($request['CD_STATUS'])) 
            $query->where('CD_STATUS', $request['CD_STATUS']);
        
        return $query;       
    }

    
    
}
