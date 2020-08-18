<!DOCTYPE html>
<html lang="es">
<head>
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
            border-top: 1px solid #ddd;
            padding: 10px 15px
        }
    </style>
</head>
<body>
    <div class="col-12">
        <h4 class="center">LISTA DE INSUMOS</h4>
        <hr>
    </div>
    <div class="col-12">
        <table class="table">
            <thead>
                <tr>
                    <th>Código</th>
                    <th>Nombre</th>
                    <th>Unidad</th>
                    <th>Punto Reorden</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($insumos as $insumo)
                    <tr>
                        <td>{{ $insumo->codigo }}</td>
                        <td>{{ $insumo->nombre_insumo }}</td>
                        <td>{{ $insumo->nombre_unidad }}</td>
                        <td>{{ $insumo->punto_reorden }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>