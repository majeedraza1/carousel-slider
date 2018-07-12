const gulp = require('gulp');
const sass = require('gulp-sass');
const sourcemaps = require('gulp-sourcemaps');
const autoprefixer = require('gulp-autoprefixer');
const concat = require('gulp-concat');
const uglify = require('gulp-uglify');
const livereload = require('gulp-livereload');
const rollup = require('rollup');
const buble = require('rollup-plugin-buble');
const file = require('gulp-file');

const sassOptions = {
    errLogToConsole: true,
    outputStyle: 'compressed'
};
const autoprefixerOptions = {
    browsers: ['last 5 versions', '> 5%']
};

gulp.task('scss', function () {
    return gulp.src('./assets/scss/**/*.scss')
        .pipe(sourcemaps.init())
        .pipe(sass(sassOptions).on('error', sass.logError))
        .pipe(autoprefixer(autoprefixerOptions))
        .pipe(sourcemaps.write('.'))
        .pipe(gulp.dest('./assets/css'))
        .pipe(livereload());
});

gulp.task('js', function () {
    return gulp.src('./assets/js/admin/*.js')
        .pipe(concat('admin.js'))
        .pipe(gulp.dest('./assets/js'))
        .pipe(concat('admin.min.js'))
        .pipe(uglify())
        .pipe(gulp.dest('./assets/js'))
        .pipe(livereload());
});

gulp.task('bundle', function () {
    return rollup.rollup({
        input: './assets/js/public/main.js',
        plugins: [buble()]
    }).then(function (bundle) {
        return bundle.generate({
            format: 'umd'
        });
    }).then(function (gen) {
        return file('temp.js', gen.code, {src: true})
            .pipe(concat('script.js'))
            .pipe(gulp.dest('./assets/js/'))
            .pipe(concat('script.min.js'))
            .pipe(uglify())
            .pipe(gulp.dest('./assets/js/'))
            .pipe(livereload());
    });
});

gulp.task('watch', function () {
    livereload.listen();
    gulp.watch('./assets/scss/**/*.scss', ['scss']);
    gulp.watch('./assets/js/admin/*.js', ['js']);
    gulp.watch('./assets/js/public/*.js', ['bundle']);
});

gulp.task('default', ['scss', 'js', 'bundle', 'watch']);
