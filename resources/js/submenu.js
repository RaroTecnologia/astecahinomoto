const produtosMenu = document.getElementById('produtos-menu');
const produtosSubmenu = document.getElementById('produtos-submenu');
const descricaoTipo = document.getElementById('descricao-tipo');
const descricaoNome = document.getElementById('descricao-tipo-nome');
const descricaoTexto = document.getElementById('descricao-tipo-descricao');
const descricaoImagem = document.getElementById('descricao-tipo-imagem');
const mobileMenuButton = document.getElementById('mobile-menu-btn');
const mainMenu = document.querySelector('nav');

// Função para detectar se a tela é mobile
const isMobile = () => window.innerWidth < 768;

// Função para controlar o overflow do body
function toggleBodyScroll(disable) {
    document.body.style.overflow = disable ? 'hidden' : '';
}

// Exibir o menu mobile ao clicar no botão de menu
if (mobileMenuButton) {
    mobileMenuButton.addEventListener('click', () => {
        mainMenu.classList.toggle('hidden');
    });
}

// Exibir/Esconder o submenu ao clicar no botão "Produtos"
produtosMenu.addEventListener('click', (event) => {
    if (isMobile()) {
        event.preventDefault(); // Prevenir navegação em mobile
        produtosSubmenu.classList.toggle('hidden');
    } else {
        event.stopPropagation(); // Evitar fechamento imediato no desktop
        const isHidden = produtosSubmenu.classList.contains('hidden');
        produtosSubmenu.classList.toggle('hidden');
        toggleBodyScroll(!isHidden); // Controla o scroll quando abre/fecha o menu
    }

    // Exibe o conteúdo do primeiro item quando o submenu é aberto
    if (!produtosSubmenu.classList.contains('hidden') && !isMobile()) {
        const primeiroItem = document.querySelector('[data-tipo-descricao]');
        if (primeiroItem) {
            const descricao = primeiroItem.getAttribute('data-tipo-descricao');
            const imagem = primeiroItem.getAttribute('data-tipo-imagem');
            const nome = primeiroItem.querySelector('a').textContent;

            descricaoNome.textContent = nome;
            descricaoTexto.textContent = descricao;
            descricaoImagem.src = imagem;
        }
    }
});

// Fechar o submenu ao clicar em qualquer outro lugar da tela
window.addEventListener('click', () => {
    if (!produtosSubmenu.classList.contains('hidden')) {
        produtosSubmenu.classList.add('hidden');
        toggleBodyScroll(false); // Restaura o scroll quando fecha o menu
    }
});

// Prevenir o fechamento ao clicar dentro do submenu
produtosSubmenu.addEventListener('click', (event) => {
    event.stopPropagation();
});

// Alterar o conteúdo da descrição ao passar o mouse sobre as categorias (Desktop)
document.querySelectorAll('[data-tipo-descricao]').forEach(function(element) {
    element.addEventListener('mouseenter', function() {
        if (!isMobile()) {
            const descricao = element.getAttribute('data-tipo-descricao');
            const imagem = element.getAttribute('data-tipo-imagem');
            const nome = element.querySelector('a').textContent;

            descricaoNome.textContent = nome;
            descricaoTexto.textContent = descricao;
            descricaoImagem.src = imagem;
        }
    });
});

// Restaurar scroll ao redimensionar a janela
window.addEventListener('resize', () => {
    if (isMobile()) {
        toggleBodyScroll(false);
    }
});
