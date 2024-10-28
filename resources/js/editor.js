import Quill from 'quill';
import 'quill/dist/quill.snow.css';

// Função para inicializar o Quill com configurações padrão
function initializeQuill(selector) {
    const element = document.querySelector(selector);
    if (!element) {
        console.error(`Elemento ${selector} não encontrado para inicializar o Quill.`);
        return null;
    }

    const quill = new Quill(selector, {
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

    // Definir altura do editor após a inicialização
    const editor = document.querySelector(`${selector} .ql-editor`);
    if (editor) {
        editor.style.height = '200px';
    } else {
        console.error(`Editor não encontrado dentro de ${selector}`);
    }

    console.log(`Quill initialized for ${selector}`);
    return quill;
}

document.addEventListener('DOMContentLoaded', function () {
    console.log('DOMContentLoaded event triggered.');

    // Inicializar os editores Quill
    const quillIngredientes = initializeQuill('#editor-ingredientes');
    const quillModoPreparo = initializeQuill('#editor-modo-preparo');

    // Capturar o conteúdo ao submeter o formulário
    const form = document.querySelector('form');
    if (form) {
        form.onsubmit = function () {
            console.log('Formulário sendo submetido.');
            if (quillIngredientes && quillModoPreparo) {
                const ingredientesInput = document.querySelector('input[name=ingredientes]');
                const modoPreparoInput = document.querySelector('input[name=modo_preparo]');

                if (ingredientesInput && modoPreparoInput) {
                    ingredientesInput.value = JSON.stringify(quillIngredientes.getContents());
                    modoPreparoInput.value = JSON.stringify(quillModoPreparo.getContents());
                    console.log('Conteúdo capturado e pronto para submissão.');
                } else {
                    console.error("Campos de input para 'ingredientes' ou 'modo_preparo' não encontrados.");
                    notyf.error("Erro ao preparar o conteúdo para submissão. Campos de entrada ausentes.");
                }
            } else {
                console.error("Quill não foi inicializado corretamente.");
                notyf.error("Erro ao preparar o conteúdo para submissão.");
            }
        };
    } else {
        console.error("Formulário não encontrado.");
    }
});