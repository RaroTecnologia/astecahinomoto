document.querySelectorAll('input[data-search]').forEach(function(inputElement) {
    inputElement.addEventListener('input', function() {
        let query = this.value;
        let context = this.getAttribute('data-search'); // Obtém o contexto (receitas, noticias, produtos)
        let resultsDiv = document.getElementById(`autocomplete-results-${context}`);

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
            resultsDiv.classList.add('hidden'); // Esconde o dropdown se a query for muito curta
        }
    });
});
