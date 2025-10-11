// rollup.config.js
import resolve from '@rollup/plugin-node-resolve';
import postcss from 'rollup-plugin-postcss';

export default {
  input: 'src/js/app.js',
  output: {
    file: 'public/build/js/app.js',
    format: 'iife',
    name: 'bundle',
    sourcemap: true,
  },
  plugins: [
    resolve(),
    postcss({
      extract: true, // Opcional: saca el CSS a un archivo externo
    }),
  ],
};
