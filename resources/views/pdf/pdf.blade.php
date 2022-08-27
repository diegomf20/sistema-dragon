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
        .w-150{
            width: 150px;
        }
        .w-100{
            width: 100px;
        }
        .w-50{
            width: 50px;
        }
        .w-75{
            width: 75px;
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
            font-size: 10px
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
            height: 80px
        }
    </style>
</head>
<body>
    <div class="pdf">
        <div class="encabezado">
            <img src="{{ public_path('img/logo.jpeg') }}" alt="">
            <h4 class="center">@yield('titulo')</h4>
        </div>
        <div class="col-12">
            @yield('content')
        </div>
    </div>
</body>