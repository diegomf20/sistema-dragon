<?php

namespace App\Http\Controllers;

use App\Model\cliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Http\Requests\ClienteValidate;
use Illuminate\Support\Facades\DB;
use PDF;

class ClienteController extends Controller
{
    /**
     * Visualiza todos los clientees
     */
    public function index(Request $request)
    {
        if ($request->has('pdf')) {
            $clientes=Cliente::all();
            $pdf = PDF::loadView('pdf.cliente',compact('clientes'));
            return $pdf->download('clientes.pdf');
        }
        if ($request->all==true) {
            $clientees=Cliente::all();
        }else{
            $clientees=Cliente::paginate(8);
        }
        return response()->json($clientees);
    }

    /**
     * Registra un nuevo cliente
     */
    public function store(clienteValidate $request)
    {
        $clienteBuscar=Cliente::where('documento',$request->documento)->first();
        if ($clienteBuscar!=null) {
            return response()->json([
                "status"=>"ERROR",
                "data"=>"El cliente ya fue registrado."
            ]);
        }
        $cliente=new Cliente();
        $cliente->documento=$request->documento;
        $cliente->razon_social=$request->razon_social;
        $cliente->telefono=$request->telefono;
        $cliente->mail=$request->mail;
        $cliente->save();
        return response()->json([
            "status"=> "OK",
            "data"  => "cliente Registrado."
        ]);
    }
    
    /**
     * Visualiza datos de un solo cliente
     */
    public function show($id)
    {
        $cliente=Cliente::where('id',$id)->first();
        return response()->json($cliente);
    }
    
    public function update(clienteValidate $request, $id)
    {
        $cliente=Cliente::where('id',$id)->first();
        $cliente->razon_social=$request->razon_social;
        $cliente->documento=$request->documento;
        $cliente->telefono=$request->telefono;
        $cliente->mail=$request->mail;
        $cliente->save();
        return response()->json([
            "status"=> "OK",
            "data"  => "cliente Actualizado."
        ]);
    }
}
