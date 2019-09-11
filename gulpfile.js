'use strict';

const gulp = require('gulp'),
  less = require('gulp-less'),
  path = require('path'),
  sourcemaps = require('gulp-sourcemaps'),
  gutil = require('gulp-util'),
  notify = require('gulp-notify'),
  LessAutoprefix = require('less-plugin-autoprefix'),
  browserSync = require("browser-sync").create();


// General settings
const autoprefix = new LessAutoprefix({browsers: ['last 2 versions']});

function styles(done) {
  return gulp.src('./less/frontend.less')
    .pipe(sourcemaps.init())
    .pipe(less({
      plugins: [autoprefix],
      paths: [path.join(__dirname, 'less', 'includes')]
    }).on('error', function(err){
      gutil.log(err);
      this.emit('end');
    }))
    .pipe(sourcemaps.write())
    .pipe(gulp.dest('./css'))
    .pipe(notify({ message: 'LESS task complete'}))
    .pipe(browserSync.stream());

  done();
}

// Watch files
function watch() {

  browserSync.init({
    proxy: 'inclusie.gebruikercentraal.co.uk'
  });

  gulp.watch('less/**/*.less', gulp.series(styles));
}


exports.styles = styles;
exports.default = watch;