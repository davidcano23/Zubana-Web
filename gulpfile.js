const { src, dest, watch, series } = require('gulp');
const sass = require('gulp-dart-sass');

// Compilar SCSS
function compilarSass() {
  return src('src/scss/**/*.scss')
    .pipe(sass().on('error', sass.logError))
    .pipe(dest('public/build/css'));
}

// Escucha cambios en SCSS
function dev() {
  watch('src/scss/**/*.scss', compilarSass);
}

exports.default = series(compilarSass, dev);
