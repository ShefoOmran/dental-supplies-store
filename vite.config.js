import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import path from 'path';

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
    ],
    resolve: {
        alias: {
            '@': path.resolve(__dirname, './resources/js'),
        },
    },
    build: {
        // Enable source maps for debugging
        sourcemap: true,
        // Optimize chunk size
        rollupOptions: {
            output: {
                manualChunks: {
                    vendor: ['vue', '@inertiajs/vue3'],
                    utils: ['axios', 'ziggy-js']
                }
            }
        },
        // Enable minification
        minify: 'terser',
        // Optimize CSS
        cssCodeSplit: true,
    },
    // Development server optimization
    server: {
        hmr: {
            host: 'localhost',
        },
    },
    // Optimize dependencies
    optimizeDeps: {
        include: ['vue', '@inertiajs/vue3', 'axios']
    }
});