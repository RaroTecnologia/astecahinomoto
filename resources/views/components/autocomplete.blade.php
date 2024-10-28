<div class="mb-8 relative">
    <input type="text" id="search-{{ $context }}" placeholder="Buscar {{ $placeholder }}..." class="px-4 py-2 w-full border rounded-lg focus:outline-none" autocomplete="off">
    <div id="autocomplete-results-{{ $context }}" class="absolute bg-white shadow-lg mt-1 w-full max-h-48 overflow-y-auto hidden">
        <!-- Resultados serão exibidos aqui -->
    </div>
</div>

<script>
    document.getElementById('search-{{ $context }}').addEventListener('input', function() {
        let query = this.value;

        if (query.length > 2) {
            fetch(`/api/search?query=${query}&type=${context}`)
                .then(response => response.json())
                .then(data => {
                    console.log(data); // Adiciona um log dos dados recebidos
                    let resultsDiv = document.getElementById(`autocomplete-results-${context}`);
                    resultsDiv.innerHTML = ''; // Limpar resultados anteriores
                    resultsDiv.classList.remove('hidden'); // Exibir dropdown de resultados

                    data.forEach(item => {
                        let div = document.createElement('div');
                        div.classList.add('p-2', 'hover:bg-gray-200', 'cursor-pointer');
                        div.innerHTML = `<a href="/${item.type}/${item.categoria?.slug || 'categoria-desconhecida'}/${item.slug}" class="block text-black">${item.nome}</a>`;
                        resultsDiv.appendChild(div);
                    });
                })
                .catch(error => console.error('Erro na busca:', error));
        } else {
            document.getElementById('autocomplete-results-{{ $context }}').classList.add('hidden');
        }
    });
</script>