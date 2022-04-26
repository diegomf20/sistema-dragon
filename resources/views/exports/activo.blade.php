<table class="table">
    <thead>
        <tr>
            <th>Código</th>
            <th>Nombre</th>
            <th>Modelo</th>
            <th>Serie</th>
            <th>Fecha Compra</th>
            <th>Precio</th>
            <th>Contable</th>
            <th>Ubicación</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($activos as $activo)
            <tr>
                <td>{{ $activo->codigo }}</td>
                <td>{{ $activo->nombre_activo }}</td>
                <td>{{ $activo->marca }}</td>
                <td>{{ $activo->serie }}</td>
                <td>{{ $activo->fecha_compra }}</td>
                <td>{{ round($activo->precio_compra,2) }}</td>
                <td>{{ $activo->contable }}</td>
                <td>{{ ($activo->obra?$activo->obra:"En Almacen")  }}</td>
            </tr>
        @endforeach
    </tbody>
</table>