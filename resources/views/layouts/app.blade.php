<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Típicos Catarina - @yield('titulo')</title>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        /* boton del menu lateral */
        .btn-menu {
            font-size: 25px;
            border: none;
            background: transparent;
            color: white;
            padding: 5px 12px;
        }

        .btn-menu:hover {
            background-color: rgba(255,255,255,0.1);
            border-radius: 5px;
        }

        /* menu lateral */
        .offcanvas { width: 320px !important; }

        .offcanvas-title { font-size: 1.5rem; }

        .offcanvas a { font-size: 1.15rem; padding: 1rem 1.25rem; }
        .offcanvas a i { font-size: 1.3rem; margin-right: 0.6rem; }

        /* las opciones del menu */
        .menu-link {
            display: block;
            padding: 14px 20px;
            color: #212529;
            text-decoration: none;
            font-size: 17px;
            border-bottom: 1px solid #eeeeee;
        }

        .menu-link:hover {
            background-color: #f1f1f1;
            color: #000;
        }

        .menu-icon {
            margin-right: 10px;
        }
        body { padding-bottom: 80px; }

        @media (min-width: 992px) {
            html { font-size: 112.5%; }   /* 18px: todo ~12% más grande */
        }
        @media (min-width: 1600px) {
            html { font-size: 125%; }     /* 20px: para monitores grandes */
        }

        .navbar-brand { font-size: 1.5rem; }
        .navbar-toggler, .navbar button { font-size: 1.6rem; }
        
        /* Letras de la gráfica */
        .apexcharts-title-text      { font-size: 22px !important; }
        .apexcharts-subtitle-text   { font-size: 16px !important; }
        .apexcharts-legend-text     { font-size: 18px !important; }
        .apexcharts-datalabel,
        .apexcharts-datalabel-label,
        .apexcharts-datalabel-value { font-size: 18px !important; }
        .apexcharts-tooltip         { font-size: 16px !important; }

        /* Separación entre los elementos de la leyenda */
        .apexcharts-legend-series   { margin: 8px 0 !important; }
        .apexcharts-legend-marker   { width: 16px !important; height: 16px !important; }
    </style>
</head>

<body class="bg-light">

    <!-- barra superior -->
    <nav class="navbar navbar-dark bg-dark">
        <div class="container-fluid px-3 px-lg-4">

            <!-- Botón del menú lateral -->
            <button 
                class="btn-menu"
                type="button"
                data-bs-toggle="offcanvas"
                data-bs-target="#menuLateral"
                aria-controls="menuLateral">
                ☰
            </button>

            <!-- titulo o nombre del sistema -->
            <a class="navbar-brand" href="{{ route('productos.index') }}">
                Típicos Catarina
            </a>

        </div>
    </nav>


    <!-- menu lateral -->
    <div 
        class="offcanvas offcanvas-start"
        tabindex="-1"
        id="menuLateral"
        aria-labelledby="menuLateralLabel">

        <!-- Encabezado -->
        <div class="offcanvas-header">

            <h5 class="offcanvas-title" id="menuLateralLabel">
                Típicos Catarina
            </h5>

            <button 
                type="button"
                class="btn-close btn-close-white"
                data-bs-dismiss="offcanvas"
                aria-label="Cerrar">
            </button>

        </div>


        <!-- opciones del menu -->
        <div class="offcanvas-body p-0">

            <a href="{{ route('inicio') }}" class="menu-link">
                <i class="bi bi-house-heart-fill"></i>
                Inicio 
            </a>

            <a href="{{ route('ventas.create') }}" class="menu-link">
                <i class="bi bi-cart-plus"></i>
                    Nueva Venta
            </a>

            <a href="{{ route('ventas.index') }}" class="menu-link">
                    <i class="bi bi-search-heart"></i>
                    Buscar Venta
            </a>

            <a href="{{ route('envios.index') }}" class="menu-link">
                    <i class="bi bi-truck"></i>
                    Envíos
            </a>

            <a href="{{ route('productos.index') }}" class="menu-link">
                <i class="bi bi-box-seam-fill"></i>
                Productos
            </a>

        </div>

    </div>


    <!-- contenido  -->
    <div class="container-fluid px-3 px-lg-4 py-4">
        @yield('contenido')
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/js/bootstrap.bundle.min.js"></script>

</body>
</html>