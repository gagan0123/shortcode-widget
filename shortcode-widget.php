<?php

/*
  Plugin Name: Shortcode Widget
  Plugin URI: http://wordpress.org/extend/plugins/shortcode-widget/
  Description: Adds a text-like widget that allows you to write shortcode in it. (Just whats missing in the default text widget)
  Author: Gagan Deep Singh
  Author URI: http://gagan.pro/
  Version: 1.4
  Text Domain: shortcode-widget
  License: GPL version 2 or later - http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */


/** If this file is called directly, abort. */
if ( !defined( 'ABSPATH' ) ) {
	die;
}

if ( !defined( 'SHORTCODE_WIDGET_PATH' ) ) {
	/**
	 * Absolute path of this plugin
	 * 
	 * @since 1.5
	 */
	define( 'SHORTCODE_WIDGET_PATH', trailingslashit( plugin_dir_path( __FILE__ ) ) );
}

/** Loading the core plugin class */
require_once SHORTCODE_WIDGET_PATH . 'includes/class-shortcode-widget-plugin.php';

