var gulp = require('gulp');
var babel = require('gulp-babel');
var gulpif = require('gulp-if');
var gutil = require('gulp-util');
var concat = require('gulp-concat');
var uglify = require('gulp-uglify');
var uglifycss = require('gulp-uglifycss');
var browserify = require('browserify');
var babelify = require('babelify');
var vueify = require('vueify');
var source = require('vinyl-source-stream');
var buffer = require('vinyl-buffer');
var watchify = require('watchify');
var sourcemaps = require('gulp-sourcemaps');

var config = {
    isProd: gutil.env.env == 'prod',
    resourcesPath: './Resources/assets/',
    targetPath: './Resources/public/',
    jsTarget: './Resources/public/js/',
    cssTarget: './Resources/public/css/',
};

gulp.task('js', function () {
    var bundler = watchify(browserify({
        entries: config.resourcesPath + "js/main.js",
        paths: [
            './node_modules',
            config.resourcesPath + 'js',
            config.resourcesPath + 'vue',
        ],
        debug: true,
        cache: {},
        packageCache: {},
    }).transform(babelify, {
        presets: ['es2015'], plugins: ["transform-runtime"]
    }).transform(vueify));

    function rebundle() {
        return bundler.bundle().on('error', function (err) {
            err.stream = null; // Quitamos contenido no leible
            console.log('     #########  ERROR   ###########');
            console.log(err);
            this.emit('end');
        })
            .pipe(source("presupuesto.js"))
            .pipe(buffer())
            .pipe(gulpif(!config.isProd, sourcemaps.init({loadMaps: true})))
            .pipe(gulpif(config.isProd, uglify()))
            .pipe(gulpif(!config.isProd, sourcemaps.write('.')))
            .pipe(gulp.dest(config.jsTarget))
    }

    bundler.on('update', function () {
        rebundle();
        gutil.log('Compilando Archivos de Vue...');
    }).on('time', function (time) {
        gutil.log('Listo en: ', gutil.colors.cyan(time + ' ms'));
    })

    return rebundle();
});

gulp.task('css', function () {
    var cssPrefixPath = config.resourcesPath + "css/"
    return gulp.src([
        cssPrefixPath + 'bootstrap.min.css',
        cssPrefixPath + 'bootstrap-responsive.min.css',
        cssPrefixPath + 'jqueryui/jquery.ui.all.css',
        cssPrefixPath + 'jgrowl/jgrowl.css',
        cssPrefixPath + 'validationEngine.jquery.css',
        cssPrefixPath + 'estilos.css'
    ])
        .pipe(gulpif(!config.isProd, sourcemaps.init({loadMaps: true})))
        .pipe(concat('styles.css'))
        .pipe(gulpif(config.isProd, uglifycss()))
        .pipe(gulpif(!config.isProd, sourcemaps.write('.')))
        .pipe(gulp.dest(config.cssTarget));
});

// gulp.task('watch', function () {
//     //gulp.watch(['**/*.{css,scss}'], { cwd: './app/Resources/assets/'}, ['css', 'admin:css']);
//     //gulp.watch(['**/*.{css,scss}'], { cwd: './web/css/'}, ['css', 'admin:css']);
//     gulp.watch([
//         'js/app.js',
//         'js/**/*.js',
//         'vue/**/*.vue',
//         'vue/**/*.js'
//     ], {cwd: './assets'}, ['js']);
// });

gulp.task('default', ['js', 'css']);

