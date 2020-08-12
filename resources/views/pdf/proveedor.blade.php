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
        <h4 class="center">LISTA DE PROVEEDORES</h4>
        <hr>
    </div>
    <div class="col-12">
        <table class="table">
            <thead>
                <tr>
                    <th>RUC</th>
                    <th>Razón Social</th>
                    <th>Mail</th>
                    <th>Teléfono</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($proveedores as $proveedor)
                    <tr>
                        <td>{{ $proveedor->documento }}</td>
                        <td>{{ $proveedor->razon_social }}</td>
                        <td>{{ $proveedor->mail }}</td>
                        <td>{{ $proveedor->telefono }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>