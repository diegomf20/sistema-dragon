@extends('pdf.pdf')

@section('titulo','LISTA DE OBRAS')

@section('content')
    <table class="table">
        <thead>
            <tr>
                <th>Inicio</th>
                <th>Fin</th>
                <th>Título de Obra</th>
                <th>Cliente</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($obras as $obra)
                <tr>
                    <td>{{ $obra->fecha_inicio }}</td>
                    <td>{{ $obra->fecha_fin }}</td>
                    <td>{{ $obra->titulo }}</td>
                    <td>{{ $obra->razon_social }}</td>
                    <td>{{ $obra->total }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection