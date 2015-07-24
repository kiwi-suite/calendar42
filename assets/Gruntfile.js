module.exports = function (grunt) {
    grunt.initConfig({
        module: 'calendar42',
        vendor_dir: 'bower_components',
        dist: 'dist',

        bower: {
            install: {
                options: {
                    copy: false
                }
            }
        },

        concurrent: {
            all: ['compile-vendor-js', 'compile-app-js', 'less:app']
        },

        concat: {
            options: {
                separator: ';'
            },
            vendor: {
                src: [
                    //'<%= vendor_dir %>/jquery/dist/jquery.js', // admin42
                    //'<%= vendor_dir %>/angular/angular.js', // admin42
                    //'<%= vendor_dir %>/moment/min/moment-with-locales.js', // admin42
                    '<%= vendor_dir %>/angular-ui-calendar/src/calendar.js',
                    '<%= vendor_dir %>/fullcalendar/dist/fullcalendar.js',
                    '<%= vendor_dir %>/fullcalendar/dist/lang-all.js'
                    //'<%= vendor_dir %>/fullcalendar/dist/gcal.js'
                ],
                dest: '<%= dist %>/js/vendor.js'
            },
            app: {
                src: [
                    'javascripts/*.js',
                    'javascripts/directive/*.js',
                    'javascripts/filter/*.js',
                    'javascripts/controller/*.js'
                ],
                dest: '<%= dist %>/js/<%= module %>.js'
            }
        },

        uglify: {
            options: {
                mangle: false
            },
            vendor: {
                src: '<%= dist %>/js/vendor.js',
                dest: '<%= dist %>/js/vendor.min.js'
            },
            app: {
                src: '<%= dist %>/js/<%= module %>.js',
                dest: '<%= dist %>/js/<%= module %>.min.js'
            }
        },

        less: {
            options: {
                compress: true,
                cleancss: true
            },
            app: {
                files: {
                    '<%= dist %>/css/<%= module %>.min.css': [
                        'less/main.less'
                    ]
                }
            }
        },

        clean: {
            all: ['<%= dist %>/fonts/', '<%= dist %>/css/', '<%= dist %>/js/', '<%= dist %>/images/'],

            vendorjs: ['<%= dist %>/js/vendor.js'],
            appjs: ['<%= dist %>/js/<%= module %>.js']
        },

        watch: {
            grunt: {
                files: ['Gruntfile.js', 'bower.json'],
                tasks: ['default']

            },
            js: {
                files: ['javascripts/**/*.js'],
                tasks: ['compile-app-js']
            },
            less: {
                files: ['less/*.less', 'less/**/*.less'],
                tasks: ['compile-css']
            }
        }
    });

    grunt.registerTask('default', ['bower', 'concurrent:all']);
    grunt.registerTask('compile-vendor-js', ['concat:vendor', 'uglify:vendor', 'clean:vendorjs']);
    grunt.registerTask('compile-app-js', ['concat:app', 'uglify:app', 'clean:appjs']);
    grunt.registerTask('compile-css', ['less:app']);
    grunt.registerTask('clear', ['clean:all']);
    require('load-grunt-tasks')(grunt);
};
