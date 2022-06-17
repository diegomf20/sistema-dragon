<table class="table">
    <thead>
        <tr>
            <th>Código</th>
            <th>Nombre</th>
            <th>Punto Reorden</th>
            <th>Stock</th>
            <th>Precio Promedio</th>
            <th>TOTAL</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($stocks as $stock)
            <tr>
                <td>{{ $stock->codigo }}</td>
                <td>{{ $stock->nombre_insumo }}</td>
                <td>{{ $stock->punto_reorden }}</td>
                <td>{{ $stock->stock }}</td>
                <td>{{ $stock->stock!=0 ? round($stock->total/$stock->stock,2) : 0 }}</td>
                <td>{{ round($stock->total,2) }}</td>
            </tr>
        @endforeach
    </tbody>
</table>