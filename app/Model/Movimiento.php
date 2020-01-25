<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Movimiento extends Model
{
    protected $table="movimiento";
    public $timestamps = false;
    public function detalles()
    {
        return $this->hasMany('App\Model\Kardex', 'documento_id', 'id')
                ->select('documento_id','producto_id','lote_id','kardex.id as kardex_id','kardex.cantidad','kardex.precio','insumo.nombre_insumo')
                ->join('insumo','insumo.id','=','kardex.producto_id');
    }
}
