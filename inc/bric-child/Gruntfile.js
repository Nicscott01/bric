//GRUNTFILE for Bric Children

module.exports = function (grunt) {

	
	// Project configuration.
	grunt.initConfig({
		pkg: grunt.file.readJSON('package.json'),
		sass: {
			options: {
				sourceMap: true,
				outputStyle: 'compressed',
				includePaths: ['../bric/assets/src/css/bric/','node_modules/bootstrap/scss/']
			},
			dev: {
				files: {
					'assets/css/bric-style-customizer.css': 'assets/src/css/bric-style.scss'
				}
			},
			dist: {
				files: {
					'assets/css/bric-style.css': 'assets/src/css/bric-style.scss'
				}
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

	// Load the plugin that provides the "sass" task.
	grunt.loadNpmTasks('grunt-sass');
	grunt.loadNpmTasks('grunt-postcss');
	
	
	// Default task(s).
	grunt.registerTask('default', ['sass:dist', 'postcss']);

};
