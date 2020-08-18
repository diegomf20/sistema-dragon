<!DOCTYPE html>
<html lang="es">
<head>
    @include('pdf.css')
</head>
<body>
    <div class="pdf">
        <div class="encabezado">
            <img src="{{ public_path('img/logo.jpeg') }}" alt="">
            <h4 class="center">LISTA DE ACTIVOS</h4>
        </div>
        <div class="col-12">
            <table class="table">
                <thead>
                    <tr>
                        <th>Código</th>
                        <th>Nombre</th>
                        <th>Modelo</th>
                        <th>Serie</th>
                        <th>Fecha Compra</th>
                        <th>Precio</th>
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
                            <td>{{ $activo->precio_compra }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>