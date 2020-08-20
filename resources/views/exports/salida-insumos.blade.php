<table class="table">
    <thead>
        <tr>
            <th>Código</th>
            <th>Nombre</th>
            <th>Cantidad Consumo</th>
            <th>Total Consumo</th>
            <th>Precio Promedio</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($datos as $dato)
            <tr>
                <td>{{ $dato->codigo }}</td>
                <td>{{ $dato->nombre_insumo }}</td>
                <td>{{ $dato->cantidad_consumo }}</td>
                <td>{{ $dato->total_consumo }}</td>
                <td>{{ round($dato->total_consumo/$dato->cantidad_consumo,2) }}</td>
            </tr>
        @endforeach
    </tbody>
</table>