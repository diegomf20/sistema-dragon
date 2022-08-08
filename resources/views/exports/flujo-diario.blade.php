<table class="table">
    <thead>
        <tr>
            <th>Descripción</th>
            <th>Monto</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($datos as $dato)
            <tr>
                <td>{{ $dato->descripcion }}</td>
                <td>{{ round($dato->monto,3) }}</td>
            </tr>
        @endforeach
    </tbody>
</table>