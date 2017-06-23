// Gulp includes
var gulp    = require('gulp');
var config  = require('./gulp-config.json');

// Plugins includes
var jshint      = require('gulp-jshint');
var less        = require('gulp-less');
var concat      = require('gulp-concat');
var minifycss   = require('gulp-minify-css');
var browserSync = require('browser-sync');
var uglify      = require('gulp-uglify');
var rename      = require('gulp-rename');
var gutil       = require('gulp-util');

// BROWSER: Proxy sync
gulp.task('browser-sync', function() {
    browserSync({
        proxy: config.url
    });
});

// BROWSER: Reload
gulp.task('bs-reload', function() {
    browserSync.reload();
});

// FONTS: Copy fonts dir
gulp.task('fonts', function() {
    return gulp.src(['bower_components/bootstrap/fonts/*', 'bower_components/font-awesome/fonts/*', 'app/Resources/public/frontend/fonts/*'])
        .pipe(gulp.dest('web/fonts'));
});

// CSS: Compile & minify Less
gulp.task('less', function() {
    return gulp.src(['app/Resources/public/frontend/css/**/*.less'])
        .pipe(concat('main.css'))
        .pipe(less({ sourceMap: true,  paths: ['./bower_components']})).on('error', gutil.log)
        .pipe(minifycss())
        .pipe(gulp.dest('web/css'));
});
gulp.task('admin-less', function() {
    return gulp.src(['app/Resources/public/admin/css/**/*.less'])
        .pipe(concat('admin.css'))
        .pipe(less({ sourceMap: true,  paths: ['./bower_components']})).on('error', gutil.log)
        .pipe(minifycss())
        .pipe(gulp.dest('web/css'));
});

// JS: Lint
gulp.task('lint', function() {
    return gulp.src('app/Resources/public/frontend/js/**/*.js')
        .pipe(jshint())
        .pipe(jshint.reporter('default'));
});

// JS: Concatenate & minify layout scripts
gulp.task('scripts', function() {
    return gulp.src([
            'bower_components/jquery/dist/jquery.js',
            'bower_components/lodash/dist/lodash.js',
            'bower_components/numeral/numeral.js',
            'bower_components/numeral/languages/es.js',
            'bower_components/modernizr/modernizr.js',
            'bower_components/holderjs/holder.js',
            'bower_components/jquery-ui/jquery-ui.min.js',
            'bower_components/bootstrap/dist/js/bootstrap.js',
            'bower_components/angular/angular.js',
            'bower_components/angular-resource/angular-resource.js',
            'bower_components/angular-cookies/angular-cookies.js',
            'bower_components/angular-sanitize/angular-sanitize.js',
            'bower_components/angular-route/angular-route.js',
            'bower_components/angular-touch/angular-touch.js',
            'bower_components/angular-nl2br/angular-nl2br.js',
            'bower_components/checklist-model/checklist-model.js',
            'bower_components/angular-google-maps/dist/angular-google-maps.js',
            'bower_components/angular-xeditable/dist/js/xeditable.js',
            'bower_components/underscore/underscore.js',
            'bower_components/ekko-lightbox.js',
            'bower_components/fancybox/source/jquery.fancybox.pack.js',
            'bower_components/restangular/dist/restangular.js',
            'bower_components/typeahead.js/dist/typeahead.bundle.js',
            'bower_components/typeahead-addresspicker/dist/typeahead-addresspicker.js',
            'bower_components/select2/select2.min.js',
            'web/bundles/mopabootstrap/js/mopabootstrap-collection.js',
            'bower_components/bootstrap-calendar/js/calendar.js',
            'bower_components/bootstrap-calendar/js/language/es-ES.js',
            'bower_components/bootstrap-calendar/js/language/ca-ES.js',
            'app/Resources/public/js/calendar.js'
    ])
        .pipe(concat('main.js'))
        .pipe(gulp.dest('web/js'))
        .pipe(rename('main.min.js'))
        .pipe(uglify())
        .pipe(gulp.dest('web/js'));
});

gulp.task('scripts-admin', function() {
    return gulp.src([
            'bower_components/angular/angular.js',
            'bower_components/Chart.js/Chart.min.js',
            'bower_components/moment/min/moment-with-locales.min.js',
            'bower_components/typeahead.js/dist/typeahead.bundle.js',
            'bower_components/typeahead-addresspicker/dist/typeahead-addresspicker.js',
            'bower_components/angular-chart.js/dist/angular-chart.js',
            'bower_components/bootstrap-daterangepicker/daterangepicker.js',
            'bower_components/angular-daterangepicker/js/angular-daterangepicker.js',
            'bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.js',
            'bower_components/bootstrap-datepicker/dist/locales/bootstrap-datepicker.ca.min.js',
            'bower_components/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js'
    ])
        .pipe(concat('admin.js'))
        .pipe(gulp.dest('web/js'))
        .pipe(rename('admin.min.js'))
        .pipe(uglify())
        .pipe(gulp.dest('web/js'));
});

// JS: Concatenate & minify custom scripts
gulp.task('my-js', function() {
    return gulp.src('app/Resources/public/frontend/js/**/*.js')
        .pipe(concat('my.js'))
        .pipe(gulp.dest('web/js'))
        .pipe(rename('my.min.js'))
        .pipe(uglify())
        .pipe(gulp.dest('web/js'));
});
// JS: Concatenate & minify custom scripts
gulp.task('admin-my-js', function() {
    return gulp.src('app/Resources/public/admin/js/**/*.js')
        .pipe(concat('my.js'))
        .pipe(gulp.dest('web/js'))
        .pipe(rename('admin-my.min.js'))
        .pipe(uglify())
        .pipe(gulp.dest('web/js'));
});

// Watch
gulp.task('watch', ['my-js', 'less', 'admin-less', 'admin-my-js'], function() {
    gulp.watch('app/Resources/views/Front/**/*.twig');
    gulp.watch('src/Demofony2/UserBundle/Resources/views/**/*.twig');
    gulp.watch('app/Resources/public/frontend/js/**/*.js', ['lint', 'my-js']);
    gulp.watch('app/Resources/public/admin/js/**/*.js', ['lint', 'admin-my-js']);
    gulp.watch('app/Resources/public/frontend/css/**/*.less', ['less']);
});

// Watch with BrowserSync
gulp.task('BSwatch', ['browser-sync'], function() {
    gulp.watch('app/Resources/views/Front/**/*.twig', ['bs-reload']);
    gulp.watch('src/Demofony2/UserBundle/Resources/views/**/*.twig', ['bs-reload']);
    gulp.watch('app/Resources/public/frontend/js/**/*.js', ['lint', 'my-js', 'bs-reload']);
    gulp.watch('app/Resources/public/frontend/css/**/*.less', ['less', 'bs-reload']);
});

// Default
gulp.task('default', ['lint', 'fonts', 'less', 'scripts', 'my-js', 'admin-my-js', 'scripts-admin', 'admin-less']);
