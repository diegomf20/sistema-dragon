<!DOCTYPE html>
<html lang="es">
<head>
    <style>
        .pdf *{
            margin: 0;
        }
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
        td,th{
            border: 1px solid #ddd;
            padding: 8px 16px;
            font-size: 12px
        }
        .encabezado{
            padding: 2rem;
            padding-bottom: 4rem;
            position: relative
        }
        .encabezado img{
            position: absolute;
            top: 0;
            left: 0;
            height: 80px;
            
        }
    </style>
</head>
<body>
    <table>
        <thead>
            <tr>
                <th><img width="100px" src="{{ public_path('img/logo.jpeg') }}" alt=""></th>
                <th><h4 class="center">@yield('titulo')</h4></th>
            </tr>
        </thead>
    </table>
    @yield('content')
</body>