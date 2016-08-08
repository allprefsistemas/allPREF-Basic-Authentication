var gulp = require('gulp');
var cleanCSS = require('gulp-clean-css');
var rename = require('gulp-rename');
var uglify = require('gulp-uglify');

gulp.task('css', function () {
    return gulp
        .src('public/dev/css/**/*.css')
        .pipe(cleanCSS({compatibility: 'ie8'}))
        .pipe(rename({ suffix: ".min" }))
        .pipe(gulp.dest('public/css/'));
});

gulp.task('js', function() {
    return gulp
        .src('public/dev/js/**/*.js')
        .pipe(uglify())
        .pipe(rename({ suffix: ".min" }))
        .pipe(gulp.dest('public/js/'));
});

gulp.task('watch', function() {
    gulp.watch('public/dev/css/**/*.css', ['css']);
    gulp.watch('public/dev/js/**/*.js', ['js']);
});

gulp.task('default', [
    'css',
    'js',
    'watch'
]);
