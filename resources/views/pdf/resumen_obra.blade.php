@extends('pdf.pdf')

@section('titulo','RESUMEN DE OBRA')

@section('content')
        <p><strong>Titulo: </strong> {{ $obra->titulo }}</p> 
        <p><strong>Descripción: </strong> {{ $obra->descripcion }}</p> 
        <p><strong>Fecha Inicio: </strong> {{ $obra->fecha_inicio }}</p> 
        <p><strong>Fecha Fin: </strong> {{ $obra->fecha_fin }}</p> 
        <p><strong>Cotizado: </strong> S/. {{ $obra->total }}</p> 
        <br>
        <h4>Insumos Consumidos</h4>
        <table class="table">
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Categoria</th>
                    <th>Insumo</th>
                    <th>Detalles</th>
                    <th class="w-100 center">Cantidad</th>
                    <th class="w-100 center">Unidad</th>
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
                        <td class="w-100 center">{{ $insumo->unidad }}</td>
                        <td class="w-100 right">{{ round($insumo->total,3) }}</td>
                    </tr>
                    @php
                        $total+= $insumo->total;   
                    @endphp
                @endforeach
                <tr>
                    <td colspan="6"><b>Total:</b></td>
                    <td class="w-100 right"><b>{{ round($total,3) }}</b></td>
                </tr>
            </tbody>
        </table>
        <br>
        <h4>Gastos Otros</h4>
        <table class="table">
            <thead>
                <tr>
                    <th class="w-100">Fecha</th>
                    <th class="center">Categoria</th>
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
                        <td class="left">{{ $gasto->categoria }}</td>
                        <td class="">{{ $gasto->descripcion }}</td>
                        <td class="w-100 right">{{ round($gasto->monto,2) }}</td>
                    </tr>
                    @php
                        $total+= $gasto->monto;   
                    @endphp
                @endforeach
                <tr>
                    <td colspan="3"><b>Total:</b></td>
                    <td class="w-100 right"><b>{{ round($total,2) }}</b></td>
                </tr>
            </tbody>
        </table>
@endsection