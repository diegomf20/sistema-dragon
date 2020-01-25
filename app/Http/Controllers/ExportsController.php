<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Maatwebsite\Excel\Facades\Excel;
use App\Exports\KardexExports;

class ExportsController extends Controller
{
    public function kardex(Request $request){
        $fecha=$request->fecha;
        $codigo=$request->codigo;
        return Excel::download(new KardexExports($fecha,$codigo), "kardex-unico.xlsx");
    } 
}
