<table class="table table-bordered">
    <thead>
        <tr>
            <th rowspan="2">Fecha</th>
            <th rowspan="2">Motivo</th>
            <th class="text-center" colspan="3">Ingreso</th>
            <th class="text-center" colspan="3">Salida</th>
            <th class="text-center" colspan="2">Saldo</th>
        </tr>
        <tr>
            <th colspan="1">Cantidad</th>
            <th colspan="1">Costo</th>
            <th colspan="1">Total</th>
            <th colspan="1">Cantidad</th>
            <th colspan="1">Costo</th>
            <th colspan="1">Total</th>
            <th colspan="1">Cantidad</th>
            <th colspan="1">Total</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($datos as $dato)
            <tr class="text-rigth">
                <td>{{ $dato->fecha }}</td>
                <td>{{ $dato->documento }}</td>
                @if ($dato->tipo=='Inicial')
                    <td>{{ $dato->cantidad }}</td>
                    <td></td>
                    <td>{{ ($dato->total) }}</td>
                @endif
                @if ($dato->tipo=='Ingreso')
                    <td>{{ $dato->cantidad }}</td>
                    <td>{{ $dato->precio }}</td>
                    <td>{{ ($dato->cantidad*$dato->precio) }}</td>
                @endif
                <td></td>
                <td></td>
                <td></td>
                @if ($dato->tipo=='Salida')
                    <td>{{ $dato->cantidad }}</td>
                    <td>{{ $dato->precio }}</td>
                    <td>{{ ($dato->cantidad*$dato->precio) }}</td>
                @endif
                <td>{{ $dato->stock }}</td>
                <td>{{ $dato->total }}</td>
            </tr>
        @endforeach
    </tbody>
</table>