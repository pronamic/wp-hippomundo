/* jshint node:true */
module.exports = function( grunt ) {
	require( 'load-grunt-tasks' )( grunt );

	// Project configuration.
	grunt.initConfig( {
		pkg: grunt.file.readJSON( 'package.json' ),

		// PHP Code Sniffer
		phpcs: {
			application: {
				src: [
					'**/*.php',
					'!bower_components/**',
					'!build/**',
					'!deploy/**',
					'!includes/xmlseclibs/**',
					'!node_modules/**',
					'!vendor/**',
					'!wp-content/**',
					'!src/kwpn.php'
				]
			},
			options: {
				bin: 'vendor/bin/phpcs',
				standard: 'phpcs.ruleset.xml',
				showSniffCodes: true
			}
		},

		// Check Text Domain
		checktextdomain: {
			options:{
				text_domain: 'hippomundo',
				keywords: [
					'__:1,2d',
					'_e:1,2d',
					'_x:1,2c,3d',
					'esc_html__:1,2d',
					'esc_html_e:1,2d',
					'esc_html_x:1,2c,3d',
					'esc_attr__:1,2d',
					'esc_attr_e:1,2d',
					'esc_attr_x:1,2c,3d',
					'_ex:1,2c,3d',
					'_n:1,2,4d',
					'_nx:1,2,4c,5d',
					'_n_noop:1,2,3d',
					'_nx_noop:1,2,3c,4d'
				]
			},
			files: {
				src:  [
					'**/*.php',
					'!deploy/**',
					'!node_modules/**',
					'!vendor/**'
				],
				expand: true
			}
		},

		// Make POT
		makepot: {
			target: {
				options: {
					domainPath: 'languages',
					type: 'wp-plugin',
					updatePoFiles: true,
					updateTimestamp: false,
					exclude: [
						'deploy/.*',
						'node_modules/.*',
						'vendor/.*'
					]
				}
			}
		},

		// Compass
		compass: {
			build: {
				options: {
					sassDir: 'src/sass',
					cssDir: 'src/css'
				}
			}
		},

		// CSS min
		cssmin: {
			style: {
				files: {
					'css/style.min.css': 'css/style.css',
				}
			}
		},

		// Clean
		clean: {
			deploy: {
				src: [ 'deploy/latest' ]
			}
		},

		// Copy
		copy: {
			style: {
				files: [
					{ // Theme CSS
						expand: true,
						cwd: 'src/css/',
						src: [ '**' ],
						dest: 'css'
					},
				]
			},

			deploy: {
				src: [
					'**',
					'!composer.json',
					'!composer.lock',
					'!Gruntfile.js',
					'!package.json',
					'!phpcs.ruleset.xml',
					'!README.md',
					'!deploy/**',
					'!node_modules/**',
					'!vendor/bin/**',
					'!vendor/squizlabs/**',
					'!vendor/wp-coding-standards/**'
				],
				dest: 'deploy/latest',
				expand: true
			}
		},

		// Compress
		compress: {
			deploy: {
				options: {
					archive: 'deploy/archives/<%= pkg.name %>.<%= pkg.version %>.zip'
				},
				expand: true,
				cwd: 'deploy/latest',
				src: ['**/*'],
				dest: '<%= pkg.name %>/'
			}
		},

		// S3
		aws_s3: {
			options: {
				region: 'eu-central-1'
			},
			deploy: {
				options: {
					bucket: 'downloads.pronamic.eu',
					differential: true
				},
				files: [
					{
						expand: true,
						cwd: 'deploy/archives/',
						src: '<%= pkg.name %>.<%= pkg.version %>.zip',
						dest: 'plugins/<%= pkg.name %>/'
					}
				]
			}
		}
	} );

	// Default task(s).
	grunt.registerTask( 'default', [ 'compass', 'copy:style', 'cssmin' ] );
	grunt.registerTask( 'pot', [ 'checktextdomain', 'makepot' ] );

	grunt.registerTask( 'deploy', [
		'default',
		'clean:deploy',
		'copy:deploy',
		'compress:deploy'
	] );

	grunt.registerTask( 's3-deploy', [
		'deploy',
		'aws_s3:deploy'
	] );
};
