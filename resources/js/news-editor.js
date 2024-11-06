import Quill from 'quill';
import 'quill/dist/quill.snow.css';

document.addEventListener('DOMContentLoaded', function () {
    // Adiciona CSS personalizado para o editor
    const style = document.createElement('style');
    style.textContent = `
        .ql-editor {
            font-size: 16px;  /* Aumenta o tamanho da fonte do conteúdo */
            line-height: 1.6; /* Ajusta o espaçamento entre linhas */
        }
        .ql-editor h1 {
            font-size: 24px;
        }
        .ql-editor h2 {
            font-size: 20px;
        }
    `;
    document.head.appendChild(style);

    // Inicializa o editor
    const quill = new Quill('#editor-conteudo', {
        theme: 'snow',
        modules: {
            toolbar: [
                ['bold', 'italic', 'underline'],
                ['link'],
                [{ list: 'ordered' }, { list: 'bullet' }],
                ['clean']
            ]
        }
    });

    // Carrega conteúdo existente
    const conteudoAtual = document.querySelector('#input-conteudo').value;
    if (conteudoAtual) {
        quill.root.innerHTML = conteudoAtual;
    }

    // Captura o formulário
    const form = document.getElementById('form-noticia');
    
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Atualiza o input hidden
            const conteudo = quill.root.innerHTML;
            document.getElementById('input-conteudo').value = conteudo;
            
            console.log('Conteúdo sendo enviado:', conteudo);
            
            // Envia o formulário
            this.submit();
        });
    }
});



