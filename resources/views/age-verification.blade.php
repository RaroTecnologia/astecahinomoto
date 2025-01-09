<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }} - Verificação de Idade</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body class="min-h-screen relative">
    <!-- Background com overlay vermelho -->
    <div class="fixed inset-0 z-0">
        <img src="{{ asset('assets/bg_pre-home.webp') }}" alt="Background" class="w-full h-full object-cover">
        <div class="absolute inset-0 bg-vermelho-asteca/45"></div>
    </div>

    <!-- Conteúdo centralizado -->
    <div class="relative z-10 min-h-screen flex items-center justify-center px-4">
        <!-- Box com backdrop blur -->
        <div class="bg-white/10 backdrop-blur-md p-8 rounded-lg border border-vermelho-asteca max-w-xl w-full mx-auto">
            <!-- Logo -->
            <img src="{{ asset('assets/astecahinomoto_logo.png') }}" 
                 alt="Logo Asteca Hinomoto" 
                 class="w-32 mb-12">

            <!-- Separador -->
            <div class="w-24 h-1 bg-white rounded-full mb-12"></div>

            <!-- Texto -->
            <div class="space-y-3 text-white">
                <div class="flex flex-col gap-2 text-4xl">
                    <span class="font-light">Você tem mais de</span>
                    <div class="flex items-baseline gap-2">
                        <span class="font-bold">18 anos</span>
                        <span class="font-light">de idade?</span>
                    </div>
                </div>
            </div>

            <!-- Botões -->
            <div class="mt-12 flex space-x-4">
                <button onclick="confirmAge(true)" class="flex-1 bg-white text-vermelho-asteca py-3 px-6 rounded-lg font-semibold hover:bg-gray-100 transition-colors">
                    Sim
                </button>
                <button onclick="confirmAge(false)" class="flex-1 bg-transparent text-white py-3 px-6 rounded-lg font-semibold border border-white hover:bg-white/10 transition-colors">
                    Não
                </button>
            </div>
        </div>
    </div>

    <script>
        function confirmAge(isAdult) {
            if (isAdult) {
                // Faz uma requisição para definir o cookie primeiro
                fetch('/api/verify-age', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                }).then(() => {
                    // 6 meses em milissegundos = 180 dias * 24 horas * 60 minutos * 60 segundos * 1000
                    const expirationTime = new Date().getTime() + (180 * 24 * 60 * 60 * 1000);
                    localStorage.setItem('ageVerified', expirationTime.toString());
                    window.location.href = '/';
                });
            } else {
                window.location.href = '/acesso-negado';
            }
        }

        // Remove a verificação automática do localStorage aqui
        // Deixamos apenas o middleware fazer a verificação
    </script>
</body>
</html> 