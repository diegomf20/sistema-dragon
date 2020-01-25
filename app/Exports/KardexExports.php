<?php 

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Support\Facades\DB;


class KardexExports implements FromView
{

    private $fecha;
    private $codigo;
 
    public function __construct($fecha,$codigo)
    {
        $this->fecha = $fecha;
        $this->codigo = $codigo;
    }

    public function view(): View
    {
        $fecha=$this->fecha;
        $codigo=$this->codigo;
        $datos=DB::select(DB::raw("(
            SELECT 	'Inicial' fecha,'Inicial' tipo ,K.stock cantidad,0.00 precio,K.stock,K.total,'' documento 
                    FROM 		kardex K INNER JOIN insumo on insumo.id=K.producto_id
                    WHERE 	insumo.codigo='0001' AND K.fecha<CONCAT(:fecha,'-01')
                    ORDER BY K.id DESC,K.fecha DESC LIMIT 1
                )
                UNION
                (
                    SELECT 	K.fecha,K.tipo,k.cantidad,K.precio,K.stock,K.total,CONCAT(M.tipo_movimiento,' ',M.documento) documento 
                    FROM 	kardex K INNER JOIN movimiento M on M.id=K.documento_id
                            INNER JOIN insumo on insumo.id=K.producto_id 
                    WHERE 	insumo.codigo=:codigo 
                    AND DATE_FORMAT(K.fecha,'%Y-%m')= :fecha2 )
                "),[
                    "codigo" =>  $codigo,
                    "fecha" =>  $fecha,
                    "fecha2" =>  $fecha,
                ]);
        return view('exports.kardex', [
            'datos' => $datos
        ]);
    }
}