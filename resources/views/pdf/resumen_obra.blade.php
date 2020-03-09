<!DOCTYPE html>
<html lang="es">
<head>
    {{-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"> --}}

    <style>
        .left{
            text-align: left

        }
        .right{
            text-align: right;
        }
        .center{
            text-align: center;
        }
        .table{
            width: 100%;
        }
        .w-100{
            width: 100px;
        }
        table{
            border-collapse: collapse;
        }
        td,th,tr,thead,tbody{
            margin: 0;
        }
        td{
            border-top: 1px solid #ddd;
            padding: 10px 15px
        }
        th{
            border-bottom: 1px solid #ddd;
            border-top: 1px solid #ddd
        }
    </style>
</head>
<body>
    <div class="col-12">
        <h4 class="center">RESUMEN DE OBRA</h4>
        <p><strong>Titulo: </strong> {{ $obra->titulo }}</p> 
        <p><strong>Descripción: </strong> {{ $obra->descripcion }}</p> 
        <p><strong>Fecha Inicio: </strong> {{ $obra->fecha_inicio }}</p> 
        <p><strong>Fecha Fin: </strong> {{ $obra->fecha_fin }}</p> 
    </div>
    <div class="col-12">
        <hr>
        <h4>Insumos Consumidos</h4>
        <table class="table">
            <thead>
                <tr>
                    <th>Insumo</th>
                    <th>SXC</th>
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
                        <td>{{ $insumo->nombre_insumo }}</td>
                        <td class="w-100 center">{{ $insumo->documentos }}</td>
                        <td class="w-100 center">{{ $insumo->cantidad }}</td>
                        <td class="w-100 right">{{ round($insumo->total,2) }}</td>
                    </tr>
                    @php
                        $total+= $insumo->total;   
                    @endphp
                @endforeach
                <tr>
                    <td colspan="3"><b>Total:</b></td>
                    <td class="w-100 right"><b>{{ round($total,2) }}</b></td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="col-12">
        <hr>
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
    </div>
</body>
</html>