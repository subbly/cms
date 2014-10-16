/*!
 * Subbly's Gruntfile
 * http://subbly.com
 */

module.exports = function(grunt) 
{
    grunt.initConfig(
    {
        pkg: grunt.file.readJSON('package.json')
      , banner: '/*! \n' +
' * Subbly  \n' +
' *\n' +
' * @package   Subbly Back-office\n' +
' * @version   v<%= pkg.version %>\n' +
' * @link      <%= pkg.homepage %>\n' +
' */\n'
      , less: 
        {
            development: 
            {
                options: 
                {
                    strictMath: true
                  , sourceMap: true
                  , outputSourceFiles: true
                  , sourceMapURL: '<%= pkg.name %>.development.css.map'
                  , sourceMapFilename: 'themes/backend/assets/css/<%= pkg.name %>.development.css.map'
                }
              , files: {
                  'themes/backend/assets/css/<%= pkg.name %>.development.css': 'src/less/subbly.less'
                }
            }
          , staging: 
            {
                options: 
                {
                    strictMath: true
                  , sourceMap: true
                  , outputSourceFiles: true
                  , sourceMapURL: '<%= pkg.name %>.staging.css.map'
                  , sourceMapFilename: 'themes/backend/assets/css/<%= pkg.name %>.staging.css.map'
                  , compress: true
                }
              , files: {
                  'themes/backend/assets/css/<%= pkg.name %>.staging.css': 'src/less/subbly.less'
                }
            }
        }
      , autoprefixer: 
        {
            options: 
            {
              browsers: ['last 2 versions', 'ie 8', 'ie 9', 'android 2.3', 'android 4', 'opera 12']
            }
          , core: 
            {
                options: 
                {
                    map:     true
                  , compile: true
                }
              , files: 
                {
                    'themes/backend/assets/css/<%= pkg.name %>.development.css': [
                        'src/less/subbly.less'
                    ]
                }
              , src: 'themes/backend/assets/css/<%= pkg.name %>.development.css'
            }
        }
      , csslint: 
        {
            options: 
            {
              csslintrc: 'src/less/.csslintrc'
            }
          , src: [ 'themes/backend/assets/css/<%= pkg.name %>.development.css' ]
        }
      , cssmin: 
        {
            options: 
            {
                compatibility: 'ie8'
              , keepSpecialComments: '*'
            }
          , core: 
            {
                files: 
                {
                  'themes/backend/assets/css/<%= pkg.name %>.production.css': 'themes/backend/assets/css/<%= pkg.name %>.development.css'
                }
            }
        }
      , usebanner: 
        {
            options: 
            {
                position: 'top'
              , banner: '<%= banner %>'
            }
          , files: 
            {
                src: 'themes/backend/assets/css/*.css'
            }
        }
      , csscomb: 
        {
            options: {
              config: 'less/.csscomb.json'
            }
          , dist: 
            {
                expand: true
              , cwd: 'themes/backend/assets/css/'
              , src: ['*.css', '!*.min.css']
              , dest: 'themes/backend/assets/css/'
            }
        }
      , concat_sourcemap: 
        {
            options: 
            {
                banner: '<%= banner %>'
              , stripBanners: false
              , sourcesContent: true
            }
          , js: 
            {
                src: [
                  // Dependencies
                  // 'src/lib/zepto/zepto.js'
                  // , 'src/lib/lodash/lodash.underscore.js'
                    'src/lib/jquery/jquery-2.1.0.js'
                  , 'src/lib/jquery/jquery-ui-1.10.4.custom.js'
                  , 'src/lib/jquery/jquery.nanoscroller.js'
                  , 'src/lib/jquery/jquery.fileupload.js'
                  , 'src/lib/underscore/underscore.js'
                  , 'src/lib/underscore/underscore.string.js'
                  , 'src/lib/backbone/backbone.js'
                  , 'src/lib/backbone/backbone-approuter.js'
                  , 'src/lib/backbone/backbone.controller.js'
                  , 'src/lib/handlebars/handlebars-v1.3.0.js'
                  , 'src/lib/bootstrap/dropdown.js'
                  , 'src/lib/bootstrap/button.js'
                  , 'src/lib/bootstrap/modal.js'
                  , 'src/lib/momentjs/moment-with-langs.js'
                  , 'src/lib/download/download.js'

                  // App
                  , 'src/js/i18n.js'
                  , 'src/js/helpers.js'
                  , 'src/js/closure.intro.js'
                  , 'src/js/closure.utils.js'


                  , 'src/js/backbone.js'
                  , 'src/js/validation.js'
                  , 'src/js/delete.js'
                  , 'src/js/uploader.js'
                  , 'src/js/xhr.js'

                  , 'src/js/models/user.js'

                  , 'src/js/collections/list.js'

                  // , 'src/js/controllers/admin.js'

                  , 'src/js/views/form.js'
                  , 'src/js/views/list.js'
                  , 'src/js/views/sortable.js'
                  , 'src/js/views/login.js'
                  , 'src/js/views/modal.js'

                  , 'src/js/subbly.js'
                  , 'src/js/session.js'
                  , 'src/js/feedback.js'

                  , 'src/js/router.js'
                  , 'src/js/closure.outro.js'
                ]
              , dest: 'themes/backend/assets/js/<%= pkg.name %>.development.js'
            }
        }
      , uglify: 
        {
          staging:
          {
              options: 
              {
                  banner: '<%= banner %>'
                , sourceMap: true
                , sourceMapName: 'themes/backend/assets/js/<%= pkg.name %>.staging.js.map'
              }
            , files: 
              {
                  'themes/backend/assets/js/<%= pkg.name %>.staging.js': 'themes/backend/assets/js/<%= pkg.name %>.development.js'
              }            
          }
        , production:
          {
              options: 
              {
                  banner: '<%= banner %>'
                , sourceMap: false
              }
            , files: 
              {
                  'themes/backend/assets/js/<%= pkg.name %>.production.js': 'themes/backend/assets/js/<%= pkg.name %>.development.js'
              }            
          }
        }
      , watch: 
        {
            javascript: 
            {
              files: ['src/js/*.js', 'src/js/**/*.js', 'src/js/*/*.js']
            , tasks: ['concat_sourcemap']
            , options: 
              {
                  debounceDelay: 250
              }
            }
          , css: 
            {
              files: ['src/less/*.less']
            , tasks: ['less-compile']
            , options: 
              {
                  debounceDelay: 250
              }
            }
          , grunt: 
            {
              files: ['Gruntfile.js']
            }
        }
    });

    // grunt.loadNpmTasks('grunt-recess');
    grunt.loadNpmTasks('grunt-contrib-less');
    grunt.loadNpmTasks('grunt-contrib-cssmin');
    grunt.loadNpmTasks('grunt-contrib-concat');
    grunt.loadNpmTasks('grunt-concat-sourcemap');
    grunt.loadNpmTasks('grunt-contrib-uglify');
    grunt.loadNpmTasks('grunt-contrib-watch');


    grunt.registerTask('less-compile', ['less:development']);

    grunt.registerTask('default', [
        'concat_sourcemap'
      , 'less-compile'
    ]);
    grunt.registerTask('build', [
        'concat_sourcemap'
      , 'uglify'
      , 'less'
      , 'cssmin'
    ]);
    grunt.registerTask('dev', [
        'watch'
    ]);

};