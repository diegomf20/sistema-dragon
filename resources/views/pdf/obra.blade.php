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
        <h4 class="center">LISTA DE OBRAS</h4>
        <hr>
    </div>
    <div class="col-12">
        <table class="table">
            <thead>
                <tr>
                    <th>Inicio</th>
                    <th>Termino</th>
                    <th>Título de Obra</th>
                    <th>Cliente</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($obras as $obra)
                    <tr>
                        <td>{{ $obra->fecha_inicio }}</td>
                        <td>{{ $obra->fecha_fin }}</td>
                        <td>{{ $obra->titulo }}</td>
                        <td>{{ $obra->razon_social }}</td>
                        <td>{{ $obra->total }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>