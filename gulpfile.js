const {src,dest,series, parallel,watch}=require('gulp');
const rename=require('gulp-rename');
const uglify=require('gulp-uglify-es').default;
const uglifycss=require('gulp-uglifycss');


function javascript(){
    return src('resources/js/*.js')
        .pipe(uglify())  
        .pipe(rename({'extname':'.min.js'}))  
        .pipe(dest('public/js'));
}

function css(){
    return src('resources/css/*.css')
        .pipe(uglifycss(
            {
                'max-length':80,
                'uglyComments':true
            }
        ))    
        .pipe(rename({'extname':'.min.css'}))
        .pipe(dest('public/css'));
}

exports.default=parallel(javascript,css);