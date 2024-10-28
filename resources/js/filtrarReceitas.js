document.addEventListener('DOMContentLoaded', function () {
    console.log('Script carregado e DOM pronto'); // Verifique se o script foi carregado

    // Capturar clique nas categorias
    document.querySelectorAll('#categories a').forEach(function (categoryLink) {
        categoryLink.addEventListener('click', function (e) {
            e.preventDefault();

            let slug = this.getAttribute('data-slug');
            console.log(`Categoria clicada: ${slug}`); // Verifique se o clique está sendo capturado

            // Fazer a requisição AJAX para a filtragem
            fetch(`/receitas/filtrar/${slug}`)
                .then(response => response.json())
                .then(data => {
                    console.log('Dados recebidos da API:', data); // Verifique se os dados estão sendo recebidos corretamente

                    // Atualizar a contagem
                    document.getElementById('count').innerText = data.count;

                    // Atualizar a listagem de receitas
                    document.getElementById('recipes-list').innerHTML = data.receitas;

                    // Atualizar a paginação
                    document.getElementById('pagination').innerHTML = data.pagination;
                })
                .catch(error => {
                    console.error('Erro ao filtrar as receitas:', error);
                });
        });
    });

    // Limpar os filtros
    document.getElementById('clear-filters').addEventListener('click', function (e) {
        e.preventDefault();

        // Recarregar a página para exibir todas as receitas
        window.location.reload();
    });

    // Paginação via AJAX
    document.addEventListener('click', function (e) {
        if (e.target.closest('#pagination a')) {
            e.preventDefault();
            let url = e.target.getAttribute('href');

            // Fazer a requisição AJAX para a paginação
            fetch(url)
                .then(response => response.json())
                .then(data => {
                    console.log('Dados da paginação:', data); // Verifique se os dados da paginação estão corretos

                    // Atualizar a contagem
                    document.getElementById('count').innerText = data.count;

                    // Atualizar a listagem de receitas
                    document.getElementById('recipes-list').innerHTML = data.receitas;

                    // Atualizar a paginação
                    document.getElementById('pagination').innerHTML = data.pagination;
                })
                .catch(error => {
                    console.error('Erro ao paginar:', error);
                });
        }
    });
});
