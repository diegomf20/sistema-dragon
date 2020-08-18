@extends('pdf.pdf')

@section('titulo','LISTA DE CLIENTES')

@section('content')
    <table class="table">
        <thead>
            <tr>
                <th>Documento</th>
                <th>Razón Social</th>
                <th>Mail</th>
                <th>Telefono</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($clientes as $cliente)
                <tr>
                    <td>{{ $cliente->documento }}</td>
                    <td>{{ $cliente->razon_social }}</td>
                    <td>{{ $cliente->mail }}</td>
                    <td>{{ $cliente->telefono }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection