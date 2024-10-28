<!-- resources/views/layouts/app.blade.php -->
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&display=swap" rel="stylesheet">

    <title>@yield('title', 'Asteca Hinomoto')</title>
    <!-- Importar os estilos do Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>

<body class="antialiased bg-white">
    <!-- Header -->
    @include('layouts.header')

    <!-- Conteúdo Principal -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    @include('layouts.footer')

    <!-- Scripts -->
    @vite(['resources/js/copiaUrl.js'])

</body>

</html>