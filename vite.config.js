import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
  server: {
    host: '0.0.0.0',     // biar bisa diakses dari luar
    port: 5173,          // default vite port
    hmr: {
      host: '10.204.85.121',  // IP kamu
    },
  },
  plugins: [
    laravel({
      input: ['resources/css/app.css', 'resources/js/app.js'],
      refresh: true,
    }),
  ],
});
