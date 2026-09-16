import { defineConfig } from 'vite'
import laravel from 'laravel-vite-plugin'
import vue from '@vitejs/plugin-vue'
import path from 'path'
import { viteStaticCopy } from 'vite-plugin-static-copy'

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
        viteStaticCopy({
            targets: [
                {
                    src: 'node_modules/tinymce/skins',
                    dest: 'tinymce'
                },
                {
                    src: 'node_modules/tinymce/icons',
                    dest: 'tinymce'
                },
                {
                    src: 'node_modules/tinymce/themes',
                    dest: 'tinymce'
                },
                {
                    src: 'node_modules/tinymce/models',
                    dest: 'tinymce'
                },
                {
                    src: 'node_modules/tinymce/plugins',
                    dest: 'tinymce'
                }
            ]
        })
    ],
    resolve: {
        alias: {
            '@': path.resolve(__dirname, './resources/js'),
        },
    },
    server: {
        middlewareMode: false,
        host: 'localhost',
        port: 5174,
        hmr: {
            host: 'localhost',
            port: 5174,
        },
        proxy: {
            '/api': {
                target: 'http://localhost:8001',
                changeOrigin: true,
                secure: false,
            },
            // Proxy everything to Laravel except for Vite's internal asset paths
            // This ensures that Routes get SSR/Blade handling, while Assets get Vite handling
            '^/(?!@|resources|node_modules|src|js|css|img|__vite).*': {
                target: 'http://localhost:8001',
                changeOrigin: true,
                secure: false,
            }
        },
    },
})
