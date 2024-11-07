document.addEventListener('DOMContentLoaded', function() {
    let selectedMarca = '';
    let selectedProduto = '';
    let selectedLinha = '';
    let currentPage = 1;

    // Função para fechar todos os dropdowns exceto o especificado
    function closeOtherDropdowns(exceptDropdownId) {
        document.querySelectorAll('[id$="Dropdown"]').forEach(dropdown => {
            if (dropdown.id !== exceptDropdownId) {
                dropdown.classList.add('hidden');
            }
        });
    }

    // Setup dos dropdowns
    function setupDropdown(buttonId, dropdownId) {
        const button = document.getElementById(buttonId);
        const dropdown = document.getElementById(dropdownId);

        if (button && dropdown) {
            button.addEventListener('click', function(e) {
                e.stopPropagation();
                
                // Fecha outros dropdowns antes de abrir/fechar o atual
                closeOtherDropdowns(dropdownId);
                
                // Toggle do dropdown atual
                dropdown.classList.toggle('hidden');
            });
        }
    }

    // Inicializar todos os dropdowns
    setupDropdown('marcaFilter', 'marcaDropdown');
    setupDropdown('produtoFilter', 'produtoDropdown');
    setupDropdown('linhaFilter', 'linhaDropdown');

    // Fechar dropdowns ao clicar fora
    document.addEventListener('click', function(event) {
        const dropdowns = document.querySelectorAll('[id$="Dropdown"]');
        const buttons = document.querySelectorAll('[id$="Filter"]');
        
        // Verifica se o clique não foi em nenhum botão de dropdown
        const isButton = Array.from(buttons).some(button => 
            button.contains(event.target)
        );

        // Verifica se o clique não foi dentro de nenhum dropdown
        const isDropdown = Array.from(dropdowns).some(dropdown => 
            dropdown.contains(event.target)
        );

        // Se o clique não foi nem em botão nem em dropdown, fecha todos
        if (!isButton && !isDropdown) {
            closeOtherDropdowns('');  // Fecha todos os dropdowns
        }
    });

    // Prevenir que o clique dentro do dropdown feche ele mesmo
    document.querySelectorAll('[id$="Dropdown"]').forEach(dropdown => {
        dropdown.addEventListener('click', function(e) {
            e.stopPropagation();
        });
    });

    // Função para resetar dropdown
    function resetDropdown(type) {
        const spanElement = document.getElementById(`selected${type}`);
        const dropdownElement = document.getElementById(`${type.toLowerCase()}Dropdown`);
        
        if (spanElement) {
            spanElement.textContent = type === 'Produto' ? 'Todos os Produtos' : 'Todas as Linhas';
        }
        
        if (dropdownElement) {
            dropdownElement.innerHTML = `<a href="#" data-${type.toLowerCase()}="" class="block px-4 py-2 hover:bg-gray-100 text-gray-700">
                ${type === 'Produto' ? 'Todos os Produtos' : 'Todas as Linhas'}
            </a>`;
        }

        // Resetar variável de estado
        switch(type) {
            case 'Produto':
                selectedProduto = '';
                break;
            case 'Linha':
                selectedLinha = '';
                break;
        }
    }

    // Atualizar seleção e carregar dados
    function updateSelection(type, value, text, slug) {
        const spanElement = document.getElementById(`selected${type}`);
        if (spanElement) {
            spanElement.textContent = text;
        }

        const dropdownElement = document.getElementById(`${type.toLowerCase()}Dropdown`);
        if (dropdownElement) {
            dropdownElement.classList.add('hidden');
        }

        // Atualizar variáveis de estado
        switch(type) {
            case 'Marca':
                selectedMarca = value;
                selectedProduto = '';
                selectedLinha = '';
                break;
            case 'Produto':
                selectedProduto = value;
                selectedLinha = '';
                break;
            case 'Linha':
                selectedLinha = value;
                break;
        }

        // Atualizar URL com os slugs
        const url = new URL(window.location);
        
        // Limpar todos os parâmetros
        url.searchParams.delete('marca');
        url.searchParams.delete('produto');
        url.searchParams.delete('linha');
        url.searchParams.delete('page');
        
        // Adicionar apenas parâmetros com valores válidos
        if (selectedMarca) {
            const marcaLink = document.querySelector(`a[data-marca="${selectedMarca}"]`);
            if (marcaLink && marcaLink.dataset.marcaSlug) {
                url.searchParams.set('marca', marcaLink.dataset.marcaSlug);
            }
        }
        
        if (selectedProduto) {
            const produtoLink = document.querySelector(`a[data-produto="${selectedProduto}"]`);
            if (produtoLink && produtoLink.dataset.produtoSlug) {
                url.searchParams.set('produto', produtoLink.dataset.produtoSlug);
            }
        }
        
        if (selectedLinha) {
            const linhaLink = document.querySelector(`a[data-linha="${selectedLinha}"]`);
            if (linhaLink && linhaLink.dataset.linhaSlug) {
                url.searchParams.set('linha', linhaLink.dataset.linhaSlug);
            }
        }
        
        window.history.pushState({}, '', url);

        // Atualizar produtos
        updateProdutos(1);
    }

    // Função para mostrar/esconder loading do dropdown
    function toggleDropdownLoading(type, show) {
        const dropdownButton = document.getElementById(`${type.toLowerCase()}Filter`);
        const dropdown = document.getElementById(`${type.toLowerCase()}Dropdown`);
        
        if (dropdownButton && dropdown) {
            const span = dropdownButton.querySelector('span');
            if (show) {
                // Mostrar loading
                if (span) {
                    span.innerHTML = `
                        <div class="flex items-center">
                            <div class="animate-spin h-4 w-4 mr-2 border-2 border-gray-500 border-t-transparent rounded-full"></div>
                            Carregando...
                        </div>
                    `;
                }
                
                dropdown.innerHTML = `
                    <div class="px-4 py-3">
                        <div class="animate-pulse space-y-2">
                            <div class="h-4 bg-gray-200 rounded"></div>
                            <div class="h-4 bg-gray-200 rounded w-5/6"></div>
                            <div class="h-4 bg-gray-200 rounded w-4/6"></div>
                        </div>
                    </div>
                `;
            } else {
                // Restaurar texto original do botão
                if (span) {
                    const originalText = type === 'Produto' ? 'Todos os Produtos' : 
                                       type === 'Linha' ? 'Todas as Linhas' : 
                                       'Todas as Marcas';
                    span.textContent = originalText;
                }
            }
        }
    }

    // Adicione estas funções no início do arquivo, logo após o DOMContentLoaded
    function disableDropdown(type) {
        const button = document.getElementById(`${type.toLowerCase()}Filter`);
        const dropdown = document.getElementById(`${type.toLowerCase()}Dropdown`);
        
        if (button) {
            button.disabled = true;
            button.classList.add('opacity-50', 'cursor-not-allowed');
            button.classList.remove('hover:bg-gray-50');
            
            // Garante que o dropdown está fechado
            if (dropdown) {
                dropdown.classList.add('hidden');
            }
        }
    }

    function enableDropdown(type) {
        const button = document.getElementById(`${type.toLowerCase()}Filter`);
        
        if (button) {
            button.disabled = false;
            button.classList.remove('opacity-50', 'cursor-not-allowed');
            button.classList.add('hover:bg-gray-50');
        }
    }

    // Carregar produtos baseado na marca
    function loadProdutos(marcaId) {
        console.log('Carregando produtos para marca:', marcaId);
        toggleDropdownLoading('Produto', true);
        disableDropdown('Produto');
        disableDropdown('Linha');

        fetch(`/api/catalogo/produtos/${marcaId}`)
            .then(response => response.json())
            .then(data => {
                if (data.success && data.produtos.length > 0) {
                    const produtoDropdown = document.getElementById('produtoDropdown');
                    if (produtoDropdown) {
                        produtoDropdown.innerHTML = '<a href="#" data-produto="" data-produto-slug="" class="block px-4 py-2 hover:bg-gray-100 text-gray-700">Todos os Produtos</a>';
                        
                        data.produtos.forEach(produto => {
                            produtoDropdown.innerHTML += `
                                <a href="#" 
                                   data-produto="${produto.id}" 
                                   data-produto-slug="${produto.slug}" 
                                   class="block px-4 py-2 hover:bg-gray-100 text-gray-700">
                                    ${produto.nome}
                                </a>
                            `;
                        });

                        // Adiciona os event listeners
                        produtoDropdown.querySelectorAll('a').forEach(link => {
                            link.addEventListener('click', function(e) {
                                e.preventDefault();
                                const value = this.dataset.produto;
                                const slug = this.dataset.produtoSlug;
                                const text = this.textContent.trim();
                                
                                updateSelection('Produto', value, text, slug);

                                if (value) {
                                    loadLinhas(value);
                                } else {
                                    disableDropdown('Linha');
                                    resetDropdown('Linha');
                                }
                            });
                        });

                        enableDropdown('Produto');
                    }
                } else {
                    disableDropdown('Produto');
                }
            })
            .catch(error => {
                console.error('Erro ao carregar produtos:', error);
                disableDropdown('Produto');
            })
            .finally(() => {
                toggleDropdownLoading('Produto', false);
            });
    }

    // Carregar linhas baseado no produto
    function loadLinhas(produtoId) {
        console.log('Carregando linhas para produto:', produtoId);
        toggleDropdownLoading('Linha', true);
        disableDropdown('Linha');

        fetch(`/api/catalogo/linhas/${produtoId}`)
            .then(response => response.json())
            .then(data => {
                if (data.success && data.linhas.length > 0) {
                    const linhaDropdown = document.getElementById('linhaDropdown');
                    if (linhaDropdown) {
                        linhaDropdown.innerHTML = '<a href="#" data-linha="" data-linha-slug="" class="block px-4 py-2 hover:bg-gray-100 text-gray-700">Todas as Linhas</a>';
                        
                        data.linhas.forEach(linha => {
                            linhaDropdown.innerHTML += `
                                <a href="#" 
                                   data-linha="${linha.id}" 
                                   data-linha-slug="${linha.slug}" 
                                   class="block px-4 py-2 hover:bg-gray-100 text-gray-700">
                                    ${linha.nome}
                                </a>
                            `;
                        });

                        // Adiciona os event listeners para os novos links
                        linhaDropdown.querySelectorAll('a').forEach(link => {
                            link.addEventListener('click', function(e) {
                                e.preventDefault();
                                const value = this.dataset.linha;
                                const slug = this.dataset.linhaSlug;
                                const text = this.textContent.trim();
                                
                                updateSelection('Linha', value, text, slug);
                                
                                // Atualiza produtos imediatamente
                                updateProdutos(1);
                            });
                        });

                        enableDropdown('Linha');
                    }
                } else {
                    disableDropdown('Linha');
                }
            })
            .catch(error => {
                console.error('Erro ao carregar linhas:', error);
                disableDropdown('Linha');
            })
            .finally(() => {
                toggleDropdownLoading('Linha', false);
            });
    }

    // Função para atualizar o grid de produtos
    function updateProdutos(page = 1) {
        console.log('Iniciando updateProdutos com página:', page);
        
        const loadingMore = document.getElementById('loading-more');
        if (loadingMore) {
            loadingMore.classList.remove('hidden');
        }

        const params = new URLSearchParams();
        if (selectedMarca) params.append('marca', selectedMarca);
        if (selectedProduto) params.append('produto', selectedProduto);
        if (selectedLinha) params.append('linha', selectedLinha);
        params.append('page', page);

        const url = `/api/catalogo/filtrar?${params.toString()}`;
        console.log('Fazendo requisição para:', url);

        fetch(url)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('produtos-container').innerHTML = data.html;
                    document.getElementById('paginacao-container').innerHTML = data.pagination;
                    
                    // Atualiza a URL com os slugs
                    const newUrl = new URL(window.location);
                    if (page > 1) {
                        newUrl.searchParams.set('page', page);
                        // Scroll suave APENAS na paginação
                        document.getElementById('produtos-container').scrollIntoView({
                            behavior: 'smooth'
                        });
                    }
                    window.history.pushState({}, '', newUrl);
                }
            })
            .catch(error => console.error('Erro ao atualizar produtos:', error))
            .finally(() => {
                if (loadingMore) {
                    loadingMore.classList.add('hidden');
                }
            });
    }

    // Event listeners iniciais para os dropdowns
    document.querySelectorAll('#marcaDropdown a').forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const value = this.dataset.marca;
            const text = this.textContent.trim();
            
            updateSelection('Marca', value, text);

            if (value) {
                // Se selecionou uma marca, carrega os produtos
                loadProdutos(value);
            } else {
                // Se desmarcou a marca, desabilita os outros dropdowns
                disableDropdown('Produto');
                disableDropdown('Linha');
                resetDropdown('Produto');
                resetDropdown('Linha');
            }
        });
    });

    // Similar para produto e linha
    document.querySelectorAll('#produtoDropdown a').forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const value = this.dataset.produto;
            const text = this.textContent.trim();
            
            updateSelection('Produto', value, text);

            if (value) {
                // Se selecionou um produto, carrega as linhas
                loadLinhas(value);
            } else {
                // Se desmarcou o produto, desabilita o dropdown de linha
                disableDropdown('Linha');
                resetDropdown('Linha');
            }
        });
    });

    // Event listener para linha
    document.querySelectorAll('#linhaDropdown a').forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const value = this.dataset.linha;
            const text = this.textContent.trim();
            console.log('Linha selecionada:', value, text);
            
            selectedLinha = value;
            
            // Atualizar texto do botão
            const span = document.getElementById('selectedLinha');
            if (span) span.textContent = text;
            
            // Fechar dropdown
            document.getElementById('linhaDropdown').classList.add('hidden');
            
            // Atualizar produtos imediatamente
            updateProdutos(1);
        });
    });

    // Intercepta cliques na paginação
    document.querySelector('#paginacao-container')?.addEventListener('click', function(e) {
        e.preventDefault();
        const link = e.target.closest('a[data-page]');
        if (!link) return;
        
        const page = link.dataset.page;
        updateProdutos(page);
    });
}); 