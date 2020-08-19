@extends('pdf.pdf')

@section('titulo','LISTA DE PROVEEDORES')

@section('content')
    <table class="table">
        <thead>
            <tr>
                <th>RUC</th>
                <th>Razón Social</th>
                <th>Mail</th>
                <th>Teléfono</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($proveedores as $proveedor)
                <tr>
                    <td>{{ $proveedor->documento }}</td>
                    <td>{{ $proveedor->razon_social }}</td>
                    <td>{{ $proveedor->mail }}</td>
                    <td>{{ $proveedor->telefono }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
