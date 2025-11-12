import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        tailwindcss(),
    ],
    
    build: {
        minify: 'terser',
        terserOptions: {
            compress: {
                drop_console: true, // Eliminar console.log en producción
                drop_debugger: true,
                pure_funcs: ['console.log', 'console.info', 'console.debug'],
            },
        },
        
        // Code splitting y chunking
        rollupOptions: {
            output: {
                manualChunks: {
                    // Separar librerías grandes en chunks
                    'vendor-chart': ['chart.js'],
                    'vendor-driver': ['driver.js'],
                    'vendor-alpine': ['alpinejs'],
                    'vendor-toast': ['vanilla-toast'],
                },
                // Nombres de archivos con hash para cache busting
                chunkFileNames: 'assets/js/[name]-[hash].js',
                entryFileNames: 'assets/js/[name]-[hash].js',
                assetFileNames: (assetInfo) => {
                    const info = assetInfo.name.split('.');
                    const ext = info[info.length - 1];
                    if (/\.(png|jpe?g|svg|gif|tiff|bmp|ico)$/i.test(assetInfo.name)) {
                        return `assets/img/[name]-[hash].${ext}`;
                    }
                    if (/\.(woff2?|eot|ttf|otf)$/i.test(assetInfo.name)) {
                        return `assets/fonts/[name]-[hash].${ext}`;
                    }
                    if (/\.css$/i.test(assetInfo.name)) {
                        return `assets/css/[name]-[hash].${ext}`;
                    }
                    return `assets/[name]-[hash].${ext}`;
                },
            },
        },
        
        // Optimización del tamaño del chunk
        chunkSizeWarningLimit: 600,
        
        // Sourcemaps solo para desarrollo
        sourcemap: false,
        
        // Optimizar CSS
        cssMinify: true,
        cssCodeSplit: true,
    },
    
    // Optimización de dependencias
    optimizeDeps: {
        include: [
            'alpinejs',
            'chart.js',
            'driver.js',
            'vanilla-toast',
            'boxicons',
        ],
    },
    
    // Performance hints
    server: {
        hmr: {
            overlay: true,
        },
    },
});
