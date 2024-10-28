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
        editor.style.height = '300px';
    } else {
        console.error(`Editor não encontrado dentro de ${selector}`);
    }

    return quill;
}

document.addEventListener('DOMContentLoaded', function () {
    const quillConteudo = initializeQuill('#editor-conteudo');

    const form = document.querySelector('form');
    if (form) {
        form.onsubmit = function () {
            if (quillConteudo) {
                const conteudoInput = document.querySelector('input[name=conteudo]');
                if (conteudoInput) {
                    conteudoInput.value = quillConteudo.root.innerHTML; // Armazena o HTML diretamente
                }
            }
            console.log('Conteúdo HTML:', quillConteudo.root.innerHTML);

        };
    }

    const tituloInput = document.getElementById('titulo');
    const slugInput = document.getElementById('slug');
    if (tituloInput && slugInput) {
        tituloInput.addEventListener('input', function () {
            slugInput.value = generateSlug(tituloInput.value);
        });
    }
});



