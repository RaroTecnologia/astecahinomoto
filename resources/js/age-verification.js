function checkAgeVerification() {
    // Não verifica na página de verificação de idade
    if (window.location.pathname === '/verificar-idade') {
        return;
    }

    const ageVerified = localStorage.getItem('ageVerified');
    
    if (!ageVerified) {
        window.location.href = '/verificar-idade';
        return;
    }

    // Verifica se a verificação expirou (24 horas)
    const expirationTime = parseInt(ageVerified);
    if (new Date().getTime() > expirationTime) {
        localStorage.removeItem('ageVerified');
        window.location.href = '/verificar-idade';
    }
}

// Executa a verificação quando o documento carrega
document.addEventListener('DOMContentLoaded', checkAgeVerification); 