document.addEventListener('DOMContentLoaded', function() {
    const shareButton = document.getElementById('shareButton');
    
    if (shareButton) {
        shareButton.addEventListener('click', function(e) {
            e.preventDefault();
            const url = window.location.href;
            const shareText = document.getElementById('shareText');
            const shareIcon = document.getElementById('shareIcon');

            navigator.clipboard.writeText(url).then(function() {
                // Atualiza o texto se o elemento existir
                if (shareText) {
                    shareText.textContent = "Copiado!";
                    shareText.classList.add('text-green-500');
                }
                
                // Atualiza o ícone se o elemento existir
                if (shareIcon) {
                    shareIcon.classList.remove('fa-share-alt');
                    shareIcon.classList.add('fa-check', 'text-green-500');
                }
                
                // Restaura após 2 segundos
                setTimeout(function() {
                    if (shareText) {
                        shareText.textContent = "Compartilhar";
                        shareText.classList.remove('text-green-500');
                    }
                    if (shareIcon) {
                        shareIcon.classList.remove('fa-check', 'text-green-500');
                        shareIcon.classList.add('fa-share-alt');
                    }
                }, 2000);
            }).catch(function(error) {
                console.error('Erro ao copiar o texto:', error);
            });
        });
    }
});
