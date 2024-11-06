import Quill from 'quill';
import 'quill/dist/quill.snow.css';

document.addEventListener('DOMContentLoaded', function () {
    // Adiciona CSS personalizado para os editores
    const style = document.createElement('style');
    style.textContent = `
        .ql-editor {
            font-size: 16px;
            line-height: 1.6;
            min-height: 200px;
        }
        .ql-editor h1 {
            font-size: 24px;
        }
        .ql-editor h2 {
            font-size: 20px;
        }
    `;
    document.head.appendChild(style);

    // Configuração comum para ambos os editores
    const editorConfig = {
        theme: 'snow',
        modules: {
            toolbar: [
                ['bold', 'italic', 'underline'],
                ['link'],
                [{ list: 'ordered' }, { list: 'bullet' }],
                ['clean']
            ]
        }
    };

    // Inicializa os editores
    const quillIngredientes = new Quill('#editor-ingredientes', editorConfig);
    const quillModoPreparo = new Quill('#editor-modo-preparo', editorConfig);

    // Carrega conteúdo existente
    const ingredientesAtual = document.querySelector('#input-ingredientes').value;
    const modoPreparoAtual = document.querySelector('#input-modo-preparo').value;

    if (ingredientesAtual) {
        quillIngredientes.root.innerHTML = ingredientesAtual;
    }

    if (modoPreparoAtual) {
        quillModoPreparo.root.innerHTML = modoPreparoAtual;
    }

    // Captura o formulário
    const form = document.getElementById('form-receita');
    
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Atualiza os inputs hidden
            const ingredientes = quillIngredientes.root.innerHTML;
            const modoPreparo = quillModoPreparo.root.innerHTML;
            
            document.getElementById('input-ingredientes').value = ingredientes;
            document.getElementById('input-modo-preparo').value = modoPreparo;
            
            console.log('Conteúdo sendo enviado:', {
                ingredientes: ingredientes,
                modoPreparo: modoPreparo
            });
            
            // Envia o formulário
            this.submit();
        });
    }
}); 