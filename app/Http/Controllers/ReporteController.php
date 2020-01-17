<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReporteController extends Controller
{
    public function stock(Request $request)
    {
        $datos=DB::select(DB::raw('SELECT insumo.*,kar.stock '.
            'FROM insumo INNER JOIN ('.'
                SELECT kardex.producto_id, kardex.stock '.
                    'FROM kardex WHERE kardex.id=('.
                        'SELECT k.id FROM kardex k WHERE k.producto_id=kardex.producto_id ORDER BY k.fecha,k.id DESC LIMIT 1'.
                    ')'.
            ') kar ON insumo.id=kar.producto_id'));
        return response()->json($datos);
    }
}
