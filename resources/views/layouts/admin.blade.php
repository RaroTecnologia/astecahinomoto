<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Web Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <!-- Vite CSS e JS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-100 text-gray-800">
    <div class="admin-container min-h-screen flex flex-col">
        <!-- Header -->
        <header class="bg-vermelho-asteca text-white shadow-md py-4">
            <div class="max-w-7xl mx-auto px-4 flex justify-between items-center">
                <h1 class="text-xl font-bold">Painel Administrativo</h1>
                <nav class="space-x-4">
                    <a href="{{ route('web-admin.index') }}" class="hover:text-gray-300">Dashboard</a>
                    <a href="{{ route('web-admin.usuarios.index') }}" class="hover:text-gray-300">Usuários</a>
                    <a href="{{ route('web-admin.categorias.index') }}" class="hover:text-gray-300">Categorias</a>
                    <a href="{{ route('web-admin.nutrientes.index') }}" class="hover:text-gray-300">Nutrientes</a>
                    <a href="{{ route('web-admin.tabelas-nutricionais.index') }}" class="hover:text-gray-300">Tabelas Nutricionais</a>
                    <a href="{{ route('web-admin.produtos.index') }}" class="hover:text-gray-300">Produtos</a>
                    <a href="{{ route('web-admin.receitas.index') }}" class="hover:text-gray-300">Receitas</a>
                    <a href="{{ route('web-admin.noticias.index') }}" class="hover:text-gray-300">Notícias</a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>

                    <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        Logout
                    </a>
                </nav>
            </div>
        </header>

        <!-- Main Content -->
        <main class="admin-content flex-grow bg-white shadow-md p-6">
            <div class="max-w-7xl mx-auto">
                @yield('content')
            </div>
        </main>
    </div>
    @yield('scripts')
    @vite(['resources/js/toast.js'])
</body>

</html>