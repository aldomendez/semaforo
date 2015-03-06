var gulp = require('gulp');
// var coffee = require('gulp-coffee');
// var gutil = require('gulp-util');
var livereload = require('gulp-livereload');
var wait = require('gulp-wait');

var DEST = '\\\\cymautocert\\osaapp\\semaforo-dev';
var BASE = 'C:\\apps\\semaforo-dev';

function serverPath (path) {
  var rgx = /[a-zA-Z-_~\. ]*$/;
  path = path.replace(rgx, '');
  return path.replace(BASE,'');
}

gulp.task('watch', function () {
  livereload.listen();
  
  gulp.watch(['**/**.coffee','**/**.php','**/**.map','**/**.sql','**/**.json','**/**.js','**/**.html'], function (event) {
    // console.log("Copiar php iniciado");
  }).on('change', function (event) {
    // Copia el archivo que cambio a el compartido 
     gulp.src(event.path)
       .pipe(gulp.dest(DEST + serverPath(event.path)))
       .pipe(wait(1200))
       .pipe(livereload());
     
    console.log("Compilado y copiado :" + event.path);
  });

});

gulp.task('default', ['watch']);