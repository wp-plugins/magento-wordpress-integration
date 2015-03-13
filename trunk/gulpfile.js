var gulp = require('gulp');

var sass = require('gulp-sass');
var concat = require('gulp-concat');
var uglify = require('gulp-uglify');
var concatCss = require('gulp-concat-css');
var minifyCSS = require('gulp-minify-css');
var autoprefixer = require('gulp-autoprefixer');

var paths = {
    frontendscripts: ['source/frontend/js/**/*.js'],
    frontendstyles: ['source/frontend/scss/**/*.scss'],
    adminscripts: ['source/admin/js/**/*.js'],
    adminstyles: ['source/admin/scss/**/*.scss'],
    src: ['inc/**/*', 'templates/**/*', 'assets/**/*', 'mwi-shortcodes.php'],
    ccsrc: ['**/*']
};

/* 	=============================
	Tasks 
	============================= */
   	
	gulp.task('frontendscripts', function() {
        // Minify and copy all JavaScript (except vendor scripts)
        return gulp.src(paths.frontendscripts)
            .pipe(uglify())
            .pipe(concat('scripts.min.js'))
            .pipe(gulp.dest('assets/frontend/js'));
	});
	
	gulp.task('frontendstyles', function () {
        return gulp.src(paths.frontendstyles)
            .pipe(sass())
            .pipe(autoprefixer({
                browsers: ['last 2 versions'],
                cascade: false
            }))
            .pipe(minifyCSS())
            .pipe(gulp.dest('assets/frontend/css'));
    });
	
	gulp.task('adminscripts', function() {
        // Minify and copy all JavaScript (except vendor scripts)
        return gulp.src(paths.adminscripts)
            .pipe(uglify())
            .pipe(concat('scripts.min.js'))
            .pipe(gulp.dest('assets/admin/js'));
	});
	
	gulp.task('adminstyles', function() {
        // Minify and copy all JavaScript (except vendor scripts)
        return gulp.src(paths.adminstyles)
            .pipe(sass())
            .pipe(autoprefixer({
                browsers: ['last 2 versions'],
                cascade: false
            }))
            .pipe(minifyCSS())
            .pipe(gulp.dest('assets/admin/css'));
	});
	
	// Rerun the task when a file changes
	gulp.task('watch', function () {
        gulp.watch(paths.frontendscripts, ['frontendscripts']);
        gulp.watch(paths.frontendstyles, ['frontendstyles']);
        gulp.watch(paths.adminscripts, ['adminscripts']);
        gulp.watch(paths.adminstyles, ['adminstyles']);
	});
	
	// The default task (called when you run `gulp` from cli)
	// gulp.task('default', ['scripts', 'styles', 'watch']);