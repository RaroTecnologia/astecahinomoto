import Quill from 'quill';
import 'quill/dist/quill.snow.css';

document.addEventListener('DOMContentLoaded', function () {
    console.log('Recipe editor carregado');

    const style = document.createElement('style');
    style.textContent = `
        .ql-editor {
            font-size: 16px;
            line-height: 1.6;
            min-height: 200px;
        }
        .ql-editor h1 { font-size: 24px; }
        .ql-editor h2 { font-size: 20px; }
    `;
    document.head.appendChild(style);

    // Configuração dos editores
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
    const ingredientesInput = document.querySelector('#input-ingredientes');
    const modoPreparoInput = document.querySelector('#input-modo-preparo');

    if (ingredientesInput?.value) {
        quillIngredientes.root.innerHTML = ingredientesInput.value;
    }

    if (modoPreparoInput?.value) {
        quillModoPreparo.root.innerHTML = modoPreparoInput.value;
    }

    // Captura o formulário
    const form = document.getElementById('form-receita');
    
    if (form) {
        console.log('Formulário encontrado');
        
        // Função para atualizar os campos hidden
        const updateHiddenFields = () => {
            const ingredientes = quillIngredientes.root.innerHTML;
            const modoPreparo = quillModoPreparo.root.innerHTML;
            
            document.getElementById('input-ingredientes').value = ingredientes;
            document.getElementById('input-modo-preparo').value = modoPreparo;
        };

        // Atualiza os campos quando o conteúdo do editor muda
        quillIngredientes.on('text-change', updateHiddenFields);
        quillModoPreparo.on('text-change', updateHiddenFields);
        
        form.addEventListener('submit', function(e) {
            e.preventDefault(); // Agora precisamos prevenir o submit padrão
            
            try {
                console.log('Submit interceptado');
                
                // Atualiza os campos hidden uma última vez antes do envio
                updateHiddenFields();
                
                // Mostra notificação de processamento
                notyf.success('Salvando receita...');
                
                // Envia o formulário via AJAX
                const formData = new FormData(form);
                
                fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        notyf.success(data.message);
                    } else {
                        notyf.error(data.message);
                    }
                })
                .catch(error => {
                    console.error('Erro:', error);
                    notyf.error('Erro ao salvar receita. Por favor, tente novamente.');
                });
                
            } catch (error) {
                console.error('Erro ao processar o formulário:', error);
                notyf.error('Erro ao salvar receita. Por favor, tente novamente.');
            }
        });
    }
}); 