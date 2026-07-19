import { defineConfig } from 'vitest/config';
import vue from '@vitejs/plugin-vue';
import { resolve } from 'path';

export default defineConfig({
    plugins: [vue()],
    test: {
        environment: 'jsdom',
        globals: true,
        include: ['resources/js/**/*.{test,spec}.{js,ts}'],
        exclude: ['node_modules', 'e2e'],
        coverage: {
            provider: 'v8',
            include: ['resources/js/composables/**', 'resources/js/components/**'],
            thresholds: {
                composables: 80,
            },
        },
    },
    resolve: {
        alias: {
            '@': resolve(__dirname, 'resources/js'),
        },
    },
});
