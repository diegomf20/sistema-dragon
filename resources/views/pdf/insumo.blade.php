@extends('pdf.pdf')

@section('titulo','LISTA DE INSUMOS')

@section('content')
    <table class="table">
        <thead>
            <tr>
                <th>Código</th>
                <th>Nombre</th>
                <th>Unidad</th>
                <th>Punto Reorden</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($insumos as $insumo)
                <tr>
                    <td>{{ $insumo->codigo }}</td>
                    <td>{{ $insumo->nombre_insumo }}</td>
                    <td>{{ $insumo->nombre_unidad }}</td>
                    <td>{{ $insumo->punto_reorden }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
