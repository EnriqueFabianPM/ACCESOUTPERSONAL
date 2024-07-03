<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>SAIIUT: Sistema de Registro de Visitantes</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- Estilos CSS adicionales si es necesario -->
    <style>
        /* Agrega tus estilos personalizados aquí */
    </style>
</head>
<body>
    <div class="container mt-4">
        @yield('content') <!-- Aquí se incluirá el contenido dinámico de cada vista -->
    </div>
    <!-- Bootstrap Bundle (JS incluido) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-+lK3I/nv9BaN7jOSp+1JfLLv9x9fq+KRQxkWhOqJ4MzFQcPVi5AqFQ1N8x6jI5Wk" crossorigin="anonymous"></script>
    <!-- Scripts adicionales si es necesario -->
    @stack('scripts')
</body>
</html>
