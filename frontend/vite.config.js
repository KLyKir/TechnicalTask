import { defineConfig } from 'vite';
import vue from '@vitejs/plugin-vue';
import path from 'path';

export default defineConfig({
    plugins: [vue()],
    build: {
        outDir: 'dist'
    },
    root: path.resolve(__dirname, '.')
});