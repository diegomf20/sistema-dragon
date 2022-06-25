@extends('exports.excel')

@section('titulo','RESUMEN DE OBRA')

@section('content')
@php
    $obra=$datos["obra"];
    $insumos=$datos["insumos"];
    $gastos=$datos["gastos"];
@endphp    
<table class="table">
            <tbody>
                <tr>
                    <td><strong>Titulo: </strong></td>
                    <td>{{ $obra->titulo }}</td>
                </tr>
                <tr>
                    <td><strong>Descripción: </strong></td>
                    <td>{{ $obra->descripcion }}</td>
                </tr>
                <tr>
                    <td><strong>Fecha Inicio: </strong></td>
                    <td>{{ $obra->fecha_inicio }}</td>
                </tr>
                <tr>
                    <td><strong>Fecha Fin: </strong></td>
                    <td>{{ $obra->fecha_fin }}</td>
                </tr>
                <tr>
                    <td><strong>Cotizado: </strong></td>
                    <td>S/. {{ $obra->total }}</td>
                </tr>
            </tbody>
        </table>
        <h4>Insumos Consumidos</h4>
        <table class="table">
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Categoria</th>
                    <th>Insumo</th>
                    <th>Detalles</th>
                    <th class="w-100 center">Cantidad</th>
                    <th class="w-100 center">Total</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $total=0;
                @endphp
                @foreach ($insumos as $insumo)
                    <tr>
                        <td>{{ $insumo->fecha }}</td>
                        <td>{{ $insumo->categoria }}</td>
                        <td>{{ $insumo->insumo }}</td>
                        <td class="w-100 center">{{ $insumo->documento }} - {{ $insumo->colaborador }}</td>
                        <td class="w-100 center">{{ $insumo->cantidad }}</td>
                        <td class="w-100 right">{{ round($insumo->total,3) }}</td>
                    </tr>
                    @php
                        $total+= $insumo->total;   
                    @endphp
                @endforeach
                <tr>
                    <td colspan="5"><b>Total:</b></td>
                    <td class="w-100 right"><b>{{ round($total,3) }}</b></td>
                </tr>
            </tbody>
        </table>
        <h4>Gastos Otros</h4>
        <table class="table">
            <thead>
                <tr>
                    <th class="w-100">Fecha</th>
                    <th class="center">Descripcion</th>
                    <th class="w-100 center">Total</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $total=0;
                @endphp
                @foreach ($gastos as $gasto)
                    <tr>
                        <td class="left">{{ $gasto->fecha }}</td>
                        <td class="">{{ $gasto->descripcion }}</td>
                        <td class="w-100 right">{{ round($gasto->monto,2) }}</td>
                    </tr>
                    @php
                        $total+= $gasto->monto;   
                    @endphp
                @endforeach
                <tr>
                    <td colspan="2"><b>Total:</b></td>
                    <td class="w-100 right"><b>{{ round($total,2) }}</b></td>
                </tr>
            </tbody>
        </table>
@endsection