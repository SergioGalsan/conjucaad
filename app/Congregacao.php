<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Congregacao extends Model{
	
    protected $table = 'TB_CONGREGACAO';
    protected $primaryKey = 'CD_CONGREGACAO';
	
	public $timestamps = false;
	
	protected $fillable = array('CD_CONGREGACAO', 'NO_CONGREGACAO');
		
    public function usuario(){
        return $this->belongsTo('App\Usuario','CD_CONGREGACAO');
    }
}
