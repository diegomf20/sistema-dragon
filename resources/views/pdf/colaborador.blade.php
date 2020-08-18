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
        <h4 class="center">LISTA DE COLABORADORES</h4>
        <hr>
    </div>
    <div class="col-12">
        <table class="table">
            <thead>
                <tr>
                    <th>Documento</th>
                    <th>Nombres</th>
                    <th>Apellidos</th>
                    <th>Teléfono</th>
                    <th>Mail</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($colaboradores as $colaborador)
                    <tr>
                        <td>{{ $colaborador->documento }}</td>
                        <td>{{ $colaborador->nombre_colaborador }}</td>
                        <td>{{ $colaborador->apellido_colaborador }}</td>
                        <td>{{ $colaborador->telefono }}</td>
                        <td>{{ $colaborador->mail }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>