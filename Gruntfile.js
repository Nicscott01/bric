//GRUNTFILE for BRIC


module.exports = function (grunt) {

	// Project configuration.
	grunt.initConfig({
		pkg: grunt.file.readJSON('package.json'),

		sass: {
			// this is the "dev" Sass config used with "grunt watch" command
			dev: {
				options: {
					style: 'compressed',
					// tell Sass to look in the Bootstrap stylesheets directory when compiling
					loadPath: ['node_modules/bootstrap/scss/', 'assets/src/css/bric/']
				},
				files: {
					// the first path is the output and the second is the input
					'assets/css/bric-style.css': 'assets/src/css/bric-style.scss'
				}
			},
			// this is the "production" Sass config used with the "grunt buildcss" command
			dist: {
				options: {
					style: 'compressed',
					loadPath: 'node_modules/bootstrap/scss/'
				},
				files: {
					'static/css/mystyle.css': 'sass/mystyle.scss'
				}
			}
		},
		// configure the "grunt watch" task
		watch: {
			sass: {
				files: 'assets/src/css/*.scss',
				tasks: ['sass:dev', 'postcss']
			}
		},

		concat: {
			options: {
				separator: ';',
				stripBanners: true,
				banner: '/*! <%= pkg.name %> <%= grunt.template.today("yyyy-mm-dd") %> */\n'
			},

			dist: {
				src: ['js/*.js'],
				dest: 'js/main.min.js'
			}
		},


		uglify: {
			options: {
				manage: false,
				preserveComments: 'all' //preserve all comments on JS files
			},
			photoswipe: {
				options: {
					preserveComments: false
				},
				files: {
					'assets/js/photoswipe-thumbnail-opener.min.js': ['assets/src/js/photoswipe-thumbnail-opener.js'],
					'assets/js/google-maps-render.min.js': ['assets/src/js/google-maps-render.js']
				}
			}
			/*bootstrap_js: {
				options: {
					preserveComments: false	
				},
				files: {
					'assets/js/bootstrap.min.js': ['node_modules/bootstrap/dist/js/bootstrap.js']
				}
			},
			slideout: {
				files: {
					'assets/js/slideout.min.js': ['node_modules/slideout/dist/slideout.min.js']
				}
			}*/
			
		},

		copy: {
			main: {
				files: [
					{
						expand:true,
						cwd: 'node_modules/bootstrap/dist/js/',
						src: ['bootstrap.bundle.min.js', 'bootstrap.bundle.min.js.map'],
						dest: 'assets/js/',
						filter: 'isFile',
						flatten: true,
					},{
						expand:true,
						cwd: 'node_modules/slideout/dist/',
						src: ['slideout.min.js'],
						dest: 'assets/js/',
						filter: 'isFile',
						flatten: true,
					},{
						expand:true,
						cwd: 'node_modules/photoswipe/dist/',
						src: ['photoswipe.min.js', 'photoswipe-ui-default.min.js'],
						dest: 'assets/js/',
						filter: 'isFile',
						flatten: true,
					},{
						expand:true,
						cwd: 'assets/src/css/photoswipe/default-skin/',
						src: ['*'],
						dest: 'assets/css/photoswipe/default-skin/',
						filter: 'isFile',
						flatten: true,
					}
				]
			}
		},
		
		postcss: {
			options: {
				map: true,
				processors: [
					require('autoprefixer')
				]
			},
			dist: {
				src: 'assets/css/*.css'
			}
		}


	});

	// Load the plugin that provides the "watch" task.
	grunt.loadNpmTasks('grunt-contrib-watch');

	// Load the plugin that provides the "sass" task.
	grunt.loadNpmTasks('grunt-contrib-sass');

	grunt.loadNpmTasks('grunt-postcss');

	grunt.loadNpmTasks('grunt-contrib-uglify');
	
	grunt.loadNpmTasks('grunt-contrib-copy');
	
	
	
	// Default task(s).
	grunt.registerTask('default', ['copy', 'sass:dev', 'postcss', 'uglify']);

};
