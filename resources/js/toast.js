import { Notyf } from 'notyf';
import 'notyf/notyf.min.css';

// Inicializar o Notyf
const notyf = new Notyf({
    duration: 2000,
    dismissible: false, 
    position: {
        x: 'right',
        y: 'top',
    }
});

window.notyf = notyf;