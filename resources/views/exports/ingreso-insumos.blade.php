<table class="table">
    <thead>
        <tr>
            <th>Código</th>
            <th>Nombre</th>
            <th>Cantidad Comprada</th>
            <th>Total Compra</th>
            <th>Precio Promedio</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($datos as $dato)
            <tr>
                <td>{{ $dato->codigo }}</td>
                <td>{{ $dato->nombre_insumo }}</td>
                <td>{{ $dato->cantidad_compra }}</td>
                <td>{{ $dato->total_compra }}</td>
                <td>{{ round($dato->total_compra/$dato->cantidad_compra,2) }}</td>
            </tr>
        @endforeach
    </tbody>
</table>