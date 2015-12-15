var gulp = require('gulp');
var coffee = require('gulp-coffee');
var gutil = require('gulp-util');
var livereload = require('gulp-livereload');
var wait = require('gulp-wait');
var webpack = require('webpack');
var webpackconfig = require('./webpack.config.js');

// Create a single instance of webpack to allow caching

devCompiler = webpack(webpackconfig);


var DEST = '\\\\cymautocert\\osaapp\\semaforo-dev';
var BASE = __dirname;

function serverPath (path) {
  // Obtiene la direccion a la que se enviaran los datos
  var rgx = /[a-zA-Z-_~\. ]*$/;
  path = path.replace(rgx, '');
  return path.replace(BASE,'');
}

copyAndReload = function copyAndReload (event) {
  console.log('Sended to: ',serverPath(event.path));
  gulp.src(event.path)
       // .pipe(gulp.dest(DEST + serverPath(event.path)))
       .pipe(wait(1200))
       .pipe(livereload());
}

compileAndPush = function compileAndPush (event) {
  path = event.path.replace(BASE,'');
  path = '.' + path.replace('\\','/');
  console.log(path);
  gulp.src(path)
    .pipe(coffee()).on('error',gutil.log)
    .pipe(gulp.dest('./js'))
    // .pipe(gulp.dest(DEST + serverPath(event.path)))
    .pipe(wait(1200))
    .pipe(livereload());
}

gulp.task('watch', function () {
  livereload.listen();
  /*
    La siguiente es la lista de todo lo que esta observando gulp, 
    me gustaria que pudiera decirle cueles son las carpetas que 
    tiene que omitir.
  */
  gulp.watch(['coffee/*.vue','coffee/components/*.vue','coffee/newHome.js'], function (event) {
  }).on('change', function (event) {
     // compileAndPush(event);
     gutil.log('starting webpack');
     devCompiler.run(function (err, stats) {
        if(err) throw new gutil.PluginError("webpack:", err)
        gutil.log("[webpack:vue]:", stats.toString({colors:true}));
        livereload()
     })
  });  
  gulp.watch(['*.php','simple.templates/*.php'], function (event) {
  }).on('change', function (event) {
     copyAndReload(event);
  });  
  gulp.watch(['js/**.map'], function (event) {
  }).on('change', function (event) {
     copyAndReload(event);
  });  
  gulp.watch(['sql/**.sql'], function (event) {
  }).on('change', function (event) {
     copyAndReload(event);
  });  
  gulp.watch(['*.json'], function (event) {
  }).on('change', function (event) {
     copyAndReload(event);
  });  
  gulp.watch(['js/**.js','gulpfile.js'], function (event) {
  }).on('change', function (event) {
     copyAndReload(event);
  });  
  gulp.watch(['*.html'], function (event) {
  }).on('change', function (event) {
     copyAndReload(event);
  });
});

gulp.task('default', ['watch']);
