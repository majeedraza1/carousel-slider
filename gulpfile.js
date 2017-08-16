const gulp = require('gulp');
const sass = require('gulp-sass');
const concat = require('gulp-concat');
const uglify = require('gulp-uglify');
const livereload = require('gulp-livereload');

gulp.task('sass', function () {
    gulp.src('./assets/scss/*.scss')
        .pipe(sass({
            outputStyle: 'compressed'
        }).on('error', sass.logError))
        .pipe(gulp.dest('./assets/css'))
        .pipe(livereload());
});

gulp.task('js', function () {
    gulp.src('./assets/js/src/*.js')
        .pipe(concat('script.js'))
        .pipe(gulp.dest('./assets/js'))
        .pipe(concat('script.min.js'))
        .pipe(uglify())
        .pipe(gulp.dest('./assets/js'))
        .pipe(livereload());
});

gulp.task('watch', function () {
    livereload.listen();
    gulp.watch('./assets/scss/*.scss', ['sass']);
    gulp.watch('./assets/js/src/*.js', ['js']);
});

gulp.task('default', ['sass', 'js', 'watch']);
