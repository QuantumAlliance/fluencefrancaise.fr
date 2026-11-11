import { defineConfig, loadEnv } from 'vite'
import laravel from 'laravel-vite-plugin'
import vue from '@vitejs/plugin-vue'
import path from 'path'
import { viteStaticCopy } from 'vite-plugin-static-copy'

export default defineConfig(({ mode }) => {
    const env = loadEnv(mode, process.cwd(), '')
    const backendPort = env.APP_PORT || env.SERVER_PORT || (env.APP_URL ? (new URL(env.APP_URL).port || '80') : '8010')
    const backendHost = env.SERVER_HOST || 'localhost'
    const backendTarget = env.APP_URL || `http://${backendHost}:${backendPort}`
    const vitePort = parseInt(env.VITE_PORT || '5174', 10)

    return {
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
            port: vitePort,
            hmr: {
                host: 'localhost',
                port: vitePort,
            },
            proxy: {
                '/api': {
                    target: backendTarget,
                    changeOrigin: true,
                    secure: false,
                },
                // Proxy everything to Laravel except for Vite's internal asset paths
                // This ensures that Routes get SSR/Blade handling, while Assets get Vite handling
                '^/(?!@|resources|node_modules|src|js|css|img|__vite).*': {
                    target: backendTarget,
                    changeOrigin: true,
                    secure: false,
                }
            },
        },
    }
})
