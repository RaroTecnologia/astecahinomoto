function checkAgeVerification() {
    // Não verifica nas páginas de verificação
    if (window.location.pathname === '/verificar-idade' || 
        window.location.pathname === '/acesso-negado') {
        return;
    }

    const ageVerified = localStorage.getItem('ageVerified');
    
    if (!ageVerified) {
        window.location.href = '/verificar-idade';
        return;
    }

    const expirationTime = parseInt(ageVerified);
    if (new Date().getTime() > expirationTime) {
        localStorage.removeItem('ageVerified');
        window.location.href = '/verificar-idade';
    }
}

// Executa a verificação quando o documento carrega
document.addEventListener('DOMContentLoaded', checkAgeVerification); 