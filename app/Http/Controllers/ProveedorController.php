<?php

namespace App\Http\Controllers;

use App\Model\Proveedor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Http\Requests\ProveedorValidate;
use Illuminate\Support\Facades\DB;
use PDF;

class ProveedorController extends Controller
{
    /**
     * Visualiza todos los Proveedores
     */
    public function index(Request $request)
    {
        if ($request->has('pdf')) {
            $proveedores=Proveedor::all();
            $pdf = PDF::loadView('pdf.proveedor',compact('proveedores'));
            return $pdf->download('proveedores.pdf');
        }
        if ($request->all==true) {
            $proveedores=Proveedor::all();
        }else{
            $proveedores=Proveedor::paginate(8);
        }
        return response()->json($proveedores);
    }

    /**
     * Registra un nuevo Proveedor
     */
    public function store(ProveedorValidate $request)
    {
        $buscarProveedor=Proveedor::where('documento',$request->documento)->first();
        if ($buscarProveedor!=null) {
            return response()->json([
                "status"=>"ERROR",
                "data"=>"El RUC del proveedor ya fue registrado."
            ]);
        }
        $proveedor=new Proveedor();
        $proveedor->documento=$request->documento;
        $proveedor->razon_social=$request->razon_social;
        $proveedor->mail=$request->mail;
        $proveedor->telefono=$request->telefono;
        $proveedor->save();
        return response()->json([
            "status"=> "OK",
            "data"  => "Proveedor Registrado."
        ]);
    }
    
    /**
     * Visualiza datos de un solo Proveedor
     */
    public function show($id)
    {
        $Proveedor=Proveedor::where('id',$id)->first();
        return response()->json($Proveedor);
    }
        
    public function update(ProveedorValidate $request, $id)
    {
        $proveedor=Proveedor::where('id',$id)->first();
        $proveedor->razon_social=$request->razon_social;
        $proveedor->mail=$request->mail;
        $proveedor->telefono=$request->telefono;
        $proveedor->save();
        return response()->json([
            "status"=> "OK",
            "data"  => "Proveedor Actualizado."
        ]);
    }
}
