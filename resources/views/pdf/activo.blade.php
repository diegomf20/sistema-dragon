@extends('pdf.pdf')

@section('titulo','LISTA DE ACTIVOS')

@section('content')
    <table class="table">
        <thead>
            <tr>
                <th>Código</th>
                <th>Nombre</th>
                <th>Modelo</th>
                <th>Serie</th>
                <th>Fecha Compra</th>
                <th>Ubicación</th>
                <th>Precio</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($activos as $activo)
                <tr>
                    <td>{{ $activo->codigo }}</td>
                    <td>{{ $activo->nombre_activo }}</td>
                    <td>{{ $activo->marca }}</td>
                    <td>{{ $activo->serie }}</td>
                    <td>{{ $activo->fecha_compra }}</td>
                    <td>{{ ($activo->titulo?$activo->titulo:"En Almacen")  }}</td>
                    <td>{{ number_format(round($activo->precio_compra,2), 2, '.', ',') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
