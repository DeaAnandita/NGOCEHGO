import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
  server: {
    host: '0.0.0.0',
    port: 5173,
    hmr: {
      host: '10.204.85.121', // IP kamu
    },
  },
});