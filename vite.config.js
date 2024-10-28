import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                 'resources/js/app.js', 
                 'resources/js/slider.js',
                 'resources/js/produtos.js',
                 'resources/js/copiaUrl.js',
                 'resources/js/editor.js',
                 'resources/js/submenu.js',
                 'resources/js/toast.js',
                ],
            refresh: true,
        }),
    ],
});
