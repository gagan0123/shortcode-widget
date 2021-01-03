<?php
/**
 * PHPUnit bootstrap file
 *
 * @package Shortcode_Widget
 */

$shortcode_widget_tests_dir = getenv( 'WP_TESTS_DIR' );

if ( ! $shortcode_widget_tests_dir ) {
	$shortcode_widget_tests_dir = rtrim( sys_get_temp_dir(), '/\\' ) . '/wordpress-tests-lib';
}

if ( ! file_exists( $shortcode_widget_tests_dir . '/includes/functions.php' ) ) {
	// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	echo "Could not find $shortcode_widget_tests_dir/includes/functions.php, have you run bin/install-wp-tests.sh ?" . PHP_EOL;
	exit( 1 );
}

// Give access to tests_add_filter() function.
require_once $shortcode_widget_tests_dir . '/includes/functions.php';

/**
 * Manually load the plugin being tested.
 */
function shortcode_widget_manually_load_plugin() {
	require dirname( dirname( __FILE__ ) ) . '/shortcode-widget.php';
}
tests_add_filter( 'muplugins_loaded', 'shortcode_widget_manually_load_plugin' );

// Start up the WP testing environment.
require $shortcode_widget_tests_dir . '/includes/bootstrap.php';
