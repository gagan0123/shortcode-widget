<?php
/*
Plugin Name: Shortcode Widget
Plugin URI: http://wordpress.org/extend/plugins/shortcode-widget/
Description: Adds a text-like widget that allows you to write shortcode in it. (Just whats missing in the default text widget)
Author: gagan0123
Author URI: http://gagan.pro/
Version: 1.0
Text Domain: shortcode-widget
License: GPL version 2 or later - http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
*/

define('SHORTCODE_WIDGET_TEXT_DOMAIN','shortcode_widget');

require_once('class-shortcode-widget.php');

function shortcode_widget_init(){
	register_widget('Shortcode_Widget');
}
add_action('widgets_init','shortcode_widget_init');

function shortcode_widget_load_text_domain(){
	load_plugin_textdomain( SHORTCODE_WIDGET_TEXT_DOMAIN, false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
}
add_action('plugins_loaded','shortcode_widget_load_text_domain');

add_shortcode('shortcode_widget_test', 'shortcode_widget_test_output');
function shortcode_widget_test_output($args){
	return "It works";
}
?>
