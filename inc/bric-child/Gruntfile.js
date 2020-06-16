//GRUNTFILE for Bric Children
const sass = require( 'sass' );

module.exports = function (grunt) {

	
	// Project configuration.
	grunt.initConfig({
		pkg: grunt.file.readJSON('package.json'),
		sass: {
			options: {
				implementation: sass,
				sourceMap: true,
				outputStyle: 'compressed',
				includePaths: ['../bric/assets/src/css/bric/', '../bric/assets/src/css/photoswipe/', 'node_modules/bootstrap/scss/']
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
		},
		svgstore: {
		  	options: {
				prefix : '', // This will prefix each <g> ID
		  	},
		  	default: {
			  	files: {
				'assets/svgs/bric-child.svg': ['assets/src/svgs/*.svg', '../bric/assets/src/svgs/*.svg' ],
				}
			}
		}
		


	});

	// Load the plugin that provides the "sass" task.
	grunt.loadNpmTasks('grunt-sass');
	grunt.loadNpmTasks('grunt-postcss');
	grunt.loadNpmTasks('grunt-svgstore');

	
	// Default task(s).
	grunt.registerTask('default', ['sass:dist', 'postcss']);

};
