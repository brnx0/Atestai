import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import react from '@vitejs/plugin-react';

export default defineConfig({
    server: {
        // Mantenha o host HMR se você usa ambientes como Docker
        host: '0.0.0.0', 
        hmr: {
            // Use o IP que você está usando (172.27.132.22) ou 'localhost'
            host: '172.27.132.22', 
        },
        // --- NOVO BLOCO DE CONFIGURAÇÃO CORS ---
        cors: {
            // Configurar o servidor Vite para permitir todas as origens
            origin: true, // Ou true, para permitir qualquer origem (mais fácil no dev)
            methods: ['GET', 'HEAD', 'PUT', 'PATCH', 'POST', 'DELETE'],
            // headers: ['Content-Type'], // Opcional, se precisar de cabeçalhos específicos
        }
        // ----------------------------------------
    },
    plugins: [
        laravel({
            input: 'resources/js/app.jsx',
            refresh: true,
        }),
        react(),
    ]

});
