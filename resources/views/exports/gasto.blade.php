<table class="table">
    <thead>
        <tr>
            <th>Fecha</th>
            <th>Descripción</th>
            <th>Monto</th>
            <th>Categoria</th>
            <th>Obra</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($datos as $dato)
            <tr>
                <td>{{ $dato->fecha }}</td>
                <td>{{ $dato->descripcion }}</td>
                <td>{{ round($dato->monto,2) }}</td>
                <td>{{ $dato->categoria }}</td>
                <td>{{ $dato->obra }}</td>
            </tr>
        @endforeach
    </tbody>
</table>