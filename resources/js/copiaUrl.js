document.addEventListener('DOMContentLoaded', function() {
    const shareButton = document.getElementById('shareButton');
    const shareText = document.getElementById('shareText');
    const shareIcon = document.getElementById('shareIcon');

    shareButton.addEventListener('click', function(e) {
        e.preventDefault();

        // Copiar a URL atual
        const url = window.location.href;

        navigator.clipboard.writeText(url).then(function() {
            // Alterar o texto e o ícone para "Copiado!"
            shareText.textContent = "Copiado!";
            shareIcon.classList.remove('fa-share-alt');
            shareIcon.classList.add('fa-check');
            shareText.classList.add('text-green-500');
            
            // Manter o ícone e o texto "Copiado!" por 2 segundos
            setTimeout(function() {
                // Restaurar o texto e o ícone originais
                shareText.textContent = "Compartilhar";
                shareIcon.classList.remove('fa-check');
                shareIcon.classList.add('fa-share-alt');
                shareText.classList.remove('text-green-500');
            }, 2000);
        }).catch(function(error) {
            console.error('Erro ao copiar o texto:', error);
        });
    });
});
