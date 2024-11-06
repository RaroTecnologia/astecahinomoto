console.log('Autocomplete script loaded');

document.addEventListener('DOMContentLoaded', function() {
    const searchInputs = document.querySelectorAll('input[data-search]');
    
    if (!searchInputs.length) {
        console.log('Nenhum input de busca encontrado');
        return;
    }

    let searchTimeout = null;
    const debounceDelay = 300;

    searchInputs.forEach(input => {
        const context = input.dataset.search;
        const resultsContainer = document.getElementById(`autocomplete-results-${context}`);
        const loadingElement = document.getElementById(`autocomplete-loading-${context}`);

        if (!resultsContainer) {
            console.log(`Container de resultados não encontrado para ${context}`);
            return;
        }

        input.addEventListener('input', function(e) {
            const query = e.target.value.trim();

            // Limpa o timeout anterior
            if (searchTimeout) {
                clearTimeout(searchTimeout);
            }

            // Esconde resultados se a query for muito curta
            if (query.length < 2) {
                resultsContainer.classList.add('hidden');
                return;
            }

            // Mostra loading
            resultsContainer.classList.remove('hidden');
            if (loadingElement) {
                loadingElement.classList.remove('hidden');
            }

            // Define novo timeout
            searchTimeout = setTimeout(() => {
                const searchUrl = `/api/search?query=${encodeURIComponent(query)}&type=${context}`;
                
                fetch(searchUrl, {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => {
                    if (!response.ok) throw new Error('Erro na resposta');
                    return response.json();
                })
                .then(data => {
                    if (loadingElement) {
                        loadingElement.classList.add('hidden');
                    }
                    
                    // Limpa resultados anteriores
                    while (resultsContainer.firstChild) {
                        resultsContainer.removeChild(resultsContainer.firstChild);
                    }

                    // Adiciona novos resultados
                    if (data && data.length > 0) {
                        data.forEach(item => {
                            const resultDiv = document.createElement('div');
                            resultDiv.className = 'p-2 hover:bg-gray-100 cursor-pointer';
                            resultDiv.innerHTML = `
                                <a href="/receitas/${item.categoria?.slug || 'sem-categoria'}/${item.slug}" 
                                   class="flex items-center">
                                    <div class="flex-1">
                                        <div class="font-medium">${item.nome}</div>
                                        <div class="text-sm text-gray-500">
                                            ${item.categoria ? item.categoria.nome : ''}
                                        </div>
                                    </div>
                                </a>
                            `;
                            resultsContainer.appendChild(resultDiv);
                        });
                    } else {
                        resultsContainer.innerHTML = '<div class="p-2 text-gray-500">Nenhum resultado encontrado</div>';
                    }
                })
                .catch(error => {
                    console.error('Erro na busca:', error);
                    if (loadingElement) {
                        loadingElement.classList.add('hidden');
                    }
                    resultsContainer.innerHTML = '<div class="p-2 text-red-500">Erro ao buscar resultados</div>';
                });
            }, debounceDelay);
        });

        // Fecha resultados ao clicar
        document.addEventListener('click', function(e) {
            if (!input.contains(e.target)) {
                resultsContainer.classList.add('hidden');
            }
        });

        // Event listeners para fechar
        document.addEventListener('click', function(e) {
            if (resultsContainer && input && !input.contains(e.target) && !resultsContainer.contains(e.target)) {
                resultsContainer.classList.add('hidden');
            }
        });

        if (input) {
            input.addEventListener('keydown', function(e) {
                if (!resultsContainer) return;
                
                const results = resultsContainer.querySelectorAll('a');
                const currentIndex = Array.from(results).findIndex(result => result === document.activeElement);

                switch(e.key) {
                    case 'ArrowDown':
                        e.preventDefault();
                        if (currentIndex < results.length - 1) {
                            results[currentIndex + 1].focus();
                        } else {
                            results[0].focus();
                        }
                        break;
                    case 'ArrowUp':
                        e.preventDefault();
                        if (currentIndex > 0) {
                            results[currentIndex - 1].focus();
                        } else {
                            results[results.length - 1].focus();
                        }
                        break;
                    case 'Escape':
                        resultsContainer.classList.add('hidden');
                        input.blur();
                        break;
                }
            });
        }
    });
});
