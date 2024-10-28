document.addEventListener('DOMContentLoaded', function() {
    const productsList = document.getElementById('products-list');
    const subcategoriesList = document.getElementById('subcategorias-list');

    // Função para aplicar o fade-in
    function fadeInElement(element) {
        element.classList.remove('opacity-0');
        element.classList.add('opacity-100');
    }

    // Função para aplicar o fade-out
    function fadeOutElement(element, callback) {
        element.classList.remove('opacity-100');
        element.classList.add('opacity-0');
        setTimeout(callback, 500); // Aguarda a transição antes de executar o callback
    }

    // Função para carregar subcategorias via AJAX
    function loadSubcategories(slug) {
        fetch(`/api/subcategorias/${slug}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                // Limpar subcategorias anteriores
                subcategoriesList.innerHTML = '';

                // Adicionar a opção "TODOS" como padrão
                let allOption = document.createElement('a');
                allOption.href = "javascript:void(0);";
                allOption.classList.add('px-4', 'py-2', 'bg-black', 'text-white', 'rounded-full', 'text-sm');
                allOption.textContent = 'TODOS';
                subcategoriesList.appendChild(allOption);

                // Adicionar as novas subcategorias
                data.subcategorias.forEach(function(subcategoria) {
                    let subcategoryLink = document.createElement('a');
                    subcategoryLink.href = "javascript:void(0);";
                    subcategoryLink.classList.add('px-4', 'py-2', 'bg-gray-200', 'text-black', 'rounded-full', 'text-sm', 'hover:bg-red-600', 'hover:text-white', 'transition');
                    subcategoryLink.textContent = subcategoria.nome;
                    subcategoryLink.setAttribute('data-slug', subcategoria.slug); // Adicionar atributo data-slug para capturar o clique
                    subcategoriesList.appendChild(subcategoryLink);
                });

                // Reaplicar os eventos de clique nas subcategorias carregadas
                applySubcategoryClick();
            })
            .catch(error => {
                console.error('Erro ao carregar as subcategorias:', error);
            });
    }

    // Função para aplicar os eventos de clique nas subcategorias
    function applySubcategoryClick() {
        document.querySelectorAll('#subcategorias-list a').forEach(function(subcategoryLink) {
            subcategoryLink.addEventListener('click', function(e) {
                e.preventDefault();

                let slug = this.getAttribute('data-slug');

                // Aplicar fade-out antes de atualizar os produtos
                fadeOutElement(productsList, function() {
                    // Fazer a requisição AJAX para filtrar produtos da subcategoria
                    fetch(`/api/produtos/filtrar/${slug}`, {
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            // Atualizar a lista de produtos
                            productsList.innerHTML = data.skus;

                            // Reaplicar as classes para o efeito de transição
                            productsList.classList.add('opacity-0');
                            requestAnimationFrame(() => {
                                productsList.classList.add('transition-opacity', 'duration-500');
                                requestAnimationFrame(() => {
                                    fadeInElement(productsList);
                                });
                            });

                            // Atualizar a paginação
                            document.getElementById('pagination-nav').innerHTML = data.pagination;

                            // Reaplicar o manipulador de eventos para os links de paginação
                            handlePagination();
                        })
                        .catch(error => {
                            console.error('Erro ao filtrar os SKUs:', error);
                        });
                });
            });
        });
    }

    // Aplicar o evento de clique nas categorias pai
    document.querySelectorAll('#categories a').forEach(function(categoryLink) {
        categoryLink.addEventListener('click', function(e) {
            e.preventDefault();

            let slug = this.getAttribute('data-slug');

            // Carregar subcategorias ao clicar na categoria pai
            loadSubcategories(slug);

            // Aplicar fade-out antes de atualizar os produtos
            fadeOutElement(productsList, function() {
                // Fazer a requisição AJAX
                fetch(`/api/produtos/filtrar/${slug}`, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        // Atualizar a lista de produtos
                        productsList.innerHTML = data.skus;

                        // Reaplicar as classes para o efeito de transição
                        productsList.classList.add('opacity-0');
                        requestAnimationFrame(() => {
                            productsList.classList.add('transition-opacity', 'duration-500');
                            requestAnimationFrame(() => {
                                fadeInElement(productsList);
                            });
                        });

                        // Atualizar a paginação
                        document.getElementById('pagination-nav').innerHTML = data.pagination;

                        // Reaplicar o manipulador de eventos para os links de paginação
                        handlePagination();
                    })
                    .catch(error => {
                        console.error('Erro ao filtrar os SKUs:', error);
                    });
            });
        });
    });

    // Inicializar o evento de paginação
    handlePagination();
});
