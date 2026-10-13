<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Típicos Catarina - @yield('titulo')</title>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        /* Botón del menú */
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

        /* Menú lateral */
        .offcanvas {
            width: 280px !important;
        }

        .offcanvas-header {
            background-color: #212529;
            color: white;
        }

        .offcanvas-title {
            font-weight: bold;
        }

        /* Opciones del menú */
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
    </style>
</head>

<body class="bg-light">

    <!-- BARRA SUPERIOR -->
    <nav class="navbar navbar-dark bg-dark">
        <div class="container">

            <!-- Botón para abrir menú -->
            <button 
                class="btn-menu"
                type="button"
                data-bs-toggle="offcanvas"
                data-bs-target="#menuLateral"
                aria-controls="menuLateral">
                ☰
            </button>

            <!-- Nombre del sistema -->
            <a class="navbar-brand" href="{{ route('productos.index') }}">
                Típicos Catarina
            </a>

        </div>
    </nav>


    <!-- MENÚ LATERAL -->
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


        <!-- Opciones -->
        <div class="offcanvas-body p-0">

            <a href="#" class="menu-link">
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

            <a href="#" class="menu-link">
                    <i class="bi bi-truck"></i>
                    Envíos
            </a>

            <a href="{{ route('productos.index') }}" class="menu-link">
                <i class="bi bi-box-seam-fill"></i>
                Productos
            </a>

        </div>

    </div>


    <!-- CONTENIDO -->
    <div class="container mt-4">

        @if (session('success'))
            
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        @yield('contenido')

    </div>


    <!-- BOOTSTRAP -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/js/bootstrap.bundle.min.js"></script>

</body>
</html>