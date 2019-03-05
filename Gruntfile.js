module.exports = function ( grunt ) {

	grunt.initConfig( {
		pkg: grunt.file.readJSON( 'package.json' ),
		wp_readme_to_markdown: {
			dist: {
				options: {
					screenshot_url: '<%= pkg.repository.url %>/raw/master/assets/{screenshot}.png',
					post_convert: function ( file ) {
						var project_icon = "<img src='" + grunt.config.get( 'pkg' ).repository.url + "/raw/master/assets/icon-128x128.png' align='right' />";
						var travis_badge = "[![build status](https://travis-ci.com/gagan0123/shortcode-widget.svg?branch=master)](https://travis-ci.com/gagan0123/shortcode-widget)"
						var pipeline_badge = "[![pipeline status](https://gitlab.com/gagan0123/shortcode-widget/badges/master/pipeline.svg)](https://gitlab.com/gagan0123/shortcode-widget/commits/master)";
						var coverage_badge = "[![coverage report](https://gitlab.com/gagan0123/shortcode-widget/badges/master/coverage.svg)](https://gitlab.com/gagan0123/shortcode-widget/commits/master)";
						var badges = pipeline_badge + ' ' + coverage_badge + "\n" + project_icon;
						file = file.replace(/^#\s[\w\s]*#/m,"$&\n"+badges );
						return file;
					}
				},
				files: {
					'README.md': 'readme.txt'
				}
			}
		},
		makepot: {
			target: {
				options: {
					domainPath: 'languages',
					exclude: [ 'node_modules/.*', 'tests/.*' ],
					mainFile: '<%= pkg.main %>',
					potFilename: '<%= pkg.name %>.pot',
					potHeaders: {
						poedit: false,
						'report-msgid-bugs-to': '<%= pkg.bugs.url %>'
					},
					type: 'wp-plugin',
					updateTimestamp: false
				}
			}
		}
	} );

	grunt.loadNpmTasks( 'grunt-wp-readme-to-markdown' );
	grunt.loadNpmTasks( 'grunt-wp-i18n' );

	grunt.registerTask( 'default', [
		'wp_readme_to_markdown', 'makepot'
	] );

};