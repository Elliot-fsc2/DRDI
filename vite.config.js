import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
  plugins: [
    laravel({
      input: ['resources/css/app.css', 'resources/js/app.js', 'resources/css/filament/instructor/theme.css',
        'resources/css/filament/student/theme.css'
      ],
      refresh: true,
    }),
    tailwindcss(),
  ],
});
