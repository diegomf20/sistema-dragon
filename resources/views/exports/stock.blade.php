<table class="table">
    <thead>
        <tr>
            <th>Código</th>
            <th>Categoria</th>
            <th>Nombre</th>
            <th>Stock</th>
            <th>Unidad</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($stocks as $stock)
            <tr>
                <td>{{ $stock->codigo }}</td>
                <td>{{ $stock->categoria }}</td>
                <td>{{ $stock->nombre_insumo }}</td>
                <td>{{ $stock->stock }}</td>
                <td>{{ $stock->unidad }}</td>
            </tr>
        @endforeach
    </tbody>
</table>