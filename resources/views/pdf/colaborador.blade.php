@extends('pdf.pdf')

@section('titulo','LISTA DE COLABORADORES')

@section('content')
    <table class="table">
        <thead>
            <tr>
                <th>Documento</th>
                <th>Nombres</th>
                <th>Apellidos</th>
                <th>Teléfono</th>
                <th>Mail</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($colaboradores as $colaborador)
                <tr>
                    <td>{{ $colaborador->documento }}</td>
                    <td>{{ $colaborador->nombre_colaborador }}</td>
                    <td>{{ $colaborador->apellido_colaborador }}</td>
                    <td>{{ $colaborador->telefono }}</td>
                    <td>{{ $colaborador->mail }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection