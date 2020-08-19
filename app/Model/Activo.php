<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Activo extends Model
{
    protected $table="activo";
    
    public function movimientos()
    {
        return $this->hasMany('App\Model\MovimientoActivo')
                    ->leftJoin('obra','obra.id','=','movimiento_activo.obra_id')
                    ->select('movimiento_activo.*','obra.titulo')
                    ->orderBy('id','DESC');
    }
}
