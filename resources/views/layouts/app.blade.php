<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title')SAIIUT</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- Additional CSS -->
    <style>
        /* Add your custom styles here */
    </style>
</head>
<body style="background-color: #b1f7a3;" style="background-color: #ffffff;">
    <div class="container mt-4" style="background-color: #ffffff;">
        @yield('content')
    </div>
    <!-- Bootstrap Bundle (JS included) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-+lK3I/nv9BaN7jOSp+1JfLLv9x9fq+KRQxkWhOqJ4MzFQcPVi5AqFQ1N8x6jI5Wk" crossorigin="anonymous"></script>
    <!-- Additional Scripts -->
    @stack('scripts')
</body>
</html>
